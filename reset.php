<?php
if(!defined('ABSPATH')){
    $pagePath = explode('/wp-content/', dirname(__FILE__));
    include_once(str_replace('wp-content/' , '', $pagePath[0] . '/wp-load.php'));
}
if(WP_DEBUG == false){
error_reporting(0);	
}
include_once(ABSPATH ."wp-load.php");
include_once(ABSPATH .'wp-content/plugins/vtupress/functions.php');
if(isset($_REQUEST["firstreset"])){

$name = $_REQUEST["username"];
$email = $_REQUEST["email"];
$password = $_REQUEST["password"];

$status = get_user_by('login',$name);
$adid = get_user_by('login',$name)->ID;

$id = $status->ID;

if(strtolower($status->user_login) == strtolower($_REQUEST["username"]) && strtolower($_REQUEST["username"]) != "admin" && $adid != "1"){
		
		
		if(strtolower($status->user_email) == strtolower($_REQUEST["email"])){
			
			$mypin = vp_getuser($id,"vp_pin",true);
			$pin = $_REQUEST["pin"];
			
			if($pin == $mypin){
			
				$verify_pass = preg_match("/[^a-zA-Z0-9\.]/",$password);
				if($verify_pass === 0){
			wp_set_password($password,$id);
			
			echo '{"status":"100","message":"Password Set To '.$password.'"}';
				}
				else{
				die('{"status":"200","message":"Password Can Only Contain Alpha-Numeric Figure with optional [dot\'.\']"}');	
				}
			}
			else{
				die('{"status":"200","message":"PIN NOT CORRECT"}');
			}
		
		}
		else{
	
	die('{"status":"200","message":"EMAIL NOT CORRECT"}');
	
		}
	
	
		
	}
	else{
	
	die('{"status":"200","message":"USERNAME NOT CORRECT"}');
	
	}




}




if(isset($_REQUEST["correct"])){
	


$id = get_current_user_id();

$user_data = get_user_by("ID",$id);

$name = $user_data->user_login;

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

if(!empty($id) && !empty($name) && !empty($email) && !empty($phone) && !empty($pin)){
	if(!is_numeric($pin)){
		die('{"status":"200","message":"PIN Must Be Numbers"}');
	}
	if(!is_numeric($phone)){
		die('{"status":"200","message":"PHONE Must Be Numbers"}');
	}
	
$current_email = $user_data->user_email;
if(email_exists($email) && $current_email != $email){
	die('{"status":"200","message":"Email Already Exist"}');
}
elseif($current_email == $email){
//do nothing
}
else{

global $wpdb;
$table_name = $wpdb->base_prefix.'users';
$user_data = $wpdb->update( $table_name, array('user_email'=>$email), array('id'=>$id));

}

vp_updateuser($id,"vp_phone",$phone);
vp_updateuser($id,"vp_pin",$pin);
if(!empty($password)){
wp_set_password($password,$id);
}
echo'{"status":"100"}';

}
else{
echo'{"status":"200","message":"No Field Should Be Empty"}';
}


	
}


?>