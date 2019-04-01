<?php
/* error_reporting(E_ALL);
ini_set('display_errors', 1); */
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

  function getAllInfo(){
  	try{ 
  		global $redisObj; 
		// getting the value from redis
  		return $redisObj->info();
  	}catch( Exception $e ){ 
  		echo $e->getMessage(); 
  	} 
  }
  function debug($val, $stop = true, $return = false) {

    $rt = false;
    $c = print_r($val, true);

    if ($return) {
        $rt = $c;
    } else {
        echo "<pre>";
        print_r($val);
        echo "</pre>";
    }

    if ($stop)
        exit();

    return $rt;
}
switch($_GET['connect']){
case 1:
openRedisConnection( 'uhkcache01-001.p0inpu.0001.apse1.cache.amazonaws.com', 6379 );
echo "openRedisConnection( 'uhkcache01-001.p0inpu.0001.apse1.cache.amazonaws.com', 6379 )";
break;
default:
openRedisConnection( 'uhkcache01-002.p0inpu.0001.apse1.cache.amazonaws.com', 6379 );
echo "openRedisConnection( 'uhkcache01-002.p0inpu.0001.apse1.cache.amazonaws.com', 6379 )";
break;
}


if($_GET['getinfo']){
 $a=getAllInfo();
 
  //echo "Server is running: ".$redisObj->ping(); 
debug($a);
}
 setValueWithTtl( 'somekey1', $_GET['value'], 3600);

$val = getValueFromKey( 'somekey1' ); 
debug($val);
?>
