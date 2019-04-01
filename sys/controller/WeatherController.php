<?php

defined('UFM_RUN') or die('No direct script access allowed.');

class WeatherController extends UController {

	function actionReset(){
		uSetCookie('ulat', '', 0, "/");
		uSetCookie('ulong', '', 0, "/");
	}
	
    function actionIndex() {
		
        $latitude = isset($_GET["lat"])&&$this->isValidLatitude($_GET["lat"]) ? (float)$_GET["lat"] : null;
        $longitude = isset($_GET["long"])&&$this->isValidLongitude($_GET["long"]) ?  (float)$_GET["long"] : null;
        $is_reload = isset($_GET["reload"]) ? (int)$_GET["reload"] : 0;
				

		if(!$is_reload){
			$latitude = uGetCookie('ulat')!=null&&$this->isValidLatitude(uGetCookie('ulat'))? (float)uGetCookie('ulat') : $latitude;
			$longitude = uGetCookie('ulong')!=null&&$this->isValidLongitude(uGetCookie('ulong'))? (float)uGetCookie('ulong') : $longitude;
			//debug($latitude,0);debug($longitude,0);debug(uGetCookie('ulat'),0);
		}
		
		if($latitude==null||$longitude==null){
			$sql = 'SELECT 	DISTINCT a.location_id as location_id,a.name AS name, a.google_lat,a.google_long, b.temp_c, b.humidity, b.icon AS weather_icon
					FROM 	tbl_location a
					LEFT JOIN tbl_weather b ON a.location_id = b.location_id
					WHERE 	a.published="1"  AND a.location_id=1 
					LIMIT 1';
		}else{
			$sql = 'SELECT 	DISTINCT c.location_id as district_key,c.name AS district_name,a.location_id as location_id,a.name AS name, a.google_lat,a.google_long, b.temp_c, b.humidity, b.icon AS weather_icon
					FROM 	tbl_location a
					LEFT JOIN tbl_location c ON c.location_id = a.district_key
					LEFT JOIN tbl_weather b ON c.location_id = b.location_id
					WHERE 	a.published="1"  
						AND a.google_lat IS NOT NULL AND a.google_lat != ""
						AND a.google_long IS NOT NULL AND a.google_long != ""
						AND a.location_level=2
					ORDER BY (abs(a.google_lat - ' . $latitude . ') + abs(a.google_long - ' . $longitude . ')) 
					LIMIT 1';
					
			//debug($this->cookietime);
			if($is_reload)
			{
				uSetCookie('ulat', $latitude, 0, "/");
				uSetCookie('ulong', $longitude, 0, "/");
			}
		}
		$location = uDb()->findOne($sql);//debug($sql,0);
		
		 $this->assign("location", $location);
		
			
		$date = date("Y 年 m 月 d 日 (D)");
		$eng = array('Mon','Tue','Wed','Thu','Fri','Sat','Sun');
		$chi = array('一','二','三','四','五','六','日');
		$date = str_replace($eng, $chi, $date);
		$this->assign("date", $date);
		
       // $this->layout = 'embedHtml';

        $this->display();
    }
function isValidLongitude($longitude){
		if(preg_match("/^-?([1]?[1-7][1-9]|[1]?[1-8][0]|[1-9]?[0-9])\.{1}\d{1,30}$/",
		  $longitude)) {
		   return true;
		} else {
		   return false;
		}
	  }
	  function isValidLatitude($latitude){
		if (preg_match("/^-?([1-8]?[1-9]|[1-9]0)\.{1}\d{1,30}$/", $latitude)) {
			return true;
		} else {
			return false;
		}
	  }
}
