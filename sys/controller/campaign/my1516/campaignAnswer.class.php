<?php
class CampaignAnswer{
	const CAMPAIGN_ID=13;
	const CAMPAIGN_ANS_TABLE='tbl_campaign_ans';

//	const fid = 2;
//	const tid = 185;

	
	function row($data){
		$rt = array();
		$fields = array(
			/* 'name' => 'contact_name',
			'phone' => 'contact_phone',
			'address' => 'contact_address',
			'hkid' => 'hkid',
			'fb_name' => 'fblogin', */
			'answer_1' => 'answer_1',
			'answer_2' => 'answer_2',
			'answer_3_mood1' => 'answer_3_mood1',
			'answer_3_mood2' => 'answer_3_mood2',
			'answer_3_mood3' => 'answer_3_mood3',
			'answer_3_mood4' => 'answer_3_mood4',
			'answer_3_mood5' => 'answer_3_mood5',
			'answer_3_mood6' => 'answer_3_mood6',
			'answer_3_mood7' => 'answer_3_mood7',
			'answer_3_mood8' => 'answer_3_mood8',
			'answer_3_mood9' => 'answer_3_mood9',
			'answer_3_mood10' => 'answer_3_mood10',
			'answer_3_mood11' => 'answer_3_mood11',
			'answer_3_mood12' => 'answer_3_mood12',
			'answer_3_reason1' => 'answer_3_reason1',
			'answer_3_reason2' => 'answer_3_reason2',
			'answer_3_reason3' => 'answer_3_reason3',
			'answer_3_reason4' => 'answer_3_reason4',
			'answer_3_reason5' => 'answer_3_reason5',
			'answer_3_reason6' => 'answer_3_reason6',
			'answer_3_reason7' => 'answer_3_reason7',
			'answer_3_reason8' => 'answer_3_reason8',
			'answer_3_reason9' => 'answer_3_reason9',
			'answer_3_reason10' => 'answer_3_reason10',
			'answer_3_reason11' => 'answer_3_reason11',
			'answer_3_reason12' => 'answer_3_reason12',
			'answer_4' => 'answer_4',
			'answer_5' => 'answer_5',
			'answer_6' => 'answer_6',
			'answer_7' => 'answer_7',
			'answer_8' => 'answer_8',
			'answer_9' => 'answer_9',
			'answer_10' => 'answer_10',
			'answer_11' => 'answer_11',
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
		
		// if($user_id <= 0){
			// $rt->error='請先登入！';
		// }
		// elseif($this->exists('user_id',$user_id)&& $post['form_page'] == '5'){
			// $rt->error='你已提交了答案，每位會員只可以參加遊戲一次，多謝支持UHK 之活動！';
		// }
		//index3
		/*  else */if($post['answer_1'] == '' && ($post['form_page'] == '1' || $post['form_page'] == '11')){
				$rt->error='1.	我本人係......?';
		} 
		 elseif($post['answer_2'] == ''  && ($post['form_page'] == '2' || $post['form_page'] == '11')){
			$rt->error='2.	我係......';
		 }
		 elseif(($post['form_page'] == '3' || $post['form_page'] == '11')  && (
		 $post['answer_3_mood1'] == ''||
		 $post['answer_3_mood2'] == ''||
		 $post['answer_3_mood3'] == ''||
		 $post['answer_3_mood4'] == ''||
		 $post['answer_3_mood5'] == ''||
		 $post['answer_3_mood6'] == ''||
		 $post['answer_3_mood7'] == ''||
		 $post['answer_3_mood8'] == ''||
		 $post['answer_3_mood9'] == ''||
		 $post['answer_3_mood10'] == ''||
		 $post['answer_3_mood11'] == ''||
		 $post['answer_3_mood12'] == ''||
		 $post['answer_3_reason1'] == ''||
		 $post['answer_3_reason2'] == ''||
		 $post['answer_3_reason3'] == ''||
		 $post['answer_3_reason4'] == ''||
		 $post['answer_3_reason5'] == ''||
		 $post['answer_3_reason6'] == ''||
		 $post['answer_3_reason7'] == ''||
		 $post['answer_3_reason8'] == ''||
		 $post['answer_3_reason9'] == ''||
		 $post['answer_3_reason10'] == ''||
		 $post['answer_3_reason11'] == ''||
		 $post['answer_3_reason12'] == ''
		 )){
			$rt->error='8.	請選用以下符號形容過去 12 個月（2015 年）嘅心情';
		 }
		//index4
		  elseif($post['answer_4'] == ''  && ($post['form_page'] == '4' || $post['form_page'] == '11')){
			$rt->error='4.	當遇到開心或唔開心時，我第一時間想同邊個分享?';
		 }
		 elseif($post['answer_5'] == ''  && ($post['form_page'] == '5' || $post['form_page'] == '11')){
			$rt->error='5.	我會用咩網上渠道去同親友抒發我嘅情緒呢?';
		 }
		 elseif($post['answer_6'] == ''  && ($post['form_page'] == '6' || $post['form_page'] == '11')){
			$rt->error='6.	開心嘅時候我會做咩?';
		 }
		 elseif($post['answer_7'] == ''  && ($post['form_page'] == '7' || $post['form_page'] == '11')){
			$rt->error='7.	唔開心嘅時候我又會做咩?';
		 }
		 elseif($post['answer_8'] == ''  && ($post['form_page'] == '8' || $post['form_page'] == '11')){
			$rt->error='3.	我嘅 2016 年大計係......?';
		 }
		 elseif($post['answer_9'] == ''  && ($post['form_page'] == '9' || $post['form_page'] == '11')){
			$rt->error='9.	我個人最關心嘅係咩?';
		 }
		 elseif($post['answer_10'] == ''  && ($post['form_page'] == '10' || $post['form_page'] == '11')){
			$rt->error='10.	以下邊首歌最適合去形容我嘅 2015 年?';
		 }
		 elseif($post['answer_11'] == ''  && $post['form_page'] == '11'){
			$rt->error='11.	我住喺......?';
		 }
		//index5
		/* if($post['email'] == '' && $post['form_page'] == '5'){
			$rt->error='請填寫U Lifestyle 會員登入電郵';
		} 
		elseif($post['username'] == '' && $post['form_page'] == '5'){
			$rt->error='請填寫U Lifestyle會員名稱';
		} 
		elseif($post['contact_name'] == '' && $post['form_page'] == '5'){
			$rt->error='請輸入你的身分證上的英文全名';
		}
		elseif(($post['hkid'] == ''||!preg_match("/(^[a-zA-Z]{1}+[0-9]{3}$)/",$post['hkid'])) && $post['form_page'] == '5'){
			$rt->error='請輸入你的身份證上的字母及首3位數字';
		}
		// elseif($post['fblogin'] == ''  && $post['form_page'] == '5'){
			// $rt->error='請輸入你的Facebook用戶名稱';
		// }
		elseif(($post['contact_phone'] == '' || !preg_match("/^[0-9]+$/",$post['contact_phone'])) && $post['form_page'] == '5'){
			$rt->error='請輸入聯絡電話 / 電話不正確';
		} 
		elseif($post['contact_address'] == '' && $post['form_page'] == '5'){
			$rt->error='請輸入你的郵寄地址';
		} */
		
		
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

		$row = array();
		
		$row['create_date'] = 'NOW()';
		$row['user_id'] = $user_id;
		$row['campaign_id'] = self::CAMPAIGN_ID;
		
		$rid = uDb()->insert(self::CAMPAIGN_ANS_TABLE,$row);
		$ans_id = uDb()->insertId();
		//debug($rid);
		$row = array();
		$row = self::row($_POST);
		$row['create_date'] = 'NOW()';
		$row['ans_id'] = $ans_id;
		$rid = uDb()->insert('tbl_campaign_my1516_ans',$row);
		// setCookie('campaign_bounce2015[answer_1]','',time()-3600);
		// uSetCookie('answer_1','',time()-3600,'/'); //清除cookie中的sid
		// unset($_COOKIE['UHK_campaign_bounce2015[answer_1]']);  
		return $ans_id;
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

