<?php
$db->product('product');

$sql = 'select * from product where id > 0';
$brand_id = $cat_id = '';


if($db->getm('brand_id')){
    $brand_id = $db->getm('brand_id');
}
if($db->getm('cat_id')){
    $cat_id = $db->getm('cat_id');
}



if($brand_id != ''){
    $sql .= ' and brand_id ='.$brand_id;
}
if($cat_id != ''){
    $sql .= ' and category_id ='.$cat_id;
}
if($type != ''){
    $sql .= ' and type ='.$type;
}

$sql .= '';

$product_list = $db->getdata($sql);


?>
<div class="row">
    
    
    <div class="col-md-12">
        <div class="d-item-wrapper">
            <div class="d-item-title">
				<h3><?php echo $title;?>  অনুসন্ধান </h3>
			</div>
            <div class="d-item-content">
        	    <form action="" method="get" class="report-filter">
        		    <div class="row">
        		        
        		        <div class="col-md-3">
    			            <select name="cat_id" class="form-control select2 form-control-sm mr-2" style="width: 100%;">
    				            <option value="">ক্যাটাগরি নির্বাচন করুন</option>
    				            <?php 
    				            $staff_name = '';
    				            $sql = 'select * from category where id > 0';
    				            if($type != ''){
                                    $sql .= ' and type = "'.str_replace([1,2],['perm','temp'],$type).'"';
                                }
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
    			        
    			      
    			        
    			      
			            
        		        <div class="col-md-1">
        		            <button type="submit" class="btn btn-primary btn-sm mr-2">অনুসন্ধান</button>
        		        </div>
        		    </div>
        		</form>
        	</div>
        </div>    	
    </div> 
    
    
    
    
    
    
    
    
    
    
    
	<div class="col-md-12">
		<div class="d-item-wrapper">
			<div class="d-item-title">
				<h3><?php echo $title2;?> তালিকা</h3>
				<?php if (in_array($page.'_add', $permission)){?>
				<div class="d-item-tools">
				    <a href="#" class="btn btn-info btn-sm" onclick="data_save()"><?php echo $title;?>  যোগ করুন</a>
				</div>
				<?php }?>
			</div>
			<div class="d-item-content">
				


				<div class="table-responsive">
					<table class="table table-striped table-bordered text-center align-middle mb-0" id="center_table">
						<thead class="thead-custom">
							<tr>
								<th>আইডি</th>
								<th>ক্যাটাগরি</th>
								<th>পণ্যের নাম</th>
								<th>পণ্যের কোড</th>
								<th>ইউনিট </th>
								<?php if (in_array($page.'_edit', $permission) or in_array($page.'_delete', $permission)){?>
								<th width="50">অপসন</th>
                                <?php }?>
							</tr>
						</thead>
						<tbody>
							<?php
        		        if($product_list){
        		            $i = 1;
        		            
        		           $ar = []; 
        		            
        		           foreach($product_list as $v){
        		               $cat = $db->getonecol('name','category','id',$v['category_id']);
        		               $unit = $db->getonecol('name','unit_type','id',$v['unit_type_id']);
        		               $ltype = '';
        		               if(isset($db->product_type()[$v['type']])){
        		                   $ltype = $db->product_type()[$v['type']];
        		               }
        		               
        		               $ar[] = [
        		                   'category_id'=>$v['category_id'],
        		                   'id'=>$v['id'],
        		                   'cat'=>$cat,
        		                   'name'=>$v['name'],
        		                   'code'=>$v['product_code'],
        		                   'unit'=>$unit,
        		                   'date'=>$db->setdate($v['cdate']),
        		                   'type'=>$ltype
        		              ];
            		           
            		       }
            		       
            		       if (!empty($ar)) {
                            usort($ar, function($a, $b) {
                                return $a['category_id'] - $b['category_id'];
                            });
                           }
            		       foreach($ar as $v){
            		           $action = '';
            		            if (in_array($page.'_edit', $permission)){
            		                $action .= '<a href="#." class="btn btn-success btn-sm" onclick="data_save('.$v['id'].')"><i class="fa fa-edit"></i></a>';
            		            }
            		            
            		            if (in_array($page.'_delete', $permission)){
            		                $action .= '<a href="'.$db->domain().$url[0].'&del_id='.$v['id'].'" onclick="return confirm(\'তুমি কি নিশ্চিত?\')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>';
            		            }
            		           
            		           
            		           echo '<tr>
            		            <td>'.$v['id'].'</td>
            		            
            		            <td>'.$v['cat'].'</td>
            		            <td>'.$v['name'].'</td>
            		            <td>'.$v['code'].'</td>
            		            <td>'.$v['unit'].'</td>';
            		            if($action != ''){
            		                echo '<td>'.$action.'</td>';
            		            }
            		            
            		        echo '</tr>';
            		        $i++;
            		       }
        		        }
        		        
        		        
        		        ?>
						</tbody>
					</table>
				</div>



			</div>
		</div>
	</div>
</div>


<script>
    function data_save(id=''){
        $('#add_modal').modal('show')
        post_ajax('control.php',{get_product:1,id:id,rtype:'<?php echo $type;?>'},'#show_data')
        
    }
</script>


