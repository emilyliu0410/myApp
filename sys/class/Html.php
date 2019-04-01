<?php defined( 'UFM_RUN' ) or die( 'No direct script access allowed.' );?>
<?php
class Html{
	//slice
	function slice($str){
		$rt = array();
		$str = str_replace(array(' '),',',$str);
		$tags = explode(',',$str);
		foreach($tags as $v){
			if(!empty($v)){
				$rt[] = trim($v);
			}
		}
		return $rt;
	}
	function filterAttr($str,$allows='') {
		if(empty($allows)){
			$pattern = '/<([a-z]+)[^>]*>/i';
			$replacement = '<$1>';
			$str = preg_replace($pattern,$replacement,$str);
		}
		else{
			$allows = self::slice($allows);
			if(count($allows)){
				$pattern = '/<([a-z]+[^>]*)(\s(?!'.join('|',$allows).')[a-z]+=[^\s>]*)+>/i';
				//debugMatch($pattern,$str,1,1,1);
				while(preg_match($pattern,$str)){
					$replacement = '<$1>';
					$str = preg_replace($pattern,$replacement,$str);
				}
			}
		}
	
		return  $str;
	}

	function filterTag($str,$allows='') {
		if(!empty($allows)){
			$allows = self::slice($allows);
			if(count($allows)) $allows = '<'.join('><',$allows).'>';
		}
		$str = strip_tags($str,'div,br');
	
		return  $str;
	}
	
	function getAttrAll($str,$tag,$attr) {
		$rt = false;
		preg_match_all("/<".$tag."[^>]*\s".$attr."=['\"]([^'\"]+)['\"]/i", $str,$matches); 
		if(is_array($matches[1])) $rt =  array_unique($matches[1]);
		return $rt;
	}

	function rmImg($str) {
		return preg_replace("/<img[^>]+>/", "", $str);
	}

	function getSelect($arr,$attr=false,$selected = null){
		return self::select($arr,$attr,$selected);
	}
	function select($arr,$attr=false,$selected = null,$options_only=false){

		if(is_array($arr)){
			foreach($arr as $v){
				if(!is_array($v)){
					$v = array($v,$v);
				}
				$extra	= '';
				//debug($selected);
				if($selected !== null){
					if (is_array( $selected ))
					{
						foreach ($selected as $val)
						{
							$k2 = is_object( $val ) ? $val->$key : $val;
							if ($v[0] == $k2)
							{
								$extra .= " selected=\"selected\"";
								break;
							}
						}
					} else {
						$extra .= ($v[0] == $selected ? " selected=\"selected\"" : '');
					}
				}
				//if($attr =='name="haunt"') debug($selected);
				//debug($v);

				if(!is_array($v[0])){
					$v[1]  = $v[1] ? $v[1] : $v[0];
					$option[] = self::element('option',$v[1],'value="'.$v[0].'" '.$extra);
				}
				//有分组
				else{
					$optionGroup = array();
					foreach($v[1] as $vs){
						$extra = $vs[0] == $selected ? " selected=\"selected\"" : '';
						$optionGroup[] = self::element('option',$vs[1],'value="'.$vs[0].'" '.$extra);
					}
					$option[] = self::element('optgroup',join("\n",$optionGroup),'label="'.$v[0][1].'"');
				}
			}
			
			$rt = join("\n",$option);
			$rt = $options_only ? $rt : self::element('select',$rt,$attr);
		}
		return $rt;
	}

	function radios($data,$name,$default=false,$htmlOptions=array()){
		
		$rt = array();
		$separator = isset($htmlOptions['separator']) ? $htmlOptions['separator'] : ' &nbsp;';

        foreach($data as $v=>$label){
			$checked = ($default !== false && $v == $default) ? 'checked':'';
			$rt[] = '<input type="radio" name="'.$name.'" value="'.$v.'" id="'.$name.''.$v.'" '.$checked.'   /> <label for="'.$name.''.$v.'">'.$label.'</label>'; 
		}
		$rt = join($separator,$rt);
		return $rt;
	}
	function radio($arr,$attr=false,$selected = null,$id=false){
		if(!is_array($arr))
			$rt = false;
		else{
			$i = 0;
			foreach($arr as $v){
				//<input type="radio" value="2" id="gender2" name="gender">
				$attr_tmp = $attr.'type="radio" value="'.$v[0].'"';
				$v[0] == $selected ? $attr_tmp .= " checked=\"checked\"" : false;
				if($id){
					$id_tmp = $id.$i;
					$attr_tmp .=' id="'.$id_tmp.'"'; 
				}
				$rd[] = self::element('input',false, $attr_tmp,' '.self::element('label',$v[1],'for="'.$id_tmp.'"'));
				$i++;
			}	
			$rt = join(" \n",$rd);
		}
		return $rt;
	}
	
	function getUl($arr,$attr=false){
		if(is_array($arr)){
			foreach($arr as $v){
				if(!is_array($v)){
					$li[] = self::element('li',$v);
				}
				//有分组
				else{
					if(!is_array($v[1])){
						//debug('ttt');
						$li[] = self::element('li',$v[1],$v[0]);
					}
					else{
						$liUl = self::getUl($v[1]);
						if(!is_array($v[0])){
							$li[] = self::element('li',$v[0].$liUL);
						}
						else{
							$li[] = self::element('li',$v[0][1].$liUl,$v[0][0]);
						//debug($li);
						//debug($v[0]);
						//debug($liUl);
						}
					}
					//debug($li);
				}
			}
			$rt = self::element('ul',join("\n",$li),$attr);
		}
		return $rt;
	}
	function getList($arr,$attr=false,$type='ul'){
		if(is_array($arr)){
			foreach($arr as $v){
				if(!is_array($v)){
					$list[] = self::element('li',$v);
				}
				else{
					$num = count($v);
					if($num == 2 && isset($v['attr'])){
						$list[] = self::element('li',$v[0],$v['attr']);

					}
					elseif($num == 2 && is_array($v[1])){
						$listSub = self::getList($v[1],false,$type);
						//debug($listSub);
						$list[]  = self::element('li',$v[0].$listSub);

					}
					elseif($num == 3 && isset($v['attr'])){
						$listSub = self::getList($v[1],$attr,$type);
						$list[] = self::element('li',$v[0].$listSub,$v['attr']);
					}
					else{
						$listSub = self::getList($v,$attr,$type);
						$list[] = self::element('li',$listSub,$v['attr']);
					}
				}
			}
			$rt = self::element($type,join("\n",$list),$attr);
		}
		return $rt;
	}
/*
	function getUl0($arr,$attr=false){
		if(is_array($arr)){
			foreach($arr as $v){
				if(!is_array($v[0])){
					$inner = self::element('a',$v[1],'id="'.$v[0].'"');
					$li[] = self::element('li',$inner,'id="'.$v[0].'"');
				}
				//有分组
				else{
					$liUl = self::getUl($v[1]);
					$inner = self::element('a',$v[0][1],'id="'.$v[0][0].'"');
					$li[] = self::element('li',$inner.$liUl);
				}
			}
			$rt = self::element('ul',join("\n",$li),$attr);
		}
		return $rt;
	}
*/
	
	function getLink($text,$href,$attr=false){
		$rt = self::a($text,$href,$attr);		
		return $rt;
	}
	function a($text,$href,$attr=false){
		if($attr) $attr = ' '.$attr;
		return self::element('a',$text,'href="'.$href.'"'.$attr);
	}

	function getMark($type,$text = false,$attr=false){
		$rt = self::element($type,$text,$attr);		
		return $rt;
	}
	function element($type,$text = false,$attr=false,$html_after=false){
		$type = strtolower(trim($type));

		$single = in_array($type,array('input','br','hr','img')) ? true:false; 
		if($attr) $attr = ' '.$attr;
		$rt = '<'.$type.$attr;
		$rt .= $single ? ' />'.$text : '>'.$text.'</'.$type.'>'; 
		if($html_after) $rt.= $html_after;
		return $rt;
	}
	
	function parse($option,$html){
		if(is_array($option)){
			$isArray = true;
			$pattern = join('|',$option);
		}
		else{
			$isArray = false;
			$pattern = $option;
		}

		preg_match_all("/<(".$pattern.")>(.*)<\/\\1>/is", $html,$matches);
		
		if($isArray){
			foreach($option as $k => $v){
				$option[$k] = $matches[2][$k];
			}
		}

		return $option;
	}
	function rmTag($option,$html){
		if(is_array($option)){
			$isArray = true;
			$pattern = join('|',$option);
		}
		else{
			$isArray = false;
			$pattern = $option;
		}
		/*
		$html = preg_replace("/<(".$pattern.")>(.*)<?\/\\1?>/is",'', $html);
		*/
		$html = preg_replace("/<(".$pattern.")\w*>?([^<>]*)<?\/(\\1)?>/is",'', $html);
		
		return $html;
	}


	// --- 單選按鈕 ---
	function radiolist( $arr, $name,  $key = 'value', $text = 'text', $selected = null, $num = 0 ,$choose='', $idtag = false)
	{
			reset( $arr );
			$html = '';
			$html_table = "<table><tr>";

			$id_text = $name;
			if ( $idtag ) {
				$id_text = $idtag;
			}

			for ($i=0, $n=count( $arr ); $i < $n; $i++ )
			{
				$k	= $arr[$i][$key];
				$t	= $arr[$i][$text];
				$id	= ( isset($arr[$i][$key]) ? @$arr[$i][$key] : null);

				$extra	= '';
				if (is_array( $selected ))
				{
					foreach ($selected as $val)
					{
						$k2 = is_object( $val ) ? $val->$key : $val;
						if ($k == $k2)
						{
							$extra .= " checked=\"checked\"";
							break;
						}
					}
				} else {
					$extra .= ($k == $selected ? " checked=\"checked\"" : '');
				}
				$html .= "<input type=\"radio\" name=\"$name\" id=\"$id_text$k\" value=\"".$k."\"$extra />$t&nbsp;&nbsp;";
				$html_table .= "<td><input type=\"radio\" name=\"$name\" id=\"$id_text$k\" value=\"".$k."\"$extra />$t</td>";
				
				if( $num > 0 && (($i+1) % $num == 0)){
					$html .= "\n";
					$html_table .= "</tr><tr>";
				}

			}
			$html .= "\n";
			$html_table .= "</tr></table>";

			if($choose=="table"){
				return $html_table;
			}else{
				return $html;
			}
	}


	// --- 多選按鈕 ---
	function checkboxlist( $arr, $name,  $key = 'value', $text = 'text',$num = 0,$choose='',$selected = null, $idtag = false )
	{
			reset( $arr );
			$html = '';
			$html_table = "<table><tr>";

			$id_text = $name;
			if ( $idtag ) {
				$id_text = $idtag;
			}

			for ($i=0, $n=count( $arr ); $i < $n; $i++ )
			{
				$k	= $arr[$i][$key];
				$t	= $arr[$i][$text];
				$id	= ( isset($arr[$i][$key]) ? @$arr[$i][$key] : null);

				$extra	= '';
				if (is_array( $selected ))
				{
					foreach ($selected as $val)
					{
						$k2 = is_object( $val ) ? $val->$key : $val;
						//if (is_object($val)){
						//	$k2 = $val->$key;
						//}else if(is_array($val)){
						//	$k2 = $val[0];
						//}else{
						//	$k2 = $val;
						//}
						
						if ($k == $k2)
						{	
							$extra .= " checked=\"checked\"";
							break;
						}
					}
				} else {
					$extra .= ($k == $selected ? " checked=\"checked\"" : '');
				}
				$html .= "<input type=\"checkbox\" name=\"$name\" id=\"$id_text$k\" value=\"".$k."\"$extra />$t&nbsp;&nbsp;";	//error
				$html_table .= "<td><input type=\"checkbox\" name=\"$name\" id=\"$id_text$k\" value=\"".$k."\"$extra />$t </td>"; //error
				
				if( $num > 0 && (($i+1)%$num == 0)){
					$html .= "<br>";
					$html_table .= "</tr><tr>";
				}
			}
			$html .= "\n";
			$html_table .= "</tr></table>";

			if($choose=="table"){
				return $html_table;
			}else{
				return $html;
			}
	}
	
	function multicheckboxlist( $arr, $name,  $key = 'value', $text = 'text',$num = 0,$choose='',$selected = null, $idtag = false )
	{
			reset( $arr );
			$html = '';
			$html_table = "<table><tr>";

			$id_text = $name;
			if ( $idtag ) {
				$id_text = $idtag;
			}

			for ($i=0, $n=count( $arr ); $i < $n; $i++ )
			{
				$k	= $arr[$i][$key];
				$t	= $arr[$i][$text];
				$id	= ( isset($arr[$i][$key]) ? @$arr[$i][$key] : null);

				$extra	= '';
				if (is_array( $selected ))
				{
					foreach ($selected as $val)
					{

						$k2 = $val[0];
						if ($k == $k2)
						{	
							$extra .= " checked=\"checked\"";
							break;
						}
					}
				} else {
					$extra .= ($k == $selected ? " checked=\"checked\"" : '');
				}
				$html .= "<input type=\"checkbox\" name=\"$name\" id=\"$name$k\" value=\"".$k."\"$extra /> <label for=\"$name$k\">$t</label>&nbsp;&nbsp;";
				$html_table .= "<td><input type=\"checkbox\" name=\"$name\" id=\"$id_text\" value=\"".$k."\"$extra /> $t </td>";
				
				if( $num > 0 && (($i+1)%$num == 0)){
					$html .= "<br>";
					$html_table .= "</tr><tr>";
				}
			}
			$html .= "\n";
			$html_table .= "</tr></table>";

			if($choose=="table"){
				return $html_table;
			}else{
				return $html;
			}
	}	

}
?>