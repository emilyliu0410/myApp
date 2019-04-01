<?php defined( '_FRAMEWORK' ) or die( 'No direct script access allowed.' );?>
<?php
class UTFrameWork{

	public $pathRoot;
	public $pathInclude;
	public $pathModel;
	public $pathLayout;
	public $pathLayoutGlobal;
	public $pathView;
	public $pathClass;
	public $pathTheme;
	public $urlRoot;
	public $urlTheme;
	public $urlJs;
	public $theme		='default';
	public $extView		='.php';
	public $extLayout	='.php';
	public $extScript	='.php';
	public $pathRootSite;

	public $component;
	public $layout;
	public $pathLib;
	public $pathApp;
    public $vars    = array();

	public $html;

	
	function __construct($conf=false) {
		if($conf){
			$this->component($conf);
		}
		else{
			$this->pathClass		= dirname(__FILE__).DS;
			$path					= realpath($this->pathClass.'..'.DS).DS;
			$this->pathRoot			= $path;
			$this->pathRootSite		= realpath($path.'..'.DS).DS;
			$this->pathInclude		= $path.'include'.DS;
			$this->pathModel		= $path.'model'.DS;
			$this->pathLayout		= $path.'layout'.DS;
			$this->pathLayoutGlobal	= $this->pathLayout.'global'.DS;
			$this->pathView			= $path.'view'.DS;

			$url	= str_replace('\\','/',str_replace(realpath($_SERVER[DOCUMENT_ROOT]),'',$path));

			$this->urlRoot	= $url;
			$this->urlTheme	= $url.'theme/'.$this->theme.'/';
			$this->urlJs	= $url.'script/';
		}
	}

	function component($conf){
		//debug(APP_PATH);

		$default = array('component','layout','theme','view','urlTheme');
		foreach($default as $v){$this->$v =  $conf[$v] ? $conf[$v] : 'default';}

		if($conf['html']) $this->html = $conf['html'];

		$root	= realpath(dirname(__FILE__).DS.'..'.DS).DS;
		$this->pathLib		= $root;
		$this->pathClass	= $root.'class'.DS;
		$this->pathModel	= $root.'model'.DS;
		$this->pathInclude	= $root.'include'.DS;
		$this->pathApp		= APP_PATH;
		$this->pathLayout	= APP_PATH.'layout'.DS;
		$this->pathView		= APP_PATH.'component'.DS.$this->component.DS.'view'.DS;
		$this->pathTheme	= APP_PATH.'theme'.DS.$this->theme.DS;
		$this->pathRootSite	= realpath(APP_PATH.'..'.DS).DS;

		$this->urlRoot	= '/';
		$this->urlTheme	= $this->urlTheme!='default' ? $this->urlTheme : '/app/theme/'.$this->theme.'/';
		//debug($this->urlTheme);
		/*
		$this->urlJs	= $url.'script/';
		*/

	}

	function get_view_content($view,$vars=false,$path=false,$url_amend=false){
		if($path)
			$file = $path.$view.$this->extView;
		else
			$file = $this->pathView.$view.$this->extView;

		$content =  $this->get_ob_content($file,$vars);
		
		if($url_amend) $content	= $this->url_amend($content);
		
		return $content;

	}

	function get_ob_content($__file,$vars=false){

		//Used by Utravel only
//		global $user,$discuz_user,$discuz_uid,$jos_id,$_category,$_footer_link,$pagegen;
//		global $user,$discuz_user,$discuz_uid,$jos_id,$_category,$_myUTravel_link,$_footer_link,$pagegen;
		global $user,$discuz_user,$uk_username,$discuz_uid,$jos_id,$uk_username,$_category,$_myUTravel_link,$_footer_link,$pagegen;
		if(!file_exists($__file))
			$rt = false;
		else{
			
			if(is_array($vars)){
				$this->vars = array_merge($this->vars,$vars);
			}
			extract($this->vars, EXTR_PREFIX_SAME, "tpl");
			/*
			if(is_array($vars)){
				foreach($vars as $k => $v){
					$$k = $v;
				}
			}
			*/
			ob_start();
			include($__file);
			$rt = ob_get_contents();
			ob_end_clean();
		}
		if(isDev()){
			$content = $this->devAccelerate($content);
		}
		return $rt;
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
			if(!$this->html && file_exists($this->pathTheme.$this->component.$this->extView)){
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
			$file		= $this->pathLayout.$layout.$this->extLayout;
			$content	= $this->get_ob_content($file,$vars);
			$content	= $this->var_replace($content,$vars);
			if(isDev()){
				$content = $this->devAccelerate($content);
			}
			echo $content;
		}
	}

	function _display($return=false){
		//layout content
		$file		= $this->pathLayout.$this->layout.$this->extLayout;
		$content	= $this->get_ob_content($file);
		
		//view content
		$file		= $this->pathView.$this->view.$this->extView;
		$view_content = $this->get_ob_content($file,$vars);
		$content	= $this->var_replace($content,array('view_content' => $view_content));
		
		//use the current theme media files 
		$content	= $this->url_amend($content);
		
		//Used by Utravel only
		$content	.= $this->get_ob_content(PATH_TRAVEL.'bottom.php');

		if(isDev()){
			$content = $this->devAccelerate($content);
		}
		
		if($return) 
			return $content;
		else
			echo $content;
	}
	
	//use the html file to display only
	function __display($return=false){
		//view content
		$file		= $this->pathTheme.$this->html.$this->extView;
		$content = $this->get_ob_content($file);

		//use the current theme media files 
		$content	= $this->url_amend($content);

		/*
		//layout content
		$file		= $this->pathLayout.$this->layout.$this->extLayout;
		$content	= $this->get_ob_content($file);
		
		
		//use the current theme media files 
		$content	= $this->url_amend($content);
		
		*/
		//Used by Utravel only
		$content	.= $this->get_ob_content(PATH_TRAVEL.'bottom.php');
		
		if($return) 
			return $content;
		else
			echo $content;
	}

	function url_amend($html){
		$url = addslashes($this->urlTheme);
		$pattern = '/(href|src|background)=([\"\'])((?!http:|https:|javascript:|#|mailto:)[^\'\"\/]+)/i';
		$replacement = '$1=$2'.$url.'$3';
		$html = preg_replace($pattern, $replacement, $html);
		
		$pattern = '/style=([\"\'].+)url\(((?!http:|\/).+)\)(.+[\"\'])/i';
		$replacement = 'style=$1url('.$url.'$2)$3';
		$html = preg_replace($pattern, $replacement, $html);
		
		if(isDev()){
			$html = $this->devAccelerate($html);
		}
		
		return $html;
	}
	function devAccelerate($html){
		$html = str_replace('https:', 'http:', $html);

		$pattern = array(
		//'gstatic.com',
		'ssl.google-analytics.com',
		'maps.googleapis.com',
		'maps.google.com',
		'ajax.google.com',
		'ajax.googleapis.com',
		//'ugoody.com',
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

	/*
    function assign($val, $key = false)
    {
		$key = $key == false ? 	array_search ($val,$GLOBALS) : $key;
		//debug($key);
        if ($key != '')  $this->vars[$key] = $val;
	}
	*/
	function setView($view,$dir=false){
		$this->view = $view;
		if($dir)	$this->pathView = $dir;
	}
	function setHtml($html,$dir=false){
		$this->html = $html;
		if($dir)	$this->pathView = $dir;
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
		$this->get_ob_content(PATH_TRAVEL.'bottom.php');
		exit();
	}

	function render($viewfile,$return=false){
		$rt = false;
		if(!file_exists($viewfile)){
			$rt = 'Can not file the view file:'.$viewfile;
		}
		else{
			$rt = $this->get_ob_content($viewfile);
		}
		
		if($return) 
			return $rt;
		else
			echo $rt;
	}
}

?>