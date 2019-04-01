<?php
defined( 'UFM_RUN' ) or die( 'No direct script access allowed.' );
require_once('trickeye2015/campaignAnswer.class.php');
class CampaignTrickeye2015Controller extends UController
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
					if (isset($_COOKIE['campaign_trickeye2015'])) {
						// unset($_COOKIE['campaign_trickeye2015']);
						uSetCookie('campaign_trickeye2015', null, -1, '/');
						return true;
					}
				}else{
					if(isset($_POST['answer_1'])) uSetCookie('campaign_trickeye2015[answer_1]',$_POST['answer_1'],0,"/");
					if(isset($_POST['answer_2'])) uSetCookie('campaign_trickeye2015[answer_2]',$_POST['answer_2'],0,"/");
					if(isset($_POST['agree'])) uSetCookie('campaign_trickeye2015[agree]',$_POST['agree'],0,"/");
					
				}
			}
			echo json_encode($response);
		}else{
			$this->redirect('/error');
		} 
	}
	
	function actionIndex(){
		$imgDir = UAPP_BASE_URL.'/sys/view/campaign/trickeye2015';
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
		$canonicalUrl = $this->createUrl('/campaign/trickeye2015');
		
		switch($_GET['p']){
			case 2:
			case 3:
			case 4:
			case 5:
			case 6:
				$canonicalUrl = $this->createUrl('/campaign/trickeye2015',array('p'=>$_GET['p']));
				$this->view='/campaign/trickeye2015/index'.$_GET['p'];
				// echo "<script>console.log( 'Debug Objects: " . $canonicalUrl . "' );</script>";
				$this->layout='directHtml2';
				$this->addJs('js/global/jquery-1.8.2.min.js');
				$subtitle = 'Page'.$_GET['p'];
			break;
		}
		
		// $this->addCss();
		$this->metaOptions['canonicalUrl'];
		$this->metaOptions['canonicalUrl']=$canonicalUrl;
		$this->pageTitle = getMetaTitle('港生活 送你@ Trick Eye Museum特麗愛3D美術館　200位免費瘋狂Selfie！ '.$subtitle);
        $this->metaKeywords = getMetaKeywords('香港, trickeye, 3d, 立體, 3d畫, 活動, 玩樂, 好玩, FUN, 藝術, 美術, art, 畫, 影相, 拍照, 免費, GIFTAWAY, GIVEAWAY, 送禮, 港生活, HK港生活, 獎品, 禮品, 入場券, TICKET, PRIZE, GIFT, FREE, PRESENT, JUMP, FIT, selfie, 自拍, 自拍館, 會員, 專享, 門券');
        $this->metaDescription = getMetaDescription('會員立即報名，即有機會獲贈Trick Eye Museum香港特麗愛3D美術館入場券兩張，請您同朋友闖入Trick Eye3D美術館，齊齊瘋狂Selfie，3D視覺效果 + 震憾性靚相包你最多Like！');
        $this->display();
	}
}
?>