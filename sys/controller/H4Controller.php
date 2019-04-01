<?php

defined('UFM_RUN') or die('No direct script access allowed.');

class H4Controller extends UController {

    function actionIndex() {
		$url = 'http://hk.ulifestyle.com.hk/tour/detail.html?id=ABBGD1ozBwhZEANr';
		//debug($url);
		header('Location:'.$url);
		exit();
		
        //call the viewer
        $this->display();
    }

}

?>