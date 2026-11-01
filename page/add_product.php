<?php
$db->product('product');
$brand_list = $db->getdata('select * from brand order by name');
$category_list = $db->getdata('select * from category order by name');
$unit_list = $db->getdata('select * from unit_type order by name');

$id = $name = $brand_id = $category_id = $product_code = $purchase_price = $unit_type_id = $type = $opening_stock = $min_stock = $id = '';
if(isset($_GET['id'])){
    foreach($db->getdata('select * from product where id ='.$_GET['id']) as $v){
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

?>

<div class="row">
	<div class="col-md-12">
		<div class="d-item-wrapper">
			<div class="d-item-title">
				<h3>পণ্য যোগ করুন</h3>
				<div class="d-item-tools">
				    <a href="manage_product" class="btn btn-info btn-sm">পণ্য পরিচালনা করুন</a>
				</div>
			</div>
			<div class="d-item-content">
			    
			    
			    <form action="" method="POST" enctype="multipart/form-data">
			        <?php if($invdid != ''){?>
			        <input type="hidden" name="invdid" value="<?php echo $invdid;?>">
			        <?php }?>
			        <div class="row">
			            <div class="col-md-3">
			                <div class="form-group">
    			                <label for="product_type">পণ্যের ধরন <span class="red">*</span></label></label>
    			                <select name="type" class="form-control" id="product_type" required onchange="get_pro_code()">
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
			            <div class="col-md-3">
			                <div class="form-group">
    			                <label for="category">ক্যাটাগরি<span class="red">*</span> </label>
    			                <select id="category_id" name="category_id" class="form-control select2" required onchange="get_pro_code()">
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
			                function get_pro_code(){
			                    catid = $('#category_id').val()
			                    type = $('#product_type').val()
		                        $.get("control.php?pr_type="+type+"&get_pro_code="+catid, function(data, status){
                                    $('#product_code').val(data.replace(/^\s+|\s+$/gm,''))
                                });
			                
			                    
			                }
			            </script>
			            <div class="col-md-3">
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
			            
			            <div class="col-md-3">
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
			            <div class="col-md-6">
			                <div class="form-group">
    			                <label for="name">পণ্যের নাম<span class="red">*</span> </label>
    			                <input name="name" value="<?php echo $name;?>" required class="form-control" id="name" placeholder="পণ্যের নাম">
			                </div>
			            </div>
			            <div class="col-md-3">
			                <div class="form-group">
    			                <label for="product_code">পণ্য কোড <span class="red">*</span></label>
    			                <input name="product_code" value="<?php echo $product_code;?>" required class="form-control" id="product_code" placeholder="পণ্য কোড">
			                </div>
			            </div>
			            <div class="col-md-3">
			                <div class="form-group">
    			                <label for="purchase_price">ক্রয় মূল্য</label>
    			                <input name="purchase_price" value="<?php echo $purchase_price;?>" class="form-control" id="purchase_price" placeholder="ক্রয় মূল্য">
			                </div>
			            </div>
			            
			            
			            <div class="col-md-3">
			                <div class="form-group">
    			                <label for="opening_stock">প্রারম্ভিক স্টক </label>
    			                <input name="opening_stock" value="<?php echo $opening_stock;?>" class="form-control" id="opening_stock" placeholder="প্রারম্ভিক স্টক">
			                </div>
			            </div>
			            <div class="col-md-3">
			                <div class="form-group">
    			                <label for="min_stock">ন্যূনতম স্টক</label>
    			                <input name="min_stock" value="<?php echo $min_stock;?>" class="form-control" id="min_stock" placeholder="ন্যূনতম স্টক">
			                </div>
			            </div>
			            <div class="col-md-12">
			                <button type="submit" name="btn" value="<?php echo $id;?>" class="btn btn-primary">সেভ</button>
			            </div>
			        </div>
			    </form>
			    
			    
            </div>
		</div>
	</div>
</div>