<?php 
class Obj
{
	public static function intAttr($obj)
	{
		$rt = $obj;
		foreach(get_object_vars($obj) as $k=>$v){
			$obj->$k = (int) $v;
		}
		return $rt;
	}
		
}
