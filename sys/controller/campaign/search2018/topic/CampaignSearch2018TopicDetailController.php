<?php

defined('UFM_RUN') or die('No direct script access allowed.');


class CampaignSearch2018TopicDetailController extends UBaseArticleDetailController
{
    var $model;

    function __construct($fm)
    {
        parent::__construct($fm);
        $this->model = new UTopic();
    }
	
	protected function getPageType()
    {
        return UTopic::PAGETYPE_ID;
    }
	
	protected function getPageLayout($infinite_scroll)
    {
        return $infinite_scroll?'blankHtml':'responsiveHtml';
    }
	
	protected function getNextArticle($exclude_ids_arr)
    {
        return $this->model->getNextTopicId($exclude_ids_arr);
    }

    protected function getPageUrlPath()
    {
        return '/campaign/search2018/topic/detail';
    }
	
	protected function getULLogChannel()
    {
        return '熱話';
    }

    protected function getCookieSetting()
    {
		$cookieSetting = new stdClass();
		$cookieSetting->cookiesName = 'topicViewedHistory';
		$cookieSetting->historyLimit = 5;
		
        return $cookieSetting;
    }

    protected function getArticle($page_id, $alias)
    {
		
		if($alias){
			$article = $this->model->findByAlias($alias);
			$page_id = $article->topic_id;
		}else{
			$article = $this->model->findByPk($page_id);
		}
        /* @var $article UTopic */
		
		// update title
		$article->title = $article->title1;
		
		//lazy loading
		$_num_of_loading_photo = 3;
        $content = uAddSignature($article->content);
		
        //get the photos of this topic
        $photos = $this->model->getPhotos($page_id);
		
		if (!empty($photos)) {
			$content = $article->addPageDivisionToContent($content, $_num_of_loading_photo);
            $article->content = $article->addSwipePhotoToContent($content, $page_id, UTopic::getImgPath(UTopic::IMGSIZE_DETAIL), UTopic::getImgPath(UEvent::IMGSIZE_LARGE), $photos,1);
			
			$fb_images = array();
			foreach ($photos as $photo) {
				if($photo->is_fb_cover==1) $fb[] = UTopic::getImgPath(UTopic::IMGSIZE_LARGE) . $photo->photo_name;
				$fb_images[] = UTopic::getImgPath(UTopic::IMGSIZE_LARGE) . $photo->photo_name;
			}

			if(sizeOf($fb)!=0){
				$fb_images=$fb;
			}
			
			$article->fb_images = $fb_images;
        }
		$article->content = uConvertContentTags($article->content, array('youtubeWidth'=>600,'youtubeHeight'=>400));
		
		$article = $this->getArticleDetail($page_id, $article);
		
        return $article;
	

    }
    
    private function getArticleDetail($page_id, $article)
	{
		$article->publish_date_time = $article->publish_date;
		$article->publish_date = formatDate($article->publish_date);

        //get the categories of this topic
//        $categories = $this->model->getTopicCat($page_id);
//        $this->assign('categories', $categories);

        //get the tags for this topic
        $article->tags = $this->model->getTopicTags($page_id);
		
		/* Main Categories */
		$UCategory = new UCategory();
		if($article->main_subcat_id){
			$article->main_cat_id = $UCategory->getCatBySubcat($article->main_subcat_id)->parent_cat_id;
		}
		$article->main_cat=($article->main_cat_id!=null)?$UCategory->getCatInfoById($article->main_cat_id)->cat_name:'';
		$article->main_subcat=($article->main_subcat_id!=null)?$UCategory->getCatInfoById($article->main_subcat_id)->cat_name:'';
		
		/* Cover Photo */
		$article->cover=UTopic::getCoverPhoto($page_id);
		
		//take UL log
		$article->LogLocation =  (array) $this->model->getLocationPairs($page_id);
		
		$article->ArticleURL = UTopic::getURL($page_id,$article->title);
		
		return $article;
	
    }
	
	protected function prepareRes()
    {
		$this->addCss('css/hk-article.css');
		$imgDir = UAPP_BASE_URL.'/sys/view/campaign/search2018/';
		array_push($this->jsFiles, $imgDir.'js/hk-article-detail.js?v='.CST_JS_CACHE_BUST);
    }

}
