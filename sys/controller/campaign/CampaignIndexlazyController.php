<?php

defined('UFM_RUN') or die('No direct script access allowed.');

class CampaignIndexlazyController extends UController
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

        //call the viewer
        $this->layout = 'directHtml';
        $this->display();
    }

}
