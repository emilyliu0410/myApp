<?php

defined('UFM_RUN') or die('No direct script access allowed.');

class AdvancedsearchIndexController extends UController {
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
		$keyword = $q = isset($_REQUEST['q']) ? urldecode($_REQUEST['q']) : '';
		if($keyword=='NOEL！2016聖誕特集') header("Location: ".$this->createUrl('/theme/detail',array('id'=>'ABtGCVoyBw4')));
		
		$tag = isset($_REQUEST['tag'])&&!is_array($_REQUEST['tag'])&&strlen($_REQUEST['tag'])>0&&$_REQUEST['tag']>0?self::to_id_array($_REQUEST['tag']):array();
		$p = isset($_REQUEST['p']) ? (int) $_REQUEST['p'] : 1;
		$s = (isset($_REQUEST['s']) && ($_REQUEST['s'] == UContent::SORTBY_HIT || $_REQUEST['s'] == UContent::SORTBY_RATING || $_REQUEST['s'] == UContent::SORTBY_DATE )) ? $_REQUEST['s'] : UContent::SORTBY_DATE;
		$cat = isset($_REQUEST['cat'])&&!is_array($_REQUEST['cat'])&&strlen($_REQUEST['cat'])>0?self::to_id_array($_REQUEST['cat']):array();
		$location_ids = isset($_REQUEST['location'])&&!is_array($_REQUEST['location'])&&strlen($_REQUEST['location'])>0&&$_REQUEST['location']>0?self::to_id_array($_REQUEST['location']):array();
		$region_ids = isset($_REQUEST['region'])&&!is_array($_REQUEST['region'])&&strlen($_REQUEST['region'])>0&&$_REQUEST['region']>0?self::to_id_array($_REQUEST['region']):array();
		$type = isset($_REQUEST['type'])&&!is_array($_REQUEST['type'])&&strlen($_REQUEST['type'])>0&&$_REQUEST['type']>0?self::to_id_array($_REQUEST['type']):array();
		$kol = isset($_REQUEST['kol'])&&!is_array($_REQUEST['kol'])&&strlen($_REQUEST['kol'])>0&&$_REQUEST['kol']>0?self::to_id_array($_REQUEST['kol']):array();
		
		$listing = isset($_REQUEST['listing']) ? (int) $_REQUEST['listing'] : 0;
		
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
		$locationModel = new ULocation();
		
		$isLogin = $this->user->isLogin();
		$this->assign('isLogin',$isLogin);
		
		
		$this->assign("selected_cat",$cat);

		$location_str = join(',',$location_ids);
		$location_str = empty($location_str)||$location_str=='0'?null:$location_str;
		//$locationNames = $locationModel->getLocation($location_str);
		//$this->assign("locationNames",$locationNames);
		$locations = $this->getLocations($location_str);
		$this->assign("locations",$locations);
		
		$region_str = join(',',$region_ids);
		$region_str = empty($region_str)||$region_str=='0'?null:$region_str;
		$regionNames = $locationModel->getRegionNames($region_str);
		$this->assign("regionNames",$regionNames);
		
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
					'price_max' => $price_max,
					'price_min' => $price_min,
					'filter_type' => $filter_type_str,
					'filter_cat' => $filter_cat_str,
					'filter_loc' => $filter_loc_str,
					'filter_kol' => $filter_kol_str
        );
		$this->logSearch($conditions);
		
		$conditions = (object)$conditions;
        $this->assign('conditions', $conditions);
		//debug($conditions->filter_loc);
		
		
		
		
		
		
		
		
		$catModel = new UCategory();
		
        $eat_cats = $catModel->getSubcat(1);
		
        $buy_cats = $catModel->getSubcat(2);
		
        $play_cats = $catModel->getSubcat(3);
		
        $cats = array(1=>$eat_cats,2=>$buy_cats,3=>$play_cats);
        $this->assign('cats', $cats);
		
		
		
		$hk_regions=ULocation::getRegionByDistrictID(1);
		foreach($hk_regions as $key=>$val){
			$area_names = ULocation::getAreaNamesByRegion($val->region_id,'、');
			$hk_regions[$key]->area_names = $area_names;
			$hk_regions[$key]->selected = (in_array($val->region_id,$region_ids)?1:0);
		}
		$kl_regions=ULocation::getRegionByDistrictID(2);
		foreach($kl_regions as $key=>$val){
			$area_names = ULocation::getAreaNamesByRegion($val->region_id,'、');
			$kl_regions[$key]->area_names = $area_names;
			$kl_regions[$key]->selected = (in_array($val->region_id,$region_ids)?1:0);
		}
		$nt_regions=ULocation::getRegionByDistrictID(3);
		foreach($nt_regions as $key=>$val){
			$area_names = ULocation::getAreaNamesByRegion($val->region_id,'、');
			$nt_regions[$key]->area_names = $area_names;
			$nt_regions[$key]->selected = (in_array($val->region_id,$region_ids)?1:0);
		}
		
		$island_regions=ULocation::getRegionByDistrictID(4);
		foreach($island_regions as $key=>$val){
			$area_names = ULocation::getAreaNamesByRegion($val->region_id,'、');
			$island_regions[$key]->area_names = $area_names;
			$island_regions[$key]->selected = (in_array($val->region_id,$region_ids)?1:0);
		}
		
		$districts = array(array('district_key'=>'1','district_name'=>'香港島','regions'=>$hk_regions),
							array('district_key'=>'2','district_name'=>'九龍','regions'=>$kl_regions),
							array('district_key'=>'3','district_name'=>'新界','regions'=>$nt_regions));
        $this->assign('districts', $districts);
		
		 $this->assign('island_regions', $island_regions);

		//debug($districts);
		
		$people = array(array('id'=>1,'name'=>'single','zh_name'=>'一個人','image'=>'/images/global/character-face-25.svg','selected'=>(in_array(1,$people)?1:0)),
						array('id'=>2,'name'=>'couple','zh_name'=>'情侶','image'=>'/images/global/character-face-26.svg','selected'=>(in_array(2,$people)?1:0)),
						array('id'=>3,'name'=>'family','zh_name'=>'親子','image'=>'/images/global/character-face-27.svg','selected'=>(in_array(3,$people)?1:0)),
						array('id'=>4,'name'=>'friend','zh_name'=>'三五知己','image'=>'/images/global/character-face-29.svg','selected'=>(in_array(4,$people)?1:0)),
						array('id'=>5,'name'=>'group','zh_name'=>'一大班人','image'=>'/images/global/character-face-28.svg','selected'=>(in_array(5,$people)?1:0))
						); 
		$this->assign('people', $people);


		$this->pageTitle = getMetaTitle('香港活動搜尋');
		


		//Ad Banner
		$fixed1 = uGetAdItem('div-gpt-ad-1472555430140-1');
		$fixed2 = uGetAdItem('div-gpt-ad-1472555430140-2');
		$fixed3 = uGetAdItem('div-gpt-ad-1472555430140-3');

		$this->assign("topBanner",$fixed1);
		$this->assign("largeRectangle1Banner",$fixed2);
		$this->assign("largeRectangle2Banner",$fixed3);
		
		$this->addCss('css/hk-search.css');
		$this->addCss('css/hk-result.css');
		$this->addCss('library/OwlCarousel2-2.2.1/dist/assets/owl.carousel.css');
		$this->addCss('library/OwlCarousel2-2.2.1/dist/assets/owl.theme.default.css');
		//$this->addCss('css/global/costRange/nouislider.css');
		$this->addCss('library/costRange/nouislider.css');
		//$this->addCss('css/global/webwidget_slideshow_dot.css');
		//$this->addCss('css/hk-search-article-list.css?v=2');
		//$this->addCss('css/hk-search-box.css');
		//$this->addCss('css/global/common_ui.css?v=20170206');
        //
		//$this->addJs('js/uhk-main.js');
		$this->addJs('js/search.js');
		$this->addJs('js/searchSelection.js');
		$this->addJs('library/OwlCarousel2-2.2.1/dist/owl.carousel.js');
		$this->addJs('library/costRange/nouislider.js');
		$this->addJs('js/global/costRange-control.js');
		$this->layout='responsive2';
		$this->display();

    }
}