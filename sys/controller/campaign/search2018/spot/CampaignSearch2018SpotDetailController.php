<?php

defined('UFM_RUN') or die('No direct script access allowed.');


class CampaignSearch2018SpotDetailController extends UBaseArticleDetailController
{
    var $model;

    function __construct($fm)
    {
        parent::__construct($fm);
        $this->model = new USpot();
    }
	
	protected function getPageType()
    {
        return USpot::PAGETYPE_ID;
    }
	
	protected function getPageLayout($infinite_scroll)
    {
        return $infinite_scroll?'blankHtml':'responsiveHtml';
    }
	
	protected function getNextArticle($exclude_ids_arr)
    {
        return $this->model->getNextSpotId($exclude_ids_arr);
    }

    protected function getPageUrlPath()
    {
        return '/campaign/search2018/spot/detail';
    }
	
	protected function getULLogChannel()
    {
        return '地方';
    }

    protected function getCookieSetting()
    {
		$cookieSetting = new stdClass();
		$cookieSetting->cookiesName = 'spotViewedHistory';
		$cookieSetting->historyLimit = 5;
		
        return $cookieSetting;
    }

    protected function getArticle($page_id, $alias)
    {
		if($alias){
			$article = $this->model->findByAlias($alias);
			$page_id = $article->spot_id;
		}else{
			$article = $this->model->getSpotForDetail($page_id);
		}
		
		$article->title = $article->spot_name;

		//lazy loading
		$_num_of_loading_photo = 3;
		$content = uAddSignature($article->content);

        //get the photos of this spot
        $photos = $this->model->getPhotos($page_id);
        if (!empty($photos)) {
			$content = $article->addPageDivisionToContent($content, $_num_of_loading_photo);
            $article->content = $article->addSwipePhotoToContent($content, $page_id, USpot::getImgPath(USpot::IMGSIZE_DETAIL), USpot::getImgPath(USpot::IMGSIZE_LARGE), $photos,1);
        }
		$article->content = uConvertContentTags($article->content, array('youtubeWidth'=>600,'youtubeHeight'=>400));

        $article = $this->getArticleDetail($page_id, $article);
		
        return $article;

    }
    
    private function getArticleDetail($page_id, $article)
	{
		$article->pano = '';
        if (isset($article->panorama_photo) && !empty($article->panorama_photo)) {
            $article->pano = USpot::getImgPath(USpot::IMGSIZE_PANO) . $article->panorama_photo;
        }

		$article->publish_date_time = $article->publish_date;
		$article->publish_date = formatDate($article->publish_date);
		
		/* Cover Photo */
		$article->cover=$this->model->getCoverPhoto($page_id);
		
		$photosResultSet = $this->model->getFBPhotos($page_id);
        $fb_images = array();
        foreach ($photosResultSet as $photosResult) {
			if($photosResult->is_fb_cover==1) $photosOG[] = USpot::getImgPath(UEvent::IMGSIZE_LARGE) . $photosResult->photo_name;
            $fb_images[] = USpot::getImgPath(USpot::IMGSIZE_LARGE) . $photosResult->photo_name;
        }
		if(sizeOf($photosOG)!=0){
			$fb_images = $photosOG;
		}
		$article->fb_images = $fb_images;
		
		/* Main Categories */
		$article->main_cat=($article->main_cat_id!=null)?UCategory::getCatInfoById($article->main_cat_id)->cat_name:'';
		$article->main_subcat=($article->main_subcat_id!=null)?UCategory::getCatInfoById($article->main_subcat_id)->cat_name:'';
		
		$article->regions = $this->model->getArticleRegion($page_id);
		
		$article->tags = $this->model->getSpotTags($page_id);
		
		//get the extra details for this spot
        $details = $this->model->getDetails($page_id);
        $this->assign('details', $details);

        //get the spot link
        $links = $this->model->getSpotLink($page_id);
        $this->assign('links', $links);
		
		//take UL log
		$article->LogLocation =  (array) $this->model->getLocationPairs($page_id);
		
		$article->ArticleURL = USpot::getURL($page_id, $article->title);
		
		return $article;
    }
	
	protected function prepareRes()
    {
		$this->addCss('css/hk-article.css');
		$imgDir = UAPP_BASE_URL.'/sys/view/campaign/search2018/';
		array_push($this->jsFiles, $imgDir.'js/hk-article-detail.js?v='.CST_JS_CACHE_BUST);
    }

}
