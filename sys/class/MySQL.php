<?php defined( 'UFM_RUN' ) or die( 'No direct script access allowed.' );?>
<?php
class MySQL{

	/*
	function select($setting=false,$debug=false,$stop=1){
		$sql = self::buildSelect($setting,$debug,$stop);
		$rt = db_fetch_all($sql);
		return $rt;
	}
	function queryBy($setting,$debug=false,$stop=1){
		$sql = self::buildSelect($setting,$debug,$stop);
		return self::queryAll($sql);
	}
	
	function queryOne($sql,$returnObj=true){
		$rs = dbQuery($sql);
		if ($rs){
			$rt = $returnObj ? mysql_fetch_object($rs) : mysql_fetch_assoc($rs);
		}
		else{
			self::error();
			return $rs;
		}

		return $rt;
	}	
	
	function queryAll($sql,$debug=false){
		if($debug) debug($sql);
		$rs = dbQuery($sql);
		if ($rs){
			while($arr = mysql_fetch_object($rs)){
				$rt[] = $arr;
			}
		}
		else{
			self::error();
			return $rs;
		}

		return $rt;
	}	
	function buildSelect($setting=false,$debug=false,$stop=1){
		//debug($setting);
		$select = is_array($setting['select']) ? $setting['select'] : array('*');
		$select = join(',',$select);

		if(isset($setting['from'])) $from = $setting['from'];
		
		if(is_array($setting['from'])){
			$a = array();
			foreach($setting['from'] as $v){
				$a[] = stristr($v,'join ') === FALSE ? ', '.$v : ' '.$v ; 
			}
			$from = ltrim(join('',$a),',');
			//debug($from);
		}
		
		$where = is_array($setting['where']) ? $setting['where'] : array();
		$where = join(' AND ',$where);
		
		$where_or = is_array($setting['where_or']) ? $setting['where_or'] : array();
		if($where){
			$where_or[] = '('.$where.')';
		}
		$where = join(' OR ',$where_or);

		$group = is_array($setting['group']) ? $setting['group'] : array();
		$group = join(', ',$group);
		
		$order = is_array($setting['order']) ? $setting['order'] : array();
		$order = join(', ',$order);		
		
		$distinct = isset($setting['distinct']) ? $setting['distinct']:true;
		if($distinct) $select = 'DISTINCT '.$select;
		
		$foundRows = isset($setting['foundRows']) ? $setting['foundRows']:true;
		if($foundRows) $select = 'SQL_CALC_FOUND_ROWS '.$select;
		
		$sql = "SELECT ".$select."\n FROM ".$from;
		
		if($where) $sql .= "\n WHERE ".$where;
		if($group) $sql .= "\n GROUP BY ".$group;
		if($order) $sql .= "\n ORDER BY ".$order;
		if(isset($setting['limit'])) $sql .= "\n LIMIT ".$setting['limit'];
		if(isset($setting['offset'])){
			if($setting['offset'] <0) $setting['offset'] = 0;
			$sql .= "\n OFFSET ".$setting['offset'];
		}
		
		if($debug)	debug($sql,$stop);
		return $sql;
	}

	function insert($table,$data,$returnId){
		$rs = db_insert_row($table,$data,$returnId);
		if(!$rs) self::error();
		return $rs;
	}
	function update($table,$data,$where=false,$limit=1,$options=array()){
		$rs = db_update_row($table,$data,$where,$limit,$options);
		if(!$rs) self::error();
		return $rs;
	}
	function delete($table,$where=false,$limit=1){
		$sql = "DELETE FROM ".$table." WHERE ".$where;
		if($limit) $sql .= " LIMIT ".$limit;
		//debug($sql);
		$rs = dbQuery($sql);
		//debug($rs);
		if(!$rs) self::error();
		return $rs;
	}
	function error(){
		if(isDev()){
			$message  = 'Invalid query: ' . mysql_error() . "\n<br>";
			$message .= 'Whole query: ' . $query."\n<br>";
			die($message);
		}
	}

	function existsRow($table,$data){
		$arr = array();
		foreach($data as $k=>$v){
			$arr[] = $k."='".$v."'";
		}
		$where = join(' AND ',$arr);
		$sql  = "SELECT * FROM ".$table.' WHERE '.$where." LIMIT 1";

		$rs = dbQuery($sql);
		if(!$rs) self::error();

		return mysql_num_rows($rs);
	}
	function getByData($table,$data){
		$arr = array();
		foreach($data as $k=>$v){
			$arr[] = $k."='".$v."'";
		}
		$where = join(' AND ',$arr);
		$sql  = "SELECT * FROM ".$table.' WHERE '.$where." LIMIT 1";

		return self::queryOne($sql);
	}
	function getList($table,$data=false,$queryfields='*'){
	
		$limit = 0;
		$where = 1;
		$offset = 0;
		
		if(is_array($data) && isset($data['limit'])){
			$limit = $data['limit'];
			unset($data['limit']);
		}
		if(isset($data['offset'])){
			$offset = $data['offset'];
			unset($data['offset']);
		}
		if(isset($data['orderby'])){
			$orderby = $data['orderby'];
			unset($data['orderby']);
		}
		//debug($data);
		if(is_array($data) && count($data)){
			
			$arr = array();
			foreach($data as $k=>$v){
				$arr[] = $k."='".$v."'";
			}
			$where = join(' AND ',$arr);
			
		}
		$sql  = "SELECT $queryfields FROM ".$table.' WHERE '.$where." ";
		if($orderby) $sql .=" ORDER BY ".$orderby;
		if($limit || $offset) $sql .=" LIMIT $offset,$limit";

		return self::queryAll($sql,0);
	}
	function countRow($sql){
		$rs = dbQuery($sql);
		if(!$rs) self::error();
		
		$rt = mysql_fetch_row($rs);
		if(count($rt) == 1) $rt = $rt[0];
		return $rt;
	}	
	function fetchFields($table,$field,$where=1){
		$sql = "select ".$field." from ".$table." where ".$where." limit 1";
		$rs = dbQuery($sql);
		if(!$rs) self::error();
		$arr = mysql_fetch_array($rs);
		return  count($arr) > 2 ? $arr : $arr[0];
	}
	function query($sql){
		return  dbQuery($sql);
	}
	
	*/
}
?>