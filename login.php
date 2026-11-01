<?php
require "main.php";
$db->login();
if($db->is_login()){
    $db->redirect($db->domain());
}
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="css/fontawesome/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="css/login.css">
	<link rel="stylesheet" type="text/css" href="select2/select2.min.css">
	<title>Admin Login</title>
	<script type="text/javascript" src="js/jquery.min.js"></script>
	<style type="text/css">
		body {
			/*background-image: url(image/background-image.jpg);*/
			background-size: cover;
		}
	    .disclaimer {
            display: none;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 35px;
        }
        .select2-container .select2-selection--single {
            height: 36px;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 34px;
        }
        .left-heading {
            margin-bottom: -25px;
            margin-top: calc(50% - 90px);
            border-bottom: 4px dotted #5c66a2;
            margin-left: 10px;
            padding-bottom: 20px;
        }
        .left-heading > .heading_1 {
            text-align: center;
            font-size: 16px;
            border-bottom: 1px solid #b8b8b8;
            margin-bottom: 10px;
            padding-bottom: 8px;
        }
        .left-heading > .heading_2 {
            color: #5C66A2;
            text-align: center;
            font-size: 18px;
            margin-top: 6px;
        }
        .image-wrap {
            width: 345px;
            height: 345px;
            margin-left: 12px;
            align-items: center;
            justify-content: center;
            display: flex;
        }
        .loading_animation {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            animation: 40s linear infinite spinner-border;
        }
        svg {
            margin: 39px 41px;
            fill: currentColor;
            height: auto;
            max-width: 270vmin;
            transform-origin: center;
            width: 270px;
        }
        .login-body {
            background: url(image/login_background.jpg);
            background-repeat: no-repeat;
            background-size: cover;
        }
        .login-box-wrap {
            max-width: 300px;
        }
	</style>
</head>
<body>
	<div class="login-body">
		<div class="login-box-wrap overflow-hidden">

			<div class="row">
				
			<!--	<div class="col-lg-6 left-side d-lg-flex" style="align-items: center;">-->
			<!--	    <div class="image-wrap position-relative">-->
			<!--	        <img src="image/logo1.png" width="200" />-->
			<!--	        <div class="loading_animation">-->
			<!--	            <svg viewBox="0 0 100 100" width="344" height="344">-->
   <!--                           <defs>-->
   <!--                             <path id="circle" d="-->
   <!--                                 M 47.8, 50-->
   <!--                                 m -39, 0-->
   <!--                                 a 37,37 0 1,1 80,0-->
   <!--                                 a 37,37 0 1,1 -80,0"></path>-->
   <!--                           </defs>-->
   <!--                           <text font-size="6" style="">-->
   <!--                             <textPath xlink:href="#circle">-->
   <!--                              একটাই লক্ষ্য হতে হবে দক্ষ-->
   <!--                             </textPath>-->
   <!--                           </text>-->
   <!--                         </svg>-->
			<!--	        </div>-->
			<!--	    </div>-->
			<!--	</div>-->
				
				<!--<div class="col-lg-6 left-side d-lg-block">-->
				<!--    <div class="left-heading">-->
				<!--        <div class="heading_1">মাদ্রাসাসহ সকল শিক্ষা প্রতিষ্ঠানে</div>-->
				<!--        <div class="heading_2">দক্ষতাভিত্তিক কারিগরি শিক্ষা ও প্রশিক্ষণ</div>-->
				<!--    </div>-->
				<!--    <div class="image-wrap position-relative">-->
				<!--        <img src="image/logo-----.png" width="200" />-->
				<!--        <div class="loading_animation">-->
				<!--            <svg viewBox="0 0 100 100" width="344" height="344">-->
    <!--                          <defs>-->
    <!--                            <path id="circle" d="-->
    <!--                                M 47.8, 50-->
    <!--                                m -39, 0-->
    <!--                                a 37,37 0 1,1 80,0-->
    <!--                                a 37,37 0 1,1 -80,0"></path>-->
    <!--                          </defs>-->
    <!--                          <text font-size="6" style="">-->
    <!--                            <textPath xlink:href="#circle">-->
    <!--                             একটাই লক্ষ্য হতে হবে দক্ষ-->
    <!--                            </textPath>-->
    <!--                          </text>-->
    <!--                        </svg>-->
				<!--        </div>-->
				<!--    </div>-->
				<!--</div>-->


				<div class="col-lg-12">


					<div class="login-form">
						<div class="login-header">
							<img src="image/logo.png" width="90" style="margin: 0 auto;display: block;padding-bottom: 20px;">
							<h2>কারিগরি ও মাদ্রাসা শিক্ষা বিভাগ</h2>
							<h3>শিক্ষা মন্ত্রণালয়</h3>
							 <div class="h4 text-center" style="font-size: 13px;margin-top: 5px;">www.tmed.gov.bd</div>
						</div>

						<!-- <div class="admin-login-notice">Username or Password is wrong</div> -->

						<div class="admin-login-form">
							<form method="POST">
							    
							
								
								<div>
									<?php
									if(isset($_SESSION['msg'])){
									    echo $_SESSION['msg'];
									    unset($_SESSION['msg']);
									}
									
									?>
								</div>
								
								<div class="input-group mb-3">
									<select class="form-control select2" name="section_id" onchange="show_staff(this.value)">
									    <option value="">শাখা নির্বাচন করুন</option>
									    <?php
									    foreach($db->getdata('select * from section') as $v){
                                            echo '<option value="'.$v['id'].'">'.$v['name'].'</option>';
                                        }
                                        ?>
									</select>
								</div>
								
								<div class="input-group mb-3">
									<select class="form-control select2 staff_id" name="staff_id">
									    <option value="">স্টাফ নির্বাচন করুন</option>
									    </select>
								</div>
								

								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text"><i class="fa fa-lock"></i></span>
									</div>
									<input type="password" name="password" class="form-control" placeholder="পাসওয়ার্ড লিখুন" required />
								</div>

								<br />

								<input type="submit" name="btn" value="লগ-ইন" class="btn btn-info btn-block" />
							</form>
						</div>
					</div>

				</div>
			</div>

		</div>
	</div>
	<div class="footer">
		<div class="d-flex justify-content-between">
			<div class="copyright-left-text">© কপিরাইট: কারিগরি ও মাদ্রাসা শিক্ষা বিভাগ, শিক্ষা মন্ত্রণালয়। (সমস্ত অধিকার সংরক্ষিত)</div>
			<div class="copyright-right-text">সিস্টেম ডেভেলপ করেছেন - <a href="https://softwaresolutioncompany.com">সফটওয়্যার সলিউশন কোম্পানি</a></div>
		</div>
	</div>
	
	<script type="text/javascript" src="select2/select2.min.js"></script>
	<script>
	    $('.select2').select2()
	    
	    
	    function show_staff(v){
	        $.post("control.php",{log_section_id: v},function(data, status){
                $('.staff_id').html(data)
            });
	    }
	</script>
</body>
</html>