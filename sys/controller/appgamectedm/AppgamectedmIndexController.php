<?php

defined('UFM_RUN') or die('No direct script access allowed.');

class AppgamectedmIndexController extends UController {

    function actionIndex() {
		$url = 'http://hk.ulifestyle.com.hk/campaign/appgame';
		//debug($url);
		header('Location:'.$url.'?utm_source=ctedm&utm_medium=redirect&utm_campaign=appgamectedm');
		exit();
		
        //call the viewer
        $this->display();
    }

}

?>