<?php
$db->staff('staff');
$desig_id = $section_id = '';
$sql = 'select a.*,b.name as desig_name from staff a

left join designation b on b.id = a.designation_id

where a.id > 0';
if($db->getm('designation_id')){
    $desig_id = $db->getm('designation_id');
    $sql .= ' and a.designation_id ='.$db->getm('designation_id');
}
if($db->getm('section_id')){
    $section_id = $db->getm('section_id');
    $sql .= ' and a.section_id ='.$section_id;
}


if($db->is_section() and !$db->is_super()){
    $sql .= ' and a.section_id ='.$db->is_section();
}

$sql .= ' order by cast(a.id_no as unsigned) asc';
$ar = $db->getdata($sql);
$title = 'কর্মচারী';
?>


<div class="row">
    
    
    <div class="col-md-12">
        <div class="d-item-wrapper">
            <div class="d-item-title">
				<h3>কর্মচারী অনুসন্ধান </h3>
			</div>
            <div class="d-item-content">
        	    <form action="" method="get" class="report-filter">
        		    <div class="row">
        		        
        		        <div class="col-md-3">
    			            <select name="designation_id" class="form-control select2 form-control-sm mr-2" style="width: 100%;">
    				            <option value="">পদবী নির্বাচন করুন</option>
    				            <?php 
    				            $staff_name = '';
    				            $sql = 'select * from designation where id > 0';
    				            if($db->is_section()){
    				                $sql .= ' and id in(select designation_id from staff where section_id = '.$db->is_section().')';
    				            }
    				            
    				            $sql .= ' order by name';
				                foreach($db->getdata($sql) as $v) {
				                    echo '<option ';
				                    if($desig_id == $v['id']){
				                        echo 'selected';
				                    }
				                    echo ' value="'.$v['id'].'">'.$v['name'].'</option>';
				                }
    				            ?>
    				        </select>
    			        </div>
    			        
    			        <div class="col-md-3">
    			            <select name="section_id" class="form-control select2 form-control-sm mr-2" style="width: 100%;">
    				            <option value="">শাখা নির্বাচন করুন</option>
    				            <?php 
    				            $staff_name = '';
    				            $sql = 'select * from section where id > 0';
    				            if($db->is_section() and !$db->is_super()){
    				                $section_id = $db->is_section();
                                    $sql .= ' and id ='.$db->is_section();
                                }
    				            $sql .= '';
				                foreach($db->getdata($sql) as $v) {
				                    echo '<option ';
				                    if($section_id == $v['id']){
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
        		<h3> <?php echo $title;?> ম্যানেজমেন্ট</h3>
        		<div class="d-item-tools">
        		    <button type="button" class="btn btn-info btn-sm" onclick="data_save()">নতুন <?php echo $title;?> যোগ করুন </button>
        		</div>
        	</div>
        	<div class="d-item-content">
        		
        		<table class="table table-bordered table-striped text-center" id="manage_staff">
        		    <thead class="thead-custom">
        		        <tr>
        		            <th style="width: 20px;">আইডি</th>
        		            <th> নাম</th>
        		            <th>পদবী</th>
        		            <th>শাখা</th>
        		            <th>কক্ষ নম্বর</th>
        		            <!--<th>ছবি</th>-->
        		            <th>মোবাইল নাম্বার</th>
        		            <th>ই-মেইল</th>
        		            <!--<th>টাইপ </th>-->
        		            <th>Status </th>
        		            
        		            <th style="width: 50px;">অপসন</th>
        		        </tr>
        		    </thead>
        		    <tbody>
        		        <?php
        		        if($ar){
        		            $i = 1;
        		           foreach($ar as $v){
        		               $desig = $v['desig_name'];
        		               $section = $db->getonecol('name','section','id',$v['section_id']);
        		               $type = $db->getonecol('name','type','id',$v['type_id']);
        		               $room_no = $db->getonecol('room_no','room','id',$v['room_id']);
            		           echo '<tr ';
            		          // if($v['section_manager'] == 1){
            		          //     echo ' style="background:#00ff2b40;"';
            		          // }
            		           
            		           echo '>
            		            <td>'.$v['id_no'].'</td>
            		            <td>'.$v['name'].'</td>
            		            <td>'.$desig.'</td>
            		            <td>'.$section.'</td>
            		            <td>'.$room_no.'</td>
            		            <!--<td>';
            		            if($v['img'] != '' and file_exists('image/staff/'.$v['img'])){
            		                echo '<img style="width:50px;max-height:50px;" src="image/staff/'.$v['img'].'">';
            		            }
            		            echo '</td>-->
            		            <td>'.$v['number'].'</td>
            		            <td>'.$v['email'].'</td>
            		            <!--<td>'.$type.'</td>-->
            		            <td>';
            		            
            		            if($v['status'] == 1){
            		                echo '<span class="badge badge-success">Active</span>';
            		            }else{
            		                echo '<span class="badge badge-danger">Inactive</span>';
            		            }
            		            echo '</td>
            		            
            		            <td>';
            		            if (in_array('manage_staff_edit', $permission)){
            		                echo '<button class="btn btn-success btn-sm" onclick="data_save('.$v['id'].')"><i class="fa fa-edit"></i></button>';
            		            }
            		            
            		            if (in_array('manage_staff_delete', $permission)){
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
        post_ajax('control.php',{get_staff:1,id:id},'#show_data')
        
    }
$(document).ready(function(){
     var dataTable = $('#manage_staff').DataTable({
      "order": [],
      dom: 'lBfrtip',
      "lengthMenu": [[10,20,50, 75, 100, -1], [10,20,50, 75, 100, "All"]],
      buttons: ['excel', 'pdf', 'print'],
      "columnDefs": [
        { "orderable": false, "targets": 0 },
      ]
     });
 });
</script>
