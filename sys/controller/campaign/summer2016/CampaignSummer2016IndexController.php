<?php
defined( 'UFM_RUN' ) or die( 'No direct script access allowed.' );
require_once('campaignAnswer.class.php');
class CampaignSummer2016IndexController extends UController
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
					if (isset($_COOKIE['campaign_summer2016'])) {
						unset($_COOKIE['campaign_summer2016']);
						setcookie('campaign_summer2016', null, -1, '/');
						// setcookie('UHK_campaign_summer2016[answer_2]', time()-3600, -1, '/');
						return true;
					}
				}else{
					if(isset($_POST['answer_1'])) uSetCookie('campaign_summer2016[answer_1]',$_POST['answer_1']);
					if(isset($_POST['answer_2'])) uSetCookie('campaign_summer2016[answer_2]',$_POST['answer_2']);
					if(isset($_POST['agree'])) uSetCookie('campaign_summer2016[agree]',$_POST['agree']);
					
				}
			}
			echo json_encode($response);
		}else{
			$this->redirect('/error');
		} 
	}
	
	function actionIndex(){
		$imgDir = UAPP_BASE_URL.'/sys/view/campaign/summer2016';
		$this->assign('imgDir',$imgDir);

		$user_id = $this->user->user_id;
		$username = uDb()->findOne('SELECT username FROM tbl_users WHERE user_id="'.$user_id.'"')->username;
		$useremail = uDb()->findOne('SELECT email FROM tbl_users WHERE user_id="'.$user_id.'"')->email;
		$this->assign('user_id',$user_id);//debug($user_id);
		$this->assign('username',$username);
		$this->assign('useremail',$useremail);
		
		$isLogin = $user_id > 0  ? true:false;
		$isVoted = CampaignAnswer::exists('user_id',$user_id) ? true:false;
		$disabled = ( $user_id > 0 && !$isVoted) ? '': ' disabled ';
		
		$this->assign('isLogin',$isLogin);
		$this->assign('isVoted',$isVoted);
		$this->assign('disabled',$disabled);

		$subtitle = null;
		$canonicalUrl = $this->createUrl('/campaign/cummer2016/index');
		// echo "<script>console.log( 'Debug Objects: " . $canonicalUrl . "' );</script>";
		switch($_GET['p']){
			case 2:
			case 3:
			case 4:
			case 5:
			case 6:
				$canonicalUrl = $this->createUrl('/campaign/summer2016/index',array('p'=>$_GET['p']));
				$this->view='/campaign/summer2016/index'.$_GET['p'];
				$this->layout='directHtml2';
				$subtitle = 'Page'.$_GET['p'];
			break;
			default:
			$this->layout='directHtml2';
			break;
		}
		$this->addJs('js/global/jquery-1.8.2.min.js');
		$this->addJs('https://code.jquery.com/ui/1.10.1/jquery-ui.min.js');
		//$this->addCss();
		
		// $this->metaOptions['canonicalUrl'];
		$this->metaOptions['canonicalUrl']=$canonicalUrl;
		$this->pageTitle = getMetaTitle('投奔初夏！HK港生活送您特選Gift Set'.$subtitle);
        $this->metaKeywords = getMetaKeywords('HK港生活, 港生活, sembreak, 暑假, 夏天, 初夏, 禮物, 送禮, giftaway, giveaway, gift, giftset, 禮品, 抽獎, 大獎, 獎品, present, 活動, 夏日, summer, luckydraw, 會員, ulifestyle, uhk, 慶祝, 投奔初夏, 戲飛, 送戲飛, Columbia, 水樽, columbia水樽, 化妝袋, 手袋, handbag, notebook');
        $this->metaDescription = getMetaDescription('終於等到Sem Break啦～打工仔們覺得「這些機會不屬於我」？為求普天同慶迎接初夏來臨，HK港生活特地為Fans送上心意Gift Set，
立即分享您今年夏天最期待嘅活動，贏取特選Gift Set啦！
');
		
        $this->display();
	}
}
?>