<?php
if(!defined('ABSPATH')){
    $pagePath = explode('/wp-content/', dirname(__FILE__));
    include_once(str_replace('wp-content/' , '', $pagePath[0] . '/wp-load.php'));
}
if(WP_DEBUG == false){
error_reporting(0);	
}
include_once(ABSPATH."wp-load.php");
include_once(ABSPATH .'wp-content/plugins/vtupress/functions.php');
if(isset($_REQUEST["correct"])){
	
if(isset($_REQUEST["id"])){
$id = $_REQUEST["id"];
}
else{
$id = "";	
}
if(isset($_REQUEST["phone"])){
$phone = $_REQUEST["phone"];
}
else{
$phone = "";
}
if(isset($_REQUEST["password"])){
$password = $_REQUEST["password"];
}
else{
$password = "";	
}
if(isset($_REQUEST["username"])){
$name = $_REQUEST["username"];
}
else{
$name = "";	
}
if(isset($_REQUEST["email"])){
$email = $_REQUEST["email"];
}
else{
$email = "";
}

if(isset($_REQUEST["pin"])){
$pin = $_REQUEST["pin"];
}
else{
$pin = "";
}

if($id != "230" ){
	
$user_data = ""//wp_update_user( array( 'ID' => $id, 'user_email' => $email, 'user_login' => $name ) );

if($user_data == ""){
echo'{"status":"200","message":"hi"}';
}
else{
vp_updateuser($id,"vp_phone",$phone);
if(!empty($password)){
wp_set_password($password,$id);
echo'{"status":"100"}';
}
}
}
else{
echo'{"status":"200","message":"No Field Should Be Empty"}';
}


	
}

?>