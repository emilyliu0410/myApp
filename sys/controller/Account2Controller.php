<?php
defined( 'UFM_RUN' ) or die( 'No direct script access allowed.' );

class Account2Controller extends UController
{
    public $useMasterDb = true;

    //modify by Ken 20150424
    function safeUsername($username)
	{
		$rt=false;
		// generate username until it is available
		while(!$rt)
		{
			$sql = "SELECT username FROM tbl_users WHERE username = '".$username."'";
			$rs = uDb()->findOne($sql);
			
			if (!$rs) 
			{
				$rt = $username;
			} else 
			{
				$username = $username.mt_rand(0,9).mt_rand(0,9).mt_rand(0,9).mt_rand(0,9);
			}
		}	
		return $rt;
	}
	/*
    function safeUsername0($username,$email)
	{
		$safe_username = false;
		$try_count = 0;
		$base_username = trim($username);
		if (!ereg("^[0-9a-zA-Z]{4,32}$",$base_username)) 
		{ 
			//username include invalid character for ut
			$parts = explode("@", $email);
			$base_username = $parts[0];
		}	
		$base_username = str_replace('.','_',$base_username);
		$base_username = str_replace('-','_',$base_username);
		
		// generate username until it is available
		while(!$safe_username)
		{
			$sql = "SELECT username FROM tbl_users WHERE username = '".$base_username."'";
			$rs = uDb()->findOne($sql);
			
			if ($rs) 
			{
				$base_username = $base_username.rand(1000,9999);
				$try_count++;
			} else 
			{
				$safe_username = $base_username;
			}
		}	
		return $safe_username;
	}
	*/
	
	function casLogin()
	{
		include_once UAPP_PLUGIN_DIR.'/cas/cas.php';
		//debug('cas');         
		// force CAS authentication						
		phpCAS::forceAuthentication();		
		$casUser = phpCAS::getAttributes();
		$email = $casUser['email'];

        debug($casUser);
		
		$casUserImage = $casUser['img'];
		if(strpos($casUserImage, 'http') === false){
			if (strpos($casUserImage, URL_SEARCH) === false) {
				$casUserImage = URL_SEARCH.'/fileData/web'.$casUserImage;
			}
		}
		
		if($casUserImage==URL_SEARCH.'/fileData/web'){
			$casUserImage=UAPP_HOST.UAPP_MEDIA_URL.'/images/global/icon_avatar.png';
		}
		//debug($casUser);

		$model = new UUser();
		
		if($user = $model->findByAttributes(array('email'=>$email)))
		//if(0)
		{
			//debug($user);
			if(phpCAS::checkAuthentication()){
				$auth = new UAuth();
				if($auth->login($user))
				{
					uDb()->update('tbl_users', 
								array(
									'password'=>$casUser['passwd'],
									'avator'=>$casUserImage,
								), 
								'email = "' . $email.'" '
								);
					//debug(uGetForward());
					$this->casRedirect(uGetForward());
				}
			}
		}
		else
		{
			//debug($casUser);
			$password = $casUser['passwd'];
			$username = $this->safeUsername($casUser['name'],$email);	
			$timestamp = time();
			$gtimes = date("Y-m-d H:i:s");
			
			//debug('aaa');
			$model->insert(array(
				'username'=>$username,
				'password'=>$password,
				'status'=>UUser::STATUS_NORMAL,
				'email'=>$email,
				'register_date'=>$gtimes,
				'uk_uid'=>$casUser['id'],
				'uk_username'=>$casUser['name'],
				'avator'=>$casUserImage,
			));
			
			if(!$user = $model->findByAttributes(array('email'=>$email)))
			{
				die('Auto insert cas user failed!');
			} 
			else
			{
				$auth = new UAuth();
				if($auth->login($user))
				{
					$this->casRedirect(uGetForward());
				}
			}
		}
	}
	function actionLogin()
	{
		
		uSessionStart();
		if(uGetForward()=='/')
			uSetForward();
		
		if(UAPP_USE_CAS)
		{
			$this->casLogin(uGetForward());
		}
		else
		{
			$msg = false;
			if(isset($_POST['username']))
			{
				//debug('login submit');
				$username = Input::str($_POST['username']);
				$password = Input::str($_POST['password']);
				
				$this->assign('username',$username);
				$this->assign('password',$password);
				
				$model = new UAuth();
				if($username=='' || $password=='')
				{
					$msg ='用戶名或密碼不能為空！';
				}
				elseif(!$model->auth($username,$password))
				{
					$msg = $model->error;
				}
				elseif(!$model->login())
				{
					$msg = $model->error;
				}
				else{
					//debug($msg);
					//$this->redirect('/account',array('action'=>'success'));
					$this->redirect(uGetForward());
				}
				//debug($msg);
			}
			//debug('aaa');
			$this->assign('msg',$msg);
			$this->display();
		}
	}
	function actionSuccess()
	{
		$this->display();
		
	}
	
	function actionRegister()
	{
		uSessionStart();
		uSetForward();
		
		if(UAPP_USE_CAS){
			include_once UAPP_PLUGIN_DIR.'/cas/cas.php';
			
			if(phpCAS::checkAuthentication()){
				$this->casLogin(uGetForward());
			}else{
				$uri = CAS_REGISTER."?from=uhk&service=".urlencode($client.$_SERVER['REQUEST_URI']);
				//debug($uri);
				header('Location:'.$uri);
				exit();
			}		
		}else{
			$this->redirect(uGetForward());
		}
		$this->display();
	}
	
	function actionLogout()
	{
		uSessionStart();
		uSetForward();
		
		$this->auth->logout();
		$this->user= $this->auth->getDefaultUser();
		
		if(UAPP_USE_CAS)
		{
			include_once UAPP_PLUGIN_DIR.'/cas/cas.php';
       		// debug(uGetForward());
			$forward =uGetForward();
			phpCAS::logout(array('service'=>$forward));
		}
		else 
		{
			$this->redirect(uGetForward());
		}
		
		$this->display();
	}
}