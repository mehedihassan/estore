<?php
$db->invoice('চাহিদা','req');
$sql = 'select * from invoice where type = "req"';

$from = date('Y-m-01');
$to = date('Y-m-d');
$staff_id = $section_id = $status = $req_type = '';
$colmd = 3;
if($db->is_super()){
    $status = 2;
}elseif($db->is_section() and !$db->is_stock()){
    $status = 0;
}elseif($db->is_stock()){
    $status = 3;
}


if($db->getm('from')){
    $from = $db->getm('from');
    $to = $db->getm('to');
    if($db->getm('status') or $db->getm('status') == 0){
        $status = $db->getm('status');
    }
}
if($db->getm('staff')){
    $staff_id = $db->getm('staff');
}
if($db->getm('section_id')){
    $section_id = $db->getm('section_id');
}
if($db->getm('type')){
    $req_type = $db->getm('type');
}







if(strlen($status) > 0){
    $sql .= ' and status ='.$status;
}
if($staff_id != ''){
    $sql .= ' and staff_id ='.$staff_id;
}

if($section_id != ''){
    $sql .= ' and section_id ='.$section_id;
}

if($req_type != ''){
    $sql .= ' and pro_type = "'.$req_type.'"';
}


if($db->getm('received_status')){
    $sql .= ' and received_status = 0';
    $sql .= ' and staff_id ='.$db->uid();
}

if($db->getm('no_received_status')){
    $sql .= ' and received_status = 0';
}

if($db->is_super()){
    $sql .= ' and status != 0';
}elseif($db->is_section() and !$db->is_stock()){
    $sql .= ' and section_id ='.$db->is_section();
}

if($db->is_stock()){
    if($db->getm('from')){
       // $sql .= ' and status in(3,1,0)';
    }else{
        $sql .= ' and status in(3)';
    }
    
    if($status == 1 or $status == 3){}else{
        $sql .= ' and staff_id ='.$db->uid();
    }
}

if($db->is_staff()){
    $sql .= ' and staff_id ='.$db->uid();
    $colmd = 2;
}











$sql .= ' and cdate between "'.$from.'" and "'.$to.'"';


$sql .= ' order by id DESC';
// echo $sql;
$requisition_list = $db->getdata($sql);

?>
<div class="row">
    
    <div class="col-md-12">
        <div class="d-item-wrapper">
            <div class="d-item-title">
				<h3>চাহিদা অনুসন্ধান </h3>
			</div>
            <div class="d-item-content">
        	    <form action="" method="get" class="report-filter">
        	        <?php
                        if($db->getm('received_status')){
                            echo '<input type="hidden" name="received_status" value="1">';
                        }
                        
                        if($db->getm('no_received_status')){
                            echo '<input type="hidden" name="no_received_status" value="1">';
                        }
                        ?>
        		    <div class="row">
        		        <?php
        		        $ok = 1;
        		        $sql = 'select id, id_no, name from staff where status = 1';
			            if($db->is_section() and !$db->is_super()){
                            $sql .= ' and section_id ='.$db->is_section();
                        }elseif(!$db->is_super() and !$db->is_stock()){
                            $ok = 0;
                        }
                        
                        if($ok == 1){
                        ?>
        		        <div class="col-md-3">
        		            <div class="form-group">
        		            <label>শাখা নির্বাচন</label>
    			            <select name="section_id" class="form-control select2 form-control-sm mr-2" style="width: 100%;" onchange="show_staff(this.value)">
    				            <option value="">শাখা নির্বাচন করুন</option>
    				            <?php 
				                foreach($db->getdata('select * from section') as $v) {
				                    echo '<option ';
				                    if($section_id == $v['id']){
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
        		            <label>কর্মচারী নির্বাচন</label>
    			            <select name="staff" class="form-control select2 form-control-sm mr-2 staff_id" style="width: 100%;">
    				            <option value="">কর্মচারী নির্বাচন করুন</option>
    				            <?php 
    				            $staff_name = '';
    				            
    				            
				                foreach($db->getdata($sql) as $staff) {
				                    echo '<option ';
				                    if($staff_id == $staff['id']){
				                        echo 'selected';
				                        $staff_name = $staff['name'];
				                    }
				                    echo ' value="'.$staff['id'].'">'.$staff['id_no'].' - '.$staff['name'].'</option>';
				                }
    				            ?>
    				        </select>
    			        </div>
    			        </div>
    			        <?php }?>
    			        
    			        <div class="col-md-<?php echo $colmd;?>">
    			            <div class="form-group">
        		            <label>চাহিদার অবস্থা</label>
        		            <?php
        		            $st_ar = [
        		                0=>'প্রক্রিয়াধীন',
        		                2=>'অনুমোদনের  অপেক্ষায়',
        		                3=>'অনুমোদিত',
        		                1=>'বিতরনকৃত',
        		                5=>'বাতিল',
        		            
        		            ];
        		            if($db->is_super()){
        		                unset($st_ar[0]);
        		            }
        		            
        		            
        		            ?>
    			            <select name="status" class="form-control form-control-sm mr-2" style="width: 100%;">
    				            <option value="">অবস্থা</option>
    				           <?php
    				           
    				           foreach($st_ar as $key=> $v){
    				              echo '<option ';
				                    if($status == $key and $status != ''){
				                        echo 'selected';
				                    }
				                    echo ' value="'.$key.'">'.$v.'</option>'; 
    				           }
    				            ?>
    				        </select>
    			        </div>
    			        </div>
    			        
    			        <div class="col-md-<?php echo $colmd;?>">
    			            <div class="form-group">
        		            <label>চাহিদার ধরন</label>
        		           
    			            <select name="type" class="form-control form-control-sm mr-2">
    				            <option value="">সকল চাহিদা</option>
    				            <option value="perm" <?php if($req_type == 'perm'){echo 'selected';}?>>স্থায়ী চাহিদা</option>
    				            <option value="temp" <?php if($req_type == 'temp'){echo 'selected';}?>>অস্থায়ী চাহিদা</option>
    				           
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
        		            <button type="submit" style="margin-top:28px" class="btn btn-primary btn-sm mr-2">অনুসন্ধান</button>
        		        </div>
        		    </div>
        		</form>
        	</div>
        </div>    	
    </div> 
    
    
    
    
    
	<div class="col-md-12">
		<div class="d-item-wrapper">
			<div class="d-item-title">
				<h3>চাহিদা ব্যবস্থাপনা</h3>
				<!--<div class="d-item-tools">-->
				<!--    <a href="add_requisition" class="btn btn-info btn-sm">চাহিদা যোগ করুন</a>-->
				<!--</div>-->
			</div>
			<div class="d-item-content">
				


				<div class="table-responsive">
					<table class="table table-striped table-bordered text-center align-middle mb-0">
						<thead class="thead-custom">
							<tr>
								<th>#</th>
								<th>তারিখ</th>
								<th>চাহিদা আইডি</th>
								
								<th> শাখা </th>
								<th>চাহিদাকারীর নাম</th>
								<th>অবস্থা</th>
								<th>চাহিদার ধরন</th>
								<th style="">অপসন</th>
							</tr>
						</thead>
						<tbody>
							<?php
        		        if($requisition_list){
        		            $i = 1;
        		           foreach($requisition_list as $v){
        		               $section = '';
        		               foreach($db->getdata('select * from staff where id ='.$v['staff_id']) as $row){
        		                   $section = $db->getonecol('name','section','id',$v['section_id']);
        		                   $staff = $row['name'];
        		               }
        		              
            		           echo '<tr>
            		            <td>'.$db->en_bn($i).'</td>
            		            <td>'.$db->setdate($v['cdate']).'</td>
            		            <td>'.$db->get_req_id($v['cus_id'],$v['financial_year']).'</td>
            		            <td>'.$section.'</td>
            		            <td>'.$staff.'</td>
            		            <td>'.$db->req_status($v['status']).'</td>
            		            <td>';
            		            if($v['pro_type'] == 'perm'){
            		                echo '<span class="badge rounded-pill bg-success text-light px-2 py-1" style="padding: 2px 6px;border-radius: 6px;">স্থায়ী</span>';
            		            }elseif($v['pro_type'] == 'temp'){
            		                echo '<span class="badge rounded-pill bg-primary text-light px-2 py-1" style="padding: 2px 6px;border-radius: 6px;">অস্থায়ী</span>';
            		            }
            		            
            		            echo '</td>
            		            <td><div style="display: flex;justify-content: center;">';
            		                $cnl = 0;
            		                
            		                if($v['status'] != 1 and $v['status'] != 5){
            		                    $cnl = 1;
            		                    if($db->is_section() and $v['status'] == 3){
            		                       $cnl = 0; 
            		                    }
            		                    
            		                }
            		                if($db->is_section() and $v['status'] == 2 and !$db->is_super()){
            		                    $cnl = 0; 
            		                }
            		                
            		                if($cnl == 1){
            		                    if($db->is_section() or $db->is_super()){
            		                       echo '<a onclick="return confirm(\'আপনি কি চাহিদা বাতিল করতে ইচ্ছুক?\')" href="'.$db->domain().'manage_requisition&cancle_id='.$v['id'].'" class="btn btn-danger btn-sm" style="margin-right:5px"><i class="fa fa-close"></i></a>'; 
            		                    }
                		            }
            		            
            		            
                		            if($v['status'] == 0){
                		               if($db->is_section()){
                		                echo '<a onclick="return confirm(\'আপনি কি চাহিদা দাখিল করতে ইচ্ছুক?\')" href="'.$db->domain().'manage_requisition&approve_id='.$v['id'].'" class="btn btn-warning btn-sm" style="margin-right:5px">অনুমোদন</a>'; 
                		               }
                		            }elseif($v['status'] == 2 and $db->is_super()){
                		               echo '<button onclick="app_by_sup('.$v['id'].')" class="btn btn-secondary btn-sm" style="margin-right:5px;width:112px;">চুড়ান্ত অনুমোদন</button>';
                		            }elseif($v['status'] == 3 and $db->is_stock()){
                		               echo '<button onclick="deliver('.$v['id'].')" class="btn btn-primary btn-sm" style="margin-right:5px">বিতরণ </button>';
                		            }
                		            
            		                echo '<button onclick="show_req_inv('.$v['id'].')" class="btn btn-success btn-sm" style="margin-right:5px"><i class="fa fa-eye"></i></button>';
            		                $eok = 1;
            		                if($db->is_staff() and $v['status'] == 2){
            		                    $eok = 0;
            		                }
            		                
            		                if($v['status'] == 0 or $v['status'] == 2){
            		                    if($eok == 1){
            		                         echo '<a href="'.$db->domain().'add_requisition&id='.$v['id'].'" style="margin-right:5px" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>';
            		                    }
            		                   
            		                }
            		                if (in_array('manage_requisition_delete', $permission) and $db->is_super() && $v['status'] != 1){
            		                    echo '<a href="'.$db->domain().$url[0].'&del_id='.$v['id'].'" onclick="return confirm(\'আপনি কি নিশ্চিত?\')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>';
            		                }
            		                
            		                if($db->getm('received_status') && $v['status'] == 1 and $db->uid() == $v['staff_id']){
            		                    echo '<a href="'.$db->domain().$url[0].'&recevied_id='.$v['id'].'" style="margin-left:5px" onclick="return confirm(\'আপনি কি নিশ্চিত?\')" class="btn btn-primary btn-sm">গৃহীত</a>';
            		                }
            		                
            		                
            		            echo '</div></td>
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
        post_ajax('control.php',{invoice_id:id},'#show_data')
    }
    function app_by_sup(id){
        $('#add_modal').modal('show')
        post_ajax('control.php',{app_by_sup_req:id},'#show_data')
    }
    
    function deliver(id){
        $('#add_modal').modal('show')
        post_ajax('control.php',{deliver_by_inch_req:id},'#show_data')
    }
    function show_staff(v){
        $.post("control.php",{log_section_id: v},function(data, status){
            $('.staff_id').html(data)
        });
    }
</script>