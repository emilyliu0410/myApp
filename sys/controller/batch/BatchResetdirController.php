<?php
defined( 'UFM_RUN' ) or die( 'No direct script access allowed.' );

class BatchResetdirController extends UController
{
	
	function actionIndex()
	{
		
		$arr = array(
			'/cms/images/spot',
			'/cms/images/tour',
			'/sys/log',
			'/sys/log/hits/2014',
		);
		
		set_time_limit(1000);		
		foreach($arr as $k => $v)
		{			
			$dir = UAPP_BASE_DIR.$v;
			if(chmod($dir, 0777)){
				echo "$dir changed<br>";
			}else{
				echo "$dir failed<br>";
			}
		}
		
		exit();		
	}	
}