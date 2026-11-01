<?php
$db->section('section');
$ar = $db->getdata('select * from section');
$title = 'শাখা';
?>


<div class="row">
    <div class="col-md-12">
        <div class="d-item-wrapper">
        	<div class="d-item-title">
        		<h3> <?php echo $title;?> ম্যানেজমেন্ট</h3>
        		<div class="d-item-tools">
        		    <button type="button" class="btn btn-info btn-sm" onclick="data_save()">নতুন <?php echo $title;?> যোগ করুন </button>
        		</div>
        	</div>
        	<div class="d-item-content">
        		
        		<table class="table table-bordered table-striped text-center" id="center_table">
        		    <thead class="thead-custom">
        		        <tr>
        		            <th style="width: 60px;">#</th>
        		            <th><?php echo $title;?>র নাম</th>
        		            <th>কক্ষ নম্বর</th>
                            <th>পদবি</th>
        		            <th style="width: 180px;">অপসন</th>
        		        </tr>
        		    </thead>
        		    <tbody>
        		        <?php
        		        if($ar){
        		            $i = 1;
        		           foreach($ar as $v){
            		           echo '<tr>
            		            <td>'.$db->en_bn($i).'</td>
            		            <td>'.$v['name'].'</td>
            		            <td>';
                                $rom = '';
                                foreach ($db->getdata('select * from room where section_id ='.$v['id']) as $key => $k) {
                                    $rom .= ', '.$k['room_no'];
                                }
                                echo trim($rom,',');

                                echo '</td>
                                <td>';
                                $desig = '';
                                foreach ($db->getdata('select * from designation where id in('.$v['designation'].')') as $key => $row) {
                                    $desig .= '/ '.$row['name'];
                                }
                                echo trim($desig,'/');

                                echo '</td>
            		            <td>';
            		            
            		            if (in_array('section_manage_edit', $permission)){
            		                echo '<button class="btn btn-success btn-sm" onclick="data_save('.$v['id'].')"><i class="fa fa-edit"></i></button>';
            		            }
            		            
            		            if (in_array('section_manage_delete', $permission)){
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
        post_ajax('control.php',{get_section:1,id:id},'#show_data')
        
    }
</script>
