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
if(isset($_POST["lun"])){


if(vp_getoption("vp_enable_registration") == "no"){
	die('{"status":"101","message":"Registration Currently Not Allowed"}');
}else{

}
$user = trim($_POST["username"]);
$email = $_POST["email"];
$pass = $_POST["pswd"];
$phone = $_POST["phone"];
$ref = $_POST["ref"];
$fun = trim($_POST["fun"]);
$lun = trim($_POST["lun"]);
$pin = $_POST["pin"];

if(vp_getoption("vp_security") == "yes"){
$ban_list = vp_getoption("vp_users_email");

if(is_numeric(stripos($ban_list,$user)) || is_numeric(stripos($ban_list,$email)) ){
	
	
die('{"status":"101","message":NOT ALLOWED (X)"}');

}

}

$verify_username = preg_match("/[^a-zA-Z0-9]/", $user);
if($verify_username === 0 && !empty($user)){
	$verify_email = preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/",$email);
if($verify_email === 1  && !empty($email)){
	$verify_pass = preg_match("/[^a-zA-Z0-9\.!@#$%^&*()\-_+\[\]]/",$pass);
	if($verify_pass === 0  && !empty($pass)){
		$verify_phone = preg_match("/[^0-9]/",$phone);
		if($verify_phone === 0 && strlen($phone) == 11  && !empty($phone)){
			$verify_ref = preg_match("/[^0-9]/",$ref);
			if(($verify_ref === 0  && !empty($ref)) || !is_plugin_active("vpmlm/vpmlm.php")){
				$verify_fn = preg_match("/[^a-zA-Z]/",$fun);
				$verify_ln = preg_match("/[^a-zA-Z]/",$lun);
				if($verify_fn === 0 && $verify_ln == 0  && !empty($fun)  && !empty($lun)){
					  
					  $verify_zero = preg_match("/^0\d+$/",$pin);
					  
				if($verify_zero === 0){  
					  $verify_pin = preg_match("/[^0-9]/", $pin);
				
				if($verify_pin === 0 && strlen($pin) >= 4 ){

					if(username_exists($user)){
						die('{"status":"101","message":"Sorry, that username already exists!"}');
					}
					elseif(email_exists($email)){
						die('{"status":"101","message":"Sorry, that email already exists!"}');
					}
					  
					  $userdata = array(
'user_login' => sanitize_user($user),
'user_email' => sanitize_email($email),
'user_pass' => sanitize_text_field($pass)
);

$userid = wp_insert_user($userdata);

do_action( 'user_register', $userid, $userdata );

if(is_plugin_active("vpmlm/vpmlm.php")){
	$ref = $_POST["ref"];// my ref id
	if($userid == $ref){
		$ref = "1";
	}
}
else{
	$ref = "1";
}

vp_updateuser($userid,'vp_pin_set','yes');
vp_updateuser($userid, 'vp_phone', $phone);
vp_updateuser($userid, 'vp_pin', $pin);
vp_updateuser($userid, 'vp_bal', 0);
vp_updateuser($userid, 'vr_id', uniqid());
vp_updateuser($userid, 'vp_ref', 0);
vp_updateuser($userid, 'vporr', 0);
vp_updateuser($userid, 'last_name', $lun);
vp_updateuser($userid, 'first_name', $fun);

global $wpdb;
$user = $wpdb->prefix."users";
$arr = ['vp_bal' => "0", 'vp_ban' => "access" ];
$where = ['ID' => $userid];
$updated = $wpdb->update($user, $arr, $where);

vp_updateuser($userid, 'vp_who_ref' , $ref); //who referred me
vp_updateuser($userid, 'vp_tot_ref' , 0); //number of my direct referrs
vp_updateuser($userid, 'vp_tot_in_ref' , 0); //number of my indirect referrs
vp_updateuser($userid, 'vp_tot_in_ref3' , 0); //number of my third level referrs



vp_updateuser($userid, 'vp_tot_ref_earn' , 0); // total earned from direct referrers
vp_updateuser($userid, 'vp_tot_in_ref_earn' , 0); // total earned from indirect referrers
vp_updateuser($userid, 'vp_tot_in_ref_earn3' , 0); // total earned from third level referrers



vp_updateuser($userid, 'vp_tot_trans' , 0);  // total transactions Attempted
vp_updateuser($userid, 'vp_tot_suc_trans' , 0);  // total Successful transactions made
vp_updateuser($userid, 'vp_tot_trans_amt' , 0); //total transactions amount consumed
vp_updateuser($userid, 'vp_tot_trans_bonus' , 0); //total transactions bonus earned
vp_updateuser($userid, 'vp_tot_withdraws' , 0); // total withdrawals made
vp_updateuser($userid, 'vp_tot_dir_trans' , 0); // total amount earned from direct trans
vp_updateuser($userid, 'vp_tot_indir_trans' , 0); // total amount earned from indirect trans
vp_updateuser($userid, 'vp_tot_indir_trans3' , 0); // total amount earned from indirect trans


if ( is_wp_error( $userid ) ) {
    // There was an error; possibly this user doesn't exist.
  	$error_message = $userid->get_error_message();
	
	echo'{"status":"101","message":"'.$error_message.'"}';
} 
else{
	
	
	
	
	
	
if(vp_getoption('paychoice') == "monnify"){
	
if(vp_getoption('monnifytestmode') == "true" ){
	$baseurl =  "https://sandbox.monnify.com";
	$mode = "test";
}
else{
	$baseurl =  "https://api.monnify.com";
	$mode = "live";
}
	

$apikeym = vp_getoption("monnifyapikey");
$secretkeym = vp_getoption("monnifysecretkey");

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => $baseurl.'/api/v1/auth/login/',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_HTTPHEADER => [
            "Content-Type: application/json",
            "Authorization: Basic ".base64_encode("$apikeym:$secretkeym") 
        ],
));

$respo = curl_exec($curl);

$json = json_decode($respo)->responseBody->accessToken;


$curl = curl_init();
$code = rand(1000,100000);
curl_setopt_array($curl, array(
  CURLOPT_URL => $baseurl.'/api/v2/bank-transfer/reserved-accounts',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{
    "accountReference": "'.$code.'",
    "accountName": "'.$_POST["username"].'",
    "currencyCode": "NGN",
    "contractCode": "'. vp_getoption("monnifycontractcode").'",
    "customerEmail": "'.$email.'",
    "customerName": "'.$fun." ".$lun.'",
    "getAllAvailableBanks": false,
    "preferredBanks": ["035","232","50515"]
	
}',
  CURLOPT_HTTPHEADER => array(
    "Authorization: Bearer $json",
    'Content-Type: application/json'
  ),
));

$respon = curl_exec($curl);

curl_close($curl);

$response = json_decode($respon,true);

if(isset($response["responseBody"]["accountReference"])){
$reference = $response["responseBody"]["accountReference"];
$customerName = $response["responseBody"]["accounts"][0]["accountName"];
$accountNumber = $response["responseBody"]["accounts"][0]["accountNumber"];
$bankName = $response["responseBody"]["accounts"][0]["bankName"];

vp_updateuser($userid,"bank_reference",$reference);
vp_updateuser($userid,"account_mode",$mode);

vp_updateuser($userid,"account_name",$customerName);
vp_updateuser($userid,"account_number",$accountNumber);
vp_updateuser($userid,"bank_name",$bankName);

if(isset($response["responseBody"]["accounts"][1]["accountName"])){
  $customerName = $response["responseBody"]["accounts"][1]["accountName"];
  $accountNumber = $response["responseBody"]["accounts"][1]["accountNumber"];
  $bankName = $response["responseBody"]["accounts"][1]["bankName"];

vp_updateuser($userid,"account_name1",$customerName);
vp_updateuser($userid,"account_number1",$accountNumber);
vp_updateuser($userid,"bank_name1",$bankName);

  }
  else{}

  
  if(isset($response["responseBody"]["accounts"][2]["accountName"])){
    $customerName = $response["responseBody"]["accounts"][2]["accountName"];
    $accountNumber = $response["responseBody"]["accounts"][2]["accountNumber"];
    $bankName = $response["responseBody"]["accounts"][2]["bankName"];

vp_updateuser($userid,"account_name2",$customerName);
vp_updateuser($userid,"account_number2",$accountNumber);
vp_updateuser($userid,"bank_name2",$bankName);

    }
    else{}

}


}



if(is_plugin_active("vpmlm/vpmlm.php")){
	
	//direct
	$ref = $_POST["ref"];// my ref id
	$total_dir_ref = vp_getuser($ref, "vp_tot_ref", true); //his cur total dir ref
	$sum_tot_dir_ref =  intval($total_dir_ref) + 1;
	vp_updateuser($ref, "vp_tot_ref", $sum_tot_dir_ref);
	
	
	//indirect
	$who_reref = vp_getuser($ref, "vp_who_ref", true); // who ref my ref
	$total_indir_ref = vp_getuser($who_reref, "vp_tot_in_ref", true); //his cur total indir ref
	$sum_tot_indir_ref =  intval($total_indir_ref) + 1;
	vp_updateuser($who_reref, "vp_tot_in_ref", $sum_tot_indir_ref);
	

	
	
	$who_reref3 = vp_getuser($who_reref, "vp_who_ref", true); // who ref my ref
	$total_indir_ref3 = vp_getuser($who_reref3, "vp_tot_in_ref3", true); //his cur total indir ref
	$sum_tot_indir_ref3 =  intval($total_indir_ref3) + 1;
	vp_updateuser($who_reref3, "vp_tot_in_ref3", $sum_tot_indir_ref3);
	
	$refs_id = vp_getuser($ref, "vp_tot_ref_id", true);
	vp_updateuser($ref, "vp_tot_ref_id", $refs_id."$userid,");
	
	$inrefs_id = vp_getuser($who_reref, "vp_tot_in_ref_id", true);
	vp_updateuser($who_reref, "vp_tot_in_ref_id", $inrefs_id."$userid,");
	
	$inrefs3_id = vp_getuser($who_reref3, "vp_tot_in_ref3_id", true);
	vp_updateuser($who_reref3, "vp_tot_in_ref3_id", $inrefs3_id."$userid,");
}
	
echo'{"status":"100"}';

}


				}
				else{
				die('{"status":"101","message":"Pin Must Be Numeric And At Least 4 Digits"}');
				}
				
				}
				else{
				die('{"status":"101","message":"Pin Must Not Start With Zero"}');	
				}
				}
				else{
					die('{"status":"101","message":"First And Last Name Must Be Of At Least (3) Letters Only Without Space"}');	
				}
				}
				else{
				die('{"status":"101","message":"Your Refer Code Must Be The Default ID Of 1 Or A Valid User ID"}');	
			}	
		}
		else{
		die('{"status":"101","message":"Enter Your 11 Digits Phone Numbers"}');	
		}
	}
	else{
	die('{"status":"101","message":"Password Must Contain Only AlphaNumeric With/Or Character Without {} or Space"}');	
	}
}
else{
	die('{"status":"101","message":"Incorrect Email"}');
}	
}
else{
	die('{"status":"101","message":"Username Must Contain Only Alpha-Numeric Character without @-/.#$%^&* or space"}');
}
}
?>