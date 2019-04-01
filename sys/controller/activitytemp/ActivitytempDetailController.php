<?php
defined( 'UFM_RUN' ) or die( 'No direct script access allowed.' );

class ActivitytempDetailController extends UController
{
	private static $cookiesName = 'eventViewedHistory';
    private static $historyLimit = 5;
	
	function actionShowdates(){
		$page_id	= isset($_REQUEST['id'])?$_REQUEST['id']:0;
		$page_id 	= (int)url_decrypt($_REQUEST['id']);
		if(!$page_id>0) die();
		/* 活動日曆 */
		$UEvent= new UEvent();
		$rows = $UEvent->getAllDate($page_id);
		$rs = array();
		foreach($rows as $k=>$v){
			$dates = array($v->start_date);
			$rs[]=array((int)date('n', strtotime(($v->start_date))),(int)date('j', strtotime(($v->start_date))),(int)date('Y', strtotime(($v->start_date))),$v->descr);
			while(end($dates) < $v->end_date){
				$dates[] = date('Y-m-d', strtotime(end($dates).' +1 day'));
				$rs[]=array((int)date('n', strtotime(end($dates))),(int)date('j', strtotime(end($dates))),(int)date('Y', strtotime(end($dates))),$v->descr);
			}
		}
		//debug(json_encode($rs),0);
		echo json_encode($rs);
	}
	function actionIndex()
	{	
		$page_id	= isset($_REQUEST['id'])?url_decrypt($_REQUEST['id']):0;
		$preview 	= isset($_REQUEST['preview']) ? (int) $_REQUEST['preview'] : 0;

		if(!$page_id>0)
			$this->redirect("/activity/list");
			
		
		
		$UEvent= new UEvent();
		$items = $UEvent->findByPk($page_id);//debug($items);

		if(empty($items)||($items->published == 0 && $preview == 0))
			$this->redirect('/activity/list');
			
		 if($preview == 0)
			$this->logPageHits($page_id, UEvent::PAGETYPE_ID); 
			
		//set this instance to cookies
        $this->setHistoryCookies(self::$cookiesName, self::$historyLimit, UEvent::PAGETYPE_ID, $items->event_id, $items->title);
		
		$this->pageTitle = getMetaTitle($items->title);
        $this->metaKeywords = getMetaKeywords($items->meta_keywords);
        $this->metaDescription = getMetaDescription($items->meta_descr);
		
		//debug($items,0);
/* 		$this->pageTitle = getTitle($items->title);
		$this->metaKeywords = $items->meta_keywords;
		$this->metaDescription = $items->meta_descr; */

		$photoRS = $UEvent->getPhotos($page_id);

		$utm_campaign = 'activity-'.$_REQUEST['id'];
		$utm_source = 'uhk';
		
		/* Content List */
		$contentList = $UEvent->getContentList($page_id);
		if($preview == 0){
			$URL_parms = array('id'=>url_encrypt($items->event_id));		
		}else{
			$URL_parms = array('id'=>url_encrypt($items->event_id), 'preview'=>$preview);
		}					
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
		
		
		if(isset($content_ids)){
			$content_id = isset($_REQUEST['content'])&&in_array($_REQUEST['content'],$content_ids)?$_REQUEST['content']:$content_ids[0];
			$currContent = $UEvent->getContentById($page_id,$content_id);

			//lazy loading
			// $content = $contentList[0]->content;
			$content = $currContent->content;
			preg_match_all('/\<p.*?\>.*?\[photo=(\d+)\].*?\<\/p\>/i', $content, $matches);
			if(count($matches[0])>2){
				$arr = explode($matches[0][2], $content);
				$referrer_id = $matches[1][2];
				$content = $arr[0];
			}
			
			if($id != 13325)
				$item['content'] = addPhotoToContent($content, '/cms/news_photo/w600/', '/cms/news_photo/original/', $relatedPhotos);
			//lazy loading	
			if (!empty($photoRS)) {
				$currContent->content = $UEvent->addPhotoToContent($content, UEvent::getImgPath(UEvent::IMGSIZE_DETAIL), UEvent::getImgPath(UEvent::IMGSIZE_LARGE), $photoRS);
			}
			$currContent->content = uConvertContentTags($currContent->content, array('youtubeWidth'=>600,'youtubeHeight'=>400));
		}

		$this->assign("referrer_id",$referrer_id);
		
		
		$photos = array();
        foreach ($photoRS as $photosResult) {
			if($photosResult->is_fb_cover==1) $photosOG[] = UEvent::getImgPath(UEvent::IMGSIZE_DETAIL) . $photosResult->photo_name;
            $photos[] = UEvent::getImgPath(UEvent::IMGSIZE_DETAIL) . $photosResult->photo_name;

        }
		// if(sizeOf($photos)==0){
			// $cover=$UEvent->getCoverPhoto($page_id);
			// $photos[] = UEvent::getImgPath(UEvent::IMGSIZE_DETAIL) . $cover->cover_photo;
		// }
		
		if(sizeOf($photosOG)!=0)
			$photos=$photosOG;
		$this->metaOptions['facebook'] = uGetFacebookMetas($photos, 5);
        $this->metaOptions['canonicalUrl'] = UEvent::getURL($page_id);
		
		$this->assign("rating", UEvent::getRatingHtml($items->avg_rating));
		
		$this->assign('currContentID',$content_id);
		$this->assign('currContent',$currContent);
		$this->assign('items',$items);

		
		/* 活動排行榜 */
		$rankingList = $UEvent->getHottest(UEvent::IMGSIZE_RELATED,UEvent::HITS_PASTWEEK,5);
		foreach($rankingList as $k=>$v){
			$rankingList[$k]->URL .= '&utm_source='.$utm_source.'&utm_campaign='.$utm_campaign.'&utm_medium=rank&utm_content=rank'.str_pad($k+1, 2, '0', STR_PAD_LEFT);
		}
		$this->assign('rankingList',$rankingList);
		//debug($rankingList,0);
		
		/* 活動細節 */
		$details = $UEvent->getEventDetailsById($page_id);
		$this->assign('details',$details);
		//debug($details,0);
		
		/* 相關話題 */
        $relatedObjectMappings = $UEvent->getRelatedObjects($page_id, 8, $items->related_tag_id);
		$related = $this->getPageSummary($relatedObjectMappings);
		foreach($related as $k=>$v){
			$related[$k]->URL  .= '&utm_source='.$utm_source.'&utm_campaign='.$utm_campaign.'&utm_medium=related&utm_content=related'.str_pad($k+1, 2, '0', STR_PAD_LEFT);
			$related[$k]->title = ($related[$k]->short_title!="") ? $related[$k]->short_title : $related[$k]->title ;
		}
        $this->assign('related', $related);   
		//debug($this->getPageSummary($relatedObjectMappings));
		
		
		/* Categories */
		$categories = $UEvent->getEventCatsById($page_id);
		$this->assign('categories',$categories);
		//debug($categories);
		
		/* Location */
		$locations = $UEvent->getEventAreas($page_id);
		$this->assign('locations',$locations);
		//debug($locations);
		
		/* 活動日曆 */
		$dates = $UEvent->getAllDate($page_id);
		$this->assign('dates',$dates);
		//debug($dates,0);
		
		//Rating
		$ratingCookies = $this->getRatingCookies(UEvent::PAGETYPE_ID, $page_id);
		if ($ratingCookies == -1) {
			$this->assign("rated", FALSE);
		} else {
			$this->assign("rated", TRUE);
		}
		
		/* Instagarm */
		if($items->ig_tag){
			$ig_tag = substr($items->ig_tag,0,1)=='#'?substr($items->ig_tag,1):$items->ig_tag;
			//debug($ig_tag);
			$option = array('hashtag'=>$ig_tag,
							'iframe_width'=>300,
							'iframe_height'=>200,
							'count'=>6,
							'img_size'=>98
							);
			$igPhoto = getIgPhoto($option);
			$this->assign('igPhoto',$igPhoto);
			$this->assign('ig_tag',$ig_tag);
		}
		
		//get viewed event from cookies
		$histories =$this->getHistoryCookies(self::$cookiesName);
		foreach($histories as $k=>$v){
			$histories[$k]->url .= '&utm_source='.$utm_source.'&utm_campaign='.$utm_campaign.'&utm_medium=history&utm_content=history'.str_pad($k+1, 2, '0', STR_PAD_LEFT);
		}
		$this->assign('histories', $histories);
		
		/* Tags */
		$tags = $UEvent->getEventTagsById($page_id);
		$this->assign('tags',$tags);
		
		/* Followed */
		$isLogin = $this->user->isLogin();
		$this->assign('isLogin',$isLogin);
		if($isLogin){
			$isFollowed = $this->user->isFollowed(UEvent::PAGETYPE_ID, $page_id);
			$this->assign('isFollowed',$isFollowed);
		}
			$followedUserAmount = $this->user->getFollowedUserAmount(UEvent::PAGETYPE_ID, $page_id);
			$this->assign('followedUserAmount',$followedUserAmount[0]->count);
		 
		$share_url = UEvent::getURL($page_id);
		$this->assign('share_url',$share_url);
		
		$fb_like_share_button = facebook_like_share_button($share_url); 
		$google_plus_one_button = google_plus_one_button($share_url);
		$this->assign('fb_like_share_button',$fb_like_share_button);
		$this->assign('google_plus_one_button',$google_plus_one_button);
		$this->assign('fb_google_button',$fb_like_share_button.$google_plus_one_button);
		
		$comment_box = facebook_comment_box($share_url,655);
		$this->assign('comment_box',$comment_box);
		
		$fb_like_box = facebook_like_box();
		$this->assign('fb_like_box',$fb_like_box);
		
		
		// rectangle banner
		/* $banner_300_250 = getAdZone(2570,300,250,'a9e7d3be');
		$this->assign("banner_300_250",$banner_300_250);
		$banner_300_100 = getAdZone(2571,300,100,'aa325ddf');
		$this->assign("banner_300_100",$banner_300_100); */
		
		//Ad Banner
		$babybanner1 = uGetAdItem('div-gpt-ad-1429860951715-0');
		$babybanner2 = uGetAdItem('div-gpt-ad-1429860951715-1');
		$lrec1 = uGetAdItem('div-gpt-ad-1429860951715-2');
		$lrec2 = uGetAdItem('div-gpt-ad-1429860951715-3');
		$skyscraper1 = uGetAdItem('div-gpt-ad-1429860951715-4');
		$skyscraper2 = uGetAdItem('div-gpt-ad-1429860951715-5');
		$superbanner1 = uGetAdItem('div-gpt-ad-1429860951715-6');
		$superbanner2 = uGetAdItem('div-gpt-ad-1429860951715-7');
		
		$this->assign("topBanner",$superbanner1);
		$this->assign("bottomBanner",$superbanner2);
		$this->assign("largeRectangle1Banner",$lrec1);
		$this->assign("largeRectangle2Banner",$lrec2);
		$this->assign("babyBanner1",$babybanner1);
		$this->assign("babyBanner2",$babybanner2);
		$this->assign("skyscraperLeftBanner",$skyscraper1);
		$this->assign("skyscraperRightBanner",$skyscraper2);
		
		// $this->addJs('js/global/jquery-1.8.2.min.js');
		$this->addJs('js/global/jquery.form.js');
		
		$this->addJs('js/global/datepicker.js');
		$this->addCss('css/global/uhk-datepicker.css');
		$this->addCss('css/uhk-activities-details.css');	
		
		
		$this->addCss('css/global/uhk-thickbox-3.1.css');
		$this->addJs('js/global/uhk-thickbox-3.1.js');
		
		
		//$this->layout = 'column2';
		$this->display();
		
	
	
	}
}
