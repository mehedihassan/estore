<?php
$db->party('party','ক্রেতা');
$ar = $db->getdata('select * from party where type = "cus" order by name');
$title = 'ক্রেতা';
?>


<div class="row">
    <div class="col-md-12">
        <div class="d-item-wrapper">
        	<div class="d-item-title">
        		<h3> <?php echo $title;?> ব্যবস্থাপনা</h3>
        		<div class="d-item-tools">
        		    <button type="button" class="btn btn-info btn-sm" onclick="data_save()">নতুন <?php echo $title;?> যোগ করুন </button>
        		</div>
        	</div>
        	<div class="d-item-content">
        		
        		<table class="table table-bordered table-striped text-center">
        		    <thead class="thead-custom">
        		        <tr>
        		            <th style="width: 20px;">#</th>
        		            <th>তারিখ</th>
        		            <th><?php echo $title;?>র এর নাম</th>
        		            <th>নাম্বার</th>
        		            <th>ই-মেইল</th>
        		            <th>ঠিকানা</th>
        		            <th style="width: 80px;">অপসন</th>
        		        </tr>
        		    </thead>
        		    <tbody>
        		        <?php
        		        if($ar){
        		            $i = 1;
        		           foreach($ar as $v){
            		           echo '<tr>
            		            <td>'.$db->en_bn($i).'</td>
            		            <td>'.$db->setdate($v['date']).'</td>
            		            <td>'.$v['name'].'</td>
            		            <td>'.$v['number'].'</td>
            		            <td>'.$v['email'].'</td>
            		            <td>'.$v['address'].'</td>
            		            <td>';
            		            if (in_array('section_manage_edit', $permission)){
            		                echo '<button class="btn btn-success btn-sm" onclick="data_save('.$v['id'].')"><i class="fa fa-edit"></i></button>';
            		            }
            		            
            		            if (in_array('supplier_management_delete', $permission)){
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

<script>
    function data_save(id=''){
        $('#add_modal').modal('show')
        post_ajax('control.php',{get_party:1,party_type:'cus',id:id},'#show_data')
        
    }
</script>
