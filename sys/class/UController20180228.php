<?php defined('UFM_RUN') or die('No direct script access allowed.'); ?>
<?php

class UController {

    public $fm;
    public $auth;
    public $user;
    public $action;
    public $actions = array();
    public $view = '/index';
    public $viewDir;
    public $layout = 'default';
    public $layoutDir;
    public $vars = array();
    public $pageTitle = '港生活  - 尋找香港好去處';
    public $metaKeywords = 'HK, 港生活, 活動, 演唱會, 展覽, 著數, 快閃, 香港, 活動, 周末, 著數, 好地方, 好去處, 周圍遊, 景點, 熱話, 話題, 趣聞, 膠聞, Lifestyle 資訊, 消息, 座位表, 門票, 免費, 行山, 郊外, 交通';
    public $metaDescription = 'HK 港生活 即時緊貼HK Style，一網打盡全港生活資訊 - 演唱會、展覽、著數活動、香港好地方、各區周圍遊路線、即時最HIT熱話等，包你搵到消閒好去處!';
//    public $cssFiles = array('css/global/uhk-global.css', 'css/global/ulife-header.css');
//    public $jsFiles = array('js/global/jquery-1.8.2.min.js', 'js/global/uhk-global.js');
// moved to layout/default
	public $cssFiles = array();
	public $jsFiles = array();
    public $metaOptions = array();
    public $redirectDomains = array('uhk.hk', 'uhk.com.hk','www.uhk.com.hk');
    
    public $useMasterDb = false;
    public $isMobile = false;

    function __construct($fm) {
 
		$s = (!empty($_SERVER['HTTP_HOST'])) ? $_SERVER['HTTP_HOST'] : ((!empty($_SERVER['SERVER_NAME'])) ? $_SERVER['SERVER_NAME'] : getenv("SERVER_NAME"));
		define('APP_ISMALLKING',strstr(strtolower($s),'mall') == false ? FALSE : TRUE);

		if(APP_ISMALLKING&&strpos($_SERVER['REQUEST_URI'],'/mall/') === false && $_SERVER['REQUEST_URI'] !=UAPP_BASE_URL.'/'){
			$this->redirect(UAPP_HOST_MALL . UAPP_BASE_URL.'/mall/index.html');
		}else if(APP_ISMALLKING&&strpos($_SERVER['REQUEST_URI'],'/mall/') !== false && strpos($_SERVER["REQUEST_URI"],'.php') !== false){
			$this->redirect(UAPP_HOST_MALL . UAPP_BASE_URL.'/mall/index.html');
		}
		
        //Redirect to hk.ulifestyle.com.hk if the current domain is one of the domain in the redirectDomains array
        if (in_array($_SERVER['SERVER_NAME'], $this->redirectDomains)) {
            header("Location: " . UAPP_HOST . $_SERVER['REQUEST_URI']);
        }		
		
		$this->assign("locationBtn",getLocationButton('menu'));
        $this->fm = $fm;

        $this->layoutDir = UFM_DIR . DS . 'layout';
        $this->viewDir = UFM_DIR . DS . 'view';

        $this->action = strtolower(isset($_REQUEST['action']) ? $_REQUEST['action'] : 'index');
        $this->view = $this->fm->urlPath . '/' . $this->action;


        /*
          if(!($this->auth->usecookie==false && $fm->urlPath == '/account' && $this->action == 'login'))
          {
          }
         */
        //debug($auth->getUser(),0);
        //debug($this);
    }

	function logSearch($para){		
		$time = date('Y-m-d H:i:s');
		//$ip = self::GetIP();
		
		$page_type = str_replace(',','_',$para['type']);
		$location = str_replace(',','_',$para['location']);
		$cat = str_replace(',','_',$para['cat']);
		$tag = str_replace(',','_',$para['tag']);
		$kol = str_replace(',','_',$para['kol']);
		
		$page_type = $page_type;
		$page = $para['p'];
		$order = $para['s'];
		$location = $location;
		$cat= $cat;
		$tag = $tag;
		$keyword = $para['q'];
		$startday = $para['sday'];
		$endday = $para['eday'];
		$kol = $kol;
		$lat = $para['lat'];
		$long = $para['long'];

		//debug(UAPP_WEB_BASE_DIR);
//		self::writeFileLog('utapp/search',"$time,$page_type,$page,$order,$region,$country,$city,$suburb,$category,$tag,$keyword,$_device,$_os,$_version,$ip",'search');
		//uLog(UAPP_BASE_DIR . "/sys/log/uhkapp/search/hits-" . date('Ymd') . ".log", "$time,$page_type,$page,$order,$district,$area,$category,$tag,$keyword,$lat,$long,$_device,$_os,$_version,$ip,");
		//uLog(UAPP_WEB_BASE_DIR . "/sys/log/uhkapp/search/search-" . date('Ymd') . ".log", "$time,$page_type,$page,$order,$district,$area,$cat1,$cat2,$cat3,$tag,$keyword,$lat,$long,$device,$os,$uuid,$ip,");

		uLog(UAPP_BASE_DIR . "/sys/log/search/" . date('Y') . "/search-" . date('Ymd') . ".log", "$time,$page_type,$page,$order,$location,$cat,$tag,$keyword,$lat,$long,$kol,$startday,$endday,");

	}
	
    function logPageHits($id, $type) {
        if ($id && $type) {
            // insert log file
            $user_id = $this->user->user_id;
            $sid = $this->user->sid;
            $time = date('d/m/y H:i:s');
            require_once(dirname(dirname(__FILE__) . '..') . '/class/Mobile_Detect.php');
            $detect = new Mobile_Detect;
            $isMobile = $detect->isMobile() ? 'Y' : 'N';
            $isTablet = $detect->isTablet() ? 'Y' : 'N';
            $log_hits_dir = date('Y');
            uLog(UAPP_BASE_DIR . "/sys/log/hits/" . date('Y') . "/hits-" . date('Ymd') . ".log", "$id,$type,$time,$isMobile,$isTablet,$sid,$user_id,");
            //uLog(UAPP_BASE_DIR.'sys/log/hits/' . $log_hits_dir, "$id,$type,$time,$isMobile,$isTablet,$sid,$user_id,", 'hits');
        }
    }
	
	function logUtm($url, $src, $med, $camp, $content) {	//logUtm($id, $type, $src, $med, $camp, $content)
        if ($url && ($src || $med || $camp || $content)) {
            // insert log file
            $time = date('d/m/y H:i:s');
            $log_utm_dir = date('Y');
            uLog(UAPP_BASE_DIR . "/sys/log/utm/" . $log_utm_dir . "/utm-" . date('Ymd') . ".log", "$url,$src,$med,$camp,$content,$time,");
            //uLog(UAPP_BASE_DIR.'sys/log/utm/' . $log_utm_dir, "$url,$src,$med,$camp,$content,$time,", 'utm');
        }
    }
	
	function utm_hits($campaign, $content, $medium, $source, $user_id){
		// insert log file
		$time = date('d/m/y H:i:s');
		$log_utm_dir = date('Y').'/'.date('m');
		$log_filename = date('Y').'-'.date('m').'-'.date('d');
		uLog(UAPP_BASE_DIR . "/sys/log/logopen/" . $log_utm_dir . "/utm-" . $log_filename . ".log", "$campaign,$source,$content,$medium,$time,$user_id");
	}

    function getRequest($name, $default = false) {
        return isset($_REQUEST['msg']) ? Input::str($_REQUEST['msg']) : $default;
    }

    function setFlash($name, $value) {
        if (!setcookie(UAPP_COOKIE_PREFIX . $name, urlencode($value), time() + 3600, '/'))
            die('setFlash failed!');
    }

    function getFlash($name, $default = false) {
        $rt = $default;
        if (isset($_COOKIE[UAPP_COOKIE_PREFIX . $name])) {
            $rt = urldecode($_COOKIE[UAPP_COOKIE_PREFIX . $name]);
            setcookie(UAPP_COOKIE_PREFIX . $name, '', time() - 3600, '/');
        }
        return $rt;
    }

    function beforeAction() {
        //$this->logPageHits();
        $this->detectMobile();
		$this->assign("headerInfo",$this->getHeaderInfo());
    }
	
    function detectMobile() 
    {	
			if(($url= uGetMobile2DestopUrl($_SERVER['REQUEST_URI']))){
				header('location:'.$url);
				exit();
			}
	 /*    if(!$this->isMobile && !isset($_SESSION['uStopMobileRedirect']) && !isset($_GET['uForceDesktop']))
		{
			$detect = new Mobile_Detect;
			
			if($detect->isMobile() && !$detect->isTablet() && ($url= uGetDestop2MobileUrl($_SERVER['REQUEST_URI'])))
			{
//				debug($url);
				header('location:'.$url);
				exit();
			}	
		}*/
    }
	
	function afterAction() {
		$url = $this->metaOptions['canonicalUrl'];
		
		$src = $_GET['utm_source'];
		$med = $_GET['utm_medium'];
		$camp = $_GET['utm_campaign'];
		$content = $_GET['utm_content'];
		
		if ($url && ($src || $med || $camp || $content))
			$this->logUtm($url, $src, $med, $camp, $content);		
    }

    function run() {
    	
    	uDb($this->useMasterDb ? true:false);

        //$this->auth = new UAuth();
        //$this->user = $this->auth->getUser();
        $this->auth = uGetAuth();
        $this->user = uGetUser();

        $methods = $this->getActions();
        //debug($methods,0);
        $method = 'action' . ucfirst($this->action);
        //debug($method,0);
        if (!in_array($method, $methods)) {
            //header('location:'.$this->createUrl('/error'));
            // echo ('<script>window.location="' . $this->createUrl('/error') . '";</script>');
            // exit();
            $this->redirect('/error');
        } 
        else 
        {
            $this->beforeAction();
        	//debug($method);
            $this->$method();
			$this->afterAction();
            /*
              try
              {
              	$this->$method();
              }
              catch (UException $e)
              {
	              //display custom message
	              $this->setFlash('error',$e->getMessage());
	              $this->redirect('/error');
              }
            */
        }
    }

    function addCss($file) {
        if (strpos($file, 'http') === 0 || strpos($file, 'https') === 0) {
            array_push($this->cssFiles, $file);
        } else {
            array_push($this->cssFiles, $this->makeupMediaURL(array('url'=>UAPP_MEDIA_URL . '/' . $file)));
        }
    }

    function addJs($file) {
        if (strpos($file, 'http') === 0 || strpos($file, 'https') === 0) {
            array_push($this->jsFiles, $file);
        } else {
            array_push($this->jsFiles, $this->makeupMediaURL(array('url'=>UAPP_MEDIA_URL . '/' . $file)));
        }
    }
	
	function makeupMediaURL($opt){
		$opt = extend(array('url'=>null,
							'version'=> null
							), $opt);
		extract($opt);
		
		list($url,$params) = explode('?', $url);
		
		$path_info = pathinfo($url);
		$ext = $path_info['extension'];
		switch($ext){
			case 'css':
				$version = CST_CSS_CACHE_BUST;
			break;
			case 'js':
				$version = CST_JS_CACHE_BUST;
			break;
		}
		if(ENABLE_COMPRESSION){
			$url = str_replace(array('.js','.css'), array('.min.js','.min.css'), $url);
			$url = str_replace(array('.min.min.js','.min.min.css'), array('.min.js','.min.css'), $url);
		}
		
		$url = $url.'?v=' . $version;
		return $url;
	}

    function redirect($url = '/', $param = array()) {
        // echo ('<script>window.location="'.$this->createUrl($url,$param).'";</script>');
        // exit();


        $url = strstr($url, '//') ? $url : $this->createUrl($url, $param);

        if (!headers_sent()) {    //If headers not sent yet... then do php redirect
            header('Location: ' . $url);
            exit();
        } else {                    //If headers are sent... do java redirect... if java disabled, do html redirect.
            echo '<script type="text/javascript">';
            echo 'window.location.href="' . $url . '";';
            echo '</script>';
            echo '<noscript>';
            echo '<meta http-equiv="refresh" content="0;url=' . $url . '" />';
            echo '</noscript>';
            exit();
        }
    }
	
	function redirect301($url = '/', $param = array()) {
        $url = strstr($url, '//') ? $url : $this->createUrl($url, $param);
		header("HTTP/1.1 301 Moved Permanently"); 
		header('Location: ' . $url);
		exit();
    }
	
	function casRedirect($url = '/', $param = array()) {
        // echo ('<script>window.location="'.$this->createUrl($url,$param).'";</script>');
        // exit();


        $url = strstr($url, '//') ? $url : $this->casCreateUrl($url, $param);

        if (!headers_sent()) {    //If headers not sent yet... then do php redirect
            header('Location: ' . $url);
            exit();
        } else {                    //If headers are sent... do java redirect... if java disabled, do html redirect.
            echo '<script type="text/javascript">';
            echo 'window.location.href="' . $url . '";';
            echo '</script>';
            echo '<noscript>';
            echo '<meta http-equiv="refresh" content="0;url=' . $url . '" />';
            echo '</noscript>';
            exit();
        }
    }
	
    function getActions() {
        if (!count($this->actions)) {
            foreach (get_class_methods($this) as $v) {
                //debug(substr($v,0,6),0);
                if (strtolower(substr($v, 0, 6)) == 'action')
                    $this->actions[] = $v;
            }
        }
        return $this->actions;
    }

    /* function createUrl($url, $query = array()) {
        if ($url == '/')
            $url = '/index';
		if(APP_ISMALLKING){
			$rt = UAPP_HOST_MALL . UAPP_BASE_URL . $url . UAPP_URL_EXT;
		}else{
			$rt = UAPP_HOST . UAPP_BASE_URL . $url . UAPP_URL_EXT;
		}
        if (count($query))
            $rt .='?' . http_build_query($query);
        return $rt;
    } */
	
	function createUrl($url, $query = array(), $full_url = true) {
		if(ENABLE_SEO_FRIENDLY){
			switch($url){
				case '/activity/detail':
				case '/activitytesting/detail':
				case '/spot/detail':
				//case '/theme/detail':
				case '/tour/detail':
				case '/topic/detail':
				case '/topic/index':
				case '/campaign/detail':
				case '/campaign/index':
				case '/theme/index':
				//case '/location/index':
				//case '/location/list':
				case '/feature/mall/index':
				case '/member/bookmark/article/index':
				case '/member/bookmark/theme/index':
				case '/newsletter/index':
				case '/newsletter/detail':
				case '/district/index':
				case '/tag/index':
				case '/category/index':
				case '/index':
				case '':
					$url = $this->createSeoFriendlyUrl($url, $query, $full_url);
				break;
				default:
					$url = $this->createDynamicUrl($url, $query, $full_url);
				break;
			}
		}else{
			$url = $this->createDynamicUrl($url, $query, $full_url);
		}
        return $url;
    }
	function createDynamicUrl($url, $query = array(), $full_url = true) {
		$query = (array)$query;
		
        if ($url == '/')
            $url = '/index';
		if(APP_ISMALLKING){
			$rt = UAPP_HOST_MALL . UAPP_BASE_URL . $url . UAPP_URL_EXT;
		}else{
			$rt = UAPP_HOST . UAPP_BASE_URL . $url . UAPP_URL_EXT;
		}
		
		if(!$full_url) $rt = $url . UAPP_URL_EXT;
		
        if (count($query)){
			/* if(isset($query['id'])&&is_numeric($query['id'])){
				$query['id'] = url_encrypt($query['id']);
			} */
			if(isset($query['url_title'])&&$query['url_title']){
				unset($query['url_title']);
			}
            $rt .='?' . http_build_query($query);
		}
        return $rt;
    }
	function createSeoFriendlyUrl($url, $query = array(), $full_url = true) {
		$query = (array)$query;
		
		//$url= rtrim($url,'/index');
		if(is_numeric($indexoftarget=strrpos($url, '/index'))){
			$url = substr($url, 0, $indexoftarget);
		}
		if(APP_ISMALLKING){
			$rt = UAPP_HOST_MALL . UAPP_BASE_URL . $url;
		}else{
			$rt = UAPP_HOST . UAPP_BASE_URL . $url;
		}
		
		if(!$full_url) $rt = $url;

		if(isset($query['id'])&&$query['id']){
			$pageid = $query['id'];
			if(!is_numeric($query['id'])) $pageid = url_decrypt($query['id']);
			$rt .='/'.$pageid;
			unset($query['id']);
		}
		if(isset($query['url_title'])&&$query['url_title']){
			$rt .='/'.url_slug($query['url_title']);
			unset($query['url_title']);
		}
		
		if (count($query)){
			$rt .='?' . http_build_query($query);
		}

        return $rt;
    }
	
	function casCreateUrl($url, $query = array()) {
        if ($url == '/')
            $url = '/index';
        $rt = UAPP_HOST .':'.UAPP_PORT. UAPP_BASE_URL . $url . UAPP_URL_EXT;
        if (count($query))
            $rt .='?' . http_build_query($query);
        return $rt;
    }
    /**
     * assigns values to template variables
     *
     * @param array|string $vars the template variable name(s)
     * @param mixed $value the value to assign
     */
    function assign($vars, $value = null) {
        if (is_array($vars)) {
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

    function getObContent($file, $vars = false) {

        if (!file_exists($file))
            die('Can not find the file: ' . $file);
        else {
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

    function getViewContent($file = false) {

        if (!$file)
            $file = $this->viewDir . $this->view . UAPP_FILE_EXT;
        //debug($file);
        $content = $this->getObContent($file);

        return $content;
    }

    function rewriteDomain($html) {
        $html = str_replace('https:', 'http:', $html);

        $pattern = array(
            'google-analytics.com',
            'googleapis.com',
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
        //debug(htmlspecialchars($html)) ;
        return $html;
    }

    function display($return = false) {

        $this->pageContent = $this->getViewContent();

        $file = $this->layoutDir . DS . $this->layout . UAPP_FILE_EXT;
        $content = $this->getObContent($file);

        //debug($file);
        if (UAPP_REWRITE_DOMAIN)
            $content = $this->rewriteDomain($content);

        if ($return)
            return $content;
        else {
            echo $content;
            //exit();
        }
    }

    /**
     * get the related objects from the mapping
     * @param stdClass[] $relatedMappings mapping results
     * @param int $imagesize image size constant from UModel
     * @relatedMappings-attribute pagetype_id
     * @relatedMappings-attribute page_id
     * @return stdClass[] related objects
     * @return-attribute page_id
     * @return-attribute pagetype_id
     * @return-attribute title
     * @return-attribute url
     * @return-attribute cover_photo
     * @return-attribute avg_rating
     */
    function getPageSummary($relatedMappings, $imagesize = UModel::IMGSIZE_RELATED) {
        $returnObjects = array();
        foreach ($relatedMappings as &$relatedMapping) {
            $instance = array();
            
            if(empty($relatedMapping->pagetype_id) || empty($relatedMapping->page_id)){
                continue;
            }
            
            switch ($relatedMapping->pagetype_id) {
                case UEvent::PAGETYPE_ID:
                    $model = new UEvent();
                    break;
                case UTopic::PAGETYPE_ID:
                    $model = new UTopic();
                    break;
                case UTour::PAGETYPE_ID:
                    $model = new UTour();
                    break;
                case USpot::PAGETYPE_ID:
                    $model = new USpot();
                    break;
            }
            
            $instance = $model->getInfo($relatedMapping->page_id);
            if (!empty($instance)) {
                $instance->cover_photo = $model->getImgPath($imagesize) . $instance->cover_photo;
                $returnObjects[] = (object) array_merge((array) $relatedMapping, (array) $instance);
            }
        }
        return $returnObjects;
    }

    /**
     * Get rating of a page from cookie 
     * @param int $pagetype_id ID of a page type
     * @param int $page_id ID of page
     * @return int rating of a page
     */
    function getRatingCookies($pagetype_id, $page_id) {
        $cookiesKey = $pagetype_id . "_" . $page_id;
        $cookies = unserialize(base64_decode(uGetCookie($cookiesKey)));
        if (empty($cookies)) {
            return -1;
        }
        return (int) $cookies;
    }

    /**
     * Add rating of a page to cookie
     * @param int $pagetype_id ID of a page type
     * @param int $page_id ID of page
     * @param int $rating rating of a page
     */
    function setRatingCookies($pagetype_id, $page_id, $rating) {
        $cookiesKey = $pagetype_id . "_" . $page_id;
        uSetCookie($cookiesKey, base64_encode(serialize($rating)), 0, "/");
    }

    /**
     * add the current spot detail page to spot history
     * @param string $cookiesKey cookie key from model
     * @param int $historyLimit how many records save in cookie
     * @param int $pagetypeID instance pagetype id
     * @param int $pageID instance id
     * @param string $title instance name
     */
    function setHistoryCookies($cookiesKey, $historyLimit, $pagetypeID, $pageID, $title) {
        $serializedHistories = uGetCookie($cookiesKey);

        if ($serializedHistories === null) {
            $serializedHistories = base64_encode(serialize(array()));
        }

        $histories = unserialize(base64_decode($serializedHistories));
        $newHistory = new stdClass();
        $newHistory->t = $title;
        $newHistory->pID = $pageID;
        $newHistory->ptID = $pagetypeID;

        //check the duplicate history
        foreach ($histories as $index => $history) {
            if ($history->pID == $newHistory->pID) {
                unset($histories[$index]);
                break;
            }
        }

        $histories = array_merge(array($newHistory), $histories);

        //remove the last object when the history larger than the limit
        if (count($histories) > $historyLimit) {
            array_pop($histories);
        }

        uSetCookie($cookiesKey, base64_encode(serialize($histories)), 0, '/');
    }

    /**
     * get the history cookies object list
     * @param string $cookiesKey cookie key from model
     * @return stdClass[] history cookies object list
     * @return-attrubute string title the page title
     * @return-attrubute string url the page url
     * @return-attrubute string id the page id
     */
    function getHistoryCookies($cookiesKey) {
        $cookies = unserialize(base64_decode(uGetCookie($cookiesKey)));
        if (empty($cookies)) {
            return array();
        }

        $histories = array();
        foreach ($cookies as $cookie) {
            $history = new stdClass();
            $history->title = $cookie->t;
            $history->id = $cookie->pID;
            switch ($cookie->ptID) {
                case UEvent::PAGETYPE_ID:
                    $history->url = UEvent::getURL($cookie->pID);
                    break;
                case UTopic::PAGETYPE_ID:
                    $history->url = UTopic::getURL($cookie->pID);
                    break;
                case UTour::PAGETYPE_ID:
                    $history->url = UTour::getURL($cookie->pID);
                    break;
                case USpot::PAGETYPE_ID:
                    $history->url = USpot::getURL($cookie->pID);
                    break;
            }
            $histories[] = $history;
        }
        return $histories;
    }

	/**
     * check the current cover photo is visited in first time
     * @param String $coverPhoto cover photo in main page
     * @return boolean $rt result of checkng the current cover photo is visited in first time
     */
	function isCoverPhotosVisited($coverPhoto){
		$rt = true;
		$cookiesKey = "coverPhotos";
		$serializedCoverPhotos = uGetCookie($cookiesKey);
		
		if ($serializedCoverPhotos == null||empty($serializedCoverPhotos)) {
            $serializedCoverPhotos = base64_encode(serialize(array()));
        }
		
		$coverPhotos = unserialize(base64_decode($serializedCoverPhotos));

        if (!in_array($coverPhoto,$coverPhotos)) {
			$coverPhotos[] = $coverPhoto;
			uSetCookie($cookiesKey, base64_encode(serialize($coverPhotos)), 0, "/");
			$rt = true;
        }else{
			$rt = false;
		}
		//debug($cover,0);
        return $rt;
	}
	function getHeaderInfo(){
		$model=new UModel();
		return $model->getHeaderCount();
	}
}
