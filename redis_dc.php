<?php

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

openRedisConnection( 'uhkcache01-001.p0inpu.0001.apse1.cache.amazonaws.com', 6379 );

//echo '<a href="/redis_dc.php">refresh</a><br><br>';
//echo '<a href="/redis_dc.php?action=flush">flushDB</a><br><br>';

if($_GET["action"]=="flush"){
	flushDB();
}

echo "DB size: ".getDBsize()."<br><br>";

echo json_encode(getInfo());

//echo "Key: <br>";
//$allKey = getAllKey();
//foreach($allKey as $k => $v){
//	echo $v." - ".getTTLFromKey($v)."s <br>";
//}
?>
