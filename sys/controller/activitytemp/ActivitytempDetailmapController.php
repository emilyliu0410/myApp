<?php

defined('UFM_RUN') or die('No direct script access allowed.');

class ActivitytempDetailmapController extends UController {

    private static $zoom = 16;

    function actionIndex() {
        /* if(is_direct_access_lightbox()){
            $this->redirect('/');
        } */
        
        $id = isset($_REQUEST['id']) ? url_decrypt($_REQUEST['id']) : 0;

        if ($id < 0) {
            $this->redirect('/error');
        }

        $model = new UEvent();
		$event = $model->getEventAllById($id);
        /* @var $event UEvent */

        $this->assign('event', $event);

        $this->assign('zoom', $event->google_zoom?$event->zoom:self::$zoom);

        $this->layout = 'directHtml';

        $this->display();
    }

}
