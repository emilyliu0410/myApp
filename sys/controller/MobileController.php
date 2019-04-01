<?php
defined( 'UFM_RUN' ) or die( 'No direct script access allowed.' );

class MobileController extends UController
{
	function actionIndex()
	{
		//Ad Banner
		$babybanner1 = uGetAdItem('div-gpt-ad-1429861291959-0');
		$babybanner2 = uGetAdItem('div-gpt-ad-1429861291959-1');
		$lrec1 = uGetAdItem('div-gpt-ad-1429861291959-2');
		$lrec2 = uGetAdItem('div-gpt-ad-1429861291959-3');
		$skyscraper1 = uGetAdItem('div-gpt-ad-1429861291959-4');
		$skyscraper2 = uGetAdItem('div-gpt-ad-1429861291959-5');
		$superbanner1 = uGetAdItem('div-gpt-ad-1429861291959-6');
		$superbanner2 = uGetAdItem('div-gpt-ad-1429861291959-7');
		
		$this->assign("topBanner",$superbanner1);
		$this->assign("bottomBanner",$superbanner2);
		$this->assign("largeRectangle1Banner",$lrec1);
		$this->assign("largeRectangle2Banner",$lrec2);
		$this->assign("babyBanner1",$babybanner1);
		$this->assign("babyBanner2",$babybanner2);
		$this->assign("skyscraperLeftBanner",$skyscraper1);
		$this->assign("skyscraperRightBanner",$skyscraper2);
		
		$this->pageTitle = getMetaTitle('應用程式');
        $this->metaKeywords = getMetaKeywords('U HK, 會員, U Lifestyle, 專享, 著數, 優惠');
        $this->metaDescription = getMetaDescription('U HK 會員專享頁面，送上會員限定優惠著數資訊。');
		
		$this->display();
	}
}
?>