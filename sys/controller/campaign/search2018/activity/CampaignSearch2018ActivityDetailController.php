<?php
defined( 'UFM_RUN' ) or die( 'No direct script access allowed.' );
require_once(dirname(__FILE__).'/../includes.php');
class CampaignSearch2018ActivityDetailController extends UBaseArticleDetailController
{
    var $model;

    function __construct($fm)
    {
        parent::__construct($fm);
        $this->model = new UEvent();
    }
	
	protected function getPageType()
    {
        return UEvent::PAGETYPE_ID;
    }
	
	protected function getPageLayout($infinite_scroll)
    {
        return $infinite_scroll?'blankHtml':'responsiveHtml';
    }
	
	protected function getNextArticle($exclude_ids_arr)
    {
        //return $this->model->getNextEvent($exclude_ids_arr);
        return $this->model->getNextEvent($exclude_ids_arr,$this->model->attributes['main_cat_id']);
    }

    protected function getPageUrlPath()
    {
        return '/campaign/search2018/activity/detail';
    }
	
	protected function getULLogChannel()
    {
        return '活動';
    }

    protected function getCookieSetting()
    {
		$cookieSetting = new stdClass();
		$cookieSetting->cookiesName = 'eventViewedHistory';
		$cookieSetting->historyLimit = 5;
		
        return $cookieSetting;
    }

    protected function getArticle($page_id, $alias)
    {
		if($alias){
			$article = $this->model->findByAlias($alias);
			$page_id = $article->event_id;
		}else{
			$article = $this->model->findByPk($page_id);
		}

		$photoRS = $this->model->getPhotos($page_id);
		
		/* Content List */
		$contentList = $this->model->getContentList($page_id);
		
		/* Default Content */
		$content_id = 0;
		$currContent = new stdClass();
		foreach($contentList as $v){
			$content_ids[] = $v->content_id;
		}
		
		/* Modify Content */
		if(isset($content_ids)){		
			// combine content
			foreach($content_ids as $v){
				$tmp_currContent = $this->model->getContentById($page_id,$v);
				$currContent->content .= $tmp_currContent->content;
			}
			
			if (!empty($photoRS)) {
				$_num_of_photo_to_div = 3;
				$currContent->content = $this->model->addPageDivisionToContent($currContent->content, $_num_of_photo_to_div);
				
				$currContent->content = $this->model->addSwipePhotoToContent($currContent->content, $page_id, UEvent::getImgPath(UEvent::IMGSIZE_DETAIL), UEvent::getImgPath(UEvent::IMGSIZE_LARGE), $photoRS, 1);
			}
			$currContent->content = uConvertContentTags($currContent->content, array('youtubeWidth'=>600,'youtubeHeight'=>400));
			$currContent->content=uAddSignature($currContent->content);
		}
		$article->content = $currContent->content;
		
		/* Facebook OG Images */
		$fb_images = array();
		$has_fb_cover = false;
        foreach ($photoRS as $v) {
			if($v->is_fb_cover==1){
				if(isset($fb_images) && !$has_fb_cover){
					$fb_images = array();
				}
				$fb_images[] = UEvent::getImgPath(UEvent::IMGSIZE_LARGE) . $v->photo_name;
				$has_fb_cover = true;
			}elseif(!$has_fb_cover){
				$fb_images[] = UEvent::getImgPath(UEvent::IMGSIZE_LARGE) . $v->photo_name;
			}
        }
		$article->fb_images = $fb_images;
		
		$article = $this->getArticleDetail($page_id, $article);
		
        return $article;
    }
    
    private function getArticleDetail($page_id, $article)
	{
        /* Published time */
		$article->publish_date_time = $article->publish_date;
		$article->publish_date = formatDate($article->publish_date);
		
		/* Main Categories */
		$article->main_cat=($article->main_cat_id!=null)?UCategory::getCatInfoById($article->main_cat_id)->cat_name:'';
		$article->main_subcat=($article->main_subcat_id!=null)?UCategory::getCatInfoById($article->main_subcat_id)->cat_name:'';
		
		/* Check event finish or not */
		$article->isEnd = UEvent::checkEventIsEnd($page_id);
		
		$article->start_date = $this->model->getDate($page_id)->start_date;
		$article->end_date = $this->model->getDate($page_id)->end_date;
		
		/* Cover Photo */
		$article->cover=UEvent::getCoverPhoto($page_id);
		$article->cover->coverName = $article->cover->cover_photo;
		
		/* Regions */
		$article->regions = $this->model->getArticleRegion($page_id);
		$article->area_name = $this->model->getEventAreas($page_id)[0]->area_name;
		
		/* 內容標籤 */
		$article->tags = $this->model->getEventTagsById($page_id);

		/* 活動細節 */
		$details = $this->model->getEventDetailsById($page_id);
		$this->assign('details',$details);
		$detail_url = $this->model->getEventDetailUrlById($page_id);
		$this->assign('detail_url',$detail_url);
		
		/* 活動日曆 */
		$dates = $this->model->getAllDate($page_id);
		$this->assign('dates',$dates);
		
		//take UL log
		$article->LogLocation =  (array) $this->model->getLocationPairs($page_id);
		
		$article->ArticleURL = UEvent::getURL($page_id, $article->title);
		
		return $article;
    }
	
	protected function prepareSEO($page_id, $article)
    {
        parent::prepareSEO($page_id, $article);
        $this->assign("json_ld_event", $this->parseArticlesEventJsonLd($article));
    }
	
	protected function prepareRes()
    {
		$this->addJs('library/waypoints/lib/shortcuts/infinite.js');
		$this->addJs('library/waypoints/lib/shortcuts/inview.js');
		$this->addCss('css/hk-article.css');
		$imgDir = UAPP_BASE_URL.'/sys/view/campaign/search2018/';
		array_push($this->jsFiles, $imgDir.'js/hk-article-detail.js?v='.CAMPAIGN_CACHE);
    }

    
}
?>