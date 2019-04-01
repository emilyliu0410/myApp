<?php
class CampaignAnswer{
	const CAMPAIGN_ID=42;
	const CAMPAIGN_ANS_TABLE='tbl_campaign_ans';

//	const fid = 2;
//	const tid = 185;

	
	function row($data){
		$rt = array();
		$fields = array(
			'name' => 'contact_name',
			'phone' => 'contact_phone',
			'fb_name' => 'fb_name',
			'hkid' => 'hkid',
			'email' => 'email',
			'answer_1' => 'answer_1',
			'answer_2' => 'answer_2',
			'answer_3' => 'answer_3',
			//'subscribe_edm' => 0
		);
		
		foreach($fields as $k=>$v){
			if(isset($data[$v]))
			$rt[$k] = safe_input($data[$v]);
		}
		
		return $rt;
	}
	function validPost(){
	
		global $user_id;
		
		$rt = new stdClass();
		$rt->error=0;
		$post = $_POST;
		
		if($user_id <= 0){
			$rt->error='請先登入！';
		}
		elseif($this->exists('user_id',$user_id)&& $post['form_page'] == '5'){
			$rt->error='你已提交了答案，每位會員只可以參加遊戲一次，多謝支持 HK港生活 之活動！';
		}
		//index3
		 elseif($post['answer_1'] == ''  && $post['form_page'] == '3'){
			$rt->error='請分享您今年夏天最期待的活動。';
		}
		//index4
		elseif($post['agree'] == ''  && $post['form_page'] == '4'){
			$rt->error='請先閱讀及同意此活動有關條款及細則內容。';
		}
		//index5
		elseif($post['answer_3']==''  && $post['form_page'] == '5'){
			$rt->error='請輸入您喜歡該商場新春佈置的原因。';
		}
		elseif($post['fb_name'] == ''  && $post['form_page'] == '5'){
			$rt->error='請輸入你的Facebook用戶名稱';
		 }
		elseif($post['contact_name'] == '' && $post['form_page'] == '5'){
			$rt->error='請輸入你身份證上的英文全名';
		}
		elseif(($post['hkid'] == ''||!preg_match("/(^[a-zA-Z]{1}+[0-9]{3}$)/",$post['hkid'])) && $post['form_page'] == '5'){
			$rt->error='請輸入你的身份證上的字母及首3位數字';
		}
		elseif(($post['contact_phone'] == '' || !preg_match("/^[0-9]{8}$/",$post['contact_phone'])) && $post['form_page'] == '5'){
			$rt->error='請輸入聯絡電話 / 電話不正確';
		}
		
		
		return $rt;
	}

	function public_forum(){
		global $discuz_uid,$discuz_user;

		$fid = self::fid;
		$tid = self::tid;
		$content = $_POST['radio'];
		
		publicsh_bbs_post($content,$discuz_uid,$discuz_user,$fid,$tid,1);
		return $tid;
	}
	
	function store(){
		global $user_id;

		$row = self::row($_POST);
		
		$row['create_date'] = 'NOW()';
		$row['user_id'] = $user_id;
		$row['campaign_id'] = self::CAMPAIGN_ID;
		
		$rid = uDb()->insert(self::CAMPAIGN_ANS_TABLE,$row);
		// setCookie('campaign_bounce2015[answer_1]','',time()-3600);
		// uSetCookie('answer_1','',time()-3600,'/'); //清除cookie中的sid
		// unset($_COOKIE['UHK_campaign_bounce2015[answer_1]']);  
		return $rid;
	}
	
	function exists($field,$value){
		$sql = 'SELECT '.$field.' 
				FROM '.self::CAMPAIGN_ANS_TABLE." 
				WHERE $field='".$value."' 
					AND campaign_id='".self::CAMPAIGN_ID."'";
		return uDb()->findList($sql);
	}
	
	function userExist($field,$value){
		$sql = 'SELECT '.$field.' 
				FROM tbl_users'." 
				WHERE email='".$value."'";
		return uDb()->findList($sql);
	}

	
}

