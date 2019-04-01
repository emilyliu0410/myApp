<?
/**
 @author Nguyen Quoc Bao <quocbao.coder@gmail.com>
 @version 1.3
 @desc A simple object for processing download operation , support section downloading
 Please send me an email if you find some bug or it doesn't work with download manager.
 I've tested it with
 	- Reget
 	- FDM
 	- FlashGet
 	- GetRight
 	- DAP
 	
 @copyright It's free as long as you keep this header .
 @example
 
 1: File Download
 	$object = new downloader;
 	$object->setFile($filename); //Download from a file
 	$object->useResume = true; //Enable Resume Mode
 	$object->download(); //Download File
 	
 2: Data Download
  $object = new downloader;
 	$object->setData($data); //Download from php data
 	$object->useResume = true; //Enable Resume Mode
 	$object->set_filename($filename); //Set download name
 	$object->set_mime($mime); //File MIME (Default: application/otect-stream)
 	$object->download(); //Download File
 	
3: Manual Download
	$object = new downloader;
	$object->set_filename($filename);
	$object->downloadEx($size);
	//output your data here , remember to use $this->seekStart and $this->seekEnd value :)
	
**/

class Downloader {

	var $data = null;
	var $dataLen = 0;
	var $dataMod = 0;
	var $dataType = 0;
	var $dataSection = 0; //section download
	/**
	 * @var ObjectHandler
	 **/
	var $handler = array('auth' => null);
	var $useResume = true;
	var $useAutoexit = false;
	var $useAuth = false;
	var $filename = null;
	var $mime = null;
	var $bufsize = 2048;
	var $seekStart = 0;
	var $seekEnd = -1;
	
	/**
	 * Total bandwidth has been used for this download
	 * @var int
	 */
	var $bandwidth = 0;
	/**
	 * Speed limit
	 * @var float
	 */
	var $speed = 0;
	
	/*-------------------
	| Download Function |
	-------------------*/
	/**
	 * Check authentication and get seek position
	 * @return bool
	 **/
	function initialize() {
		global $HTTP_SERVER_VARS;
		
		if ($this->useAuth) //use authentication
		{
			if (!$this->_auth()) //no authentication
			{
				header('WWW-Authenticate: Basic realm="Please enter your username and password"');
    		header('HTTP/1.0 401 Unauthorized');
    		header('status: 401 Unauthorized');
    		if ($this->useAutoexit) exit();
				return false;
			}
		}
		if ($this->mime == null) $this->mime = "application/octet-stream"; //default mime
		
		if (isset($_SERVER['HTTP_RANGE']) || isset($HTTP_SERVER_VARS['HTTP_RANGE']))
		{
			
			if (isset($HTTP_SERVER_VARS['HTTP_RANGE'])) $seek_range = substr($HTTP_SERVER_VARS['HTTP_RANGE'] , strlen('bytes='));
			else $seek_range = substr($_SERVER['HTTP_RANGE'] , strlen('bytes='));
			
			$range = explode('-',$seek_range);
			
			if ($range[0] > 0)
			{
				$this->seekStart = intval($range[0]);
			}
			
			if ($range[1] > 0) $this->seekEnd = intval($range[1]);
			else $this->seekEnd = -1;
			
			if (!$this->useResume)
			{
				$this->seekStart = 0;
				
				//header("HTTP/1.0 404 Bad Request");
				//header("Status: 400 Bad Request");
				
				//exit;
				
				//return false;
			}
			else
			{
				$this->dataSection = 1;
			}
			
		}
		else
		{
			$this->seekStart = 0;
			$this->seekEnd = -1;
		}
		
		return true;
	}
	/**
	 * Send download information header
	 **/
	function header($size,$seekStart=null,$seekEnd=null) {
		header('Content-type: ' . $this->mime);
		header('Content-Disposition: attachment; filename="' . $this->filename . '"');
		header('Last-Modified: ' . date('D, d M Y H:i:s \G\M\T' , $this->dataMod));
		
		if ($this->dataSection && $this->useResume)
		{
			header("HTTP/1.0 206 Partial Content");
			header("Status: 206 Partial Content");
			header('Accept-Ranges: bytes');
			header("Content-Range: bytes $seekStart-$seekEnd/$size");
			header("Content-Length: " . ($seekEnd - $seekStart + 1));
		}
		else
		{
			header("Content-Length: $size");
		}
	}
	
	function downloadEx($size)
	{
		if (!$this->initialize()) return false;
		ignore_user_abort(true);
		//Use seek end here
		if ($this->seekStart > ($size - 1)) $this->seekStart = 0;
		if ($this->seekEnd <= 0) $this->seekEnd = $size - 1;
		$this->header($size,$seek,$this->seekEnd);
		$this->dataMod = time();
		return true;
	}
	
	/**
	 * Start download
	 * @return bool
	 **/
	function download() {
		if (!$this->initialize()) return false;
		
		$seek = $this->seekStart;
		$speed = $this->speed;
		$bufsize = $this->bufsize;
		$packet = 1;
		
		//do some clean up
		@ob_end_clean();
		$old_status = ignore_user_abort(true);
		@set_time_limit(0);
		$this->bandwidth = 0;
		
		$size = $this->dataLen;
		
		if ($this->dataType == 0) //download from a file
		{
			
			$size = filesize($this->data);
			if ($seek > ($size - 1)) $seek = 0;
			if ($this->filename == null) $this->filename = basename($this->data);
			$res = fopen($this->data,'rb');
			if ($seek) fseek($res , $seek);
			if ($this->seekEnd < $seek) $this->seekEnd = $size - 1;
			
			$this->header($size,$seek,$this->seekEnd); //always use the last seek
			$size = $this->seekEnd - $seek + 1;
			
			while (!(connection_aborted() || connection_status() == 1) && $size > 0)
			{
				if ($size < $bufsize)
				{
					echo fread($res , $size);
					$this->bandwidth += $size;
				}
				else
				{
					echo fread($res , $bufsize);
					$this->bandwidth += $bufsize;
				}
				
				$size -= $bufsize;
				flush();
				
				if ($speed > 0 && ($this->bandwidth > $speed*$packet*1024))
				{
					sleep(1);
					$packet++;
				}
			}
			fclose($res);
			
		}
		
		elseif ($this->dataType == 1) //download from a string
		{
			if ($seek > ($size - 1)) $seek = 0;
			if ($this->seekEnd < $seek) $this->seekEnd = $this->dataLen - 1;
			$this->data = substr($this->data , $seek , $this->seekEnd - $seek + 1);
			if ($this->filename == null) $this->filename = time();
			$size = strlen($this->data);
			$this->header($this->dataLen,$seek,$this->seekEnd);
			while (!connection_aborted() && $size > 0) {
				if ($size < $bufsize)
				{
					$this->bandwidth += $size;
				}
				else
				{
					$this->bandwidth += $bufsize;
				}
				
				echo substr($this->data , 0 , $bufsize);
				$this->data = substr($this->data , $bufsize);
				
				$size -= $bufsize;
				flush();
				
				if ($speed > 0 && ($this->bandwidth > $speed*$packet*1024))
				{
					sleep(1);
					$packet++;
				}
			}
		} else if ($this->dataType == 2) {
			//just send a redirect header
			header('location: ' . $this->data);
		}
		
		if ($this->useAutoexit) exit();
		
		//restore old status
		ignore_user_abort($old_status);
		set_time_limit(ini_get("max_execution_time"));
		
		return true;
	}
	
	function setFile($dir) {
		if (is_readable($dir) && is_file($dir)) {
			$this->dataLen = 0;
			$this->data = $dir;
			$this->dataType = 0;
			$this->dataMod = filemtime($dir);
			return true;
		} else return false;
	}
	
	function setData($data) {
		if ($data == '') return false;
		$this->data = $data;
		$this->dataLen = strlen($data);
		$this->dataType = 1;
		$this->dataMod = time();
		return true;
	}
	
	function setUrl($data) {
		$this->data = $data;
		$this->dataLen = 0;
		$this->dataType = 2;
		return true;
	}
	
	function setLastModtime($time) {
		$time = intval($time);
		if ($time <= 0) $time = time();
		$this->dataMod = $time;
	}
	
	/**
	 * Check authentication
	 * @return bool
	 **/
	function _auth() {
		if (!isset($_SERVER['PHP_AUTH_USER'])) return false;
		if (isset($this->handler['auth']) && function_exists($this->handler['auth']))
		{
			return $this->handler['auth']('auth' , $_SERVER['PHP_AUTH_USER'],$_SERVER['PHP_AUTH_PW']);
		}
		else return true; //you must use a handler
	}
	
}
/*
$realfile = realpath('./1.jpg');
$object = new Downloader;
$object->setFile($realfile); //Download from a file
$object->useResume = true; //Enable Resume Mode
$object->download(); //Download File		
*/