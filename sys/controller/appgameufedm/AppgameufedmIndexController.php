<?php

defined('UFM_RUN') or die('No direct script access allowed.');

class AppgameufedmIndexController extends UController {

    function actionIndex() {
		$url = 'http://hk.ulifestyle.com.hk/campaign/appgame';
		//debug($url);
		header('Location:'.$url.'?utm_source=ufedm&utm_medium=redirect&utm_campaign=appgameufedm');
		exit();
		
        //call the viewer
        $this->display();
    }

}

?>