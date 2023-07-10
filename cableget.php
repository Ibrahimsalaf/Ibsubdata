<?php
if(!defined('ABSPATH')){
    $pagePath = explode('/wp-content/', dirname(__FILE__));
    include_once(str_replace('wp-content/' , '', $pagePath[0] . '/wp-load.php'));
}
if(WP_DEBUG == false){
error_reporting(0);	
}
include_once(ABSPATH."wp-load.php");
$iuc = $_REQUEST['iuc'];
$cable = $_REQUEST['cable'];
$me =  wp_remote_retrieve_body( wp_remote_get( "https://vtupress.com/billget.php?cableget=yes&service=$cable&meter=$iuc"));

echo $me;
?>