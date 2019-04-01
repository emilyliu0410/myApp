<?php
defined( 'UFM_RUN' ) or die( 'No direct script access allowed.' );
require_once('campaignValue.php');
require_once('campaignAnswer.class.php');
class CampaignSma2017VotesController extends UController
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
			}else{
				
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
		$imgDir = UAPP_BASE_URL.'/sys/view/campaign/sma2017/';
		$this->assign('imgDir',$imgDir);
		
		$htmlDir=UAPP_BASE_URL.'/campaign/sma2017/';
		$this->assign('htmlDir',$htmlDir);

		$subtitle = null;
		$canonicalUrl = UAPP_HOST.UAPP_BASE_URL.'/campaign/sma2017/';
		$this->view='/campaign/sma2017/votes';
		$this->layout='campaign';
		
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
		
		array_push($this->cssFiles, $imgDir.'dist/css/bootstrap.min.css?v='.CAMPAIGN_CACHE);
		array_push($this->cssFiles, $imgDir.'assets/css/ie10-viewport-bug-workaround.css?v='.CAMPAIGN_CACHE);
		array_push($this->cssFiles, $imgDir.'css/navbar-fixed-top.css?v='.CAMPAIGN_CACHE);
		array_push($this->cssFiles, $imgDir.'css/grid.css?v='.CAMPAIGN_CACHE);
		array_push($this->cssFiles, $imgDir.'css/justified-nav.css?v='.CAMPAIGN_CACHE);
		array_push($this->cssFiles, $imgDir.'css/sticky-footer.css?v='.CAMPAIGN_CACHE);
		array_push($this->cssFiles, $imgDir.'css/newStyle.css?v='.CAMPAIGN_CACHE);
		

		$this->metaOptions['facebook'] = uGetFacebookMetas(CAMPAIGN_META_IMAGE, 5);
		$this->metaOptions['canonicalUrl']=$canonicalUrl;
		$this->pageTitle = CAMPAIGN_META_TITLE.' - 立即投票';
        $this->metaKeywords = getMetaKeywords(CAMPAIGN_META_KEYWORD);
        $this->metaDescription = getMetaDescription(CAMPAIGN_META_DESCRIPTION);
		
        $this->display();
	}
}
?>