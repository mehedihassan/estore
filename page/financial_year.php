<?php
$db->financial_year('financial_year');
$ar = $db->getdata('select * from financial_year');
$title = 'অর্থ বছর';
?>


<div class="row">
    <div class="col-md-12">
        <div class="d-item-wrapper">
        	<div class="d-item-title">
        		<h3>অর্থ বছর সেট করা</h3>
        		<div class="d-item-tools">
        		    <button type="button" class="btn btn-info btn-sm" onclick="data_save()">নতুন অর্থ বছর সেট করুন </button>
        		</div>
        	</div>
        	<div class="d-item-content">
        		
        		<table class="table table-bordered table-striped text-center">
        		    <thead class="thead-custom">
        		        <tr>
        		            <th style="width: 180px;">#</th>
        		            <th>শুরু</th>
        		            <th>শেষ</th>
        		            <th>অর্থ বছর</th>
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
            		            <td>'.date('d M, Y', strtotime($v['start'])).'</td>
            		            <td>'.date('d M, Y', strtotime($v['end'])).'</td>
            		            <td>'.date('Y', strtotime($v['start'])).'-'.date('Y', strtotime($v['end'])).'</td>
            		            <td>
            		                <button class="btn btn-success btn-sm" onclick="data_save('.$v['id'].')"><i class="fa fa-edit"></i></button>
            		                <a href="'.$db->domain().'financial_year&del_id='.$v['id'].'" onclick="return confirm(\'তুমি কি নিশ্চিত?\')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
            		            </td>
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
        post_ajax('control.php',{get_financial_year:1,id:id},'#show_data')
        
    }
</script>
