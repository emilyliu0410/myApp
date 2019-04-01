<?php defined('UFM_RUN') or die('No direct script access allowed.');

class CampaignIndexController extends UController
{
    function actionIndex()
    {
        $p = isset($_REQUEST['p']) ? (int)$_REQUEST['p'] : 1;

        $model = new UCampaign();

        $pagination = new UPagination(array(
            'page' => $p,
            'limit' => 12
        ));

        $campaigns = $model->getCampaigns(array(
            'offset' => $pagination->offset,
            'limit' => $pagination->limit
        ));

        $currentTime = time();

        foreach ($campaigns as $k => $v) {
            $campaigns[$k]->cover_photo = UCampaign::getImgPath() . $v->cover_image;
            if ($currentTime >= strtotime($v->start_date . ' 00:00:00') && $currentTime <= strtotime($v->end_date . ' 23:59:59')) {
                $campaigns[$k]->heading = '活動招募';
                $campaigns[$k]->isInProgress = true;
            } else if ($currentTime >= strtotime($v->result_start_date . ' 00:00:00')) {
                $campaigns[$k]->heading = '得獎結果';
                $campaigns[$k]->URL = UCampaign::getURL($v->campaign_id);
                $campaigns[$k]->isInProgress = false;
            } else if ($currentTime >= strtotime($v->end_date . ' 23:59:59') && $currentTime <= strtotime($v->result_start_date . ' 00:00:00')) {
                $campaigns[$k]->heading = '會員專享';
                $campaigns[$k]->URL = UCampaign::getURL($v->campaign_id);
                $campaigns[$k]->isInProgress = false;
            }
        }
        $pagination->setTotal(uDb()->foundRows());

        $this->assign("pagination", $pagination);
        $this->assign('campaigns', $campaigns);

        $this->pageTitle = getMetaTitle('會員專享');
        $this->metaKeywords = getMetaKeywords('U HK, 會員, U Lifestyle, 專享, 著數, 優惠');
        $this->metaDescription = getMetaDescription('U HK 會員專享頁面，送上會員限定優惠著數資訊。');
        $this->metaOptions['canonicalUrl'] = $this->createUrl('/campaign/index');

        //Ad Banner
        $fixed1 = uGetAdItem('div-gpt-ad-1472555496439-1');
        $fixed2 = uGetAdItem('div-gpt-ad-1472555496439-2');
        $fixed3 = uGetAdItem('div-gpt-ad-1472555496439-3');

        $this->assign("topBanner", $fixed1);
        $this->assign("innerListAds1", $fixed2);
        $this->assign("innerListAds2", $fixed3);
        $this->assign("ads_order_matrix", array(
            [1, 1, 1],
            [1, 1, 1],
            [3, 1, 1],
            [3, 3, 1],
            [3, 3, 3],
            [5, 3, 3],
            [5, 5, 3],
            [5, 5, 3]
        ));

        /*****add JS&CSS*****/
        $this->addJs('js/global/jquery.hk-loadmore.js');
		$this->addCss('css/global/hk-card-update.css');

        $this->layout = 'responsive2';
        $this->display();
    }

}
