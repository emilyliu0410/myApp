<?php
defined( 'UFM_RUN' ) or die( 'No direct script access allowed.' );
require_once(dirname(__FILE__).'/includes.php');

class CampaignHk3bdPrize01Tnc_formController extends UController
{
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
		$canonicalUrl = $this->createUrl('/campaign/hk3bd/'.CAMPAIGN_PERIOD.'/tnc_form');
		$this->view='/campaign/hk3bd/questions/'.CAMPAIGN_PERIOD.'/tnc_form';
		$this->layout='campaign';
		
		array_push($this->cssFiles, $imgDir.'dist/css/bootstrap.min.css?v='.CAMPAIGN_CACHE);
		array_push($this->cssFiles, $imgDir.'assets/css/ie10-viewport-bug-workaround.css?v='.CAMPAIGN_CACHE);
		array_push($this->cssFiles, $imgDir.'css/navbar-fixed-top.css?v='.CAMPAIGN_CACHE);
		array_push($this->cssFiles, $imgDir.'css/grid.css?v='.CAMPAIGN_CACHE);
		array_push($this->cssFiles, $imgDir.'css/justified-nav.css?v='.CAMPAIGN_CACHE);
		array_push($this->cssFiles, $imgDir.'css/newStyle.css?v='.CAMPAIGN_CACHE);
		array_push($this->cssFiles, $imgDir.'assets/js/ie-emulation-modes-warning.js?v='.CAMPAIGN_CACHE);
		
		$this->metaOptions['facebook'] = uGetFacebookMetas(CAMPAIGN_META_IMAGE, 5);
		$this->metaOptions['canonicalUrl']=$canonicalUrl;
		$this->pageTitle = '參加表格 | '.CAMPAIGN_META_TITLE;
        $this->metaKeywords = getMetaKeywords(CAMPAIGN_META_KEYWORD);
        $this->metaDescription = getMetaDescription(CAMPAIGN_META_DESCRIPTION);
		
        $this->display();
	}
}
?>