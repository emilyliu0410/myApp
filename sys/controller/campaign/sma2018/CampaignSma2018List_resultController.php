<?php
defined( 'UFM_RUN' ) or die( 'No direct script access allowed.' );
require_once(dirname(__FILE__).'/includes.php');

class CampaignSma2018List_resultController extends UController
{
	 var $page_name = 'list_result';
	 var $page_title = '得獎名單';
	function actionIndex(){
		global $user_id;
		if($this->user->isLogin()){
			$arr = CampaignAnswer::exists('user_id',$this->user->user_id);
			$is_user_exists = count($arr);
			$user_id = $this->user->user_id;
		}
		
		$this->assign('is_user_exists',$is_user_exists);
		
		$imgDir = UAPP_BASE_URL.'/sys/view/campaign/'.CAMPAIGN_NAME.'/';
		$this->assign('imgDir',$imgDir);
		
		
		$htmlDir= UAPP_BASE_URL.'/campaign/'.CAMPAIGN_NAME.'/';
		$this->assign('htmlDir',$htmlDir);

		$subtitle = null;
		$canonicalUrl = $this->createUrl('/campaign/'.CAMPAIGN_NAME.'/'.$this->page_name);
		$this->view=CAMPAIGN_LAYOUT;
		$this->layout='campaign';
		$this->assign('view',$this->page_name);
		$this->assign('header_active',array('index'=>1,'tnc'=>0));
		
		array_push($this->cssFiles, $imgDir.'css/bootstrap.min.css?v='.CAMPAIGN_CACHE);
		array_push($this->cssFiles, $imgDir.'css/ie10-viewport-bug-workaround.css?v='.CAMPAIGN_CACHE);
		array_push($this->cssFiles, $imgDir.'css/newStyle.css?v='.CAMPAIGN_CACHE);
		array_push($this->jsFiles, $imgDir.'js/ie-emulation-modes-warning.js?v='.CAMPAIGN_CACHE);
		
		$this->metaOptions['facebook'] = uGetFacebookMetas(CAMPAIGN_META_IMAGE, 5);
		$this->metaOptions['canonicalUrl']=$canonicalUrl;
		$this->pageTitle = $this->page_title.' | '.CAMPAIGN_META_TITLE;
        $this->metaKeywords = getMetaKeywords(CAMPAIGN_META_KEYWORD);
        $this->metaDescription = getMetaDescription(CAMPAIGN_META_DESCRIPTION);
		
        $this->display();
	}
}
?>