<?php defined( 'UFM_RUN' ) or die( 'No direct script access allowed.' );?>
<?php

/**
 * Write watermark to image file
 *
 * @category  Image
 * @package   Watermark
 * @author    Ken <ken1291@hotmail.com>
 * @copyright 2010 Ken HUANG 
 * @license   http://www.debian.org/misc/bsd.license  BSD License (3 Clause)
 * @link      http://travelusb.com
 */
class Watermark{	
    /**
     * An image resource of watermark
     * @access private
     * @var resource 
     */
	var $im;
    /**
     * Image file of the watermark
     * @access private
     * @var int 
     */
	var $img;
    /**
     * Width and height of the image watermark
     * @access private
     * @var int 
     */
	var $img_w,$img_h;
    /**
     * run time error
     * @access public
     * @var string
     */
	var $error;

    /**
     * The two images will be merged according to pct which can range from 0 to 100. 
     * @access public
     * @var int
     */
	var $pct;

    /**
     * Constructs the Watermark
     *
     * @param string $img_file (water image file.) 
     * @param string $position  (the position of the watermark be written to . LeftTop/RightBottom...)
     *
     * @access protected
     */
    function Watermark($img_file,$position='RightBottom',$pct=99)
    {

		$this->set_watermark($img_file);
		$this->position = trim(strtolower($position));
		$this->pct = $pct;
    }

    /**
     * set the  watermark vars
     *
     * @param string $img_file (water image file.) 
     *
     * @access public
     */
    function set_watermark($img_file)
    {
		$im = $this->create($img_file);
		if($im){
			list($this->img_w,$this->img_h) = getimagesize($img_file);
			$this->img = $img_file;
			$this->im = $im;
		}
		$this->set_watermark_v2();
    }

	/**
	* add a transparency background to watermark
	*
	* use for watermark "watermark_logo.png" only, 
	*/
	function set_watermark_v2(){
		$temp_im = imagecreatetruecolor($this->img_w,$this->img_h);
		$black = imagecolorallocate($temp_im, 0, 0, 0);
		imagecolortransparent($temp_im, $black);
		imagecopy($temp_im,$this->im, 0, 0, 0, 0, $this->img_w, $this->img_h);
		$this->im = $temp_im;
	}
     /**
     * create image resource
     *
     * @param string $img_file ( image file.) 
     * @param string $position  (the position of the watermark be written to . LeftTop/RightBottom...)
     *
     * @access protected
     */
    function create($img_file)
    {
		if(!file_exists($img_file) || !$water_info = getimagesize($img_file)) {
			$this->error = 'Water image not exists or can not be readed!';
			return false;
		}

		//if is the animated gif then return false
		$image_head = file_get_contents($img_file, FILE_BINARY , NULL, 0, 1024);
		if(strpos($image_head, 'NETSCAPE2.0') !== FALSE) {
			$this->error = 'Can not be animated gif!.';
			return false;
		}
		
		$im = false;
		
		$type = $this->image_type($img_file);

		if(!in_array($type,array('gif','jpeg','png','jpg'))){
			$this->error = 'Water image type error! must be gif/jpeg/png.';
			return false;
		}
		else{
			$fun = 'imagecreatefrom'.$type;
		}
		$im = @$fun($img_file);
		return $im;
    }


     /**
     * get image type
     *
     * @param string $img_file ( image file.) 
     *
     * @return string (e.g. gif/png/jpeg )
     * @access protected
     */
    function image_type($img_file)
    {
		//fix what
		$arr = getimagesize($img_file);
		return substr($arr['mime'],6);
    }

   /**
     * calculate the positions of destination images that the Watermark be written to .
     *
     * @param string $img_dst  (the destination image file .)
     *
     * @access protected
     */
    function get_position($img_dst)
    {
		list($w_dst,$h_dst) = getimagesize($img_dst);
		list($w_src,$h_src) = getimagesize($this->img);
		$x = $y = 0;
		//debug($this->position,false);
		switch($this->position) {
			case 'lefttop':
				$x = 0;
				$y = 0;
				break;
			case 'righttop':
				$x = $w_dst - $w_src;
				$y = 0;
				break;
			case 'leftbottom':
				$x = 0;
				$y = $h_dst - $h_src;
				break;
			case 'rightbottom':
				$x = ($w_dst - $w_src) -20;
				$y = ($h_dst - $h_src) -20;
				break;
			case 'middlemiddle':
				$x = ($w_dst - $w_src) / 2;
				$y = ($h_dst - $h_src) / 2;
				break;
			case 'middletop':
				$x = ($w_dst - $w_src) / 2;
				$y = 0;
				break;
			case 'middlebottom':
				$x = ($w_dst - $w_src) / 2;
				$y = $h_dst - $h_src;
				break;
			case 'fill':
				$x_count = ((Integer)($w_dst / $w_src)) + 1;
				$y_count = ((Integer)($h_dst / $h_src)) + 1;
				$x = $y = Array();
				$pos = 0;
				for($loop_count = 0; $loop_count < $x_count; $loop_count ++){
					$x[count($x)] = $pos;
					$pos += $w_src;
				}
				$pos = 0;
				for($loop_count = 0; $loop_count < $y_count; $loop_count ++){
					$y[count($y)] = $pos;
					$pos += $h_src;
				}
				break;
			default:
				//random
				$x = mt_rand(0, ($w_dst - $w_src));
				$y = mt_rand(0, ($h_dst - $h_src));
		}

		//debug($x.' - '.$y );
		return array($x,$y);
    }

    /**
     * Write watermark to image file
	 *
     * @param string $img_dst		(the destination image file will be marked the watermark.) 
     * @param string $img_watermark	(the watermark image file.) 
     * @param string $position		(the position of the watermark be written to . LeftTop/RightBottom...)
     * @param boolean $bakup		(Back the destination image file or not.) 
     *
	 * @access public
     * @return boolean
     */
	function write($img_dst,$img_watermark=false,$position=false,$bakup=false,$pct=false) {
		
		if($img_watermark)	$this->set_watermark($watermark);
		if($position)		$this->position = trim(strtolower($position));
		if($pct)		$this->pct = $pct;
		
		$this->pct = 98;
		
		//debug($img_dst);
		$im_dst = $this->create($img_dst);
		if(!$im_dst || !$this->im){
			$rt = false;
		}
		else{
			//bakup original file 
			if($bakup) copy($img_dst,$img_dst.'.bak');
			
			//圖像的混色模式
			imagealphablending($im_dst, true);

			list($x,$y) = $this->get_position($img_dst);
			
			//拷貝水印到目標文件
			if(is_Array($x) && is_Array($y)){
				//多重式水印
				for($loop_count_y = 0; $loop_count_y < count($y); $loop_count_y ++){
					for($loop_count_x = 0; $loop_count_x < count($x); $loop_count_x ++){
						//imagecopy($im_dst, $this->im, $x[$loop_count_x], $y[$loop_count_y], 0, 0, $this->img_w, $this->img_h);
						imagecopymerge($im_dst, $this->im, $x[$loop_count_x], $y[$loop_count_y], 0, 0, $this->img_w, $this->img_h,(100 - $this->pct));
					}
				}
			}else{
				//單一式水印
				//imagecopy($im_dst, $this->im, $x, $y, 0, 0, $this->img_w, $this->img_h);
				imagecopymerge($im_dst, $this->im, $x, $y, 0, 0, $this->img_w, $this->img_h,(100 - $this->pct));
			}

			$fun = 'image'.$this->image_type($img_dst);
			//debug($fun);
			//$fun($im_dst, $img_dst,$this->pct);
			$fun($im_dst, $img_dst);
			imagedestroy($im_dst);
			$rt = true;
		}
		return $rt;
	}
	
	function backupPhoto($dir, $backup_dir, $real_dir = false){
		if(!$real_dir)
			$real_dir = "$backup_dir/".basename($dir).date("_Ymd");
		//echo $real_dir."<br>";
		@mkdir($real_dir,0777);
		if ($handle = opendir($dir)) {
			while (false !== ($file = readdir($handle))) {
				if ($file != "." && $file != "..") {
					$file = $dir.'/'.$file;
					if(is_dir($file)){
						$this->backupPhoto($file, $backup_dir, "$real_dir/".basename($file));
					}
					else{
						echo "Backup: $real_dir/".basename($file)."<br>";
						copy($file, "$real_dir/".basename($file));
					}
				}
			}
			closedir($handle);
		}
		else
			die("Backup falsed: ".$dir);
	}
	
	function backupAPhoto($dir){
		//get the directory
		$dirName = dirname($dir);
		
		//get the file name
		$fileName = basename($dir);
		
		//check the backup file in original
		if(basename($dirName)=="original"){
		
			//execute backup
			$backupDir = $dirName."/backup";
			//echo $backupDir."<br/>";
			@mkdir($backupDir,0777);
			echo "Backup: $backupDir/$fileName <br>";
			copy($dir, "$backupDir/$fileName");
		}
	}
	function backupPhotoCMS($dir,$dest){
		//get the directory
		$dirName = dirname($dir);
		
		//get the file name
		$fileName = basename($dest);
		$destDir = dirname($dest);
		
		//check the backup file in original
		//execute backup
		$backupDir = $destDir."/backup";
		//echo $backupDir."<br/>";
		if(!is_dir($backupDir)){
			@mkdir($backupDir,0777);
		}
		//echo "Backup: $backupDir/$fileName <br>";
		copy($dir, "$backupDir/$fileName");
	}
}
?>