<?php defined( 'UFM_RUN' ) or die( 'No direct script access allowed.' );?>
<?php
class HtmlTable{

	private $rows;
	private $attr; 

	function __construct($attr=false) {
		if($attr) $this->attr	= ' '.$attr;
	}

	function add_row($cell,$type='td',$attr=false){

		if($attr) $attr = ' '.$attr;

		if(!is_array($cell)){
			$row = "<tr".$attr."><".$type.">".$cell."</".$type."></tr>\n";
		}
		elseif($cell['attr']){
			if(count($cell) < 3)
				$row = "<tr".$attr."><".$type." ".$cell['attr'].">".$cell[0]."</".$type."></tr>\n";
			else{
				//print_r($cell);
				foreach($cell as $k => $v){
					//echo $k." attr <br>";
					if($k !== 'attr'){
						$arr[] = "<".$type." ".$cell['attr'].">".$v."</".$type.">";
					}
				}
				$row = "<tr".$attr.">".join('',$arr)."</tr>\n";
			}
		}
		else{
			foreach($cell as $v){
				if(!is_array($v)){
					$arr[] = "<".$type.">".$v."</".$type.">";
				}
				elseif($v['attr']){
					$arr[] = "<".$type." ".$v['attr'].">".$v[0]."</".$type.">";
				}
				else{
					$arr[] = "<".$type.">".join('',$v)."</".$type.">";
				}
			}
			$row = "<tr".$attr.">".join('',$arr)."</tr>\n";
		}
		$this->row[] = $row;
	}

	function get_html(){
		if(!is_array($this->row))
			$rt = false;
		else{
			$rt = "<table".$this->attr.">\n".join('',$this->row)."</table>\n";
		}
		return $rt;
	}
}
/*
$tbl = new HtmlTable('style="color:#f00"');
$tbl->add_row(array(4,array(5,'attr'=>'style="color:#0f0"'),6),'th');
$tbl->add_row(array(1,2,3,'attr'=>'style="color:#0f0"'));
echo $tbl->get_html();
*/
?>