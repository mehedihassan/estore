<?php
require "main.php";


if(isset($_GET['get_pro_code'],$_GET['pr_type'])){
    foreach($db->getdata('select max(sl) as msl from product where category_id ='.$_GET['get_pro_code']) as $v){
        $sl = $v['msl']+1;
    }
    if($sl > 0){}else{
        $sl = '1';
    }
    $type = $_GET['pr_type'];
    if($type == 1){
        $code = 'TMED';$catt = 'perm';
    }else{
        $code = 'TMED-TP';$catt = 'temp';
    }
    
    $pcode = '';
    if($_GET['get_pro_code'] > 0){
        $pcode = $code.'/'.$db->get_digit($_GET['get_pro_code']).'-'.$db->get_digit($sl);
    }
    $cats = '<option value="">ক্যাটাগরি নির্বাচন করুন</option>';
    //echo 'select * from category where type = "'.$catt.'"';
    foreach($db->getdata('select * from category where type = "'.$catt.'"') as $v){
        $cats .= '<option value="'.$v['id'].'">'.$v['name'].'</option>';
    }
    
    echo json_encode(['cat'=>$cats,'code'=>$pcode]);
   
}


if(isset($_GET['get_finance'])){
    $fid = $db->get_financial_id($_GET['get_finance']);
    foreach($db->getdata('select * from financial_year where id ='.$fid) as $v){
        echo date('Y', strtotime($v['start'])).'-'.date('Y', strtotime($v['end']));
    }
}


if(isset($_POST['req_product_id'])){
    $produdct_id = $_POST['req_product_id'];
    $stock = $db->get_stock($produdct_id);
    $sql = 'select * from invoice_details where type = "req" and product_id ='.$produdct_id;
    $sql .= ' and status = 1 and staff_id ='.$db->uid();
    $sql .= ' order by id desc limit 0,1';
    $qty = $date = '';
    foreach($db->getdata($sql) as $v){
        $date = $v['cdate'];
        $qty = $v['app_qty'];
    }
    echo json_encode(['last_date'=>$date,'qty'=>$qty,'stock'=>$stock]);
    
}


if(isset($_POST['log_section_id'])){
    foreach($db->getdata('select * from staff where section_id ='.$_POST['log_section_id'].' and status = 1 order by sl asc') as $v){
        echo '<option value="'.$v['id'].'">'.$v['name'].' ('.$v['id_no'].')</option>';
    }
}



if(isset($_POST['get_type_content'])){
    $id = $name = '';
    $acar = [];
    if(isset($_POST['id'])){
        foreach($db->getdata('select * from type where id ='.$_POST['id']) as $v){
            $id = $v['id'];
            $name = $v['name'];
            $acar = explode(',',$v['access']);
        }
    }
    echo '
        <div class="modal-header">
            <h5 class="modal-title" id="add_modalLabel">টাইপ ম্যানেজমেন্ট</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <style>
        .cus_check{
            height: 20px;width: 20px;margin-top: 5px;display: block;margin: 0 auto;
        }
       .access_perm .custom-control-label::before {
            top: .1rem;
       }
       .access_perm .custom-control-label::after {
            top: .1rem;
        }
        </style>
        <form method="post">
            <div class="modal-body">
                <div class="form-group">
                    <label for="type_name">টাইপ এর নাম <span class="red">*</span></label>
                    <input type="text" name="name" value="'.$name.'" class="form-control" required placeholder="টাইপ এর নাম">
                </div>
                
                <div class="form-group access_perm">
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                        <th colspan="5" class="text-center" style="position:relative">
                        Access Permission
                  
                        <div class="custom-control custom-checkbox" style="position:absolute;right: 8px;top: 8px;">
                            <input type="checkbox" class="custom-control-input" id="allcheck" onclick="check_all(this)">
                            <label class="custom-control-label font-weight-normal" for="allcheck">Select All</label>
                        </div>
                        <script>
                        function check_all(e){
                            $(\'.perm_list input[type="checkbox"]\').prop(\'checked\', e.checked);
                        }
                        </script>
                        </th>
                            <tr>
                            <th>Page Name</th>
                            <th>Add</th>
                            <th>View</th>
                            <th>Edit</th>
                            <th>Delete</th>
                            </tr>
                            </thead>
                            <tbody class="perm_list">
                            ';
                        $i = 1;
                        foreach($db->left_menu() as $key => $v){
                            echo '<tr>
                            <th class="text-left" colspan="2">'.$v['name'].'</th>
                            <th class="text-center"><input type="checkbox" ';
                            if(in_array($v['url'],$acar)){
                                echo 'checked';
                            }
                            echo ' class="cus_check" name="access[]" value="'.$v['url'].'"/></th>
                            <th class="text-center"></th>
                            <th class="text-center"></th>
                            </tr>
                            
                            ';
                            if(isset($v['others'])){
                                foreach($v['others'] as $rkey=> $rv){
                                    echo '<tr>
                                    <td colspan="3" style="padding-left: 25px;">'.$rv.'
                                    
                                    
                                    <div style="display: flex;width: 138px;float: right;">
                                    <input ';
                                    
                                    if(in_array($rkey.'_tem',$acar)){
                                        echo 'checked';
                                    }
                                    echo ' type="checkbox" class="cus_check" name="access[]" value="'.$rkey.'_tem"> অস্থায়ী
                                    <input ';
                                    
                                    if(in_array($rkey.'_perm',$acar)){
                                        echo 'checked';
                                    }
                                    echo ' type="checkbox" class="cus_check" name="access[]" value="'.$rkey.'_perm"> স্থায়ী
                                    </div>
                                    </td>
                                    <td></td>
                                    <td></td>
                                    </tr>';
                                }
                            }
                            
                            
                            
                            
                            if(isset($v['sub_menu'])){
                                foreach($v['sub_menu'] as $key1 => $sv){
                                 echo '<tr>
                                <th class="text-left" style="padding-left: 25px;">'.$key1.'</th>
                                <th class="text-center">';
                                if(isset($v['option'][$sv])){
                                    $a_av = $v['option'][$sv];
                                    if(in_array('add', $a_av)){
                                       echo '<input type="checkbox" ';
                                        if(in_array($sv.'_add',$acar)){
                                            echo 'checked';
                                        }
                                        echo ' class="cus_check" name="access[]" value="'.$sv.'_add"/>'; 
                                    }
                                }
                                echo '</th>
                                
                                <th class="text-center">';
                                if(isset($v['option'][$sv])){
                                    $a_av = $v['option'][$sv];
                                    if(in_array('view', $a_av)){
                                       echo '<input type="checkbox" ';
                                        if(in_array($sv.'_view',$acar)){
                                            echo 'checked';
                                        }
                                        echo ' class="cus_check" name="access[]" value="'.$sv.'_view"/>'; 
                                    }
                                }
                                echo '</th>
                                
                                
                                <th class="text-center">';
                                if(isset($v['option'][$sv])){
                                    $a_av = $v['option'][$sv];
                                    if(in_array('edit', $a_av)){
                                       echo '<input type="checkbox" ';
                                        if(in_array($sv.'_edit',$acar)){
                                            echo 'checked';
                                        }
                                        echo ' class="cus_check" name="access[]" value="'.$sv.'_edit"/>'; 
                                    }
                                }
                                echo '</th>
                                
                                
                                <th class="text-center">';
                                if(isset($v['option'][$sv])){
                                    $a_av = $v['option'][$sv];
                                    if(in_array('delete', $a_av)){
                                       echo '<input type="checkbox" ';
                                        if(in_array($sv.'_delete',$acar)){
                                            echo 'checked';
                                        }
                                        echo ' class="cus_check" name="access[]" value="'.$sv.'_delete"/>'; 
                                    }
                                }
                                echo '</th>
                                                  
                                </tr>
                                
                                
                                
                                
                                ';
                                
                                
                                
                                
                                
                                
                                
                                }
                            }
                        }
                            
                        echo '</tbody>
                    
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">বন্ধ</button>
                <button type="submit" class="btn btn-primary" value="'.$id.'" name="btn">সেভ</button>
            </div>
        </form>
      ';
}






if(isset($_POST['get_section'])){
    $id = $name = $address = $designation = '';
    if(isset($_POST['id'])){
        foreach($db->getdata('select * from section where id ='.$_POST['id']) as $v){
            $id = $v['id'];
            $name = $v['name'];
            $address = $v['address'];
            $designation = $v['designation'];
        }
    }
    echo '
        <div class="modal-header">
            <h5 class="modal-title" id="add_modalLabel">শাখা ম্যানেজমেন্ট</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form method="post">
            <div class="modal-body">
                <div class="form-group">
                    <label for="type_name">শাখা এর নাম <span class="red">*</span></label>
                    <input type="text" name="name" value="'.$name.'" class="form-control" required placeholder="শাখা এর নাম">
                </div>

                <div class="form-group">
                    <label for="type_name">শাখা এর ঠিকানা </label>
                    <input type="text" name="address" value="'.$address.'" class="form-control" placeholder="শাখা এর ঠিকানা">
                </div>
                <div class="form-group">
                    <label for="type_name">পদবি<span class="red">*</span></label>
                    <select class="form-control select2" name="designation[]" multiple>';
                        $sql = 'select * from designation where id > 0 order by name';
                        $dar = explode(',', $designation);
                        foreach($db->getdata($sql) as $v){
                            echo '<option ';
                            if (in_array($v['id'], $dar)) {
                                echo 'selected';
                            }

                            echo ' value="'.$v['id'].'">'.$v['name'].'</option>';
                        }
                    echo '</select>
                </div>
            </div>
            <script>
            setTimeout(function(){ 
            $(\'.select2\').select2();
            }, 400);

            
            </script>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">বন্ধ</button>
                <button type="submit" class="btn btn-primary" value="'.$id.'" name="btn">সেভ</button>
            </div>
        </form>
      ';
}


if(isset($_POST['get_room'])){
    $id = $name = $room_no = $section_id = '';
    if(isset($_POST['id'])){
        foreach($db->getdata('select * from room where id ='.$_POST['id']) as $v){
            $id = $v['id'];
            $name = $v['name'];
            $room_no = $v['room_no'];
            $section_id = $v['section_id'];
        }
    }
    echo '
        <div class="modal-header">
            <h5 class="modal-title" id="add_modalLabel">কক্ষ ম্যানেজমেন্ট</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form method="post">
            <div class="modal-body">
                <div class="form-group">
                    <label for="type_name">শাখা <span class="red">*</span></label>
                    <select name="section_id" class="form-control chosen" required>';
                    echo '<option value="">শাখা নির্বাচন করুন</option>';
                    $sql = 'select * from section where id > 0';
                    if($db->is_section()){
                        if($section_id == ''){
                            $section_id = $db->is_section();
                        }
                        
                        $sql .= ' and id ='.$db->is_section();
                    }
                    $sql .= ' order by name';
                    foreach($db->getdata($sql) as $v){
                        echo '<option ';
                        if($section_id == $v['id']){
                            echo 'selected';
                        }
                        echo ' value="'.$v['id'].'">'.$v['name'].'</option>';
                    }
                    
                    echo '</select>
                </div>

                <div class="form-group">
                    <label for="type_name">কক্ষ নং  <span class="red">*</span></label>
                    <input type="text" name="room_no" value="'.$room_no.'" class="form-control" required placeholder="কক্ষ নং">
                </div>
            </div>
            <script>
            setTimeout(function(){ 
            $(\'.chosen\').chosen();
            }, 400);
            </script>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">বন্ধ</button>
                <button type="submit" class="btn btn-primary" value="'.$id.'" name="btn">সেভ</button>
            </div>
        </form>
      ';
}




if(isset($_POST['designation'])){
    $title = 'পদবী';
    $id = $name = '';
    if(isset($_POST['id'])){
        foreach($db->getdata('select * from designation where id ='.$_POST['id']) as $v){
            $id = $v['id'];
            $name = $v['name'];
        }
    }
    echo '
        <div class="modal-header">
            <h5 class="modal-title" id="add_modalLabel">'.$title.' ম্যানেজমেন্ট</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form method="post">
            <div class="modal-body">
                <div class="form-group">
                    <label for="type_name">'.$title.' এর নাম <span class="red">*</span></label>
                    <input type="text" name="name" value="'.$name.'" class="form-control" required placeholder="'.$title.' এর নাম">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">বন্ধ</button>
                <button type="submit" class="btn btn-primary" value="'.$id.'" name="btn">সেভ</button>
            </div>
        </form>
      ';
}



if(isset($_POST['get_staff'])){
    $title = 'কর্মচারী';
    $id = $name = $id_no = $number = $email = $address = $section_id = $designation_id = $type_id = $password = $section_manager = $room_id = $img = $status = '';
    $id_no = 1001;
    foreach($db->getdata('select max(id_no) as idn from staff') as $v){
        if($v['idn'] != ''){
            $id_no = $v['idn']+1;
        }
    }
    
    
    if(isset($_POST['id'])){
        foreach($db->getdata('select * from staff where id ='.$_POST['id']) as $v){
            $id = $v['id'];
            $name = $v['name'];
            $id_no = $v['id_no'];
            $number = $v['number'];
            $email = $v['email'];
            $address = $v['address'];
            $section_id = $v['section_id'];
            $designation_id = $v['designation_id'];
            $type_id = $v['type_id'];
            $password = $v['password'];
            $room_id = $v['room_id'];
            $section_manager = $v['section_manager'];
            $img = $v['img'];
            $status = $v['status'];
        }
    }
    echo '
        <div class="modal-header">
            <h5 class="modal-title" id="add_modalLabel">'.$title.' ম্যানেজমেন্ট</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form method="post" enctype="multipart/form-data">
            <div class="modal-body">
            
            <div class="row">
            
            <div class="col-md-8">
                <div class="form-group">
                    <label for="type_name">নাম <span class="red">*</span></label>
                    <input type="text" name="name" required value="'.$name.'" class="form-control" required placeholder="এর নাম">
                </div>
            </div>    
                
            <div class="col-md-4">     
                <div class="form-group">
                    <label for="type_name">আইডি নং </label>
                    <input type="text" name="id_no" value="'.$id_no.'" class="form-control" placeholder="আইডি নং">
                </div>
            </div>    
                
            <div class="col-md-6">     
                <div class="form-group">
                    <label for="type_name"> নাম্বার	</label>
                    <input type="text" name="number" value="'.$number.'" class="form-control" placeholder="নাম্বার">
                </div>
            </div>    
                
            <div class="col-md-6">     
                <div class="form-group">
                    <label for="type_name"> ই-মেইল	</label>
                    <input type="email" name="email" value="'.$email.'" class="form-control" placeholder="ই-মেইল">
                </div>
            </div>    
                
            <div class="col-md-12">     
                <div class="form-group">
                    <label for="type_name">ঠিকানা </label>
                    <input type="text" name="address" value="'.$address.'" class="form-control" placeholder="ঠিকানা">
                </div>
            </div>    
                
            
            
            
            
            <div class="col-md-6">
                <div class="form-group">
                    <label for="type_name">শাখা <span class="red">*</span></label>
                    <select name="section_id" class="form-control chosen" required onchange="get_desig_room(this.value)">';
                    echo '<option value="">শাখা নির্বাচন করুন</option>';
                    $sql = 'select * from section where id > 0';
                    if($db->is_section() and !$db->is_super()){
                        if($section_id == ''){
                            $section_id = $db->is_section();
                        }
                        
                        $sql .= ' and id ='.$db->is_section();
                    }
                    $sql .= '';
                    foreach($db->getdata($sql) as $v){
                        echo '<option ';
                        if($section_id == $v['id']){
                            echo 'selected';
                        }
                        echo ' value="'.$v['id'].'">'.$v['name'].'</option>';
                    }
                    
                    echo '</select>
                </div>
            </div>';
            $designation_name = 'পদবী  নির্বাচন করুন';
            if($designation_id != ''){
                $designation_name = $db->getonecol('name','designation','id',$designation_id);
            }
            $room_no = 'কক্ষ  নির্বাচন করুন';
            if($room_id != ''){
                $room_no = $db->getonecol('room_no','room','id',$room_id);
            }
            echo '<div class="col-md-6">
                <div class="form-group">
                    <label for="type_name">পদবী <span class="red">*</span></label>
                    <select name="designation_id" id="pup_designation_id" class="form-control " required>';
                    echo '<option value="'.$designation_id.'">'.$designation_name.'</option>';
                    
                    if($section_id != ''){
                        $desig_ids = $db->getonecol('designation','section','id',$section_id);
                        $sql = 'select * from designation where id > 0 and id in('.$desig_ids.')';
                        foreach($db->getdata($sql) as $v){
                            echo '<option value="'.$v['id'].'">'.$v['name'].'</option>';
                        }
                    }
                    
                    echo '</select>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="form-group">
                    <label for="type_name">কক্ষ  <span class="red">*</span></label>
                    <select name="room_id" id="pup_room_id" class="form-control " required>';
                   echo '<option value="">কক্ষ  নির্বাচন করুন</option>';
                    if($section_id != ''){
                        foreach($db->getdata('select * from room where section_id ='.$section_id) as $v){
                            echo '<option ';
                            if($room_id == $v['id']){
                                echo 'selected';
                            }
                            echo ' value="'.$v['id'].'">'.$v['room_no'].'</option>';
                        }
                    }
                    
                    echo '</select>
                </div>
            </div>
            
            
            
            <div class="col-md-6">
                <div class="form-group">
                    <label for="type_name">টাইপ  <span class="red">*</span></label>
                    <select name="type_id" class="form-control type_id_area" required>';
                    echo '<option value="">টাইপ  নির্বাচন করুন</option>';
                    $sql = 'select * from type where id > 0';
                    if($type_id == ''){
                        if($db->is_section()){
                            if($type_id == ''){
                                $type_id = 2;
                            }
                            $sql .= ' and id =2';
                        }
                    }
                    $sql .= ' order by name';
                    foreach($db->getdata($sql) as $v){
                        echo '<option ';
                        if($type_id == $v['id']){
                            echo 'selected';
                        }
                        echo ' value="'.$v['id'].'">'.$v['name'].'</option>';
                    }
                    
                    echo '</select>
                </div>
            </div>
            
            
            
            
            
            <div class="col-md-6">     
                <div class="form-group">
                    <label for="type_name">পাসওয়ার্ড </label>
                    <input type="text" name="password" value="" class="form-control" placeholder="পাসওয়ার্ড">
                </div>
            </div> 
            
            <div class="col-md-6 section_manager_area" style="">    
                <label >শাখা পরিচালক </label>
                <div class="form-group" style="vertical-align: middle;border: 1px solid #ddd;padding: 7px 10px;border-radius: 3px;">
                    
                    <input ';
                    if($section_manager == 1){
                        echo 'checked';
                    }
                    echo ' type="checkbox" name="section_manager" value="1" id="section_manager" style="margin: 0;vertical-align: middle;height: 20px;width: 20px;">
                    <label for="section_manager" style="vertical-align: middle;margin: 0;font-size: 1rem;">শাখা পরিচালক</label>
                </div>
            </div> ';
            
            if($img != ''){
                echo '<input type="hidden" name="img" value="'.$img.'">';
            }
            echo '
            <div class="col-md-6">     
                <div class="form-group">
                    <label for="type_name">ছবি  আপলোড</label>
                    <input type="file" name="img" value="" class="form-control">
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="form-group">
                    <label for="status"> Status <span class="red">*</span></label>
                    <select name="status" class="form-control status" required>
                        <option value="1">Active</option>
                        <option value="0" ';
                        echo strlen($status)>0&&$status==0?'selected':'';
                        echo '>Inactive</option>
                    </select>
                </div>
            </div>
            
            
        <script>
        setTimeout(function(){ 
        $(\'.chosen\').chosen();
        $(\'.type_id_area\').change();
        }, 400);

        
        </script>
        
        <script>
            function get_desig_room(section){
                $.post("control.php",{get_desig_room: section},function(data, status){
                    obj = JSON.parse(data)
                    $(\'#pup_designation_id\').html(obj.desig)
                    $(\'#pup_room_id\').html(obj.room)
                });
            }
        </script>

            </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">বন্ধ</button>
                <button type="submit" class="btn btn-primary" value="'.$id.'" name="btn">সেভ</button>
            </div>
        </form>
      ';
}




if(isset($_POST['get_desig_room'])){
    $desig_ids = $db->getonecol('designation','section','id',$_POST['get_desig_room']);
    $desig_html = '<option value="">পদবী  নির্বাচন করুন</option>';
    foreach($db->getdata('select * from designation where id in('.$desig_ids.')') as $v){
        $desig_html .= '<option value="'.$v['id'].'">'.$v['name'].'</option>';
    }
    
    
    $room_html = '<option value="">কক্ষ  নির্বাচন করুন</option>';
    foreach($db->getdata('select * from room where section_id = '.$_POST['get_desig_room']) as $v){
        $room_html .= '<option value="'.$v['id'].'">'.$v['room_no'].'</option>';
    }
    
    echo json_encode(['desig'=>$desig_html,'room'=>$room_html]);
    
    
}



if(isset($_POST['get_unit_type'])){
    $title = 'ইউনিট টাইপ';
    $id = $name = '';
    if(isset($_POST['id'])){
        foreach($db->getdata('select * from unit_type where id ='.$_POST['id']) as $v){
            $id = $v['id'];
            $name = $v['name'];
        }
    }
    echo '
        <div class="modal-header">
            <h5 class="modal-title" id="add_modalLabel">'.$title.' ম্যানেজমেন্ট</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form method="post">
            <div class="modal-body">
                <div class="form-group">
                    <label for="type_name">'.$title.' এর নাম <span class="red">*</span></label>
                    <input type="text" name="name" value="'.$name.'" class="form-control" required placeholder="'.$title.' এর নাম">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">বন্ধ</button>
                <button type="submit" class="btn btn-primary" value="'.$id.'" name="btn">সেভ</button>
            </div>
        </form>
      ';
}




if(isset($_POST['get_category'])){
    $title = 'ক্যাটাগরি';
    $id = $name = $temp = $perm = '';
    if(isset($_POST['id'])){
        foreach($db->getdata('select * from category where id ='.$_POST['id']) as $v){
            $id = $v['id'];
            $name = $v['name'];
            if($v['type'] == 'perm'){
                $perm = 'selected';
            }elseif($v['type'] == 'temp'){
                $temp = 'selected';
            }
        }
    }
    echo '
        <div class="modal-header">
            <h5 class="modal-title" id="add_modalLabel">'.$title.' ম্যানেজমেন্ট</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form method="post">
            <div class="modal-body">
                <div class="form-group">
                    <label for="type_name">'.$title.' এর নাম <span class="red">*</span></label>
                    <input type="text" name="name" value="'.$name.'" class="form-control" required placeholder="'.$title.' এর নাম">
                </div>
                
                <div class="form-group">
	                <label for="product_type">সম্পদের ধরন <span class="red">*</span></label>
	                <select name="type" class="form-control" id="product_typecr" required="" onchange="get_pro_code(\'type\')">
	                    <option value="">পণ্যের ধরন নির্বাচন করুন</option>
	                    <option '.$perm.' value="perm">স্থায়ী</option>
	                    <option '.$temp.' value="temp">অস্থায়ী</option>
	                </select>
                </div>
            
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">বন্ধ</button>
                <button type="submit" class="btn btn-primary" value="'.$id.'" name="btn">সেভ</button>
            </div>
        </form>
      ';
}

if(isset($_POST['get_brand'])){
    $title = 'ব্র্যান্ড';
    $id = $name = '';
    if(isset($_POST['id'])){
        foreach($db->getdata('select * from brand where id ='.$_POST['id']) as $v){
            $id = $v['id'];
            $name = $v['name'];
        }
    }
    echo '
        <div class="modal-header">
            <h5 class="modal-title" id="add_modalLabel">'.$title.' ম্যানেজমেন্ট</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form method="post">
            <div class="modal-body">
                <div class="form-group">
                    <label for="type_name">'.$title.' এর নাম <span class="red">*</span></label>
                    <input type="text" name="name" value="'.$name.'" class="form-control" required placeholder="'.$title.' এর নাম">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">বন্ধ</button>
                <button type="submit" class="btn btn-primary" value="'.$id.'" name="btn">সেভ</button>
            </div>
        </form>
      ';
}






    
if(isset($_POST['pro_search'])){
    $cat = $db->rpv('pro_cat');
    $s = $_POST['pro_search'];
    $pro_type = $_POST['pro_type'];
    $sql = 'select *,length(name) as len from product where
    (name like "%'.$s.'%" or
    product_code like "%'.$s.'%")';
    if($pro_type == 'perm'){
        $sql .= ' and type = 1';
    }elseif($pro_type == 'temp'){
        $sql .= ' and type = 2';
    }
    if($cat > 0){
        $sql .= ' and category_id ='.$cat;
    }
    
    $sql .= ' order by len asc,name';
    foreach($db->getdata($sql) as $key => $v){
        $unit = $db->getonecol('name','unit_type','id',$v['unit_type_id']);
        $sl = $key+1;
        echo '<a href="#" onclick="add_pro(this)" data-id="'.$v['id'].'" data-code="'.$v['product_code'].'" data-unit="'.$unit.'" class="pro_list" id="cus'.$sl.'">'.$v['name'].'</a>';
    }
}





if(isset($_POST['pre_used'])){
    echo '<input type="hidden" name="pre_used[]" class="form-control">100 PCS';
}

if(isset($_POST['pro_stock'])){
    echo $db->get_stock($_POST['pro_stock']);
}



if(isset($_POST['pur_invoice_id'])){
    

    
    $id = $_POST['pur_invoice_id'];
    foreach($db->getdata('select * from invoice where id ='.$id) as $value){
        
    $name = $db->getonecol('name','party','id',$value['party_id']);
    $num = $db->getonecol('number','party','id',$value['party_id']);
    if($value['type'] == 'sales'){
        $inv_type = 'নিষ্পত্তি';
        $party_type = 'ক্রেতা';
    }else{
        $inv_type = 'ক্রয় ';
        $party_type = 'সরবরাহকারী';
        $name = $value['party_name'];
    }
        
    
    
    echo '
    <style>
    .modal-dialog {
        max-width: 650px;
    }
    </style>
    <div class="modal-header">
        <h5 class="modal-title" id="add_modalLabel">'.$inv_type.' ব্যবস্থাপনা </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
    </div>
        
        
    <div class="modal-body" id="print_area">    
    
    
    '.$db->head().'
    
    <h3 style="font-weight: bold;text-align: center;font-size: 18px;padding-top: 7px;margin-top:0;margin-bottom:0;">'.$inv_type.' চালান</h3>
    <h3 style="font-weight: bold;text-align: center;font-size: 18px;padding-top: 7px;margin-top:0">'.$inv_type.' আইডি : '.$value['cus_id'].'-'.$value['financial_year'].'</h3>
    
    <div style="margin-bottom:10px;overflow:hidden;">
    <div style="width:45%;float:left;">
        <p style="margin:0">'.$party_type.'  নাম : '.$name.'</p>';
        if($num != ''){
            echo '<p style="margin:0">মোবাইল নাম্বার : '.$num.'</p>';
        }
        
        
    echo '</div>
    
    <div style="width:45%;float:right;">
        <p style="margin:0;text-align:right;">তারিখ : '.$db->setdate($value['cdate']).'</p>';
        
        if($value['date'] != $value['update_date']){
            echo '<p style="margin:0;text-align:right;">আপডেটের তারিখ : '.$db->setdate($value['update_date']).'</p>';
        }

    echo '</div>
    </div>
    <table class="table table-bordered table-striped">
    
    <thead>
    <tr>
    <th>#</th>
    <th> দ্রব্যের নাম</th>
    <th>মন্তব্য</th>
    <th>রেজিস্ট্রারের পৃষ্ঠা নং</th>
    <th>পরিমাণ</th>
    <th>মূল্য</th>
    <th>সাব টোটাল</th>
    </tr>
    </thead>
    <tbody>';
    $tqty = $tamount = 0;
    foreach($db->getdata('select * from invoice_details where invoice_id ='.$id) as $key => $v){
        $name = $unit = '';
        foreach($db->getdata('select * from product where id ='.$v['product_id']) as $row){
            $name = $row['name'].' ('.$row['product_code'].')';
            $unit = $db->getonecol('name','unit_type','id',$row['unit_type_id']);
        }
        echo '<tr>
        <td  class="text-center">'.$db->en_bn($key+1).'</td>
        <td class="text-left">'.$name.'</td>
        <td class="text-center">'.$v['note'].'</td>
        <td class="text-center">'.$v['register_number'].'</td>
        <td class="text-center">'.$db->en_bn($v['qty']).' '.$unit.'</td>
        <td class="text-center">'.$v['rate'].'</td>
        <td class="text-right">'.$v['amount'].'</td>

        </tr>';
        $tqty += $v['qty'];
        $tamount += $v['amount'];
    }
    
    echo '
    <tr>
    <th class="text-right" colspan="4">মোট</th>
    <th>'.($tqty).'</th>
    <th></th>
    <th style="text-align:right !important">'.($tamount).'</th>
    </tr>';
    
    $after_dis = $tamount-$value['less'];
    if($value['less'] != 0){
        echo '
        <tr>
        <th class="text-right" colspan="5">ছাড় </th>
        <th>('.($value['less']).')</th>
        <th style="text-align:right !important">'.$after_dis.'</th>
        </tr>';
    }
    
    if($value['charge'] != 0){
        echo '
        <tr>
        <th class="text-right" colspan="5">চার্জ </th>
        <th>'.($value['charge']).'</th>
        <th style="text-align:right !important">'.($after_dis+$value['charge']).'</th>
        </tr>';
    }
    
    
    
    echo '</tbody>
    
    </table>
    
    
                
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">বন্ধ</button>
        <button type="submit" class="btn btn-primary"  name="btn" onclick="printPageArea(\'print_area\',\'body{margin:10px 25px 10px 15px !important}\')">প্রিন্ট</button>
    </div>
    
    ';
    
    
    
    }
    
    

}




if(isset($_POST['show_pro_cat_wise'])){
    $sql = 'select * from product where category_id ='.$_POST['show_pro_cat_wise'];
    echo '<option value="">পণ্য নির্বাচন করুন</option>';
    foreach($db->getdata($sql) as $v){
        echo '<option value="'.$v['id'].'">'.$v['name'].'</option>';
    }
}




if(isset($_POST['req_pro_connect']) and isset($_GET['invdid'])){
    echo '
        
            <div class="modal-header">
                <h5 class="modal-title" id="add_modalLabel">পণ্য ব্যবস্থাপনা </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body"> 
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
        	                <label for="product_type">সম্পদের ধরন <span class="red">*</span></label>
        	                <select name="type" class="form-control" id="product_typecr" required="" onchange="get_pro_code(\'type\')">
        	                    <option value="">পণ্যের ধরন নির্বাচন করুন</option>
        	                    <option value="1">স্থায়ী</option><option value="2">অস্থায়ী</option>&gt;
        	                </select>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
        	                <label for="category">ক্যাটাগরি<span class="red">*</span> </label>
        	                <select id="category_id" name="category_id" class="form-control select2" required="" onchange="show_pro_cat(this.value)">
        	                    <option value="">ক্যাটাগরি নির্বাচন করুন</option>
        	                </select>
        	            </div>
                    </div>
                    
                    <div class="col-md-12">
                        <div class="form-group">
        	                <label for="product_id">পণ্য<span class="red">*</span> </label>
        	                <select id="product_id_area" name="product_id" class="form-control chosen" required="" ">
        	                    <option value="">পণ্য নির্বাচন করুন</option>
        	                </select>
        	            </div>
                    </div>
                    
                  
                    
                    
                </div>
                
                
            </div>
            
            <div class="modal-footer">
               
                <button type="button" class="btn btn-secondary" data-dismiss="modal">বন্ধ</button>
                <button type="submit" class="btn btn-success" onclick="save_con()">সেভ</button>
            </div>

            ';
            ?>
            <script>
            function save_con(){
                product_id = $('#product_id_area').val()
                product_name = $('#product_id_area option:selected').text()
                if(product_id > 0){
                    
                    $.post("control.php?connect_save=1",{product_id:product_id,details_id:'<?php echo $_GET['invdid'];?>'},function(data, status){
                        json = JSON.parse(data)
                        if(json.status == 'suc'){
                            toastr.success('পণ্য সফলভাবে সমন্বয় হয়েছে')
                            $('.row_id_<?php echo $_GET['invdid'];?> .pro_name').html(product_name)
                            $('#add_modal1').modal('hide')
                            
                        }else{
                            toastr.error('পণ্য সমন্বয় ব্যর্থ হয়েছে')
                        }
                    });
                }else{
                   toastr.error('দয়া করে পণ্য নির্বাচন করুন')   
                }
            }
            function show_pro_cat(v){
                $.post("control.php",{show_pro_cat_wise:v,id:''},function(data, status){
                    $('#product_id_area').html(data)
                    $('.chosen').trigger('chosen:updated');
                });
            }
            $('.chosen').chosen();

                function get_pro_code(ty){
                    catid = $('#category_id').val()
                    type = $('#product_typecr').val()
                    $.get("control.php?pr_type="+type+"&get_pro_code="+catid, function(data, status){
                        obj = JSON.parse(data)
                        code = obj.code
                        if(ty == 'type'){
                            $('#category_id').html(obj.cat)
                        }
                        
                        $('#product_code').val(code.replace(/^\s+|\s+$/gm,''))
                    });
                
                    
                }
            </script>
            <?php
}


if(isset($_POST['product_id'],$_POST['details_id']) and isset($_GET['connect_save'])){
    if($_POST['details_id'] > 0 and $_POST['product_id'] > 0){
        if($db->qedit('invoice_details',['product_id'=>$_POST['product_id']],'id',$_POST['details_id'])){
            echo json_encode(['status'=>'suc']);
        }else{
            echo json_encode(['status'=>'fail']);
        }
    }
}



if(isset($_POST['req_pro_create_type']) and isset($_GET['invdid'])){
    echo '<div class="modal-header">
                <h5 class="modal-title" id="add_modalLabel">পণ্য ব্যবস্থাপনা </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body"> 
                <div class="row">
                    <div class="col-md-12 text-center">
                        <button class="btn btn-danger" onclick="post_ajax(\'control.php?invdid='.$_GET['invdid'].'\',{get_product:1,id:\'\'},\'#show_data1\')">নতুন পণ্য যোগ করুন</button>
                        <button class="btn btn-success" onclick="post_ajax(\'control.php?invdid='.$_GET['invdid'].'\',{req_pro_connect:1,id:\'\'},\'#show_data1\')">পণ্য সমন্বয় করুন</button>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">বন্ধ</button>
            </div>
            
            ';
}



if(isset($_POST['deliver_by_inch_req'])){
    
    
    
    $id = $_POST['deliver_by_inch_req'];
    foreach($db->getdata('select * from invoice where id ='.$id) as $value){
        
    $staff_name = $db->getonecol('name','staff','id',$value['staff_id']);
    $staff_id_no = $db->getonecol('id_no','staff','id',$value['staff_id']);
    $staff_num = $db->getonecol('number','staff','id',$value['staff_id']);
    
    $approve_by = $db->getonecol('name','staff','id',$value['approved_by']);
    $desig_id = $db->getonecol('designation_id','staff','id',$value['staff_id']);
    $desig = $db->getonecol('name','designation','id',$desig_id);
    $sec_name = $db->getonecol('name','section','id',$value['section_id']);
    echo '
    <style>
    #add_modal .modal-dialog {
        max-width: 900px;
    }
    </style>
    <div class="modal-header">
        <h5 class="modal-title" id="add_modalLabel">চাহিদা ব্যবস্থাপনা </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
    </div>
        
         <form method="post">
         <input type="hidden" name="inv_id" value="'.$value['id'].'">
    <div class="modal-body" id="print_area">    
    
    
    '.$db->head().'
    
    <h3 style="font-weight: normal;text-align: center;font-size: 18px;padding-top: 7px;margin-top:0;margin-bottom:0">চাহিদা পত্র </h3>
    <h3 style="font-weight: normal;text-align: center;font-size: 18px;padding-top: 7px;margin-top:0;margin-bottom:10px;">চাহিদা আইডি : '.$db->get_req_id($value['cus_id'],$value['financial_year']).'</h3>
   
    <div style="margin-bottom:10px;overflow:hidden;">
    <div style="width:45%;float:left;">
        <p style="margin:0">চাহিদাকারীর নাম : '.$staff_name.'</p>
        <p style="margin:0">পদবি : '.$desig.'</p>
        <p style="margin:0">শাখা : '.$sec_name.'</p>
        <p style="margin:0">মোবাইল নাম্বার : '.$staff_num.'</p>
    </div>
    
    <div style="width:45%;float:right;">
        <p style="margin:0;text-align:right;">তারিখ : '.$db->setdate($value['cdate']).'</p>';
        
        if($value['date'] != $value['update_date']){
            echo '<p style="margin:0;text-align:right;">আপডেটের তারিখ : '.$db->setdate($value['update_date']).'</p>';
        }
        
        if($approve_by != ''){
            echo '<p style="margin:0;text-align:right;">অনুমোদিত : '.$approve_by.'</p>';
        }
        
        if($value['status'] == 1){
            echo '<p style="margin:0;text-align:right;">অবস্থা :  <span style="    padding: 5px 8px 5px 8px;"  class="badge text-white  bg-success">অনুমোদিত</span></p>';
        }elseif($value['status'] == 0){
            echo '<p style="margin:0;text-align:right;">অবস্থা :  <span style="    padding: 5px 8px 5px 8px;"  class="badge text-white  bg-danger">বিচারাধীন</span></p>';
        }
    
    echo '</div>
    </div>
    
    <table class="table table-bordered table-striped">
    
    <thead>
    <tr>
    <th>#</th>
    <th>চাহিদা দ্রব্যের নাম</th>
   
    <th>পরিমাণ</th>
    <th style="width:100px;">চাহিদার বিপরীতে অনুমোদিত</th>
     <th>রেজিস্ট্রারের পৃষ্ঠা নং</th>
     <th>অনুমোদনকারীর মন্তব্য</th>
     <th>চাহিদাকারীর মন্তব্য</th>
     <th>ক্রয় তারিখ</th>
    </tr>
    </thead>
    <tbody>';
    foreach($db->getdata('select * from invoice_details where invoice_id ='.$id) as $key => $v){
        $name = $unit = '';
        foreach($db->getdata('select * from product where id ='.$v['product_id']) as $row){
            $name = $row['name'];
            $unit = $db->getonecol('name','unit_type','id',$row['unit_type_id']);
        }
        if($v['product_id'] == 0){
            $name = $v['product_name'];
            if($db->is_stock()){
                $name .= '<br><span class="hdprint" style="color: #132bd5c9;font-weight: bold;cursor: pointer;"><a data-toggle="modal" href="#add_modal1" onclick="post_ajax(\'control.php?invdid='.$v['id'].'\',{req_pro_create_type:1,id:\'\'},\'#show_data1\')" href="#"> তালিকায় যুক্ত করুন</a></span>';
            }
        }
        
        $bqty = $v['qty'] - $v['app_qty'];
        echo '<tr class="row_id_'.$v['id'].'">
        <td  class="text-center">'.$db->en_bn($key+1).'</td>
        <td class="pro_name">'.$name.'</td>
        
        <td class="text-center">'.$db->en_bn($v['qty']).' '.$unit.'</td>
        <td class="text-center"><input type="hidden" name="allids[]" class="form-control" value="'.$v['id'].'">'.$v['app_qty'].' '.$unit.'</td>
        <td><input type="text" name="register_number[]" class="form-control" value="'.$v['register_number'].'"></td>
        <td>'.$v['super_note'].'</td>
        <td>'.$v['note'].'</td>
        <td>
        <select class="form-control" name="ref[]">
            <option value="">নির্বাচন করুন</option>';
            foreach($db->getdata('select * from invoice_details where product_id ='.$v['product_id'].' and type = "pur" order by id DESC') as $row){
                echo '<option value="'.$row['invoice_id'].'">'.$db->setdate($row['cdate']).'</option>';
            }
        echo '</select>
        
        
        </td>
        
        </tr>';
    }
    
    echo '
    
  
    </tbody>
    
    </table>
    <label>মন্তব্য</label>
    <textarea class="form-control" name="note" placeholder="মন্তব্য"></textarea>
    
                
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">বন্ধ</button>
        <button type="submit" class="btn btn-primary"  name="deliver_req" onclick="return confirm(\'আপনি কি চূড়ান্ত অনুমোদন করতে ইচ্ছুক?\')">সেভ</button>
    </div>
    </form>
    
    ';
    
    
    
    }
    
    


}







if(isset($_POST['app_by_sup_req'])){
    
    
    $id = $_POST['app_by_sup_req'];
    foreach($db->getdata('select * from invoice where id ='.$id) as $value){
        
    $staff_name = $db->getonecol('name','staff','id',$value['staff_id']);
    $staff_id_no = $db->getonecol('id_no','staff','id',$value['staff_id']);
    $staff_num = $db->getonecol('number','staff','id',$value['staff_id']);
    
    $approve_by = $db->getonecol('name','staff','id',$value['approved_by']);
    $desig_id = $db->getonecol('designation_id','staff','id',$value['staff_id']);
    $desig = $db->getonecol('name','designation','id',$desig_id);
    $sec_name = $db->getonecol('name','section','id',$value['section_id']);
    echo '
    <style>
    .modal-dialog {
        max-width: 900px;
    }
    </style>
    <div class="modal-header">
        <h5 class="modal-title" id="add_modalLabel">চাহিদা ব্যবস্থাপনা </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
    </div>
        
         <form method="post">
         <input type="hidden" name="inv_id" value="'.$value['id'].'">
    <div class="modal-body" id="print_area">    
    
    
    '.$db->head().'
    
    <h3 style="font-weight: normal;text-align: center;font-size: 18px;padding-top: 7px;margin-top:0;margin-bottom:0">চাহিদা পত্র </h3>
    <h3 style="font-weight: normal;text-align: center;font-size: 18px;padding-top: 7px;margin-top:0;margin-bottom:10px;">চাহিদা আইডি : '.$db->get_req_id($value['cus_id'],$value['financial_year']).'</h3>
   
    <div style="margin-bottom:10px;overflow:hidden;">
    <div style="width:45%;float:left;">
        <p style="margin:0">চাহিদাকারীর নাম : '.$staff_name.'</p>
        <p style="margin:0">পদবি : '.$desig.'</p>
        <p style="margin:0">শাখা : '.$sec_name.'</p>
        <p style="margin:0">মোবাইল নাম্বার : '.$staff_num.'</p>
    </div>
    
    <div style="width:45%;float:right;">
        <p style="margin:0;text-align:right;">তারিখ : '.$db->setdate($value['cdate']).'</p>';
        
        if($value['date'] != $value['update_date']){
            echo '<p style="margin:0;text-align:right;">আপডেটের তারিখ : '.$db->setdate($value['update_date']).'</p>';
        }
        
        if($approve_by != ''){
            echo '<p style="margin:0;text-align:right;">অনুমোদিত : '.$approve_by.'</p>';
        }
        
        if($value['status'] == 1){
            echo '<p style="margin:0;text-align:right;">অবস্থা :  <span style="    padding: 5px 8px 5px 8px;"  class="badge text-white  bg-success">অনুমোদিত</span></p>';
        }elseif($value['status'] == 0){
            echo '<p style="margin:0;text-align:right;">অবস্থা :  <span style="    padding: 5px 8px 5px 8px;"  class="badge text-white  bg-danger">বিচারাধীন</span></p>';
        }
    
    echo '</div>
    </div>
    
    <table class="table table-bordered table-striped">
    
    <thead>
    <tr>
    <th>#</th>
    <th>চাহিদা দ্রব্যের নাম</th>
   
    <th>পরিমাণ</th>
    <th>সর্বশেষ গ্রহণের তারিখ</th>
    <th>সর্বশেষ গ্রহণের পরিমান</th>
    <th style="width:100px;background:#9eed2161;">চাহিদার বিপরীতে অনুমোদন</th>
    
    <th>বর্তমান স্টক</th>
    <th>ক্রয় করা প্রয়োজন</th>
    <th>অনুমোদনকারীর মন্তব্য</th>
    <th>চাহিদাকারীর মন্তব্য</th>
    </tr>
    </thead>
    <tbody>';
    foreach($db->getdata('select * from invoice_details where invoice_id ='.$id) as $key => $v){
        $name = $unit = $stock ='';
        if($v['product_id'] > 0){
            $stock = $db->get_stock($v['product_id']);
        }
        $need_pur_checked = '';
        if($stock <= 0){
            $need_pur_checked = 'checked';
        }
        
        foreach($db->getdata('select * from product where id ='.$v['product_id']) as $row){
            $name = $row['name'].' ('.$row['product_code'].')';
            $unit = $db->getonecol('name','unit_type','id',$row['unit_type_id']);
        }
        if($v['product_id'] == 0){
            $name = $v['product_name'];
        }
        $accept_date = $accept_qty= '';
        
        foreach($db->getdata('select * from invoice_details where staff_id ='.$v['staff_id'].' and product_id ='.$v['product_id'].' and status = 1 order by id DESC limit 0,1') as $row){
            $accept_date = $db->cdate($row['cdate']);
            $accept_qty = $row['app_qty'];
        }
        
        $bqty = $v['qty'] - $v['app_qty'];
        echo '<tr>
        <td  class="text-center">'.$db->en_bn($key+1).'</td>
        <td>'.$name.'</td>
        
        <td class="text-center">'.$db->en_bn($v['qty']).' '.$unit.'</td>
        <td>'.$accept_date.'</td>
        <td>'.$accept_qty.'</td>
        <td style="background:#9eed2161;">
        <input type="hidden" name="allids[]" class="form-control" value="'.$v['id'].'">
        <input type="number" name="app_qty[]" class="form-control" value="'.$bqty.'">
        </td>
        
        <td class="text-center">'.$db->en_bn($stock).'</td>
        <td class="text-center"><input type="checkbox" name="need_purchase'.$v['id'].'" '.$need_pur_checked.' value="1" style="width:30px;height:30px;"></td>
        <td><input type="text" name="super_note[]" class="form-control" value="'.$v['super_note'].'"></td>
        <td>'.$v['note'].'</td>
        </tr>';
    }
    
    echo '
    
    <tr style="display:none">
    <td class="text-right" style="font-weight:bold" colspan="7">ক্রয় আইডি</td>
    <td colspan="2"><input type="text" name="ref" class="form-control"></td>
    
    </tr>
    
    </tbody>
    
    </table>
    
    
                
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">বন্ধ</button>
        <button type="submit" class="btn btn-primary"  name="app_req" onclick="return confirm(\'আপনি কি চূড়ান্ত অনুমোদন করতে ইচ্ছুক?\')">সেভ</button>
    </div>
    </form>
    
    ';
    
    
    
    }
    
    

}






if(isset($_POST['invoice_id'])){
    $id = $_POST['invoice_id'];
    foreach($db->getdata('select * from invoice where id ='.$id) as $value){
        
    $staff_name = $db->getonecol('name','staff','id',$value['staff_id']);
    $staff_id_no = $db->getonecol('id_no','staff','id',$value['staff_id']);
    $staff_num = $db->getonecol('number','staff','id',$value['staff_id']);
    
    $approve_by = $db->getonecol('name','staff','id',$value['approved_by']);
    
    $desig_id = $db->getonecol('designation_id','staff','id',$value['staff_id']);
    $desig = $db->getonecol('name','designation','id',$desig_id);
    $sec_name = $db->getonecol('name','section','id',$value['section_id']);
    echo '
    <style>
    .modal-dialog {
        max-width: 650px;
    }
    </style>
    <div class="modal-header">
        <h5 class="modal-title" id="add_modalLabel">চাহিদা ব্যবস্থাপনা </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
    </div>
        
        
    <div class="modal-body" id="print_area">    
    
    
    '.$db->head().'
    
    <h3 style="font-weight:normal;text-align: center;font-size: 15px;padding-top: 7px;margin-top:0;margin-bottom:0">চাহিদা পত্র </h3>
    <h3 style="font-weight:normal;text-align: center;font-size: 15px;padding-top: 7px;margin-top:0;margin-bottom:10px;">চাহিদা আইডি : '.$db->get_req_id($value['cus_id'],$value['financial_year']).'</h3>
    
    <div style="margin-bottom:10px;overflow:hidden;">
    <div style="width:45%;float:left;">
        <p style="margin:0"><span>চাহিদাকারীর নাম :</span> '.$staff_name.'</p>
        <p style="margin:0"><span>পদবি :</span> '.$desig.'</p>
        <p style="margin:0">শাখা : '.$sec_name.'</p>
        <p style="margin:0"><span>মোবাইল নাম্বার :</span> '.$staff_num.'</p>
    </div>
    
    <div style="width:45%;float:right;">
        <p style="margin:0;text-align:right;">তারিখ : '.$db->setdate($value['cdate']).'</p>';
        
        if($value['date'] != $value['update_date']){
            echo '<p style="margin:0;text-align:right;">আপডেটের তারিখ : '.$db->setdate($value['update_date']).'</p>';
        }
        
        if($approve_by != ''){
            echo '<p style="margin:0;text-align:right;">অনুমোদিত : '.$approve_by.'</p>';
        }

        
        echo '<p style="margin:0;text-align:right;">অবস্থা : '.$db->req_status($value['status']).'</p>';
    
    echo '</div>
    </div>
    <table class="table table-bordered table-striped">
    
    <thead>
    <tr>
    <td style="text-align:center;">ক্রমিক নং</td>
    <td style="text-align:center;">চাহিদা দ্রব্যের নাম</td>
    <td style="text-align:center;">চাহিদার পরিমাণ</td>
    <td style="text-align:center;">সর্বশেষ গ্রহণের তারিখ</td>
    <td style="text-align:center;">সর্বশেষ গ্রহণের পরিমান</td>
    
    <td style="text-align:center;">চাহিদার বিপরীতে অনুমোদিত</td>
    <td style="text-align:center;">চাহিদাকারীর মন্তব্য</td>
    <td style="text-align:center;">অনুমোদনকারীর মন্তব্য</td>';
    if($value['status'] == '1'){
        echo '<td style="text-align:center;" class="hdprint">কিউআর কোড</td>';
    }
    echo '</tr>
    </thead>
    <tbody>';
    $tqty = 0;
    foreach($db->getdata('select * from invoice_details where invoice_id ='.$id) as $key => $v){
        $name = $unit = '';
        foreach($db->getdata('select * from product where id ='.$v['product_id']) as $row){
            $name = $row['name'].' ('.$row['product_code'].')';
            $unit = $db->getonecol('name','unit_type','id',$row['unit_type_id']);
        }
        
        $last_date = '';
        if($v['last_date'] != ''){
            $last_date = $db->setdate($v['last_date']);
        }
        if($v['product_id'] == 0){
            $name = $v['product_name'];
            
        }
        echo '<tr>
        <td  class="text-center">'.$db->en_bn($key+1).'</td>
        <td>'.$name.'</td>
        <td class="text-center">'.$db->en_bn($v['qty']).' '.$unit.'</td>
        <td class="text-center">'.$last_date.'</td>
        <td class="text-center">'.$v['last_qty'].'</td>
        <td class="text-center">'.$db->en_bn($v['app_qty']).' '.$unit.'</td>
        <td>';
        if($v['status'] == 0 && $v['need_purchase'] == 1){
            echo 'ক্রয় করতে হবে<br>';
        }
        echo $v['note'].'</td>';
        echo '<td>'.$v['super_note'].'</td>';
        if($value['status'] == '1'){
            echo '<td class="hdprint"><button onclick="show_bercode('.$v['id'].')" class="btn btn-success btn-sm" style="margin-right:5px">দেখুন</button></td>';
        }
        
        echo '</tr>';
        $tqty += $v['qty'];
    }
    
    echo '</tbody>
    <tfoot>
    <tr>
    <th colspan="2">মোট</th>
    <th style="text-align:center">'.$tqty.'</th>
    
    </tr>';
    if(!empty($value['delivery_note'])){
        
    
    echo '<tr>
    <th colspan="2">মন্তব্য</th>
    <th style="text-align:left!important" colspan="6">'.$value['delivery_note'].'</th>
    
    </tr>';
    }
    
    echo '</tfoot>
    </table>
    
    
                
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">বন্ধ</button>
        <button type="submit" class="btn btn-primary"  name="btn" onclick="printPageArea(\'print_area\',\'body{margin:10px 25px 10px 15px !important}\')">প্রিন্ট</button>
    </div>
    
    ';
    
    
    
    }
    
    
}


if(isset($_POST['get_party'])){
    $party_type = '';
    if(isset($_POST['party_type'])){
        $party_type = $_POST['party_type'];
    }
    $title = 'সরবরাহকারী';
    $id = $name = $number = $email = $address = '';
    if(isset($_POST['id'])){
        foreach($db->getdata('select * from party where id ='.$_POST['id']) as $v){
            $id = $v['id'];
            $name = $v['name'];
            $number = $v['number'];
            $email = $v['email'];
            $address = $v['address'];
            $party_type = $v['type'];
        }
    }
    echo '
        <div class="modal-header">
            <h5 class="modal-title" id="add_modalLabel">'.$title.' ম্যানেজমেন্ট</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form method="post">
            <input type="hidden" name="type" value="'.$party_type.'">
            <div class="modal-body">
            
                <div class="form-group">
                    <label for="type_name">নাম <span class="red">*</span></label>
                    <input type="text" name="name" required value="'.$name.'" class="form-control" required placeholder="এর নাম">
                </div>
                
            
                
                <div class="form-group">
                    <label for="type_name"> নাম্বার <span class="red">*</span>	</label>
                    <input type="text" name="number" required value="'.$number.'" class="form-control" required placeholder="নাম্বার">
                </div>
                
                <div class="form-group">
                    <label for="type_name"> ই-মেইল	</label>
                    <input type="email" name="email" value="'.$email.'" class="form-control" placeholder="ই-মেইল">
                </div>
                
                <div class="form-group">
                    <label for="type_name">ঠিকানা <span class="red">*</span></label>
                    <input type="text" name="address" required value="'.$address.'" class="form-control" required placeholder="ঠিকানা">
                </div>
                
               
                
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">বন্ধ</button>
                <button type="submit" class="btn btn-primary" value="'.$id.'" name="btn">সেভ</button>
            </div>
        </form>
      ';
}






if(isset($_POST['get_financial_year'])){
    $id = $start = $end = '';
    if(isset($_POST['id'])){
        foreach($db->getdata('select * from financial_year where id ='.$_POST['id']) as $v){
            $id = $v['id'];
            $start = $v['start'];
            $end = $v['end'];
        }
    }
    echo '
        <div class="modal-header">
            <h5 class="modal-title" id="add_modalLabel">অর্থ বছর ম্যানেজমেন্ট</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form method="post">
            <div class="modal-body">
                <div class="row">
                <div class="col-md-6">
                    <label for="type_name">অর্থ বছর শুরুর তারিখ <span class="red">*</span></label>
                    <input type="date" name="start" value="'.$start.'" class="form-control" required placeholder="অর্থ বছর শুরুর তারিখ">
                </div>

                <div class="col-md-6">
                    <label for="type_name">অর্থ বছর শেষ তারিখ <span class="red">*</span></label>
                    <input type="date" name="end" value="'.$end.'" class="form-control" required placeholder="অর্থ বছর শেষ তারিখ">
                </div>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">বন্ধ</button>
                <button type="submit" class="btn btn-primary" value="'.$id.'" name="btn">সেভ</button>
            </div>
        </form>
      ';
}













if(isset($_POST['get_product'])){

$title = 'পণ্য ';
$id = $name = $brand_id = $category_id = $product_code = $purchase_price = $unit_type_id = $type = $opening_stock = $min_stock = $id = '';
   
   
   
if(isset($_POST['rtype'])){
    $type = $_POST['rtype'];
}
$brand_list = $db->getdata('select * from brand order by name');
$sql = 'select * from category where id > 0';
if($type != ''){
    $sql .= ' and type = "'.str_replace([1,2],['perm','temp'],$type).'"';
}

$category_list = $db->getdata($sql);
$unit_list = $db->getdata('select * from unit_type order by name');

if(isset($_POST['id'])){
    foreach($db->getdata('select * from product where id ='.$_POST['id']) as $v){
        $id = $v['id'];
        $name = $v['name'];
        $brand_id = $v['brand_id'];
        $category_id = $v['category_id'];
        $product_code = $v['product_code'];
        $purchase_price = $v['purchase_price'];
        $unit_type_id = $v['unit_type_id'];
        $type = $v['type'];
        $opening_stock = $v['opening_stock'];
        $min_stock = $v['min_stock'];
    }
}
    
$invdid = '';
if(isset($_GET['invdid'])){
    $invdid = $_GET['invdid'];
}
    
    echo '
        <div class="modal-header">
            <h5 class="modal-title" id="add_modalLabel">'.$title.' ম্যানেজমেন্ট</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form method="post" enctype="multipart/form-data">
            <div class="modal-body body_propup">
            ';?>
            <?php if($invdid != ''){?>
	        <input type="hidden" name="invdid" class="cus_pro_dtid" value="<?php echo $invdid;?>">
	        <?php }?>
            <div class="row">
            <div class="col-md-6">
                <div class="form-group">
	                <label for="product_type">পণ্যের ধরন <span class="red">*</span></label></label>
	                <select name="type" class="form-control" id="product_typecr" required onchange="get_pro_code('type')">
	                    <option value="">পণ্যের ধরন নির্বাচন করুন</option>
	                    <?php
	                    foreach($db->product_type() as $key => $v){
	                        echo '<option ';
                            if($type == $key){
                                echo 'selected';
                            }
                            echo ' value="'.$key.'">'.$v.'</option>';
	                    }
	                    
	                    ?>>
	                </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
	                <label for="category">ক্যাটাগরি<span class="red">*</span> </label>
	                <select id="category_id" name="category_id" class="form-control select2" required onchange="get_pro_code('cat')">
	                    <option value="">ক্যাটাগরি নির্বাচন করুন</option>
	                    <?php
	                    $perm_cat = $temp_cat = '';
	                    foreach($category_list as $v){
	                        echo '<option ';
                            if($category_id == $v['id']){
                                echo 'selected';
                            }
                            echo ' value="'.$v['id'].'">'.$v['name'].'</option>';
	                    }
	                    
	                    ?>
	                </select>
	            </div>
            </div>
            <script>
                function get_pro_code(ty){
                    catid = $('#category_id').val()
                    type = $('#product_typecr').val()
                    $.get("control.php?pr_type="+type+"&get_pro_code="+catid, function(data, status){
                        obj = JSON.parse(data)
                        code = obj.code
                        if(ty == 'type'){
                            $('#category_id').html(obj.cat)
                        }
                        
                        $('#product_code').val(code.replace(/^\s+|\s+$/gm,''))
                    });
                
                    
                }
            </script>
            <div class="col-md-6">
                <div class="form-group">
	                <label for="brand">ব্র্যান্ড নির্বাচন করুন</label>
	                <select name="brand_id" class="form-control">
	                    <option value="">ব্র্যান্ড নির্বাচন করুন</option>
	                    <?php
	                    foreach($brand_list as $v){
	                        echo '<option ';
                            if($brand_id == $v['id']){
                                echo 'selected';
                            }
                            echo ' value="'.$v['id'].'">'.$v['name'].'</option>';
	                    }
	                    
	                    ?>
	                </select>
	            </div>
            </div>
            
            <div class="col-md-6">
                <div class="form-group">
	                <label for="unit_type">ইউনিট টাইপ <span class="red">*</span></label>
	                <select name="unit_type_id" class="form-control" required>
	                    <option value="">নির্বাচন করুন</option>
	                    <?php
	                    foreach($unit_list as $v){
	                        echo '<option ';
                            if($unit_type_id == $v['id']){
                                echo 'selected';
                            }
                            echo ' value="'.$v['id'].'">'.$v['name'].'</option>';
	                    }
	                    
	                    ?>
	                </select>
                </div>
          
            </div>
            <div class="col-md-12">
                <div class="form-group">
	                <label for="name">পণ্যের নাম<span class="red">*</span> </label>
	                <input name="name" value="<?php echo $name;?>" required class="form-control" id="name" placeholder="পণ্যের নাম">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
	                <label for="product_code">পণ্য কোড <span class="red">*</span></label>
	                <input name="product_code" value="<?php echo $product_code;?>" required class="form-control" id="product_code" placeholder="পণ্য কোড">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
	                <label for="purchase_price">ক্রয় মূল্য</label>
	                <input name="purchase_price" value="<?php echo $purchase_price;?>" class="form-control" id="purchase_price" placeholder="ক্রয় মূল্য">
                </div>
            </div>
            
            
            <div class="col-md-6">
                <div class="form-group">
	                <label for="opening_stock">প্রারম্ভিক স্টক </label>
	                <input name="opening_stock" value="<?php echo $opening_stock;?>" class="form-control" id="opening_stock" placeholder="প্রারম্ভিক স্টক">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
	                <label for="min_stock">ন্যূনতম স্টক</label>
	                <input name="min_stock" value="<?php echo $min_stock;?>" class="form-control" id="min_stock" placeholder="ন্যূনতম স্টক">
                </div>
            </div>
            
        </div>
            
            <?php echo '
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary md_close" data-dismiss="modal">বন্ধ</button>
                <button type="button" class="btn btn-primary pro_btn" value="'.$id.'" name="btn">সেভ</button>
            </div>
        </form>
      ';
      ?>
      <script>
                $(document).ready(function(){
                    $('.pro_btn').on('click',function(e){
                        type = $('.body_propup #product_typecr').val()
                        invid = '';
                        if($('.body_propup .cus_pro_dtid').length){
                            invid = $('.body_propup .cus_pro_dtid').val()
                        }
                        
                        
                        category_id = $('.body_propup [name="category_id"]').val()
                        brand_id = $('.body_propup [name="brand_id"]').val()
                        unit_type_id = $('.body_propup [name="unit_type_id"]').val()
                        name = $('.body_propup [name="name"]').val()
                        product_code = $('.body_propup [name="product_code"]').val()
                        purchase_price = $('.body_propup [name="purchase_price"]').val()
                        opening_stock = $('.body_propup [name="opening_stock"]').val()
                        min_stock = $('.body_propup [name="min_stock"]').val()
                        data = {type:type,category_id:category_id,brand_id:brand_id,unit_type_id:unit_type_id,name:name,product_code:product_code,invdid:invid,
                        purchase_price:purchase_price,opening_stock:opening_stock,opening_stock:opening_stock,min_stock:min_stock,btn:'<?php echo $id;?>'}
                        ok = 1;
                        if(name == '' || category_id == '' || unit_type_id == '' || type == '' || product_code == ''){
                            ok = 0
                            toastr.error('বাধ্যতামূলক ইনপুট খালি হওয়া উচিত নয়')
                        }
                        if(ok == 1){
                        $.post("control.php?create_pro=1",data,function(data, status){
                            obj = JSON.parse(data)
                            if(obj.status == 'suc'){
                               
                                htmld = `<div class="thank-you-pop">
        							<img src="image/Green-Round-Tick.png" alt="">
        							<h1>`+obj.msg+`</h1>
        							<h3 class="cupon-pop"><button type="button" class="btn btn-secondary md_close" data-dismiss="modal">বন্ধ</button></h3>
        							
         						</div>`;
         						$('.body_propup').html(htmld)
         						$('.modal-footer').hide()
                 			 	toastr.success(obj.msg)
                            }else{
                                toastr.error(obj.msg)
                            }
                        });
                        }
                    })
                })
            </script>
      <?php
}


if(isset($_GET['create_pro'])){
    $db->product('product');
}
if(isset($_GET['get_data_deliver_code'])){
    $code = $_GET['get_data_deliver_code'];
    //$sql = "SELECT *, json_extract(deliver_all_code, '$[2]') AS code FROM invoice_details where type =".$code;
    $ar = explode('-',$code);
    $index = $ar[2]-1;
    $sql = "SELECT * FROM invoice_details where json_extract(deliver_all_code, '$[".$index."]') = '".$code."'";

    $ar = ['status'=>0];
    if($db->aut($sql)){
        foreach($db->getdata($sql) as $v){
            $pro_name = $db->getonecol('name','product','id',$v['product_id']);
            $ar = [
              'index'=>$index,  
              'product_id'=>$v['product_id'],  
              'details_id'=>$v['id'],
              'pro_name'=>$pro_name,
              'status'=>1,
            ];
        }
    }
    echo json_encode($ar);
    
}






if(isset($_POST['get_cat_pro'])){
    $type = $_POST['get_cat_pro'];
    $cat_id = $_POST['cat_id'];
    $product_id = $_POST['product_id'];
    $rtype = $_POST['rttype'];
    
    $cats = '<option value="">সকল ধরন </option>';
    $products = '<option value="">সকল পণ্য </option>';
    if($rtype == 'type'){
    $sql = 'select * from category where id > 0';
    if($type != ''){
        $sql .= ' and type ="'.$type.'"';
    }
    foreach($db->getdata($sql) as $v){
        $cats .= '<option value="'.$v['id'].'">'.$v['name'].'</option>';
    }
    }
    
    
    $sql = 'select * from product where id > 0';
    
    if($type != ''){
        $sql .= ' and type = '.str_replace(['perm','temp'],[1,2],$type);
    }
    if($cat_id > 0){
        $sql .= ' and category_id ='.$cat_id;
    }

    foreach($db->getdata($sql) as $v){
        $products .= '<option value="'.$v['id'].'">'.$v['name'].' ('.$v['id_no'].')</option>';
    }
    
    echo json_encode(['cat'=>$cats,'pro'=>$products]);
}