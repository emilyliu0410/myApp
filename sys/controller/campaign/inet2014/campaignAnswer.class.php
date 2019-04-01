<?php
class CampaignAnswer{
	const CAMPAIGN_ID=38;
	const CAMPAIGN_ANS_TABLE='tbl_campaign_ans';

//	const fid = 2;
//	const tid = 185;

	
	function row($data){
		$rt = array();
		$fields = array(
			'name' => 'contact_name',
			'phone' => 'contact_phone',
			'hkid' => 'hkid',
			'fb_name' => 'fblogin',
			'answer_1' => 'answer_1'//,
			//'answer_2' => 'answer_2',
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
		
		//debug($post);
		if($user_id <= 0){
			$rt->error='請先登入！';
		}
		elseif($this->exists('user_id',$user_id)){
			$rt->error='你已提交了答案，每位會員只可以參加遊戲一次，多謝支持U Travel 之活動！';
		}
		elseif($post['answer_1'] == '' ){
				$rt->error='請選擇你最喜歡的行程，並於所屬行程下留言分享你喜歡的原因。';
		} 
		/* elseif($post['answer_2'] == '' ){
			$rt->error='請分享投選喜愛短片的原因';
		} */

		elseif($post['contact_name'] == ''){
			$rt->error='請輸入你的身分證上的英文全名';
		}
		elseif($post['hkid'] == ''||!preg_match("/(^[a-zA-Z]{1}+[0-9]{3}$)/",$post['hkid'])){
			$rt->error='請輸入你的身份證上的字母及首3位數字';
		}
		elseif($post['fblogin'] == '' ){
			$rt->error='請輸入你的Facebook用戶名稱';
		}
		elseif($post['contact_phone'] == '' || !preg_match("/^[0-9]+$/",$post['contact_phone'])){
			$rt->error='請輸入聯絡電話 / 電話不正確';
		}
		
		elseif($post['agree'] == '' ){
			$rt->error='請先閱讀及同意此活動有關條款及細則內容。';
		}
		return $rt;
	}
	/*
	function validPhotos(){
		$rt = true;
		$upload = false;
		for($i=0; $i<$this->photoMax; $i++){
			$uploadfile =  $_FILES['photo'.$i];
			if(isset($uploadfile['error'])){
				if($uploadfile['error'] && $uploadfile['error'] != UPLOAD_ERR_NO_FILE){
					$this->error = 'upload error:'.$uploadfile['error'];
					$rt = false;
					break;
				}
				elseif($uploadfile['error'] == UPLOAD_ERR_OK){ 
					$upload = true;
					$img = Image::getAttr($uploadfile['tmp_name']);
					if($img->size > 2048 || !in_array($img->type,array('jpg','gif','png'))){
						$rt = false;
						$this->error = $i+1;
						break;
					}
				}
			}
		}
		if(!$upload){
			$this->error = 1;
		}
		return $rt;
	}
		*/
	/*
	 Array
        (
            [name] => 01.jpg
            [type] => image/pjpeg
            [tmp_name] => D:\wamp\tmp\php156.tmp
            [error] => 0
            [size] => 114538
        )

	function genPhotoName($filename,$fix,$i){
		global $discuz_user;
		$ext = strrchr($filename,'.');
	//debug(date('YmdHi_').$discuz_user.'_'.$fix."_".($i+1).$ext);
		return date('YmdHi_').$discuz_user.'_'.$fix."_".($i+1).$ext;
	}
	function uploadPhotos(){

		$rt = true;
		$photos = array();
		$fix = rand(0,9).rand(0,9).rand(0,9).rand(0,9);
		for($i=0; $i<$this->photoMax; $i++){
			$uploadfile =  $_FILES['photo'.$i];
			if($uploadfile["error"] == UPLOAD_ERR_OK){
				$name= $this->genPhotoName($uploadfile['name'],$fix,$i);
				if(move_uploaded_file($uploadfile['tmp_name'], PATH_TRAVEL.$this->uploadDir.$name)) 
					$photos[] = $name;
				else{
					$rt = false;
					//$this->error = ($i+1);
					//$this->error = ($i+1)." (upload failed!) ";
					break;
				}
			}
		}
		//if($rt) $rt = $photos;
		if($rt) $rt = $photos;
		
		//debug($photos);
		//$this->error = print_r($photos,true);
		return $rt;
	}*/
	function public_forum(){
		global $discuz_uid,$discuz_user;
//		$fid = $this->fid;
		
//		$content = $this->travelContent;
//		$related_title = $this->travelTitle;

/*		if(is_array($this->photos)){
			foreach($this->photos as $k => $v){
				$content .="\n\n[img]".URL_MAIN.$this->uploadDir.$v."[/img]";
			}
		}	*/

		
//		$tid = create_bbs_thread($content,$discuz_uid,$discuz_user,$fid,0,$related_title);
//		$tid = $this->tid;

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
		
		//debug($row);
		$rid = uDb()->insert(self::CAMPAIGN_ANS_TABLE,$row);
		//$rid = db_insert_row(self::CAMPAIGN_ANS_TABLE,$row,1);
		/*
		if($rid && is_array($this->photos)){
			foreach($this->photos as $k => $v){
				$img = Image::getAttr(PATH_TRAVEL.$this->uploadDir.$v);
				$row = array(
					'reply_id'=>$rid, 	
					'filename' =>$v,
					'position' =>$k,
					'width' =>$img->width,
					'height' =>$img->height,
					'create_date' =>'NOW()'
				);
				db_insert_row('tbl_quiz_replies_photo',$row);
			}
		}
		//debug($rid);
		*/
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

