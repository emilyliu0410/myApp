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

openRedisConnection( 'uhkcachetest01.p0inpu.ng.0001.apse1.cache.amazonaws.com', 6379 );
setValueWithTtl( 'somekey100', 'abcde', 3600);
$val = getValueFromKey( 'somekey100' );
echo $val;

?>
