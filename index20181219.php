<?php
require_once('sys/init.php');
if($_GET['phpinfo']){ 
phpinfo();
exit();
}
error_reporting(UAPP_ERR_LEV);
$_fm = new UFramework();

$switchReferences = array(	'/tour/index'=>array('pointTo'=>'/search/index','requestUri'=>'type=3'),
							'/topic/index'=>array('pointTo'=>'/search/index','requestUri'=>'type=2')
						);
if($switchReferences[$_fm->urlPath]['pointTo']){
	$urlPath = $_fm->urlPath;
	$_fm->setController($switchReferences[$urlPath]['pointTo']);
	$parms = explode('&',$switchReferences[$urlPath]['requestUri']);//debug($parms,0);
	foreach($parms as $v){
		$parm = explode('=',$v);
		$_GET[$parm[0]] = $parm[1];
		if($parm[0]=='id')
			$_fm->articleId=$parm[1];
	}
}


$file = $_fm->getControllerFile();
if(!is_file($file))	$_fm->setController('/error');



if(preg_match('/(?i)msie [5-7]/',$_SERVER['HTTP_USER_AGENT']))
{
   $_fm->setController('/suggestbrowser');
}

//debug($_fm);
//debug($file);
//debug($_fm->getControllerFile());
include $_fm->getControllerFile();

$name = $_fm->controllerName;
$controller = new $name($_fm);


$redirect301Urls = array(
					'/theme/detail'=>array('redirectTo'=>'/theme/index','requestUri'=>''),
					'/m/activity/detail'=>array('redirectTo'=>'/activity/detail','requestUri'=>''),
					'/m/spot/detail'=>array('redirectTo'=>'/spot/detail','requestUri'=>''),
					'/m/tour/detail'=>array('redirectTo'=>'/tour/detail','requestUri'=>''),
					'/m/topic/detail'=>array('redirectTo'=>'/topic/detail','requestUri'=>''),
					);
if($redirect301Url = $redirect301Urls[$_fm->urlPath]['redirectTo']){
	if($_GET['id']) $id = url_decrypt($_GET['id']);
	$controller->redirect($redirect301Url,array('id'=>$id));
}else if(strpos($_fm->urlPath,'/m/') !== false){
	$controller->redirect('/index');
}

//debug($_fm);
try
{
	$controller->run();
}
catch (UException $e)
{
	if($name == 'ErrorController')
		die($e->getMessage());
	else
	{
		//debug($e->getMessage());
		$controller->setFlash('error',$e->getMessage());
		$controller->redirect('/error');
	}
}



