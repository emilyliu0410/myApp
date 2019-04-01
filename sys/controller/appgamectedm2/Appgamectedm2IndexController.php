<?php

defined('UFM_RUN') or die('No direct script access allowed.');

class Appgamectedm2IndexController extends UController {

    function actionIndex() {
		$url = 'http://hk.ulifestyle.com.hk/activity/detail.html?id=ABJGD1owBwpZHgNj';
		//debug($url);
		header('Location:'.$url.'&utm_source=ctedm&utm_medium=redirect&utm_campaign=appgamectedm2');
		exit();
		
        //call the viewer
        $this->display();
    }

}

?>