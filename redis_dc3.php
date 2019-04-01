<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$redisObj = new Redis();

function openRedisConnection( $hostName, $port){ 
	global $redisObj; 
	// Opening a redis connection
  	$redisObj->connect( $hostName, $port );
  	return $redisObj; 
  } 

  function setValueWithTtl( $key, $value, $ttl ){ 

  	try{ 
  		global $redisObj; 
		// setting the value in redis
  		$redisObj->setex( $key, $ttl, $value );
  	}catch( Exception $e ){ 
  		echo $e->getMessage(); 
  	} 
  } 

  function getValueFromKey( $key ){ 
  	try{ 
  		global $redisObj; 
		// getting the value from redis
  		return $redisObj->get( $key);
  	}catch( Exception $e ){ 
  		echo $e->getMessage(); 
  	} 
  }

function getTTLFromKey( $key ){ 
  	try{ 
  		global $redisObj; 
  		return ($redisObj->PTTL($key))/1000;
  	}catch( Exception $e ){ 
  		echo $e->getMessage(); 
  	} 
  } 
  
  function flushDB(){
	try{
		global $redisObj; 
		
		return $redisObj->flushdb();
	}catch( Exception $e ){ 
		echo $e->getMessage(); 
	} 
		
	}
	
	function getAllKey(){
		try{
			global $redisObj; 
			
			$allKeys = $redisObj->keys('*');
			return $allKeys;
		} catch (Exception $e) {
			echo $e->getMessge();
		}
	}
	
	function getDBsize(){
		try{
			global $redisObj; 
			
			$allKeys = $redisObj->DBSIZE();
			return $allKeys;
		} catch (Exception $e) {
			echo $e->getMessge();
		}
	}
	
	function getInfo(){
		try{
			global $redisObj; 
			
			return $redisObj->info();
		} catch (Exception $e) {
			echo $e->getMessge();
		}
	}

if(isset($_GET["action"]) && $_GET["action"]=="log"){
	//unlink("/export/d28/test-n1/sys/log/redis/mysql_7-2017-04-25.log");
	//echo 'log remove';
	
	$logs = array();
	$logs[0] = ",";
	$num = 1;
	foreach (glob("/export/d28/test-n1/sys/log/redis_pro/redis_2017*.log") as $file) {
		//echo 'file name'.$file."<br>";
		$file_handle = fopen($file, "r");
		
		$logs[] = substr($file,44,-4);
		
		while (!feof($file_handle)) {			
			$line = fgets($file_handle);
			
			if($num == 1){
				$logs[0] .= substr($line,0, strrpos($line, " ")).",";
				if(substr($line,0, strrpos($line, " ")) == "slave0:") $logs[0].= ",,,,";
			}
			
			$logs[$num] .= ",".substr($line, strrpos($line, " "),-3);
			
		}
		fclose($file_handle);
		
		$num++;
	}
	
	// write to file
	$r_file = fopen("/export/d28/test-n1/sys/log/redis_pro/redis_merge.csv","w");
	foreach($logs as $log){
		fwrite($r_file,$log."\n");
	}
	fclose($r_file);
	
	echo "merge to file.";
	
	exit();
}

openRedisConnection( 'uhkcache01.p0inpu.ng.0001.apse1.cache.amazonaws.com', 6379 );

echo '<a href="/redis_dc3.php">refresh</a><br><br>';
echo '<a href="/redis_dc3.php?action=flush">flushDB</a><br><br>';

if(isset($_GET["action"]) && $_GET["action"]=="flush"){
	flushDB();
}

echo "DB size: ".getDBsize()."<br><br>";

// echo json_encode(getInfo());

echo "Key: <br>";
$allKey = getAllKey();
$result = array();
foreach($allKey as $k => $v){
	$result[] = array("key" => $v, "ttl" => getTTLFromKey($v));
}

function sortByOrder($a, $b) {
    return $b['ttl'] - $a['ttl'];
}
usort($result, 'sortByOrder');
foreach($result as $k => $v){
	echo $v['key']." - ".$v['ttl']."s <br>";
}
?>
