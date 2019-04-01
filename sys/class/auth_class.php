<?php
error_reporting(0);

/*
* 驗證碼程序
*/
session_start();

//require_once '../joomla_user.php';
$num			= 5;
//$string			= rand(10000,99999);//取5個數字
//$string			= md5(rand(1,1000));//取5個數字字母
//$string			= substr($string,0,$num);

$ychar="A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z";
$list=explode(",",$ychar);
for($i=0;$i<$num;$i++){
	$randnum=rand(0,25);
	$string.=$list[$randnum];
}

// echo"11";

$_SESSION['sCheckCode']=$string;

setcookie('encrypt', $string,0,'/','',$S);
// setCookie('encrypt',simple_encrypt($string),0,'/','',$S);

$len			= strlen($string);
$bordercolor	= "#000000";
$bgcolor		= "#ffffff";//背景色
$height			= 55;
$width			= 130;
$image			= imageCreate($width, $height);

//畫邊框
$bordercolor = getcolor($image,$bordercolor);
imagefilledrectangle($image,0,0,$width+1,$height+1,$bordercolor);

//畫背景
$back = getcolor($image,$bgcolor);
imagefilledrectangle($image,1,1,$width-2,$height-2,$back);

//字體大小
$size = ceil($width / $len);

//寫字
for($i=0;$i<$len;$i++)
{
 $TempText=substr($string,$i,1);
 //字體著色
 $textColor = imageColorAllocate($image, rand(0, 100), rand(0, 100), rand(0, 100));
 //取隨機大小
 $randsize =rand($size-$size/6,$size+$size/6);
 //取得字體
 $fontsnum=rand(0,0);

 switch($fontsnum){
	 case 0:$fonts="ALGER";break;
	 case 1:$fonts="heiti";break;
	 case 2:$fonts="STLITI";break;
	 case 3:$fonts="STLITI";break;
	 case 4:$fonts="STHUPO";break;
	 case 5:$fonts="heiti";break;
	 case 6:$fonts="heiti";break;
	 case 7:$fonts="heiti";break;
	 default:$fonts="heiti";
 }

 $font = "../../media/fonts/".$fonts.".TTF"; 
 //$font = "../images/font/".$fonts.".ttf";

 //取角度
 $randAngle = rand(-15,15);

 //取每次的位置
  $x=6+($width-$width/8)/$len*$i;

 //取得每次的高度
 $y=rand($height-13,$height-10);
 imagettftext($image,$randsize,$randAngle,$x,$y,$textColor,$font,$TempText);
}

//画干扰元素
$num = 50; //干扰元素的个数
setnoise($image,$width,$height,$num);

//画干扰直线
$num = 2; //干扰直线的个数
setnoiseline($image,$width,$height,$num);
header("Content-type: image/png");
imagePng($image);
imagedestroy($image);

//取得色彩
function getcolor($image,$color)
{
     global $image;
     $color = eregi_replace ("^#","",$color);
     $r = $color[0].$color[1];
     $r = hexdec ($r);
     $b = $color[2].$color[3];
     $b = hexdec ($b);
     $g = $color[4].$color[5];
     $g = hexdec ($g);
     $color = imagecolorallocate ($image, $r, $b, $g);
     return $color;
}

//-- 画干扰点
function setnoise($image,$width,$height,$noisenum)
{
 for ($i=0; $i<$noisenum; $i++){
  $randColor = imageColorAllocate($image, rand(0, 255), rand(0, 255), rand(0, 255));//分配颜色
  imageSetPixel($image, rand(0, $width), rand(0, $height), $randColor);//画点
 }
}

//-- 画干扰直线
function setnoiseline($image,$width,$height,$noisenum)
{
 for ($i=0; $i<$noisenum; $i++){
  $randColor = imageColorAllocate($image, rand(0, 255), rand(0, 255), rand(0, 255));//分配颜色
  imageLine($image, rand(0, $width), rand(0, $width), rand(0, $height), rand(0, $height), $randColor);//画线
 }
}
//This function is used to encrypt data.
 function simple_encrypt($text, $salt = "earlysandwich.com")
 {
	 /* $alphabet = range('A', 'Z');
	 for ($i=0,$i<strlen($text),$i++){
		 $tempCode=array_search($text[$i], $alphabet); 
		 if($tempCode<10) {
			 $code=$code.'0'.$tempCode;
		 }else{
			 $code=$code.$tempCode;
		 }
	 } */
	 // return array_search('E', $alphabet); 
    return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $salt, $text, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));
}
// This function will be used to decrypt data.
 function simple_decrypt($text, $salt = "earlysandwich.com")
 {
    return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $salt, base64_decode($text), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
}
?>