<?php
require "main.php";
if($db->is_login()){
    $db->render_body($db);
}else{
   $db->redirect('login.php');
}


