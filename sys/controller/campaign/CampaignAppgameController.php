<?php
defined( 'UFM_RUN' ) or die( 'No direct script access allowed.' );
require_once('appgame/campaignAnswer.class.php');
class CampaignAppgameController extends UController
{
	public $useMasterDb = true;
	function actionValidation(){
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
						// unset($_COOKIE['campaign_bounce2015']);
						uSetCookie('campaign_bounce2015', null, -1, '/');
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
		$imgDir = UAPP_BASE_URL.'/sys/view/campaign/appgame';
		$this->assign('imgDir',$imgDir);

		$user_id = $this->user->user_id;
		$username = uDb()->findOne('SELECT username FROM tbl_users WHERE user_id="'.$user_id.'"')->username;
		$this->assign('user_id',$user_id);
		$this->assign('username',$username);

		
		
		$isLogin = $user_id > 0  ? true:false;
		$isVoted = CampaignAnswer::exists('user_id',$user_id) ? true:false;
		$disabled = ( $user_id > 0 && !$isVoted) ? '': ' disabled ';
		
		$this->assign('isLogin',$isLogin);
		$this->assign('isVoted',$isVoted);
		$this->assign('disabled',$disabled);
		
		foreach($_POST as $k=>$v){
			if(!in_array($k,array('form_page','form_action')))
				$answers[$k]=$v;
		}
		$this->assign('answers',$answers);

		$subtitle = null;
		$canonicalUrl = $this->createUrl('/campaign/appgame');
		
		$current_page = $_POST['form_page']?$_POST['form_page']++:$_GET['p'];
		
		switch($current_page){
			case 2:
			case 3:
			case 4:
			case 5:
			case 6:
				$canonicalUrl = $this->createUrl('/campaign/appgame',array('p'=>$current_page));
				$this->view='/campaign/appgame/index'.$current_page;
				// echo "<script>console.log( 'Debug Objects: " . $canonicalUrl . "' );</script>";
				$this->layout='directHtml2';
				$this->addJs('js/global/jquery-1.8.2.min.js');
				$subtitle = 'Page'.$current_page;
			break;
			case 'tc':
				$canonicalUrl = $this->createUrl('/campaign/appgame',array('p'=>$current_page));
				$this->view='/campaign/appgame/tc';
				$this->layout='directHtml2';
				$this->addJs('js/global/jquery-1.8.2.min.js');
				$subtitle = 'Page'.$current_page;
				break;
			case 'personal':
				$canonicalUrl = $this->createUrl('/campaign/appgame',array('p'=>$current_page));
				$this->view='/campaign/appgame/personal';
				$this->layout='directHtml2';
				$this->addJs('js/global/jquery-1.8.2.min.js');
				$subtitle = 'Page'.$current_page;
				break;
			case 'thankyou':
				$canonicalUrl = $this->createUrl('/campaign/appgame',array('p'=>$current_page));
				$this->view='/campaign/appgame/thankyou';
				$this->layout='directHtml2';
				$this->addJs('js/global/jquery-1.8.2.min.js');
				$subtitle = 'Page'.$current_page;
				break;
		}
		
		// $this->addCss();
		$this->metaOptions['canonicalUrl'];
		$this->metaOptions['canonicalUrl']=$canonicalUrl;
		$this->pageTitle = getMetaTitle('HK港生活App全線上架！　為用家送上「港至有經典大獎」 '.$subtitle);
        $this->metaKeywords = getMetaKeywords('香港, BOUNCE, 彈床, 樂園, 室內, 活動, 玩樂, 好玩, FUN, EXCITING, 刺激, SPORTS, 運動, 跳彈床, 試玩, 免費, GIFTAWAY, GIVEAWAY, 送禮, 港生活, HK港生活, 獎品, 禮品, 入場券, TICKET, PRIZE, GIFT, FREE, PRESENT, JUMP, FIT, 瘦身, 減肥, 會員, 專享, 門券');
        $this->metaDescription = getMetaDescription('會員立即報名，即有機會獲贈BOUNCE 一小時免費試玩入場券兩張，請您同朋友一齊盡情釋放香港人生活壓力，玩轉全港最大彈床樂園，開心到彈起！');
        $this->layout = 'directHtml2';
		$this->display();
	}
}
?>