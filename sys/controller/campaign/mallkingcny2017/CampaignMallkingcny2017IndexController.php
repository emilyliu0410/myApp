<?php
defined( 'UFM_RUN' ) or die( 'No direct script access allowed.' );
require_once('campaignAnswer.class.php');
class CampaignMallkingcny2017IndexController extends UController
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
					if (isset($_COOKIE['campaign_mallkingcny2017'])) {
						unset($_COOKIE['campaign_mallkingcny2017']);
						setcookie('campaign_mallkingcny2017', null, -1, '/');
						// setcookie('UHK_campaign_mallkingcny2017[answer_2]', time()-3600, -1, '/');
						return true;
					}
				}else{
					if(isset($_POST['answer_1'])) uSetCookie('campaign_mallkingcny2017[answer_1]',$_POST['answer_1']);
					if(isset($_POST['answer_2'])) uSetCookie('campaign_mallkingcny2017[answer_2]',$_POST['answer_2']);
					if(isset($_POST['agree'])) uSetCookie('campaign_mallkingcny2017[agree]',$_POST['agree']);
					
				}
			}
			echo json_encode($response);
		}else{
			$this->redirect('/error');
		} 
	}
	
	function actionIndex(){
		$imgDir = UAPP_BASE_URL.'/sys/view/campaign/mallkingcny2017';
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
		$canonicalUrl = $this->createUrl('/campaign/mallkingcny2017/index');
		// echo "<script>console.log( 'Debug Objects: " . $canonicalUrl . "' );</script>";
		switch($_GET['p']){
			case 2:
			case 3:
			case 4:
			case 5:
			case 6:
				$canonicalUrl = $this->createUrl('/campaign/mallkingcny2017/index',array('p'=>$_GET['p']));
				$this->view='/campaign/mallkingcny2017/index'.$_GET['p'];
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
		$this->pageTitle = getMetaTitle('Mall王新春呈獻  送您130份限量版商場利是封'.$subtitle);
        $this->metaKeywords = getMetaKeywords('Mall王, 新年, 利是, 利是封, 商場, 送利是封, 免費利是封, 又一城, 太古城中心, 荃灣商場, YOHO Mall, MOKO 新世紀廣場, 黃埔新天地, 荷里活廣場, 荃新天地, Citywalk, The One, 朗豪坊, 皇室堡, 新地九大商場, Mikiki, 新城市廣場, The East, MTR Malls');
        $this->metaDescription = getMetaDescription('又是時候送猴迎雞！Mall王已經準備好同大家一齊迎春接福啦！已特意為各位Fans搜羅各大商場新春利是封，款款設計均別出心裁，實行派利是同收利是都咁開心！由即日起至2017年1月19日或以前，Mall王Fans 只要揀選您最喜愛的商場利是封及新春佈置，即有機會獲得由各大商場送出之利是封或精品乙份，立即參加啦！
');
		
        $this->display();
	}
}
?>