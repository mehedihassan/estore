<?php
$db->invoice('নিষ্পত্তি','pur');
$purchase_list = $db->getdata('select * from invoice where type = "sales" order by id DESC');


?>
<div class="row">
	<div class="col-md-12">
		<div class="d-item-wrapper">
			<div class="d-item-title">
				<h3>নিষ্পত্তি ব্যবস্থাপনা</h3>
				
			</div>
			<div class="d-item-content">
				


				<div class="table-responsive">
					<table class="table table-striped table-bordered text-center align-middle mb-0">
						<thead class="thead-custom">
							<tr>
								<th>#</th>
								<th>তারিখ</th>
								<th>নিষ্পত্তি আইডি</th>
								<th>ক্রেতার নাম</th>
								<th>মূল্য</th>
								<th>ছাড় </th>
								<th>চার্জ </th>
								<th style="width:200px;">অপসন</th>
							</tr>
						</thead>
						<tbody>
							<?php
        		        if($purchase_list){
        		            $i = 1;
        		           foreach($purchase_list as $v){
        		               $name = $db->getonecol('name','party','id',$v['party_id']);
        		            
            		           echo '<tr>
            		            <td>'.$db->en_bn($i).'</td>
            		            <td>'.$db->setdate($v['cdate']).'</td>
            		            <td>'.$v['cus_id'].'-'.$v['financial_year'].'</td>
            		            <td>'.$name.'</td>
            		            <td>'.$v['total_amount'].'</td>
            		            <td>'.$v['less'].'</td>
            		            <td>'.$v['charge'].'</td>
            		            <td>';
            		                echo '<button onclick="show_req_inv('.$v['id'].')" class="btn btn-success btn-sm" style="margin-right:5px"><i class="fa fa-eye"></i></button>';
            		                if (in_array('manage_purchase_edit', $permission)){
            		                    echo '<a href="'.$db->domain().'add_purchase&id='.$v['id'].'" style="margin-right:5px" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>';
            		                }
            		                if (in_array('manage_purchase_delete', $permission)){
            		                    echo '<a href="'.$db->domain().$url[0].'&del_id='.$v['id'].'" onclick="return confirm(\'তুমি কি নিশ্চিত?\')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>';
            		                }
            		            echo '</td>
            		        </tr>';
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
    function show_req_inv(id){
        $('#add_modal').modal('show')
        post_ajax('control.php',{pur_invoice_id:id},'#show_data')
        
    }
</script>




