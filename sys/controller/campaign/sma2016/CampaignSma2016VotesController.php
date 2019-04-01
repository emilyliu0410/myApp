<?php
defined( 'UFM_RUN' ) or die( 'No direct script access allowed.' );
require_once('campaignAnswer.class.php');
class CampaignSma2016VotesController extends UController
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
					if(!$model->store()) 
						$response->error = 'Data store failed!';

				}
			}
			echo json_encode($response);
		}else{
			$this->redirect('/error');
		} 
	}
	
	function actionIndex(){
		$imgDir = UAPP_BASE_URL.'/sys/view/campaign/sma2016/';
		$this->assign('imgDir',$imgDir);
		
		$htmlDir=UAPP_BASE_URL.'/html/campaign/sma2016/';
		$this->assign('htmlDir',$htmlDir);

		$user_id = $this->user->user_id;
		$username = uDb()->findOne('SELECT username FROM tbl_users WHERE user_id="'.$user_id.'"')->username;
		$useremail = uDb()->findOne('SELECT email FROM tbl_users WHERE user_id="'.$user_id.'"')->email;
		$this->assign('user_id',$user_id);
		$this->assign('username',$username);
		$this->assign('useremail',$useremail);
		
		$isLogin = $user_id > 0  ? true:false;
		// $isVoted = CampaignAnswer::exists('user_id',$user_id) ? true:false;
		$isVoted = false;
		$disabled = ( $user_id > 0 && !$isVoted) ? '': ' disabled ';
		
		$this->assign('isLogin',$isLogin);
		$this->assign('isVoted',$isVoted);
		$this->assign('disabled',$disabled);

		$subtitle = null;
		$canonicalUrl = $this->createUrl('/campaign/sma2016/votes');
		$this->view='/campaign/sma2016/votes';
		$this->layout='directHtml2';
		
		// $this->addJs('js/global/jquery-1.8.2.min.js');
		$this->addJs('https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js');
		$this->addJs('https://code.jquery.com/ui/1.10.1/jquery-ui.min.js');
		//$this->addCss();
		
		// $this->metaOptions['canonicalUrl'];
		$this->metaOptions['canonicalUrl']=$canonicalUrl;
		$this->pageTitle = getMetaTitle('我最喜愛商場選舉2016');
        $this->metaKeywords = getMetaKeywords('香港, 免費, 送禮, 獎品, HK港生活, BOUNCE, 彈床, TRICKEYE, 3D, 戲飛, 朱古力, 酒店, 下午茶, GIVEAWAY, 天GIFT, 抽獎, giveaway, giftaway, free, 獎品免費, 餐券, 會員, 專享, 活動, 送禮, 有獎遊戲, 露營車, 車camp');
        $this->metaDescription = getMetaDescription('會員立即試用網站新版，搜尋本地食買玩住資訊，即有機會獲贈免費海迎灘豪華露營車住宿一晚、BOUNCE 彈床樂園試玩卷、Trick Eye Museum 3D館入場券、Azzita電動平衝車試玩券、酒店High Tea或Hershey’s朱古力套裝！
100份玩樂大獎齊齊Fun，立即參加啦！
');
		
        $this->display();
	}
}
?>