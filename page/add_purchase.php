<?php
$db->invoice('ক্রয়','pur');
$date = $db->cdate();
$id  = $invoice_history = $party_id = $party_name = $method = $image = '';
$charge = $less = 0;
if(isset($_GET['id'])){
    foreach($db->getdata('select * from invoice where id ='.$_GET['id'].' and type = "pur"') as $v){
        $party_id = $v['party_id'];
        $id = $v['id'];
        $party_name = $v['party_name'];
        $method = $v['method'];
        $charge = $v['charge'];
        $less = $v['less'];
        $image = $v['image'];
        $date = $v['cdate'];
        $invoice_history = $db->getdata('select * from invoice_details where invoice_id ='.$id);
    }
}



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
        		<h3>ক্রয় যোগ করুন</h3>
        		<!--<div class="d-item-tools">-->
        		<!--    <a href="manage_requisition" class="btn btn-info btn-sm">ক্রয় তালিকা</a>-->
        		<!--</div>-->
        	</div>
        	<div class="d-item-content">
        	    <form method="post" enctype="multipart/form-data">
        	        <div class="row">
        	            <div class="col-md-2">
                    	    <select class="form-control product_type" onchange="get_pro_list()">
                    	        <option value="">পণ্যের ধরন</option>
                    	        <option value="perm">স্থায়ী</option>
                    	        <option value="temp">অস্থায়ী</option>
                    	    </select>
            	        </div>
            	        <div style="position: relative;" class="col-md-3">
                    	    <input type="text" autofocus="on" class="form-control" id="search_pro" placeholder="Search Product" >
                    	    <div class="show_pro_sug" id="show_pro_sug" style="width: 100%;max-height: 335px;overflow: hidden auto;"></div>
            	        </div>
            	        
            	        
            	        <div class="col-md-3">
                    	    <input type="text" name="party_name" value="<?php echo $party_name;?>" required placeholder="সরবরাহকারীর নাম লিখুন" class="form-control">
            	        </div>
            	        
            	        <div class="col-md-2">
                    	   <input type="date" class="form-control" name="cdate" value="<?php echo $date;?>" onchange="get_finance(this.value)"/>
            	        </div>
            	        <div class="col-md-2">
                    	    <input type="text" name="method" list="method" required value="<?php echo $method;?>" placeholder="ক্রয় প্রক্রিয়া" class="form-control">
                    	    <datalist id="method">
                              <?php
                              foreach($db->method() as $v){
                                  echo '<option value="'.$v.'">';
                              }
                              ?>
                            </datalist>
            	        </div>
            	        <?php
            	        $finance = '';
            	        foreach($db->getdata('select * from financial_year where id ='.$db->get_financial_id($date)) as $v){
            	            $finance = date('Y', strtotime($v['start'])).'-'.date('Y', strtotime($v['end']));
            	        }
            	        ?>
            	        <div class="col-md-3" style="display:none">
                    	   <input type="text" readonly class="form-control show_financial_year" value="<?php echo $finance;?>" />
            	        </div>
            	        
            	        <script>
            	            function get_finance(v){
            	                $.get("control.php?get_finance="+v, function(data, status){
                                    $('.show_financial_year').val(data)
                                });
            	            }
            	        </script>
            	        
            	        
            	        
        	        </div>
        	    
        	    <table class="table table-bordered text-center align-middle mt-2">
        	        <thead class="thead-custom">
        	            <tr>
        	                <th width="20">#</th>
        	                <th> দ্রব্যের নাম</th>
        	               
        	                <th>রেজিস্ট্রারের পৃষ্ঠা নং</th>
        	                <th style="width:80px;"> ইউনিট </th>
        	                <th style="width:120px;">পরিমাণ</th>
        	                <th style="width:120px;">একক মূল্য</th>
        	                <th style="width:90px;">মোট</th>
        	                <th style="width:95px;">বর্তমান স্টক</th>
        	                <th>মন্তব্য</th>
        	                <th width="40">অপসন</th>
        	                
        	            </tr>
        	        </thead>
        	        <tbody id="show_product_row">
        	            <?php
        	            $sl = $tot_qty = $tot_amount = 0;
        	            if($invoice_history != ''){
        	                
        	                foreach($invoice_history as $v){
        	                    $sl++;
        	                    $name = $unit = '';
        	                    foreach($db->getdata('select * from product where id ='.$v['product_id']) as $row){
        	                        $name = $row['name'].' ('.$row['product_code'].')';
        	                        $unit = $db->getonecol('name','unit_type','id',$row['unit_type_id']);
        	                    }
        	                    echo '<tr data-un="'.$sl.'" id="pro_'.$sl.'">
                                    <td scope="row"></td>
                                    <td class="text-left">
                                        <input type="hidden" value="'.$v['id'].'" name="did[]">
                                        <input type="hidden" value="'.$v['product_id'].'" name="product_id[]">'.$name.'
                                    </td>
                                    
                                    <td>
                                        <input type="text" name="register_number[]" value="'.$v['register_number'].'" class="form-control register_number" required>
                                    </td>
                                    <td class="unit">'.$unit.'</td>
                                    
                                    <td>
                                        <input type="number" name="qty[]" onkeyup="calaculation()" required="" value="'.$v['qty'].'" class="form-control qty">
                                    </td>
                                    <td>
                                        <input type="text" name="rate[]" onkeyup="calaculation()" value="'.$v['rate'].'" class="form-control rate">
                                    </td>
                                    
                                    <td class="subtotal">'.($v['amount']).'</td>
                                   
                                    <td class="stock"> <span></span></td>
                                    
                                    <td>
                                        <input type="text" name="note[]" value="'.$v['note'].'" class="form-control note">
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm" onclick="$(this).parent().parent().remove()"><i class="fa fa-trash"></i></button>
                                    </td>
                                </tr>';
                                $tot_qty += $v['qty'];
                                $tot_amount += $v['amount'];
        	                }
        	                
        	            }
        	            
        	            ?>
        	        </tbody>
        	        <tfoot>
        	            <tr>
        	                <th colspan="5">
        	                    <div class="text-right">
        	                        <span>সর্ব মোট:</span>
        	                    </div>
        	                </th>
        	                <th class="text-center tot_qty"><?php echo $tot_qty;?></th>
        	                <th class="text-center"></th>
        	                <th class="text-center tot_amount"><?php echo $tot_amount;?></th>
        	                <th></th>
        	            </tr>
        	            
        	            <tr style="">
        	                <th class="text-right" colspan="5">আপলোড ফাইল</th>
        	                <th class="text-center" colspan="5">
        	                    <input style="padding-bottom:32px" type="hidden" class="form-control" name="image" value="<?php echo $image;?>">
        	                    <input style="padding-bottom:32px" type="file" class="form-control" name="image">
        	                </th>
        	            </tr>
        	            <tr style="display:none;">
        	                <th class="text-right" colspan="6">ছাড় (টাকায়)</th>
        	                <th class="text-center"><input onkeyup="calaculation()" type="text" value="<?php echo $less;?>" class="form-control less" placeholder="ছাড় (টাকায়)" name="less"></th>
        	                <th class="text-center total_after_discount"><?php echo $total_after_discount = $tot_amount-$less;?></th>
        	                <th class="text-center"></th>
        	                <th></th>
        	            </tr>
        	            <tr style="display:none;">
        	                <th class="text-right" colspan="6">অন্যান্য চার্জ</th>
        	                <th class="text-center"><input onkeyup="calaculation()" type="text" value="<?php echo $charge;?>" class="form-control charge" placeholder="অন্যান্য চার্জ" name="charge"></th>
        	                <th class="text-center total_after_charge"><?php echo $total_after_discount+$charge;?></th>
        	                <th class="text-center"></th>
        	                <th></th>
        	            </tr>
        	            
        	           
        	         
        	        </tfoot>
        	    </table>
        	    <button type="submit" class="btn btn-success btn-md text-center" onclick="return ca()" value="<?php echo $id;?>" name="btn" style="margin: 0 auto;display: block;">Save</button>
        	    </form>
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
        pro_type = $('.product_type').val()
        if (keyCode != 40 && keyCode != 38) {
            post_ajax('control.php',{pro_search:pro_search,pro_type:pro_type},'#show_pro_sug')
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
        title = $(e).text()
        pro_id = $(e).attr('data-id')
        unit = $(e).attr('data-unit')
        un = 0;
            $('#show_product_row tr').each(function() {
              var value = parseFloat($(this).attr('data-un'));
              un = (value > un) ? value : un;
            });
        
            un = un+1;
            
            var data = `
            <tr data-un="`+un+`" id="pro_`+un+`">
            <td scope="row"></td>
            <td class="text-left">
                <input type="hidden" class="pro_id_`+pro_id+`" value="`+pro_id+`" name="product_id[]">`+title+`
            </td>
            
            <td>
                <input type="text" name="register_number[]" class="form-control register_number" required>
            </td>
            <td class="unit">`+unit+`</td>
            <td>
                <input type="number" autocomplete="off" name="qty[]" onkeyup="calaculation()" required class="form-control qty">
            </td>
            <td>
                <input type="text" autocomplete="off" name="rate[]" onkeyup="calaculation()" class="form-control rate">
            </td>
            <td class="subtotal"></td>
            <td class="stock"></td>
            <td>
                <input type="text" name="note[]" class="form-control note">
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm" onclick="$(this).parent().parent().remove()"><i class="fa fa-trash"></i></button>
            </td>
        </tr>`;
        
        dup_len = $('.pro_id_'+pro_id).length
        if(!dup_len){
            $('#show_product_row').append(data);
            $('#search_pro').val('').focus()
            
            post_ajax('control.php',{pro_stock:pro_id},'#pro_'+un+' .stock')
            
        }else{
            $('#search_pro').focus()
            toastr.error('এই পণ্য ইতিমধ্যে যোগ করা হয়েছে')
        }
        
    }
    
    
    function calaculation(){
        total_qty = 0;
        tot_amount = 0;
        $("#show_product_row tr").each(function(){
            un = $(this).attr('data-un')
            qty = Number($('#pro_'+un+' .qty').val())
            rate = Number($('#pro_'+un+' .rate').val())
            sub = qty * rate;

            tot_amount += sub;
            total_qty += qty;
            sub = sub.toFixed()
            $('#pro_'+un+' .subtotal').text(sub)

        });
        $('.tot_qty').text(total_qty.toFixed())
        $('.tot_amount').text(tot_amount.toFixed())
        
        less = Number($('.less').val())
        
        total_after_discount = tot_amount - less;
        $('.total_after_discount').text(total_after_discount)
        
        charge = Number($('.charge').val())
        total_after_charge = total_after_discount + charge;
        $('.total_after_charge').text(total_after_charge)
        
        
    }
    
    
    

    
    
</script>