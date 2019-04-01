<?php
defined( 'UFM_RUN' ) or die( 'No direct script access allowed.' );
require_once('campaignAnswer.class.php');
class CampaignBounce2015IndexController extends UController
{
	public $useMasterDb = true;
	function actionValidation(){//debug('abc' );
		if(isset($_POST['form_action'])){
			$GLOBALS['user_id'] = $this->user->user_id;
			$action = safe_input($_POST['form_action']);
			
			$response = new stdClass();
			$response->error = 0;
			
			$model = new CampaignAnswer();
			$valid = $model->validPost();
			
			if($valid->error){
				$response->error = $valid->error;
			}
			else{
				if($_POST['form_page']=='5'){
					if(!$model->store()) $response->error = 'Data store failed!';
					if (isset($_COOKIE['campaign_bounce2015'])) {
						unset($_COOKIE['campaign_bounce2015']);
						setcookie('campaign_bounce2015', null, -1, '/');
						// setcookie('UHK_campaign_bounce2015[answer_2]', time()-3600, -1, '/');
						return true;
					}
				}else{
					if(isset($_POST['answer_1'])) uSetCookie('campaign_bounce2015[answer_1]',$_POST['answer_1']);
					if(isset($_POST['answer_2'])) uSetCookie('campaign_bounce2015[answer_2]',$_POST['answer_2']);
					if(isset($_POST['agree'])) uSetCookie('campaign_bounce2015[agree]',$_POST['agree']);
					
				}
			}
			echo json_encode($response);
		}else{
			$this->redirect('/error');
		} 
	}
	
	function actionIndex(){
		$imgDir = UAPP_BASE_URL.'/sys/view/campaign/bounce2015';
		$this->assign('imgDir',$imgDir);

		$user_id = $this->user->user_id;
		$username = uDb()->findOne('SELECT username FROM tbl_users WHERE user_id="'.$user_id.'"')->username;
		$this->assign('user_id',$user_id);//debug($user_id);
		$this->assign('username',$username);
		
		$isLogin = $user_id > 0  ? true:false;
		$isVoted = CampaignAnswer::exists('user_id',$user_id) ? true:false;
		$disabled = ( $user_id > 0 && !$isVoted) ? '': ' disabled ';
		
		$this->assign('isLogin',$isLogin);
		$this->assign('isVoted',$isVoted);
		$this->assign('disabled',$disabled);

		$subtitle = null;
		$canonicalUrl = $this->createUrl('/campaign/bounce2015/index');
		// echo "<script>console.log( 'Debug Objects: " . $canonicalUrl . "' );</script>";
		switch($_GET['p']){
			case 2:
			case 3:
			case 4:
			case 5:
			case 6:
				$canonicalUrl = $this->createUrl('/campaign/bounce2015/index',array('p'=>$_GET['p']));
				$this->view='/campaign/bounce2015/index'.$_GET['p'];
				$this->layout='directHtml2';
				$subtitle = 'Page'.$_GET['p'];
			break;
			
		}
		$this->addJs('js/global/jquery-1.8.2.min.js');
		//$this->addCss();
		
		// $this->metaOptions['canonicalUrl'];
		$this->metaOptions['canonicalUrl']=$canonicalUrl;
		$this->pageTitle = getMetaTitle('港生活送你100位 開心到彈起！ @BOUNCE室內彈床樂園 '.$subtitle);
        $this->metaKeywords = getMetaKeywords('香港, BOUNCE, 彈床, 樂園, 室內, 活動, 玩樂, 好玩, FUN, EXCITING, 刺激, SPORTS, 運動, 跳彈床, 試玩, 免費, GIFTAWAY, GIVEAWAY, 送禮, 港生活, HK港生活, 獎品, 禮品, 入場券, TICKET, PRIZE, GIFT, FREE, PRESENT, JUMP, FIT, 瘦身, 減肥, 會員, 專享, 門券');
        $this->metaDescription = getMetaDescription('會員立即報名，即有機會獲贈BOUNCE 一小時免費試玩入場券兩張，請您同朋友一齊盡情釋放香港人生活壓力，玩轉全港最大彈床樂園，開心到彈起！');
		
        $this->display();
	}
}
?>