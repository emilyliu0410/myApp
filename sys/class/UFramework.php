<?php
defined('UFM_RUN') or die('No direct script access allowed.');

class UFramework
{
    public $urlPath = '/index';
    public $controllerDir;
    public $controllerName;
    public $urlParams;
    public $articleId;
    public $urlRules = array(
        '/\/demo\/news\/([0-9]+)/' => '/demo/news/detail',
        '/\/activity\/((?!detaillazy|list|detail|get_data)[a-zA-Z0-9%+?@#:$^&*.,\_-]+)/' => '/activity/detail',
        '/\/tour\/((?!detaillazy|list|detail|get_data|index|location|theme|all)[a-zA-Z0-9%+?@#:$^&*.,\_-]+)/' => '/tour/detail',
        '/\/topic\/((?!detaillazy|list|detail|get_data|index)[a-zA-Z0-9%+?@#:$^&*.,\_-]+)/' => '/topic/detail',
        '/\/spot\/((?!detaillazy|list|detail|get_data)[a-zA-Z0-9%+?@#:$^&*.,\_-]+)/' => '/spot/detail',
        '/\/location\/((?!detaillazy|list|detail|index|detect)[a-zA-Z0-9%+?@#:$^&*.,\_-]+)/' => '/location/detail',
        '/\/theme\/((?!detaillazy|list|listlazy|detail|mall|location|locationlazy|cat|catlazy|tag|taglazy|index)[a-zA-Z0-9%+?@#:$^&*.,\_-]+)/' => '/theme/detail',
        '/\/newsletter\/((?!index|indexlazy|detail)[a-zA-Z0-9%+?@#:$^&*.,\_-]+)/' => '/newsletter/index',
        '/\/membergame\/((?!listlazy|detail|mall|location|cat|tag)[a-zA-Z0-9%+?@#:$^&*.,\/_-]+)/' => '/member/list',
        '/\/membergame\/listlazy/' => '/member/listlazy',
        '/\/membergame/' => '/member/list',
    );


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
    function __construct()
    {
        //global $uAdverts_list,$uAdverts;
        global $uResponsiveAdverts, $uAdverts;

        $this->controllerDir = UFM_DIR . DS . 'controller';

        $arr = parse_url($_SERVER['REQUEST_URI']);
        $urlPath = substr($arr['path'], strlen(UAPP_BASE_URL));

        $path_ext = pathinfo($urlPath, PATHINFO_EXTENSION);

        //debug($urlPath,0);
        if (preg_match('/(?:detail|district|tag|category|author|theme|location)(?:\/)([0-9]+)/i', $urlPath, $match)) {
            $urlPathArr = explode($match[1], $urlPath);
            $urlPath = $urlPathArr[0];
            if (in_array($urlPath, array('/district/', '/tag/', '/category/', '/author/', '/theme/', '/tour/location/', '/tour/theme/', '/location/'))) $urlPath = $urlPath . 'index/';
            $this->urlPath = rtrim($urlPath, "/");
            $this->articleId = $match[1];
        } elseif (substr($urlPath, -1) == '/') {
            $this->urlPath = $urlPath . 'index';
        } elseif ($path_ext == "") {
            $this->urlPath = $urlPath . '/index';
        } else {
            $ext = strrchr($urlPath, '.');
            if (in_array($ext, array('.php', '.html'))) $urlPath = substr($urlPath, 0, -strlen($ext));
            $this->urlPath = $urlPath;
        }

        if (count($this->urlRules)) {
            foreach ($this->urlRules as $k => $v) {
                if (preg_match($k, $this->urlPath, $matches)) {
                    $this->urlParams = array_slice($matches, 1);
                    $this->urlPath = $v;
                    break;
                }
            }
        }
		
		switch($this->urlPath){
			case '/mall/index':
			case '/mall/detail':
			case '/mall/list':
			case '/mall/listlazy':
				$media_subpath = '';
			break;
			default:
				$media_subpath = '/rv';
			break;
		}
		
		define('UAPP_MEDIA_DIR',UAPP_BASE_DIR.'/media'.$media_subpath );
		define('UAPP_MEDIA_URL',UAPP_BASE_URL.'/media'.$media_subpath );
		
        //debug($this->urlPath,0);
        //debug($this->urlParams);

        $arr = array();
        foreach (explode('/', $this->urlPath) as $v) {
            $arr[] = ucfirst(strtolower($v));
        }
        $this->controllerName = join('', $arr) . 'Controller';


        //$uAdverts = $uResponsiveAdverts[$this->urlPath];//debug($uAdverts);
		$uAdverts = getResponsiveAdverts($this->urlPath);
        if (!$uAdverts) {
            $uAdverts = $uResponsiveAdverts['default'];
        }

        /* 		$uAdverts = $uAdverts_list[$this->urlPath];
                if(!$uAdverts){
                    $uAdverts = $uAdverts_list['default'];
                }
         */

    }

    function setController($urlPath)
    {
        $this->urlPath = $urlPath;
        $arr = array();
        foreach (explode('/', $this->urlPath) as $v) {
            $arr[] = ucfirst(strtolower($v));
        }
        $this->controllerName = join('', $arr) . 'Controller';
    }

    function getControllerFile()
    {
        $dir = dirname($this->urlPath);
        if ($dir == '\\' || $dir == '/') $dir = '';
        return $this->controllerDir . $dir . '/' . $this->controllerName . UAPP_FILE_EXT;
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
