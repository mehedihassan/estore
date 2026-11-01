<?php
$db->transfer('transfer');

$sql = 'select * from transfer where id > 0';

if($db->is_super()){
    
}elseif($db->is_section()){
    $sql .= ' and (created_by ='.$db->is_section().' or current_section_id = '.$db->is_section().')';
}

$sql .= ' order by id desc';

$ar = $db->getdata($sql);
$title = 'শাখা পরিবর্তন';
?>


<div class="row">
    <div class="col-md-12">
        <div class="d-item-wrapper">
        	<div class="d-item-title">
        		<h3> <?php echo $title;?> ব্যবস্থাপনা</h3>
        		<div class="d-item-tools">
        		    <a href="transfer" class="btn btn-info btn-sm" ><?php echo $title;?> করুন </a>
        		</div>
        	</div>
        	<div class="d-item-content">
        		
        		<table class="table table-bordered table-striped text-center">
        		    <thead class="thead-custom">
        		        <tr>
        		            <th style="width: 18px;">ক্রমিক নং</th>
        		            <th style="width: 100px;">তারিখ</th>
        		            <th>কর্মচারীর নাম</th>
        		            <th>পূর্বের শাখা</th>
        		            <th>পূর্বের কক্ষ নং</th>
        		            <th>বর্তমান শাখা</th>
        		            <th>বর্তমান কক্ষ নং</th>
        		            <th>অবস্থা</th>
        		            <th style="width: 120px;">অপসন</th>
        		        </tr>
        		    </thead>
        		    <tbody>
        		        <?php
        		        if($ar){
        		            $i = 1;
        		           foreach($ar as $v){
            		           echo '<tr>
            		            <td>'.$db->en_bn($i).'</td>
            		            <td>'.$db->setdate($v['cdate']).'</td>
            		            <td>'.$db->getonecol('name', 'staff', 'id', $v['staff_id']).'</td>
            		            <td>'.$db->getonecol('name', 'section', 'id', $v['pre_section_id']).'</td>
            		            <td>'.$db->getonecol('room_no', 'room', 'id', $v['pre_room_id']).'</td>
            		            <td>'.$db->getonecol('name', 'section', 'id', $v['current_section_id']).'</td>
            		            <td>'.$db->getonecol('room_no', 'room', 'id', $v['current_room_id']).'</td>
            		            <td>';
            		            if($v['status'] == 1){
            		                echo '<span class="badge rounded-pill bg-success text-light px-2 py-1" style="padding: 2px 6px;border-radius: 6px;">অনুমোদিত</span>';
            		            }elseif($v['status'] == 0){
            		                echo '<span class="badge rounded-pill bg-danger text-light px-2 py-1" style="padding: 2px 6px;border-radius: 6px;">প্রক্রিয়াধীন</span>';
            		            }
            		            
            		            echo '</td>
            		            <td>';
            		           
            		            if ($v['current_section_id'] == $db->is_section() and $v['status'] == 0){
            		                echo '<a href="'.$db->domain().$url[0].'&approve='.$v['id'].'" onclick="return confirm(\'তুমি কি নিশ্চিত?\')" class="btn btn-success btn-sm">অনুমোদন</a>';
            		            }
            		            
            		            if (in_array('transfer_list_delete', $permission)){
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
        post_ajax('control.php',{get_room:1,id:id},'#show_data')
        
    }
</script>
