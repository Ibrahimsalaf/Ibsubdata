<?php

if(!isset($_SERVER['HTTP_REFERER'])){
	
	die("BAD");
}
else{
	
}
if(!defined('ABSPATH')){
    $pagePath = explode('/wp-content/', dirname(__FILE__));
    include_once(str_replace('wp-content/' , '', $pagePath[0] . '/wp-load.php'));
}
if(WP_DEBUG == false){
error_reporting(0);	
}
include_once(ABSPATH ."wp-load.php");
include_once(ABSPATH .'wp-content/plugins/vtupress/functions.php');
date_default_timezone_set("Africa/Lagos");
if(isset($_REQUEST["enable-schedule"])){
	
	vp_updateoption("enable-schedule",$_REQUEST["enable-schedule"]);
	die("100");
}

if(isset($_REQUEST["schedule-time"])){
	
	vp_updateoption("schedule-time",$_REQUEST["schedule-time"]);
	$now = date('h:i:s', strtotime(date('h:i:s'). '+'.intval($_REQUEST["schedule-time"]).' Minutes'));
	vp_updateoption("next-schedule", $now);
	die("100");
}

?>