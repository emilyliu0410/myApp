<?php
defined( 'UFM_RUN' ) or die( 'No direct script access allowed.' );
class ActivityDetailreviewController extends UController
{
	public $useMasterDb = true;	
	
	function insertUserReview($filter=array()){
		$o = extend(array(
			"user_id" => 0,
            "display_name" => '',
			"pagetype_id" => 0,
            "page_id" => 0,
            "content" => '',
            "create_date" => '',
			"ip" => null,
			"invisible" => uHasAdultWords($filter['content']) ? 1 : 0
		),$filter);
		
		return uDb()->insert("tbl_user_comment", $o);
	}
	
	private function validate_text($str){
		if(mb_strlen($str,'utf8')<1)
			return false;
		
		// Encode all html special characters (<, >, ", & .. etc) and convert
		// the new line characters to <br> tags:
		$str = nl2br(htmlspecialchars($str));
		
		// Remove the new line characters that are left
		$str = str_replace(array(chr(10),chr(13)),'',$str);
		
		return $str;
	}
	
	function actionIndex()
	{	
		$a = array('id','pagetype','display_name','message', 'user_id');

		foreach($a as $v){
			$$v = safe_input(trim($_REQUEST[$v]));
		}
		
		$id = url_decrypt($id);
        if ($id < 0 || $pagetype < 0) {
            $this->redirect('/error');
        }
		
		$rt = array(
			'ack' => false,
			'err' => false,
		);
		if(!$message = $this->validate_text($message)){
			$rt['err'] = '請輸入留言';
		}elseif($this->user->status=='S'){
			$rt['err'] = '你不能留言';
		}else{
			$currentDateTime = date("Y-m-d H:i:s", time());
			$avator = $this->user->avator?$this->user->avator:$this->user->getDefaultAvatarPhoto();
			$filter = array(
						'user_id' => $user_id,
						'display_name' => $display_name,
						'page_id' => $id,
						'pagetype_id' => $pagetype,
						'content' => $message,
						'create_date' => $currentDateTime,
						'ip' => GetIP(),
					);
			$cmid = $this->insertUserReview($filter);
			
			$invisible = uHasAdultWords($message) ? 1 : 0;
			
			if($cmid) {
				$rt['ack'] = true;
				if(!$invisible){
					$rt['html'] =	'<div class="padding-tb comment-list new-comment-list">
										<div class="col-xs-12 col-sm-12 col-md-4 col-lg-3">
											<div style="float:left" class="avatar"><img src="'.$avator.'" alt="'.$display_name.'"></div>
											<div style="float:left" class="user_name">
												<span class="text-weight-400 hk-text-000000">'.$display_name.'</span><br>
												<span class="text12 text-weight-300 hk-text-5a83c2">'.date('d/m/Y H:i',strtotime($currentDateTime)).'</span>
											</div>
										</div>
										<div class="col-xs-12 col-sm-12 col-md-8 col-lg-9 text-weight-300" style="float:left;">
											<div style="float:left; width:85%">'.stripslashes($message).'</div>
											<div align="right" style="float:left; width:15%"></div>
										</div>
									</div>';
				}
			}
		}
		
		echo json_encode($rt);
        exit();
	}
}
