<?php
session_destroy();
if(isset($_SESSION['user_id'])){
    unset($_SESSION['user_id']);
    $db->redirect('login.php');
}
?>