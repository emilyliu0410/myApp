<?php
defined( 'UFM_RUN' ) or die( 'No direct script access allowed.' );
class CampaignSearch2018ListingController extends UController
{

	function getCatByCatid($cat_id) {
		$sql='SELECT cat_id, cat_name FROM tbl_cat
			WHERE cat_id IN ('.$cat_id.') 
			LIMIT 1';
        return uDb()->findOne($sql);
    }
	function getTags($tag_ids_str){
		$tags = array();
        if (!empty($tag_ids_str)) {
		$sql = 'SELECT tag_id, tag_name
				FROM tbl_tags
				WHERE tag_id IN ('.$tag_ids_str.')
				';
		$tags = uDb()->findList($sql);
        }
		
        return $tags;
	}
	function getLocations($location_ids_str){
		$locations = array();
        if (!empty($location_ids_str)) {
		$sql = 'SELECT l.location_id,  l.name 
                FROM tbl_location l 
                WHERE l.location_level IN (1,2,3) AND l.location_id IN (' . $location_ids_str . ')
				ORDER BY l.location_id ASC
				';
        $locations = uDb()->findList($sql);
		}
        return $locations;
	}
	function getCats($cat_ids_str){
		$cats = array();
        if (!empty($cat_ids_str)) {
		$sql = 'SELECT a.cat_id, a.cat_name
                FROM tbl_cat a 
                WHERE a.cat_id IN (' . $cat_ids_str . ')
				ORDER BY a.cat_id ASC
				';
        $cats = uDb()->findList($sql);
		}
        return $cats;
	}
	function getAuthors($author_ids_str) {
        $authors = array();
        if (!empty($author_ids_str)) {
            $sql = "SELECT author_id, author_name " .
                    "FROM tbl_author " .
                    "WHERE published = 1 " .
                    "AND author_id IN (" . $author_ids_str . ") 
					ORDER BY author_name ASC";
            
            $authors = uDb()->findList($sql);
        }
		
        return $authors;
    }
	function getPagetypeNames($pagetype_ids_str) {
        $resultNames = array();
        if (!empty($pagetype_ids_str)) {
            $sql = 'SELECT	pagetype_id, pagetype_name
                        FROM	tbl_pagetypes
                        WHERE 	pagetype_id IN ('.$pagetype_ids_str.')
						';
			$rs = uDb()->findList($sql);
			foreach($rs as $type){
				$resultNames[] = $type->pagetype_name;
			}
        }
		
        return implode(', ', $resultNames);
    }
	function getThemesByAttributes($attributes=array()){
		$o = extend(array(
            /* 'district_key_str' => '',
            'area_key_str' => '',
            'landmark_key_str' => '', */
			'location_id_str' => '',
            'cat_id_str' => '',
            'author_id_str' => '',
            'limit' => 1,
        ), $attributes);
        extract($o);
		$sql = 'SELECT theme_id FROM tbl_theme t
				WHERE 
					t.location_id_str = "'.$location_id_str.'"
					AND t.cat_id_str = "'.$cat_id_str.'"
					AND t.author_id_str = "'.$author_id_str.'"
				LIMIT '.$limit.'
				';//debug($sql);
		if($location_id_str!=''&&$cat_id_str!=''&&$author_id_str!=''){
			return uDb()->findList($sql);
		}else {
			return null;
		}
	}
	function to_id_array($str){
		$arr=array();
		$tem_arr=explode(',',$str);
		if(count($tem_arr)>0){	
			foreach($tem_arr as $k=>$v){
				if(is_numeric($v))
					$arr[]=$v;
			}
		}	
		return $arr;
	}

    function actionIndex() {
		$keyword = $q = isset($_GET['q']) ? urldecode($_GET['q']) : '';
		if($keyword=='NOEL！2016聖誕特集') header("Location: ".$this->createUrl('/theme/detail',array('id'=>'ABtGCVoyBw4')));
		
		$tag = isset($_GET['tag'])&&!is_array($_GET['tag'])&&strlen($_GET['tag'])>0&&$_GET['tag']>0?self::to_id_array($_GET['tag']):array();
		$p = isset($_GET['p']) ? (int) $_GET['p'] : 1;
		$s = (isset($_GET['s']) && ($_GET['s'] == UContent::SORTBY_HIT || $_GET['s'] == UContent::SORTBY_RATING || $_GET['s'] == UContent::SORTBY_DATE || $_GET['s'] == UContent::SORTBY_HIT_WEEK )) ? $_GET['s'] : UContent::SORTBY_DATE;
		$cat = isset($_GET['cat'])&&!is_array($_GET['cat'])&&strlen($_GET['cat'])>0?self::to_id_array($_GET['cat']):array();
		$location_ids = isset($_GET['location'])&&!is_array($_GET['location'])&&strlen($_GET['location'])>0&&$_GET['location']>0?self::to_id_array($_GET['location']):array();
		$multi_location = isset($_GET['multi_location']) ? (int) $_GET['multi_location'] : 0;
		$location_type = isset($_GET['location_type']) ? (int) $_GET['location_type'] : 0;
		$region_ids = isset($_GET['region'])&&!is_array($_GET['region'])&&strlen($_GET['region'])>0&&$_GET['region']>0?self::to_id_array($_GET['region']):array();
		$type = isset($_GET['type'])&&!is_array($_GET['type'])&&strlen($_GET['type'])>0&&$_GET['type']>0?self::to_id_array($_GET['type']):array();
		$kol = isset($_GET['kol'])&&!is_array($_GET['kol'])&&strlen($_GET['kol'])>0&&$_GET['kol']>0?self::to_id_array($_GET['kol']):array();
		
		$listing = isset($_GET['listing']) ? (int) $_GET['listing'] : 0;
		
		$sday = isset($_GET['sday'])&&is_numeric($_GET['sday'])&&strlen($_GET['sday'])==8?$_GET['sday']:null;
		$eday = isset($_GET['eday'])&&is_numeric($_GET['eday'])&&strlen($_GET['eday'])==8?$_GET['eday']:null;

		$price_min = isset($_GET['price_min'])&&is_numeric($_GET['price_min'])?$_GET['price_min']:0;
		$price_max = isset($_GET['price_max'])&&is_numeric($_GET['price_max'])?$_GET['price_max']:800;
		$people = isset($_GET['people'])&&!is_array($_GET['people'])&&strlen($_GET['people'])>0&&$_GET['people']>0?self::to_id_array($_GET['people']):array();
		
		$filter_type = isset($_GET['filter_type'])&&!is_array($_GET['filter_type'])&&strlen($_GET['filter_type'])>0&&$_GET['filter_type']>0?self::to_id_array($_GET['filter_type']):array();
		$filter_kol = isset($_GET['filter_kol'])&&!is_array($_GET['filter_kol'])&&strlen($_GET['filter_kol'])>0&&$_GET['filter_kol']>0?self::to_id_array($_GET['filter_kol']):array();
		$filter_loc = isset($_GET['filter_loc'])&&!is_array($_GET['filter_loc'])&&strlen($_GET['filter_loc'])>0&&$_GET['filter_loc']>0?self::to_id_array($_GET['filter_loc']):array();
		$filter_cat = isset($_GET['filter_cat'])&&!is_array($_GET['filter_cat'])&&strlen($_GET['filter_cat'])>0&&$_GET['filter_cat']>0?self::to_id_array($_GET['filter_cat']):array();

		/* if(count($type)==1&&$type[0]==UTopic::PAGETYPE_ID){
			parse_str($_SERVER['QUERY_STRING'], $parameters);
			unset($parameters['type']);
			unset($parameters['listing']);
			$this->redirect301('/topic/index',$parameters);
		} */
		
		$model = new UContent();
		$locationModel = new ULocation();
		
		$eday_str = $eday?substr($eday,0,4).'-'.substr($eday,4,2).'-'.substr($eday,6):null;
		$sday_str = $sday?substr($sday,0,4).'-'.substr($sday,4,2).'-'.substr($sday,6):null;
		
		$date_display = '';
		$date_display .= $sday_str;
		if($sday_str && $eday_str) $date_display .= ' 至 ';
		$date_display .= $eday_str;
		$this->assign('date_display',$date_display);
		
		$fb_like_box = facebook_like_box();
		$this->assign('fb_like_box',$fb_like_box);

		/* 主題內容 */
		$UTheme = new UTheme();
		//$theme = $UTheme->getThemeByArticle(UEvent::PAGETYPE_ID,$page_id);
		
		/* $district_key_str = implode(',',$district_key);
		$area_key_str = implode(',',$area_key);
		$landmark_key_str = implode(',',$landmark_key); */
		$location_id_str = implode(',',$location_ids);
		$region_id_str = implode(',',$region_ids);
		$cat_id_str = implode(',',$cat);
		$tag_id_str = implode(',',$tag);
		$author_id_str = implode(',',$kol);
		
		$atttibutes =array( /* 'district_key_str' => $district_key_str,
							'area_key_str' => $area_key_str,
							'landmark_key_str' => $landmark_key_str, */
							'location_id_str' => $location_id_str,
							'cat_id_str' => $cat_id_str,
							'author_id_str' => $author_id_str,
							'limit' => 1
						);
						
		$theme_ids = $this->getThemesByAttributes($atttibutes);
		
		$isLogin = $this->user->isLogin();
		$this->assign('isLogin',$isLogin);

		
		$pagination = new UPagination(array(
            'page' => $p,
            'limit' => 12,
            'length' => 4,
            'prevLabel' => '<img src="' . UAPP_MEDIA_URL . '/images/global/arrow-prev.png" width="8" height="12" style="display:inline-block"/>',
            'lastLabel' => '<img src="' . UAPP_MEDIA_URL . '/images/global/arrow-next-end.png" width="12" height="12" style="display:inline-block"/>',
            'firstLabel' => '<img src="' . UAPP_MEDIA_URL . '/images/global/arrow-prev-end.png" width="12" height="12" style="display:inline-block"/>',
            'nextLabel' => '<img src="' . UAPP_MEDIA_URL . '/images/global/arrow-next.png" width="8" height="12" style="display:inline-block"/>'
        ));
		
		$location_str = join(',',$location_ids);
		$location_str = empty($location_str)||$location_str=='0'?null:$location_str;
		//$locationNames = $locationModel->getLocation($location_str);
		//$this->assign("locationNames",$locationNames);
		$locations = $this->getLocations($location_str);
		$this->assign("locations",$locations);
		
		$region_str = join(',',$region_ids);
		$region_str = empty($region_str)||$region_str=='0'?null:$region_str;
		if(count($region_ids)){
			$regionNames = $locationModel->getRegionList($region_ids);
			$this->assign("regionNames",$regionNames);
		}
		
		//$categoryModel = new UCategory();
		$cat_str = join(',',$cat);
		$cat_str = empty($cat_str)||$cat_str=='0'?null:$cat_str;
		$cats = $this->getCats($cat_str);
		$this->assign("cats",$cats);
		
		if(count($cat)){
			$search_cat = $this->getCatByCatid($cat[0]);
			$this->assign("search_cat",$search_cat);
		}
		
		//$authorModel = new UAuthor();
		$kol_str = join(',',$kol);
		$kol_str = empty($kol_str)||$kol_str=='0'?null:$kol_str;
		$authors = $this->getAuthors($kol_str);
		$this->assign("authors",$authors);
		
		$tag_str = join(',',$tag);
		$tag_str = empty($tag_str)||$tag_str=='0'?null:$tag_str;
		$tags = $this->getTags($tag_str);
		$this->assign("tags",$tags);
		
		
		
		$type_str = join(',',$type);
		$type_str = empty($type_str)||$type_str=='0'?null:$type_str;
		$typeNames = $this->getPagetypeNames($type_str);
		$this->assign("typeNames",$typeNames);
		
		if(count($people)>0){
			$peopleNames = array();
			if(in_array(1,$people)) $peopleNames[]=array('id'=>1,'name'=>'single','zh_name'=>'一個人');
			if(in_array(2,$people)) $peopleNames[]=array('id'=>2,'name'=>'couple','zh_name'=>'情侶');
			if(in_array(3,$people)) $peopleNames[]=array('id'=>3,'name'=>'family','zh_name'=>'親子');
			if(in_array(4,$people)) $peopleNames[]=array('id'=>4,'name'=>'friend','zh_name'=>'三五知己');
			if(in_array(5,$people)) $peopleNames[]=array('id'=>5,'name'=>'group','zh_name'=>'一大班人');
			$this->assign('peopleNames', $peopleNames);
		}
		
		
		if(count($filter_type)){
			$filter['type'] = $filter_type;
		}
		$filter_type_str = join(',',$filter_type);
		$filter_type_str = empty($filter_type_str)||$filter_type_str=='0'?null:$filter_type_str;
		
		if(count($filter_cat)){
			$filter['maincat'] = $filter_cat;
		}
		$filter_cat_str = join(',',$filter_cat);
		$filter_cat_str = empty($filter_cat_str)||$filter_cat_str=='0'?null:$filter_cat_str;
		
		if(count($filter_loc)){
			$filter['location'] = $filter_loc;
		}
		$filter_loc_str = join(',',$filter_loc);
		$filter_loc_str = empty($filter_loc_str)||$filter_loc_str=='0'?null:$filter_loc_str;
		
		if(count($filter_kol)){
			$filter['kol'] = $filter_kol;
		}
		$filter_kol_str = join(',',$filter_kol);
		$filter_kol_str = empty($filter_kol_str)||$filter_kol_str=='0'?null:$filter_kol_str;
		//debug($filter);
        //common search conditions
        $standardConditions = array(
            'q' => stripslashes($q),
            'tag' => $tag,
			'p' => $p,
            's' => $s,
            'kol' => $kol,
            'type' => array(1),
            'sday' => $sday_str,
            'eday' => $eday_str,
			'region' => $region_str,
			'price_min' => $price_min,
			'price_max' => $price_max,
			'people' => $people_str,
			'multi_location' => $multi_location,
			'location_type' => $location_type,
			'filter' => $filter,
			'not_lcsd' => 1
        );
		    
		$locationLevelPair = array();
		if (!empty($location_str)) {
			$ULocation = new ULocation();
			$locationLevelPairResults = $ULocation->getLocationLevelByIDs($location_str);
			if (!empty($locationLevelPairResults)) {
				foreach($locationLevelPairResults as $locationLevelPairResult){
					if(!isset($locationLevelPair[$locationLevelPairResult->location_level])){
						$locationLevelPair[$locationLevelPairResult->location_level] = array();
					}
					$locationLevelPair[$locationLevelPairResult->location_level][] = $locationLevelPairResult->location_id;
				}
			}
		}
		$standardConditions['locationLevelPairs'] = $locationLevelPair;
		
		$location_ordering = array();
		if(count($standardConditions['locationLevelPairs'][2]))
			$location_ordering = $standardConditions['locationLevelPairs'][2];
		
		$catLevelPair = array();
		if (!empty($cat_str)) {
			$UCategory = new UCategory();
			$catLevelPairResults = $UCategory->getCategoryLevelByIDs($cat_str);
			if (!empty($catLevelPairResults)) {
				foreach($catLevelPairResults as $catLevelPairResult){
					if(!isset($catLevelPair[$catLevelPairResult->cat_level])){
						$catLevelPair[$catLevelPairResult->cat_level] = array();
					}
					$catLevelPair[$catLevelPairResult->cat_level][] = $catLevelPairResult->cat_id;
				}
			}
		}
		$standardConditions['catLevelPairs'] = $catLevelPair;
		

        $contents = $model->search(array_merge($standardConditions, array(
            'offset' => $pagination->offset,
            'limit' => $pagination->limit
        )));
        $pagination->setTotal(uDb()->foundRows());
//debug(123);
        $utm_source = 'uhk';
		$utm_campaign = 'search-all';
		$x = 0;
		
		// Shift first LCSD article
		if($contents[0]->lcsd_id>0){
			foreach($contents as $k => $v) {
				if(empty($v->lcsd_id)) {
					$temp_content = $contents[$k];
					unset($contents[$k]);
					array_unshift($contents, $temp_content); 
					break;
				}
			}
		}
		
		
		$topContents = array();
		foreach($contents as $k=>$v){
			if($v->is_top > 0){
				$topContents[] = $v;
				unset($contents[$k]);
			}
		}
		if(!empty($topContents)){
			$contents = array_values($contents);
			foreach($topContents as $k=>$v){
				$tmpOrdering = 2 + ($k*2);   //this case start from 3
				array_splice($contents, (int)$tmpOrdering, 0, array($v));
		   
				$contents = array_values($contents);
			}
		}
		
		$meta_keyword_area = array();
		$meta_keyword_cat = array();
		$content_titles = array();
		
		foreach ($contents as &$content) {
			$pkey = "";
			$content_titles[] = trim($content->title);
            //find the related categories from a specific content
            $content->content = utf8_trim_text(trim_html_text($content->content), 35);
			$content->alt = $cover->title;
			$pkey = $content->pkey_name;
			
			if(($content->pagetype_id)==1){
				$content->alias = "activity";
				
				$during = UEvent::getDate($content->page_id);
				if($during){
					$content->during = UEvent::showStartEndDate($during->start_date,$during->end_date);
				}
			}
			
			//$content->imgURL = $this->createUrl('/'.$content->alias.'/detail', array('id' => url_encrypt($content->page_id),'utm_source' => $utm_source,'utm_medium' => 'img','utm_campaign' => $utm_campaign,'utm_content' => 'img'.$p.'-'.($x+1)));
			//$content->titleURL = $this->createUrl('/'.$content->alias.'/detail', array('id' => url_encrypt($content->page_id),'utm_source' => $utm_source,'utm_medium' => 'title','utm_campaign' => $utm_campaign,'utm_content' => 'title'.$p.'-'.($x+1)));
			
			$campaign_path = 'campaign/search2018/';
			$content->imgURL = $this->createUrl('/'.$content->alias.'/detail', array('id' => $content->page_id, 'url_title'=>$content->title));
			$content->titleURL = $this->createUrl('/'.$content->alias.'/detail', array('id' => $content->page_id, 'url_title'=>$content->title));
			
			$content->imgURL = str_replace($content->alias.'/detail', $campaign_path.$content->alias.'/detail', $content->imgURL);
			$content->titleURL = str_replace($content->alias.'/detail', $campaign_path.$content->alias.'/detail', $content->titleURL);
			
			$content->tags = $model->getContentTags($content->content_id);
			
			$content->author_name = utf8_trim_text($content->author_name,10);
			
			$content->imgPath = UContent::getImgPath($content->pagetype_id, UModel::IMGSIZE_RELATED);
			
			if(($content->pagetype_id)==UTour::PAGETYPE_ID){
				$UTour = new UTour();
				$cover = $UTour->getCoverPhotoByID($content->page_id);
				$cover_photo = $cover->cover_photo;
				if(!empty($cover_photo)){
					$content->cover_photo=UAPP_HOST . UAPP_BASE_URL . '/cms/images/tour/cover/'.CST_IMGSIZE_LISTING.'/'.$cover_photo;
					$content->cover_photo_w600 = UAPP_HOST . UAPP_BASE_URL . '/cms/images/tour/cover/'.CST_IMGSIZE_LISTING.'/'.$cover_photo;
					
					// check 480x270
					$cover_url_480x270 = UTour::getCoverPath2(UModel::IMGSIZE_RELATED_480_270) . $cover_photo;
					$cover_photo_file = str_replace(UAPP_HOST.UAPP_BASE_URL.'/','',$cover_url_480x270);
					if(file_exists($cover_photo_file)){
//						debug('480x270 tour_id: '.$v->page_id,0);
						$content->cover_photo = $cover_url_480x270;
					}
				}else{
					$content->cover_photo = UAPP_MEDIA_URL.'/images/global/default'.CST_IMGSIZE_LISTING.'.jpg';
				}
				
			}else{
				if(!empty($content->cover_photo)){
					$cover_photo =  $content->cover_photo;
					$content->cover_photo = $content->imgPath . $content->cover_photo;
					
					// check 480x270
					$cover_url_480x270 = UContent::getImgPath($content->pagetype_id, UModel::IMGSIZE_RELATED_480_270) . $cover_photo;
					$cover_photo_file = str_replace(UAPP_HOST.UAPP_BASE_URL.'/','',$cover_url_480x270);
					if(file_exists($cover_photo_file)){
//						debug('480x270 id: '.$v->page_id,0);
						$content->cover_photo = $cover_url_480x270;
					}
				}else{
					$content->cover_photo = UAPP_MEDIA_URL.'/images/global/default'.CST_IMGSIZE_LISTING.'.jpg';
				}
			}
			$locationArea = $model->getLocationAreaOrderbyLocationid($content->content_id,$location_ordering);
			
			$content->area_key = $locationArea[0]->area_key;
			$content->area_name= $locationArea[0]->area_name;
			
			// location tag
			if($content->content_id){
				$locations = UContent::getLocationArea($content->content_id,10);
				$content->location_tag = getLocationHtmlTag($locations);
			}

			$x++;
			
			if($content->area_name && !in_array($content->area_name,$meta_keyword_area)){
				$meta_keyword_area[] = $content->area_name;
			}
			if($content->main_subcat && !in_array($content->main_subcat,$meta_keyword_cat)){
				$meta_keyword_cat[] = $content->main_subcat;
			}
        }
        $this->assign('contents', $contents);
		
//debug($contents);
		

		$paginationConditions = array(
            'q' => stripslashes($q),
            'type' => $type_str,
            'tag' => $tag_str,
            's' => $s,
			'cat' => $cat_str,
            'kol' => $kol_str,
			'location' => $location_str,
			'region' => $region_str,
			'sday' => $sday,
			'eday' => $eday,
			'price_min' => $price_min,
			'price_max' => $price_max,
			'people' => $people_str,
			'filter_type' => $filter_type_str,
			'filter_cat' => $filter_cat_str,
			'filter_loc' => $filter_loc_str,
			'filter_kol' => $filter_kol_str
        );
        $pagination->getUrl = $this->createUrl('/search/index', $paginationConditions) . '&p=';

        $pagination->renderPages();
        $this->assign("pagination", $pagination);
		

		$conditions = array(
                    'q' => stripslashes($q),
                    'tag' => $tag_str,
//					'p' => $p,
                    's' => $s,
					'kol' => $kol_str,
					'type' => $type_str,
					'cat' => $cat_str,
					'location' => $location_str,
					'region' => $region_str,
					'listing' => $listing,
					'sday' => $sday,
					'eday' => $eday,
					'price_min' => $price_min,
					'price_max' => $price_max,
					'people' => $people_str,
					'multi_location' => $multi_location,
					'location_type' => $location_type,
					'filter_type' => $filter_type_str,
					'filter_cat' => $filter_cat_str,
					'filter_loc' => $filter_loc_str,
					'filter_kol' => $filter_kol_str
        );
		//$this->logSearch($conditions);
		
		$conditions = (object)$conditions;
        $this->assign('conditions', $conditions);
		//debug($conditions->filter_loc);

		
				

		//$word_count =0;
		$search_title = '搜尋結果';
		
		if($q){
			$content_titles = array_merge(array($q),$content_titles);
			$search_title = $q.'的搜尋結果';
		}
		
		switch($conditions->type){
			case UTopic::PAGETYPE_ID:
				$search_title = '本地熱話';
				$this->metaOptions['canonicalUrl'] = $this->createUrl('/topic/index');
			break;
			case UTour::PAGETYPE_ID:
				$search_title = '周圍遊';
				$this->metaOptions['canonicalUrl'] = $this->createUrl('/tour/index');
			break;
		}

		$metaDescription = implode('|',$content_titles);
		//$metaDescription = utf8_trim_text($metaDescription, 80);
		$metaDescription = mb_substr($metaDescription, 0, 79, 'UTF-8');
		//$metaDescription .= '...';
		$this->metaDescription = getMetaDescription($metaDescription);
		
		$this->assign('search_title', $search_title);
		$this->assign('page_type', $title_type);
		$this->pageTitle = getMetaTitle($search_title);	
		
		$this->addCss('css/hk-search.css');
		$this->addCss('css/hk-result.css');
		//$this->addCss('css/global/webwidget_slideshow_dot.css');
		//$this->addCss('css/hk-search-article-list.css?v=2');
		//$this->addCss('css/hk-search-box.css');
		//$this->addCss('css/global/common_ui.css?v=20170206');
        //
		//$this->addJs('js/uhk-main.js');
		$this->layout='responsiveHtml';
		$this->display();

    }
}
?>