<?php
defined( 'UFM_RUN' ) or die( 'No direct script access allowed.' );
require_once('campaignAnswer.class.php');
class CampaignMy1516IndexController extends UController
{
	public $useMasterDb = true;
	public $file = '/campaign/my1516/index.html';
	
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
				
				if($_POST['form_page']=='11'){
					if(!$ans_id=$model->store()) 
						$response->error = 'Data store failed!';
						else
							$response->result_url = UAPP_BASE_URL.$this->file.'?p=result&id='.url_encrypt($ans_id);
					
					if (isset($_COOKIE['campaign_my1516'])) {
						// unset($_COOKIE['campaign_bounce2015']);
						uSetCookie('campaign_my1516', null, -1, '/');
						return true;
					}
				}else{
					for($j=1;$j<12;$j++){
						if(isset($_POST['answer_'.$j])) uSetCookie('campaign_my1516[answer_'.$j.']',$_POST['answer_'.$j],0,"/");
					}
					for($i=1;$i<13;$i++){
						if(isset($_POST['answer_3_mood'.$i])) uSetCookie('campaign_my1516[answer_3_mood'.$i.']',$_POST['answer_3_mood'.$i],0,"/");
						if(isset($_POST['answer_3_reason'.$i])) uSetCookie('campaign_my1516[answer_3_reason'.$i.']',$_POST['answer_3_reason'.$i],0,"/");
					}
				}
			}
			echo json_encode($response);
		}else{
			$this->redirect('/error');
		} 
	}
	
	function actionIndex(){
		$imgDir = UAPP_BASE_URL.'/sys/view/campaign/my1516';
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
		
		foreach($_POST as $k=>$v){
			if(!in_array($k,array('form_page','form_action')))
				$answers[$k]=$v;
		}
		$this->assign('answers',$answers);

		$subtitle = null;
		$canonicalUrl = $this->createUrl('/campaign/my1516/index');
		
		$current_page = $_POST['form_page']?$_POST['form_page']++:$_GET['p'];
		$this->view='/campaign/my1516/index';
		$next_url = UAPP_BASE_URL.$this->file.'?p=q1';
		switch($current_page){
			case 'q1':
			case 'q2':
			case 'q3':
			case 'q4':
			case 'q5':
			case 'q6':
			case 'q7':
			case 'q8':
			case 'q9':
			case 'q10':
				$canonicalUrl = $this->createUrl('/campaign/my1516/index',array('p'=>$current_page));
				$this->view='/campaign/my1516/'.$current_page;
				$int_current_page = substr($current_page, 1);
				$subtitle = 'Question '.$int_current_page;
				$next_url = UAPP_BASE_URL.$this->file.'?p=q'.((int)$int_current_page+1);
			break;
			case 'q11':
				$canonicalUrl = $this->createUrl('/campaign/my1516/index',array('p'=>$current_page));
				$this->view='/campaign/my1516/'.$current_page;
				$subtitle = 'Question '.$current_page;
				$next_url = UAPP_BASE_URL.$this->file.'?p=result';
			break;
			case 'result':
				$ans_id=url_decrypt($_GET['id']);
				$ans_id=$ans_id>0?$ans_id:0;
				$sql = 'SELECT *
						FROM tbl_campaign_my1516_ans
						WHERE ans_id='.$ans_id;
				$answers = uDb()->findOne($sql);
				if($answers){
					$canonicalUrl = $this->createUrl('/campaign/my1516/index',array('p'=>$current_page,'id'=>$_GET['id']));
					$this->view='/campaign/my1516/result';
					$subtitle = 'Page '.$current_page;
					$next_url = UAPP_BASE_URL.$this->file.'?p=result';
					$share_url = UAPP_BASE_URL.$this->file.'?p=share&id='.$_GET['id'];
					
					$count_happy=0;
					$result=0;
					$this->assign('answers',$answers);
					$this->assign('share_url',$share_url);
					for($i=1;$i<=12;$i++){
						if($answers->{'answer_3_mood'.$i}=='1.開心'){
							$count_happy++;
						}
					}
					
					switch($count_happy){
						case 0:
						case 1:
						case 2:
						case 3:
							$result=30;
						break;
						case 4:
							$result=50;
						break;
						case 5:
							$result=70;
						break;
						default:
							$result=100;
						break;
					}
					$this->assign('result',$result);
				}
				break;
			case 'share':
				$ans_id=url_decrypt($_GET['id']);
				$ans_id=$ans_id>0?$ans_id:0;
				$sql = 'SELECT *
						FROM tbl_campaign_my1516_ans
						WHERE ans_id='.$ans_id;
				$answers = uDb()->findOne($sql);
				if($answers){
					$canonicalUrl = $this->createUrl('/campaign/my1516/index',array('p'=>$current_page,'id'=>$_GET['id']));
					$this->view='/campaign/my1516/share';
					$subtitle = 'Page '.$current_page;
					$this->assign('answers',$answers);
				}
				//debug($row);
				break;
		}
		//debug($this->view);
		$this->addJs('js/global/jquery-1.8.2.min.js');
		$this->assign('next_url',$next_url);
		
		// $this->addCss();
		$this->metaOptions['canonicalUrl'];
		$this->metaOptions['canonicalUrl']=$canonicalUrl;
		$this->pageTitle = getMetaTitle('2016 快樂指數大預測 '.$subtitle);
        $this->metaKeywords = getMetaKeywords('快樂, 不快樂, 指數, 占卜, 大測試, test, 心理, psychological test, 開心, 唔開心, 2016, 2015, 預測, 年尾, 回顧, 測試');
        $this->metaDescription = getMetaDescription('無論2015年過得開心定唔開心都已經不再重要～做人係要向前看！2016年話咁快就到，不如利用一兩分數時間，快速回顧一下過去一年嘅心路歷程，順便預測吓您2016會過成點啦！');
        $this->layout = 'directHtml2';
		$this->display();
	}
}
?>