<?php
defined( 'UFM_RUN' ) or die( 'No direct script access allowed.' );

class ActivityListController extends UController
{
	function validateDate($date, $format = 'Y-m-d H:i:s')
	{
		$d = DateTime::createFromFormat($format, $date);
		return $d && $d->format($format) == $date;
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
	
	function actionIndex()
	{	
		$this->redirect301('/index');
		
		$keyword		= isset($_REQUEST['q'])?htmlspecialchars(stripslashes($_REQUEST['q'])):null;
		$page			= isset($_REQUEST['p'])&&(int)$_REQUEST['p']>0?(int)$_REQUEST['p']:1;	
		$tag			= isset($_REQUEST['tag'])&&is_numeric($_REQUEST['tag'])?(int)$_REQUEST['tag']:null;
		$sday			= isset($_REQUEST['sday'])&&is_numeric($_REQUEST['sday'])&&strlen($_REQUEST['sday'])==8?$_REQUEST['sday']:null;
		$eday			= isset($_REQUEST['eday'])&&is_numeric($_REQUEST['eday'])&&strlen($_REQUEST['eday'])==8?$_REQUEST['eday']:null;
		$dist 			= isset($_REQUEST['dist'])&&!is_array($_REQUEST['dist'])&&strlen($_REQUEST['dist'])>0&&$_REQUEST['dist']>0?$this->to_id_array($_REQUEST['dist']):array();
		$area 			= isset($_REQUEST['area'])&&!is_array($_REQUEST['area'])&&strlen($_REQUEST['area'])>0&&$_REQUEST['area']>0?$this->to_id_array($_REQUEST['area']):array();		
		$cat			= isset($_REQUEST['cat'])&&!is_array($_REQUEST['cat'])&&strlen($_REQUEST['cat'])>0?$this->to_id_array($_REQUEST['cat']):array();
		/* $cat2			= isset($_REQUEST['cat2'])&&is_numeric($_REQUEST['cat2'])?(int)$_REQUEST['cat2']:null;
		$cat3			= isset($_REQUEST['cat3'])&&is_numeric($_REQUEST['cat3'])?(int)$_REQUEST['cat3']:null; */
		$sort			= isset($_REQUEST['s'])&&in_array($_REQUEST['s'],array(UEvent::SORTBY_DATE,UEvent::SORTBY_HIT,UEvent::SORTBY_RATING))?$_REQUEST['s']:UEvent::SORTBY_DATE;
		$location 	= isset($_REQUEST['location'])&&!is_array($_REQUEST['location'])&&strlen($_REQUEST['location'])>0&&$_REQUEST['location']>0?$this->to_id_array($_REQUEST['location']):array();		
    
    if(empty($location) && (!empty($dist) || !empty($area))){
        $locationModel = new ULocation();
        $location = explode(',', $locationModel->convetDistrictsAreasToLocations(implode(',', $dist), implode(',', $area)));
    }
    
		//debug($dist);
		$pagination = new UPagination(array(
			'page' => $page,
			'limit' => 13,
			'prevLabel'=> '<img src="'.UAPP_MEDIA_URL.'/images/global/arrow-prev.png" width="8" height="12" style="display:inline-block"/>',
		    'lastLabel'	=> '<img src="'.UAPP_MEDIA_URL.'/images/global/arrow-next-end.png" width="12" height="12" style="display:inline-block"/>',
		    'firstLabel'	=> '<img src="'.UAPP_MEDIA_URL.'/images/global/arrow-prev-end.png" width="12" height="12" style="display:inline-block"/>',
		    'nextLabel'	=> '<img src="'.UAPP_MEDIA_URL.'/images/global/arrow-next.png" width="8" height="12" style="display:inline-block"/>'
		));
		
		$model= new UEvent();
		
		$locationModel = new ULocation();
		$dist_str = join(',',$dist);
		$area_str = join(',',$area);
		$location_str = join(',',$location);
		$dist_str = empty($dist_str)||$dist_str=='0'?null:$dist_str;
		$area_str = empty($area_str)||$area_str=='0'?null:$area_str;
		$location_str = empty($location_str)||$location_str=='0'?null:$location_str;
		$districtNames = $locationModel->getLocationNames($location_str);
		$this->assign("districtNames",$districtNames);
		
		$categoryModel = new UCategory();
		$cat_str = join(',',$cat);
		$cat_str = empty($cat_str)||$cat_str=='0'?null:$cat_str;
		$catNames = $categoryModel->getCatNames($cat_str);
		$this->assign("catNames",$catNames);
		
		$tagName = !empty($tag) ? $model->getTagName($tag)->tag_name : '';
		$this->assign("tagName",$tagName);
		
		$url_parms = array(
							"cat"=>$cat_str,
							/* "cat2"=>$cat2,
							"cat3"=>$cat3, */
							"s"=>$sort,
							"sday"=>$sday,
							"eday"=>$eday,
							"q"=>$keyword,
							"tag"=>$tag,
//							"dist"=>$dist_str,
//							"area"=>$area_str,
							"location"=>$location_str
							);

		//debug($url_parms,0);
	
		$this->assign("url_parms",$url_parms);

		$eday 	= $eday?substr($eday,0,4).'-'.substr($eday,4,2).'-'.substr($eday,6):null;
		$sday 	= $sday?substr($sday,0,4).'-'.substr($sday,4,2).'-'.substr($sday,6):null;

		$filter = array('keyword'=>$keyword,
						'offset'=>$pagination->offset,
						'limit'=>$pagination->limit,
//						'dist'=>$dist,
//						'area'=>$area,
						'sday'=>$sday,
						'eday'=>$eday,
						/* "cat"=>$cat,
						"cat2"=>$cat2,
						"cat3"=>$cat3, */
						'sort'=>$sort,
						'tag'=>$tag
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
    $filter['locationLevelPairs'] = $locationLevelPair;  
	
    $catLevelPair = array();
    if (!empty($cat_str)) {
        $UCategory = new UCategory();
        $catLevelPairResults = $UCategory->getcategoryLevelByIDs($cat_str);
        if (!empty($catLevelPairResults)) {
            foreach($catLevelPairResults as $catLevelPairResult){
                if(!isset($catLevelPair[$catLevelPairResult->cat_level])){
                    $catLevelPair[$catLevelPairResult->cat_level] = array();
                }
                $catLevelPair[$catLevelPairResult->cat_level][] = $catLevelPairResult->cat_id;
            }
        }
    }
    $filter['catLevelPairs'] = $catLevelPair;

/* 		//debug($test=http_build_query($filter),0);
		
		parse_str($test, $output);
		//debug($output); */
		
		$items = $model->search($filter);
		$pagination->setTotal(uDb()->foundRows());
		$pagination->getUrl = $this->createUrl('/activity/list', $url_parms).'&p=';
		//$pagination->renderPages(array('showFirstLast'=>true));
		$this->assign("pagination",$pagination);
		
		//$eventCount = UDb()->foundRows();
		//$this->assign('eventCount',$eventCount);
		//debug($pagination,0);
		
		foreach($items as $k=>$v){
			$during = $model->getDate($v->event_id);
			if($during){
				$items[$k]->during 	= $model->showStartEndDate($during->start_date,$during->end_date);
			}
			$items[$k]->categories 	= $model->getEventCatsById($v->event_id);
			$photo	= $model->getCoverPhoto($v->event_id);
			$cover_photo = $model->getImgPath(UEvent::IMGSIZE_LISTING).str_replace(' ', '%20',$photo->cover_photo);
			/* $imagesize=getimagesize($cover_photo);
			$items[$k]->cover_photo	= ($imagesize>0)?$cover_photo:$model->getImgPath(UEvent::IMGSIZE_LISTING).UEvent::DEFAULT_COVER;
			$items[$k]->alt	= $photo->alt;
			$items[$k]->width	= 300;
			$resized_height = $photo->height/($photo->width/$items[$k]->width);
			$items[$k]->height	= ($imagesize>0)?$resized_height:409; */

			$items[$k]->cover_photo	= $cover_photo;
			$items[$k]->alt	= $photo->alt;
			$items[$k]->width	= 300;
			$resized_height = $photo->height/($photo->width/$items[$k]->width);
			$items[$k]->height	= ($resized_height>0)?$resized_height:409;
			
			$defaultContent = $model->getDefaultContent($v->event_id);
			$items[$k]->content = !empty($defaultContent)&&!empty($defaultContent->content)?$defaultContent->content:' ';
			$items[$k]->rating = UEvent::getRatingHtml($v->avg_rating);
			$items[$k]->isEnd = $model->isEventEnd($v->event_id);
			$items[$k]->areas = $model->getEventAreas($v->event_id);
			$medium = array('img'=>'img','title'=>'title');
			$items[$k]->urlFormImg = $this->createUrl('/activity/detail',array('id'=>url_encrypt($v->event_id),'utm_source'=>'uhk','utm_medium'=>$medium['img'],'utm_campaign'=>'activity-list','utm_content'=>$medium['img'].$page.'-'.($k+1)));
			$items[$k]->urlFormTitle = $this->createUrl('/activity/detail',array('id'=>url_encrypt($v->event_id),'utm_source'=>'uhk','utm_medium'=>$medium['title'],'utm_campaign'=>'activity-list','utm_content'=>$medium['title'].$page.'-'.($k+1)));
			
			//$content = $model->getDefaultContent($v->event_id);
			//$items[$k]->content  = null;//!empty($content)?utf8_trim_text(trim_html_text($content->content), 60):null;
			//$items[$k]->content	= utf8_trim_text(trim_html_text($v->content), 60);
		}

		$this->assign('items',$items);
		//debug($items,0);
		
		
		/* 已選地區 */
		/* $selected_districts = $model->getDistricts($dist);
		$this->assign('selected_districts',$selected_districts);
		$selected_area = $model->getDistrictAreas($area);
		$this->assign('selected_area',$selected_area); */
		
		/* 地區 */
		/* $districts = $model->listDistricts();
		foreach($districts as $k=>$v){
			$areas = $model->listDistrictAreas($v->district_id);
			$districts[$k]->area = $areas;
		}
		$this->assign('districts',$districts); */
		
		/* 活動分類 */
		/* $filter = array('cat_type_id'=>1,'event_flag'=>1);
		$event_cats = $model->listCategories($filter);
		$this->assign('event_cats',$event_cats); */
		//debug($event_cats,0);
		
		/* 對象分類 */
		/* $filter = array('cat_type_id'=>2,'event_flag'=>1);
		$target_cats = $model->listCategories($filter);
		$this->assign('target_cats',$target_cats); */
		//debug($target_cats,0);
		
		/* 性質分類 */
		/* $filter = array('cat_type_id'=>3,'event_flag'=>1);
		$nature_cats = $model->listCategories($filter);
		$this->assign('nature_cats',$nature_cats); */
		//debug($target_cats,0);
			
		//get the hottest tags for the filter form
        $hottestTags = $model->getHottestTagList(5);
        $this->assign('hottestTags', $hottestTags);
		
		// rectangle banner
		/* $banner_300_250 = getAdZone(2568,300,250,'abeda234',1);
		$this->assign("banner_300_250",$banner_300_250);
		$banner_300_100 = getAdZone(2569,300,100,'a458b99a',1);
		$this->assign("banner_300_100",$banner_300_100); */
		
		//Ad Banner
		//if($keyword==null&&$tag==null&&$sday==null&&$eday==null&&$dist==null&&$area==null&&$cat==null&&$cat2==null&&$cat3==null){
		
		
		$superbanner1 = uGetAdItem('div-gpt-ad-1429860922816-6');
		$superbanner2 = uGetAdItem('div-gpt-ad-1429860922816-7');
		$skyscraper1 = uGetAdItem('div-gpt-ad-1429860922816-4');
		$skyscraper2 = uGetAdItem('div-gpt-ad-1429860922816-5');
		
		$this->assign("topBanner",$superbanner1);
		$this->assign("bottomBanner",$superbanner2);
		$this->assign("skyscraperLeftBanner",$skyscraper1);
		$this->assign("skyscraperRightBanner",$skyscraper2);
		
		if(empty($keyword)&&empty($tag)&&empty($sday)&&empty($eday)&&empty($dist)&&empty($area)&&empty($cat)&&empty($cat2)&&empty($cat3)){
			$babybanner1 = uGetAdItem('div-gpt-ad-1429860922816-0');
			$babybanner2 = uGetAdItem('div-gpt-ad-1429860922816-1');
			$lrec1 = uGetAdItem('div-gpt-ad-1429860922816-2');
			$lrec2 = uGetAdItem('div-gpt-ad-1429860922816-3');
			
			$this->assign("largeRectangle1Banner",$lrec1);
			$this->assign("largeRectangle2Banner",$lrec2);
			$this->assign("babyBanner1",$babybanner1);
			$this->assign("babyBanner2",$babybanner2);
		}
		
		//$this->layout = 'column2';
		
		
		$this->addCss('css/global/form.css');
		$this->addCss('css/global/uhk-datepicker.css');
		$this->addCss('css/global/uhk-thickbox-3.1.css');
		$this->addCss('css/grid/main.css');
		$this->addCss('css/uhk-activities-list.css?v=3');
		$this->addJs('js/global/datepicker.js');
		$this->addJs('js/global/custom-form-elements.js');
		$this->addJs('js/grid/jquery.wookmark.js');
		$this->addJs('js/uhk-activity-list.js?v=1');
		$this->addJs('js/global/uhk-thickbox-3.1.js?v=3');
		
		$metaTitleFilter = array( 'default'=>'港活動',
								  'keyword'=>$keyword,
								  'tag'=>$tag,
								  'cats'=>$cat
								);
		$listPageMetaTitle = $model->getListPageMetaTitle($metaTitleFilter);
 		$this->pageTitle = getMetaTitle($listPageMetaTitle);
 		$this->metaKeywords = getMetaKeywords('U HK, 活動, 香港, 演唱會, 表演, 展覽, 著數, 體驗, 商場, 飲食, 運動, 文娛, 節日, 優惠, 免費');
		$this->metaDescription = getMetaDescription('U HK港活動提供全港各類型消閒活動，包括演唱會、表演、展覽、著數、免費體驗、商場資訊、飲食、運動、文娛康樂等活動。'); 

		/*$coverPhoto ='/demo/'.$item->photo;*/
		$this->display();

		
	}
}
