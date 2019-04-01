<?php
defined( 'UFM_RUN' ) or die( 'No direct script access allowed.' );
error_reporting(E_ERROR);

class ApiHomesliderController extends UController
{	
	function actionIndex()
	{
		$_debug = $_REQUEST['debug'];
		
		$sliders = Slider::getSlider();

		if($sliders){
			$result = array('success'=>1,'message'=>'', 'result'=>$sliders);
		}else{
			$result = array('success'=>0,'message'=>'Internal Error');
		}
		
		//debug(123);
		if($_debug){
			debug($result);
		}else{
			echo  uConvertToJson($result);
		}
	}
}

class Slider
{
	function getSlider(){
		$rt = false;
		$model = new UHomeSlider();
		$slider = $model->findLatestOne();
		if($slider){
			$model = new UHomeSliderPhoto();
			$sliderPhotos = $model->getBySliderId($slider->slider_id);
			$temp = false;
			foreach($sliderPhotos as $k=>$v){
				$temp['title'] = $v->text;
				$temp['url'] = $v->url;
				$temp['img'] = UAPP_HOST.'/cms/images/slider_photo/1220x686/'.$v->photo_name;				
				$rt[] = $temp;
			}
		}
		
		return $rt;
	}
}