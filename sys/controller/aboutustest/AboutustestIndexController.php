<?php
defined( 'UFM_RUN' ) or die( 'No direct script access allowed.' );

class AboutustestIndexController extends UController
{
	private static $cookiesName = 'eventViewedHistory';
    private static $historyLimit = 5;

	function actionIndex()
	{	

		/*Login*/
		$isLogin = $this->user->isLogin();
		$this->assign('isLogin',$isLogin);
		
		/*UTM*/
		$utm_campaign = 'theme-'.$_REQUEST['id'];
		$utm_source = 'uhk';
		
		/*metadata*/
	
        $this->metaOptions['canonicalUrl'] = UAPP_HOST.UAPP_BASE_URL.'/aboutustest/index.html';
		$this->pageTitle = getMetaTitle('關於我們');
        $this->metaKeywords = getMetaKeywords($items->meta_keywords);
        $this->metaDescription = getMetaDescription($items->meta_descr);
		
		$this->assign('currContentID',$content_id);
		$this->assign('currContent',$currContent);
		

		/*****Ad Banner*****/
		
		// rectangle banner
		/* $banner_300_250 = getAdZone(2570,300,250,'a9e7d3be');
		$this->assign("banner_300_250",$banner_300_250);
		$banner_300_100 = getAdZone(2571,300,100,'aa325ddf');
		$this->assign("banner_300_100",$banner_300_100); */

		$babybanner1 = uGetAdItem('div-gpt-ad-1429861175783-0');
		$babybanner2 = uGetAdItem('div-gpt-ad-1429861175783-1');
		$lrec1 = uGetAdItem('div-gpt-ad-1429861175783-2');
		$lrec2 = uGetAdItem('div-gpt-ad-1429861175783-3');
		$skyscraper1 = uGetAdItem('div-gpt-ad-1429861175783-4');
		$skyscraper2 = uGetAdItem('div-gpt-ad-1429861175783-5');
		$superbanner1 = uGetAdItem('div-gpt-ad-1429861175783-6');
		$superbanner2 = uGetAdItem('div-gpt-ad-1429861175783-7');
		
		$this->assign("topBanner",$superbanner1);
		$this->assign("bottomBanner",$superbanner2);
		$this->assign("largeRectangle1Banner",$lrec1);
		$this->assign("largeRectangle2Banner",$lrec2);
		$this->assign("babyBanner1",$babybanner1);
		$this->assign("babyBanner2",$babybanner2);
		$this->assign("skyscraperLeftBanner",$skyscraper1);
		$this->assign("skyscraperRightBanner",$skyscraper2);
		



		
		
		/*****add JS&CSS*****/		
		
		// $this->addCss('css/global/global.css');
		$this->addCss('css/hk-aboutus.css');	
		// $this->addCss('css/global/common.css');

		// $this->addJs('js/global/uhk-thickbox-3.1.js');
		// $this->addCss('css/global/uhk-thickbox-3.1.css');
		// $this->addCss('css/global/hk-thickbox-3.1.css');
		

		//$this->layout = 'column2';
		$this->layout = 'responsive';
		$this->display();
		
	
	
	}
}
