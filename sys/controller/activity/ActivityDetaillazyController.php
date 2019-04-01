<?php
// defined( 'UFM_RUN' ) or die( 'No direct script access allowed.' );

class ActivityDetaillazyController extends UController
{
	
	function actionIndex()
	{	
		global $useRedis;
		$useRedis = true;
			
		$page_id	= isset($_REQUEST['id'])?url_decrypt($_REQUEST['id']):0;
        $source = isset($_REQUEST['source']) ? $_REQUEST['source'] : 0;
		$page	= isset($_REQUEST['page'])?$_REQUEST['page']:0;
		$preview 	= isset($_REQUEST['preview']) ? (int) $_REQUEST['preview'] : 0;
		
		if(!is_numeric($page_id) && !($page_id>0)) {
			$this->redirect("/error");
        }
		
		
		$this->assign('page',$page++);
		
		$UEvent= new UEvent();
		$items = $UEvent->findByPk($page_id);//debug($items);

		if ($items->published == 0 && $preview == 0) {
            $this->redirect("/error");
        }
		
		$photoRS = $UEvent->getPhotos($page_id);

		$utm_campaign = 'activity-'.$_REQUEST['id'];
		$utm_source = 'uhk';
		
		/* Content List */
		$contentList = $UEvent->getContentList($page_id);
		
		foreach($contentList as $k=>$v){
			$URL_parms['content'] = $v->content_id;
			$URL_parms['utm_source'] = $utm_source;
			$URL_parms['utm_campaign'] = $utm_campaign;
			$contentList[$k]->URL = $this->createUrl('/activity/detail', $URL_parms);
		}
		$this->assign('contentList',$contentList);
		//debug($contentList,0);
		
		/* Default Content */
		$content_id = 0;
		$currContent = new stdClass();
		foreach($contentList as $v){
			$content_ids[] = $v->content_id;
		}
		
		
		// $content=$UEvent->addPhotoToContent($content, UEvent::getImgPath(UEvent::IMGSIZE_DETAIL), UEvent::getImgPath(UEvent::IMGSIZE_LARGE), $photoRS, 'mobile');
			
		
		// $this->assign("content",$content);
		if(isset($content_ids)){
//			$content_id = isset($_REQUEST['content'])&&in_array($_REQUEST['content'],$content_ids)?$_REQUEST['content']:$content_ids[0];
//			$currContent = $UEvent->getContentById($page_id,$content_id);
			
			// combine content
			foreach($content_ids as $v){
				$tmp_currContent = $UEvent->getContentById($page_id,$v);
				$currContent->content .= $tmp_currContent->content;
			}
			$currContent->content=uAddSignature($currContent->content);
			
		$_num_of_loading_photo = 3;
		$content = $currContent->content;
		$matches = getMatchesByPhotoTagFromContent($content);

        $key = array_search ($source, $matches[1]);

		if($key){
			$start = $matches[0][$key];
			$referrer_id = $matches[1][$key+$_num_of_loading_photo];
			if(count($matches[0])>$key){
				$end = $matches[0][$key+$_num_of_loading_photo];
				$arr = explode($start, $content);
				$result = $arr[1];
				if($end){
					$arr = explode($end, $result);
					$result = $arr[0];
				}
				$content = u_force_balance_tags($start.$result);
			}
		}
		if($id != 13325)
			$item['content'] = addPhotoToContent($content, '/cms/news_photo/w600/', '/cms/news_photo/original/', $relatedPhotos);
		
			if (!empty($photoRS)) {
				$currContent->content = $UEvent->addPhotoToContent($content, UEvent::getImgPath(UEvent::IMGSIZE_DETAIL), UEvent::getImgPath(UEvent::IMGSIZE_LARGE), $photoRS);
			}
			$currContent->content = uConvertContentTags($currContent->content, array('youtubeWidth'=>600,'youtubeHeight'=>400));
		}
		
		$photos = array();
        foreach ($photoRS as $photosResult) {
            $photos[] = UEvent::getImgPath(UEvent::IMGSIZE_DETAIL) . $photosResult->photo_name;
        }
		// $this->metaOptions['facebook'] = uGetFacebookMetas($photos, 5);
        // $this->metaOptions['canonicalUrl'] = UEvent::getURL($page_id);
		
		// $this->assign("rating", UEvent::getRatingHtml($items->avg_rating));
		$this->assign("referrer_id",$referrer_id);
		if($referrer_id>0){
			$url_next_content = UAPP_HOST.UAPP_BASE_URL.'/activity/detaillazy.html?id='.url_encrypt($page_id).'&source='.$referrer_id.'&page='.$page;
			if($preview) $url_next_content .= '&preview=1';
			$this->assign('url_next_content',$url_next_content);
		}

        $this->assign("page_id",url_encrypt($page_id));
        $this->layout='blankHtml';
		
		$this->assign('currContentID',$content_id);
		$this->assign('currContent',$currContent);
		$this->assign('items',$items);//debug($currContent);
		
		$this->layout='blankHtml';
		$this->display();
		
	
	
	}
}
