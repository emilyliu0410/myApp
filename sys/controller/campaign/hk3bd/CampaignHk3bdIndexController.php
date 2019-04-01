<?php
defined( 'UFM_RUN' ) or die( 'No direct script access allowed.' );
class CampaignHk3bdIndexController extends UController
{
	function actionIndex(){
		$imgDir = UAPP_BASE_URL.'/sys/view/campaign/hk3bd/';
		$this->assign('imgDir',$imgDir);
		
		$htmlDir=UAPP_BASE_URL.'/campaign/hk3bd/';
		$this->assign('htmlDir',$htmlDir);

		$subtitle = null;
		$canonicalUrl = $this->createUrl('/campaign/hk3bd/index');
		$this->view='/campaign/hk3bd/index';
		$this->layout='campaign';
		
		
		array_push($this->cssFiles, $imgDir.'css/style.css');
		

		$this->metaOptions['facebook'] = uGetFacebookMetas(UAPP_HOST.$imgDir.'images/2.jpg', 5);
		$this->metaOptions['canonicalUrl']=$canonicalUrl;
		$this->pageTitle = '港玩港食港生活 同你玩轉3周年';
        $this->metaKeywords = getMetaKeywords('');
        $this->metaDescription = getMetaDescription('為答謝各位忠實會員過去3年來的厚愛及支持，港生活一於與眾同樂，於未來三星期裡強勢送出多份超人氣精選獎品回饋大家！');
		
        $this->display();
	}
}
?>