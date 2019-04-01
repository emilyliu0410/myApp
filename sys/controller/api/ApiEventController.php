<?php
defined( 'UFM_RUN' ) or die( 'No direct script access allowed.' );
header('Content-Type: application/json');
error_reporting(E_ERROR);

class ApiEventController extends UController
{	
	function actionIndex()
	{
		$_debug = $_REQUEST['debug'];
		$_limit = isset($_GET['limit'])&&is_numeric($_GET['limit'])?$_GET['limit']:5;
		$_get = isset($_GET['get'])&&in_array($_GET['get'],array('latestEvent','hotEvent'))?$_GET['get']:'';
		
		if($_get){
			if($_get=='latestEvent'){
				$events = Event::getLatestEvent($_limit);
			}elseif($_get=='hotEvent'){
				$events = Event::getHotEvent($_limit);
			}
			$result = array('success'=>1,'message'=>'', 'result'=>$events);
		}else{
			$result = array('success'=>0, 'message'=>'Invalid Parameter');
		}

		
		//debug(123);
		if($_debug){
			debug($result);
		}else{
			echo  uConvertToJson($result);
		}
	}
}

class Event
{
	function getLatestEvent($limit=5){
		$model = new UEvent();
		
		$sql = "SELECT	a.event_id, a.title, b.photo_name, c.content
				FROM	tbl_event a, tbl_event_photo b, tbl_event_content c
				WHERE	a.event_id = b.event_id
					AND a.event_id = c.event_id
					AND	a.published = 1
					AND a.lcsd_id IS NULL
					AND	b.is_cover = 1
					AND c.ordering = 1
				ORDER BY a.update_date DESC
				LIMIT ".$limit;		//prev ordering:ORDER BY a.publish_date DESC
		$rs = uDb()->findList($sql);
		
		$rt = array();
		foreach($rs as $k=>$v){
			// $temp = array();
			// $temp[id] = $v->event_id;
			// $temp[title] = $v->title;
			// $temp[content] = trim_html_text($v->content);
			// $temp[url] = $model->getURL($v->event_id);
			// $cover_photo = $model->getImgPath(UModel::IMGSIZE_RELATED) .str_replace(' ', '%20',$v->photo_name);
			// $temp[cover_photo] = (getimagesize($cover_photo)>0)?$cover_photo:$model->getImgPath(UModel::IMGSIZE_RELATED).UEvent::DEFAULT_COVER;
			// $rt[] = $temp;
			
			$rs[$k]->url = $model->getURL($v->event_id,$v->title);			
			$cover_photo = $model->getImgPath(UModel::IMGSIZE_RELATED) .str_replace(' ', '%20',$v->photo_name);
			$rs[$k]->cover_photo = ($cover_photo)?$cover_photo:$model->getImgPath(UModel::IMGSIZE_RELATED).UEvent::DEFAULT_COVER;
			$rs[$k]->content = trim_html_text($v->content);
			unset($rs[$k]->photo_name);
		}
		
		//$rt = $model->getLatest(UModel::IMGSIZE_RELATED, $limit, 1);
		return $rs;
	}

	function getHotEvent($limit=5){
		$model = new UEvent();
		$rt = $model->getHottest(UModel::IMGSIZE_RELATED, UModel::HITS_PASTWEEK, $limit, $limit);
		return $rt;
	}
}