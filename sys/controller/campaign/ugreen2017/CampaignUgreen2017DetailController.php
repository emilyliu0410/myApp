<?php
defined( 'UFM_RUN' ) or die( 'No direct script access allowed.' );
class CampaignUgreen2017DetailController extends UController
{
	function actionIndex(){
		$imgDir = UAPP_BASE_URL.'/sys/view/campaign/ugreen2017/';
		$this->assign('imgDir',$imgDir);
		
		$htmlDir=UAPP_BASE_URL.'/campaign/ugreen2017/';
		$this->assign('htmlDir',$htmlDir);

		$subtitle = null;
		$canonicalUrl = $this->createUrl('/campaign/ugreen2017/detail');
		$this->view='/campaign/ugreen2017/detail';
		$this->layout='campaign';
		
		array_push($this->cssFiles, $imgDir.'dist/css/bootstrap.min.css');
		array_push($this->cssFiles, $imgDir.'css/navbar-fixed-top.css');
		array_push($this->cssFiles, $imgDir.'css/grid.css');
		array_push($this->cssFiles, $imgDir.'css/justified-nav.css');
		array_push($this->cssFiles, $imgDir.'css/newStyle2.css');
		

		$this->metaOptions['facebook'] = uGetFacebookMetas(UAPP_HOST.$imgDir.'images/ugreen_1200X630-min.png', 5);
		$this->metaOptions['canonicalUrl']=$canonicalUrl;
		$this->pageTitle = 'U Green Awards 2017 – 活動簡介';
        $this->metaKeywords = getMetaKeywords('');
        $this->metaDescription = getMetaDescription('每年U Green都會提倡一個綠色議題，由綠色飲食以至綠化規劃等， 綠色應變將會是今年的環保訊息及提倡重點。 氣候變化是當前全球面對的挑戰，影響著每一個人， 使地球環境、物種與人類的生活來到一個臨界點， 時間無多，必須立即行動，扭轉危機。 其中一個方法是透過政府及企業推動節能減排及發展可再生能源， 並與公眾一起改變日常生活模式， 對抗由氣候變化所帶來的人類及環境威脅。');
		
        $this->display();
	}
}
?>