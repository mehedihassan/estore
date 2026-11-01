<?php
require "main.php";
require_once("qrcode.php");




function get_qr_date($data){
    $qr = new QRCode();
    $qr->setErrorCorrectLevel(QR_ERROR_CORRECT_LEVEL_L);
    $qr->setTypeNumber(12);
    $t = "
    চাহিদাকারীর নাম : ".$data['name']."
    বিতরনের তারিখ : ".$data['bdate'];
    if($data['pur_date'] != ''){
       $t .= "
    ক্রয়ের তারিখ : ".$data['pur_date'];
     
    }
    if($data['pur_date'] != ''){
       $t .= "
    মূল্য : ".$data['pur_price'];
     
    }
    
    $t .= "
    পণ্যের নাম : ".$data['pro_name']."
    ধরন : ".$data['cat'];
   $qr->addData($t);
   $qr->make();
   $qr->printSVG();
    
}



if(isset($_POST['show_qr_code'])){
     foreach($db->getdata('select * from invoice_details where id ='.$_POST['show_qr_code']) as $v){
     foreach($db->getdata('select * from invoice where id ='.$v['invoice_id']) as $row){
        $product_name = $db->getonecol('name', 'product', 'id', $v['product_id']);
        $product_code = $db->getonecol('product_code', 'product', 'id', $v['product_id']);
        $cat_id = $db->getonecol('category_id', 'product', 'id', $v['product_id']);
        $name = $db->getonecol('name', 'staff', 'id', $v['staff_id']);
        $cat = $db->getonecol('name', 'category', 'id', $cat_id);
        $section = $db->getonecol('name', 'section', 'id', $v['section_id']);
        $pur_date = $db->getonecol('cdate', 'invoice', 'id', $v['ref']);
        $pur_price = '';
        if($pur_date != ''){
            $pur_date = $db->setdate($pur_date);
            foreach($db->getdata('select * from invoice_details where product_id ='.$v['product_id'].' and invoice_id ='.$v['invoice_id']) as $in){
                $pur_price = $db->en_bn($in['rate']);
            }
        }
        $bdate = '';
        if($row['delivered_date'] != ''){
            $bdate = $db->setdate($row['delivered_date']);
        }
        
        
    echo '
<style>
#print_qr_area svg {
    margin: 0 auto !important;
    display: block;
    width: 114px;
    height: 114px;
}
.qr_img {
    border: 1px solid #ddd;
    margin-bottom: 9px !important;
    padding: 4px 0;
    border-radius: 5px;
}
</style>
        <div class="modal-header">
            <h5 class="modal-title" id="add_modalLabel">কিউআর কোড ম্যানেজমেন্ট</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
        </div>
            <div class="modal-body" id="print_qr_area">';
            
            
            for ($i=1; $i <= $v['app_qty']; $i++) { 
            $bcode = $product_code.'-'.$db->get_digit($i).'/'.$v['financial_year'];
                echo '<div style="width: 200px;margin: 0 auto;" class="qr_img">
                    <img src="image/logo.png" style="display:block;width: 50px;margin: 0 auto;">
                    <p style="margin: 0;text-align: center;font-weight: bold;font-size: 12px;">শিক্ষা মন্ত্রণালয়</p>
                    <p style="margin: 0;text-align: center;font-weight: bold;font-size: 12px;">কারিগরি ও মাদ্রাসা শিক্ষা বিভাগ</p>';
                    $data = ['name'=>$name,'bdate'=>$bdate,'pro_name'=>$product_name,'code'=>$product_code,'cat'=>$cat,'bcode'=>$bcode,'pur_date'=>$pur_date,'pur_price'=>$pur_price];
                    get_qr_date($data);
                    echo '
                      <p style="margin: 0;text-align: center;font-size: 12px;color: black;padding-top: 5px;">পণ্যের নাম : '.$product_name.'</p>
                      <p style="margin: 0;text-align: center;font-size: 12px;color: black;">'.$bcode.'</p>
                      <p style="margin: 0;text-align: center;font-size: 12px;color: black;">'.$section.'</p>
                
                </div>';
            }
                
                
                
            echo '</div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">বন্ধ</button>
                <button type="submit" class="btn btn-primary" name="btn" onclick="printPageArea(\'print_qr_area\',\'body{margin:10px 25px 10px 15px !important}\')">প্রিন্ট</button>
            </div>
      ';
}
}
}