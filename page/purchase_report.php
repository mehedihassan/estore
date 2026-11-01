<?php 

$from = date('Y-m-01');
$to = date('Y-m-d');
$type = $cat_id = $product_id = '';
$method = '';
if (isset($_POST['search'])) {
    $from = $_POST['from'];
    $to = $_POST['to'];
    $type = $db->rpv('type');
    $cat_id = $db->rpv('cat_id');
    $product_id = $db->rpv('product_id');
    $method = $db->rpv('method');
}

$sql = 'select * from invoice_details where cdate between "'.$from.'" and "'.$to.'" and type = "pur"';


if($product_id > 0){
    $sql .= ' and product_id ='.$product_id;
}
if($method != ''){
    $sql .= ' and method = "'.$method.'"';
}


if($type != ''){
    $sql .= ' and product_id in(select id from product where type = '.str_replace(['temp','perm'],['2','1'],$type).')';
}

if($cat_id > 0){
    $sql .= ' and product_id in(select id from product where category_id ='.$cat_id.')';
}

$sql .= ' order by id DESC';



//echo $sql;


$query = $db->getdata($sql);

?>

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
        		            <label>সম্পদ</label>
        		           
    			            <select name="type" class="form-control form-control-sm mr-2 product_type" onchange="show_cat_pro('type')">
    				            <option value="">সকল </option>
    				            <option value="perm" <?php if($type == 'perm'){echo 'selected';}?>>স্থায়ী সম্পদ</option>
    				            <option value="temp" <?php if($type == 'temp'){echo 'selected';}?>>অস্থায়ী পণ্য</option>
    				           
    				        </select>
    			        </div>
    			        </div>
    			        
    			        <div class="col-md-3">
    			            <div class="form-group">
        		            <label>ধরন</label>
    			            <select name="cat_id" class="form-control select2 form-control-sm mr-2 cat_id" style="width: 100%;" onchange="show_cat_pro('cat')">
    				            <option value="">ধরন নির্বাচন করুন</option>
    				            <?php 
    				            // $staff_name = '';
    				            $sql = 'select * from category';
				                foreach($db->getdata($sql) as $v) {
				                    echo '<option ';
				                    if($cat_id == $v['id']){
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
        		            <label>পণ্য</label>
    			            <select name="product_id" class="form-control select2 form-control-sm mr-2 product_id" style="width: 100%;">
    				            <option value="">পণ্য নির্বাচন করুন</option>
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
        		            <label>ক্রয় প্রক্রিয়া</label>
        		           
    			            <select name="method" class="form-control form-control-sm mr-2 product_type" >
    				            <option value="">সকল </option>
    				            <?php
                                  foreach($db->method() as $v){
                                      echo '<option value="'.$v.'">'.$v.' </option>';
                                  }
                                  ?>
    				           
    				        </select>
    			        </div>
    			        </div>
        		        <div class="col-md-3">
        		            
			                <div class="form-group">
			                    <label>শুরু</label>
    			                <input type="date" name="from" class="form-control form-control-sm mr-2" value="<?php echo $from;?>" />
    			            </div>
			            </div>
			            
			            <div class="col-md-3">
			                <div class="form-group">
			                    <label>শেষ</label>
    			                <input type="date" name="to" class="form-control form-control-sm mr-2" value="<?php echo $to;?>" />
    			            </div>
			            </div>
			            
        		        <div class="col-md-1">
        		            <button type="submit" name="search" style="margin-top:28px" class="btn btn-primary btn-sm mr-2">অনুসন্ধান</button>
        		        </div>
        		    </div>
        		</form>
        	</div>
        </div>    	
    </div> 
    
    
	<div class="col-md-12">
		<div class="d-item-wrapper">
			<div class="d-item-title no-print">
				<h3>ক্রয়  রিপোর্ট</h3>
				<div class="d-item-tools">
				    <button type="button" class="btn btn-success btn-sm pb-0" onclick="window.print();">প্রিন্ট</button>
				</div>
			</div>
			<div class="d-item-content">
			    

			    <?php echo $db->head(); ?>
                <h3 style="font-weight: bold;text-align: center;font-size: 18px;padding-top: 7px;margin-top:0;margin-bottom:5px;">ক্রয় রিপোর্ট</h3>
                <h3 style="text-align: center;font-size: 14px;padding-top: 7px;margin-top:0;margin-bottom:10px;"><?php echo $db->setdate($from) . ' - ' . $db->setdate($to); ?></h3>
                <h3 style="text-align: center;font-size: 14px;padding-top: 0;margin-top:0;margin-bottom:10px;font-weight: bold;"><?php echo $staff_name; ?></h3>
			    
			    <div class="table-responsive">
					<table class="table table-striped table-bordered text-center align-middle mb-0">
						<thead class="thead-custom">
							<tr>
								<th>#</th>
								<th>তারিখ</th>
                                <th>ক্রয় আইডি</th>

								<th>ধরন</th>
								<th>দ্রব্যের নাম</th>
								<th>ক্রয় প্রক্রিয়া</th>
								
								<th>পরিমান</th>
								<th>মূল্য</th>
								<th>মোট </th>
							</tr>
						</thead>
						<tbody>
						    <?php 
						        if ($query) :
						            $i = 0;
						            $tqty = $tamount = 0;
						            foreach ($query as $p_item) :
						                $i++;
						                $tAmount += $p_item['total_amount'];
						                $tDiscount += $p_item['discount'];
						                $tCharge += $p_item['charge'];
						                
						            $pro_dtls = "select type,name,product_code,unit_type_id from product where id = ".$p_item['product_id'];
						            $pro_info = $db->getdata($pro_dtls)[0];
						            $unit_type_id = $pro_info['unit_type_id'];
						            $unit_type = $db->getonecol('name','unit_type','id',$unit_type_id);
						            
						            $tqty += $p_item['qty'];
						            $tamount += $p_item['amount'];
						    ?>
						    <tr>
						        <td><?php echo $db->en_bn($i); ?></td>
						        <td><?php echo $db->setdate($p_item['cdate']); ?></td>
            		            <td><?php echo $db->get_req_id($p_item['cus_id'],$p_item['financial_year']); ?></td>
            		            
            		            <td><?php 
            		             if($pro_info['type'] == 1){
            		                echo '<span class="badge rounded-pill bg-success text-light px-2 py-1" style="padding: 2px 6px;border-radius: 6px;">স্থায়ী</span>';
            		            }elseif($pro_info['type'] == 2){
            		                echo '<span class="badge rounded-pill bg-primary text-light px-2 py-1" style="padding: 2px 6px;border-radius: 6px;">অস্থায়ী</span>';
            		            }
            		           
            		            ?></td>
            		            <td><?php echo $pro_info['name']; ?></td>
            		            <td><?php echo $p_item['method']; ?></td>
            		            
            		            <td><?php echo $db->en_bn($p_item['qty']).' '.$unit_type; ?> </td>
            		            <td><?php echo $db->en_bn($p_item['rate']); ?> </td>
            		            <td><?php echo $db->en_bn($p_item['amount']); ?> </td>
						    </tr>
						    <?php 
						            endforeach;
						        else :
						    ?>
						    <tr>
						        <td colspan="8">কোন আইটেম নেই</td>
						    </tr>
						    <?php 
						        endif;
						    ?>
						</tbody>
						<tfoot>
						    <tr>
						        <th colspan="6" class="text-left">মোট:</th>
						        <th><?php echo $db->en_bn($tqty); ?></th>
						        <th></th>
						        <th><?php echo $db->en_bn($tamount); ?></th>
						    </tr>
						</tfoot>
					</table>
				</div>
				
							
			</div>
		</div>
	</div>
</div>
<script>
    function show_staff(v){
        $.post("control.php",{log_section_id: v},function(data, status){
            $('.staff_id').html(data)
        });
    }
    
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