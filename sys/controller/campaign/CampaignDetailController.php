<?php

defined('UFM_RUN') or die('No direct script access allowed.');

class CampaignDetailController extends UController
{

    function actionIndex()
    {
        $id = $_id = isset($_REQUEST['id']) ? url_decrypt($_REQUEST['id']) : 0;
		$infinite_scroll = isset($_REQUEST['scroll']) ? (int) $_REQUEST['scroll'] : 0;
		$exclude_ids = isset($_GET['exclude']) ? $_GET['exclude'] : null;
		if($exclude_ids){
			$exclude_ids_arr = explode(',',$exclude_ids);
		}

        if ($this->fm->articleId)
            $id = $this->fm->articleId;

        if ($id <= 0) {
            $this->redirect('/error');
        }

        $model = new UCampaign();
        $campaignDetail = $model->getCampaignDetail($id);
		
		if(count($exclude_ids_arr)<5){
			$exclude_ids_arr[] = $id;
			$exclude_ids = implode(',',$exclude_ids_arr);
			$next_campaign = $model->getCampaigns(array(
				'need_active'=>1,
				'exclude_ids_arr'=>$exclude_ids_arr,
				'offset' => 0,
				'limit' => 1
			));

			if($next_campaign[0]->campaign_id){
				
				$next_campaign_id = $next_campaign[0]->campaign_id;
				$next_campaign_url = $this->createUrl('/campaign/detail', array('id'=>$next_campaign_id)).'?scroll=1&exclude='.$exclude_ids;
				$next_campaign_title = getMetaTitle($next_campaign[0]->campaign_name);
				$this->assign('next_campaign_url', $next_campaign_url);
				$this->assign('next_campaign_id',$next_campaign_id);
				$this->assign('next_campaign_title',$next_campaign_title);
				
				$inline_datas[] = 'data-next-id="'.$next_campaign_id.'"';
				$inline_datas[] = 'data-next-url="'.$next_campaign_url.'"';
				$inline_datas[] = 'data-next-title="'.$next_campaign_title.'"';
			}
		}
		
		$inline_datas[] = 'data-title="'.getMetaTitle($campaignDetail->campaign_name).'"';
		$inline_datas[] = 'data-page-id="'.$id.'"';
		$inline_datas[] = 'data-page-url="'.$this->createUrl('/campaign/detail',array('id'=>$id,'url_title'=>$campaignDetail->campaign_name),false).'"';
		
		$this->assign('inline_datas',$inline_datas);

        if ($_id > 0 && ENABLE_SEO_FRIENDLY) {
            parse_str($_SERVER['QUERY_STRING'], $parameters);
            unset($parameters['id']);
            $parameters = array_merge(array('id' => $_id, 'url_title' => $campaignDetail->campaign_name), $parameters);
            $this->redirect301('/campaign/detail', $parameters);
        }

        $currentTime = time();

        if ($currentTime >= strtotime($campaignDetail->result_start_date . ' 00:00:00') && ($campaignDetail->result_start_date != '0000-00-00')) {
            $campaignDetail->shown_result = true;
        } else {
            $campaignDetail->shown_result = false;
        }

        if (empty($campaignDetail->result_start_date) || $campaignDetail->result_start_date == '0000-00-00') {
            unset($campaignDetail->result_start_date);
        } else {
            $campaignDetail->result_start_date = date("Y.m.d", strtotime($campaignDetail->result_start_date));
        }

        //get the photos of this campaign
        $photos = $model->getPhotos($id);
        if (!empty($photos)) {
			$_num_of_photo_to_div = 3;
			$campaignDetail->content = $model->addPageDivisionToContent($campaignDetail->content, $_num_of_photo_to_div);
			$campaignDetail->content = $model->addSwipePhotoToContent($campaignDetail->content, $id, UCampaign::getImgPath(UCampaign::IMGSIZE_DETAIL), UCampaign::getImgPath(UCampaign::IMGSIZE_LARGE), $photos, 0);
        }
        $campaignDetail->content = uConvertContentTags($campaignDetail->content, array('youtubeWidth' => 600, 'youtubeHeight' => 400));
        $campaignDetail->result_descr = uConvertContentTags($campaignDetail->result_descr, array('youtubeWidth' => 600, 'youtubeHeight' => 400));
		$campaignDetail->id = $id;
        $this->assign('article', $campaignDetail);

        $locationModel = new ULocation();
        $userLocation = $locationModel->getUserLocation();
        $this->assign('userLocation', $userLocation);

        //最新文章
		$contentModel = new UContent();
		$options = array(
			'imgsizeArray' => array(UMODEL::IMGSIZE_RELATED),
			'_limit' => 3,
		);
		$latestArticles = $contentModel->getLatest($options);
		$this->assign('pageRightLatestArticles', $latestArticles);

        $this->metaOptions['canonicalUrl'] = $this->createUrl('/campaign/detail', array('id' => $id, 'url_title' => $campaignDetail->campaign_name));

        $this->pageTitle = getMetaTitle($campaignDetail->campaign_name);
        $this->metaKeywords = getMetaKeywords($campaignDetail->meta_keywords);
        $this->metaDescription = getMetaDescription($campaignDetail->meta_descr);

        //Ad Banner
        $fixed1 = uGetAdItem('div-gpt-ad-1472555527067-1'.ADV_BANNER_TIMESTAMP);
        $fixed2 = uGetAdItem('div-gpt-ad-1472555527067-2'.ADV_BANNER_TIMESTAMP);
        $fixed3 = uGetAdItem('div-gpt-ad-1472555527067-3'.ADV_BANNER_TIMESTAMP);
		
		$infiniteBanners = array(
			'div_gpt_ad_1472555527067_1'.ADV_BANNER_TIMESTAMP2,
			'div_gpt_ad_1472555527067_2'.ADV_BANNER_TIMESTAMP2,
			'div_gpt_ad_1472555527067_3'.ADV_BANNER_TIMESTAMP2 
		);


        $this->addCss('css/hk-other-page.css');
        $this->addCss('css/hk-article.css');
		$this->addCss('css/global/hk-card-update.css');
        $this->addJs('library/waypoints/lib/shortcuts/inview.js');
		$this->addJs('library/waypoints/lib/shortcuts/infinite.js');
		
		if($infinite_scroll){
			$this->layout = 'blankHtml';
			$this->view= '/campaign/detail/campaign';
		}else{
			$this->layout = 'responsive2';
			$this->assign("topBanner", $fixed1);
			$this->assign("largeRectangle1Banner", $fixed2);
			$this->assign("largeRectangle2Banner", $fixed3);
			$this->assign("infiniteBanners",$infiniteBanners);
		}
        
		
        $this->display();
    }
}
