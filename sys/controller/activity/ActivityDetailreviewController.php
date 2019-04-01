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
		
		$userStatus = UUser::getUserStatus($user_id);
		$rt = array(
			'ack' => false,
			'err' => false,
		);
		
		if($userStatus != UUser::STATUS_NORMAL){
            $rt['err'] = '你暫時被禁止使用此功能';
        }else if(!$message = $this->validate_text($message)){
			$rt['err'] = '請輸入留言';
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
					$rt['html'] =	'<div class="row">
										<div class="col-1 col-xs-1 col-sm-1 col-md-1 col-lg-1 member-info">
											<div class="avatar"><img src="'.$avator.'" alt="'.$display_name.'"></div>
										</div>

										<div class="col-11 col-xs-11 col-sm-11 col-md-11 col-lg-11 comment">                           
											<div class="new-content">
												<span class="member-name text-weight-400 text-break hk-text-000000">'.$display_name.'</span>
												'.stripslashes($message).'
											</div>
											<div class="time">'.date('d/m/Y H:i',strtotime($currentDateTime)).'</div>
										</div>
									</div>';
				}
			}
		}
		
		echo json_encode($rt);
        exit();
	}
}
