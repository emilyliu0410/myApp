<?php
defined( 'UFM_RUN' ) or die( 'No direct script access allowed.' );

class ErrorController extends UController
{
	private $taskName = 'crons-error';		// task name of this cron job
	private $lockProcess = 1;				// lock to prevent concurrent execution or not
	private $echoLog = 1;					// echo log content to screen or not
	
	function actionIndex()
	{
		// $crons = new UCrons($this->taskName,$this->lockProcess,$this->echoLog);
		
		// if($crons->start()){
			// $crons->log("Hello World");
		// }
		
		// $crons->end();
		header("HTTP/1.1 404 Not Found");
		$msg = $this->getFlash('error','Error page!');
		$this->assign('message',$msg);
		$this->layout = 'responsive2';
		$this->pageTitle = "404";
		$this->display();
		
	}
}
