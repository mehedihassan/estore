<?php 
$db->invoice('চাহিদা','req');
$from = date('Y-m-01');
$to = date('Y-m-d');
$staff_id = $status = $section_id = '';
$all_st = $app_st = $pen_st = '';
if ($db->getm('from')) {
    $from = $db->getm('from');
    $to = $db->getm('to');
    $staff_id = $db->getm('staff');
    $section_id = $db->getm('section_id');
    
}
if(strlen($db->getm('status')) == 1){
    $status = $db->getm('status');
}





$sql = 'select * from invoice where id > 0';

if($db->is_staff()){
    $sql .= ' and staff_id ='.$db->uid();
}

$sql .= ' and type = "req" and pro_type = "'.$pro_type.'"';

if ($db->getm('from')) {
    $sql .= ' and cdate between "'.$from.'" and "'.$to.'"';
}



if($section_id > 0){
    $sql .= ' and section_id ='.$section_id;
}

if($staff_id != ''){
    $sql .= ' and staff_id ='.$staff_id;
}

if($staff_id != ''){
    $sql .= ' and staff_id ='.$staff_id;
}
if ($status >= 0 and strlen($status) == 1) {
    $sql .= ' and status ='.$status;
}


if($db->is_super()){
    if($db->is_section() and $status == 0){
        $sql .= ' and section_id ='.$db->is_section();
    }
}elseif($db->is_section() and !$db->is_stock()){
    $sql .= ' and section_id ='.$db->is_section();
}



//echo $sql;
$sql .= ' order by id DESC';

$req_list = $db->getdata($sql);

$title_type = '';
if($status == 3){
    $title_type = 'বিতরনযোগ্য';
}elseif($status == '0,2'){
    $title_type = 'প্রক্রিয়াধীন';
}

?>
<style>
    td{
        background:#fff;
    }
</style>
<div class="row">
    
    <div class="col-md-12">
        <div class="d-item-wrapper">
            <div class="d-item-title">
				<h3>অনুসন্ধান </h3>
			</div>
            <div class="d-item-content">
        	    <form action="" method="get" class="report-filter no-print">
    			    <div class="row mb-3">
    			        
    			        <div class="col-md-2">
        		            <div class="form-group">
    			            <select name="section_id" class="form-control select2 form-control-sm mr-2" style="width: 100%;" onchange="show_staff(this.value)">
    				            <option value="">সকল শাখা </option>
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
    			            <select name="staff" class="form-control select2 form-control-sm mr-2 staff_id" style="width: 100%;">
    				            <option value="">কর্মচারী নির্বাচন করুন</option>
    				            <?php 
    				            $staff_name = '';
    				            $sql = 'select id, id_no, name from staff where status = 1';
    				            if($db->is_section() and !$db->is_super()){
                                    $sql .= ' and section_id ='.$db->is_section();
                                }
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
    			        <div class="col-md-2">
    			            <select class="form-control form-control-sm mr-2" name="status">
    			                <option value="99">সব </option>
    			                <option value="0" <?php if($status == '0'){echo 'selected';}?>>প্রক্রিয়াধীন</option>
    			                  <option value="2" <?php if($status == 2){echo 'selected';}?>>চূড়ান্ত অনুমোদনের অপেক্ষায়</option>
    			                 <option value="3" <?php if($status == 3){echo 'selected';}?>>বিতরনযোগ্য</option>
    			            </select>
    			        </div>
    			        <div class="col-md-2">
    			            <input type="date" name="from" class="form-control form-control-sm mr-2" value="<?php echo $from; ?>">
    			        </div>
    			        <div class="col-md-2">
    			            <input type="date" name="to" class="form-control form-control-sm mr-2" value="<?php echo $to; ?>">
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
			<div class="d-item-title no-print">
				<h3>চাহিদা রিপোর্ট</h3>
				<div class="d-item-tools">
				    <button type="button" class="btn btn-success btn-sm pb-0" onclick="window.print();">প্রিন্ট</button>
				</div>
			</div>
			<div class="d-item-content">
			    
			    
			    <?php echo $db->head(); ?>
                <h3 style="font-weight: bold;text-align: center;font-size: 18px;padding-top: 7px;margin-top:0;margin-bottom:5px;"><?php echo $title.' ';?> চাহিদার বর্তমান প্রতিবেদন</h3>
                <h3 style="text-align: center;font-size: 14px;padding-top: 7px;margin-top:0;margin-bottom:10px;"><?php echo $db->setdate($from) . ' - ' . $db->setdate($to); ?></h3>
                <h3 style="text-align: center;font-size: 14px;padding-top: 0;margin-top:0;margin-bottom:10px;font-weight: bold;"><?php echo $staff_name; ?></h3>
			    
			    <div class="table-responsive">
					<table class="table table-striped table-bordered text-center align-middle mb-0">
						<thead class="thead-custom">
							<tr>
								<th style="width:20px">ক্রমিক নং</th>
								<th style="width:90px;">তারিখ</th>
								<th>চাহিদা আইডি</th>
								<th>চাহিদাকারীর নাম</th>
								<th>শাখা</th>
								<th>কক্ষ</th>
								<th>পণ্যের নাম</th>
								<th>পণ্য কোড</th>
								<th>পরিমাণ</th>
								<th>
								<?php
								if($status == 3){
								    echo 'অনুমোদনকারীর';
								}else{
								    echo 'চাহিদাকারীর';
								}
								
								?> মন্তব্য
								
								</th>
								<th>অবস্থা</th>
								<th class="hdprint">অপসন</th>
							</tr>
						</thead>
						<tbody>
						    <?php 
						        if ($req_list) :
						            $i = 1;
						            $tAmount = $tDiscount = $tCharge = $tqty = 0;
						            foreach ($req_list as $v) :
						                $name = $db->getonecol('name','staff','id',$v['staff_id']);
						                $room_id = $db->getonecol('room_id','staff','id',$v['staff_id']);
						                
						                
						    
            						    $sub_sql = 'select a.*,b.name,b.product_code,b.unit_type_id from invoice_details a
            						    left join product b on b.id = a.product_id
            						    where a.invoice_id ='.$v['id'];
            						    $sub_ar = $db->getdata($sub_sql);
            						    $row_span = count($sub_ar) + 1;
            						    
            						    $html = '<tr>
            						        <td rowspan="'.$row_span.'">'.$db->en_bn($i).'</td>
            						        <td rowspan="'.$row_span.'">'.$db->setdate($v['cdate']).'</td>
                        		            <td rowspan="'.$row_span.'">'.$db->get_req_id($v['cus_id'],$v['financial_year']).'</td>
                        		            <td rowspan="'.$row_span.'" style="text-align:left">'.$name.'</td>
                        		            <td rowspan="'.$row_span.'" style="text-align:center">'.$db->getonecol('name','section','id',$v['section_id']).'</td>
                        		            <td rowspan="'.$row_span.'" style="text-align:center">'.$db->getonecol('room_no','room','id',$room_id).'</td>
                        		            <td style="padding:0px 5px !important"></td>
                        		            <td style="padding:0px 5px !important"></td>
                        		            <td style="padding:0px 5px !important"></td>
                        		            <td style="padding:0px 5px !important"></td>
                        		            <td rowspan="'.$row_span.'">'.$db->req_status($v['status']).'</td>
                        		            
                        		            <td rowspan="'.$row_span.'"  class="hdprint">';
                        		            if($v['status'] == 0){
                        		               if($db->is_section() and $db->is_section()){
                        		                $html .= '<a onclick="return confirm(\'আপনি কি  অনুমোদন করতে ইচ্ছুক?\')" href="'.$db->domain().'manage_requisition&approve_id='.$v['id'].'" class="btn btn-warning btn-sm" style="margin-right:5px">অনুমোদন</a>'; 
                        		               }
                        		            }
                        		            if($v['status'] == 2 and $db->is_super()){
                        		               $html .= '<button onclick="app_by_sup('.$v['id'].')" class="btn btn-secondary btn-sm" style="margin-right:5px">চুড়ান্ত অনুমোদন</button>';
                        		            }
                        		            if($v['status'] == 3 and $db->is_stock()){
                        		               $html .= '<button onclick="deliver('.$v['id'].')" class="btn btn-primary btn-sm" style="margin-right:5px">বিতরণ </button>';
                        		            }
                        		            

                    		            $html .= '</td>
                        		            
            						    </tr>';
            						    $sub_html = '';
            						    foreach($sub_ar as $row){
            						        $unit = $db->getonecol('name','unit_type','id',$row['unit_type_id']);
            						        $qty = $row['qty'];
            						        $note = $row['note'];
            						        if($status == 3){
            						            $note = $row['super_note'];
            						            $qty = $row['app_qty'];
            						        }
            						        $tqty += $qty;
            						        $name = $row['name'];
            						        if($row['product_id'] == 0){
            						            $name = $row['product_name'];
            						        }
            						        $sub_html .= '<tr>
                        		            <td style="text-align:left">'.$name.'</td>
                        		            <td style="text-align:center">'.$row['product_code'].'</td>
                        		            <td style="text-align:center">'.$db->en_bn($qty).' '.$unit.'</td>
                        		            <td style="text-align:center">'.$note.'</td>
            						    </tr>';
            						    }
            						    
            						    echo $html.$sub_html;
						    
						    
						    
						    
        						    $i++;
        						  endforeach;
        						else :
						    ?>
						    <tr>
						        <td colspan="13">কোন আইটেম নেই</td>
						    </tr>
						    <?php 
						        endif;
						    ?>
						</tbody>
						<tfoot>
						    <tr>
						        <th colspan="8" class="text-center">মোট:</th>
						        <th><?php echo $db->en_bn($tqty); ?></th>
						        <th></th>
						        <th></th>
						        <th class="hdprint"></th>
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
            $('.staff_id').html('<option value="">সকল কর্মচারী</option>'+data)
        });
    }
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
</script>