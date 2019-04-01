<?php
defined( 'UFM_RUN' ) or die( 'No direct script access allowed.' );
require_once('campaignValue.php');
class CampaignBestmoment2017IndexController extends UController
{
	function actionIndex(){
		$imgDir = UAPP_BASE_URL.'/sys/view/campaign/bestmoment2017/';
		$this->assign('imgDir',$imgDir);
		
		$htmlDir=UAPP_BASE_URL.'/campaign/bestmoment/';
		$this->assign('htmlDir',$htmlDir);

		$subtitle = null;
		$canonicalUrl = UAPP_HOST.UAPP_BASE_URL.'/campaign/bestmoment2017/';
		$this->view='/campaign/bestmoment2017/index';
		$this->layout='campaign';
		
		array_push($this->cssFiles, $imgDir.'css/bootstrap.css?v='.CAMPAIGN_CACHE);
		array_push($this->cssFiles, $imgDir.'css/custom.css?v='.CAMPAIGN_CACHE);
		array_push($this->cssFiles, $imgDir.'css/animate.css?v='.CAMPAIGN_CACHE);
		array_push($this->cssFiles, $imgDir.'css/font-awesome-4.7.0/css/font-awesome.min.css?v='.CAMPAIGN_CACHE);
		
		array_push($this->cssFiles, $imgDir.'addon/slick/css/slick.css?v='.CAMPAIGN_CACHE);
		array_push($this->cssFiles, $imgDir.'addon/slick/css/slick-theme.css?v='.CAMPAIGN_CACHE);
		

		$this->metaOptions['facebook'] = uGetFacebookMetas(CAMPAIGN_META_IMAGE, 5);
		$this->metaOptions['canonicalUrl']=$canonicalUrl;
		$this->pageTitle = CAMPAIGN_META_TITLE;
		$this->metaKeywords = getMetaKeywords(CAMPAIGN_META_KEYWORD);
		$this->metaDescription = getMetaDescription(CAMPAIGN_META_DESCRIPTION);
		
		$this->display();
	}
}
?>