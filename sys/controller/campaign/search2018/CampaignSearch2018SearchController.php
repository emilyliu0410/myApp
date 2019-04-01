<?php
defined( 'UFM_RUN' ) or die( 'No direct script access allowed.' );
require_once(dirname(__FILE__).'/includes.php');

class CampaignSearch2018SearchController extends UController
{
	var $page_name = 'search';
	var $page_title = '香港活動搜尋';
    function actionIndex(){
		$imgDir = UAPP_BASE_URL.'/sys/view/campaign/search2018/';
		$this->assign('imgDir',$imgDir);
			
		$canonicalUrl = $this->createUrl('/campaign/'.CAMPAIGN_NAME.'/'.$this->page_name);
		
		$this->metaOptions['facebook'] = uGetFacebookMetas(CAMPAIGN_META_IMAGE, 5);
		$this->metaOptions['canonicalUrl']=$canonicalUrl;
		$this->pageTitle = $this->page_title.' | '.CAMPAIGN_META_TITLE;
        $this->metaKeywords = getMetaKeywords(CAMPAIGN_META_KEYWORD);
        $this->metaDescription = getMetaDescription(CAMPAIGN_META_DESCRIPTION);
		
		$this->view='/campaign/search2018/search/index';
		$this->layout='responsiveHtml';
		
        $this->display();
	}
}
?>