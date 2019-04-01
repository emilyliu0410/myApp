<?php
class CampaignAnswer{
	// const CAMPAIGN_ID=28;
	// const CAMPAIGN_ANS_TABLE='tbl_campaign_ans';
	const CAMPAIGN_ANS_TABLE='tbl_campaign_hk3bd_ans';
	const CAMPAIGN_USER_TABLE='tbl_campaign_hk3bd_users';
	function validPost(){
	
		global $user_id;
		
		$rt = new stdClass();
		$rt->error=0;
		$post = $_POST;

		//input value
		if($user_id==0 && $user_id==''){
			$rt->error='請先登入';
	 	}
		elseif($post['q1']=='' && $post['q2']==''){
			$rt->error='請回答問題';
		}
		elseif($post['q1']==''){
			$rt->error='請回答問題一';
		}
		elseif($post['q2']==''){
			$rt->error='請回答問題二';
		}
		elseif($post['is_checked_tnc']==''||$post['is_checked_tnc']==0){
			$rt->error='必須點選同意才進入下一頁';
		}
		elseif($post['name'] == ''){
			$rt->error='請輸入身份證上的英文姓名';
		}
		elseif(($post['hkid'] == ''||!preg_match("/(^[a-zA-Z]{1}[0-9]{3}$)/",$post['hkid']))){
			$rt->error='請輸入身份證首4個字母及數字';
		}
		elseif($post['fbname'] == ''){
			$rt->error='請輸入Facebook帳戶名稱';
		}
		elseif($post['phone'] == '' || !preg_match("/^[0-9]{8}$/",$post['phone'])){
			$rt->error='請輸入聯絡電話 / 電話不正確';
		}
		elseif($this->exists('user_id',$user_id)&&$this->exists('period',CAMPAIGN_PERIOD)){
			$rt->error='你已提交過了';
			
	 	}
		
		return $rt;
	}
	
	function validPostQuestion(){
	
		global $user_id;
		
		$rt = new stdClass();
		$rt->error=0;
		$post = $_POST;

		//input value
		
		if($user_id==0 && $user_id==''){
			$rt->error='請先登入';
	 	}
		elseif($post['q1']=='' && $post['q2']==''){
			$rt->error='請回答問題';
		}
		elseif($post['q1']==''){
			$rt->error='請回答問題一';
		}
		elseif($post['q2']==''){
			$rt->error='請回答問題二';
		}
		elseif($this->exists('user_id',$user_id)&&$this->exists('period',CAMPAIGN_PERIOD)){
			$rt->error='你已提交過了';
			
	 	}
		
		return $rt;
	}
	
	function validPostTnc(){
	
		global $user_id;
		
		$rt = new stdClass();
		$rt->error=0;
		$post = $_POST;

		//input value
		if($user_id==0 && $user_id==''){
			$rt->error='請先登入';
	 	}
		elseif($post['is_checked_tnc']==''||$post['is_checked_tnc']==0){
			$rt->error='必須點選同意才進入下一頁';
		}
		elseif($this->exists('user_id',$user_id)&&$this->exists('period',CAMPAIGN_PERIOD)){
			$rt->error='你已提交過了';
			
	 	}
		
		return $rt;
	}
	
	function store(){
		global $user_id;
		//insert user

		$sql="INSERT INTO ".self::CAMPAIGN_ANS_TABLE."
			(period,user_id,name,answer_1,answer_2,answer_3,answer_4,answer_5,answer_6,fb_name,email,phone,hkid,create_date)
			VALUES ('".CAMPAIGN_PERIOD."', '".$user_id."','".safe_input($_POST['name'])."','".safe_input($_POST['q1'])."','".safe_input($_POST['q2'])."','".safe_input($_POST['q3'])."','".safe_input($_POST['q4'])."','".safe_input($_POST['q5'])."','".safe_input($_POST['q6'])."','".$_POST['fbname']."','".$_POST['email']."','".$_POST['phone']."','".$_POST['hkid']."',NOW())";
		$rid=uDb()->query($sql);

		return $rid;
	}
	
	function exists2($field,$value){
		$sql = 'SELECT '.$field.' 
				FROM '.self::CAMPAIGN_USER_TABLE." 
				WHERE $field='".$value."'"; 

		return uDb()->findList($sql);
	}
	
	
	function validPostForm(){
	
		global $user_id;
		
		$rt = new stdClass();
		$rt->error=0;
		$post = $_POST;

		//personal info
		if($user_id==0 && $user_id==''){
			$rt->error='請先登入';
	 	}
		elseif($post['name'] == ''){
			$rt->error='請輸入身份證上的英文姓名';
		}
		/* elseif($post['email'] == ''){
			$rt->error='請輸入提名人電郵地址';
		}
		elseif(!filter_var($post['email'], FILTER_VALIDATE_EMAIL)){
			$rt->error='請輸入正確的電郵';
		} */
		elseif(($post['hkid'] == ''||!preg_match("/(^[a-zA-Z]{1}[0-9]{3}$)/",$post['hkid']))){
			$rt->error='請輸入身份證首4個字母及數字';
		}
		elseif($post['fbname'] == ''){
			$rt->error='請輸入Facebook帳戶名稱';
		}
		elseif($post['phone'] == '' || !preg_match("/^[0-9]{8}$/",$post['phone'])){
			$rt->error='請輸入聯絡電話 / 電話不正確';
		}
		elseif($this->exists('user_id',$user_id)&&$this->exists('period',CAMPAIGN_PERIOD)){
			$rt->error='你已提交過了';
			
	 	}/* elseif(strtolower($post['authcode']) != strtolower($_COOKIE['encrypt'])){
			$rt->error="請輸入正確的驗證文字！";  
		} */
		return $rt;
	}
	

	function exists($field,$value){
		$sql = 'SELECT '.$field.' 
				FROM '.self::CAMPAIGN_ANS_TABLE." 
				WHERE $field='".$value."' AND period='".CAMPAIGN_PERIOD."'"; 

		return uDb()->findList($sql);
	}

}

