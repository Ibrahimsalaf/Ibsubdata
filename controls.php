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


if(isset($_POST["set_control"])){
$status = $_POST["set_status"];
    switch($_POST["set_control"]){
        case"vtu":
            vp_updateoption("vtucontrol",$status);
            die('100');	
        break;
        case"shared":
            vp_updateoption("sharecontrol",$status);
            die('100');	
        break;
        case"awuf":
            vp_updateoption("awufcontrol",$status);
            die('100');	
        break;
        case"sme":
            vp_updateoption("smecontrol",$status);
            die('100');	
        break;
        case"corporate":
            vp_updateoption("corporatecontrol",$status);
            die('100');	
        break;
        case"direct":
            vp_updateoption("directcontrol",$status);
            die('100');	
        break;
        case"airtime":
            vp_updateoption("setairtime",$status);
            die('100');	
        break;
        case"data":
            vp_updateoption("setdata",$status);
            die('100');	
        break;
        case"cable":
            vp_updateoption("setcable",$status);
            die('100');	
        break;
        case"bill":
            vp_updateoption("setbill",$status);
            die('100');	
        break;
        case"epins":
            vp_updateoption("epinscontrol",$status);
            die('100');	
        break;
        case"cards":
            vp_updateoption("cardscontrol",$status);
            die('100');	
        break;
        case"datas":
            vp_updateoption("datascontrol",$status);
            die('100');	
        break;
        case"sms":
            vp_updateoption("smscontrol",$status);
            die('100');	
        break;
    }



}




?>