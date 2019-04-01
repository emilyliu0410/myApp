<?php
defined( 'UFM_RUN' ) or die( 'No direct script access allowed.' );

class BatchChmodController extends UController
{
	
	function actionIndex()
	{
		$arr = array(
			'/cms/images/' => '0777', //775
			'/cms/images/location/' => '0777', //755
			'/cms/images/location/300x200/' => '0777', //755
		);
		
		set_time_limit(1000);
		
		foreach ($arr as $path => $mode)
		{
			if(chmod($path, $mode)){
				echo $path.' => '.$mode.' OK!<br>';
			}else{
				echo $path.' => '.$mode.' FAILED!!<br>';
			}
		}
		
		exit();
	}	
}