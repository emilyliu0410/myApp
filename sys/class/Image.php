<?php
class Image {
	public static function resize2($srcImg,$dstImg=false,$max=1024,$dstRatio=false,$options=array()){
		
		$rt = false;
		//$max=1024;
		//$dstRatio=0.8;
		$o = (object) extend(array(
			'quality' => 100,
			'crop' => false,
			'enlarge' => false,
			),$options);
		extract((Array)$o);
		
		$dstImg = $dstImg === false ? $srcImg : $dstImg;
		
		$resampled=false;

		if(!is_file($srcImg)){
			throw new Exception($srcImg.' is not a file');
		}
		else{
			$srcIm = self::createFrom($srcImg);
			$dir = dirname($dstImg);
			if(!is_dir($dir)) mkdir($dir,ZDIR_UPLOAD_MODE,true);
			list($srcW,$srcH) = getimagesize($srcImg);
			$srcRatio = $srcW/$srcH;

			if($dstRatio === false) $dstRatio = $srcRatio;
			
			if($dstRatio > 1){
				$cvsW = $max;
				$cvsH = round($cvsW / $dstRatio);
			}
			else{
				$cvsH = $max;
				$cvsW = round($cvsH * $dstRatio);
			}
			
			$dstX=0;
			$dstY=0;
			$srcX=0;
			$srcY=0;
			$dstW=$cvsW;
			$dstH=$cvsH;
			//debug("Param: srcW=>$srcW srcH=>$srcH, reqW=>$cvsW reqH=>$cvsH,	enlarge=>$enlarge, crop=>$crop, quality=>$quality",0);
			
			//same ratio
			if($srcRatio == $dstRatio){
				//do not enlarge src img 
				if($srcW == $cvsW || ($srcW < $cvsW && $enlarge == false)){
					$rt = @copy($srcImg,$dstImg);
				}
				//enlarge src img 
				elseif(($srcW < $cvsW && $enlarge == true) || $srcW > $cvsW){
					$srcW=$srcW;
					$srcH=$srcH;
					$resampled=true;
				}
			}
			// get resample action coords
			else{
				//copy src img center area to temp canvas
				if($crop){
					if($srcRatio > $dstRatio){
						$tmpCvsH = $tmpSrcH = $tmpDstH = $srcH;
						$tmpCvsW = $tmpSrcW = $tmpDstW = round($tmpDstH * $dstRatio);
						
						$tmpSrcX = round(($srcW-$tmpDstW)/2);
						$tmpSrcY = 0;
						
						$tmpDstX=0;
						$tmpDstY=0;
					}
					else{
						$tmpCvsW = $tmpSrcW = $tmpDstW = $srcW;
						$tmpCvsH = $tmpSrcH = $tmpDstH = round($tmpDstW / $dstRatio);
						
						$tmpSrcY = round(($srcH-$tmpDstH)/2);
						$tmpSrcX = 0;
						
						$tmpDstX=0;
						$tmpDstY=0;
					}
					
					$tmpDstIm=ImageCreatetruecolor($tmpDstW,$tmpDstH); 
					imagefill($tmpDstIm,0,0,imagecolorallocate($tmpDstIm, 0xFF, 0xFF, 0xFF));
					imageCopyResampled($tmpDstIm,$srcIm,$tmpDstX,$tmpDstY,$tmpSrcX,$tmpSrcY,$tmpDstW,$tmpDstH,$tmpSrcW,$tmpSrcH);
					
					imagedestroy($srcIm); 
					$srcIm = $tmpDstIm;
					
					$srcW=$tmpDstW;
					$srcH=$tmpDstH;
					$resampled=true;
					
					//do not enlarge src img 
					if($tmpCvsW < $cvsW && $enlarge == false){
						$dstW=$cvsW = $tmpCvsW;
						$dstH=$cvsH = $tmpCvsH;
					}
					//debug((object) array('cvsW'=>$cvsW,'cvsH'=>$cvsH,'dstW'=>$dstW,'dstH'=>$dstH,'srcX'=>$srcX,'srcY'=>$srcY,'dstX'=>$dstX,'dstY'=>$dstY,'dstW'=>$dstW,'dstH'=>$dstH,'srcW'=>$srcW,'srcH'=>$srcH,));
				}
				//copy src img all range to tmp canvas created with dst ratio
				else{
					if($srcRatio > $dstRatio){
						$tmpCvsW = $srcW;
						$tmpCvsH = round($tmpCvsW / $dstRatio);
						
						$tmpDstH = $tmpSrcH = $srcH;
						$tmpDstW = $tmpSrcW = $srcW;
						
						$tmpSrcX = 0;
						$tmpSrcY = 0;
						
						$tmpDstX=0;
						$tmpDstY= round(($tmpCvsH-$srcH)/2);
					}
					else{
						$tmpCvsH = $srcH;
						$tmpCvsW = round($tmpCvsH * $dstRatio);
						
						$tmpDstH = $tmpSrcH = $srcH;
						$tmpDstW = $tmpSrcW = $srcW;
						
						$tmpSrcX = 0;
						$tmpSrcY = 0;
						
						$tmpDstY=0;
						$tmpDstX= round(($tmpCvsW-$srcW)/2);
					}
					//debug((object) array('tmpDstW'=>$tmpCvsW,'tmpCvsH'=>$tmpCvsH,'tmpCvsW'=>$tmpDstW,'tmpDstH'=>$tmpDstH,'tmpSrcX'=>$tmpSrcX,'tmpSrcY'=>$tmpSrcY,'tmpDstX'=>$tmpDstX,'tmpDstY'=>$tmpDstY,'tmpDstW'=>$tmpDstW,'tmpDstH'=>$tmpDstH,'tmpSrcW'=>$tmpSrcW,'tmpSrcH'=>$tmpSrcH,),0);
					
					$tmpDstIm=ImageCreatetruecolor($tmpCvsW,$tmpCvsH); 
					imagefill($tmpDstIm,0,0,imagecolorallocate($tmpDstIm, 0xFF, 0xFF, 0xFF));
					imageCopyResampled($tmpDstIm,$srcIm,$tmpDstX,$tmpDstY,$tmpSrcX,$tmpSrcY,$tmpDstW,$tmpDstH,$tmpSrcW,$tmpSrcH);
					
					imagedestroy($srcIm); 
					$srcIm = $tmpDstIm;

					$srcW=round($tmpCvsW);
					$srcH=round($tmpCvsH);
					$resampled=true;
					
					//do not enlarge src img 
					if($tmpCvsW < $cvsW && $enlarge == false){
						$dstW=$cvsW = $tmpCvsW;
						$dstH=$cvsH = $tmpCvsH;
					}
					//debug((object) array('cvsW'=>$cvsW,'cvsH'=>$cvsH,'dstW'=>$dstW,'dstH'=>$dstH,'srcX'=>$srcX,'srcY'=>$srcY,'dstX'=>$dstX,'dstY'=>$dstY,'dstW'=>$dstW,'dstH'=>$dstH,'srcW'=>$srcW,'srcH'=>$srcH,));
				}
			}
			// do resample action
			if($resampled){
				$dstIm=ImageCreatetruecolor($cvsW,$cvsH); 
				imagefill($dstIm,0,0,imagecolorallocate($dstIm, 0xFF, 0xFF, 0xFF));
				if(
					imageCopyResampled($dstIm,$srcIm,$dstX,$srcX,$srcX,$srcY,$dstW,$dstH,$srcW,$srcH)
					&&
					imageJpeg($dstIm,$dstImg,$quality)
					) $rt = true;
					imagedestroy($dstIm); 
				imagedestroy($srcIm); 
			}
		}
		return $rt;
	}
	function getExif($img,$keys=false)
	{
		$rt = array();
		if(!function_exists('exif_read_data')) return $rt;
		
		$imgtype = array("", "GIF", "JPG", "PNG", "SWF", "PSD", "BMP", "TIFF(intel byte order)", "TIFF(motorola byte order)", "JPC", "JP2", "JPX", "JB2", "SWC", "IFF", "WBMP", "XBM");
		$Orientation = array("", "top left side", "top right side", "bottom right side", "bottom left side", "left side top", "right side top", "right side bottom", "left side bottom");
		$ResolutionUnit = array("", "", "英寸", "厘米");
		$YCbCrPositioning = array("", "the center of pixel array", "the datum point");
		$ExposureProgram = array("未定义", "手動", "标准程序", "光圈先决", "快门先决", "景深先决", "运动模式", "肖像模式", "风景模式");
		$MeteringMode_arr = array(
			"0" => "未知",
			"1" => "平均",
			"2" => "中央重點平均測光",
			"3" => "點測",
			"4" => "分區",
			"5" => "評估",
			"6" => "局部",
			"255" => "其他"
		);
		$Lightsource_arr = array("0" => "未知",
			"1" => "日光",
			"2" => "荧光灯",
			"3" => "钨丝灯",
			"10" => "闪光灯",
			"17" => "标准灯光A",
			"18" => "标准灯光B",
			"19" => "标准灯光C",
			"20" => "D55",
			"21" => "D65",
			"22" => "D75",
			"255" => "其他"
			);
		$Flash_arr = array("0" => "flash did not fire",
			"1" => "flash fired",
			"5" => "flash fired but strobe return light not detected",
			"7" => "flash fired and strobe return light detected",
			);

		$exif = exif_read_data($img, "IFD0");
		//debug($exif,false);
		if ($exif !== false) {
			$exif = exif_read_data ($img, 0, true);
			$rt = array (
				//"文件信息" => "-----------------------------",
				"文件名" => $exif[FILE][FileName],
				"文件类型" => $imgtype[$exif[FILE][FileType]],
				"文件格式" => $exif[FILE][MimeType],
				"文件大小" => $exif[FILE][FileSize],
				"时间戳" => date("Y-m-d H:i:s", $exif[FILE][FileDateTime]),
				//"图像信息" => "-----------------------------",
				"图片说明" => $exif[IFD0][ImageDescription],
				"制造商" => $exif[IFD0][Make],
				"型号" => $exif[IFD0][Model],
				"方向" => $Orientation[$exif[IFD0][Orientation]],
				"水平分辨率" => $exif[IFD0][XResolution] . $ResolutionUnit[$exif[IFD0][ResolutionUnit]],
				"垂直分辨率" => $exif[IFD0][YResolution] . $ResolutionUnit[$exif[IFD0][ResolutionUnit]],
				"创建软件" => $exif[IFD0][Software],
				"修改时间" => $exif[IFD0][DateTime],
				"作者" => $exif[IFD0][Artist],
				"YCbCr位置控制" => $YCbCrPositioning[$exif[IFD0][YCbCrPositioning]],
				"版权" => $exif[IFD0][Copyright],
				"摄影版权" => $exif[COMPUTED][Copyright . Photographer],
				"编辑版权" => $exif[COMPUTED][Copyright . Editor],
				//"拍摄信息" => "-----------------------------",
				"Exif版本" => $exif[EXIF][ExifVersion],
				"FlashPix版本" => "Ver. " . number_format($exif[EXIF][FlashPixVersion] / 100, 2),
				"拍摄时间" => $exif[EXIF][DateTimeOriginal],
				"数字化时间" => $exif[EXIF][DateTimeDigitized],
				"拍摄分辨率高" => $exif[COMPUTED][Height],
				"拍摄分辨率宽" => $exif[COMPUTED][Width],
				"光圈" => $exif[EXIF][ApertureValue],
				"快门速度" => $exif[EXIF][ShutterSpeedValue],
				"快门光圈" => $exif[COMPUTED][ApertureFNumber],
				"最大光圈值" => "F" . $exif[EXIF][MaxApertureValue],
				"曝光时间" => $exif[EXIF][ExposureTime],
				"F-Number" => $exif[EXIF][FNumber],
				"测光模式" => $MeteringMode_arr[$exif[EXIF][MeteringMode]],
				"光源" => $Lightsource_arr[$exif[EXIF][LightSource]],
				"闪光灯" =>$Flash_arr[$exif[EXIF][Flash]],
				"曝光模式" => ($exif[EXIF][ExposureMode] == 1?"手動":"自動"),
				"白平衡" => ($exif[EXIF][WhiteBalance] == 1?"手動":"自動"),
				"曝光程序" => $ExposureProgram[$exif[EXIF][ExposureProgram]],
				"曝光补偿" => $exif[EXIF][ExposureBiasValue] . "EV",
				"ISO感光度" => $exif[EXIF][ISOSpeedRatings],
				"分量配置" => (bin2hex($exif[EXIF][ComponentsConfiguration]) == "01020300"?"YCbCr":"RGB"), // '0x04,0x05,0x06,0x00'="RGB" '0x01,0x02,0x03,0x00'="YCbCr"
				"图像压缩率" => $exif[EXIF][CompressedBitsPerPixel] . "Bits/Pixel",
				"对焦距离" => $exif[COMPUTED][FocusDistance] . "m",
				"焦距" => $exif[EXIF][FocalLength] . "mm",
				"等价35mm焦距" => $exif[EXIF][FocalLengthIn35mmFilm] . "mm",
				"用户注释编码" => $exif[COMPUTED][UserCommentEncoding],
				"用户注释" => $exif[COMPUTED][UserComment],
				"色彩空间" => ($exif[EXIF][ColorSpace] == 1?"sRGB":"Uncalibrated"),
				"Exif图像宽度" => $exif[EXIF][ExifImageLength],
				"Exif图像高度" => $exif[EXIF][ExifImageWidth],
				"文件来源" => (bin2hex($exif[EXIF][FileSource]) == 0x03?"digital still camera":"unknown"),
				"场景类型" => (bin2hex($exif[EXIF][SceneType]) == 0x01?"A directly photographed image":"unknown"),
				"缩略图文件格式" => $exif[COMPUTED][Thumbnail . FileType],
				"缩略图Mime格式" => $exif[COMPUTED][Thumbnail . MimeType]
				);
		}
		//debug($exif,0);
		//debug($rt,0);
		if($keys != false){
			$tmp = array();
			foreach(explode(',',$keys) as $k){
				$tmp[$k] = $rt[$k];
			}
			$rt = $tmp;
		}
		
		return $rt;
	}
	function sizeName($filename,$size){
		$ext = uFileExt($filename);
		return basename($filename,$ext).'_'.$size.$ext;
	}


	function getAttr($file){
		
		$rt = new stdClass();
		$types = array(1=>'gif',2=>'jpg',3=>'png');
		
		list($rt->width,$rt->height,$type) = GetImageSize($file);
		
		$rt->type=$types[$type];;
		$rt->size=round(filesize($file)/1024);
		
		return $rt;
	} 
	//create image by file 
	public static function createFrom($file){
		$data = GetImageSize($file); 
		switch ($data[2]) { 
			case IMAGETYPE_GIF :
				$rt = @ImageCreateFromGIF($file);
				break;
			case IMAGETYPE_JPEG :
				$rt = @ImagecreateFromJpeg($file);
				break;
			case IMAGETYPE_PNG :
				$rt = @ImageCreateFromPNG($file);
				break;
			case IMAGETYPE_BMP :
				$rt = @imagecreatefromwbmp($file);
				break;
			default:
				$rt = false;
		}
		return $rt;
	}
	
	//get crop croords by jcrop data
	public static function getOrgImgCoords($fileOrg,$data){
		$rt = new stdClass();
		list($orgW, $orgH) = getimagesize($fileOrg);

		//debug($data,0);
		$rt->x = round($data->x*$orgW/$data->bgW);
		$rt->y = round($data->y*$orgH/$data->bgH);
		$rt->w = round($data->w*$orgW/$data->bgW);
		$rt->h = round($data->h*$orgH/$data->bgH);
		
		return $rt;
	}
	public static function crop($srcImg,$dstImg,$coords){
		
		$srcImg = str_replace('\\','/',$srcImg);
		$dstImg = str_replace('\\','/',$dstImg);
	
		$srcCreate = self::createFrom($srcImg);
	
		$srcW=ImagesX($srcCreate);
		$srcH=ImagesY($srcCreate);
		
		$dstCreate=ImageCreatetruecolor($coords->w,$coords->h); 
		$white = imagecolorallocate($dstCreate, 0xFF, 0xFF, 0xFF);
		imagefill($dstCreate,0,0,$white);
	
		if(imageCopyResampled($dstCreate,$srcCreate,0,0,$coords->x,$coords->y,$coords->w,$coords->h,$coords->w,$coords->h)){
			if(imageJpeg($dstCreate,$dstImg))
				$rt = true;
			
			else
				$rt = false;
		}                        
		imagedestroy($srcCreate); 
		imagedestroy($dstCreate); 
	
		return $rt;
	}

	//resize image to dstW & dstH and keep the W&H proportion.
	/*
	function resize0($srcImg,$dstImg,$dstW,$dstH,$forceDst=false,$quality=99){

		$dir = dirname($dstImg);
		if(!is_dir($dir)) uCreateDir($dir);


		$srcImg = str_replace('\\','/',$srcImg);
		$dstImg = str_replace('\\','/',$dstImg);

		$srcCreate = imageCreateFrom($srcImg);

		$srcW=ImagesX($srcCreate);
		$srcH=ImagesY($srcCreate);
		
		if($forceDst){
			$dstBgW = $dstW;
			$dstBgH = $dstH;
		}

		if($srcW < $dstW && $srcH < $dstH){
			$dstW = $srcW;
			$dstH = $srcH;
		}
		else{

			if ($srcW*$dstH>=$srcH*$dstW) { 
				$dstW=$dstW; 
				$dstH=round($srcH*$dstW/$srcW);	
			} 
			else {
				$dstH=$dstH; 
				$dstW=round($srcW*$dstH/$srcH); 
			} 

		}
		
		if(!$forceDst){
			$dstBgW = $dstW;
			$dstBgH = $dstH;
			$dstX = 0;
			$dstY = 0;
		}
		else{
			$srcP = $srcW/$srcH;
			$dstP = $dstBgW/$dstBgH;

			if($srcP > $dstP){
				$dstY = floor(($dstBgH - $dstH)/2);
			}
			elseif($srcP < $dstP){
				//debug($dstW);
				$dstX = floor(($dstBgW - $dstW)/2);
			}
		}

		$dstCreate=ImageCreatetruecolor($dstBgW,$dstBgH); 
		$white = imagecolorallocate($dstCreate, 0xFF, 0xFF, 0xFF);
		imagefill($dstCreate,0,0,$white);

		if(imageCopyResampled($dstCreate,$srcCreate,$dstX,$dstY,0,0,$dstW,$dstH,$srcW,$srcH)){
			if(imageJpeg($dstCreate,$dstImg,$quality))
				$rt = true;
			
			else
				$rt = false;
		}                        
		imagedestroy($srcCreate); 
		imagedestroy($dstCreate); 

		return $rt;
	}
	*/
	function resize($srcImg,$dstImg,$dstW,$dstH,$forceDst=false,$quality=100){

		$dir = dirname($dstImg);
		if(!is_dir($dir)) uCreateDir($dir);


		$srcImg = str_replace('\\','/',$srcImg);
		$dstImg = str_replace('\\','/',$dstImg);

		$srcCreate = imageCreateFrom($srcImg);

		$srcW=ImagesX($srcCreate);
		$srcH=ImagesY($srcCreate);
		
		if($forceDst){
			$dstBgW = $dstW;
			$dstBgH = $dstH;
		}

		if($srcW < $dstW && $srcH < $dstH & $forceDst==false){
			$dstW = $srcW;
			$dstH = $srcH;
		}
		else{

			if ($srcW*$dstH>=$srcH*$dstW) { 
				$dstW=$dstW; 
				$dstH=round($srcH*$dstW/$srcW);	
			} 
			else {
				$dstH=$dstH; 
				$dstW=round($srcW*$dstH/$srcH); 
			} 

		}
		
		if(!$forceDst){
			$dstBgW = $dstW;
			$dstBgH = $dstH;
			$dstX = 0;
			$dstY = 0;
		}
		else{
			$srcP = $srcW/$srcH;
			$dstP = $dstBgW/$dstBgH;

			if($srcP > $dstP){
				$dstY = floor(($dstBgH - $dstH)/2);
			}
			elseif($srcP < $dstP){
				//debug($dstW);
				$dstX = floor(($dstBgW - $dstW)/2);
			}
		}

		$dstCreate=ImageCreatetruecolor($dstBgW,$dstBgH); 
		$white = imagecolorallocate($dstCreate, 0xFF, 0xFF, 0xFF);
		imagefill($dstCreate,0,0,$white);

		if(imageCopyResampled($dstCreate,$srcCreate,$dstX,$dstY,0,0,$dstW,$dstH,$srcW,$srcH)){
			if(imageJpeg($dstCreate,$dstImg,$quality))
				$rt = true;
			
			else
				$rt = false;
		}                        
		imagedestroy($srcCreate); 
		imagedestroy($dstCreate); 

		return $rt;
	}
	//resize image to dstW & dstH and keep the W&H proportion.
	function ceterResize($srcImg,$dstImg,$dstW,$dstH,$forceDst=false,$quality=100){
		
		$dir = dirname($dstImg);
		if(!is_dir($dir)) uCreateDir($dir);

		$rt = false;
		
		$attr = self::getAttr($srcImg);
		
		$ratio = $attr->width/$attr->height;
		$ratioDst = $dstW/$dstH;
		
		if( $ratio != $ratioDst && 
			(
			($attr->width > $dstW || $attr->height > $dstH ) 
			|| 
			(($attr->width <= $dstW && $attr->height <= $dstH ) && $forceDst))
		){
		
			$coords = new stdclass;
			
			if($ratio <= $ratioDst){
				$coords->x = 0;
				$coords->w = $attr->width;
				$coords->h = round($coords->w * $dstH / $dstW);
				$coords->y = round(($attr->height - $coords->h)/2);
				
			}
			else{
				$coords->y = 0;
				$coords->h = $attr->height;
				$coords->w = round($coords->h * $ratioDst);
				$coords->x = round(($attr->width - $coords->w)/2);
			}
			
			if(!self::crop($srcImg,$dstImg,$coords)) die( __METHOD__ . ' crop failed');
			$srcImg = $dstImg;
		}
		
		$rt = self::resize($srcImg,$dstImg,$dstW,$dstH,$forceDst,$quality);
		

		return $rt;
	}
	
	function watermark($file,$watermark_type=false, $quality=1,$backup_thread=false,$watermarkFile=false){
		$quality = 100-$quality;
		$watermarkFile = $watermarkFile == false ? PATH_TRAVEL.'images/watermark/watermark_logo.png' : $watermarkFile;
		//debug($watermarkFile);
		if(!$watermark_type)	$watermark_type = "fill";
		$wm = new Watermark($watermarkFile,$watermark_type,$quality);
		//debug($wm);
		return $wm->write($file);
	}
	
} 

/*
* testing
echo '<pre>';
Image::getAttr('./1.png');
*/
?>