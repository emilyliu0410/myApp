<?php
defined( 'UFM_RUN' ) or die( 'No direct script access allowed.' );

class IndexController extends UController
{
//    public $layout = 'index';

	function actionIndex()
	{
        $filter_activity = isset($_REQUEST['filter_activity']) ? (int) $_REQUEST['filter_activity'] : 0;		
		$filter_topic = isset($_REQUEST['filter_topic']) ? (int) $_REQUEST['filter_topic'] : 0;
		
		//get cover photo file
		/* $coverPhotoUrl = UAPP_MEDIA_URL.'/images/global/header-img.jpg';
		$model = new UHomeCover();
		$cover = $model->findLatestOne();
		if($cover)
		{
			$model = new UHomeCoverPhoto();
			$coverPhoto = $model->randOneByCoverId($cover->cover_id);
			$url = $coverPhoto ? '/cms/images/cover_photo/'.$coverPhoto->photo_name : false;
			if($url && is_file(UAPP_BASE_DIR.$url)) $coverPhotoUrl = UAPP_BASE_URL.$url ;
		}
		$this->assign('coverPhotoUrl',$coverPhotoUrl);
		//debug($coverPhoto->photo_name,0);
		$this->assign('isFirstTimeVisit',$this->isCoverPhotosVisited($coverPhotoUrl)); */
		
		//get slider photos
		
		if(APP_ISMALLKING){
			$this->redirect301('/mall/index');
		}
		

		$utm_code=array(	'utm_source'=>'uhk',
							'utm_campaign'=>'uhk-home'
						);
		
		$sliderPhotos =array();
		$model = new UHomeSlider();
		$slider = $model->findLatestOne();
		//debug($slider);
		if($slider)
		{
			$model = new UHomeSliderPhoto();
			$sliderPhotos = $model->getBySliderId($slider->slider_id);

			foreach($sliderPhotos as $k=>$v){
				/* $utm_code=array('utm_source' 	=> $utm_setting['utm_source'],
								'utm_campaign' 	=> $utm_setting['utm_campaign'],
								'utm_medium'	=> null,
								'utm_content'	=> $utm_setting['utm_content']['slider'].$i++); */
				$utm_code['utm_medium'] = null;
				$utm_code['utm_content'] = 'slider'. ($k+1);
				$sliderPhotos[$k]->url = assignUtmCode($v->url,$utm_code);
			}
		}
		$this->assign('sliderPhotos',$sliderPhotos);
		
		
		$locationModel = new ULocation();
		$userLocation = $locationModel->getUserLocation();
		$this->assign('userLocation', $userLocation);
		
		$isLogin = $this->user->isLogin();
		$this->assign('isLogin',$isLogin);
		

		//最新文章
			$contentModel = new UContent();
			//全部
			$this->assign('latestSpotevents', $this->latestSpot('',5,$isLogin,$this->user->user_id));
			//食
			$this->assign('eat', $this->latestSpot(1,5,$isLogin,$this->user->user_id));
			//買
			$this->assign('buy', $this->latestSpot(2,5,$isLogin,$this->user->user_id));
			//玩
			$this->assign('play', $this->latestSpot(3,5,$isLogin,$this->user->user_id));
		
		
		//Latest Spotevents for filter
		/* $filter_list = array(1,2,3,4);
		$filter_type = array();
		foreach($filter_list as $k=>$v){
			$filter_type[$k]->main_cat_id = $v;
			$filter_type[$k]->main_cat = UCategory::getCatInfoById($v)->cat_name;
			
			$filter_type[$k]->empty = false;
		}
		// debug($filter_type,0);
		$this->assign('latestSpotevents_filter', $filter_type);
		$this->assign('latestTopic_filter', $filter_type); */
		
		//Latest Tour
		$options = array(
			'pagetypeArray' => array(UTour::PAGETYPE_ID),
			'imgsizeArray' => array(UTheme::IMGSIZE_LARGE),
			'area_key' => $userLocation->area_key,
			'is_frontpage' => 1,
			'_limit' => 4,
		);
		$latestTour = $contentModel->getLatest($options);
		foreach($latestTour as $k=>$v){
			$latestTour[$k]->publish_date = formatDate($latestTour[$k]->publish_date);
			
			// user follow
			if($isLogin){
				$latestTour[$k]->isFollowed = $this->user->isFollowed($v->pagetype_id, $v->page_id);
			}
						
			// location tag
			if($latestTour[$k]->location_area>0){
				$latestTour[$k]->location_tag_url = $this->createUrl('/search/index',array('location'=>$v->location_area));
				$latestTour[$k]->location_tag_str = $latestTour[$k]->location_area_str;
			}else if($latestTour[$k]->location_district>0){
				$latestTour[$k]->location_tag_url = $this->createUrl('/search/index',array('location'=>$v->location_district));
				$latestTour[$k]->location_tag_str = $latestTour[$k]->location_district_str;
			}else{
				$latestTour[$k]->location_tag_url = $this->createUrl('/search/index',array('type'=>'3'));
				$latestTour[$k]->location_tag_str = '全港';
			}
			
			$latestTour[$k]->main_subcat_url = $this->createUrl('/search/index',array('cat'=>$v->main_subcat_id));
            
			if($latestTour[$k]->author_detail!=null){
				if($latestTour[$k]->author_detail->photo!=null){
					$latestTour[$k]->author_detail->photo =  UAPP_HOST . UAPP_BASE_URL . '/cms/images/author/120x120/'.$latestTour[$k]->author_detail->photo;
				}else{
					$latestTour[$k]->author_detail->photo =  UAPP_HOST . UAPP_BASE_URL . '/media/images/global/profile.png';
				}
			}
			
			$cover = UTour::getCoverPhotoByID($v->page_id);
			$cover = $cover->index_cover_photo;
			$latestTour[$k]->cover =($cover!=null)?array(UAPP_HOST . UAPP_BASE_URL . '/cms/images/tour/index_cover/870x460/'.$cover):UAPP_MEDIA_URL.'/images/global/default300x300.jpg';;

			/* foreach($latestTour[$k]->cover as &$cover){
				if(empty($cover)){
					$cover = UAPP_MEDIA_URL.'/images/global/default300x300.jpg';
				}
			} */
		}
		$this->assign('latestTour', $latestTour);
		
		
		
		//Latest Topic
		$options = array(
			'pagetypeArray' => array(UTopic::PAGETYPE_ID),
			'imgsizeArray' => array(UTheme::IMGSIZE_RELATED),
			'main_cat_id' => $filter_topic,
			'_limit' =>5,
		);
		$latestTopic = $contentModel->getLatest($options);
		foreach($latestTopic as $k=>$v){
			$latestTopic[$k]->publish_date = formatDate($latestTopic[$k]->publish_date);
			
			// user follow
			if($isLogin){
				$latestTopic[$k]->isFollowed = $this->user->isFollowed($v->pagetype_id, $v->page_id);
			}
			
			// location tag
			if($latestTopic[$k]->location_area>0){
				$latestTopic[$k]->location_tag_url = $this->createUrl('/search/index',array('location'=>$v->location_area));
				$latestTopic[$k]->location_tag_str = $latestTopic[$k]->location_area_str;
			}else if($latestTopic[$k]->location_district>0){
				$latestTopic[$k]->location_tag_url = $this->createUrl('/search/index',array('location'=>$v->location_district));
				$latestTopic[$k]->location_tag_str = $latestTopic[$k]->location_district_str;
			}else{
				$latestTopic[$k]->location_tag_url = $this->createUrl('/search/index',array('type'=>'2'));
				$latestTopic[$k]->location_tag_str = '全港';
			}
			
			$latestTopic[$k]->main_subcat_url = $this->createUrl('/search/index',array('cat'=>$v->main_subcat_id));
            
			if($latestTopic[$k]->author_detail!=null){
				if($latestTopic[$k]->author_detail->photo!=null){
					$latestTopic[$k]->author_detail->photo =  UAPP_HOST . UAPP_BASE_URL . '/cms/images/author/120x120/'.$latestTopic[$k]->author_detail->photo;
				}else{	
					$latestTopic[$k]->author_detail->photo =  UAPP_HOST . UAPP_BASE_URL . '/media/images/global/profile.png';
				}
			}
			
			foreach($latestTopic[$k]->cover as &$cover){
				if(empty($cover)){
					$cover = UAPP_MEDIA_URL.'/images/default/300x200_default.jpg';
				}
			} 
		}
		$this->assign('latestTopic', $latestTopic);
		
		//latest theme
		$themeModel=new UTheme();
		
		$theme_types = $themeModel->getThemeTypes();
		$this->assign('theme_types', $theme_types);
		
		//全部
		/* $allTheme=$themeModel->searchTheme(array(
			'offset'=>0,
			'limit'=>6
        )); */
		
		$sql = 'SELECT a.theme_id, a.thumbnail_photo, a.title
				FROM tbl_theme a
				WHERE a.theme_id IN (9200,8953,8952,8602,8603,8604)
				ORDER BY FIELD(theme_id,9200,8953,8952,8602,8603,8604)
				';
		$allTheme = uDb()->findList($sql);

		$this->assign('allTheme',$allTheme);
		//活動
		/* $theme1=$themeModel->searchTheme(array(
            'theme_type' => 2,
			'offset'=>0,
			'limit'=>6
        )); */
		$sql = 'SELECT a.theme_id, a.thumbnail_photo, a.title
				FROM tbl_theme a
				WHERE a.theme_id IN (8959,9203,8768,9194,9243,9205)
				ORDER BY FIELD(theme_id,8959,9203,8768,9194,9243,9205)
				';
		$theme1 = uDb()->findList($sql);
		$this->assign('theme1',$theme1);
		//地方
		/* $theme2=$themeModel->searchTheme(array(
            'theme_type' => 3,
			'offset'=>0,
			'limit'=>6
        )); */

		$sql = 'SELECT a.theme_id, a.thumbnail_photo, a.title
				FROM tbl_theme a
				WHERE a.theme_id IN (8777,9204,9202,8784,9108,9196)
				ORDER BY FIELD(theme_id,8777,9204,9202,8784,9108,9196)
				';
		$theme2 = uDb()->findList($sql);
		$this->assign('theme2',$theme2);
		//主題
		/* $theme3=$themeModel->searchTheme(array(
            'theme_type' => 1,
			'offset'=>0,
			'limit'=>6
        )); */
		$sql = 'SELECT a.theme_id, a.thumbnail_photo, a.title
				FROM tbl_theme a
				WHERE a.theme_id IN (9258,8894,9117,85,8758,8639)
				ORDER BY FIELD(theme_id,9258,8894,9117,85,8758,8639)
				';
		$theme3 = uDb()->findList($sql);
		$this->assign('theme3',$theme3);
		
		$theme_utm_params = 'utm_source=uhk&utm_campaign=uhk-home&utm_medium=theme';
		$this->assign('theme_utm_params',$theme_utm_params);
		
		//location btn
		$this->assign('locationBtn_',getLocationButton('index'));
		
		//熱門文章
		$hot=$contentModel->getHottest(UEvent::IMGSIZE_LISTING,UEvent::HITS_PASTWEEK,5);
		$this->assign('hot',$hot);
		
		// 地區熱門地標
		$hottestLandmark = $locationModel->getHottestLandmarksInArea($userLocation->area_key);
		foreach($hottestLandmark as $k=>$v){
			if(empty($hottestLandmark[$k]->cover_photo)){
				$hottestLandmark[$k]->cover_photo = UAPP_MEDIA_URL.'/images/global/default75x75.jpg';
			}else{
				$hottestLandmark[$k]->cover_photo = UAPP_BASE_URL.'/cms/images/location/120x120/'.$hottestLandmark[$k]->cover_photo;
			}
		}
		$this->assign('hottestLandmark', $hottestLandmark);
		
		// 商場好去處
		$options = array(
			'pagetypeArray' => array(UEvent::PAGETYPE_ID),
			'exclude_page_idArray' => $latestSpotevents_id,
			'imgsizeArray' => array(UModel::IMGSIZE_RANKING),
			'area_key' => $userLocation->area_key,
			'main_subcat_id' => 50,
			'subcat_id' => array(50),
		);
		$pageRightMallKing = $contentModel->getLatest($options);
		foreach($pageRightMallKing as $k=>$v){
			foreach($pageRightMallKing[$k]->cover as &$cover){
				if(empty($cover)){
					$cover = UAPP_MEDIA_URL.'/images/global/default75x75.jpg';
				}
			}
		}
		$this->assign('pageRightMallKing', $pageRightMallKing);
		
		// filter option
		$filter = array(
					'activity' => $filter_activity,
					'topic' => $filter_topic,
				);
		$this->assign('filter',$filter);
		
		
		// 熱門標籤
		$tagModel = new UTag();
		$hotTags = $tagModel->getHotTags();
		$this->assign('pageRightHotTags', $hotTags);
		
		
		
		$fb_like_box = facebook_like_box(	$url 			= CFG_FACEBOOK_PAGE_URL,
											$width			= '300',  	// width of the box in pixel	
											$height			= '268',		// height of the box in pixel	
											$colorscheme	= 'light',
											$stream			= 'false', 	// true if show profile stream of public profile
											$show_faces		= 'true', 	// show number of sample users who like the page
											$header			= 'false', 	// true if display "find us on Facebook"
											$show_border	= 'false');
		$this->assign('fb_like_box',$fb_like_box);
		
		
        $this->metaOptions['canonicalUrl'] = UAPP_HOST . UAPP_BASE_URL;
		
		//Ad Banner
		$special = uGetAdItem('div-gpt-ad-1472555235089-0','style="height:1px;width:1px;"');
		$fixed1 = uGetAdItem('div-gpt-ad-1472555235089-1');
		$fixed2 = uGetAdItem('div-gpt-ad-1472555235089-2');
		$fixed3 = uGetAdItem('div-gpt-ad-1472555235089-3');
		$skyscraper1 = uGetAdItem('div-gpt-ad-1472555235089-4');
		$skyscraper2 = uGetAdItem('div-gpt-ad-1472555235089-5');

		$this->assign("specialBanner",$special);
		$this->assign("topBanner",$fixed1);
		$this->assign("largeRectangle1Banner",$fixed2);
		$this->assign("largeRectangle2Banner",$fixed3);
		$this->assign("skyscraperLeftBanner",$skyscraper1);
		$this->assign("skyscraperRightBanner",$skyscraper2);
		
		$this->addCss('css/swiper.min.css');
		$this->addCss('css/hk-index.min.css');
		$this->addCss('css/animate.min.css');
		$this->addCss('css/global/common_ui.min.css');
		// $this->addCss('css/hk-index.css');
		$this->layout = 'responsive';
		
		$this->display();
	}
	function latestSpot($filter_activity='',$limit=5,$isLogin,$user){
		$include_priority = (/* in_array($filter_activity,array(1,2,3))|| */$filter_activity=='')?1:0;
		$today = date('Y-m-d');
		$start_date=date('Y-m-d', strtotime('2017-09-27'));
		$end_date=date('Y-m-d', strtotime('2017-09-27'));
		if (($today >= $start_date) && ($today <= $end_date)){
			$onTopPageIDs = array(108081);
		}
		
		$startdate=date('Y-m-d', strtotime('2017-05-13'));
		$enddate=date('Y-m-d', strtotime('2017-05-15'));
		if (($today >= $startdate) && ($today <= $enddate)){
			$start_date=$startdate;
			$end_date=$enddate;
			if($today==$start_date){
			/* $onTopPageIDs = array(108211);
			if($_GET['testing'])  */
			$onTopPageIDs = array(108214,108211);
			}else
			$onTopPageIDs = array(108211);
		}
		
		//Latest Spotevents
		$contentModel = new UContent();
		$options = array(
			'pagetypeArray' => array(UEvent::PAGETYPE_ID,USpot::PAGETYPE_ID),
			'imgsizeArray' => array(UTheme::IMGSIZE_DETAIL,UTheme::IMGSIZE_RELATED),
			'main_cat_id' => $filter_activity,
			'is_frontpage' => 1,
			'area_key' => $userLocation->area_key,
			'_limit'=>$limit,
			'exclude_page_idArray'=>$onTopPageIDs,
			'include_priority'=>$include_priority
		);
		$latestSpotevents_id = array();
		$latestSpotevents = $contentModel->getLatest($options);
		// Shift first LCSD article
		if(!empty($latestSpotevents[0]->lcsd_id)){
			foreach($latestSpotevents as $k => $v) {
				if(empty($v->lcsd_id)) {
					$temp_event = $latestSpotevents[$k];
					unset($latestSpotevents[$k]);
					array_unshift($latestSpotevents, $temp_event); 
					break;
				}
			}
		}
		
		if($include_priority){
			$topContents = array();
			foreach($latestSpotevents as $k=>$v){
				if($v->is_top > 0){
					$topContents[] = $v;
					unset($latestSpotevents[$k]);
				}
			}
			if(!empty($topContents)){
				$latestSpotevents = array_values($latestSpotevents);
				foreach($topContents as $k=>$v){
					$tmpOrdering = 2 + ($k*2);   //this case start from 3
					array_splice($latestSpotevents, (int)$tmpOrdering, 0, array($v));
			   
					$latestSpotevents = array_values($latestSpotevents);
				}
			}
		}//if($_GET['testing'])debug($latestSpotevents);
		
		if(count($onTopPageIDs)&&$filter_activity==''){
			$sql = 'SELECT c.content_id, c.page_id, c.pagetype_id, c.title, c.content, 
			c.cover_photo, c.title, c.publish_date, c.update_date, c.main_cat_id, 
			c.main_subcat_id, c.main_cat, c.main_subcat FROM tbl_content c 
			WHERE c.published = 1 AND c.publish_date <= NOW() AND page_id IN('.implode(',',$onTopPageIDs).')
			ORDER BY FIELD(page_id,'.implode(',',$onTopPageIDs).');';
			$topContents = uDb()->findList($sql);
			if(!empty($topContents)){
				foreach ($topContents as $index => &$result) {
					$model=UTheme::pagetypeSwitch($result->pagetype_id);
					$detail=$model->findByPk($result->page_id);

					//get Cover
					$imgsizeArray = array(UTheme::IMGSIZE_DETAIL,UTheme::IMGSIZE_RELATED);
					$cover=UTheme::getCover($result->pagetype_id,$result->page_id);
					foreach ($imgsizeArray as $k=>$v){
						$result->cover[]=($cover!=null)?$model->getImgPath($v) . $cover :'';
					}
					// get url
					$result->page_url=$model->getURL($result->page_id,$result->title);
					
					$detail=$model->findByPk($result->page_id);
						
					//get autor information
					$result->author_id=$detail->author_id;
					$result->author=$detail->author;
					$result->author_detail=($detail->author_id!=null)?UTheme::getArticleAuthor($detail->author_id):'';

					unset($result->content_id);
				}
			}
			$latestSpotevents = array_values($latestSpotevents);
			if(!empty($topContents)){
				foreach($topContents as $k=>$v){
					//debug($start_date);
					if($today==$start_date&&$v->page_id==$onTopPageIDs[0]){
					//$tmpOrdering = 0;
					//array_splice($latestSpotevents, (int)$tmpOrdering, 0, array($v));
					$latestSpotevents[0] = $v;
					}else{
					$tmpOrdering = 4;   //this case start from 3
					array_splice($latestSpotevents, (int)$tmpOrdering, 0, array($v));
			   
					$latestSpotevents = array_values($latestSpotevents);
					
					}
				}				//if($_GET['testing'])debug($latestSpotevents);

				for($i=count($latestSpotevents)-1;$i>=5;$i--){
					unset($latestSpotevents[$i]);
				}
			}
		}
 
		foreach($latestSpotevents as $k=>$v){
			$latestSpotevents[$k]->publish_date = formatDate($latestSpotevents[$k]->publish_date);
			
			// user follow
			if($isLogin){
				// $latestSpotevents[$k]->isFollowed = $this->user->isFollowed($v->pagetype_id, $v->page_id);
				$themeModel = new UTheme();
				$latestSpotevents[$k]->isFollowed = $themeModel->followed($user, $v->page_id,$v->pagetype_id);
			}
			
			// location tag
			if($latestSpotevents[$k]->location_area>0){
				$latestSpotevents[$k]->location_tag_url = $this->createUrl('/search/index',array('location'=>$v->location_area));
				$latestSpotevents[$k]->location_tag_str = $latestSpotevents[$k]->location_area_str;
			}else if($latestSpotevents[$k]->location_district>0){
				$latestSpotevents[$k]->location_tag_url = $this->createUrl('/search/index',array('location'=>$v->location_district));
				$latestSpotevents[$k]->location_tag_str = $latestSpotevents[$k]->location_district_str;
			}else{
				$latestSpotevents[$k]->location_tag_url = $this->createUrl('/search/index',array('type'=>'1,4'));
				$latestSpotevents[$k]->location_tag_str = '全港';
			}
			
			$latestSpotevents[$k]->main_subcat_url = $this->createUrl('/search/index',array('cat'=>$v->main_subcat_id));
			
			if($latestSpotevents[$k]->author_detail!=null){
				if($latestSpotevents[$k]->author_detail->photo!=null){
					$latestSpotevents[$k]->author_detail->photo =  UAPP_HOST . UAPP_BASE_URL . '/cms/images/author/120x120/'.$latestSpotevents[$k]->author_detail->photo;
				}else{				
					$latestSpotevents[$k]->author_detail->photo =  UAPP_HOST . UAPP_BASE_URL . '/media/images/global/profile.png';
				}
			}
			
			foreach($latestSpotevents[$k]->cover as &$cover){
				if(empty($cover)){
					$cover = UAPP_MEDIA_URL.'/images/default/300x200_default.jpg';
				}
			}
			
			$latestSpotevents_id[] = $latestSpotevents[$k]->page_id;
		}
		return $latestSpotevents;
		
	}
}