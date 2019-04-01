<?php defined( 'UFM_RUN' ) or die( 'No direct script access allowed.' );?>
<?php
class Str{

	function slice($str,$isInt=false,$hasEmpty=false){
		$rt = array();
		$str = str_replace(array(' ','，'),',',$str);
		$arr = explode(',',$str);
		foreach($arr as $v){
			if($hasEmpty){
				$rt[] = $isInt ? (int) $v : Input::str($v);
			}
			elseif(!empty($v)){
				$rt[] = $isInt ? (int) $v : Input::str($v);
			}
		}
		if(count($rt))	$rt = array_unique($rt);
		//debug($rt);
		return $rt;
	}

	function join($arr){
		$rt = '';
		if(is_array($arr)){
			$rt = join(',',$arr);
		}
		return $rt;
	}
}