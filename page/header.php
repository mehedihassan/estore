<?php 
    
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="shortcut icon" href="image/fav.png" type="image/x-icon">
    <link rel="shortcut icon" href="image/fav.png"/>
	<link rel="stylesheet" type="text/css" href="css/fontawesome/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="css/toast.css">
	
	<link rel="stylesheet" type="text/css" href="css/jquery.dataTables.min.css">
	<link rel="stylesheet" type="text/css" href="css/dataTables.bootstrap4.min.css">
	<link rel="stylesheet" type="text/css" href="select2/select2.min.css">
	
    
    <link rel="stylesheet" href="select2/chosen.css" type="text/css" media="all" />
	

	<title>Dashboard - Admin Panel</title>
	
	<!--Scripts-->
	<script type="text/javascript" src="js/jquery.min.js"></script>
</head>
<style>
.chosen-search input{
    display: block;
    width: 100%;
    height: 30px;
    padding: 0 .75rem;
    font-size: 1rem;
    font-weight: 400;
    line-height: 1.5;
    color: #495057;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #ced4da;
    border-radius: .25rem;
    transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
}
body {
    counter-reset: my-sec-counter;
}
[scope="row"]::before {
  /* Increment "my-sec-counter" by 1 */
  counter-increment: my-sec-counter;
  content: counter(my-sec-counter);
}
.red {
    color: red;
    font-weight: bold;
    padding-left: 5px;
}
th{
    text-align:center !important;
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
@media print
{    
    .no-print,.navbar-default,.top_header,.hdprint
    {
        display: none !important;
    }
    th{
        text-align:center !important;
        color:black !important;
    }
    td{
        color:#212529 !important;
    }
    .badge {
        border:none !important;
        background:none !important;
        color:#212529 !important;
    }
}
</style>
<body>
    
	<!-- Full body -->
	<div id="full-body">

		<!-- Navigation Bar -->
		<nav class="navbar-default" id="menu-side">
			<div class="nav-menu">
				<div class="nav-header">
					<div class="dropdown profile-block">
					<?php 
					$img = $db->getonecol('img', 'staff', 'id', $db->uid());
					if (file_exists('image/staff/'.$img) and $img != '') {
						$profile_pic = '<img style="margin:0 auto;" src="image/staff/'.$img.'" class="d-block rounded-circle nav-profile-logo">';
					}else{
						$profile_pic = '<img style="margin:0 auto;" src="image/logo.jpg" class="d-block rounded-circle nav-profile-logo">';
					}
                    echo $profile_pic;
					?>
						
						<a href="#" data-toggle="dropdown" style="text-align:center">
						    
							<b class="d-block profile-name">
							
							<?php
						    if(isset($_SESSION['user_name'])){
						        echo $_SESSION['user_name'];
						    }
						    ?>
							</b>
							<span class="text-muted d-block profile-status"><?php
						    if(isset($_SESSION['user_desig'])){
						        echo $_SESSION['user_desig'];
						    }
						    ?> <i class="fa fa-caret-down"></i></span>
						</a>
						<ul class="dropdown-menu">
							<li><a class="dropdown-item" href="profile">Profile</a></li>
							<li class="dropdown-divider"></li>
							<li><a class="dropdown-item" href="logout">Logout</a></li>
						</ul>
					</div>
					<div class="profile-logo-block">
						<?php echo $profile_pic;?>
					</div>
				</div>
				<ul class="list-unstyled" id="nav-menus">
				    
				    <?php
				    
				    
				    $left_menu = $db->left_menu();
				    
				    if (isset($url[0]) && $url[0] != "") {
				        $activeUrl = $url[0];
				    } else {
				        $activeUrl = 'dashboard';
				    }
				    

				    if (is_array($left_menu)) {
				        foreach($left_menu as $key => $value) {
				            if (isset($value['url'])) {
                                $murl = $value['url'];
                            }else{
                                $murl = '';
                            }
                            $submenu = '';
                            
                            $mainUrl = [$value['url']];
                            
                            if (isset($value['sub_menu'])) {
	                            if (is_array($value['sub_menu'])) {
	                                foreach($value['sub_menu'] as $k => $v) {
	                                    $mainUrl[] = $v;
	                                }
	                            }
                            }

                    ?>
                            
                    <?php if (in_array($value['url'], $permission)) : ?>        
				    <li<?php if (in_array($activeUrl, $mainUrl)) : echo ' class="active"'; endif; ?>>
						<a href="<?php echo $value['url']; ?>"><i class="<?php echo $value['icon']; ?>"></i> <span class="nav-label"><?php echo $value['name']; ?></span>
						<?php if (isset($value['sub_menu'])) : ?>
							<?php if (is_array($value['sub_menu'])) : ?>
								<span class="nav-arrow fa fa-angle-left"></span>
							<?php endif; ?></a>
						<?php endif; ?></a>
						<?php if (isset($value['sub_menu'])) : ?>
						<?php if (is_array($value['sub_menu'])) : ?>
						<ul>
						    
						    <?php foreach($value['sub_menu'] as $name => $sub_url) : ?>
						    <?php if (in_array($sub_url.'_view', $permission)) : ?> 
							<li<?php if ($sub_url == $activeUrl) : echo ' class="active"'; endif; ?>><a href="<?php echo $sub_url; ?>"><?php echo $name; ?></a></li>
							<?php endif;?>
							<?php endforeach; ?>
						</ul>
						<?php endif; ?>
						<?php endif; ?>
					</li>
                    <?php endif;?>         
                            
                    <?php         
				        }
				    }
				    
				    
				    ?>

				</ul>
			</div>
		</nav>

		<!-- Main body -->
		<div id="main-body">

			<!-- Navbar Header -->
			<div class="row border-bottom top_header">
				<header class="navbar navbar-header-top bg-white">
					<div class="navbar-header">
						<button class="btn navbar-toggler-btn" id="navbar-slide-toggler"><i class="fa fa-bars"></i></button>
					</div>
					
					<div class="navbar-header">
						<button class="btn btn-success navbar-toggler-btn" style="border-radius: 17px;cursor: default;">
						    <?php
						    if($db->is_super()){
						        echo 'সুপার এডমিন';
						    }elseif($db->is_stock()){
						        echo 'স্টোর ইনচার্জ';
						    }elseif($db->is_section()){
						        echo 'শাখা ব্যবস্থাপক';
						    }elseif($db->is_staff()){
						        echo 'এন্ট্রি ইউজার';
						    }
						    
						    ?>
						</button>
					</div>
					
					<ul class="navbar-header-right list-unstyled">
						
						
						
						<li>
							<a href="logout">
								<i class="fa fa-sign-out"></i> Log out
							</a>
						</li>
						<li>
							<a href="#" class="right-sidebar-toggle">
								<i class="fa fa-tasks"></i>
							</a>
						</li>
					</ul>
				</header>
			</div>

			<!-- Content Body -->
			<div id="content-body">
