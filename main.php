<?php
ob_start();
session_start();
spl_autoload_register(function($clsname){
    include_once 'config/'.$clsname.'.php';
});
$db = new Functions();

