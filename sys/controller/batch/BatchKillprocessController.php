<?php
defined( 'UFM_RUN' ) or die( 'No direct script access allowed.' );

class BatchKillprocessController extends UController
{
	
	function actionIndex()
	{
		$sql = "SHOW PROCESSLIST";		
		$rs = db_fetch_all($sql);		
		if($rs){	
			foreach($rs as $key=> $value){
				$id= $value['Id'];
				$time= $value['Time'];
				$state= $value['State'];
				if($time > 30){
				//if($state == 'Locked'){
					 db_fetch_all('kill '.$id);
					 echo 'kill '.$id.'<br>';
				}
			}
		}
	}
	
}