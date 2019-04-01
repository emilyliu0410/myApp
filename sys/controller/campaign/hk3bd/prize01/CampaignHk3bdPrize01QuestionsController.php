<?php
defined( 'UFM_RUN' ) or die( 'No direct script access allowed.' );
require_once(dirname(__FILE__).'/includes.php');

class CampaignHk3bdPrize01QuestionsController extends UController
{
	public $useMasterDb = true;
	function actionIndex(){
		global $user_id, $prize_list;
		if(CAMPAIGN_PERIOD!=SELF_PERIOD)
			$this->redirect('/campaign/hk3bd/index');
		
		
		if($this->user->isLogin()){
			$arr = CampaignAnswer::exists('user_id',$this->user->user_id);
			$is_user_exists = count($arr);
			$user_id = $this->user->user_id;
		}else{
			$this->redirect('/campaign/hk3bd/index');
		}
		
		$this->assign('is_user_exists',$is_user_exists);
		$this->assign('prize_list',$prize_list);
		
		$imgDir = UAPP_BASE_URL.'/sys/view/campaign/hk3bd/';
		$this->assign('imgDir',$imgDir);
		
		$htmlDir=UAPP_BASE_URL.'/campaign/hk3bd/';
		$this->assign('htmlDir',$htmlDir);

		$subtitle = null;
		$canonicalUrl = $this->createUrl('/campaign/hk3bd/'.CAMPAIGN_PERIOD.'/questions');
		$this->view='/campaign/hk3bd/questions/'.CAMPAIGN_PERIOD.'/questions';
		$this->layout='campaign';
		
		array_push($this->cssFiles, $imgDir.'dist/css/bootstrap.min.css?v='.CAMPAIGN_CACHE);
		array_push($this->cssFiles, $imgDir.'assets/css/ie10-viewport-bug-workaround.css?v='.CAMPAIGN_CACHE);
		array_push($this->cssFiles, $imgDir.'css/navbar-fixed-top.css?v='.CAMPAIGN_CACHE);
		array_push($this->cssFiles, $imgDir.'css/grid.css?v='.CAMPAIGN_CACHE);
		array_push($this->cssFiles, $imgDir.'css/justified-nav.css?v='.CAMPAIGN_CACHE);
		array_push($this->cssFiles, $imgDir.'css/newStyle.css?v='.CAMPAIGN_CACHE);
		array_push($this->cssFiles, $imgDir.'css/newStyle.php?v='.CAMPAIGN_CACHE);
		array_push($this->cssFiles, $imgDir.'assets/js/ie-emulation-modes-warning.js?v='.CAMPAIGN_CACHE);
		
		$this->metaOptions['facebook'] = uGetFacebookMetas(CAMPAIGN_META_IMAGE, 5);
		$this->metaOptions['canonicalUrl']=$canonicalUrl;
		$this->pageTitle = '參加表格 | '.CAMPAIGN_META_TITLE;
        $this->metaKeywords = getMetaKeywords(CAMPAIGN_META_KEYWORD);
        $this->metaDescription = getMetaDescription(CAMPAIGN_META_DESCRIPTION);
        $this->display();
	}
	function actionValidation(){
		global $user_id;
		if($this->user->isLogin()){
			$user_id = $this->user->user_id;
		}
		if(isset($_POST['form_action'])&&isset($_GET['type'])){
//			$GLOBALS['user_id'] = $this->user->user_id;
//			$action = safe_input($_POST['form_action']);
		
			$response = new stdClass();
			$valid = new stdClass();
			$response->error = 0;
			$successUrl = '';
			$model = new CampaignAnswer();
			
			switch($_GET['type']){
				case 'question':
				$valid = $model->validPostQuestion();
				$successUrl = $this->createUrl('/campaign/hk3bd/'.CAMPAIGN_PERIOD.'/tnc_form');
				break;
				case 'form':
				$valid = $model->validPostForm();
				$successUrl = $this->createUrl('/campaign/hk3bd/'.CAMPAIGN_PERIOD.'/share');
				break;
				case 'tnc':
				$valid = $model->validPostTnc();
				$successUrl = $this->createUrl('/campaign/hk3bd/'.CAMPAIGN_PERIOD.'/form');
				break;
				case 'share':
				$valid = $model->validPostQuestion();
				$valid = $valid->error?$valid:$model->validPostForm();
				$valid = $valid->error?$valid:$model->validPostTnc();
				$successUrl = $this->createUrl('/campaign/hk3bd/'.CAMPAIGN_PERIOD.'/thanks');
				break;
				
				default:
				$valid->error = 'Invalid Form Request';
				break;
			}

			if($valid->error){
				$response->error = $valid->error;
			}else{
				$response->successUrl = $successUrl;
			}
			echo json_encode($response);
		}else{
			$this->redirect('/error');
		} 
	}
	function actionSubmit(){
		global $user_id;
		if($this->user->isLogin()){
			$user_id = $this->user->user_id;
		}
		if(isset($_POST['form_action'])){
//			$GLOBALS['user_id'] = $this->user->user_id;
//			$action = safe_input($_POST['form_action']);
			
			$response = new stdClass();
			$valid = new stdClass();
			$response->error = 0;
			$successUrl = '';
			$model = new CampaignAnswer();

			$valid = $model->validPost();

			$successUrl = $this->createUrl('/campaign/hk3bd/'.CAMPAIGN_PERIOD.'/thanks');

			if($valid->error){
				$response->error = $valid->error;
			}else{
				if(!$model->store()){
					$response->error = 'Data store failed!';
				}else{					
					$response->successUrl = $successUrl;
				}
			}
			echo json_encode($response);
		}else{
			$this->redirect('/error');
		} 
	}
}
?>