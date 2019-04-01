<?php defined('UFM_RUN') or die('No direct script access allowed.');
abstract class UBaseArticleDetailController extends UController
{
	var $UTheme;
	var $UContent;
	var $infinite_scroll;
	
    function __construct($fm)
    {
        parent::__construct($fm);
		$this->UTheme = new UTheme();
		$this->UContent = new UContent();
    }
	
	protected function actionIndex()
    {
		global $useRedis, $articleID;
		$useRedis = true;
		
		$page_id = isset($_REQUEST['id'])?url_decrypt($_REQUEST['id']) : 0;
		$this->infinite_scroll = isset($_REQUEST['scroll']) ? (int) $_REQUEST['scroll'] : 0;
		
		if(isset($this->fm->urlParams[0]) && count($this->fm->urlParams)){
			$page_id = url_decrypt($this->fm->urlParams[0]);

			if(!is_numeric($page_id) && !($page_id>0)){
				$alias = urldecode($this->fm->urlParams[0]);
			}
		}
		
		if($this->fm->articleId) {
			$page_id = $this->fm->articleId;
		}
			
		if(!is_numeric($page_id) && !($page_id>0) && !$alias) {
			$this->redirect("/error");
        }
		
		if ($page_id) {
			$articleID = $page_id;
            if ($this->infinite_scroll) {
                $this->displayInfiniteArticle($page_id, $alias);
            } else {
                $this->displayArticle($page_id, $alias);
            }
        } else if ($this->isCustom) {
            $this->displayCustom();
        }
			
    }
	
	/**
	 * loading the whole html of article page
	 * @param type $page_id
	 * @param type $alias
	 */
    protected function displayArticle($page_id = null, $alias = null)
    {
        $this->prepareData($page_id, $alias);
        $this->prepareAds();
        $this->prepareRes();

        $this->layout = $this->getPageLayout($this->infinite_scroll);
        $this->display();
    }
	
	/**
	 * loading the infinite scroll article 
	 * @param type $page_id
	 * @param type $alias
	 */
    protected function displayInfiniteArticle($page_id = null, $alias = null)
    {
		$this->prepareData($page_id, $alias, true);
		
		$this->view= $this->getPageUrlPath() . '/article';
		$this->layout=$this->getPageLayout($this->infinite_scroll);
		$this->display();
        
    }
	
	/**
	 * prepare article date
	 * @param type $page_id
	 * @param type $alias
	 * @param type $is_Infinite
	 */
	private function prepareData($page_id, $alias = null, $is_Infinite = false)
    {
		$preview = isset($_REQUEST['preview']) ? (int) $_REQUEST['preview'] : 0;

		$article = $this->getArticle($page_id, $alias);
		
		if(empty($article)||($article->published == 0 && $preview == 0)||(strtotime($article->publish_date_time) > strtotime('now') && $preview == 0)){
			$this->redirect("/error");
		}
		
		if ($preview == 0) {
			$this->logPageHits($page_id, $this->getPageType()); 
		}
		
		$_id = isset($_REQUEST['id']) ? url_decrypt($_REQUEST['id']) : 0;
		if($_id>0 && ENABLE_SEO_FRIENDLY){
			parse_str($_SERVER['QUERY_STRING'], $parameters);
			unset($parameters['id']);
			$parameters = array_merge(array('id'=>$_id,'url_title'=>$article->title),$parameters);
			$this->redirect301($this->getPageUrlPath(),$parameters);
		}
		
		$isLogin = $this->user->isLogin();
		$this->assign('isLogin',$isLogin);
		
		/* Cover Photo */
		$cover_photo = '';
		if($this->getPageType()==UTour::PAGETYPE_ID){
			$cover_photo = UTour::getCoverPath2(UMODEL::IMGSIZE_RATIOS_16_9).$article->cover->cover_photo;
				
			// check ratio 16:9
			$cover_photo_file = str_replace(UAPP_HOST.UAPP_BASE_URL.'/','',$cover_photo);
			if(!file_exists($cover_photo_file)){
				$cover_photo = UTour::getCoverPath2(UMODEL::IMGSIZE_LARGE).$article->cover->cover_photo;
			}
		}else{
			if($article->cover->coverName){
				$cover_photo = UContent::getImgPath($this->getPageType(), UMODEL::IMGSIZE_RATIOS_16_9) . $article->cover->coverName;
				
				// check ratio 16:9
				$cover_photo_file = str_replace(UAPP_HOST.UAPP_BASE_URL.'/','',$cover_photo);
				if(!file_exists($cover_photo_file)){
					$cover_photo = UContent::getImgPath($this->getPageType(), UMODEL::IMGSIZE_LARGE) . $article->cover->coverName;
				}
			}
		}
		$article->cover->cover_photo = $cover_photo ? $cover_photo : UTheme::getDefaultPhoto('600x400');
		
		/* Followed */
		if($isLogin){
			$isFollowed = $this->user->isFollowed($this->getPageType(), $page_id);
			$this->assign('isFollowed',$isFollowed);
		}
		
		/* Price Range */
		if($article->price_range_id){
			 $price_info = UContent::getPriceInfoByRangeId($article->price_range_id);
			 $article->price_range = $price_info->range;
		}
		
		//??唳?蝡?
		$options = array(
			'imgsizeArray' => array(UMODEL::IMGSIZE_RELATED),
			'_limit' => 3,
		);
		$latestArticles = $this->UContent->getLatest($options);
		$this->assign('pageRightLatestArticles', $latestArticles);
		$this->assign('pageRightLatestArticles_track', 'Detail Page Links');
		
		/* User Comment  */
		$filter = array(
			'id' => $page_id,
			'pagetype_id' => $this->getPageType(),
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
		$user_comments['pagetype_id'] = $this->getPageType();
		$this->assign('user_comments',$user_comments);
		
		/* ?賊??? */
		if($article->tags){
			$related_tags_articles = $this->UContent->getRelatedTagsArticles(UTheme::IMGSIZE_RANKING, $this->getPageType(), $page_id, 6);
			//foreach($related_tags_articles as $k=>$v){
				// get c tag			
				//$related_contents_tags = $this->UContent->getContentTags($related_tags_articles[$k]->content_id);
				//if($related_contents_tags){
					//$related_tags_articles[$k]->tag->tag_name = $v->tag_name;
					//$related_tags_articles[$k]->tag->tag_id = $v->tag_id;
				//}
			//}
			$this->assign('related_tags_articles',$related_tags_articles);
		}
		
		/* Share Section */
		$shareUrl = $this->createUrl($this->getPageUrlPath(), array('id' => url_encrypt($page_id), 'utm_medium' => 'share'));
		$share_button = share_with_bookmark_buttons2(
			array(
				'title'=>$article->title,
				'url'=>$shareUrl,
				'pageId'=>$page_id,
				'pageType'=>$this->getPageType(),
				'isLogin'=>$isLogin,
				'isFollowed'=>$isFollowed,
				'main_cat_id'=>$article->main_cat_id,
				'main_cat'=>$article->main_cat,
                'eventCategory'=>'\'Share Button\''
			)
		);
		$this->assign('share_button',$share_button);

		$share_button_above = share_button_in_article_details(
            array(
                'title'=>$article->title,
                'url'=>$shareUrl,
                'pageType'=>$this->getPageType(),
                'main_cat_id'=>$article->main_cat_id,
                'eventCategory'=>'\'Share Button - Above\''
            )
        );
        $this->assign('share_button_above',$share_button_above);

        $share_button_below = share_button_in_article_details(
            array(
                'title'=>$article->title,
                'url'=>$shareUrl,
                'pageType'=>$this->getPageType(),
                'main_cat_id'=>$article->main_cat_id,
                'eventCategory'=>'\'Share Button - Below\''
            )
        );
        $this->assign('share_button_below',$share_button_below);

		//take UL log
		foreach($article->tags as $k=>$v){
			$tagname[]=$v->tag_name;
		} 
		writeULLog($this->getULLogChannel(), $article->main_cat.'|'.$article->main_subcat, $page_id, $article->title, join('|',$tagname), join('|',$article->LogLocation));
		
		//set this instance to cookies
		$cookieSetting = $this->getCookieSetting();
        $this->setHistoryCookies($cookieSetting->cookiesName, $cookieSetting->historyLimit, $this->getPageType(), $page_id, $article->title);
		
		/* Author Info */
		if($article->author_id>0){
			$author = $this->UTheme->getArticleAuthor($article->author_id);
			if(!empty($author->photo)){
				$author->photo =  UAPP_HOST . UAPP_BASE_URL . '/cms/images/author/120x120/'.$author->photo;
			}else{
				$author->photo = UAPP_HOST . UAPP_BASE_URL . '/media/images/global/profile.png';
			}
			$this->assign('author',$author);
			$article->author_name = $author->author_name;
		}
		
		// infinite article
        $exclude_ids = isset($_GET['exclude']) ? $_GET['exclude'] : null;
		if($exclude_ids){
			$exclude_ids_arr = explode(',',$exclude_ids);
		}
		
		if(count($exclude_ids_arr)<9){
			$exclude_ids_arr[] = $page_id;
			$exclude_ids = implode(',',$exclude_ids_arr);
			$next_article = $this->getNextArticle($exclude_ids_arr);

			if($next_article->page_id){
				$next_article_url = $this->createUrl($this->getPageUrlPath() ,array('id'=>$next_article->page_id)).'?scroll=1&exclude='.$exclude_ids;
				$next_article_id = $next_article->page_id;
				$next_article_title = subStringInBytes($next_article->title, 30, '...');
				$next_article_maincat = $next_article->main_cat;
				
				$this->assign('next_article_url',$next_article_url);
				$this->assign('next_article_id',$next_article_id);
				$this->assign('next_article_title',$next_article_title);
				$this->assign('next_article_maincat',$next_article_maincat);
				
				$inline_datas[] = 'data-next-id="'.$next_article_id.'"';
				$inline_datas[] = 'data-next-url="'.$this->createUrl($this->getPageUrlPath(),array('id'=>$next_article_id,'url_title'=>$next_article->title),false).'"';
				$inline_datas[] = 'data-next-title="'.$next_article_title.'"';
				$inline_datas[] = 'data-next-maincat="'.$next_article_maincat.'"';
				
			}
		}
		
		$this->assign("article", $article);
		
		//if(!$is_Infinite){
			$this->prepareSEO($page_id, $article);
		//}
		
		$inline_datas[] = 'data-title="'.getMetaTitle($article->title).'"';
		$inline_datas[] = 'data-page-id="'.$article->id.'"';
		$inline_datas[] = 'data-page-url="'.$this->createUrl($this->getPageUrlPath(),array('id'=>$article->id,'url_title'=>$article->title),false).'"';
		if($next_article->page_id){
		}
		$this->metaOptions['gaDimension']['dimension8'] = $_COOKIE['ULVID'];
		$this->metaOptions['gaDimension']['dimension9'] = $_COOKIE['GVID'];
		if(isset($this->metaOptions['gaDimension'])){
			foreach($this->metaOptions['gaDimension'] as $k=>$v){
				if(empty($v)) continue;
				$inline_datas[] = 'data-'.$k.'="'.htmlentities ($v, ENT_QUOTES).'"';
			}
		}
		$this->assign('inline_datas',$inline_datas);

    }
	
	protected function prepareSEO($page_id, $article)
    {
		$this->pageTitle = getMetaTitle($article->title);
		$this->metaAuthor = $article->author_name;
		$this->metaOgType = 'article';
		
		$this->metaOptions['facebook'] = uGetFacebookMetas($article->fb_images, 5);
        $this->metaOptions['canonicalUrl'] = $article->ArticleURL;

		// get GA dimension
		$this->metaOptions['gaDimension'] = $this->UContent->getGaPageviewDimension($this->getPageType(), $page_id);
		
		$this->metaDescription = getMetaDescriptionFromContent($article->meta_descr, $article->summary, $article->content);
		
		// og title description
		$this->metaOgDescription = empty($article->og_descr) ? $this->metaDescription : htmlspecialchars($article->og_descr);
		$this->metaOgTitle = empty($article->og_title) ? $this->pageTitle : htmlspecialchars($article->og_title);
		
		$this->metaKeywords = getMetaKeywordsFromTags($article->tags);
		
		$this->metaTitle = empty($article->meta_title) ? $this->pageTitle : htmlspecialchars($article->meta_title);
		
		$this->assign("json_ld_breadcrumb", $this->parseSubcatBreadcrumbJsonLd($article->main_cat_id, $article->main_cat, $article->main_subcat_id, $article->main_subcat));
    }

	
	/**
     * parse the BreadcrumbList JSON-LD for sub-category
     * @param $main_cat_id - the id of main category
     * @param $main_cat_name - the name of main category
     * @param $sub_cat_id - the id of sub-category
     * @param $sub_cat_name - the name of sub-category
     * @return null|string - return the json string of the result, null when no specified main category id
     * @since
     */
    protected function parseSubcatBreadcrumbJsonLd($main_cat_id, $main_cat_name, $sub_cat_id, $sub_cat_name)
    {
        if ($main_cat_id === null) {
            return null;
        }
        $json_ld = array(
            '@context' => 'http://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                array(
                    '@type' => 'ListItem',
                    'position' => 1,
                    'item' => array(
                        '@id' => $this->createUrl('/category/index', array('id' => $main_cat_id, 'url_title' => $main_cat_name)),
                        'name' => $main_cat_name
                    )
                ),
                array(
                    '@type' => 'ListItem',
                    'position' => 2,
                    'item' => array(
                        '@id' => $this->createUrl('/category/index', array('id' => $sub_cat_id, 'url_title' => $sub_cat_name)),
                        'name' => $sub_cat_name
                    )
                )
            ]
        );
        return json_encode($json_ld, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    /**
     * parse the Event JSON-LD for articles, article that does not contain `address` and `start_date` will be skipped
     * @param $article - article
     * @return null|string - return the json string of the result, null when the parsed json array is empty
     * @since
     */
    protected function parseArticlesEventJsonLd($article)
    {
        $json_ld = array();
		unset($article->content);
		if (!$article->address || !$article->start_date || $article->start_date === '0000-00-00') {
			return null;
		}
		$ld_obj = array(
			'@context' => 'http://schema.org',
			'@type' => 'Event',
			'startDate' => $article->start_date,
			'url' => $article->ArticleURL,
			'location' => array(
				'@type' => 'Place',
				'name' => $article->area_name,
				'address' => $article->address
			),
			'name' => $article->title,
			'image' => [$article->cover->cover_photo]
		);
		if ($article->end_date && $article->end_date !== '0000-00-00') {
			$ld_obj['endDate'] = $article->end_date;
		}
		$json_ld[] = $ld_obj;
        
        if (empty($json_ld)) return null;
        return json_encode($json_ld, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
	

    /**
     * assign ad items into view variables in this function
     * @since
     */
    protected function prepareAds()
    {
        //Ad Banner
		$fixed1 = uGetAdItem('div-gpt-ad-1472555377034-1'.ADV_BANNER_TIMESTAMP);
		$fixed2 = uGetAdItem('div-gpt-ad-1472555377034-2'.ADV_BANNER_TIMESTAMP);
		$fixed3 = uGetAdItem('div-gpt-ad-1472555377034-3'.ADV_BANNER_TIMESTAMP);

		$this->assign("topBanner",$fixed1);
		$this->assign("largeRectangle1Banner",$fixed2);
		$this->assign("largeRectangle2Banner",$fixed3);
		
		$infiniteBanners = array(
			'div_gpt_ad_1472555377034_1'.ADV_BANNER_TIMESTAMP2,
			'div_gpt_ad_1472555377034_2'.ADV_BANNER_TIMESTAMP2,
			'div_gpt_ad_1472555377034_3'.ADV_BANNER_TIMESTAMP2 
		);
		$this->assign("infiniteBanners",$infiniteBanners);
    }
	
	/**
     * load the extra css and js files in this function
     * @since
     */
    protected function prepareRes()
    {
		$this->addJs('library/waypoints/lib/shortcuts/infinite.js');
		$this->addJs('library/waypoints/lib/shortcuts/inview.js');

		$this->addJs('js/hk-article-detail.js');
		$this->addCss('css/hk-article.css');

		$this->addJs('js/global/bookmark.js');
		$this->addCss('library/animate/animate.min.css');
		$this->addCss('css/global/hk-card-update.css');
    }
	
	protected abstract function getPageType();
	
	protected abstract function getNextArticle($exclude_ids_arr);
	
    protected abstract function getPageUrlPath();
	
	protected abstract function getULLogChannel();

    protected abstract function getCookieSetting();
	
    protected abstract function getArticle($page_id, $alias);
	
	protected abstract function getPageLayout($infinite_scroll);
}