<?php
$db->company_setting('company_setting');
$value = $db->getdata('select * from company_setting where id = 1')[0];
$title = 'কোম্পানি সেটিংস';
?>


<div class="row">
    <div class="col-md-12">
        <div class="d-item-wrapper">
        	<div class="d-item-title">
        		<h3><?php echo $title;?> </h3>
        		<div class="d-item-tools">
        		</div>
        	</div>
        	<div class="d-item-content">
        	    <form method="post">
        		<div class="row">
        		    <div class="col-md-4">
        		        <div class="form-group">
        		            <label>কোম্পানির নাম</label>
        		            <input type="text" name="company_name" value="<?php echo $value['company_name'];?>" class="form-control" placeholder="কোম্পানির নাম">
        		        </div>
        		    </div>
        		    <div class="col-md-4">
        		        <div class="form-group">
        		            <label>কোম্পানির নম্বর</label>
        		            <input type="text" name="number" value="<?php echo $value['number'];?>" class="form-control" placeholder="কোম্পানির নম্বর">
        		        </div>
        		    </div>
        		    <div class="col-md-4">
        		        <div class="form-group">
        		            <label>ইমেইল ঠিকানা</label>
        		            <input type="text" name="email" value="<?php echo $value['email'];?>" class="form-control" placeholder="ইমেইল ঠিকানা">
        		        </div>
        		    </div>
        		    
        		    <div class="col-md-12">
        		        <div class="form-group">
        		            <label>ঠিকানা</label>
        		            <textarea class="form-control" name="address" placeholder="ঠিকানা"><?php echo $value['address'];?></textarea>
        		        </div>
        		    </div>
        		    <div class="col-md-12">
        		        <button type="submit" name="btn" class="btn btn-success">সেভ</button>
        		    </div>
        		</div>
        		
        	</div>
        	</form>
        </div>
    </div>
</div>

<script>
    function data_save(id=''){
        $('#add_modal').modal('show')
        post_ajax('control.php',{get_type_content:1,id:id},'#show_data')
        
    }
</script>
