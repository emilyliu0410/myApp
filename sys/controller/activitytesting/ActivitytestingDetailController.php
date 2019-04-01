<?php
defined( 'UFM_RUN' ) or die( 'No direct script access allowed.' );

class ActivitytestingDetailController extends UController
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
		global $useRedis;
		$useRedis = true;
		
		$page_id = $_id= isset($_REQUEST['id'])?url_decrypt($_REQUEST['id']):0;
		$preview 	= isset($_REQUEST['preview']) ? (int) $_REQUEST['preview'] : 0;

		/* if(!$page_id>0)
			$this->redirect("/activity/list");
			 */
		
		if(isset($this->fm->urlParams[0])&&count($this->fm->urlParams)){
			$page_id = url_decrypt($this->fm->urlParams[0]);

			if(!is_numeric($page_id) && !($page_id>0)){
				$alias = urldecode($this->fm->urlParams[0]);
			}
		}
		if($this->fm->articleId)
			$page_id = $this->fm->articleId;
			
		if(!is_numeric($page_id) && !($page_id>0) && !$alias) {
			$this->redirect("/error");
        }
		
		$UEvent= new UEvent();
		
		if($alias){
			$items = $UEvent->findByAlias($alias);
			$page_id = $items->event_id;
		}else{
			$items = $UEvent->findByPk($page_id);
		}
		
		if(empty($items)||($items->published == 0 && $preview == 0))
			$this->redirect("/error");

		if($_id>0 && ENABLE_SEO_FRIENDLY){
			parse_str($_SERVER['QUERY_STRING'], $parameters);
			unset($parameters['id']);
			$parameters = array_merge(array('id'=>$_id,'url_title'=>$items->title),$parameters);
			$this->redirect301('/activity/detail',$parameters);
		}
		
		 if($preview == 0)
			$this->logPageHits($page_id, UEvent::PAGETYPE_ID); 
			
		//set this instance to cookies
        $this->setHistoryCookies(self::$cookiesName, self::$historyLimit, UEvent::PAGETYPE_ID, $items->event_id, $items->title);
		
		$this->pageTitle = getMetaTitle($items->title);
        $this->metaKeywords = getMetaKeywords($items->meta_keywords,true);
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
//			$content_id = isset($_REQUEST['content'])&&in_array($_REQUEST['content'],$content_ids)?$_REQUEST['content']:$content_ids[0];
//			$currContent = $UEvent->getContentById($page_id,$content_id);
			
			// combine content
			foreach($content_ids as $v){
				$tmp_currContent = $UEvent->getContentById($page_id,$v);
				$currContent->content .= $tmp_currContent->content;
			}
			$currContent->content=uAddSignature($currContent->content);
			
			//lazy loading
			// $content = $contentList[0]->content;
			$_num_of_loading_photo = 3;
			$content = $currContent->content;
//			$matches = getMatchesByPhotoTagFromContent($content);
//			if(count($matches[0])>$_num_of_loading_photo){
//				$arr = explode($matches[0][$_num_of_loading_photo], $content);
//				$referrer_id = $matches[1][$_num_of_loading_photo];
//				$content = u_force_balance_tags($arr[0]);
//			}
			
			if($id != 13325)
				$item['content'] = addPhotoToContent($content, '/cms/news_photo/w600/', '/cms/news_photo/original/', $relatedPhotos);
			//lazy loading	
			if (!empty($photoRS)) {
				$content = $UEvent->addPageDivisionToContent($content, $_num_of_loading_photo);
				$currContent->content = $UEvent->addPhotoToContent2($content, UEvent::getImgPath(UEvent::IMGSIZE_DETAIL), UEvent::getImgPath(UEvent::IMGSIZE_LARGE), $photoRS);
			}
			$currContent->content = uConvertContentTags($currContent->content, array('youtubeWidth'=>600,'youtubeHeight'=>400));
		}

		$this->assign("referrer_id",$referrer_id);

		if($referrer_id>0){
			$url_next_content = UAPP_HOST.UAPP_BASE_URL.'/activity/detaillazy.html?id='.url_encrypt($page_id).'&source='.$referrer_id.'&page=2';
			if($preview) $url_next_content .= '&preview=1';
			$this->assign('url_next_content',$url_next_content);
		}
		
		$UTheme = new UTheme();
		
		/* Published time */
		$items->publish_date = formatDate($items->publish_date);
		
		/* Main Categories */
		$items->main_cat=($items->main_cat_id!=null)?UCategory::getCatInfoById($items->main_cat_id)->cat_name:'';
		$items->main_subcat=($items->main_subcat_id!=null)?UCategory::getCatInfoById($items->main_subcat_id)->cat_name:'';
		
		$regions = $UEvent->getArticleRegion($page_id);
		$this->assign("regions",$regions);
		
		// Get Author info
		if($items->author_id>0){
			$author = $UTheme->getArticleAuthor($items->author_id);
			if(!empty($author->photo)){
				$author->photo =  UAPP_HOST . UAPP_BASE_URL . '/cms/images/author/120x120/'.$author->photo;
			}else{
				$author->photo = UAPP_HOST . UAPP_BASE_URL . '/media/images/global/profile.png';
			}
			$this->assign('author',$author);
		}
		
		$photos = array();
        foreach ($photoRS as $photosResult) {
			if($photosResult->is_fb_cover==1) $photosOG[] = UEvent::getImgPath(UEvent::IMGSIZE_LARGE) . $photosResult->photo_name;
            $photos[] = UEvent::getImgPath(UEvent::IMGSIZE_LARGE) . $photosResult->photo_name;

        }
		// if(sizeOf($photos)==0){
			// $cover=$UEvent->getCoverPhoto($page_id);
			// $photos[] = UEvent::getImgPath(UEvent::IMGSIZE_DETAIL) . $cover->cover_photo;
		// }
		
		// check event finish or not finish
		$items->isEnd = UEvent::checkEventIsEnd($items->event_id);
		
		$items->cover=UEvent::getCoverPhoto($page_id);
		if($items->cover->cover_photo){
			$items->cover->cover_photo = UEvent::getImgPath(UEvent::IMGSIZE_DETAIL) . $items->cover->cover_photo;
		}else{
			$items->cover->cover_photo = UAPP_MEDIA_URL.'/images/global/default600x400.jpg';
		}
		
		
		if(sizeOf($photosOG)!=0)
			$photos=$photosOG;
		$this->metaOptions['facebook'] = uGetFacebookMetas($photos, 5);
        $this->metaOptions['canonicalUrl'] = UEvent::getURL($page_id,$items->title);
		
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
		
		/* Categories - MallKing */
		$cat_mallking = $UEvent->getEventCatsById($page_id,1,1);
		foreach($cat_mallking as $k=>$v){
			if($v->cat_id == 50){
				$this->assign('cat_mallking',$v);
			}
		}
		
		/* Followed */
		$isLogin = $this->user->isLogin();
		$this->assign('isLogin',$isLogin);
		
		/* 主題內容 */
		$theme = $UTheme->getThemeByArticle(UEvent::PAGETYPE_ID,$page_id);
		if($theme!=null){
			// Get page location
			$page_location=$UTheme->getArticleLocation(UEvent::PAGETYPE_ID,$page_id);
			
			if($theme->theme_type == 2 || $theme->theme_type == 3 || $items->address != null || $page_location->landmark_key != null){
				$theme->thumbnail_photo = ($theme->thumbnail_photo!=null)?UTheme::getImgPath('thumbnail'). $theme->thumbnail_photo:UTheme::getDefaultPhoto("300x300");
//				$theme->detail = $UTheme->getThemeDetailById($theme->theme_id);
				unset($theme->address);
				
				if($isLogin){
					$theme->isFollowed = $this->user->isFollowed(UTheme::PAGETYPE_ID, $theme->theme_id);
				
					// Theme Rating
					$ratingCookies=$UTheme->rated($this->user->user_id,$theme->theme_id);
					if($ratingCookies==0){
						$this->assign("theme_rated",FALSE);
					}else{
						$this->assign("theme_rated", TRUE);
					}
				}
				
				// get theme rating
				$theme->rating_count=$UTheme->rating_count($theme->theme_id);
				
				// get theme page url
				$theme->page_url=$UTheme->getURL($theme->theme_id,'detail',$theme->title);
				
				// get page address
				/*  if($items->address != null){
					$theme->address = $items->address;
					$theme->google_lat = $items->google_lat;
					$theme->google_long = $items->google_long;
					$theme->google_zoom = $items->google_zoom;
				}  */
				
				// 了解更多
//				if($theme->theme_type == 3 && !empty($theme->landmark_key_str) ){
//					$temp_landmark_key = explode(",",$theme->landmark_key_str);
//					$theme->main_landmark_key = $temp_landmark_key[0];
//				}
				
				$this->assign('theme',$theme);
			}
			
			$exclude_articles = array();

			/* 伸延閱讀 */
			$related_articles = $UTheme->getRelatedArticles(UTheme::IMGSIZE_RELATED,UEvent::PAGETYPE_ID,$page_id,$theme->theme_id);
			foreach($related_articles as $k=>$v){
				// user follow
				if($isLogin){
					$related_articles[$k]->isFollowed = $this->user->isFollowed($v->pagetype_id, $v->page_id);
				}
				
				if($related_articles[$k]->author_detail!=null){
					if($related_articles[$k]->author_detail->photo!=null){
						$related_articles[$k]->author_detail->photo =  UAPP_HOST . UAPP_BASE_URL . '/cms/images/author/120x120/'.$related_articles[$k]->author_detail->photo;
					}else{
						$related_articles[$k]->author_detail->photo =  UAPP_HOST . UAPP_BASE_URL . '/media/images/global/profile.png';
					}
				}
				
				//$related_articles[$k]->update_date=formatDate($related_articles[$k]->update_date);
				$related_articles[$k]->publish_date=formatDate($related_articles[$k]->publish_date);
				
				$exclude_articles[] = $v->page_id;
			}
			$this->assign('related_articles',$related_articles);

			/* 相關主題 */
			$related_themes = $UTheme->getRelatedThemesByArticle(UEvent::PAGETYPE_ID,$page_id,$theme->theme_id);
			foreach($related_themes as $k=>$v){				
				if($v->cover_photo!=null){
					$related_themes[$k]->cover_photo = $UTheme->getImgPath('cover_small'). str_replace(' ', '%20',$v->cover_photo);
				}else{
					$related_themes[$k]->cover_photo = $UTheme->getDefaultPhoto('300x300');
				}
				// user follow
				if($isLogin){
					$related_themes[$k]->isFollowed = $this->user->isFollowed(UTheme::PAGETYPE_ID, $v->theme_id);
				}
			}
			$this->assign('related_themes',$related_themes);
			
			
			
		}
		
		//熱門標籤
		$tagModel = new UTag();
        $hotTags = $tagModel->getHotTags();
        $this->assign('pageRightHotTags', $hotTags);
		$this->assign('pageRightHotTags_track', 'Detail Page Links');
  
		$locationModel = new ULocation();
		$userLocation = $locationModel->getUserLocation();
		$this->assign('userLocation', $userLocation);
  
        //Latest Spotevents
		$contentModel = new UContent();
        $latestSpotevents = $contentModel->getLatestSpotevents(UModel::IMGSIZE_RANKING,$userLocation->area_key);
        $this->assign('pageRightLatestSpotevents', $latestSpotevents);
		$this->assign('pageRightLatestSpotevents_track', 'Detail Page Links');
		
		
		// get user comment 
		$filter = array(
					'id' => $page_id,
					'pagetype_id' => UEvent::PAGETYPE_ID,
					'page' => 1,
					'limit' => PHP_INT_MAX,
				);
		$user_comments['content'] = $this->user->getUserReview($filter);
		foreach($user_comments['content'] as &$user_comment){
			if(!$user_comment->avator){
				$user_comment->avator = $this->user->getDefaultAvatarPhoto();
			}
		}
		$user_comments['paget_id'] = $page_id;
		$user_comments['pagetype_id'] = UEvent::PAGETYPE_ID;
		$this->assign('user_comments',$user_comments);
		
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
		foreach($tags as $k=>$v){
			$tagname[]=$v->tag_name;
		} 
		
		/* 相關文章 */
		if($tags){
			$related_tags_articles = $contentModel->getRelatedTagsArticles(UTheme::IMGSIZE_RELATED,UEvent::PAGETYPE_ID,$page_id,6,$exclude_articles);
			foreach($related_tags_articles as $k=>$v){
				// user follow
				if($isLogin){
					$related_tags_articles[$k]->isFollowed = $this->user->isFollowed($v->pagetype_id, $v->page_id);
				}

				if($related_tags_articles[$k]->author_detail!=null){
					if($related_tags_articles[$k]->author_detail->photo!=null){
						$related_tags_articles[$k]->author_detail->photo =  UAPP_HOST . UAPP_BASE_URL . '/cms/images/author/120x120/'.$related_tags_articles[$k]->author_detail->photo;
					}else{
						$related_tags_articles[$k]->author_detail->photo =  UAPP_HOST . UAPP_BASE_URL . '/media/images/global/profile.png';
					}
				}

				//$related_tags_articles[$k]->update_date=formatDate($related_tags_articles[$k]->update_date);
				$related_tags_articles[$k]->publish_date=formatDate($related_tags_articles[$k]->publish_date);
			}
		}
		$this->assign('related_tags_articles',$related_tags_articles);
		
		/* Followed */
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
		
		$shareUrl = $this->createUrl('/activity/detail', array('id' => url_encrypt($page_id), 'utm_medium' => 'share'));
		//$share_button = share_button($items->title,$shareUrl);
		$share_button = share_with_bookmark_buttons(array('title'=>$items->title,'url'=>$shareUrl,'pageId'=>$page_id,'pageType'=>UEvent::PAGETYPE_ID,'isLogin'=>$isLogin,'isFollowed'=>$isFollowed));
		$this->assign('share_button',$share_button);
		
		$comment_box = facebook_comment_box($share_url,655);
		$this->assign('comment_box',$comment_box);
		
		$fb_like_box = facebook_like_box();
		$this->assign('fb_like_box',$fb_like_box);
		
		
		// rectangle banner
		/* $banner_300_250 = getAdZone(2570,300,250,'a9e7d3be');
		$this->assign("banner_300_250",$banner_300_250);
		$banner_300_100 = getAdZone(2571,300,100,'aa325ddf');
		$this->assign("banner_300_100",$banner_300_100); */
		
		//take UL log
		$location=  (array) $UEvent->getLocationPairs($page_id);
		writeULLog('活動',$items->main_cat.'|'.$items->main_subcat,$page_id,$items->title,join('|',$tagname),join('|',$location));
		
		//Ad Banner
		$special = uGetAdItem('div-gpt-ad-1472555377034-0'.ADV_BANNER_TIMESTAMP,'style="height:1px;width:1px;"');
		$fixed1 = uGetAdItem('div-gpt-ad-1472555377034-1'.ADV_BANNER_TIMESTAMP);
		$fixed2 = uGetAdItem('div-gpt-ad-1472555377034-2'.ADV_BANNER_TIMESTAMP);
		$fixed3 = uGetAdItem('div-gpt-ad-1472555377034-3'.ADV_BANNER_TIMESTAMP);
		$skyscraper1 = uGetAdItem('div-gpt-ad-1472555377034-4'.ADV_BANNER_TIMESTAMP);
		$skyscraper2 = uGetAdItem('div-gpt-ad-1472555377034-5'.ADV_BANNER_TIMESTAMP);

		$this->assign("specialBanner",$special);
		$this->assign("topBanner",$fixed1);
		$this->assign("largeRectangle1Banner",$fixed2);
		$this->assign("largeRectangle2Banner",$fixed3);
		$this->assign("skyscraperLeftBanner",$skyscraper1);
		$this->assign("skyscraperRightBanner",$skyscraper2);
		
		$infiniteBanners = array('div_gpt_ad_1472555377034_1'.ADV_BANNER_TIMESTAMP2,
									'div_gpt_ad_1472555377034_2'.ADV_BANNER_TIMESTAMP2,
									'div_gpt_ad_1472555377034_3'.ADV_BANNER_TIMESTAMP2);
		
		$this->assign("infiniteBanners",$infiniteBanners);
		
//		 $this->addJs('js/global/jquery-1.8.2.min.js');
		$this->addJs('js/global/jquery.form.js');
		
//		$this->addJs('js/global/datepicker.js');
//		$this->addCss('css/global/uhk-datepicker.css');
//		$this->addCss('css/uhk-activities-details.css');
		$this->addCss('css/hk-article-detail.css?v=1');
		$this->addCss('css/global/common_ui.css');
		
		
		$this->addCss('css/global/hk-thickbox-3.1.css');
//		$this->addJs('js/global/uhk-thickbox-3.1.js');
		
		// get GA dimension
		$this->metaOptions['gaDimension'] = $contentModel->getGaPageviewDimension(UEvent::PAGETYPE_ID, $page_id);
		
		
		$this->assign('encrypted_page_id',url_encrypt($page_id));
		//debug($page_id);
        $is_infinite_scroll = isset($_REQUEST['scroll']) ? (int) $_REQUEST['scroll'] : 0;
		$this->assign("is_infinite_scroll",$is_infinite_scroll);
		
		$exclude_ids = isset($_GET['exclude'])?$_GET['exclude']:null;
		if($exclude_ids)
		$exclude_ids_arr = explode(',',$exclude_ids);
		if(count($exclude_ids_arr)<9){
			$exclude_ids_arr[] = $page_id;
			$exclude_ids = implode(',',$exclude_ids_arr);
			$next_id = $UEvent->getNextEventId($exclude_ids_arr);
		 	//$next_id = 108105;
			if($next_id)
				$this->assign('url_next',$this->createUrl('/activity/detail',array('id'=>$next_id)).'?scroll=1&exclude='.$exclude_ids);
		}
		
		if($is_infinite_scroll){
			$this->view='/activity/detail/article';
			$this->layout='blankHtml';
		}else{
			$this->layout = 'responsive';
		}
		
		$this->display();
		
	
	
	}
	function actionShare(){
        $id = isset($_REQUEST['id']) ? url_decrypt($_REQUEST['id']) : 0;
        $preview = isset($_REQUEST['preview']) ? (int) $_REQUEST['preview'] : 0;

		if(isset($this->fm->urlParams[0])&&count($this->fm->urlParams)){
			$id = url_decrypt($this->fm->urlParams[0]);

			if(!is_numeric($id) && !($id>0)){
				$alias = urldecode($this->fm->urlParams[0]);
			}
		}
		if(!is_numeric($id) && !($id>0) && !$alias) {
			$this->redirect("/error");
        }

		
        $utm_campaign = 'activity_'.url_encrypt($id);
		$utm_source = 'uhk';
		
        $model = new UEvent();
		
		$event = $model->findByPk($id);
		
		$isLogin = $this->user->isLogin();
		$isFollowed = 0;
		
		if($isLogin){
			$isFollowed = $this->user->isFollowed(UEvent::PAGETYPE_ID, $id);
		}
		
		//social media share
		$shareUrl = $this->createUrl('/event/detail', array('id' => url_encrypt($id), 'utm_medium' => 'share'));
		$share_button = share_with_bookmark_buttons(array('title'=>$event->title,'url'=>$shareUrl,'pageId'=>$id,'pageType'=>UEvent::PAGETYPE_ID,'isLogin'=>$isLogin,'isFollowed'=>$isFollowed));


		//$output = new stdClass();
		$obj->shares=$share_button;
		
		$output =json_encode($obj);
		echo $output;
	}
	function actionRating(){
        $id = isset($_REQUEST['id']) ? url_decrypt($_REQUEST['id']) : 0;
        $preview = isset($_REQUEST['preview']) ? (int) $_REQUEST['preview'] : 0;

		if(isset($this->fm->urlParams[0])&&count($this->fm->urlParams)){
			$id = url_decrypt($this->fm->urlParams[0]);

			if(!is_numeric($id) && !($id>0)){
				$alias = urldecode($this->fm->urlParams[0]);
			}
		}
		if(!is_numeric($id) && !($id>0) && !$alias) {
			$this->redirect("/error");
        }
		
        $model = new UEvent();
		
		$event = $model->findByPk($id);
		
		$this->assign("event",$event);
		
		$this->view='/activity/detail/rating';
		$this->layout='blankHtml';
		
		$obj->rating=$this->display(true);
		
		$output =json_encode($obj);
		echo $output;
	}
}
