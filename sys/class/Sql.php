<?php defined( 'UFM_RUN' ) or die( 'No direct script access allowed.' );?>
<?php
class Sql{
	function select($setting=false,,$distinct){
		
		$select = is_array($setting['select']) ? $setting['select'] : array(*);
		$select = join(',',$select);
		
		$foundRows = $setting['foundRows'] ? false:true;
		if($foundRows) $select = 'SQL_CALC_FOUND_ROWS'.$select;
		
		$sql = "SELECT SQL_CALC_FOUND_ROWS DISTINCT  ".join(',',$select)."\n".$sql;

	)	
	
}
/*

*/
?>