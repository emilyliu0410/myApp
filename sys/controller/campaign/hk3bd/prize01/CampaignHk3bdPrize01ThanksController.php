<?php
defined( 'UFM_RUN' ) or die( 'No direct script access allowed.' );
require_once(dirname(__FILE__).'/includes.php');

class CampaignHk3bdPrize01ThanksController extends UController
{
	function actionIndex(){
		global $user_id, $prize_list;
		if(CAMPAIGN_PERIOD!=SELF_PERIOD)
			$this->redirect('/campaign/hk3bd/index');
			
		$imgDir = UAPP_BASE_URL.'/sys/view/campaign/hk3bd/';
		$this->assign('imgDir',$imgDir);
		
		$htmlDir=UAPP_BASE_URL.'/campaign/hk3bd/';
		$this->assign('htmlDir',$htmlDir);

		if($this->user->isLogin()){
			$arr = CampaignAnswer::exists('user_id',$this->user->user_id);
			$is_user_exists = count($arr);
			$user_id = $this->user->user_id;
		}else{
			$this->redirect('/campaign/hk3bd/index');
		}
		$this->assign('is_user_exists',$is_user_exists);
		$this->assign('prize_list',$prize_list);
		
		$subtitle = null;
		$canonicalUrl = $this->createUrl('/campaign/hk3bd/'.CAMPAIGN_PERIOD.'/thanks');
		$this->view='/campaign/hk3bd/questions/'.CAMPAIGN_PERIOD.'/thanks';
		$this->layout='campaign';
		
		array_push($this->cssFiles, $imgDir.'dist/css/bootstrap.min.css?v='.CAMPAIGN_CACHE);
		array_push($this->cssFiles, $imgDir.'assets/css/ie10-viewport-bug-workaround.css?v='.CAMPAIGN_CACHE);
		array_push($this->cssFiles, $imgDir.'css/navbar-fixed-top.css?v='.CAMPAIGN_CACHE);
		array_push($this->cssFiles, $imgDir.'css/grid.css?v='.CAMPAIGN_CACHE);
		array_push($this->cssFiles, $imgDir.'css/justified-nav.css?v='.CAMPAIGN_CACHE);
		array_push($this->cssFiles, $imgDir.'css/newStyle.css?v='.CAMPAIGN_CACHE);
		array_push($this->cssFiles, $imgDir.'css/newStyle.php?v='.CAMPAIGN_CACHE);
		array_push($this->cssFiles, $imgDir.'assets/js/ie-emulation-modes-warning.js?v='.CAMPAIGN_CACHE);
		
		$this->metaOptions['facebook'] = uGetFacebookMetas(CAMPAIGN_META_IMAGE, 5);
		$this->metaOptions['canonicalUrl']=$canonicalUrl;
		$this->pageTitle = '感謝你的參與 | 港玩港食港生活 同你玩轉3周年';
        $this->metaKeywords = getMetaKeywords('');
        $this->metaDescription = getMetaDescription('為答謝各位忠實會員過去3年來的厚愛及支持，港生活一於與眾同樂，於未來三星期裡強勢送出多份超人氣精選獎品回饋大家！');
		
        $this->display();
	}
}
?>