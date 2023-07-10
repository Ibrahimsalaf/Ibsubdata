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

function harray_key_first($arr) {
	$arg = json_decode($arr);
	if(is_array($arg)){
		$response  = array("him"=>"me", "them"=>"you");
        foreach($resp as $key => $value) {
            if(!is_array($value)){
                return $arr[$key];
            }else{
                return "error";
            }
        }
		
	}else{
		return $arr;
	}
        
}

$input = file_get_contents("php://input");

$event = json_decode(str_replace(" ","",$input));

$charge = floatval(vp_getoption("charge_back"));

$admine = get_bloginfo('admin_email');

$headers = array('Content-Type: text/html; charset=UTF-8');

function computeSHA512TransactionHash($stringifiedData, $clientSecret) {
    $computedHash = hash_hmac('sha512', $stringifiedData, $clientSecret);
    return $computedHash;
  }


if(isset($event->eventType) && $event->eventType == "SUCCESSFUL_TRANSACTION" && vp_getoption('paychoice') == "monnify" ){




if (!function_exists('getallheaders')){
    function getallheaders()
    {
           $headers = [];
       foreach ($_SERVER as $name => $value)
       {
           if (substr($name, 0, 5) == 'HTTP_')
           {
               $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
           }
       }
       return $headers;
    }
}


    if(function_exists('getallheaders')){

        if(isset(getallheaders()["monnify-signature"])){
            $signature = getallheaders()["monnify-signature"];
        }
        elseif(isset(getallheaders()["Monnify-Signature"])){

            $signature = getallheaders()["Monnify-Signature"];
        }
        else{
        die("No Signature From MONNIFY");
        }

    }else{
        die("getallheaders() Not Running On Your Server. Contact US");  
    }



$DEFAULT_MERCHANT_CLIENT_SECRET = trim(vp_getoption("monnifysecretkey"));

$computedHash = computeSHA512TransactionHash($input, $DEFAULT_MERCHANT_CLIENT_SECRET);

if($signature != $computedHash){
die("Signature Mismatch");
}
else{
echo "Signature = ComputedHash <br>";
}


$email =  $event->eventData->customer->email;
$amount = $event->eventData->amountPaid;
$total_amount = $amount;
$userid = get_user_by( 'email', $email )->ID;

$ref = $event->eventData->transactionReference;

global $wpdb;
$sd_name = $wpdb->prefix.'vp_wallet_webhook';
$rest = $wpdb->get_results("SELECT * FROM $sd_name WHERE referrence = '$ref'");
if(!empty($rest)){

http_response_code("HTTP 200 OK");

header("HTTP/1.1 200 OK");

    die("This Transaction Has Been Processed Before");
}
else{}

$wpdb->insert($sd_name, array(
'user_id'=> $userid,
'gateway' => 'Monnify',
'amount'=> $event->eventData->amountPaid,
'referrence' => $ref,
'status' => "pending",
'response' => " ".esc_html(harray_key_first($input))."",
'the_time' => date('Y-m-d H:i:s A')
));



$user_name =  get_user_by( 'email', $email )->user_login;

$ini = vp_getuser($userid, 'vp_bal', true);


if(vp_getoption("charge_method") == "fixed"){
$minus = $total_amount - $charge;
}
else{
$remove = ($total_amount *  $charge) / 100;
$minus = $total_amount - $remove ;
}



$toti = $ini + $minus;

vp_updateuser($userid, 'vp_bal', $toti);

$now = vp_getuser($userid, 'vp_bal', true);


global $wpdb;
$name = get_userdata($userid)->user_login;
$description = 'Credited By You [Online]';
$fund_amount= $minus;
$before_amount = $ini;
$now_amount = $toti;
$user_id = $userid;
$the_time = current_time('mysql', 1);

$table_name = $wpdb->prefix.'vp_wallet';
$added = $wpdb->insert($table_name, array(
'name'=> $name,
'type'=> 'wallet',
'description'=> $description,
'fund_amount' => $fund_amount,
'before_amount' => $before_amount,
'now_amount' => $now_amount,
'user_id' => $user_id,
'status' => "approved",
'the_time' => current_time('mysql', 1)
));

if(is_numeric($added)){
    global $wpdb;
    $table_name = $wpdb->prefix."vp_wallet_webhook";
    $wpdb->update($table_name, array("status"=>"success"), array("referrence"=>$ref));
}
else{
    global $wpdb;
    $table_name = $wpdb->prefix."vp_wallet_webhook";
    $wpdb->update($table_name, array("status"=>"failed"), array("referrence"=>$ref));  
}

$content = "
<!DOCTYPE html>
<html>
<body>
<h3>New Transaction Logged!</h3><br/>
<table>
<thead>
<tr>
<th>Details</th>
<th>Data</th>
</tr>
</thead>
<tbody>
<tr>
<td>Name</td>
<td>$user_name</td>
</tr>
<tr>
<td>Email</td>
<td>$email</td>
</tr>
<tr>
<td>Previous Balane</td>
<td>$ini</td>
</tr>
<tr>
<td>Funded</td>
<td>$minus</td>
</tr>
</tbody>
<tfoot>
<tr>
<td>Current Balance</td>
<td>$toti</td>
</tr>
</tfoot>
</table>

</body>
</html>


";

wp_mail($admine, "$user_name Wallet Funding [MONNIFY]", $content, $headers);
wp_mail($email, "$user_name Wallet Funding [MONNIFY]", $content, $headers);
http_response_code("HTTP 200 OK");

header("HTTP/1.1 200 OK");

echo "Successful Monnify";
}
else{
	echo "None Successful";
}


?>