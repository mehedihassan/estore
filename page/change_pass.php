<?php
$db->change_pass('pass');
?>


<div class="row">
    <div class="col-md-12">
        <div class="d-item-wrapper">
        	<div class="d-item-title">
        		<h3>পাসওয়ার্ড এবং ছবি  পরিবর্তন করুন</h3>
        	</div>
        	<div class="d-item-content" style="padding-bottom:20px">
        	
        		<form method="post">
        		    <div class="row" style="margin-bottom:20px">
        		        <div class="col-md-3">
        		            <label>পুরাতন পাসওয়ার্ড <span class="red">*</span></label>
        		            <input type="password" class="form-control"  name="old_pass" placeholder="পুরাতন পাসওয়ার্ড">
        		        </div>
        		        
        		        <div class="col-md-3">
        		            <label>নতুন পাসওয়ার্ড <span class="red">*</span></label>
        		            <input onkeyup="" type="password" class="form-control"  name="new_pass" placeholder="নতুন পাসওয়ার্ড">
        		        </div>
        		        
        		        <div class="col-md-3">
        		            <label>পাসওয়ার্ড নিশ্চিত করুন <span class="red">*</span></label>
        		            <input onkeyup="confirm_pass()" type="password" class="form-control"  name="confirm" placeholder="পাসওয়ার্ড নিশ্চিত করুন">
        		            <span class="red not_match" style="font-size:9px;"></span>
        		        </div>
        		        
        		        <div class="col-md-2 text-center">
        		            <button type="submit" name="btn" class="btn btn-success" style="margin-top: 27px;">পরিবর্তন করুন</button>
        		        </div>
        		        
        		        
        		    </div>
        		</form>
        		
        		<form method="post" enctype="multipart/form-data">
        		    <div class="row" style="margin-bottom:20px">
        		        <div class="col-md-3">
        		            <label>ছবি আপলোড </label>
        		            <input type="file" name="img" value="" class="form-control">
        		        </div>
        		        
        		        <div class="col-md-2 text-center">
        		            <button type="submit" name="btn_up" class="btn btn-success" style="margin-top: 27px;">আপলোড</button>
        		        </div>
        		        
        		        
        		    </div>
        		</form>
        	</div>
        </div>
    </div>
</div>

<script>
    function confirm_pass(){
        new_pass = $('[name="new_pass"]').val()
        confirmp = $('[name="confirm"]').val()
        if(confirmp != new_pass){
            $('.not_match').text('নতুন পাসওয়ার্ড এবং নিশ্চিত পাসওয়ার্ড মেলে না ')
        }else{
            $('.not_match').text('')
        }
    }
</script>
