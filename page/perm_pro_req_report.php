<?php 
$category_list = $db->getdata('
select a.category_id as cat_id,b.name as cat_name from product a 
inner join category b on b.id = a.category_id

where a.type = 1
group by a.category_id
');
$financial_list = $db->getdata('select * from financial_year');
$category_id = $finance = $cat_name = $product_id = '';


if (isset($_POST['search'])) {
    $category_id = $db->rpv('cat_id');
    $product_id = $db->rpv('product_id');
    $finance = $db->rpv('finance');
}

$sql = 'select a.*
,b.product_code
,b.name
,b.unit_type_id
,sum(a.app_qty) as total_app_qty
from invoice_details a

inner join product b on b.id = a.product_id
where b.type = 1 and a.type = "req"';
if($category_id != ''){
    $sql .= ' and b.category_id ='.$category_id;
}
if($product_id > 0){
    $sql .= ' and a.product_id ='.$product_id;
}

if($finance != ''){
    $sql .= ' and a.cdate between '.str_replace(['{','}'],['"','"'],$finance);
}


$sql .=' group by a.product_id

having total_app_qty > 0
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
				<h3>স্থায়ী সম্পদের আইটেমওয়ারী বিতরণ ও অবস্থান সংক্রান্ত বার্ষিক প্রতিবেদন অনুসন্ধান </h3>
			</div>
            <div class="d-item-content">
        	    <form action="" method="post" class="report-filter">
        		    <div class="row">
        		        <div class="col-md-3">
			                <div class="form-group">
    			                <label for="category">ক্যাটাগরি </label>
    			                <select name="cat_id" class="form-control select2" onchange="show_cat_pro('cat')">
    			                    <option value="">ক্যাটাগরি নির্বাচন করুন</option>
    			                    <?php
    			                    foreach($category_list as $v){
    			                        echo '<option ';
                                        if($category_id == $v['cat_id']){
                                            echo 'selected';
                                            $cat_name = $v['cat_name'];
                                        }
                                        echo ' value="'.$v['cat_id'].'">'.$v['cat_name'].'</option>';
    			                    }
    			                    
    			                    ?>
    			                </select>
    			            </div>
			            </div>
			            
			            <div class="col-md-3">
    			            <div class="form-group">
        		            <label>পণ্য নির্বাচন করুন</label>
    			            <select name="product_id" class="form-control select2 form-control-sm mr-2 product_id" style="width: 100%;">
    				            <option value="">সকল পণ্য </option>
    				            <?php 
    				            // $staff_name = '';
    				            $sql = 'select * from product';
				                foreach($db->getdata($sql) as $v) {
				                    echo '<option ';
				                    if($product_id == $v['id']){
				                        echo 'selected';
				                    }
				                    echo ' value="'.$v['id'].'">'.$v['name'].'</option>';
				                }
    				            ?>
    				        </select>
    			        </div>
    			        </div>
			            
			            
			            
			            <div class="col-md-3">
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
    
    
    <?php if ($financial_year != ''){?>
        	
	<div class="col-md-12">
		<div class="d-item-wrapper">
			<div class="d-item-title">
				<h3>স্থায়ী সম্পদের আইটেমওয়ারী বিতরণ ও অবস্থান সংক্রান্ত  বার্ষিক প্রতিবেদন</h3>
				<div class="d-item-tools" style="margin-top: -4px;">
				    <div class="d-flex">
				        <button type="button" class="btn btn-success btn-sm pb-0" onclick="window.print();">প্রিন্ট</button>
    				</div>
				</div>
			</div>
			<div class="d-item-content">
			    
			    
			    
			    <?php echo $db->head(); ?>
                <h3 style="text-align: center;font-size: 14px;padding-top: 7px;margin-top:0;margin-bottom:5px;font-weight: bold;text-decoration: underline;">
                    স্থায়ী সম্পদের আইটেমওয়ারী বিতরণ ও অবস্থান সংক্রান্ত বার্ষিক প্রতিবেদন (<?php echo $financial_year;?>)</h3>
                <!--<h3 style="text-align: center;font-size: 14px;padding-top: 7px;margin-top:0;margin-bottom:10px;"><?php echo $db->setdate($from) . ' - ' . $db->setdate($to); ?></h3>-->
			    <p style="margin: 8px;font-size: 17px;">সম্পদের ধরন: <?php echo $cat_name;?></p>
			    <div class="table-responsive">
					<table class="table table-striped table-bordered text-center align-middle mb-0">
						<thead class="thead-custom">
							<tr>
								<th>সম্পদের নাম</th>
								<th>বর্তমান অর্থ বছরে ক্রয়কৃত সংখ্যা</th>
								<th>বিতরণের কোড নম্বর</th>
								<th>বিতরণ/অবস্থান সংক্রান্ত তথ্য</th>
								<th style="width:50px;">রেজিস্ট্রারের পৃষ্ঠা নং </th>
								<th>মন্তন্য (যদি থাকে) </th>
							</tr>
						</thead>
						<tbody>
						    
						    <?php 
						        if ($data_list) :
						            $sl = 1;
						            foreach ($data_list as $v) :
						               $unit = $db->getonecol('name','unit_type','id',$v['unit_type_id']);
						               

        						    
            						   $sql = 'select a.*,b.name,b.section_id,b.room_id,b.designation_id from invoice_details a inner join staff b on b.id = a.staff_id
            						   where a.product_id = '.$v['product_id'].' and a.type = "req" and a.app_qty > 0 and a.status = 1';
    					               $req_details = $db->getdata($sql);
    					               $html = '';
    					               $coun = $v['total_app_qty'];
    					               
    					               $ar = [];
    					               if($req_details){
    					                   foreach($req_details as $row){
    					                       
    					                       
    					                       
    					                        
    					                        $jar = json_decode($row['deliver_all_code'],true);
    					                        foreach($jar as $dkey => $dval){
    					                            
    					                            $section = $room = $designatio = '';
    					                            if(isset($dval['staff'])){
    					                                foreach($db->getdata('select * from staff where id ='.$dval['staff']) as $dsaff){
    					                                    $section = $db->getonecol('name','section','id',$dsaff['section_id']);
    					                                    $room = $db->getonecol('room_no','room','id',$dsaff['room_id']);
    					                                    $designatio = $db->getonecol('name','designation','id',$dsaff['designation_id']);
    					                                }
    					                                
    					                            }
    					                            
    					                            
    					                            $staff_info = $designatio.' ('.$section.'), কক্ষ নং-'.$room;
    					                            $ar[] = ['financial_year'=>$v['financial_year'],'product_code'=>$v['product_code'],'staff'=>$staff_info,
                                                    'super_note'=>$row['super_note'],'register_number'=>$row['register_number'],'deliver_code'=>$dval['code']];
    					                        }
    					                        
    					                       // for ($i=1; $i <= $row['app_qty']; $i++) { 
    					                       //    $staff_info = $designatio.' ('.$section.'), কক্ষ নং-'.$room;
                                //                     $ar[] = ['financial_year'=>$v['financial_year'],'product_code'=>$v['product_code'],'staff'=>$staff_info,
                                //                     'super_note'=>$row['super_note'],'register_number'=>$row['register_number']];
                                //                 }
                                                
    					                       
    					                   }
    					                   
    					               }
    					               foreach($ar as $k=> $d){
    					                   $html .= '
    					                       <tr>
    					                            <td class="text-left">'.$d['deliver_code'].'</td>
    					                            <td class="text-left">'.$d['staff'].'</td>
    					                            <td class="text-center">'.$d['register_number'].'</td>
    					                            <td class="text-left">'.$d['super_note'].'</td>
    					                       </tr>';
    					               }
    					               
    					               
                                       $coun = $coun+1;
            						    echo '<tr>
                        		            <td rowspan="'.$coun.'" class="text-left">'.$db->en_bn($sl).'। '.$v['name'].'</td>
                        		            <td rowspan="'.$coun.'" class="text-center">'.$db->en_bn($v['total_app_qty']).' '.$unit.'</td>
            						    </tr>';
            						    echo $html;
        						   
        						    
        						    
        						    $sl++;
						            endforeach;
						        else :
						    ?>
						    <tr>
						        <td colspan="7">কোন আইটেম নেই</td>
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
	<?php }?>
	
	
	
	
	
	
</div>


<script>
    function show_cat_pro(type){
        product_type = $('.product_type').val()
        cat_id = $('.cat_id').val()
        product_id = $('.product_id').val()
        $.post("control.php",{get_cat_pro: product_type,cat_id:cat_id,product_id:product_id,rttype:type},function(data, status){
           json = JSON.parse(data)
           if(type == 'type'){
               $('.cat_id').html(json.cat)
           }
           
           $('.product_id').html(json.pro)
        });
    }
</script>