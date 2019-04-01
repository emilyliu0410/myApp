<?php defined('UFM_RUN') or die('No direct script access allowed.');

abstract class UBaseContentListController extends UController
{
    protected $contentTitles = array();
    protected $isCustom = false;

    function __construct($fm)
    {
        parent::__construct($fm);
    }

    protected function actionIndex()
    {
        $page_id = $this->fm->articleId;

        if ($page_id||$this->isCustomList) {
            if ($_REQUEST['mode'] === 'lazy') {
                $this->displayListingAjax($page_id);
            } else {
                $this->displayListing($page_id);
            }
        } else if ($this->isCustom) {
            $this->displayCustom();
        } else {
            $this->redirect("/index");
        }
    }

    /**
     * logic for displaying a custom page, default is empty
     * @since
     */
    protected function displayCustom()
    {
    }

    /**
     * loading the whole html of a listing page, default page 1
     * @param $page_id - the id of the specified section item
     * @since
     */
    protected function displayListing($page_id = null)
    {
        $this->prepareData($page_id, 1);
        $this->assignAdsOrderMatrix();
        $this->prepareAds();
        $this->prepareRes();

        $this->layout = 'responsive2';
        $this->display();
    }

    /**
     * loading the item list by ajax, `p` in query string as the page number to load
     * @param $page_id - the id of the specified section item
     * @since
     */
    protected function displayListingAjax($page_id = null)
    {
        $this->prepareData($page_id, intval($_REQUEST['p']), true);

        $this->view = $this->getPageUrlPath() . '/lazy';
        $this->layout = 'directHtmlLazy';
        $this->display();
    }

    private function prepareData($id, $page, $is_ajax = false)
    {
        $this->assign("p", $page);
        $this->assign("page_id", $id);
        $filter = isset($_REQUEST['filter']) ? $_REQUEST['filter'] : 0;
        $this->assign("filter", $filter);
		$s = isset($_REQUEST['s']) ? $_REQUEST['s'] : '';

        /* Login */
        $isLogin = $this->user->isLogin();
        $this->assign('isLogin', $isLogin);

        $page_info = $this->getPageInfo($id);
        if (!$page_info->name) {
            $this->redirect('/search/index');
        }
        $this->assign('page_info', $page_info);

        $pagination = new UPagination(array(
            'page' => $page,
            'limit' => 12,
            'length' => 4
        ));

        $standardConditions = array(
			's' => $s,
            'filter' => $filter
        );
        $conditionKey = $this->getStandardConditionsKey();
        if ($conditionKey !== null) {
            $standardConditions[$conditionKey] = $id;
        }

        $articles = $this->getArticles(array_merge($standardConditions, array(
            'offset' => $pagination->offset,
            'limit' => $pagination->limit
        )));

        $pagination->setTotal(uDb()->foundRows());

        $this->handleArticles($articles, $is_ajax);
        $this->assign("articles", $articles);
        $this->assign('pagination', $pagination);

        if (!$is_ajax) {
            $this->prepareSEO($page_info, $articles);
        }
    }

    /**
     * loop through the article list to insert further information into it
     * @param $articles - the result article list
     * @param $is_ajax - to determine if the request is from ajax
     * @since
     */
    protected function handleArticles($articles, $is_ajax)
    {
        foreach ($articles as $k => $v) {
            if (!$is_ajax) {
                $this->contentTitles[] = trim($v->title);
            }
            $articles[$k]->isFollowed = UTheme::followed($this->user->user_id, $v->page_id, $v->pagetype_id);
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
                $cover = $v->cover;
                $cover_url = UContent::getImgPath($v->pagetype_id, UModel::IMGSIZE_RELATED) . $cover;
				
				// check 480x270
				$cover_url_480x270 = UContent::getImgPath($v->pagetype_id, UModel::IMGSIZE_RELATED_480_270) . $cover;
				$cover_photo_file = str_replace(UAPP_HOST.UAPP_BASE_URL.'/','',$cover_url_480x270);
				if(file_exists($cover_photo_file)){
//					debug('480x270 id: '.$v->page_id,0);
					$cover_url = $cover_url_480x270;
				}
            }
            $articles[$k]->cover = $cover ? $cover_url : UTheme::getDefaultPhoto(CST_IMGSIZE_LISTING);
            $articles[$k]->pageUrl = UContent::getURL($v->content_id);
			
			if($v->content_id){
				$locations = UContent::getLocationArea($v->content_id,10);
				$articles[$k]->location_tag = getLocationHtmlTag($locations);
			}
			
			$content_flag = UContent::getContentFlagByContentID($articles[$k]->content_id, $articles[$k]->pagetype_id);
            if(!empty($content_flag)) {
                $articles[$k]->content_flag = $content_flag;
            }
			
        }
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
     * @param $articles - the array of article
     * @return null|string - return the json string of the result, null when the parsed json array is empty
     * @since
     */
    protected function parseArticlesEventJsonLd($articles)
    {
        $json_ld = array();
        foreach ($articles as $article) {
            unset($article->content);
            if (!$article->address || !$article->start_date || $article->start_date === '0000-00-00') {
                continue;
            }
            $ld_obj = array(
                '@context' => 'http://schema.org',
                '@type' => 'Event',
                'startDate' => $article->start_date,
                'url' => $article->pageUrl,
                'location' => array(
                    '@type' => 'Place',
                    'name' => $article->area_name,
                    'address' => $article->address
                ),
                'name' => $article->title,
                'image' => [$article->cover]
            );
            if ($article->end_date && $article->end_date !== '0000-00-00') {
                $ld_obj['endDate'] = $article->end_date;
            }
            $json_ld[] = $ld_obj;
        }
        if (empty($json_ld)) return null;
        return json_encode($json_ld, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    protected function prepareSEO($page_info, $articles)
    {
        /*utm*/
        $utm_campaign = 'theme-' . $_REQUEST['id'];
        $utm_source = 'uhk';
		if($page_info->id){
			$this->metaOptions['canonicalUrl'] = $this->createUrl($this->getPageUrlPath(), array('id' => $page_info->id, 'url_title' => $page_info->name));
		}else{
			$this->metaOptions['canonicalUrl'] = $this->createUrl($this->getPageUrlPath());
		}

        /*metadata*/
        $this->pageTitle = getMetaTitle($page_info->name);
        $this->metaKeywords = getMetaKeywords($page_info->name, true);
        $contentTitles = array_unique(array_merge(array($page_info->name), $this->contentTitles));
		if($page_info->name2){
			$this->pageTitle = getMetaTitle($page_info->name2);
			$this->metaKeywords = getMetaKeywords($page_info->name2, true);
			$contentTitles = array_unique(array_merge(array($page_info->name2), $this->contentTitles));
		}
        $metaDescription = subStringInBytes(implode('|', $contentTitles), 160);
        $this->metaDescription = getMetaDescription($metaDescription);
		$this->metaOgTitle = $this->pageTitle;
        $this->assign("json_ld_event", $this->parseArticlesEventJsonLd($articles));
    }

    /**
     * load the extra css and js files in this function
     * @since
     */
    protected function prepareRes()
    {
        $this->addJs('js/global/jquery.hk-loadmore.js');
    }

    /**
     * assign ad items into view variables in this function
     * @since
     */
    protected function prepareAds()
    {
        $fixed1 = uGetAdItem('div-gpt-ad-1472555452473-1');
        $fixed2 = uGetAdItem('div-gpt-ad-1472555452473-2');
        $fixed3 = uGetAdItem('div-gpt-ad-1472555452473-3');

        $this->assign("topBanner", $fixed1);
        $this->assign("innerListAds1", $fixed2);
        $this->assign("innerListAds2", $fixed3);
    }

    protected function assignAdsOrderMatrix()
    {
        $this->assign("ads_order_matrix", $this->getAdsOrderMatrix());
    }

    /**
     * get the matrix of ads ordering
     * first value in the row of array is xs screen, second is lg, third is sm and xl
     * @return array - the matrix of ads ordering
     * @since
     */
    protected function getAdsOrderMatrix()
    {
        return array(
            [1, 1, 1],
            [1, 1, 1],
            [3, 1, 1],
            [3, 3, 1],
            [3, 3, 3],
            [5, 3, 3],
            [5, 5, 3],
            [5, 5, 3]
        );
    }

    /**
     * get the url path of the page
     * @return string - the url path
     * @since
     */
    protected abstract function getPageUrlPath();

    /**
     * get the info of the section item with the specified id and corresponding model, should contains `id` and `name` attributes
     * @param $id - the id of the section item
     * @return mixed - the db result of the page info
     * @since
     */
    protected abstract function getPageInfo($id);

    /**
     * get the condition key used for db query
     * @return string - the primary key of the table
     * @since
     */
    protected abstract function getStandardConditionsKey();

    /**
     * the abstract function for getting the articles given the search conditions
     * @param $searchConditions - the array of condition which used for query
     * @return array - the result article array
     * @since
     */
    protected abstract function getArticles($searchConditions);

}