<?php
defined( 'UFM_RUN' ) or die( 'No direct script access allowed.' );
require_once('campaignPrizeList.php');
class CampaignHk3bdIntroController extends UController
{
	function actionIndex(){
		global $prize_list;
		$imgDir = UAPP_BASE_URL.'/sys/view/campaign/hk3bd/';
		$this->assign('imgDir',$imgDir);
		
		$htmlDir=UAPP_BASE_URL.'/campaign/hk3bd/';
		$this->assign('htmlDir',$htmlDir);

		$this->assign('prize_list',$prize_list);
		
		$subtitle = null;
		$canonicalUrl = $this->createUrl('/campaign/hk3bd/intro');
		$this->view='/campaign/hk3bd/intro';
		$this->layout='campaign';
		
		array_push($this->cssFiles, $imgDir.'dist/css/bootstrap.min.css?v='.CAMPAIGN_CACHE);
		array_push($this->cssFiles, $imgDir.'assets/css/ie10-viewport-bug-workaround.css?v='.CAMPAIGN_CACHE);
		array_push($this->cssFiles, $imgDir.'css/navbar-fixed-top.css?v='.CAMPAIGN_CACHE);
		array_push($this->cssFiles, $imgDir.'css/grid.css?v='.CAMPAIGN_CACHE);
		array_push($this->cssFiles, $imgDir.'css/justified-nav.css?v='.CAMPAIGN_CACHE);
		array_push($this->cssFiles, $imgDir.'css/newStyle.css?v='.CAMPAIGN_CACHE);
		array_push($this->cssFiles, $imgDir.'css/newStyle.php?v='.CAMPAIGN_CACHE);
		array_push($this->cssFiles, $imgDir.'assets/js/ie-emulation-modes-warning.js?v='.CAMPAIGN_CACHE);
		
		$this->metaOptions['facebook'] = uGetFacebookMetas(CAMPAIGN_META_IMAGE_DAILY, 5);
		$this->metaOptions['canonicalUrl']=$canonicalUrl;
		$this->pageTitle = '遊戲玩法 | '.CAMPAIGN_META_TITLE;
        $this->metaKeywords = getMetaKeywords(CAMPAIGN_META_KEYWORD);
        $this->metaDescription = getMetaDescription(CAMPAIGN_META_DESCRIPTION);
		
        $this->display();
	}
}
?>