<?php
class Time {
	function ago($time_str){
		$rt = false;
		$time = strtotime($time_str);
		$seconds = (time() - $time);
		$minues = round($seconds/60);
		$hours  = round($minues/60);
		if($seconds > 0 && $seconds < 60){
			$rt = "1分鐘前";
			//$rt = $seconds."秒前";
		}
		elseif($minues > 0 && $minues < 60){
			$rt = $minues."分鐘前";
		}
		elseif($minues > 0 && $hours < 24){
			$rt = $hours."小時前";
		}
		else{
			$rt = date("Y-m-d",$time);
		}
		return $rt;
	}
} 

/*
* testing
define('WEATHER_PARTNER_ID',1126770212);
define('WEATHER_LICENSE_KEY','ea1cd4bb2b0d44cd');

$helper = new Weather();
echo '<pre>';
print_r($helper->get_day_weather('CHXX0049'));
*/
?>