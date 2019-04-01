<?php defined( 'UFM_RUN' ) or die( 'No direct script access allowed.' );?>
<?php
class Valid{

	//价格
	function price($str)
	{
		return preg_match('/^[0-9\.]+$/',$str) ? true:false;
	}
	//数字
	function num($str,$min=false,$max=false)
	{
		if($min!== false && $max !== false)
			$pattern = "/^[0-9]{".$min.",".$max."}$/";
		elseif($min !== false)
			$pattern = "/^[0-9]{".$min.",}$/";
		elseif($max !== false)
			$pattern = "/^[0-9]{1,".$max."}$/";
		else
			$pattern = "/^[0-9]+$/";
		return preg_match($pattern,$str) ? true:false;
	}

	//字母及数字
	function charnum($str)
	{
		return preg_match("/^[a-zA-Z0-9]+$/",$str) ? true:false;
	}


	//Email
	function email($str)
	{
        return preg_match('/^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,4}$/i', $str) ? true:false;
	}

	//身份证(中国)
	function id($str)
	{
        return preg_match("/^([0-9]{15}|[0-9]{17}[0-9a-z])$/i", $str) ? true:false;
	}

	//座机电话
	function phone($str)
	{
        //return preg_match("/^([0-9]{3}|0[0-9]{3})-?[0-9]{7,8}(-[0-9]{3,4})?$/",$str) ? true:false;
        return preg_match("/^([0-9]{3}|0[0-9]{3})?-?[0-9]{7,8}(-[0-9]{3,4})?$/",$str) ? true:false;
	}

	//移动电话
	function mobile($str)
	{
        return preg_match("/13[0-9]{9}|15[0|1|2|3|5|6|7|8|9]\d{8}|18[0|5|6|7|8|9]\d{8}/", $str) ? true:false;
	}
	
	//电话国家代码
	function country_no($str)
	{
        return preg_match("/[0-9]{1,4}/", $str) ? true:false;
	}
	//邮编
    function zip($str)
    {
        return preg_match("/^[1-9]\d{5}$/", $str) ? true:false;
    } 
	//IP
    function ip($str)
    {
        return preg_match("/^((2[0-4]\d|25[0-5]|[01]?\d\d?)\.){3}(2[0-4]\d|25[0-5]|[01]?\d\d?)$/", $str) ? true:false;
    } 
	//URL
    function url($str)
    {
        return preg_match("/^([a-z]{3,5}:\/\/)?(([\w-]+\.)+[a-z]{2,3})+([\w-\/.\?%&=]*)?/i", $str) ? true:false;
    } 
	//chinese
    function chinese($str)
    {
		//$partion = "/([".chr(0xb0)."-".chr(0xf7)."][".chr(0xa1)."-".chr(0xfe)."])+/i";	//ANSI
		$partion = "/[\x{4e00}-\x{9fa5}]+/u";											//utf8
        return preg_match($partion, $str) ? true:false;
    } 

    function day( $date, $format='YYYY-MM-DD')
    {
		$rt = false;
		$match= true;
        switch( $format )
        {
            case 'YYYY/MM/DD':
            case 'YYYY-MM-DD':
            list( $y, $m, $d ) = preg_split( '/[-\.\/ ]/', $date );
            break;

            case 'YYYY/DD/MM':
            case 'YYYY-DD-MM':
            list( $y, $d, $m ) = preg_split( '/[-\.\/ ]/', $date );
            break;

            case 'DD-MM-YYYY':
            case 'DD/MM/YYYY':
            list( $d, $m, $y ) = preg_split( '/[-\.\/ ]/', $date );
            break;

            case 'MM-DD-YYYY':
            case 'MM/DD/YYYY':
            list( $m, $d, $y ) = preg_split( '/[-\.\/ ]/', $date );
            break;

            case 'YYYYMMDD':
            $y = substr( $date, 0, 4 );
            $m = substr( $date, 4, 2 );
            $d = substr( $date, 6, 2 );
            break;

            case 'YYYYDDMM':
            $y = substr( $date, 0, 4 );
            $d = substr( $date, 4, 2 );
            $m = substr( $date, 6, 2 );
            break;
			default:
				$match=false;
        }
		//debug($date);
		//debug($m.' '.$d.' '.$y);
        return $match ? checkdate( $m, $d, $y ) : false;
    }	
}
?>