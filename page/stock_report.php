<?php 
$category_list = $db->getdata('
select a.category_id as cat_id,b.name as cat_name from product a 
inner join category b on b.id = a.category_id
where a.type = '.$pro_type.'
group by a.category_id
');
$financial_list = $db->getdata('select * from financial_year');

$cat_id = $finance = $cat_name = '';

$from = date('Y-m-d');
$to = date('Y-m-d');
$type = $financial_year = '';

$type = $db->getm('type');
if($db->getm('finance')){
   $finance = $db->getm('finance'); 
}else{
    $finance = $db->get_financial_id($from);
}

$cat_id = $db->getm('cat_id');

if($finance != ''){
    foreach($db->getdata('select * from financial_year where id ='.$finance) as $v){
        $from = $v['start'];
        $to = $v['end'];
    }
}
$rtype = $db->getm('rtype');
if($rtype == 'stock'){
    $from = $db->getm('from');
    $to = $db->getm('to');
}





$osql = ' and a.cdate >= "'.$from.'" and a.cdate <= "'.$to.'"';
$sql = '
select a.product_id,b.name,b.product_code,b.opening_stock,b.unit_type_id,
sum(
CASE 
    WHEN a.type = "pur" and a.cdate < "'.$from.'" then a.qty
    WHEN a.type = "opening_stock" then a.qty';
    
    if($act_stock){
        $sql .= ' WHEN a.type = "sales" and a.cdate < "'.$from.'" then - a.qty';
    }else{
        $sql .= ' WHEN a.status = 1 and a.type = "req" and a.cdate < "'.$from.'" then - a.app_qty';
    }

$sql .= ' END
) as open,
sum(if(a.type = "pur"'.$osql.',qty,0)) as pur,';

if($act_stock){
    $sql .= 'sum(if(a.type = "sales"'.$osql.',a.qty,0)) as req';
}else{
    $sql .= 'sum(if(a.status = 1 and a.type = "req"'.$osql.',a.app_qty,0)) as req';
}


$sql .= ' from invoice_details a
left join product b on b.id = a.product_id
where a.id > 0
';
if($cat_id != ''){
    $sql .= ' and b.category_id ='.$cat_id;
}


if($pro_type){
    $sql .= ' and b.type ='.$pro_type;
}
$sql .= ' group by a.product_id
';
$req_list = $db->getdata($sql);

$title = '';
if($pro_type == '1'){
  $title = 'স্থায়ী সম্পদের ';  
}elseif($pro_type == '2'){
  $title = 'অস্থায়ী পণ্যের ';  
}


//echo $sql;
?>
<style>
    .select2 {
        width:100% !important;
    }
</style>
<div class="row">
    
    <div class="col-md-12">
        <div class="d-item-wrapper">
            <div class="d-item-title">
				<h3>  অনুসন্ধান </h3>
			</div>
            <div class="d-item-content">
        	    <form action="" method="get" class="report-filter no-print">
    			    <div class="row mb-3">
    			        <div class="col-md-3">
			                <div class="form-group">
    			                <label for="category">ক্যাটাগরি </label>
    			                <select name="cat_id" class="form-control select2">
    			                    <option value="">ক্যাটাগরি নির্বাচন করুন</option>
    			                    <?php
    			                    foreach($category_list as $v){
    			                        echo '<option ';
                                        if($cat_id == $v['cat_id']){
                                            echo 'selected';
                                            $cat_name = $v['cat_name'];
                                        }
                                        echo ' value="'.$v['cat_id'].'">'.$v['cat_name'].'</option>';
    			                    }
    			                    
    			                    ?>
    			                </select>
    			            </div>
			            </div>
    			       <div class="col-md-2">
			                <div class="form-group">
    			                <label for="rtype">প্রতিবেদন  টাইপ </label>
    			                <select name="rtype" class="form-control form-control-sm" style="padding-right: 0;" onchange="report_type(this.value)">
    			                    <option value="year" <?php if($rtype == 'year'){echo 'selected';}?>>বাৎসরিক প্রতিবেদন</option>
    			                    <option value="stock" <?php if($rtype == 'stock'){echo 'selected';}?>>তারিখ অনুযায়ী প্রতিবেদন </option>
    			                </select>
    			            </div>
    			     </div>
    			     <script>
    			         function report_type(v){
    			             if(v == 'stock'){
    			                 $('.date_per').show()
    			                 $('.financial_year').hide()
    			                 
    			             }else if(v == 'year'){
    			                 $('.date_per').hide()
    			                 $('.financial_year').show()
    			             }
    			             
    			         }
    			     </script>
    			     <?php
    			     $date_per_st =  'display:none';
    			     $finacial_st =  '';
    			     if($rtype == 'stock'){
    			         $date_per_st =  '';
    			         $finacial_st =  'display:none';
    			     }
    			     
    			     ?>
    			       <div class="col-md-2 financial_year" style="<?php echo $finacial_st;?>">
			                <div class="form-group">
    			                <label for="category">অর্থ বছর নির্বাচন করুন </label>
    			                <select name="finance"  id="finance_year"class="form-control select2">
    			                    <option value="">অর্থ বছর নির্বাচন করুন</option>
    			                    <?php
    			                    foreach($financial_list as $v){
    			                        $value = '{'.$v['start'].'} and {'.$v['end'].'}';
    			                        $d = date('Y', strtotime($v['start'])).'-'.date('Y', strtotime($v['end']));
    			                        echo '<option ';
                                        if($finance == $v['id']){
                                            echo 'selected';
                                            $financial_year = $d;
                                        }
                                        echo ' value="'.$v['id'].'">'.$d.'</option>';
    			                    }
    			                    
    			                    ?>
    			                </select>
    			            </div>
			            </div>
			            
			            <div class="col-md-2 date_per" style="<?php echo $date_per_st;?>">
			                <div class="form-group">
			                    <label>শুরু</label>
			                    <input type="date" class="form-control form-control-sm" name="from" value="<?php echo $from;?>">
			                </div>
			            </div>
			            
			            <div class="col-md-2 date_per" style="<?php echo $date_per_st;?>">
			                <div class="form-group">
			                    <label>শেষ</label>
			                    <input type="date" class="form-control form-control-sm" name="to" value="<?php echo $to;?>">
			                </div>
			            </div>
    			       
    			       <div class="col-md-1">
    			            <button type="submit" class="btn btn-primary btn-sm mr-2" style="margin-top:27px">অনুসন্ধান</button>
    			        </div>
    			    </div>
    			</form>
        	</div>
        </div>    	
    </div>  
    
    
    
	<div class="col-md-12">
		<div class="d-item-wrapper">
			<div class="d-item-title no-print">
				<h3>স্টক রিপোর্ট</h3>
				<div class="d-item-tools">
				    <button type="button" class="btn btn-success btn-sm pb-0" onclick="window.print();">প্রিন্ট</button>
				</div>
			</div>
			<div class="d-item-content">
			    
			    
			    
			    <?php echo $db->head(); ?>
                <h3 style="font-weight: bold;text-align: center;font-size: 18px;padding-top: 7px;margin-top:0;margin-bottom:0;"><?php echo $title;
                if($act_stock){
                    echo 'প্রকৃত';
                }
                
                ?> স্টক রিপোর্ট</h3>
                <?php if($rtype == 'year'){?>
                <h3 style="text-align: center;font-size: 14px;padding-top: 7px;margin-top:0;margin-bottom:10px;"><?php echo $financial_year; ?></h3>
                <?php } if($rtype == 'stock'){?>
                <h3 style="text-align: center;font-size: 14px;padding-top: 7px;margin-top:0;margin-bottom:10px;"><?php echo $db->setdate($from) . ' - ' . $db->setdate($to); ?></h3>
                <?php }?>
                <p style="margin: 8px;font-size: 17px;">সম্পদের ধরন: <?php echo $cat_name;?></p>
			    <div class="table-responsive">
					<table class="table table-striped table-bordered text-center align-middle mb-0">
						<thead class="thead-custom">
							<tr>
								<th>সম্পদের  নাম</th>
								<th style="width:116px;">পূর্ববর্তী জের</th>
								<th style="width:140px;"><?php echo $financial_year;?> অর্থ বছরে ক্রয়কৃত</th>
								<th style="width:110px;">মোট সম্পদের সংখ্যা</th>
								<th style="width:110px;">
								    <?php 
								    if($act_stock){
								        echo 'মোট  নিষ্পত্তি';
								    }else{
								        echo 'মোট বিতরণ';
								    }
								    ?>
								    
								    
								    </th>
								<th style="width:110px;">স্টোরে সংরক্ষিত</th>
							</tr>
						</thead>
						<tbody>
						    <?php 
						    $par = [];$i = 0;$topen = $tpur = $treq = $tb = $tdebit = 0;$ids = '';
						        if ($req_list) :
						            
						            foreach ($req_list as $v) : 
						                $ids .= ','.$v['product_id'];
						                $open = $v['open'];
						                $pur = $v['pur'];
						                $req = $v['req'];
						                $debit = $pur + $open;
						                $b = $open + $pur - $req;
						                $unit = $db->getonecol('name','unit_type','id',$v['unit_type_id']);
						                $par[] = [
						                    'name'=>$v['name'],'unit'=>$unit,
						                    'pur'=>$pur,'debit'=>$debit,
						                    'req'=>$req,'b'=>str_replace('-','',$b),'open'=>$open,
						                    ];
						            
						            ?>
        						    
						    <?php 
						            endforeach;
						        else :
						    ?>
						    <tr>
						        <td colspan="7">কোন আইটেম নেই</td>
						    </tr>
						    <?php 
						        endif;
						        
						        $ids = trim($ids,',');
						        $sql = 'select a.*,b.name as unit_name from product a
						        left join unit_type b on b.id = a.unit_type_id
						        where a.id > 0';
						        
						        if($ids != ''){
						            $sql .= ' and a.id not in('.$ids.')';
						        }
						        
						        
						        if($cat_id != ''){
                                    $sql .= ' and a.category_id ='.$cat_id;
                                }
                                if($pro_type){
                                    $sql .= ' and a.type ='.$pro_type;
                                }
                                
						        foreach($db->getdata($sql) as $v){
						            $par[] = [
					                    'name'=>$v['name'],'unit'=>$v['unit_name'],
					                    'pur'=>0,'debit'=>0,
					                    'req'=>0,'b'=>0,'open'=>0,
					                    ];
						        }
						        
						        
						        if (!empty($par)) {
                                    usort($par, function($a, $b) {
                                        return $b['b'] - $a['b'];
                                    });
                                }
						        
						        if($par){
						        foreach($par as $v){
						        $i++;
						        $open = $v['open'];
				                $pur = $v['pur'];
				                $req = $v['req'];
				                $debit = $pur + $open;
				                $b = $open + $pur - $req;
				                $topen += $open;
				                $tpur += $pur;
				                $treq += $req;
				                $tb += $b;
				                $tdebit += $debit;
				                $unit = $v['unit'];
						        ?>
						        
						        <tr>
                		            <td style="text-align:left"><?php echo $db->en_bn($i).'। '.$v['name']; ?></td>
                		            <td><?php if($open !=0): echo $db->en_bn($open).' '.$unit; endif?></td>
                		            <td><?php if($pur !=0): echo $db->en_bn($pur).' '.$unit; endif?></td>
                		            <td><?php if($debit !=0): echo $db->en_bn($debit).' '.$unit; endif?></td>
                		            <td><?php if($req !=0): echo $db->en_bn($req).' '.$unit; endif?></td>
                		            <td><?php if($b !=0): echo $db->en_bn($b).' '.$unit; endif?></td>
    						    </tr>
						            
						        <?php }}
						    ?>
						</tbody>
						<tfoot>
						    <tr>
						        <th colspan="1" class="text-center">মোট:</th>
						        <th><?php echo $db->en_bn($topen); ?></th>
						        <th><?php echo $db->en_bn($tpur); ?></th>
						        <th><?php echo $db->en_bn($tdebit); ?></th>
						        <th><?php echo $db->en_bn($treq); ?></th>
						        <th><?php echo $db->en_bn($tb); ?></th>
						    </tr>
						</tfoot>
					</table>
				</div>
				
							
			</div>
		</div>
	</div>
</div>