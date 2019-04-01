<?php

defined('UFM_RUN') or die('No direct script access allowed.');

class Edm_logController extends UController {

    function actionIndex() {
		
        $campaign = $_GET['campaign'];
		$log = '';
		
		if($_GET['exe']=='start' && $campaign){
			$sql = "SELECT DISTINCT content, SUM(hits) count
					FROM tbl_log_campaign a 
					WHERE campaign = '".$campaign."' 
					GROUP BY content
					ORDER BY content ASC";
			$rs=uDb()->findList($sql);
			
			if($rs){
				$log = '<table style="width:100%">
						<tr>
							<td style="border:1px solid black;padding:5px;">Content Name</td>
							<td style="border:1px solid black;padding:5px;">Count</td>
						</tr>
						';
				foreach($rs as $v){
					$log .= '<tr>
								<td style="border:1px solid black;padding:5px;">'.$v->content.'</td>
								<td style="border:1px solid black;padding:5px;">'.$v->count.'</td>
							</tr>';
				}
				$log .= '</table>';
			}else{
				$log = 'No data found. 
						<a href="#" onClick="javascript:window.close();">Close</a>';
			}
			
			echo $log;
		}
	}

}
