<?php
defined( 'UFM_RUN' ) or die( 'No direct script access allowed.' );

class CampaignInet2014Controller extends UController
{
//    public $layout = 'index';

	function actionIndex(){
		$this->pageTitle = getMetaTitle('U Travel X iNet 送您 SIM Card 賞韓楓');
        $this->metaKeywords = getMetaKeywords('日韓，SIM Card，賞楓，紅葉，追楓，韓國精選追楓路線，韓國上網卡，韓國數據卡，賞楓精選路線，U Travel，iNet 日通國際通訊有限公司');
        $this->metaDescription = getMetaDescription('又到賞楓季節啦！由即日起至2014年10月20日期間，U Travel 會員只要完成以下步驟，即有機會獲得iNet 日通國際通訊有限公司贊助之3G南韓7日1GB上網卡乙張 (價值$299)，名額50個，立即參加啦！');
		debug($this->metaDescription);
		$this->layout = 'directHtml';
        $this->display();
	}
}
?>