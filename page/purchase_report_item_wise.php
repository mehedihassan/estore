<?php 

$from = date('Y-m-d');
$to = date('Y-m-d');

if (isset($_POST['search'])) {
    $from = $_POST['from'];
    $to = $_POST['to'];
}

$sql = 'select * from invoice_details where cdate between "'.$from.'" and "'.$to.'" and type = "pur" order by date DESC';

$purchase_list = $db->getdata($sql);

?>

<div class="row">
	<div class="col-md-12">
		<div class="d-item-wrapper">
			<div class="d-item-title">
				<h3>আইটেম অনুযায়ী ক্রয় রিপোর্ট</h3>
				<div class="d-item-tools" style="margin-top: -4px;">
				    <div class="d-flex">
				        <button type="button" class="btn btn-success btn-sm pb-0" onclick="window.print();">প্রিন্ট</button>
    				</div>
				</div>
			</div>
			<div class="d-item-content">
			    
			    <form action="" method="POST" class="report-filter">
    			    <div class="row mb-3">
    			        <div class="col-md-2">
    			            <input type="date" name="from" class="form-control form-control-sm mr-2" value="<?php echo $from; ?>">
    			        </div>
    			        <div class="col-md-2">
    			            <input type="date" name="to" class="form-control form-control-sm mr-2" value="<?php echo $to; ?>">
    			        </div>
    			        <div class="col-md-1">
    			            <button type="submit" name="search" class="btn btn-primary btn-sm mr-2">অনুসন্ধান</button>
    			        </div>
    			    </div>
    			</form>
			    
			    <?php echo $db->head(); ?>
                <h3 style="font-weight: bold;text-align: center;font-size: 18px;padding-top: 7px;margin-top:0;margin-bottom:5px;">আইটেম অনুযায়ী ক্রয় রিপোর্ট</h3>
                <h3 style="text-align: center;font-size: 14px;padding-top: 7px;margin-top:0;margin-bottom:10px;"><?php echo $db->setdate($from) . ' - ' . $db->setdate($to); ?></h3>
			    
			    <div class="table-responsive">
					<table class="table table-striped table-bordered text-center align-middle mb-0">
						<thead class="thead-custom">
							<tr>
								<th>#</th>
								<th>তারিখ</th>
								<th>ক্রয় আইডি</th>
								<th>সরবরাহকারী নাম</th>
								<th>পণ্যের নাম</th>
								<th>মূল্য</th>
								<th>পরিমাণ </th>
								<th>উপ মোট</th>
							</tr>
						</thead>
						<tbody>
						    <?php 
						        if ($purchase_list) :
						            $i = 0;
						            $tqty = $tamount = 0;
						            foreach ($purchase_list as $p_item) :
						                $i++;
						                $name = $p_item['party_name'];
						                $tAmount += $p_item['total_amount'];
						                $tDiscount += $p_item['discount'];
						                $tCharge += $p_item['charge'];
						                
						            $pro_dtls = "select name,unit_type_id from product where id = ".$p_item['product_id'];
						            $pro_info = $db->getdata($pro_dtls)[0];
						            $unit_type_id = $pro_info['unit_type_id'];
						            $unit_type = $db->getonecol('name','unit_type','id',$unit_type_id);
						            
						            $tqty += $p_item['qty'];
						            $tamount += $p_item['amount'];
						            
						    ?>
						    <tr>
						        <td><?php echo $db->en_bn($i); ?></td>
						        <td><?php echo $db->setdate($p_item['cdate']); ?></td>
            		            <td>PI<?php echo $p_item['cus_id']; ?></td>
            		            <td><?php echo $name; ?></td>
            		            <td><?php echo $pro_info['name']; ?></td>
            		            <td><?php echo $db->en_bn($p_item['rate']); ?></td>
            		            <td><?php echo $db->en_bn($p_item['qty']).' '.$unit_type; ?> </td>
            		            <td><?php echo $db->en_bn($p_item['amount']); ?></td>
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
						        <th><?php echo $db->en_bn($tamount); ?></th>
						    </tr>
						</tfoot>
					</table>
				</div>
				
							
			</div>
		</div>
	</div>
</div>