<?php
defined( 'UFM_RUN' ) or die( 'No direct script access allowed.' );

class MaintenanceController extends UController
{
	function actionIndex()
	{
		
		$this->addCss('css/hk-404-error.css');
		
		// $crons->end();
		//header("HTTP/1.1 404 Not Found");
		$msg = $this->getFlash('error','Error page!');
		$this->assign('message',$msg);
		$this->layout = 'responsive';
		$this->display();
		
	}
}
