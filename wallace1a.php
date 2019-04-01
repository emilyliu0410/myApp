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

openRedisConnection( 'uhkcache01-002.p0inpu.0001.apse1.cache.amazonaws.com', 6379 );
setValueWithTtl( 'somekey1', 'abc', 3600);
$val = getValueFromKey( 'somekey1' );
echo $val;

?>
