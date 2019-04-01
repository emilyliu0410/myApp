<?php

defined('UFM_RUN') or die('No direct script access allowed.');

class DistrictController extends UController {

    function actionIndex() {
//        $district = isset($_REQUEST['dist']) ? $_REQUEST['dist'] : "";
//        $area = isset($_REQUEST['area']) ? $_REQUEST['area'] : "";
        $location = isset($_REQUEST['location']) ? $_REQUEST['location'] : "";
        $pagetypeID = isset($_REQUEST['pagetype_id']) ? (int) $_REQUEST['pagetype_id'] : 0;

//        if (!empty($location)) {
            //Using location table
            $locationArray = explode(",", $location);
            
            $locationModel = new ULocation();
            //District
            $locations = $locationModel->getLocationsByLevel(1);
            foreach ($locations as $item) {
                $item->select = 0;
                if (in_array($item->id, $locationArray)) {
                    $item->select = 1;
                }
            }
            //Area
            foreach ($locations as $locationItem) {
                $areaList = $locationModel->getAreasByDistrictID($locationItem->id);
                foreach ($areaList as $item) {
                    $item->select = 0;
                    if (in_array($item->id, $locationArray)) {
                        $item->select = 1;
                    }
                }
                $locationItem->areaList = $areaList;
            }
            
            $this->assign("locations", $locations);
            $this->assign("generateWithLocations", true);
//        } else {
//            $districtArray = explode(",", $district);
//            $areaArray = explode(",", $area);
//
//            $model = new UDistrict();
//            //District
//            $districts = $model->getDistricts();
//            foreach ($districts as $item) {
//                $item->select = 0;
//                if (in_array($item->district_id, $districtArray)) {
//                    $item->select = 1;
//                }
//            }
//            //Area
//            foreach ($districts as $district) {
//                $areaList = $model->getAreasByDistrictID($district->district_id);
//                foreach ($areaList as $item) {
//                    $item->select = 0;
//                    if (in_array($item->area_id, $areaArray)) {
//                        $item->select = 1;
//                    }
//                }
//                $district->areaList = $areaList;
//            }
//            $this->assign("districts", $districts);
//            $this->assign("generateWithLocations", false);
//        }
        $this->layout = 'directHtml';

        //page ID
        $this->assign("pagetypeID", $pagetypeID);

        $this->display();
    }

}
