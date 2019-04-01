<?php

defined('UFM_RUN') or die('No direct script access allowed.');

class AppgameetnetIndexController extends UController {

    function actionIndex() {
		$url = 'http://hk.ulifestyle.com.hk/campaign/appgame';
		//debug($url);
		header('Location:'.$url.'?utm_source=etnet&utm_medium=redirect&utm_campaign=appgameetnet');
		exit();
		
        //call the viewer
        $this->display();
    }

}

?>