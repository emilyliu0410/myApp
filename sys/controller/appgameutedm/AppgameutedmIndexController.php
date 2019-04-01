<?php

defined('UFM_RUN') or die('No direct script access allowed.');

class AppgameutedmIndexController extends UController {

    function actionIndex() {
		$url = 'http://hk.ulifestyle.com.hk/campaign/appgame';
		//debug($url);
		header('Location:'.$url.'?utm_source=utedm&utm_medium=redirect&utm_campaign=appgameutedm');
		exit();
		
        //call the viewer
        $this->display();
    }

}

?>