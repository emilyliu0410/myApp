<?php

defined('UFM_RUN') or die('No direct script access allowed.');

class RatingController extends UController {

	public $useMasterDb = true;		// connect master database for insert/update

    function actionIndex() {
        $pageID = isset($_REQUEST['page_id']) ? url_decrypt($_REQUEST['page_id']) : 0;
        $pagetypeID = isset($_REQUEST['pagetype_id']) ? (int) $_REQUEST['pagetype_id'] : 0;
        $redirectUrl = isset($_REQUEST['redirect_url']) ? $_REQUEST['redirect_url'] : UAPP_HOST . UAPP_BASE_URL . "/index.html";

        if ($pageID > 0 && $pagetypeID > 0) {
            $this->assign("pageID", url_encrypt($pageID));
            $this->assign("pagetypeID", $pagetypeID);
            $this->assign("redirectUrl", $redirectUrl);
            $this->layout = 'directHtml';
            $this->display();
        }
    }

}
