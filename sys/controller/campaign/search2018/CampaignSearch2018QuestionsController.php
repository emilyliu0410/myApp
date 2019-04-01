<?php
defined( 'UFM_RUN' ) or die( 'No direct script access allowed.' );
require_once(dirname(__FILE__).'/includes.php');

class CampaignSearch2018QuestionsController extends UController
{
	 var $page_name = 'questions';
	 var $page_title = '參加表格';
	 public $useMasterDb = true;
	function actionIndex(){
		global $user_id;
		if($this->user->isLogin()){
			$arr = CampaignAnswer::exists('user_id',$this->user->user_id);
			$is_user_exists = count($arr);
			$user_id = $this->user->user_id;
		}else{
			$this->redirect('/campaign/'.CAMPAIGN_NAME.'/index');
		}
		$this->assign('is_user_exists',$is_user_exists);
		
		$imgDir = UAPP_BASE_URL.'/sys/view/campaign/'.CAMPAIGN_NAME.'/';
		$this->assign('imgDir',$imgDir);
		
		$htmlDir=UAPP_BASE_URL.'/campaign/'.CAMPAIGN_NAME.'/';
		$this->assign('htmlDir',$htmlDir);

		$subtitle = null;
		$canonicalUrl = $this->createUrl('/campaign/'.CAMPAIGN_NAME.'/'.$this->page_name);
		$this->view=CAMPAIGN_LAYOUT;
		$this->layout='campaign';
		$this->assign('view',$this->page_name);
		

		array_push($this->cssFiles, $imgDir.'css/font-awesome-4.7.0/css/font-awesome.min.css?v='.CAMPAIGN_CACHE);	
		array_push($this->cssFiles, $imgDir.'css/bootstrap.min.css?v='.CAMPAIGN_CACHE);
		array_push($this->cssFiles, $imgDir.'css/ie10-viewport-bug-workaround.css?v='.CAMPAIGN_CACHE);
		array_push($this->cssFiles, $imgDir.'css/newStyle.css?v='.CAMPAIGN_CACHE);
		array_push($this->cssFiles, $imgDir.'js/ie-emulation-modes-warning.js?v='.CAMPAIGN_CACHE);
		
		$this->metaOptions['facebook'] = uGetFacebookMetas(CAMPAIGN_META_IMAGE, 5);
		$this->metaOptions['canonicalUrl']=$canonicalUrl;
		$this->pageTitle = $this->page_title.' | '.CAMPAIGN_META_TITLE;
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
				$successUrl = $this->createUrl('/campaign/'.CAMPAIGN_NAME.'/form');
				break;
				case 'form':
				$valid = $model->validPostForm();
				$successUrl = $this->createUrl('/campaign/'.CAMPAIGN_NAME.'/tnc_form');
				break;
				case 'tnc':
				$valid = $model->validPostTnc();
				$successUrl = $this->createUrl('/campaign/'.CAMPAIGN_NAME.'/share');
				break;
				case 'share':
				$valid = $model->validPostQuestion();
				$valid = $valid->error?$valid:$model->validPostForm();
				$valid = $valid->error?$valid:$model->validPostTnc();
				$successUrl = $this->createUrl('/campaign/'.CAMPAIGN_NAME.'/thanks');
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

			$successUrl = $this->createUrl('/campaign/'.CAMPAIGN_NAME.'/thanks');

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