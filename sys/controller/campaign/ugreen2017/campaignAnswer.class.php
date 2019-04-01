<?php
class CampaignAnswer{
	// const CAMPAIGN_ID=28;
	// const CAMPAIGN_ANS_TABLE='tbl_campaign_ans';
	const CAMPAIGN_ANS_TABLE='tbl_campaign_ugreen2017_ans';
	const CAMPAIGN_USER_TABLE='tbl_campaign_ugreen2017_users';
	
	const CAMPAIGN_VOTE_ANS_TABLE='tbl_campaign_ugreen2017vote_ans';
	const CAMPAIGN_VOTE_USER_TABLE='tbl_campaign_ugreen2017vote_users';
	
	private $ans_options_title = array("我最喜愛的香港環保人物","我最喜愛的香港綠色藝人","我最喜愛的香港綠色建築","我最喜愛的香港環保行動","我最喜愛的香港行山路線","我最喜愛的香港大自然景點","我最喜愛的綠色城市 (環球)");
	
	function validPost2(){
	
		global $user_id;
		
		$rt = new stdClass();
		$rt->error=0;
		$post = $_POST;
		
		//input value
		if($post['group-1']==''){
			$rt->error='請選出「'.$this->ans_options_title[0].'」';
			
		}elseif($post['group-2']==''){
			$rt->error='請選出「'.$this->ans_options_title[1].'」';
			
		}elseif($post['group-3']==''){
			$rt->error='請選出「'.$this->ans_options_title[2].'」';
			
		}elseif($post['group-4']==''){
			$rt->error='請選出「'.$this->ans_options_title[3].'」';
			
		}elseif($post['group-5']==''){
			$rt->error='請選出「'.$this->ans_options_title[4].'」';
			
		}elseif($post['group-6']==''){
			$rt->error='請選出「'.$this->ans_options_title[5].'」';
			
		}elseif($post['group-7']==''){
			$rt->error='請選出「'.$this->ans_options_title[6].'」';
		}
		
		//personal info
		elseif(($post['name'] == '')){
			$rt->error='請輸入你的姓名';
			
		}elseif($post['email'] == ''){
			$rt->error='請輸入你的電郵地址';
			
		}elseif(!filter_var($post['email'], FILTER_VALIDATE_EMAIL)){
			$rt->error='請輸入正確的電郵';
			
		}elseif(($post['hkid'] == ''||!preg_match("/(^[0-9]{4}$)/",$post['hkid']))){
			$rt->error='請輸入你的身份證首4位數';
			
		}elseif($post['phone'] == '' || !preg_match("/^[0-9]{8}$/",$post['phone'])){
			$rt->error='請輸入聯絡電話 / 電話不正確';
			
		}elseif($this->exists2('email',$post['email'])){
			$rt->error='你已提交過了';
			
	 	}elseif(strtolower($post['authcode']) != strtolower($_COOKIE['encrypt'])){
			$rt->error="請輸入正確的驗證文字！";  
		}
		
		
		
		return $rt;
	}
	
	function store2(){
		//insert user
		$agree1=($_POST['agree1']==1)?1:0;
		$agree2=($_POST['agree2']==1)?1:0;
		
		$sql="INSERT INTO ".self::CAMPAIGN_VOTE_USER_TABLE."
			(name,email,hkid,phone,create_date,edm_umag,edm_hket)
			VALUES ('".safe_input($_POST['name'])."','".$_POST['email']."','".$_POST['hkid']."','".$_POST['phone']."',NOW(),'".$agree1."','".$agree2."')";
		$rid=uDb()->query($sql);
		
		if($rid){
			$user_id=mysql_insert_id();
			
			$sql="INSERT INTO ".self::CAMPAIGN_VOTE_ANS_TABLE."
					(user_id,question_id,option_id,answer)
					VALUES ";
			
			$comma = false;
			for($i=1;$i<8;$i++){
				if($_POST['group-'.$i] != ''){
					if($comma){
						$sql .= ",";						
					}else{
						$comma = true;
					}
					$arr=explode('//',$_POST['group-'.$i],2);
					$sql.="('".$user_id."','".$i."','".safe_input($arr[0])."','".safe_input($arr[1])."')";
				}
			}
			
			$rid=uDb()->query($sql);
		}
		
		return $rid;
	}
	
	function exists2($field,$value){
		$sql = 'SELECT '.$field.' 
				FROM '.self::CAMPAIGN_VOTE_USER_TABLE." 
				WHERE $field='".$value."'"; 

		return uDb()->findList($sql);
	}
	
	
	function validPost(){
	
		global $user_id;
		
		$rt = new stdClass();
		$rt->error=0;
		$post = $_POST;


		//input value
		if($post['T1_name']=='' && $post['T2_name']==''){
			$rt->error='請輸入提名';
			
		}elseif($post['T1_name']!='' && $post['T2_name']!='' && $post['T1_option']==$post['T2_option']){
			$rt->error='每項大獎只能提名一次';
			
		}elseif(mb_strlen($post['T1_reason'],'utf8')>200 || mb_strlen($post['T2_reason'],'utf8')>200){
			$rt->error='提名原因不可超出200字';
			
		}elseif($post['T1_reason']!='' && $post['T1_name']==''){
			$rt->error='請輸入提名';
			
		}elseif($post['T2_reason']!='' && $post['T2_name']==''){
			$rt->error='請輸入提名';
			
		}
		
		//personal info
		elseif(($post['name'] == '')){
			$rt->error='請輸入提名人英文全名';
		}
		elseif($post['email'] == ''){
			$rt->error='請輸入提名人電郵地址';
		}
		elseif(!filter_var($post['email'], FILTER_VALIDATE_EMAIL)){
			$rt->error='請輸入正確的電郵';
		}
		elseif(($post['hkid'] == ''||!preg_match("/(^[0-9]{4}$)/",$post['hkid']))){
			$rt->error='請輸入提名人身份證首4位數';
		}
		elseif($post['phone'] == '' || !preg_match("/^[0-9]{8}$/",$post['phone'])){
			$rt->error='請輸入聯絡電話 / 電話不正確';
		}
		elseif($this->exists('email',$post['email'])){
			$rt->error='你已提交過了';
			
	 	}elseif(strtolower($post['authcode']) != strtolower($_COOKIE['encrypt'])){
			$rt->error="請輸入正確的驗證文字！";  
		}
		return $rt;
	}
	
	function store(){
		//insert user
		$agree1=($_POST['agree1']==1)?1:0;
		$agree2=($_POST['agree2']==1)?1:0;
		
		$sql="INSERT INTO ".self::CAMPAIGN_USER_TABLE."
			(name,email,hkid,phone,create_date,edm_umag,edm_hket)
			VALUES ('".safe_input($_POST['name'])."','".$_POST['email']."','".$_POST['hkid']."','".$_POST['phone']."',NOW(),'".$agree1."','".$agree2."')";
		$rid=uDb()->query($sql);
		
		if($rid){
			$user_id=mysql_insert_id();
			
			$sql="INSERT INTO ".self::CAMPAIGN_ANS_TABLE."
					(user_id,option_id,name,reason)
					VALUES ";
			
			if($_POST['T1_name'] != ''){
				$sql.="('".$user_id."','".$_POST['T1_option']."','".safe_input($_POST['T1_name'])."','".safe_input($_POST['T1_reason'])."')";
			}
			
			if($_POST['T2_name'] != ''){
				if($_POST['T1_name'] != ''){
					$sql.=",";
				}
				$sql.="('".$user_id."','".$_POST['T2_option']."','".safe_input($_POST['T2_name'])."','".safe_input($_POST['T2_reason'])."')";
			}
			
			$rid=uDb()->query($sql);
		}
		
		return $rid;
	}

	function exists($field,$value){
		$sql = 'SELECT '.$field.' 
				FROM '.self::CAMPAIGN_USER_TABLE." 
				WHERE $field='".$value."'"; 

		return uDb()->findList($sql);
	}

}

