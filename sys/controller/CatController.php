<?php

defined('UFM_RUN') or die('No direct script access allowed.');

class CatController extends UController {

    function actionIndex() {
		
        $cat = isset($_REQUEST['cat']) ? $_REQUEST['cat'] : "";
        $pagetypeID = isset($_REQUEST['pagetype_id']) ? (int) $_REQUEST['pagetype_id'] : 0;

            //Using location table
            $catArray = explode(",", $cat);
            $catModel = new UCategory();
            //Cat
            $level1 = $catModel->getCatsByLevel(1);
			
            foreach ($level1 as $item) {
                $item->select = 0;
                if (in_array($item->cat_id, $catArray)) {
                    $item->select = 1;
                }
            }

            //Subcat
            foreach ($level1 as $catItem) {
                $subcatList = $catModel->getSubcat($catItem->cat_id);
                foreach ($subcatList as $item) {
                    $item->select = 0;
                    if (in_array($item->cat_id, $catArray)) {
                        $item->select = 1;
                    }
                }
                $catItem->subcatList = $subcatList;
            }
            $this->assign("cats", $level1);
            $this->assign("generateWithLocations", true);

        $this->layout = 'directHtml';
        //page ID
        $this->assign("pagetypeID", $pagetypeID);

        $this->display();
    }

}
