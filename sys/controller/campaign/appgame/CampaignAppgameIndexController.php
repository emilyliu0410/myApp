<?php
defined( 'UFM_RUN' ) or die( 'No direct script access allowed.' );
require_once('campaignAnswer.class.php');
class CampaignAppgameIndexController extends UController
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
				if($_POST['form_page']=='6'){
					if(!$model->store()) $response->error = 'Data store failed!';
					if (isset($_COOKIE['campaign_hk1bd'])) {
						// unset($_COOKIE['campaign_trickeye2015']);
						uSetCookie('campaign_hk1bd', null, -1, '/');
						return true;
					}
				}else{
					if(isset($_POST['answer_1'])) uSetCookie('campaign_hk1bd[answer_1]',$_POST['answer_1'],0,"/");
					if(isset($_POST['answer_2'])) uSetCookie('campaign_hk1bd[answer_2]',$_POST['answer_2'],0,"/");
					if(isset($_POST['agree'])) uSetCookie('campaign_hk1bd[agree]',$_POST['agree'],0,"/");
					
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

		$subtitle = null;
		$canonicalUrl = $this->createUrl('/campaign/appgame');
		
		switch($_GET['p']){
			case 1:
			case 2:
			case 3:
			case 4:
			case 5:
			case 6:
			case 7:
			case 8:
				$canonicalUrl = $this->createUrl('/campaign/appgame',array('p'=>$_GET['p']));
				$this->view='/campaign/appgame/index'.$_GET['p'];
				// echo "<script>console.log( 'Debug Objects: " . $canonicalUrl . "' );</script>";
				$this->layout='directHtml2';
				$this->addJs('js/global/jquery-1.8.2.min.js');
				$subtitle = 'Page'.$_GET['p'];
			break;			
			case 'tc':
				$this->view='/campaign/appgame/tc';
				$this->layout='directHtml2';
				// $this->addJs('js/global/jquery-1.8.2.min.js');
				// $subtitle = 'Page'.$current_page;
				break;
			case 'personal':
				$this->view='/campaign/appgame/personal';
				$this->addJs('js/global/jquery-1.8.2.min.js');
				$subtitle = 'Page'.$current_page;
				$this->layout='directHtml2';
				break;
			case 'thankyou':
				$this->view='/campaign/appgame/thankyou';
				$this->addJs('js/global/jquery-1.8.2.min.js');
				$subtitle = 'Page'.$current_page;
				$this->layout='directHtml2';
				break;
		}
		
		// $this->addCss();
		$this->metaOptions['canonicalUrl'];
		$this->metaOptions['canonicalUrl']=$canonicalUrl;
		$this->pageTitle = getMetaTitle('HK港生活App全線上架！　為用家送上「港至有經典大獎」 '.$subtitle);
        $this->metaKeywords = getMetaKeywords('香港, 活動, App, 手機app, 手機程式, 港生活, HK港生活, 遊戲, 送禮, 獎品, 大獎, 免費, GIFTAWAY, GIVEAWAY, 下載, DOWNLOAD, 好去處, 香港周圍遊, 半島, HIGHTEA,下午茶,  food, 美食, 避風塘, 海鮮, 包艇,  OCEANPARK, 海洋公園, 全年通行證, 電影, 戲飛, 電車, PARTY, 包場, gift, member, 會員, 專享 ');
        $this->metaDescription = getMetaDescription('輕鬆贏取百張電影全年通行換票証，同時更有機會得到香港至有嘅特選經典大獎，包括半島High Tea、海洋公園全年通行証、避風塘包船海鮮套餐及電車包場Party等，火速下載 [HK港生活]，體驗、分享、攞大獎！');
        $this->layout = 'directHtml2';
		$this->display();
	}
}
?>