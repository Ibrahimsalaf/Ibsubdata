<?php
if(!defined('ABSPATH')){
    $pagePath = explode('/wp-content/', dirname(__FILE__));
    include_once(str_replace('wp-content/' , '', $pagePath[0] . '/wp-load.php'));
}
if(WP_DEBUG == false){
error_reporting(0);	
}
include_once(ABSPATH ."wp-load.php");
if(isset($_REQUEST["reset"])){
	
if(isset($_REQUEST['wallet'])){
global $wpdb;
$table_name = $wpdb->prefix . 'vp_wallet';
$sql = "DROP TABLE IF EXISTS $table_name";
$wpdb->query($sql);
die("100");
}


if(isset($_REQUEST['airtime'])){
global $wpdb;
$table_name = $wpdb->prefix . 'sairtime';
$sql = "DROP TABLE IF EXISTS $table_name";
$wpdb->query($sql);

global $wpdb;
$table_name = $wpdb->prefix . 'sdata';
$sql = "DROP TABLE IF EXISTS $table_name";
$wpdb->query($sql);

global $wpdb;
$table_name = $wpdb->prefix . 'fairtime';
$sql = "DROP TABLE IF EXISTS $table_name";
$wpdb->query($sql);

global $wpdb;
$table_name = $wpdb->prefix . 'fdata';
$sql = "DROP TABLE IF EXISTS $table_name";
$wpdb->query($sql);

//$airtime = file_get_contents(esc_url(plugins_url('vtupress/install.php?vpaction=install&slug=vtupress/vtupress.php&link=https://vtupress.com/vtupress.zip')));
die("100");

}

if(isset($_REQUEST['bill'])){

global $wpdb;
$table_name = $wpdb->prefix . 'scable';
$sql = "DROP TABLE IF EXISTS $table_name";
$wpdb->query($sql);

global $wpdb;
$table_name = $wpdb->prefix . 'sbill';
$sql = "DROP TABLE IF EXISTS $table_name";
$wpdb->query($sql);

global $wpdb;
$table_name = $wpdb->prefix . 'fcable';
$sql = "DROP TABLE IF EXISTS $table_name";
$wpdb->query($sql);

global $wpdb;
$table_name = $wpdb->prefix . 'fbill';
$sql = "DROP TABLE IF EXISTS $table_name";
$wpdb->query($sql);

	
$bill = file_get_contents(esc_url(plugins_url('vtupress/install.php?vpaction=install&slug=bcmv/bcmv.php&link=https://vtupress.com/bcmv.zip')));

die("100");

}

die("200");
}

?>