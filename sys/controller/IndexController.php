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
		
		$isLogin = $this->user->isLogin();
		$this->assign('isLogin',$isLogin);
		
		$contentModel = new UContent();
		
		// custom index latest
		$customLatestArticlesID = $contentModel->getLatestNew();
		
		//最新文章
		$options = array(
			'include_page_idArray' => $customLatestArticlesID,
			'pagetypeArray' => array(UEvent::PAGETYPE_ID,UTopic::PAGETYPE_ID),
			'imgsizeArray' => array(UMODEL::IMGSIZE_RELATED),
			'is_frontpage' => 1,
			'include_priority' => 1,
			'_limit' => 8,
		);
		$latestArticles = $contentModel->getLatest($options);
		$this->assign('latestArticles', $latestArticles);
		
		// custom index latest
		if($customLatestArticlesID){
			$count = count($latestArticles);
			$customLatestArticles = array();
			
			foreach($latestArticles as $k => $article){
				$ad_rank = array_search ($article->page_id, $customLatestArticlesID);
				if($ad_rank !== false){
					$customLatestArticles[$ad_rank] = $article;
					unset($latestArticles[$k]);
				}
			}
			for($i=0; $i<$count; $i++){
				if(empty($customLatestArticles[$i])){
					$customLatestArticles[$i] = array_shift($latestArticles);
				}
			}
			ksort($customLatestArticles);
			$this->assign('latestArticles', $customLatestArticles);
		}
		
		//玩樂情報
		$options = array(
			'imgsizeArray' => array(UMODEL::IMGSIZE_RELATED,UMODEL::IMGSIZE_RANKING),
			'main_cat_id' => 3,
			'is_frontpage' => 1,
			'period' => 'hits_past_week',
			'_limit' => 4,
		);
		$playLatestArticles = $contentModel->getLatest($options);
		$this->assign('playLatestArticles', $playLatestArticles);
		
		//購物情報
		$options = array(
			'imgsizeArray' => array(UMODEL::IMGSIZE_RELATED,UMODEL::IMGSIZE_RANKING),
			'main_cat_id' => 2,
			'is_frontpage' => 1,
			'period' => 'hits_past_week',
			'_limit' => 4,
		);
		$buyLatestArticles = $contentModel->getLatest($options);
		$this->assign('buyLatestArticles', $buyLatestArticles);
		
		//飲食情報
		$options = array(
			'imgsizeArray' => array(UMODEL::IMGSIZE_RELATED,UMODEL::IMGSIZE_RANKING),
			'main_cat_id' => 1,
			'is_frontpage' => 1,
			'period' => 'hits_past_week',
			'_limit' => 4,
		);
		$eatLatestArticles = $contentModel->getLatest($options);
		$this->assign('eatLatestArticles', $eatLatestArticles);
		
		//最新主題
		$UTheme=new UTheme();
		$latestThemes = $UTheme->getThemeList(0,2);
		$this->assign('latestThemes',$latestThemes);
		
		//熱門文章
		$content_ids = $contentModel->getMostViewedArticles(array(
			'is_frontpage' => 0,
			'limit' => 4,
			'period' => 'hits_past_week'
		));
		$hotArticles = $contentModel->getArticleCardValues($content_ids);
		foreach($hotArticles as $k=>$v){
			if ($v->pagetype_id == 3) {
                $cover = UTour::getCoverImg($v->page_id);
                $cover_url = UTour::getCoverPath2(UModel::IMGSIZE_RELATED) . $cover;
				
				// check 480x270
				$cover_url_480x270 = UTour::getCoverPath2(UModel::IMGSIZE_RELATED_480_270) . $cover;
				$cover_photo_file = str_replace(UAPP_HOST.UAPP_BASE_URL.'/','',$cover_url_480x270);
				if(file_exists($cover_photo_file)){
//					debug('480x270 tour_id: '.$v->page_id,0);
					$cover_url = $cover_url_480x270;
				}
            } else {
                $cover = $v->cover_photo;
                $cover_url = UContent::getImgPath($v->pagetype_id, UModel::IMGSIZE_RELATED) . $cover;
				
				// check 480x270
				$cover_url_480x270 = UContent::getImgPath($v->pagetype_id, UModel::IMGSIZE_RELATED_480_270) . $cover;
				$cover_photo_file = str_replace(UAPP_HOST.UAPP_BASE_URL.'/','',$cover_url_480x270);
				if(file_exists($cover_photo_file)){
//					debug('480x270 id: '.$v->page_id,0);
					$cover_url = $cover_url_480x270;
				}
            }
			$hotArticles[$k]->cover = $cover ? $cover_url : UTheme::getDefaultPhoto(CST_IMGSIZE_LISTING);
		}
		$this->assign('hotArticles',$hotArticles);
		
		//Latest Tour
		$options = array(
			'pagetypeArray' => array(UTour::PAGETYPE_ID),
			'imgsizeArray' => array(UTheme::IMGSIZE_LARGE),
			'is_frontpage' => 1,
			'_limit' => 4,
		);
		$latestTour = $contentModel->getLatest($options);
		foreach($latestTour as $k=>$v){
			$cover = UTour::getCoverPhotoByID($v->page_id);
			$cover = $cover->index_cover_photo;
			$latestTour[$k]->cover =($cover!=null)?array(UAPP_HOST . UAPP_BASE_URL . '/cms/images/tour/index_cover/870x460/'.$cover):UAPP_MEDIA_URL.'/images/global/default300x300.jpg';;
		}
		$this->assign('latestTour', $latestTour);
		
		$mostSearchKeywords = USearch::getSuggestionWithoutKeyword(3);
		$this->assign('mostSearchKeywords', $mostSearchKeywords);
		
		
		$campaigns = UCampaign::getCampaigns(array(
            'offset' => 0,
            'limit' => 4
        ));

        $currentTime = time();
		
        foreach ($campaigns as $k=>$v) {
            $campaigns[$k]->cover_photo = UCampaign::getImgPath() . $v->cover_image;
            $campaigns[$k]->URL = UCampaign::getURL($v->campaign_id,$v->campaign_name);
            if ($currentTime >= strtotime($v->start_date . ' 00:00:00') && $currentTime <= strtotime($v->end_date . ' 23:59:59')) {
                $campaigns[$k]->heading = '活動招募';
                $campaigns[$k]->holding = false;
            } else if($currentTime>=strtotime($v->result_start_date . ' 00:00:00') ){
                $campaigns[$k]->heading = '得獎結果';
                $campaigns[$k]->holding = true;
            } else if ($currentTime >= strtotime($v->end_date . ' 23:59:59') && $currentTime<=strtotime($v->result_start_date . ' 00:00:00')){
				$campaigns[$k]->heading = '會員專享';
                $campaigns[$k]->holding = true;
			}
        }
		$this->assign('campaigns', $campaigns);
		//debug($campaigns);
		
		// filter option
		$filter = array(
					'activity' => $filter_activity,
					'topic' => $filter_topic,
				);
		$this->assign('filter',$filter);
		
		

		
        $this->metaOptions['canonicalUrl'] = UAPP_HOST . UAPP_BASE_URL;
		
		//Ad Banner
		$fixed1 = uGetAdItem('div-gpt-ad-1472555235089-1');
		$fixed2 = uGetAdItem('div-gpt-ad-1472555235089-2');
		$fixed3 = uGetAdItem('div-gpt-ad-1472555235089-3');

		$this->assign("topBanner",$fixed1);
		$this->assign("largeRectangle1Banner",$fixed2);
		$this->assign("largeRectangle2Banner",$fixed3);

		$this->addCss('library/slick/css/slick.css');
		$this->addCss('library/slick/css/slick-theme.css');
		$this->addCss('css/hk-index.css');
		$this->addCss('css/global/hk-card-update.css');
		
		$this->addJs('library/slick/js/slick.js');
		$this->addJs('js/index-js.js');
		$this->layout = 'responsive2';
		
		$this->display();
	}
}