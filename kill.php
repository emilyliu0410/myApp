<?php
define("uForceConnectMasterDB", false);

	
	$sql = "SHOW PROCESSLIST";
	$rs = db_fetch_all($sql);
	if($rs){	
		foreach($rs as $key=> $value){
			$id= $value['Id'];
			$time= $value['Time'];
			$state= $value['State'];
			if($time > 20){
			//if($state == 'Locked'){
				 db_fetch_all('kill '.$id);
				 echo 'kill '.$id.'<br>';
			}
		}
?>