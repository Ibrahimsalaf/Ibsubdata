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

if(isset($_REQUEST["secure"])){
$http_redirect = $_REQUEST["http"];
$global_security = $_REQUEST["global"];
$security_mode = $_REQUEST["security"];
$ips = $_REQUEST["httips"];
$users = $_REQUEST["users"];
$ban_email = $_REQUEST["email"];
$access_website = $_REQUEST["access-website"];
$access_user = $_REQUEST["user-dashboard"];
$access_country = $_REQUEST["other-country"];
$tself = $_REQUEST["tself"];
$tothers = $_REQUEST["tothers"];

/*
echo $http_redirect."http <br>";
echo $global_security."global <br>";
echo $security_mode."sec mide <br>";
echo $ips."ips <br>";
echo $users."users <br>";
echo $access_website."aw <br>";
echo $access_user."au <br>";
echo $access_country."oc <br>";
*/
$stream = stream_context_create (array("ssl" => array("capture_peer_cert" => true)));
$read = fopen(vp_getoption("siteurl"), "rb", false, $stream);
$cont = stream_context_get_params($read);
$var = ($cont["options"]["ssl"]["peer_certificate"]);
if(is_null($var)){
	
}
else{
if($http_redirect == "true" && vp_getoption("siteurl") == "http://".$_SERVER["SERVER_NAME"] && vp_getoption("siteurl") == "http://".$_SERVER['HTTP_HOST']){
vp_updateoption("siteurl", "https://".$_SERVER['HTTP_HOST']);
}
}


vp_updateoption("http_redirect", $http_redirect);
vp_updateoption("global_security", $global_security);
vp_updateoption("secur_mod", $security_mode);
vp_updateoption("vp_ips_ban", $ips);
vp_updateoption("vp_users_ban", $users);
vp_updateoption("access_website", $access_website);
vp_updateoption("access_user_dashboard", $access_user);
vp_updateoption("vp_users_email", $ban_email);
vp_updateoption("access_country", $access_country);
vp_updateoption("tself", $tself);
vp_updateoption("tothers", $tothers);

/*
echo vp_getoption("http_redirect")."http <br>";
echo vp_getoption("global_security")."global <br>";
echo vp_getoption("secur_mod")."security <br>";
echo vp_getoption("vp_ips_ban")."ips <br>";
echo vp_getoption("vp_users_ban")."users <br>";
echo vp_getoption("access_website")."aw <br>";
echo vp_getoption("access_user_dashboard")."au <br>";
echo vp_getoption("access_country")."oc <br>";
*/


die("100");
}
?>