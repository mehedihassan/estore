<?php 
$pro_list = $db->getdata('select * from product where type = 1 order by name
');
$financial_list = $db->getdata('select * from financial_year');
$category_id = $finance = $pro_name = '';

$product_id = '';
if(isset($_POST['pro_id'])){
    $product_id = $_POST['pro_id'];
}

$sql = '
select * from invoice_details where type in("pur","req") and product_id = '.$product_id.'



';

$data_list = $db->getdata($sql);
$financial_year = '';
?>
<style>
    td,th{
        background:#fff;
        font-size:12px !important;
    }
    @media print{@page {size: landscape}}
</style>
<div class="row">
    <div class="col-md-12">
        <div class="d-item-wrapper">
            <div class="d-item-title">
				<h3> অনুসন্ধান </h3>
			</div>
            <div class="d-item-content">
        	    <form action="" method="post" class="report-filter">
        		    <div class="row">
        		        <div class="col-md-3">
			                <div class="form-group">
    			                <label for="category">আইটেম </label>
    			                <select name="pro_id" class="form-control select2">
    			                    <option value="">আইটেম নির্বাচন করুন</option>
    			                    <?php
    			                    foreach($pro_list as $v){
    			                        echo '<option ';
                                        if($product_id == $v['id']){
                                            echo 'selected';
                                            $pro_name = $v['name'];
                                        }
                                        echo ' value="'.$v['id'].'">'.$v['name'].'</option>';
    			                    }
    			                    
    			                    ?>
    			                </select>
    			            </div>
			            </div>
			            <div class="col-md-3" style="display:none">
			                <div class="form-group">
    			                <label for="category">অর্থ বছর নির্বাচন করুন </label>
    			                <select name="finance" class="form-control select2">
    			                    <option value="">অর্থ বছর নির্বাচন করুন</option>
    			                    <?php
    			                    foreach($financial_list as $v){
    			                        $value = '{'.$v['start'].'} and {'.$v['end'].'}';
    			                        $d = date('Y', strtotime($v['start'])).'-'.date('Y', strtotime($v['end']));
    			                        echo '<option ';
                                        if($finance == $value){
                                            echo 'selected';
                                            $financial_year = $d;
                                        }
                                        echo ' value="'.$value.'">'.$d.'</option>';
    			                    }
    			                    
    			                    ?>
    			                </select>
    			            </div>
			            </div>
        		        <div class="col-md-1">
        		            <button type="submit" name="search" class="btn btn-primary btn-sm mr-2" style="margin-top:27px">অনুসন্ধান</button>
        		        </div>
        		    </div>
        		</form>
        	</div>
        </div>    	
    </div>    	
    
    

	<div class="col-md-12">
		<div class="d-item-wrapper">
			<div class="d-item-title">
				<h3>স্থায়ী সম্পদের মজুদ ও বিতরণ রেজিস্টার</h3>
				<div class="d-item-tools" style="margin-top: -4px;">
				    <div class="d-flex">
				        <button type="button" class="btn btn-success btn-sm pb-0" onclick="window.print();">প্রিন্ট</button>
    				</div>
				</div>
			</div>
			<div class="d-item-content">
			    
			    
			    
			    <?php echo $db->head(); ?>
                <h3 style="text-align: center;font-size: 14px;padding-top: 7px;margin-top:0;margin-bottom:5px;font-weight: bold;text-decoration: underline;">
                    স্থায়ী সম্পদের মজুদ ও বিতরণ রেজিস্টার</h3>
			    <p style="margin: 8px;font-size: 17px;">আইটেমের নাম: <?php echo $pro_name;?></p>
			    <div class="table-responsive">
					
					<table class="table table-striped table-bordered text-center align-middle mb-0">
					    <thead>
					        <tr>
					            <th rowspan="3">তারিখ</th>
					            <th colspan="6">মজুদ অংশ</th>
					            <th colspan="7">বিতরণ অংশ</th>
					            <th rowspan="3">পরিদর্শনকারী কর্মকর্তার স্বাক্ষর ও সিল</th>
					        </tr>
					        <tr>
					            <th rowspan="2">পূর্বের জমা</th>
					            <th colspan="2">নতুন জমা সংক্রান্ত তথ্য</th>
					            <th rowspan="2">মোট জমা</th>
					            <th rowspan="2">মন্তব্য</th>
					            <th rowspan="2">স্টোর ইনচার্জের স্বাক্ষর</th>
					            <th colspan="2">চাহিদা সংক্রান্ত তথ্য</th>
					            <th rowspan="2">পরিমান</th>
					            <th rowspan="2">অবশিষ্ট</th>
					            <th rowspan="2">স্টোর ইনচার্জের স্বাক্ষর</th>
					            <th rowspan="2">গ্রহণকারীর স্বাক্ষর</th>
					            <th rowspan="2">মন্তব্য</th>
					        </tr>
					        <tr>
					            <th>সংখ্যা</th>
					            <th>সরবরাহকারী প্রতিষ্ঠানের নাম ও চালান/মেমো নম্বর এবং তারিখ</th>
					            <th>পরিচয়</th>
					            <th>নং ও তারিখ</th>
					        </tr>
					    </thead>
					    <tbody>
					      
					        
					        
					        
					        
					        <?php 
					        
					        $balance = 0;
					        foreach($db->getdata('select * from invoice_details where type = "opening_stock" and product_id ='.$product_id) as $v){
					            $balance += $v['qty'];
					            echo '<tr>
        					            <td></td>
        					            <td>'.$v['qty'].'</td>
        					            <td></td>
        					            
        					            <td></td>
        					            <td></td>
        					            <td></td>
        					            <td></td>
        					            <td></td>
        					            <td></td>
        					            <td></td>
        					            <td>'.$balance.'</td>
        					            
        					            <td></td>
        					            <td></td>
        					            <td></td>
        					            <td></td>
        					        </tr>';
					        }
					        
					        
					        
						        if ($data_list) :
						            $sl = 1;
						            foreach($data_list as $v){
						                $pur_qty = $req_qty = '';
						                $supplier = $employee = $pdate = $rdate = $note = '';
						                if($v['type'] == 'pur'){
						                    $pur_qty = $v['qty'];
						                    $balance += $pur_qty;
						                    $supplier = $v['party_name'];
						                    $pdate = $db->setdate($v['cdate']);
						                    $note = $v['note'];
						                }elseif($v['type'] == 'req'){
						                    $req_qty = $v['app_qty'];
						                    $balance -= $req_qty;
						                    $employee = $db->getonecol('name','staff','id',$v['staff_id']);
						                    $rdate = $db->setdate($v['cdate']);
						                    $note = $v['note'];
						                }
						                
						                if($v['type'] == 'pur' or ($v['type'] == 'req' and $v['app_qty'] > 0)){
						                
						                echo '<tr>
                					            <td>'.$pdate.'</td>
                					            <td></td>
                					            <td>'.$pur_qty.'</td>
                					            
                					            <td>'.$supplier.'</td>
                					            <td>'.$pur_qty.'</td>
                					            <td></td>
                					            <td></td>
                					            <td>'.$employee.'</td>
                					            <td>'.$rdate.'</td>
                					            <td>'.$req_qty.'</td>
                					            <td>'.$balance.'</td>
                					            <td></td>
                					            <td></td>
                					            <td>'.$note.'</td>
                					            <td></td>
                					        </tr>';
						                }
						                
						            }
						            
						            
						            
						            
						            
						        else :
						    ?>
						    <tr>
						        <td colspan="20">কোন আইটেম নেই</td>
						    </tr>
						    <?php 
						        endif;
						    ?>
						    
						    
						    
					    </tbody>
					</table>
					
					
				</div>
				
							
			</div>
		</div>
	</div>

	
	
	
	
	
	
</div>