<style type="text/css">
  .table-bordered.table-fixed td, .table-bordered.table-fixed th {
    padding: 5px 12px;
}
</style>

<?php
$db->product_transfer('product_transfer');
?>
    
<style>
.transfer h4 {
    font-size: 20px;
    font-weight: 600;
}
.stat_info th{
    text-align:left !important;
}
</style>
  <?php

  ?>
  <div class="row transfer">
    <div class="col-md-12">
        
        
        <div class="d-item-wrapper">
        	<div class="d-item-title">
        		<h3>পণ্য স্থানান্তর </h3>
        		
        	</div>
        	<div class="d-item-content">
        	    <form method="post">
        	    <input type="hidden" name="details_id" class="details_id">
        	    <input type="hidden" name="index" class="index">
        		<div class="row">
                    <div class="col-md-12 text-center">
                      <p class="message"></p>
                    </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label>বিতরণের কোড নম্বর	</label>
                      <input type="text" class="form-control deliver_code" name="deliver_code" required placeholder="বিতরণের কোড নম্বর">
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label>পণ্যের নাম	</label>
                      <input type="text" class="form-control pro_name" name="pro_name" readonly required placeholder="পণ্যের নাম">
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label>শাখা নির্বাচন করুন</label>
                      <select class="form-control select2" name="section_id" onchange="show_staff(this.value)">
					    <option value="">শাখা নির্বাচন করুন</option>
					    <?php
					    foreach($db->getdata('select * from section') as $v){
                            echo '<option value="'.$v['id'].'">'.$v['name'].'</option>';
                        }
                        ?>
					</select>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label>কর্মচারী নির্বাচন করুন *</label>
                      <select name="staff_id" id="staff_id" class="form-control select2 staff_id" required>
                        <option value="0">কর্মচারী নির্বাচন করুন </option>
                        
                      </select>
                    </div>
                  </div>
                  
                  <div class="col-md-12 text-center">
                      <button type="submit" name="btn" class="btn btn-success">সেভ</button>
                  </div>
                  </form>
                  
                </div>
                
               
        
        
        
        
    </div>
</div>
<script>
  $(".deliver_code").focusout(function(){
    $.get("control.php?get_data_deliver_code="+$(this).val(), function(data, status){
      json = JSON.parse(data)
      if(json.status){
         $('.index').val(json.index) 
         $('.pro_name').val(json.pro_name) 
         $('.details_id').val(json.details_id) 
         $('.message').html('')
      }else{
          $('.index').val('') 
          $('.pro_name').val('') 
          $('.details_id').val('') 
          $('.message').html('<span style="color: red;font-weight: bold;">খুজে পাওয়া যায় নি</span>')
      }
      
    });
  });
    function show_staff(v){
    $.post("control.php",{log_section_id: v},function(data, status){
        $('.staff_id').html(data)
    });
}

</script>





