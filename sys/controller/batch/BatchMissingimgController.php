<?php
defined( 'UFM_RUN' ) or die( 'No direct script access allowed.' );
define('UBASE_WEB',realpath(dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR));

class BatchMissingimgController extends UController
{
	private $count = 0;
	
	function actionIndex()
	{
		set_time_limit(1000);
		
		$type = isset($_GET['type']) ? $_GET['type'] : '';
		
		$UContent = new UContent();
		$UTheme = new UTheme();
		
		if($type == 'count'){
			//article contents
			$contents = $UContent->search();
			echo "<p>Total article content:".uDb()->foundRows()."</p>";
			
			//theme contents
			$contents = $UTheme->searchTheme();
			echo "<p>Total theme content:".uDb()->foundRows()."</p>";
			
			exit();
		}
		
		// article content
		if($type == 'article' || empty($type)){
			echo '===================================<br>';
			echo '===================================<br>';
			echo '=============Article cover=============<br>';
			echo '===================================<br>';
			echo '===================================<br>';
			
			
			$sql = " SELECT SQL_CALC_FOUND_ROWS
						t.content_id,
						t.pagetype_id,
						t.page_id,
						t.title,
						t.create_date,
						t.publish_date,
						t.update_date,
						t.cover_photo
					FROM
						tbl_content t
					WHERE
						t.published = 1 AND t.publish_date <= NOW()
					GROUP BY t.content_id, t.update_date DESC";
			$contents = uDb()->findList($sql);

			foreach ($contents as $k => $v)
			{
				if(!empty($v->cover_photo)){
					$this->batch('article', $v);
				}
			}
			
			echo "<p style='color:blue'>Total: $this->count</p>";
		}
		
		// theme content
		if($type == 'theme' || empty($type)){
			echo '===================================<br>';
			echo '===================================<br>';
			echo '==============Theme cover============<br>';
			echo '===================================<br>';
			echo '===================================<br>';

			$this->count = 0;
			
			$sql = " SELECT SQL_CALC_FOUND_ROWS
						t.theme_id,
						t.pagetype_id_import,
						t.page_id_import,
						t.title,
						t.create_date,
						t.publish_date,
						t.update_date,
						t.cover_photo,
						t.thumbnail_photo
					FROM
						tbl_theme t
					WHERE
						t.published = 1 AND t.publish_date <= NOW()
					GROUP BY t.theme_id, t.update_date DESC";
			$contents = uDb()->findList($sql);

			foreach ($contents as $k => $v)
			{
				if(!empty($v->cover_photo)){
					$this->batch('theme', $v);
				}
			}
			
			echo "<p style='color:blue'>Total: $this->count</p>";
		}
		
		exit();
		
	}
	function batch($type,$v)
	{
		if($type=='article'){
			$imgPath = $this->getImgPath($v->pagetype_id, UModel::IMGSIZE_RELATED);
			$imgPath_url = $this->getImgPath($v->pagetype_id, UModel::IMGSIZE_RELATED,false);
		}elseif($type=='theme'){
			$imgPath = $this->getImgPath(UTheme::PAGETYPE_ID, 'cover_small');
			$imgPath_url = $this->getImgPath(UTheme::PAGETYPE_ID, 'cover_small',false);
		}
		
		$cover_photo = $imgPath . $v->cover_photo;
		$cover_photo_url = $imgPath_url . $v->cover_photo;
		
		// missing image
		if(!file_exists($cover_photo)){
			if($type=='article'){
				$page_url = $this->getURL($v->pagetype_id, $v->page_id);
				
			}elseif($type=='theme'){
				$page_url = $this->getURL(UTheme::PAGETYPE_ID, $v->theme_id);
			}
			
			$this->show($type,$cover_photo_url,$page_url,$v);
			$this->count++;
		}
		
	}
	
	function show($type,$cover_photo,$page_url,$v)
	{
		if($type=='article'){
			echo "<p>content_id:$v->content_id pagetype_id:$v->pagetype_id page_id:$v->page_id title:<a href='$page_url'>$v->title</a> create_date:$v->create_date</p>";

		}elseif($type=='theme'){
			echo "<p>theme_id:$v->theme_id pagetype_id_import:$v->pagetype_id_import page_id_import:$v->page_id_import title:<a href='$page_url'>$v->title</a> create_date:$v->create_date</p>";
		}
		
		if(!strpos($cover_photo,' ')) echo "<span style='color:red'>NO space</span>";
		echo "<p>$cover_photo </p>";
		echo "<p>--------------------</p>";
	}
	
	
	function getImgPath($pagetype_id, $imgSize, $soure=true) {
		$path = '';
		switch ($pagetype_id) {
			case UEvent::PAGETYPE_ID:
				$path = '/cms/images/event/';
                break;
			case USpot::PAGETYPE_ID:
				$path = '/cms/images/spot/';
                break;
			case UTour::PAGETYPE_ID:
				$path = '/cms/images/tour/';
                break;
			case UTopic::PAGETYPE_ID:
				$path = '/cms/images/topic/';
                break;
			case UTheme::PAGETYPE_ID:
				$path = '/cms/images/theme/';
				break;
				
        }
		
        $directory = '';
        switch ($imgSize) {
			case UModel::IMGSIZE_HOME:
				$directory = '150x200';
                break;
            case UModel::IMGSIZE_LISTING:
                $directory = 'w300';
                break;
            case UModel::IMGSIZE_DETAIL:
                $directory = 'w600';
                break;
            case UModel::IMGSIZE_LARGE:
                $directory = '1024';
                break;
            case UModel::IMGSIZE_RELATED:
                $directory = '300x200';
                break;
			case UModel::IMGSIZE_RANKING:
                $directory = '120x120';
                break;
			case 'thumbnail':
                $directory = '300x300';
            break;	
			case 'cover_small':
                $directory = '750x380';
            break;
			case 'cover':
                $directory = '900x456';
            break;
        }
		
		$head = '';
		if($soure){
			$head = UBASE_WEB ;
		}else{
			$head = UAPP_HOST . UAPP_BASE_URL;
		}
		
		
        return $head . $path . $directory . '/';
    }
	
	function getURL($pagetype_id, $page_id) {
		$url = '';
		switch ($pagetype_id) {
			case UEvent::PAGETYPE_ID:
				$url = UEvent::getURL($page_id);
                break;
			case USpot::PAGETYPE_ID:
				$url = USpot::getURL($page_id);
                break;
			case UTour::PAGETYPE_ID:
				$url = UTour::getURL($page_id);
                break;
			case UTopic::PAGETYPE_ID:
				$url = UTopic::getURL($page_id);
                break;
			case UTheme::PAGETYPE_ID:
				$url = UTheme::getURL($page_id);
				break;
        }
		
        return $url;
    }
}