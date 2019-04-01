<?php
defined('UFM_RUN') or die('No direct script access allowed.');

class AboutusIndexController extends UController
{
    private static $cookiesName = 'eventViewedHistory';
    private static $historyLimit = 5;

    function actionIndex()
    {
        /*Login*/
        $isLogin = $this->user->isLogin();
        $this->assign('isLogin', $isLogin);

        /*UTM*/
        $utm_campaign = 'theme-' . $_REQUEST['id'];
        $utm_source = 'uhk';

        /*metadata*/

        $this->metaOptions['canonicalUrl'] = UAPP_HOST . UAPP_BASE_URL . '/aboutus';
        $this->pageTitle = getMetaTitle('關於我們');
        $this->metaKeywords = getMetaKeywords($items->meta_keywords);
        $this->metaDescription = getMetaDescription($items->meta_descr);

        $this->assign('currContentID', $content_id);
        $this->assign('currContent', $currContent);

        /*****Ad Banner*****/
        $fixed1 = uGetAdItem('div-gpt-ad-1472555452473-1');
        $fixed2 = uGetAdItem('div-gpt-ad-1472555452473-2');
        $fixed3 = uGetAdItem('div-gpt-ad-1472555452473-3');

        $this->assign("topBanner", $fixed1);
        $this->assign("largeRectangle1Banner", $fixed2);
        $this->assign("largeRectangle2Banner", $fixed3);


        /*****add JS&CSS*****/
        $this->addCss('css/hk-other-page.css');

        $this->layout = 'responsive2';
        $this->display();


    }
}
