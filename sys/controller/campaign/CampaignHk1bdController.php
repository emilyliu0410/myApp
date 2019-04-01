<?php
defined( 'UFM_RUN' ) or die( 'No direct script access allowed.' );
require_once('hk1bd/campaignAnswer.class.php');
class CampaignHk1bdController extends UController
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
		$imgDir = UAPP_BASE_URL.'/sys/view/campaign/hk1bd';
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
		$canonicalUrl = $this->createUrl('/campaign/hk1bd');
		
		switch($_GET['p']){
			case 2:
			case 3:
			case 4:
			case 5:
			case 6:
			case 7:
				$canonicalUrl = $this->createUrl('/campaign/hk1bd',array('p'=>$_GET['p']));
				$this->view='/campaign/hk1bd/index'.$_GET['p'];
				// echo "<script>console.log( 'Debug Objects: " . $canonicalUrl . "' );</script>";
				$this->layout='directHtml2';
				$this->addJs('js/global/jquery-1.8.2.min.js');
				$subtitle = 'Page'.$_GET['p'];
			break;
			case tc:
				$this->view='/campaign/hk1bd/tc';
				$this->layout = 'directHtml2';
		}
		
		// $this->addCss();
		$this->metaOptions['canonicalUrl'];
		$this->metaOptions['canonicalUrl']=$canonicalUrl;
		$this->pageTitle = getMetaTitle('【港生活一歲啦！】Hard Core Fans募集！'.$subtitle);
        $this->metaKeywords = getMetaKeywords('香港, 活動, 生日, 生日快樂, birthday, happybirthday, 免費, 餐飲券, 餐券, free, restaurant, 餐廳, GIFTAWAY, GIVEAWAY, 送禮, 港生活, HK港生活, 獎品, 禮品, voucher, food, 美食, 堤岸餐廳及酒吧, 皇家太平洋酒店, tasting, gift, member, 會員, 專享 ');
        $this->metaDescription = getMetaDescription('港生活一歲啦！現募集港生活嘅Hardcore Fans，為我哋自創一句口號，為答謝大家對我哋嘅支持同厚愛，港生活將會送您$1,000餐飲券共20份，俾您望住無敵海景品嘗美食享受港生活！ 立即打開創意台，分享您嘅港Slogan啦！');
        $this->layout = 'directHtml2';
		$this->display();
	}
}
?>