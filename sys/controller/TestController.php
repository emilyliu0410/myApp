<?php

defined('UFM_RUN') or die('No direct script access allowed.');

class TestController extends UController {

	public $useMasterDb = true;		// connect master database for insert/update

    function actionIndex() {
		//debug($_SERVER);
		debug(123);
		/*
		//require_once UAPP_PLUGIN_DIR.'/AWSSDKforPHP/Sdk.php';
		require_once UAPP_PLUGIN_DIR.'/aws/Aws/Sdk.php';

		// Instantiate the Amazon DynamoDB client.
		// REMEMBER: You need to set 'defaultcacheconfig' in your config.inc.php.
		$dynamodb = new AmazonDynamoDB();

		// Register the DynamoDB Session Handler.
		// $handler = $dynamodb->registersessionhandler(array(
			// 'tablename' => 'my-sessions-table'
		// ));
		*/
		
		// ** 1 **		
		$step = $_REQUEST['s'];		
		if($step == 1){
			session_start(); echo '1. session started, session id '.session_id().'<br>'; 
			$_SESSION['test'] = '12345'; echo '2. assigned value to session<br>'; 
			debug($_SESSION); echo '3. $_SESSION value:'; 
		}elseif($step == 2){
			//echo '1. session started<br>'; session_start(); 
			//echo '2. assigned value to session<br>'; $_SESSION['test'] = '12345'; 
			echo '3. $_SESSION value:'; debug($_SESSION);
		}elseif($step == 3){
			session_start(); echo '1. session started, session id '.session_id().'<br>'; 
			//echo '2. assigned value to session<br>'; $_SESSION['test'] = '12345'; 
			debug($_SESSION); echo '3. $_SESSION value:'; 
		}else{
			session_start();
			$_SESSION['test'] = 'testing session';
			debug($_SESSION);
		}		
		
		// ** 2 **
		// $old_session = $_SESSION;
		// session_destroy();
		//set up a new session, of name based on the ticket
		// $session_id = 'id12345';
		// echo "Session ID: ".$session_id.'<br>';
		// session_id($session_id);
		// session_start();
		// echo "Restoring old session vars".'<br>';
		// $_SESSION = $old_session;
		// echo $_SESSION['test'].'<br>';
		
		$id = url_decrypt("ABBGD1ozBwhZEANr");		
		debug($id);
		
        $pageID = isset($_REQUEST['page_id']) ? url_decrypt($_REQUEST['page_id']) : 0;
        $pagetypeID = isset($_REQUEST['pagetype_id']) ? (int) $_REQUEST['pagetype_id'] : 0;
        $redirectUrl = isset($_REQUEST['redirect_url']) ? $_REQUEST['redirect_url'] : UAPP_HOST . UAPP_BASE_URL . "/index.html";

        if ($pageID > 0 && $pagetypeID > 0) {
            $this->assign("pageID", url_encrypt($pageID));
            $this->assign("pagetypeID", $pagetypeID);
            $this->assign("redirectUrl", $redirectUrl);
            $this->layout = 'directHtml';
            $this->display();
        }
    }

}
