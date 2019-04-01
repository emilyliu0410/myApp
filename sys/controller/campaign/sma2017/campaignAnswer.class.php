<?php
class CampaignAnswer{
	// const CAMPAIGN_ID=28;
	// const CAMPAIGN_ANS_TABLE='tbl_campaign_ans';
	const CAMPAIGN_ANS_TABLE='tbl_campaign_sma2017_ans';
	const CAMPAIGN_USER_TABLE='tbl_campaign_sma2017_users';
	const USER_TABLE='tbl_users';

	
	function row($data){
		$rt = array();
		$fields = array(
			'email'=>'email',
			'phone' => 'contact_phone',
			'hkid' => 'hkid'
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


		//index5
		if(sizeof($post['q1'])!=25){
			$rt->error='請於以下50個候選單位中選出 25 個 「我最喜愛商場活動」';
		/* }elseif($post['q1_mall']==''||$post['q1_activity']==''||$post['q1_reason']==''){
			$rt->error='你曾經參加過商場內哪一個活動令你最難忘? 又或是哪一個商場活動令你難忘而不在候選列內?'; */
		}elseif(sizeof($post['q2'])!=10){
			$rt->error='請於以下20個候選單位中選出10個 「我最喜愛商場」';
		/* }elseif($post['q2_reason']==''){
			$rt->error='選出「我最喜愛商場」的原因為何?'; */
		}elseif(sizeof($post['q3'])!=1){
			$rt->error='請於以下20個候選單位中選出1個 「最佳商場Facebook專頁」';
		/* }elseif($post['q3_reason']==''){
			$rt->error='選出「最佳數碼社交媒體 - 最佳商場Facebook專頁」的原因為何?'; */
		}elseif(sizeof($post['q4'])!=1){
			$rt->error='請於以下30個候選單位中選出1個 「最佳商場應用程式」';
		}elseif($post['q4_reason']==''){
			$rt->error='你喜愛逛商場的原因是什麼？';
		} 
		//personal info
		
		elseif(($post['name'] == '')){
			$rt->error='請輸入你的英文全名 (必須與身份證相符)';
		}
		elseif(($post['hkid'] == ''||!preg_match("/(^[a-zA-Z]{1}+[0-9]{3}$)/",$post['hkid']))){
			$rt->error='請輸入你的身份證上的字母及首3位數字';
		}
		elseif($post['email'] == ''){
			$rt->error='請輸入電郵';
		}
		elseif(!filter_var($post['email'], FILTER_VALIDATE_EMAIL)){
			$rt->error='請輸入正確的電郵';
		}
		/*  
		elseif(($post['hkid'] == ''||!preg_match("/(^[0-9]{4}$)/",$post['hkid']))){
			$rt->error='請輸入你的身份證上的首4位數字';
		}
		elseif($post['contact_phone'] == '' || !preg_match("/^[0-9]{8}$/",$post['contact_phone'])){
			$rt->error='請輸入聯絡電話 / 電話不正確';
		}*/
		elseif($this->exists('email',$post['email'])&&$post['form_page'] == '5'){
			$rt->error='你已提交了答案，每位會員只可以參加遊戲一次，多謝支持 HK港生活 之活動！';
	 	}elseif(strtolower($post['authcode']) != strtolower($_COOKIE['encrypt'])){
			$rt->error="請輸入正確的驗證文字！";  
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
		// global $user_id;
		
		$user_id=$this->user_id();
		
		$q1=$_POST['q1'];
		$q2=$_POST['q2'];
		$q3=$_POST['q3'];
		$q4=$_POST['q4'];
		
		$sql="INSERT INTO ".self::CAMPAIGN_ANS_TABLE."( user_id,question_id,option_id,answer) VALUES ";
		  //q1		
		for($i=0; $i<sizeof($q1); $i++){
			$arr=explode("//", $q1[$i], 2);
			if($i!=0) $sql.=",";
			$sql.="('".$user_id."','1','".$arr[0]."','".$arr[1]."')";
		}  
		 //q2		
		for($i=0; $i<sizeof($q2); $i++){
			$arr=explode('//',$q2[$i],2);
			$sql.=",";
			$sql.="('".$user_id."','2','".$arr[0]."','".$arr[1]."')";
		}  
		  //q3		
		for($i=0; $i<sizeof($q3); $i++){
			$arr=explode('//',$q3[$i],2);
			$sql.=",";
			$sql.="('".$user_id."','3','".$arr[0]."','".$arr[1]."')";
		} 
		//q4		
		for($i=0; $i<sizeof($q4); $i++){
			$arr=explode('//',$q4[$i],2);
			$sql.=",";
			$sql.="('".$user_id."','4','".$arr[0]."','".$arr[1]."')";
		}  
		$sql.=",('".$user_id."','5','','".$_POST['q4_reason']."')";  
		
		uDb()->query($sql); 

		
		//insert user
		$agree1=($_POST['agree1']==1)?1:0;
		$agree2=($_POST['agree2']==1)?1:0;
		
		$sql="INSERT INTO ".self::CAMPAIGN_USER_TABLE."
			(name,email,hkid,create_date,edm_umag,edm_hket)
			VALUES ('".$_POST['name']."','".$_POST['email']."','".$_POST['hkid']."',NOW(),'0','".$agree2."')";
		$rid=uDb()->query($sql);
		// $row['campaign_id'] = self::CAMPAIGN_ID;
		// $rid = uDb()->insert(self::CAMPAIGN_USER_TABLE,$row);
		
		return $rid;
	}
	function user_id(){
		$sql="select count(*) as counting
			FROM ".self::CAMPAIGN_USER_TABLE;
		$result=uDb()->findOne($sql);	
		return ($result->counting+1);	
	}
	function exists($field,$value){
/* 		$sql = 'SELECT '.$field.' 
				FROM '.self::CAMPAIGN_ANS_TABLE." 
				WHERE $field='".$value."' 
					AND campaign_id='".self::CAMPAIGN_ID."'"; */
		$sql = 'SELECT '.$field.' 
				FROM '.self::CAMPAIGN_USER_TABLE." 
				WHERE $field='".$value."'"; 

		return uDb()->findList($sql);
	}
	function userExist($field,$value){
		$sql = 'SELECT user_id 
				FROM '.self::USER_TABLE." 
				WHERE $field='".$value."'";
		$result=uDb()->findOne($sql);
		return $result->user_id;
	}
	// This function will be used to decrypt data.
	 function simple_decrypt($text, $salt = "earlysandwich.com")
	 {
		return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $salt, base64_decode($text), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
	}
	/* function userExist($field,$value){
		$sql = 'SELECT '.$field.' FROM tbl_users WHERE email="'.$value.'"';
				
		return uDb()->findList($sql);
		// return $sql;
	} */

}

