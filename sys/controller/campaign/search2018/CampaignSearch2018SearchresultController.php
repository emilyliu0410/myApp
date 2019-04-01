<?php
defined( 'UFM_RUN' ) or die( 'No direct script access allowed.' );
require_once(dirname(__FILE__).'/includes.php');

class CampaignSearch2018SearchresultController extends UController
{
	var $page_name = 'searchresult';
	var $page_title = '搜尋結果';
    function actionIndex(){
		$imgDir = UAPP_BASE_URL.'/sys/view/campaign/search2018/';
		$this->assign('imgDir',$imgDir);
		
		$canonicalUrl = $this->createUrl('/campaign/'.CAMPAIGN_NAME.'/'.$this->page_name);
		
		$this->metaOptions['facebook'] = uGetFacebookMetas(CAMPAIGN_META_IMAGE, 5);
		$this->metaOptions['canonicalUrl']=$canonicalUrl;
		$this->pageTitle = $this->page_title.' | '.CAMPAIGN_META_TITLE;
        $this->metaKeywords = getMetaKeywords(CAMPAIGN_META_KEYWORD);
        $this->metaDescription = getMetaDescription(CAMPAIGN_META_DESCRIPTION);
		
		$this->addCss('library/photoswipe/css/photoswipe.css');
		$this->addCss('library/photoswipe/css/default-skin.css');
		$this->addJs('library/photoswipe/js/photoswipe.js');
		$this->addJs('library/photoswipe/js/photoswipe-ui-default.js');

		$this->addJs('js/global/photoswipe-control.js');
		
		$this->view='/campaign/search2018/search/result';
		$this->layout='responsiveHtml';
		
        $this->display();
	}
}
?>