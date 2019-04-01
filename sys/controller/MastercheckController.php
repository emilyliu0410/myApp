<?php
defined( 'UFM_RUN' ) or die( 'No direct script access allowed.' );

class MastercheckController extends UController
{
    public $useMasterDb = true;

    function actionIndex()
	{
		$sql = "SELECT 1 FROM tbl_users LIMIT 1";
		$rs = uDb()->findList($sql);
		echo "OK";
	}
}