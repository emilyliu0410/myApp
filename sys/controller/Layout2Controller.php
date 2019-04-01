<?php
defined( 'UFM_RUN' ) or die( 'No direct script access allowed.' );

class Layout2Controller extends UController
{
	
	function actionIndex()
	{
		$this->layout = 'column2';
		$this->display();
		
	}
}
