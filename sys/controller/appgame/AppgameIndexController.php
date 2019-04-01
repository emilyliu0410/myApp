<?php

defined('UFM_RUN') or die('No direct script access allowed.');

class AppgameIndexController extends UController {

    function actionIndex() {
		$url = 'http://hk.ulifestyle.com.hk/campaign/appgame';
		//debug($url);
		header('Location:'.$url.'?utm_source=newspaper&utm_medium=redirect&utm_campaign=appgame');
		exit();
		
        //call the viewer
        $this->display();
    }

}

?>