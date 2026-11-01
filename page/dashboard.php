<?php
$from = date('Y-m-01');
$to = date('Y-m-d');
$total_pen_req = $total_app_qty = $total_long_req = $total_long_req2 = $total_long_app_req = $total_pen_req2 = 0;

$osql = '';
$osql2 = '';
$pen_sql = '';
if($db->is_stock()){
    $pen_sql = ' and staff_id ='.$db->uid();
}else{
    if($db->is_staff()){
        $osql = ' and staff_id ='.$db->uid();
    }elseif($db->is_super()){
       if($db->is_section()){
           $osql2 .= ' and section_id ='.$db->is_section();
       } 
    }
}
$def_status = 0;
if($db->is_section()){
    $def_status = 2;
}
if($db->is_super()){
    $def_status = 2;
}elseif($db->is_section() and !$db->is_stock()){
    $osql = ' and section_id ='.$db->is_section();
}




$sql = '
select 
sum(if(pro_type = "temp" and status = 0'.$osql2.$osql.$pen_sql.',1,null)) as q,
sum(if(pro_type = "temp" and status = 2'.$osql.$pen_sql.',1,null)) as q2,
sum(if(pro_type = "temp" and status = 3'.$osql.',1,null)) as a,

sum(if(pro_type = "perm" and status = 0'.$osql2.$osql.$pen_sql.',1,null)) as lq,
sum(if(pro_type = "perm" and status = 2'.$osql.$pen_sql.',1,null)) as lq2,
sum(if(pro_type = "perm" and status = 3'.$osql.',1,null)) as la
from invoice where type = "req"';
//echo $sql;
foreach($db->getdata($sql) as $v){
   $total_pen_req = $v['q']==''?0:$v['q']; 
   $total_pen_req2 = $v['q2']==''?0:$v['q2']; 
   
   $total_app_qty = $v['a']==''?0:$v['a']; 
   $total_long_req = $v['lq']==''?0:$v['lq']; 
   $total_long_req2 = $v['lq2']==''?0:$v['lq2']; 
   
   $total_long_app_req = $v['la']==''?0:$v['la'];
    
}

$total_pen_req = $total_pen_req + $total_pen_req2;
$total_long_req = $total_long_req + $total_long_req2;

$total_stock = $total_stock1 = 0;
$st_sql = 'select 
sum(
CASE 
    WHEN b.type = 2 and a.type = "pur" then a.qty
    WHEN b.type = 2 and a.type = "opening_stock" then a.qty
    WHEN b.type = 2 and a.type = "req" and a.status = 1 then - a.app_qty
END
) as balance,

sum(
CASE 
    WHEN b.type = 1 and a.type = "pur" then a.qty
    WHEN b.type = 1 and a.type = "opening_stock" then a.qty
    WHEN b.type = 1 and a.type = "req" and a.status = 1 then - a.app_qty
END
) as balance2



from invoice_details a

left join product b on b.id = a.product_id


';

foreach($db->getdata($st_sql) as $v){
    if($v['balance'] != ''){
        $total_stock = $db->en_bn($v['balance']);
    }
    $total_stock1 = $db->en_bn($v['balance2']);
}


$long_pro = $short_pro = 0;
$sql = '
select 
count(if(type = 1,id,null)) as longs,
count(if(type = 2,id,null)) as shorts

from product

';
foreach($db->getdata($sql) as $v){
    $long_pro = $v['longs'];
    $short_pro = $v['shorts'];
}




$temp_title = 'অস্থায়ী পণ্যের চাহিদা';
$perm_title = 'স্থায়ী সম্পদের চাহিদা';
if($db->is_super()){
    $temp_title = 'অস্থায়ী পণ্য';
    $perm_title = 'স্থায়ী সম্পদ';
}

?>
<style>
.bg_deepgreen {
    background: #14c1a1;
    background: -webkit-linear-gradient( 45deg , #6a11cb, #23af8f)!important;
    background: linear-gradient( 45deg , #11cb64 , #258ffc)!important;
}
.count-card p{
    font-size:15px;
}
.count-card .card {
    cursor: pointer;
}
.count-card .card:hover {
    box-shadow: 0 2px 9px rgb(0 0 0 / 40%);
}
</style>

<div class="row">
	<div class="col-md-12">
		<div class="d-item-wrapper">
			<div class="d-item-title border-bottom text-center" style="color: #fff;background-color: #7187ff;border-radius: 25px;">
				<h3><?php echo $temp_title;?></h3>
			</div>
		</div>
	</div>

</div>



<div class="row count-card">
    <div class="col-lg-3 col-md-6">
		<div class="card bg_deepgreen">
		    <a href="temp_add_requisition">
			<div class="card-body">
				<div class="d-flex align-items-center" style="height: 50px;">
					<div class="mc-auto" style="margin: 0 auto;">
						<i class="fa fa-plus count-icon text-white" style="font-size: 35px;"></i>
					</div>
				</div>
				
				<div class="d-flex align-items-center text-white">
					<p class="mb-0" style="margin:0 auto">নতুন চাহিদা যোগ করুন</p>
					<p class="mb-0 ml-auto"><span><i class="bx bx-up-arrow-alt"></i></span></p>
				</div>
			</div>
			</a>
		</div>
	</div>
    <?php if (in_array('new_req_tem', $permission)){?>
	<div class="col-lg-3 col-md-6">
		<div class="card bg-deepblue">
		    <a class="text-white" href="temp_req_report?status=<?php echo $def_status;?>">
			<div class="card-body">
				<div class="d-flex align-items-center">
					<h5 class="mb-0 text-white"><?php echo $db->en_bn($total_pen_req);?></h5>
					<div class="ml-auto">
						<i class="fa fa-shopping-cart count-icon text-white"></i>
					</div>
				</div>
				<div class="progress my-2 bg-white-transparent" style="height:4px;">
					<div class="progress-bar bg-white" role="progressbar" style="width: 55%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
				</div>
				<div class="d-flex align-items-center text-white">
					<p class="mb-0">নতুন চাহিদা</p>
					<p class="mb-0 ml-auto"><span><i class="bx bx-up-arrow-alt"></i></span></p>
				</div>
			</div>
			</a>
		</div>
	</div>
    <?php }?>
    <?php if (in_array('pre_app_req_tem', $permission)){?>
	<div class="col-lg-3 col-md-6">
		<div class="card bg-ohhappiness">
		    <a class="text-white" href="temp_req_report?status=3">
			<div class="card-body">
				<div class="d-flex align-items-center">
					<h5 class="mb-0 text-white"><?php echo $db->en_bn($total_app_qty);?></h5>
					<div class="ml-auto">
						<i class="fa fa-shopping-cart count-icon text-white"></i>
					</div>
				</div>
				<div class="progress my-2 bg-white-transparent" style="height:4px;">
					<div class="progress-bar bg-white" role="progressbar" style="width: 55%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
				</div>
				<div class="d-flex align-items-center text-white">
					<p class="mb-0">বিতরণযোগ্য চাহিদা</p>
					<p class="mb-0 ml-auto"><span><i class="bx bx-up-arrow-alt"></i></span></p>
				</div>
			</div>
			</a>
		</div>
	</div>
    <?php }?>
    
    
    <?php
    $sql = "select count(id) as t from invoice where pro_type = 'temp' and status = 1 and type = 'req' and received_status = 0 and staff_id =".$db->uid();
    $t = $db->getdata($sql)[0]['t'];
    ?>
    <div class="col-lg-3 col-md-6">
		<div class="card bg_deepgreen">
		    <a class="text-white" href="manage_requisition?received_status=1&status=1&type=temp&from=2024-01-01&to=<?php echo $db->cdate();?>">
			<div class="card-body">
				<div class="d-flex align-items-center">
					<h5 class="mb-0 text-white"><?php echo $db->en_bn($t);?></h5>
					<div class="ml-auto">
						<i class="fa fa-shopping-cart count-icon text-white"></i>
					</div>
				</div>
				<div class="progress my-2 bg-white-transparent" style="height:4px;">
					<div class="progress-bar bg-white" role="progressbar" style="width: 55%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
				</div>
				<div class="d-flex align-items-center text-white">
					<p class="mb-0">গ্রহনযোগ্য চাহিদা</p>
					<p class="mb-0 ml-auto"><span><i class="bx bx-up-arrow-alt"></i></span></p>
				</div>
			</div>
			</a>
		</div>
	</div>
	
	<?php if (in_array('need_purchase_tem', $permission)){?>
	<?php
    $sql = "select count(id) as t from invoice_details where pro_type = 'temp' and status = 0 and type = 'req' and need_purchase = 1";
    $t = $db->getdata($sql)[0]['t'];
    ?>
    <div class="col-lg-3 col-md-6">
		<div class="card bg-ohhappiness">
		    <a class="text-white" href="need_purchase?type=temp">
			<div class="card-body">
				<div class="d-flex align-items-center">
					<h5 class="mb-0 text-white"><?php echo $db->en_bn($t);?></h5>
					<div class="ml-auto">
						<i class="fa fa-shopping-cart count-icon text-white"></i>
					</div>
				</div>
				<div class="progress my-2 bg-white-transparent" style="height:4px;">
					<div class="progress-bar bg-white" role="progressbar" style="width: 55%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
				</div>
				<div class="d-flex align-items-center text-white">
					<p class="mb-0">ক্রয় করা প্রয়োজন</p>
					<p class="mb-0 ml-auto"><span><i class="bx bx-up-arrow-alt"></i></span></p>
				</div>
			</div>
			</a>
		</div>
	</div>
    
    <?php }?>
    
    
    
    <?php if (in_array('total_pro_tem', $permission)){?>
	<div class="col-lg-3 col-md-6">
		<div class="card bg-ibiza">
		    <a href="temp_pro_list" class="text-white">
			<div class="card-body">
				<div class="d-flex align-items-center">
					<h5 class="mb-0 text-white"><?php echo $db->en_bn($short_pro);?></h5>
					<div class="ml-auto">
						<i class="fa fa-shopping-cart count-icon text-white"></i>
					</div>
				</div>
				<div class="progress my-2 bg-white-transparent" style="height:4px;">
					<div class="progress-bar bg-white" role="progressbar" style="width: 55%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
				</div>
				<div class="d-flex align-items-center text-white">
					<p class="mb-0">অস্থায়ী পণ্যের তালিকা</p>
					<p class="mb-0 ml-auto"><span><i class="bx bx-up-arrow-alt"></i></span></p>
				</div>
			</div>
			</a>
		</div>
	</div>
    <?php }?>
    
    
    
    
    
    <?php if (in_array('not_received_tem', $permission)){?>
    
    <?php
    $sql = "select count(id) as t from invoice where pro_type = 'temp' and status = 1 and type = 'req' and received_status = 0";
    $total_not_received = $db->getdata($sql)[0]['t'];
    ?>
	<div class="col-lg-3 col-md-6">
		<div class="card bg-deepblue">
		    <a href="manage_requisition?section_id=&staff=&status=1&type=temp&from=2024-07-01&to=<?=$db->cdate()?>&no_received_status=1" class="text-white">
			<div class="card-body">
				<div class="d-flex align-items-center">
					<h5 class="mb-0 text-white"><?php echo $db->en_bn($total_not_received);?></h5>
					<div class="ml-auto">
						<i class="fa fa-shopping-cart count-icon text-white"></i>
					</div>
				</div>
				<div class="progress my-2 bg-white-transparent" style="height:4px;">
					<div class="progress-bar bg-white" role="progressbar" style="width: 55%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
				</div>
				<div class="d-flex align-items-center text-white">
					<p class="mb-0">ইউজার কতৃক অগৃহীত</p>
					<p class="mb-0 ml-auto"><span><i class="bx bx-up-arrow-alt"></i></span></p>
				</div>
			</div>
			</a>
		</div>
	</div>
    <?php }?>
    
    
    <?php if (in_array('stock_tem', $permission)){?>
	<div class="col-lg-3 col-md-6">
		<div class="card bg-moonlit">
		    <a class="text-white" href="temp_stock_report">
			<div class="card-body">
				<div class="d-flex align-items-center">
					<h5 class="mb-0 text-white"><?php echo $total_stock;?></h5>
					<div class="ml-auto">
						<i class="fa fa-shopping-cart count-icon text-white"></i>
					</div>
				</div>
				<div class="progress my-2 bg-white-transparent" style="height:4px;">
					<div class="progress-bar bg-white" role="progressbar" style="width: 55%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
				</div>
				<div class="d-flex align-items-center text-white">
					<p class="mb-0">বর্তমান স্টক</p>
					<p class="mb-0 ml-auto"><span><i class="bx bx-up-arrow-alt"></i></span></p>
				</div>
			</div>
			</a>
		</div>
	</div>
<?php }?>
 
</div>

<?php if (in_array('recent_req_tem', $permission)){?>
<div class="row">
	<div class="col-md-12">
		<div class="d-item-wrapper">
			<div class="d-item-title">
				<h3>নতুন দ্রব্যাদির চাহিদা পত্র</h3>
			</div>
			<div class="d-item-content">
				
                
				<div class="table-responsive">
					<table class="table table-striped table-bordered text-center align-middle mb-0">
						<thead class="thead-custom">
							<tr>
							    <th>#</th>
						    	<th>চাহিদার তারিখ</th>
							    <th>চাহিদা আইডি</th>
								<th>চাহিদাকারীর নাম</th>
								<th>পণ্যের নাম</th>
								<th>পণ্য কোড</th>
								<th>অবস্থা</th>
								<th>পরিমাণ</th>
							</tr>
						</thead>
						<tbody>
						    <?php
						        $sql = '
                                select a.*,b.name,b.product_code,b.unit_type_id from invoice_details a
                                left join product b on b.id = a.product_id
                                where a.type = "req" and b.type = 2';
                                if($db->is_stock()){
                                    $sql .= ' and a.status in(3,1)';
                                }else{
                                    if(!$db->is_super()){
                                        $sql .= ' and a.staff_id ='.$db->uid();
                                    }elseif($db->is_super()){
                                        $sql .= ' and a.status != 0';
                                    }
                                }
                                
                                $sql .= ' order by a.id DESC LIMIT 0, 10';
                                $ar = $db->getdata($sql);
						        $perm_his = $tem_his = '';
						        $i = 1;
						        foreach($ar as $v) :
						            $staff = $db->getonecol('name','staff','id',$v['staff_id']);
						            $unit = $db->getonecol('name','unit_type','id',$v['unit_type_id']);
					
						            echo '<tr>
            							    <td>'.$db->en_bn($i).'</td>
            							    <td>'.$db->setdate($v['cdate']).'</td>
            								<td>'.$db->get_req_id($v['cus_id'],$v['financial_year']).'</td>
            								<td>'.$staff.'</td>
            								<td>'.$v['name'].'</td>
            								<td>'.$v['product_code'].'</td>
            								<td>'.$db->req_status($v['status']).'</td>';
            								if($db->is_stock()){
            								    echo '<td>'.$db->en_bn($v['app_qty']).' '.$unit.'</td>';
            								}else{
            								    echo '<td>'.$db->en_bn($v['qty']).' '.$unit.'</td>';
            								}
            								
            							echo '</tr>';
            						$i++;
						            
						            ?>
    							
    							<?php  endforeach; ?>
						</tbody>
					</table>
				</div>



			</div>
		</div>
	</div>
</div>
<?php }?>

<div class="row" style="margin-top:20px">

	<div class="col-md-12">
		<div class="d-item-wrapper">
			<div class="d-item-title border-bottom text-center" style="color: #fff;background-color: #ce0e0ec9;border-radius: 25px;">
				<h3><?php echo $perm_title;?></h3>
			</div>
		</div>
	</div>

</div>


<div class="row count-card">
    
    <div class="col-lg-3 col-md-6">
		<div class="card bg_deepgreen">
		    <a href="perm_add_requisition">
			<div class="card-body">
				<div class="d-flex align-items-center" style="height: 50px;">
					<div class="mc-auto" style="margin: 0 auto;">
						<i class="fa fa-plus count-icon text-white" style="font-size: 35px;"></i>
					</div>
				</div>
				
				<div class="d-flex align-items-center text-white">
					<p class="mb-0" style="margin:0 auto">নতুন চাহিদা যোগ করুন</p>
					<p class="mb-0 ml-auto"><span><i class="bx bx-up-arrow-alt"></i></span></p>
				</div>
			</div>
			</a>
		</div>
	</div>
	
    <?php if (in_array('new_req_perm', $permission)){?>
	<div class="col-lg-3 col-md-6">
		<div class="card bg-deepgreen">
		    <a class="text-white" href="perm_req_report?status=<?php echo $def_status;?>">
			<div class="card-body">
				<div class="d-flex align-items-center">
					<h5 class="mb-0 text-white"><?php echo $db->en_bn($total_long_req);?></h5>
					<div class="ml-auto">
						<i class="fa fa-shopping-cart count-icon text-white"></i>
					</div>
				</div>
				<div class="progress my-2 bg-white-transparent" style="height:4px;">
					<div class="progress-bar bg-white" role="progressbar" style="width: 55%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
				</div>
				<div class="d-flex align-items-center text-white">
					<p class="mb-0">নতুন চাহিদা</p>
					<p class="mb-0 ml-auto"><span><i class="bx bx-up-arrow-alt"></i></span></p>
				</div>
			</div>
			</a>
		</div>
	</div>
    <?php }?>
    <?php if (in_array('pre_app_req_perm', $permission)){?>
	<div class="col-lg-3 col-md-6">
		<div class="card bg-ohhappiness2">
		    <a class="text-white" href="perm_req_report?status=3">
			<div class="card-body">
				<div class="d-flex align-items-center">
					<h5 class="mb-0 text-white"><?php echo $db->en_bn($total_long_app_req);?></h5>
					<div class="ml-auto">
						<i class="fa fa-shopping-cart count-icon text-white"></i>
					</div>
				</div>
				<div class="progress my-2 bg-white-transparent" style="height:4px;">
					<div class="progress-bar bg-white" role="progressbar" style="width: 55%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
				</div>
				<div class="d-flex align-items-center text-white">
					<p class="mb-0">বিতরণযোগ্য চাহিদা</p>
					<p class="mb-0 ml-auto"><span><i class="bx bx-up-arrow-alt"></i></span></p>
				</div>
			</div>
			</a>
		</div>
	</div>
    <?php }?>
    
    
    <?php
    $sql = "select count(id) as t from invoice where pro_type = 'perm' and status = 1 and type = 'req' and received_status = 0 and staff_id =".$db->uid();
    $t = $db->getdata($sql)[0]['t'];
    ?>
    <div class="col-lg-3 col-md-6">
		<div class="card bg_deepgreen">
		    <a class="text-white" href="manage_requisition?received_status=1&status=1&type=perm&from=2024-01-01&to=<?php echo $db->cdate();?>">
			<div class="card-body">
				<div class="d-flex align-items-center">
					<h5 class="mb-0 text-white"><?php echo $db->en_bn($t);?></h5>
					<div class="ml-auto">
						<i class="fa fa-shopping-cart count-icon text-white"></i>
					</div>
				</div>
				<div class="progress my-2 bg-white-transparent" style="height:4px;">
					<div class="progress-bar bg-white" role="progressbar" style="width: 55%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
				</div>
				<div class="d-flex align-items-center text-white">
					<p class="mb-0">গ্রহনযোগ্য চাহিদা</p>
					<p class="mb-0 ml-auto"><span><i class="bx bx-up-arrow-alt"></i></span></p>
				</div>
			</div>
			</a>
		</div>
	</div>
	
	
	
	<?php if (in_array('need_purchase_perm', $permission)){?>
	<?php
    $sql = "select count(id) as t from invoice_details where pro_type = 'perm' and status = 0 and type = 'req' and need_purchase = 1";
    $t = $db->getdata($sql)[0]['t'];
    ?>
    <div class="col-lg-3 col-md-6">
		<div class="card bg-deepgreen">
		    <a class="text-white" href="need_purchase?type=perm">
			<div class="card-body">
				<div class="d-flex align-items-center">
					<h5 class="mb-0 text-white"><?php echo $db->en_bn($t);?></h5>
					<div class="ml-auto">
						<i class="fa fa-shopping-cart count-icon text-white"></i>
					</div>
				</div>
				<div class="progress my-2 bg-white-transparent" style="height:4px;">
					<div class="progress-bar bg-white" role="progressbar" style="width: 55%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
				</div>
				<div class="d-flex align-items-center text-white">
					<p class="mb-0">ক্রয় করা প্রয়োজন</p>
					<p class="mb-0 ml-auto"><span><i class="bx bx-up-arrow-alt"></i></span></p>
				</div>
			</div>
			</a>
		</div>
	</div>
    
    <?php }?>
    
    
    <?php if (in_array('total_pro_perm', $permission)){?>
	<div class="col-lg-3 col-md-6">
		<div class="card bg-ibiza2">
		    <a href="perm_pro_list" class="text-white">
			<div class="card-body">
				<div class="d-flex align-items-center">
					<h5 class="mb-0 text-white"><?php echo $db->en_bn($long_pro);?></h5>
					<div class="ml-auto">
						<i class="fa fa-shopping-cart count-icon text-white"></i>
					</div>
				</div>
				<div class="progress my-2 bg-white-transparent" style="height:4px;">
					<div class="progress-bar bg-white" role="progressbar" style="width: 55%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
				</div>
				<div class="d-flex align-items-center text-white">
					<p class="mb-0">স্থায়ী সম্পদের তালিকা</p>
					<p class="mb-0 ml-auto"><span><i class="bx bx-up-arrow-alt"></i></span></p>
				</div>
			</div>
			</a>
		</div>
	</div>
    <?php }?>
    
    
    
    <?php if (in_array('not_received_tem', $permission)){?>
    
    <?php
    $sql = "select count(id) as t from invoice where pro_type = 'perm' and status = 1 and type = 'req' and received_status = 0";
    $total_not_received = $db->getdata($sql)[0]['t'];
    ?>
	<div class="col-lg-3 col-md-6">
		<div class="card bg-deepblue">
		    <a href="manage_requisition?section_id=&staff=&status=1&type=perm&from=2024-07-01&to=<?=$db->cdate()?>&no_received_status=1" class="text-white">
			<div class="card-body">
				<div class="d-flex align-items-center">
					<h5 class="mb-0 text-white"><?php echo $db->en_bn($total_not_received);?></h5>
					<div class="ml-auto">
						<i class="fa fa-shopping-cart count-icon text-white"></i>
					</div>
				</div>
				<div class="progress my-2 bg-white-transparent" style="height:4px;">
					<div class="progress-bar bg-white" role="progressbar" style="width: 55%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
				</div>
				<div class="d-flex align-items-center text-white">
					<p class="mb-0">ইউজার কতৃক অগৃহীত</p>
					<p class="mb-0 ml-auto"><span><i class="bx bx-up-arrow-alt"></i></span></p>
				</div>
			</div>
			</a>
		</div>
	</div>
    <?php }?>
    
    
    
    
    
    
    
    
    <?php if (in_array('stock_perm', $permission)){?>
	<div class="col-lg-3 col-md-6">
		<div class="card bg-moonlit2">
		    <a class="text-white" href="perm_stock_report">
			<div class="card-body">
				<div class="d-flex align-items-center">
					<h5 class="mb-0 text-white"><?php echo $db->en_bn($total_stock1);?></h5>
					<div class="ml-auto">
						<i class="fa fa-shopping-cart count-icon text-white"></i>
					</div>
				</div>
				<div class="progress my-2 bg-white-transparent" style="height:4px;">
					<div class="progress-bar bg-white" role="progressbar" style="width: 55%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
				</div>
				<div class="d-flex align-items-center text-white">
					<p class="mb-0">বর্তমান  স্টক</p>
					<p class="mb-0 ml-auto"><span><i class="bx bx-up-arrow-alt"></i></span></p>
				</div>
			</div>
			</a>
		</div>
	</div>
    <?php }?>
    
</div>


<?php if (in_array('recent_req_perm', $permission)){?>
<div class="row">
	<div class="col-md-12">
		<div class="d-item-wrapper">
			<div class="d-item-title">
				<h3>নতুন দ্রব্যাদির চাহিদা পত্র</h3>
			</div>
			<div class="d-item-content">
				
                <?php 
                    $requisition_list = $db->getdata('select * from invoice where type = "req" order by id DESC LIMIT 0, 10');
                ?>

				<div class="table-responsive">
					<table class="table table-striped table-bordered text-center align-middle mb-0">
						<thead class="thead-custom">
							<tr>
							    <th>#</th>
						    	<th>চাহিদার তারিখ</th>
							    <th>চাহিদার আইডি</th>
								<th>চাহিদাকারীর নাম</th>
								<th>পণ্যের নাম</th>
								<th>পণ্য কোড</th>
								<th>অবস্থা</th>
								<?php if($db->is_stock()){?>
								<th>অনুমোদনের পরিমাণ</th>
								<?php }else{?>
								<th>চাহিদার পরিমাণ</th>
								<?php }?>
							</tr>
						</thead>
						<tbody>
						    <?php
						        $sql = '
                                select a.*,b.name,b.product_code,b.unit_type_id from invoice_details a
                                left join product b on b.id = a.product_id
                                where a.type = "req" and b.type = 1';
                                if($db->is_stock()){
                                    $sql .= ' and a.status in(3,1)';
                                }else{
                                    if(!$db->is_super()){
                                        $sql .= ' and a.staff_id ='.$db->uid();
                                    }elseif($db->is_super()){
                                        $sql .= ' and a.status != 0';
                                    }
                                }

                                $sql .= ' order by a.id DESC LIMIT 0, 10';

                                $ar = $db->getdata($sql);
						        $perm_his = $tem_his = '';
						        $i = 1;
						        foreach($ar as $v) :
						            $staff = $db->getonecol('name','staff','id',$v['staff_id']);
						            $unit = $db->getonecol('name','unit_type','id',$v['unit_type_id']);
					
						            echo '<tr>
            							    <td>'.$db->en_bn($i).'</td>
            							    <td>'.$db->setdate($v['cdate']).'</td>
            								<td>'.$db->get_req_id($v['cus_id'],$v['financial_year']).'</td>
            								<td>'.$staff.'</td>
            								<td>'.$v['name'].'</td>
            								<td>'.$v['product_code'].'</td>
            								<td>'.$db->req_status($v['status']).'</td>';
            								if($db->is_stock()){
            								    echo '<td>'.$db->en_bn($v['app_qty']).' '.$unit.'</td>';
            								}else{
            								    echo '<td>'.$db->en_bn($v['qty']).' '.$unit.'</td>';
            								}
            								
            							echo '</tr>';
            						$i++;
						            
						            ?>
    							
    							<?php  endforeach; ?>
						</tbody>
					</table>
				</div>



			</div>
		</div>
	</div>
</div>
<?php }?>







<script>
function show_req_inv(id){
$('#add_modal').modal('show')
post_ajax('control.php',{invoice_id:id},'#show_data')

}
</script>