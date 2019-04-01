<?php
defined( 'UFM_RUN' ) or die( 'No direct script access allowed.' );
class CampaignSearch2018ListinglazyController extends UController
{
	function getCatByCatid($cat_id) {
		$sql='SELECT cat_id, cat_name FROM tbl_cat
			WHERE cat_id IN ('.$cat_id.') 
			LIMIT 1';
        return uDb()->findOne($sql);
    }
	function getTagNames($tag_ids_str){
		$resultNames = array();
        if (!empty($tag_ids_str)) {
		$sql = 'SELECT tag_id, tag_name
				FROM tbl_tags
				WHERE tag_id IN ('.$tag_ids_str.')
				';
		$tags = uDb()->findList($sql);
			foreach($tags as $tag){
				$resultNames[] = $tag->tag_name;
			}
        }
		
        return implode(', ', $resultNames);
	}
	function getAuthorNames($author_ids_str) {
        $resultNames = array();
        if (!empty($author_ids_str)) {
            $sql = "SELECT author_name " .
                    "FROM tbl_author " .
                    "WHERE published = 1 " .
                    "AND author_id IN (" . $author_ids_str . ") 
					ORDER BY author_name ASC";
            
            $authors = uDb()->findList($sql);
			foreach($authors as $author){
				$resultNames[] = $author->author_name;
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
		return uDb()->findList($sql);
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
		$keyword = $q = isset($_REQUEST['q']) ? urldecode($_REQUEST['q']) : '';
		$tag = isset($_REQUEST['tag'])&&!is_array($_REQUEST['tag'])&&strlen($_REQUEST['tag'])>0&&$_REQUEST['tag']>0?self::to_id_array($_REQUEST['tag']):array();
		$p = isset($_REQUEST['p']) ? (int) $_REQUEST['p'] : 1;
		$s = (isset($_REQUEST['s']) && ($_REQUEST['s'] == UContent::SORTBY_HIT || $_REQUEST['s'] == UContent::SORTBY_RATING || $_REQUEST['s'] == UContent::SORTBY_DATE || $_REQUEST['s'] == UContent::SORTBY_HIT_WEEK)) ? $_REQUEST['s'] : UContent::SORTBY_DATE;
		$cat = isset($_REQUEST['cat'])&&!is_array($_REQUEST['cat'])&&strlen($_REQUEST['cat'])>0?self::to_id_array($_REQUEST['cat']):array();
		$location_ids = isset($_REQUEST['location'])&&!is_array($_REQUEST['location'])&&strlen($_REQUEST['location'])>0&&$_REQUEST['location']>0?self::to_id_array($_REQUEST['location']):array();
		$multi_location = isset($_REQUEST['multi_location']) ? (int) $_REQUEST['multi_location'] : 0;
		$location_type = isset($_REQUEST['location_type']) ? (int) $_REQUEST['location_type'] : 0;
		$region_ids = isset($_REQUEST['region'])&&!is_array($_REQUEST['region'])&&strlen($_REQUEST['region'])>0&&$_REQUEST['region']>0?self::to_id_array($_REQUEST['region']):array();
        /* $district_key = isset($_REQUEST['district_key'])&&!is_array($_REQUEST['district_key'])&&strlen($_REQUEST['district_key'])>0&&$_REQUEST['district_key']>0?self::to_id_array($_REQUEST['district_key']):array();
        $area_key = isset($_REQUEST['area_key'])&&!is_array($_REQUEST['area_key'])&&strlen($_REQUEST['area_key'])>0&&$_REQUEST['area_key']>0?self::to_id_array($_REQUEST['area_key']):array();
        $landmark_key = isset($_REQUEST['landmark_key'])&&!is_array($_REQUEST['landmark_key'])&&strlen($_REQUEST['landmark_key'])>0&&$_REQUEST['landmark_key']>0?self::to_id_array($_REQUEST['landmark_key']):array();
		 */
		$type = isset($_REQUEST['type'])&&!is_array($_REQUEST['type'])&&strlen($_REQUEST['type'])>0&&$_REQUEST['type']>0?self::to_id_array($_REQUEST['type']):array();
		$kol = isset($_REQUEST['kol'])&&!is_array($_REQUEST['kol'])&&strlen($_REQUEST['kol'])>0&&$_REQUEST['kol']>0?self::to_id_array($_REQUEST['kol']):array();
		
		$sday = isset($_REQUEST['sday'])&&is_numeric($_REQUEST['sday'])&&strlen($_REQUEST['sday'])==8?$_REQUEST['sday']:null;
		$eday = isset($_REQUEST['eday'])&&is_numeric($_REQUEST['eday'])&&strlen($_REQUEST['eday'])==8?$_REQUEST['eday']:null;
		$price_min = isset($_REQUEST['price_min'])&&is_numeric($_REQUEST['price_min'])?$_REQUEST['price_min']:0;
		$price_max = isset($_REQUEST['price_max'])&&is_numeric($_REQUEST['price_max'])?$_REQUEST['price_max']:800;
		$people = isset($_REQUEST['people'])&&!is_array($_REQUEST['people'])&&strlen($_REQUEST['people'])>0&&$_REQUEST['people']>0?self::to_id_array($_REQUEST['people']):array();

		
		$filter_type = isset($_REQUEST['filter_type'])&&!is_array($_REQUEST['filter_type'])&&strlen($_REQUEST['filter_type'])>0&&$_REQUEST['filter_type']>0?self::to_id_array($_REQUEST['filter_type']):array();
		$filter_kol = isset($_REQUEST['filter_kol'])&&!is_array($_REQUEST['filter_kol'])&&strlen($_REQUEST['filter_kol'])>0&&$_REQUEST['filter_kol']>0?self::to_id_array($_REQUEST['filter_kol']):array();
		$filter_loc = isset($_REQUEST['filter_loc'])&&!is_array($_REQUEST['filter_loc'])&&strlen($_REQUEST['filter_loc'])>0&&$_REQUEST['filter_loc']>0?self::to_id_array($_REQUEST['filter_loc']):array();
		$filter_cat = isset($_REQUEST['filter_cat'])&&!is_array($_REQUEST['filter_cat'])&&strlen($_REQUEST['filter_cat'])>0&&$_REQUEST['filter_cat']>0?self::to_id_array($_REQUEST['filter_cat']):array();

		$model = new UContent();
		
		
		$eday_str = $eday?substr($eday,0,4).'-'.substr($eday,4,2).'-'.substr($eday,6):null;
		$sday_str = $sday?substr($sday,0,4).'-'.substr($sday,4,2).'-'.substr($sday,6):null;
		


		/* 主題內容 */
		$UTheme = new UTheme();
		$locationModel = new ULocation();
		
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
		$locationNames = $locationModel->getLocationNames($location_str);
		$this->assign("locationNames",$locationNames);
		
		$region_str = join(',',$region_ids);
		$region_str = empty($region_str)||$region_str=='0'?null:$region_str;
		
		$categoryModel = new UCategory();
		$cat_str = join(',',$cat);
		$cat_str = empty($cat_str)||$cat_str=='0'?null:$cat_str;
		$catNames = $categoryModel->getCatNames($cat_str);
		$this->assign("catNames",$catNames);
		
		if(count($cat)){
			$search_cat = $this->getCatByCatid($cat[0]);
			$this->assign("search_cat",$search_cat);
		}
		
		//$authorModel = new UAuthor();
		$kol_str = join(',',$kol);
		$kol_str = empty($kol_str)||$kol_str=='0'?null:$kol_str;
		$authorNames = $this->getAuthorNames($kol_str);
		$this->assign("authorNames",$authorNames);
		
		$tag_str = join(',',$tag);
		$tag_str = empty($tag_str)||$tag_str=='0'?null:$tag_str;
		$tagNames = $this->getTagNames($tag_str);
		$this->assign("tagNames",$tagNames);
		
		
		
		$type_str = join(',',$type);
		$type_str = empty($type_str)||$type_str=='0'?null:$type_str;
		
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
			'price_min' => $price_min,
			'price_max' => $price_max,
			'people' => $people_str,
			'region' => $region_str,
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
		
		foreach ($contents as &$content) {
			$pkey = "";
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
			
			if(!isset($content->author_photo) || trim($content->author_photo)==='')
				$content->author_photo = UAPP_MEDIA_URL.'/images/global/profile.png';
			else
				$content->author_photo = UAPP_HOST . UAPP_BASE_URL . '/cms/images/author/120x120/'.$content->author_photo;
			
			$content->imgPath = UContent::getImgPath($content->pagetype_id, UModel::IMGSIZE_RELATED);
			
			if(($content->pagetype_id)==UTour::PAGETYPE_ID){
				$UTour = new UTour();
				$cover = $UTour->getCoverPhotoByID($content->page_id);
				$cover_photo = $cover->cover_photo;
				if(!empty($cover_photo)){
					$content->cover_photo=UAPP_HOST . UAPP_BASE_URL . '/cms/images/tour/cover/'.CST_IMGSIZE_LISTING.'/'.$cover_photo;
					
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
			
			if($isLogin){
				$content->isFollowed = $this->user->isFollowed($content->pagetype_id, $content->page_id);
			}
			
			$x++;
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
		

		$conditions = (object) array(
                    'q' => stripslashes($q),
                    'tag' => $tag_str,
					'p' => $p,
                    's' => $s,
					'kol' => $kol_str,
					'type' => $type_str,
					'cat' => $cat_str,
					'location' => $location_str,
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
        $this->assign('conditions', $conditions);
		//debug($conditions->filter_loc);


		$this->layout='directHtmlLazy';
		$this->display();

    }
}
?>