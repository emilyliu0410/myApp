<?php

defined('UFM_RUN') or die('No direct script access allowed.');

class AppgamectfbIndexController extends UController {

    function actionIndex() {
		$url = 'http://hk.ulifestyle.com.hk/campaign/appgame';
		//debug($url);
		header('Location:'.$url.'?utm_source=ctfb&utm_medium=redirect&utm_campaign=appgamectfb');
		exit();
		
        //call the viewer
        $this->display();
    }

}

?>