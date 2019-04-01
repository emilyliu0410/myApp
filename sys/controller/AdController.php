<?php

defined('UFM_RUN') or die('No direct script access allowed.');

class AdController extends UController {

    function actionIndex() {
		$_zone_id = isset($_REQUEST['zone_id'])? $_REQUEST['zone_id']:0;
		$_n = isset($_REQUEST['n'])?$_REQUEST['n']:'';
		$show_openx = 1;
		if ($_zone_id > 0 && $_n != '') {
			$this->assign("_zone_id", $_zone_id);
            $this->assign("_n", $_n);
            $this->assign("show_openx", $show_openx);
            $this->layout = 'directHtml';
            $this->display();
		}
    }

}
