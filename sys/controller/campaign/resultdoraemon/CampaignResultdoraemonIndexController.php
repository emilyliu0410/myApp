<?php
defined( 'UFM_RUN' ) or die( 'No direct script access allowed.' );
//require_once('campaignAnswer.class.php');
class CampaignResultDoraemonIndexController extends UController
{
	function actionIndex(){
		$imgDir = 'http://hk.ulifestyle.com.hk/sys/view/campaign/resultdoraemon';
		$this->assign('imgDir',$imgDir);

/*		$user_id = $this->user->user_id;
		$username = uDb()->findOne('SELECT username FROM tbl_users WHERE user_id="'.$user_id.'"')->username;
		$this->assign('user_id',$user_id);//debug($user_id);
		$this->assign('username',$username);
		
		$isLogin = $user_id > 0  ? true:false;
		$isVoted = CampaignAnswer::exists('user_id',$user_id) ? true:false;
		$disabled = ( $user_id > 0 && !$isVoted) ? '': ' disabled ';
		
		$this->assign('isLogin',$isLogin);
		$this->assign('isVoted',$isVoted);
		$this->assign('disabled',$disabled);	*/
		
		/* Instagarm */
//		if($items->ig_tag){
//			$ig_tag = substr($items->ig_tag,0,1)=='#'?substr($items->ig_tag,1):$items->ig_tag;
			//debug($ig_tag);
			$ig_tag = 'Doraemon';
			$option = array('hashtag'=>$ig_tag,
							'iframe_width'=>300,
							'iframe_height'=>200,
							'count'=>6,
							'img_size'=>98
							);
			$igPhoto = getIgPhoto($option);
			$this->assign('igPhoto',$igPhoto);
			$this->assign('ig_tag',$ig_tag);
//		}

		$this->pageTitle = getMetaTitle('【港生活呈獻】真心紀念：送給您最好的保存 得獎結果公佈');
//        $this->metaKeywords = getMetaKeywords('玩具 陶泥 展覽 玩樂 活動 香港 藝術 文娛 童年 回憶 周末 節目');
//        $this->metaDescription = getMetaDescription('每個人的童年都有不同玩具陪伴成長，四位陶藝創作者用陶作重拾童年的回憶。');
		
		$this->addCss('css/uhk-member-detail.css');

        $this->display();
	}
}
?>