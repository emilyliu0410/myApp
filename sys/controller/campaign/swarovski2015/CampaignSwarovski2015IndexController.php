<?php
defined( 'UFM_RUN' ) or die( 'No direct script access allowed.' );
require_once('campaignAnswer.class.php');
class CampaignSwarovski2015IndexController extends UController
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
				debug('111');
			// $this->redirect('/error');
		} 
	}
	
	function actionIndex(){
		$imgDir = UAPP_BASE_URL.'/sys/view/campaign/swarovski2015';
		$this->assign('imgDir',$imgDir);
		
		$share_url=UAPP_HOST . UAPP_BASE_URL . '/campaign/swarovski2015/index.php';
		// $fb_like_share_button = facebook_like_share_button($share_url); 
		$this->assign("facebookLikeShareButton", facebook_like_share_button($share_url));
		
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
		$canonicalUrl = $this->createUrl('/campaign/swarovski2015');
		
		$current_page = $_POST['form_page']?$_POST['form_page']++:$_GET['p'];
		
		switch($current_page){
			case 2:
			case 3:
			case 4:
			case 5:
			case 6:
				$canonicalUrl = $this->createUrl('/campaign/swarovski2015',array('p'=>$current_page));
				$this->view='/campaign/swarovski2015/index'.$current_page;
				// echo "<script>console.log( 'Debug Objects: " . $canonicalUrl . "' );</script>";
				$this->layout='directHtml2';
				$this->addJs('js/global/jquery-1.8.2.min.js');
				$subtitle = 'Page'.$current_page;
			break;
			case 'tc':
				$canonicalUrl = $this->createUrl('/campaign/swarovski2015',array('p'=>$current_page));
				$this->view='/campaign/swarovski2015/tc';
				$this->layout='directHtml2';
				$this->addJs('js/global/jquery-1.8.2.min.js');
				$subtitle = 'Page'.$current_page;
				break;
			case 'personal':
				$canonicalUrl = $this->createUrl('/campaign/swarovski2015',array('p'=>$current_page));
				$this->view='/campaign/swarovski2015/personal';
				$this->layout='directHtml2';
				$this->addJs('js/global/jquery-1.8.2.min.js');
				$subtitle = 'Page'.$current_page;
				break;
			case 'thankyou':
				$canonicalUrl = $this->createUrl('/campaign/swarovski2015',array('p'=>$current_page));
				$this->view='/campaign/swarovski2015/thankyou';
				$this->layout='directHtml2';
				$this->addJs('js/global/jquery-1.8.2.min.js');
				$subtitle = 'Page'.$current_page;
				break;
		}
		
		// $this->addCss();
		$this->metaOptions['canonicalUrl'];
		$this->metaOptions['canonicalUrl']=$canonicalUrl;
		$this->pageTitle = getMetaTitle('【HK港生活呈獻】SWAROVSKI閃亮聖誕Perfect Gift   -　HK港生活 '.$subtitle);
        $this->metaKeywords = getMetaKeywords('香港, 活動, App, 手機app, 手機程式, 港生活, HK港生活, 遊戲, 送禮, 獎品, 禮物, 免費, GIFTAWAY, GIVEAWAY, SWAROVSKI, 水晶, 首飾, 手鏈, 閃亮, 聖誕,  CHRISTMAS, XMAS, 燈飾, 裝潢, 佈置,  雪, winter, 冬天, gift, 機場, 聖誕樹, PARTY, bling, member, 會員, 專享 ');
        $this->metaDescription = getMetaDescription('Swarovski聖誕裝飾今年載譽歸來，為港人帶來閃亮聖誕！HK港生活特地聯同Swarovski為各位粉絲送上Perfect Gift！立即睇片分享，贏取您的聖誕禮物！');
        $this->layout = 'directHtml2';
		$this->display();
	}
}
?>