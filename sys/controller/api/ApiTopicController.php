<?php
defined( 'UFM_RUN' ) or die( 'No direct script access allowed.' );
header('Content-Type: application/json');
error_reporting(E_ERROR);

class ApiTopicController extends UController
{	
	function actionIndex()
	{
		$_debug = $_REQUEST['debug'];
		$_limit = isset($_GET['limit'])&&is_numeric($_GET['limit'])?$_GET['limit']:5;

		$artitles = Topic::getHotTopic($_limit);

		if($artitles){
			$result = array('success'=>1,'message'=>'', 'result'=>$artitles);
		}else{
			$result = array('success'=>0,'message'=>'Internal Error');
		}
		
		//debug(123);
		if($_debug){
			debug($result);
		}else{
			echo  uConvertToJson($result);
		}
	}
}

class Topic
{
	function getHotTopic($limit=5){
		$model = new UTopic();
		$rt = $model->getHottest(UModel::IMGSIZE_RELATED, UModel::HITS_PASTWEEK, $limit, $limit);
		foreach($rt as $k=>$v){
			$rt[$k]->title = trimInvalidutf8(htmlspecialchars($v->title));
			$rt[$k]->alt = trimInvalidutf8(htmlspecialchars($v->alt));
			$rt[$k]->url = trimInvalidutf8(htmlspecialchars($v->URL));
			unset($rt[$k]->URL);
		}
		return $rt;
	}
}

	function trimInvalidutf8($string)
	{
		if (!empty($string)) 
		{
			$regex = '/(
				[\xC0-\xC1] # Invalid UTF-8 Bytes
				| [\xF5-\xFF] # Invalid UTF-8 Bytes
				| \xE0[\x80-\x9F] # Overlong encoding of prior code point
				| \xF0[\x80-\x8F] # Overlong encoding of prior code point
				| [\xC2-\xDF](?![\x80-\xBF]) # Invalid UTF-8 Sequence Start
				| [\xE0-\xEF](?![\x80-\xBF]{2}) # Invalid UTF-8 Sequence Start
				| [\xF0-\xF4](?![\x80-\xBF]{3}) # Invalid UTF-8 Sequence Start
				| (?<=[\x0-\x7F\xF5-\xFF])[\x80-\xBF] # Invalid UTF-8 Sequence Middle
				| (?<![\xC2-\xDF]|[\xE0-\xEF]|[\xE0-\xEF][\x80-\xBF]|[\xF0-\xF4]|[\xF0-\xF4][\x80-\xBF]|[\xF0-\xF4][\x80-\xBF]{2})[\x80-\xBF] # Overlong Sequence
				| (?<=[\xE0-\xEF])[\x80-\xBF](?![\x80-\xBF]) # Short 3 byte sequence
				| (?<=[\xF0-\xF4])[\x80-\xBF](?![\x80-\xBF]{2}) # Short 4 byte sequence
				| (?<=[\xF0-\xF4][\x80-\xBF])[\x80-\xBF](?![\x80-\xBF]) # Short 4 byte sequence (2)
			)/x';
			$string = preg_replace($regex, '', $string);
	 
			$result = "";
			$current;
			$length = strlen($string);
			for ($i=0; $i < $length; $i++)
			{
				$current = ord($string{$i});
				if (($current == 0x9) ||
					($current == 0xA) ||
					($current == 0xD) ||
					(($current >= 0x20) && ($current <= 0xD7FF)) ||
					(($current >= 0xE000) && ($current <= 0xFFFD)) ||
					(($current >= 0x10000) && ($current <= 0x10FFFF)))
				{
					$result .= chr($current);
				}
				else
				{
					$ret;    // use this to strip invalid character(s)
					// $ret .= " ";    // use this to replace them with spaces
				}
			}
			$string = $result;
		}
		return $string;
	}