<?php defined( 'UFM_RUN' ) or die( 'No direct script access allowed.' );?>
<?php
class UFramework{
	
	public $fileExt	='.php';
	public $urlExt	='.html';
	public $layout	='default';
	public $controller='/index';
	public $controllerDir;
	public $controllerName;
	public $view	='/index';
	public $viewDir;
	public $layoutDir;
	public $vars = array();
	
	public $pageTitle='Title';
	public $metaKeywords='keywords';
	public $metaDescription='description';
	public $cssFiles=array('style.css');
	public $jsFiles=array('jquery-1.11.1.min.js','global.js');

/*

	public $pathRoot;
	public $pathInclude;
	public $pathModel;
	public $layoutDirGlobal;
	public $viewDir;
	public $pathClass;
	public $pathTheme;
	public $urlRoot;
	public $urlTheme;
	public $urlJs;
	public $fileExt		='.php';
	public $extScript	='.php';
	public $pathRootSite;

	public $component;
	public $layout;
	public $pathLib;
	public $pathApp;
    public $vars    = array();

	public $html;
*/	
	function __construct() {
		
		$this->layoutDir	= UFM_DIR.DS.'layout';
		$this->viewDir		= UFM_DIR.DS.'view';
		$this->controllerDir		= UFM_DIR.DS.'controller';
		
		$arr = parse_url($_SERVER['REQUEST_URI']);
		$controller = substr($arr['path'],strlen(UAPP_BASE_URL));
		if($controller !='/')
		{
			$ext = strrchr($controller, '.');
			if(in_array($ext,array('.php','.html'))) $controller = substr($controller,0,-strlen($ext));
			$this->view = $this->controller = $controller;
		}
		
		
		$arr = array();
		foreach(explode('/',$this->controller) as $v);
		{
			$arr[] = ucfirst(strtolower($v));
			
		}
		$this->controllerName = join('', $arr).'Controller';
		//debug($this->controllerName);
		
	}
	
	function setController($controller) {
		$this->view = $this->controller = $controller;
	}
	
	function getControllerFile() {
		return $this->controllerDir.$this->controller.$this->fileExt;
	}
	
	function createUrl($url) {
		if($url == '/') $url = '/index';
		return UAPP_BASE_URL.$url.$this->urlExt;
	}
	


    /**
     * assigns values to template variables
     *
     * @param array|string $vars the template variable name(s)
     * @param mixed $value the value to assign
     */
    function assign($vars, $value = null)
    {
        if (is_array($vars)){
            foreach ($vars as $key => $val) {
                if ($key != '') {
                    $this->vars[$key] = $val;
                }
            }
        } else {
            if ($vars != '')
                $this->vars[$vars] = $value;
        }
    }
	
	function getObContent($file,$vars=false){

		if(!file_exists($file))
			die('Can not find the file: '.$file);
		else
		{
			extract($this->vars, EXTR_PREFIX_SAME, "tpl");
			
			//debug($this->vars);
			//debug($message);
			
			ob_start();
			include($file);
			$rt = ob_get_contents();
			ob_end_clean();
		}
		
		
		return $rt;
	}

	function getViewContent($file=false){
	
		if(!$file)	$file	= $this->viewDir.$this->view.$this->fileExt;
		//debug($file);
		$content =  $this->getObContent($file,$vars);

		return $content;

	}
	
	
	function rewriteDomain($html){
		$html = str_replace('https:', 'http:', $html);

		$pattern = array(
			'ajax.googleapis.com',
			'ugoody.com',
			'apis.google.com',
			'www.google.com',
			'adms.hket.com',
			'connect.facebook.net',
			'facebook.com',
			'www.facebook.com',
		);
		$replacement = 'localhost';
		$html = str_replace($pattern, $replacement, $html);
		return $html;
	}
	
	function display($return=false){
		
		$this->pageContent = $this->getViewContent();
		
		$file		= $this->layoutDir.DS.$this->layout.$this->fileExt;
		$content	= $this->getObContent($file);
		
		//debug($file);
		if(UAPP_REWRITE_DOMAIN)	$content = $this->rewriteDomain($content);
		
		if($return) 
			return $content;
		else 
		{
			echo $content;
			exit();
		}
	}
	
/*
	function admendMediaUrl($html){
		$url = addslashes($this->urlTheme);
		$pattern = '/(href|src|background)=([\"\'])((?!http:|https:|javascript:|#|mailto:)[^\'\"\/]+)/i';
		$replacement = '$1=$2'.$url.'$3';
		$html = preg_replace($pattern, $replacement, $html);
		
		$pattern = '/style=([\"\'].+)url\(((?!http:|\/).+)\)(.+[\"\'])/i';
		$replacement = 'style=$1url('.$url.'$2)$3';
		$html = preg_replace($pattern, $replacement, $html);
		
		return $html;
	}
    function display($layout='default',$vars=false){
		if($this->component){

			//use the component'name as html name
			if(!$this->html && file_exists($this->pathTheme.$this->component.$this->fileExt)){
				$this->html = $this->component;
			}

			if($this->html){
				$this->__display();
			}
			else{
				$this->_display();
			}
		}
		else{
			$file		= $this->layoutDir.$layout.$this->fileExt;
			$content	= $this->getObContent($file,$vars);
			$content	= $this->var_replace($content,$vars);
			if(isDev()){
				$content = $this->rewriteDomain($content);
			}
			echo $content;
		}
	}
	function var_replace($content,$vars){
		if(!is_array($vars))
			$rt = $content;
		else{
			foreach($vars as $k => $v){
				if(is_string($v)){
					$ks[] = '{'.$k.'}';
					$vs[] = $v;
				}
			}
			$rt = str_replace($ks,$vs,$content);
		}
		return $rt;
	}
	function get_html_head($file,$path=false,$extFix=false){

		if(!$extFix) 
			$ext = get_ext($file);
		else
			$ext = $extFix;

		//debug($ext);

		if($ext == '.css'){
			if(!$path && !$extFix) 
				$file = $this->urlTheme.$file;
			else
				$file = $path.$file;

			$rt = '<link rel="stylesheet" type="text/css" href="'.$file.'" />'."\n";
		}
		elseif($ext == '.js'){
			if(!$path && !$extFix) 
				$file = $this->urlJs.$file;
			else
				$file = $path.$file;
			$rt = '<script type="text/javascript" src="'.$file.'"></script>'."\n";
		}
		else{
			die('unknow file ext: '.$ext.'  (function:get_html_head) ');
		}
		return $rt;
	}

	function head($file,$path=false,$extFix=false){
		
		//js, css code Etc. 
		if(preg_match("/<.+>/is",$file))
			$this->vars['_head'] .= $file;
		else
			$this->vars['_head'] .= $this->get_html_head($file,$path,$extFix);
	}

	function display($layout='default',$vars=false){
		if($this->component){

			//use the component'name as html name
			if(!$this->html && file_exists($this->pathTheme.$this->component.$this->fileExt)){
				$this->html = $this->component;
			}

			if($this->html){
				$this->__display();
			}
			else{
				$this->_display();
			}
		}
		else{
			$file		= $this->layoutDir.$layout.$this->fileExt;
			$content	= $this->getObContent($file,$vars);
			$content	= $this->var_replace($content,$vars);
			if(isDev()){
				$content = $this->rewriteDomain($content);
			}
			echo $content;
		}
	}

	function _display($return=false){
		//layout content
		$file		= $this->layoutDir.$this->layout.$this->fileExt;
		$content	= $this->getObContent($file);
		
		//view content
		$file		= $this->viewDir.$this->view.$this->fileExt;
		$view_content = $this->getObContent($file,$vars);
		$content	= $this->var_replace($content,array('view_content' => $view_content));
		
		//use the current layout media files 
		$content	= $this->admendMediaUrl($content);
		
		//Used by Utravel only
		$content	.= $this->getObContent(PATH_TRAVEL.'bottom.php');

		if(isDev()){
			$content = $this->rewriteDomain($content);
		}
		
		if($return) 
			return $content;
		else
			echo $content;
	}
	
	//use the html file to display only
	function __display($return=false){
		//view content
		$file		= $this->pathTheme.$this->html.$this->fileExt;
		$content = $this->getObContent($file);

		//use the current layout media files 
		$content	= $this->admendMediaUrl($content);

		
		//Used by Utravel only
		$content	.= $this->getObContent(PATH_TRAVEL.'bottom.php');
		
		if($return) 
			return $content;
		else
			echo $content;
	}

	function load_class($className){
		$file = $this->pathClass.$className.$this->extScript;
		//debug($file);
		if(file_exists($file)) include_once($file);
	}
	function load_model($className){
		$file = $this->pathModel.$className.$this->extScript;
		//debug($file);
		if(file_exists($file)) include_once($file);
	}

	//search model,class dirs and load it when exists.
	function load(){
		$args = func_get_args();
		$paths = array($this->pathModel,$this->pathClass);
		foreach($args as $className){
			foreach($paths as $v){
				$file = $v.$className.$this->extScript;
				if(file_exists($file)){
					include_once($file);
					break;
				}
			}
		}
		return $rt;
	}

	
	function setView($view,$dir=false){
		$this->view = $view;
		if($dir)	$this->viewDir = $dir;
	}
	function setHtml($html,$dir=false){
		$this->html = $html;
		if($dir)	$this->viewDir = $dir;
	}
	
	function redirect($url){
		//debug($url,0);
		if(is_array($url)) $url = uGetUrl($url);
		//debug($url);
		header('location:'.$url);
		exit();
	}
	
	function end(){
		//Used by Utravel only
		$this->getObContent(PATH_TRAVEL.'bottom.php');
		exit();
	}

	function render($viewfile,$return=false){
		$rt = false;
		if(!file_exists($viewfile)){
			$rt = 'Can not file the view file:'.$viewfile;
		}
		else{
			$rt = $this->getObContent($viewfile);
		}
		
		if($return) 
			return $rt;
		else
			echo $rt;
	}
 */	

}
