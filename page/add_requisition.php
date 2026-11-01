<?php
$db->invoice('চাহিদা','req');

$id  = $requsition_history = '';
if(isset($_GET['id'])){
    foreach($db->getdata('select * from invoice where id ='.$_GET['id'].' and type = "req"') as $v){
        $id = $v['id'];
        $requsition_history = $db->getdata('select * from invoice_details where invoice_id ='.$id);
    }
}


if(empty(@$pro_type)){
    if($url[0] == 'temp_add_requisition'){
        $pro_type = 'temp';
    }else{
        $pro_type = 'perm';
    }
}


if($pro_type == 'temp'){
    $title = 'অস্থায়ী পণ্যের ';
    $ptype = 2;
}else{
    $title = 'স্থায়ী পণ্যের ';
    $ptype = 1;
}



$category_list = $db->getdata('
select a.category_id as cat_id,b.name as cat_name from product a 
inner join category b on b.id = a.category_id

where a.type = '.$ptype.'
group by a.category_id
');
?>

<style>
.show_pro_sug .pro_list {
    color: black;
    text-align: left;
    padding: 8px 10px 5px 10px;
    display: block;
    border-bottom: 1px solid #ddd;
}

.show_pro_sug .pro_list:hover {
    background:#5e72e4;
    color:#fff;
}
.show_pro_sug {
    width: 100%;
    position: absolute;
    background: #fff;
    box-shadow: 0px 1px 5px 1px rgb(1 1 1 / 10%);
    border-radius: 6px;
}
#show_product_row input{
    text-align:center;
}
</style>



<div class="row">
    <div class="col-md-12">
        <div class="d-item-wrapper">
        	<div class="d-item-title">
        		<h3><?php echo $title;?>চাহিদা যোগ করুন
        		
        		
        		<span style="font-size:14px;" class="badge rounded-pill bg-danger text-light px-2 py-1">[বিঃদ্রঃ তালিকা বহির্ভূত কোন পণ্যের জন্য আলাদা চাহিদাপত্র দাখিল করতে হবে]</span>
        		 </h3>
        		
        		
        		
        		<!--<div class="d-item-tools">-->
        		<!--    <a href="manage_requisition" class="btn btn-info btn-sm"><?php echo $title;?>চাহিদা তালিকা</a>-->
        		<!--</div>-->
        	</div>
        	<div class="d-item-content"> 
        	    <?php if($db->aut('select * from invoice where pro_type = "'.$pro_type.'" and type = "req" and received_id = 0 and staff_id ='.$db->get_user_id()) && !isset($_GET['id'])){?>
        	        <h2 style="font-size: 20px;text-align: center;color: #dc3545ed;font-weight: bold;margin: 0;">আপনার আবেদনকৃত চাহিদাটি  এখনো গ্রহণ  করেন নি</h2>
        	    <?php }elseif($db->aut('select * from invoice where pro_type = "'.$pro_type.'" and cdate between "'.date('Y-m-01').'" and "'.date('Y-m-d').'" and type = "req" and received_id = 1 and staff_id ='.$db->get_user_id()) && !isset($_GET['id'])){?>
        	        <h2 style="font-size: 20px;text-align: center;color: #dc3545ed;font-weight: bold;margin: 0;">আপনি এই মাসে একটি চাহিদা গ্রহন করেছেন</h2>
        	    <?php }else{?>
        	    <div style="display:flex;justify-content: left;align-items: center;">
        	    <div style="max-width: 300px;float:left;width: 100%;margin-right:10px;margin-top:10px;" class="cus_cat_search">
        	        <select class="form-control select2" id="req_category_id" onchange="get_pro_list(1)">
        	            <option value="">সকল  ধরন</option>
    			        <?php
	                    foreach($category_list as $v){
	                        echo '<option value="'.$v['cat_id'].'">'.$v['cat_name'].'</option>';
	                    }
	                    
	                    ?>
        	        </select>
        	    </div>
        	    <div style="max-width: 400px;position: relative;float: left;width: 100%;margin-top:10px;" class="cus_cat_search">
            	    <input type="text" autofocus="on" class="form-control" id="search_pro" placeholder="Search Product" >
            	    <div class="show_pro_sug" id="show_pro_sug" style="width: 100%;max-height: 335px;overflow: hidden auto;display:none"></div>
        	    </div>
        	    
        	    
        	    <div style="margin-top:10px;float: left;width: 100%;">
        	        <?php if(!$db->is_super()){?>
            	        <!--<button type="button" class="btn btn-success cus_add_btn" style="margin-left: 12px;font-size: 14px;" onclick="add_pro('custome')">ধরন ও আইটেমের তালিকায় পণ্য  অন্তর্ভুক্ত না থাকলে</button>-->
            	    <?php }else{?>  
            	        <!--<button type="button" class="btn btn-success cus_add_btn" style="margin-left: 12px;font-size: 14px;" data-toggle="modal" href="#add_modal" onclick="post_ajax('control.php',{get_product:1,id:''},'#show_data')">পণ্য যোগ করুন</button>-->
        	        <?php }?>
        	    </div>
        	    
        	    
        	    </div>
        	    <form method="post">
        	        <input type="hidden" name="pro_type" value="<?php echo $pro_type;?>" >
        	    <table class="table table-bordered text-center align-middle mt-2">
        	        <thead class="thead-custom">
        	            <tr>
        	                <th width="77">ক্রমিক নং</th>
        	                <th>চাহিদাকৃত দ্রব্যের নাম</th>

        	                <th width="170">চাহিদার পরিমান</th>
        	                <th width="170">সর্বশেষ গ্রহণের তারিখ</th>
        	                <th width="170">সর্বশেষ গ্রহণের পরিমান</th>
        	                <th style="width:95px;display:none">বর্তমান স্টক</th>
        	                <th>মন্তব্য</th>
        	                <th width="100">অপসন</th>
        	            </tr>
        	        </thead>
        	        <tbody id="show_product_row">
        	            <?php
        	            $sl = $tot_qty = 0;
        	            if($requsition_history != ''){
        	                
        	                foreach($requsition_history as $v){
        	                    $sl++;
        	                    $name = '';
        	                    foreach($db->getdata('select * from product where id ='.$v['product_id']) as $row){
        	                        $name = $row['name'].' ('.$row['product_code'].')';
        	                    }
        	                    echo '<tr data-un="'.$sl.'" id="pro_'.$sl.'">
                                    <td scope="row"></td>
                                    <td class="text-left">';
                                    if($v['product_id'] == 0){
                                        $cuscls = '';
                                        echo '<input style="text-align:left;" type="text" class="form-control cus_product_name" value="'.$v['product_name'].'" name="product_name[]" placeholder="পণ্যের নাম লিখুন">';
                                    }else{
                                        $cuscls = 'cus_product_id';
                                    }
                                    echo '<input type="hidden" class="'.$cuscls.'" value="'.$v['id'].'" name="did[]">
                                        <input type="hidden" value="'.$v['product_id'].'" name="product_id[]">'.$name.'
                                    </td>
                                    <td>
                                        <input type="text" name="qty[]" onkeyup="calaculation()" value="'.$v['qty'].'" required="" class="form-control qty">
                                    </td>
                                    <td class="last_date">'.$v['last_date'].'<input type="hidden" name="last_date[]" value="'.$v['last_date'].'"></td>
                                    <td class="last_qty">'.$v['last_qty'].'<input type="hidden" name="last_qty[]" value="'.$v['last_qty'].'"></td>
                                   
                                    <td class="stock" style="display:none"> <span></span></td>
                                    <td>
                                        <input type="text" name="note[]" value="'.$v['note'].'" class="form-control note">
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm" onclick="$(this).parent().parent().remove()"><i class="fa fa-trash"></i></button>
                                    </td>
                                </tr>';
                                $tot_qty += $v['qty'];
        	                }
        	                
        	            }
        	            
        	            ?>
        	        </tbody>
        	        <tfoot>
        	            <tr>
        	                <th colspan="2">
        	                    <div class="text-center">
        	                        <span>সর্ব মোট:</span>
        	                    </div>
        	                </th>
        	                <th class="text-center tot_qty"><?php echo $tot_qty;?></th>
        	                <th></th>
        	            </tr>
        	           
        	         
        	        </tfoot>
        	    </table>
        	    <button type="submit" class="btn btn-success btn-md text-center" value="<?php echo $id;?>" onclick="return ca()" name="btn" style="margin: 0 auto;display: block;">Send</button>
        	    </form>
        	    
        	    <?php }?>
        	</div>
        </div>
    </div>
    
</div>


<script type="text/javascript">
    document.getElementById('search_pro').addEventListener("keyup", function(e) {
        get_pro_list(e.keyCode)
    });
    
    
    
    function get_pro_list(keyCode){
        $('#show_pro_sug').show()
        pro_search = $('#search_pro').val()
        cat = $('#req_category_id').val()
        if (keyCode != 40 && keyCode != 38) {
            post_ajax('control.php',{pro_cat:cat,pro_search:pro_search,pro_type:'<?php echo $pro_type;?>'},'#show_pro_sug')
        }
    }
    
     window.onclick = function(event) {
      if (!event.target.matches('#search_pro')) {
        $('#show_pro_sug').hide()
      }else{
          $('#show_pro_sug').show()
      }
      
    }   

    function removeRow(row_sn){
        $('#row_'+row_sn).remove();
    }
    
    function add_pro(e){
        if(e == 'custome'){
            title = '';
            pro_id = '';
        }else{
            title = $(e).text()
            pro_id = $(e).attr('data-id')
        }
        
        
        
            un = 0;
            $('#show_product_row tr').each(function() {
              var value = parseFloat($(this).attr('data-un'));
              un = (value > un) ? value : un;
            });
        
            un = un+1;
            
            var data = `
            <tr data-un="`+un+`" id="pro_`+un+`" class="product_id_`+pro_id+`">
            <td scope="row"></td>
            <td class="text-left">`;
            
            
            if(e == 'custome'){
                data += `<input style="text-align:left;" type="text" value="" class="form-control cus_product_name" required name="product_name[]" placeholder="পণ্যের নাম লিখুন">`;
                data += `<input type="hidden" value="0" class="form-control" name="product_id[]">`;
            }else{
                data += `<input type="hidden" value="`+pro_id+`" class="form-control cus_product_id" name="product_id[]">`+title;
            }
            data += `</td>
            <td>
                <input type="text" name="qty[]" onkeyup="calaculation()" required class="form-control qty">
            </td>
            <td class="last_date"></td>
            <td class="last_qty"></td>
            
            <td class="stock" style="display:none"> <span></span></td>
            
            <td>
                <input type="text" name="note[]" class="form-control note">
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm" onclick="$(this).parent().parent().remove()"><i class="fa fa-trash"></i></button>
            </td>
        </tr>`;
        
        dup_len = $('.product_id_'+pro_id).length
        if(pro_id == 0){
            
        }
        if($('.cus_product_name').length || pro_id == 0){
            $('.cus_cat_search').hide()
        }else{
            $('.cus_add_btn').hide()
        }
        if(!dup_len || pro_id == 0){
            
            
            if(!$('.cus_product_name').length && pro_id > 0){
                $('#show_product_row').append(data);
            }else if(pro_id == 0 && !$('.cus_product_id').length){
                $('#show_product_row').append(data);
            }else{
                toastr.error('ভিন্ন চাহিদাপত্র দাখিল করতে হবে')
            }
            
            $('#search_pro').val('').focus()
            
            $.post('control.php',{req_product_id:pro_id},function(data, status){
                if(data != ''){
                    obj = JSON.parse(data)
                    $('#pro_'+un+' .last_date').html(obj.last_date+'<input type="hidden" name="last_date[]" value="'+obj.last_date+'">')
                    $('#pro_'+un+' .last_qty').html(obj.qty+'<input type="hidden" name="last_qty[]" value="'+obj.qty+'">')
                    $('#pro_'+un+' .stock').html(obj.stock)
                }
                
            });
            
            
        }else{
            $('#search_pro').focus()
            toastr.error('এই পণ্য ইতিমধ্যে যোগ করা হয়েছে')
        }
        
    }
    
    
    function calaculation(){
        total_qty = 0;
        $(".qty").each(function(){
            total_qty += Number($(this).val())
        });
        $('.tot_qty').text(total_qty)
    }
    
    
    $(document).on('keyup', '.qty', function () {
        $q = $(this).val()
        $uq = $q.replace('০','0').replace('১','1').replace('২','2').replace('৩','3').replace('৪','4').replace('৫','5').replace('৬','6').replace('৭','7').replace('৮','8').replace('৯','9')
        $(this).val($uq)
    })

    
    
</script>