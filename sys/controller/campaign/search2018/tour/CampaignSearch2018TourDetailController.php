<?php

defined('UFM_RUN') or die('No direct script access allowed.');


class CampaignSearch2018TourDetailController extends UBaseArticleDetailController
{
    var $model;

    function __construct($fm)
    {
        parent::__construct($fm);
        $this->model = new UTour();
    }
	
	protected function getPageType()
    {
        return UTour::PAGETYPE_ID;
    }
	
	protected function getPageLayout($infinite_scroll)
    {
        return $infinite_scroll?'blankHtml':'responsiveHtml';
    }
	
	protected function getNextArticle($exclude_ids_arr)
    {
        return $this->model->getNextTourId($exclude_ids_arr);
    }

    protected function getPageUrlPath()
    {
        return '/campaign/search2018/tour/detail';
    }
	
	protected function getULLogChannel()
    {
        return '周圍遊';
    }

    protected function getCookieSetting()
    {
		$cookieSetting = new stdClass();
		$cookieSetting->cookiesName = 'tourViewedHistory';
		$cookieSetting->historyLimit = 5;
		
        return $cookieSetting;
    }

    protected function getArticle($page_id, $alias)
    {
		
		if($alias){
			$article = $this->model->findByAlias($alias);
			$page_id = $article->tour_id;
		}else{
			$article = $this->model->findByPk($page_id);
		}
		
		$infoPhotos = $this->model->getPhoto($page_id, null);
		if (!empty($infoPhotos)) {
			$article->content = $this->model->addSwipePhotoToContent($article->content, $page_id, UTour::getImgPath(UTour::IMGSIZE_DETAIL), UTour::getImgPath(UTour::IMGSIZE_LARGE), $infoPhotos,1);
		}
		$article->content = uConvertContentTags($article->content, array('youtubeWidth'=>600,'youtubeHeight'=>400));
		
		$article = $this->getArticleDetail($page_id, $article);
		
        return $article;

    }
    
    private function getArticleDetail($page_id, $article)
	{
		$article->categories = $this->model->getCategoriesByID($page_id);
		$article->districts = $this->model->getDistrictsByID($page_id);
		$article->tags = $this->model->getTagsByID($page_id);

		/* Cover Photo */
		$article->cover=$this->model->getCoverPhotoByID($page_id);

		/* Published time */
		$article->publish_date_time = $article->publish_date;
		$article->publish_date = formatDate($article->publish_date);

		/* Main Categories */
		$article->main_cat=($article->main_cat_id!=null)?UCategory::getCatInfoById($article->main_cat_id)->cat_name:'';
		$article->main_subcat=($article->main_subcat_id!=null)?UCategory::getCatInfoById($article->main_subcat_id)->cat_name:'';


		$article->travel_note = uConvertContentTags($article->travel_note, array('youtubeWidth'=>600,'youtubeHeight'=>400));
		$article->travel_note = uAddSignature($article->travel_note);

		
		$fb_images = array();
		$ogImagesArray= $this->model->getOGImages($page_id, 5);
		foreach($ogImagesArray as $key=>$value) 
		{
			$fb_images[]=UTour::getImgPath(UTour::IMGSIZE_LARGE).$value->cover_photo;
		}

		$cover = $this->model->getCoverPhotoByID($page_id);
		if($cover->fb_cover_photo) $fb[] = $this->model->getFbCoverPath().$cover->fb_cover_photo;
		if(sizeOf($fb[0])!=0){
			$fb_images = $fb;
		}
		$article->fb_images = $fb_images;

		//Sites
		$sites = $this->model->getSitesByID($page_id);
		foreach ($sites as $siteIndex => $site) {
			$photos = $this->model->getPhoto($page_id, $site->site_id);
			if (!empty($photos)) {
				$site->content = $this->model->addSwipePhotoToContent($site->content, $page_id.'-'.$siteIndex, UTour::getImgPath(UTour::IMGSIZE_DETAIL), UTour::getImgPath(UTour::IMGSIZE_LARGE), $photos);
				foreach ($photos as $photo) {
					$photo->cover_photo_large = UTour::getImgPath(UTour::IMGSIZE_LARGE) . $photo->photo_name;
					$photo->cover_photo = UTour::getImgPath(UTour::IMGSIZE_RELATED) . $photo->photo_name;
					
					// check 480x270
					$cover_url_480x270 = UTour::getImgPath(UModel::IMGSIZE_RELATED_480_270) . $photo->photo_name;
					$cover_photo_file = str_replace(UAPP_HOST.UAPP_BASE_URL.'/','',$cover_url_480x270);
					if(file_exists($cover_photo_file)){
						$photo->cover_photo = $cover_url_480x270;
					}
				}
				$site->photos = $photos;
			}
		}
		$siteList = $this->getSiteSummary($sites);
		foreach ($siteList as $siteIndex => $site) {
			if (!empty($site->URL)) {
				$site->URL = $this->model->appendUtmTrack($site->URL, $page_id, UTour::UTM_MEDIA_SITE, $siteIndex + 1);
			}
		}

		$this->assign("sites", $siteList);
		
		//take UL log
		$article->LogLocation =  (array) $this->model->getLocationPairs($page_id);
		
		$article->ArticleURL = $this->model->getURL($page_id,$article->title);
		
		return $article;
	
    }
	
    private function getSiteSummary($relatedMappings) 
	{
        foreach ($relatedMappings as &$site) {
            if (!empty($site->pagetype_id) && !empty($site->page_id)) {
                switch ($site->pagetype_id) {
                    case UEvent::PAGETYPE_ID:
                        $model = new UEvent();
                        break;
					case UTopic::PAGETYPE_ID:
                        $model = new UTopic();
                        break;
                    case UTour::PAGETYPE_ID:
                        $model = new UTour();
                        break;
                    case USpot::PAGETYPE_ID:
                        $model = new USpot();
                        break;
                }
                $siteInfo = $this->model->getInfo($site->page_id);
				$site->title = $siteInfo->title;
                $site->URL = $siteInfo->URL;
				
                $site->address = $siteInfo->address;
				if($siteInfo->price_type_id==1){
					$site->price = $siteInfo->price;
				}elseif($siteInfo->price_type_id==2){
					$site->price = $siteInfo->type_name;
				}
				
				$site->dates = $siteInfo->dates;
				
            }
        }
        return $relatedMappings;
    }
	
	protected function prepareRes()
    {
		$this->addCss('css/hk-article.css');
		$imgDir = UAPP_BASE_URL.'/sys/view/campaign/search2018/';
		array_push($this->jsFiles, $imgDir.'js/hk-article-detail.js?v='.CST_JS_CACHE_BUST);
		
		$this->addCss('library/slick/css/slick.css');
		$this->addCss('library/slick/css/slick-theme.css');
		$this->addJs('library/slick/js/slick.js');
    }

}
