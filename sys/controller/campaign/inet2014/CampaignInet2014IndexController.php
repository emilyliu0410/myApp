<?php
defined( 'UFM_RUN' ) or die( 'No direct script access allowed.' );
require_once('campaignAnswer.class.php');
class CampaignInet2014IndexController extends UController
{
	function actionValidation(){
		if(isset($_POST['form_action'])){
			$GLOBALS['user_id'] = $this->user->user_id;
			$action = safe_input($_POST['form_action']);
			
			$response = new stdClass();
			$response->error = 0;
			
			$model = new CampaignAnswer();
			$valid = $model->validPost();
			
			if($valid->error){
				$response->error = $valid->error;
			}
			else{
				if(!$model->store()) $response->error = 'Data store failed!';
				//debug($model->public_forum());
		//		if(!($response->tid = $model->public_forum())) $response->error = 'Data store failed!';
			}
			echo json_encode($response);
		}else{
			$this->redirect('/error');
		} 
	}
	
	function actionIndex(){
		$imgDir = 'http://travel.ulifestyle.com.hk:8889/uhk/sys/view/campaign/inet2014';
		$this->assign('imgDir',$imgDir);

		$user_id = $this->user->user_id;
		$username = uDb()->findOne('SELECT username FROM tbl_users WHERE user_id="'.$user_id.'"')->username;
		$this->assign('user_id',$user_id);//debug($user_id);
		$this->assign('username',$username);
		
		$isLogin = $user_id > 0  ? true:false;
		$isVoted = CampaignAnswer::exists('user_id',$user_id) ? true:false;
		$disabled = ( $user_id > 0 && !$isVoted) ? '': ' disabled ';
		
		$this->assign('isLogin',$isLogin);
		$this->assign('isVoted',$isVoted);
		$this->assign('disabled',$disabled);

		$this->pageTitle = getMetaTitle('U Travel X iNet 送您 SIM Card 賞韓楓');
        $this->metaKeywords = getMetaKeywords('日韓，SIM Card，賞楓，紅葉，追楓，韓國精選追楓路線，韓國上網卡，韓國數據卡，賞楓精選路線，U Travel，iNet 日通國際通訊有限公司');
        $this->metaDescription = getMetaDescription('又到賞楓季節啦！由即日起至2014年10月20日期間，U Travel 會員只要完成以下步驟，即有機會獲得iNet 日通國際通訊有限公司贊助之3G南韓7日1GB上網卡乙張 (價值$299)，名額50個，立即參加啦！');

        $this->display();
	}
}
?>