<?php

defined('UFM_RUN') or die('No direct script access allowed.');
//session_cache_limiter ('private, must-revalidate');    

    //$cache_limiter = session_cache_limiter();

    //
//session_cache_limiter('private,max-age=10800');
  //  session_cache_expire(60); // in minutes 
//session_cache_limiter('nocache');//清空表單
//session_set_cookie_params(60,'/','http://hk.ulifestyle.com.hk:80', 0, 1);
session_start();
class TestingsessionController extends UController {

    function actionIndex() {
		
		$_value = isset($_REQUEST['value'])? $_REQUEST['value']:0;
		$_SESSION['debug_1']=$_value;
		/* switch(session_status()){
			case 0:
			echo 'session_status:  DISABLED';
			break;
			case 1:
			echo 'session_status: NONE';
			break;
			case 2:
			echo 'session_status: ACTIVE';
			break;
		} */
		$currentCookieParams = session_get_cookie_params(); 
		//debug($currentCookieParams,0);
		debug('value: '.$_SESSION['debug_1']);
    }
	function actionGet(){
		$temp=$_SESSION['debug_1'];
		/* switch(session_status()){
			case 0:
			echo 'session_status:  DISABLED';
			break;
			case 1:
			echo 'session_status: NONE';
			break;
			case 2:
			echo 'session_status: ACTIVE';
			break;
		} */
		$currentCookieParams = session_get_cookie_params(); 
		//debug($currentCookieParams,0);
		debug('value: '.$temp);
	}
}
