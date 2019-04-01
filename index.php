<?php
require_once('sys/init.php');
/*
if($_GET['phpinfo']){ 
phpinfo();
exit();
}
*/
error_reporting(UAPP_ERR_LEV);
$_fm = new UFramework();

$switchReferences = array(
							'/topic/index'=>array('pointTo'=>'/search/index','requestUri'=>'type=2'),
                            '/tour/all/index'=>array('pointTo'=>'/search/index','requestUri'=>'type=3')
						);
if($switchReferences[$_fm->urlPath]['pointTo']){
	$urlPath = $_fm->urlPath;
	$_fm->setController($switchReferences[$urlPath]['pointTo']);
	// var_dump($switchReferences[$urlPath]['pointTo']);
	$parms = explode('&',$switchReferences[$urlPath]['requestUri']);//debug($parms,0);
	foreach($parms as $v){
		$parm = explode('=',$v);
		$_GET[$parm[0]] = $parm[1];
		if($parm[0]=='id')
			$_fm->articleId=$parm[1];
	}
}


$file = $_fm->getControllerFile();
var_dump($file);
if(!is_file($file))	$_fm->setController('/error');



if(preg_match('/(?i)msie [5-7]/',$_SERVER['HTTP_USER_AGENT']))
{
   $_fm->setController('/suggestbrowser');
}

//debug($_fm);
// var_dump($file);
//debug($_fm->getControllerFile());
include $_fm->getControllerFile();

$name = $_fm->controllerName;
$controller = new $name($_fm);


$redirect301Urls = array('/theme/detail'=>array('redirectTo'=>'/theme/index','requestUri'=>''),
						);
if($redirect301Url = $redirect301Urls[$_fm->urlPath]['redirectTo']){
	if($_GET['id']) $id = url_decrypt($_GET['id']);
	$controller->redirect301($redirect301Url,array('id'=>$id));
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



