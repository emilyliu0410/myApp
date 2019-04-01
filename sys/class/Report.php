<?php
class Report{

	const REPORT_FROM_EMAIL = 'noreply2@smtp.mail1.ulifestyle.com.hk';
	const REPORT_FROM_NAME = 'UHK Reports';
	
	private $report;
	private $fileName = array();
	private $mailFrom;
	private $mailFromName;
	private $mailTo;
	private $mailToName;
	private $mailTitle;
	private $mailContent;
	private $reportURL = array();
	private $fileType;
	private $hasNoAttachment = FALSE;

	public function __construct($reportName, $dirName, $reportPre=null, $writeMode="w", $fileType="csv"){
		// echo $reportName;
		// echo "<br>";
		
		$this->fileType = in_array($fileType,array('csv','xls'))?$fileType:'csv';
		$this->addMailAttachment($dirName, $reportPre, $writeMode);
		//debug($this->fileName);
		$this->mailFrom = self::REPORT_FROM_EMAIL;
		$this->mailFromName = self::REPORT_FROM_NAME;
		$this->mailTitle = $reportName." reported on (".date("Y/m/d").")";
		$this->mailContent = "Dear All,<br>
							<br> 
							Attached the report on ".date("d M Y").".<br>
							<br>
							Regards,<br>
							UTB Support Team";
	}
	
	public function addMailAttachment($dirName, $reportPre=null, $writeMode="w"){
		
		$report_dir = UAPP_BASE_DIR.'/sys/report/'.$dirName;
		
		if (!is_dir($report_dir)){
			mkdir($report_dir);
		}
		if(!$reportPre){
			$reportPre = $dirName;
		}

		$filename = $reportPre.'-'.date('Ymd').'.'.$this->fileType;
		
		
		$this->reportURL[] = UAPP_BASE_URL.'/sys/report/'.$dirName.'/'.$filename;
		
		$this->fileName[] = $report_dir.'/'.$filename;

		switch($this->fileType){
			case 'xls':
				$this->formExcelFile();
				break;
			default:
				$this->formCsvFile($writeMode);
				break;
		}
	}
	
	private function formExcelFile($sheet_index=0){
		require_once 'sys/plugin/PHPExcel/PHPExcel.php';

		// Create new PHPExcel object
		$this->report = new PHPExcel();
	}
	
	public function createExcelSheet($title=null){
		$sheet_index = $this->report->getSheetCount();
		$sheet = $this->report->createSheet($sheet_index); 
		$this->report->setActiveSheetIndex($sheet_index);
		$this->setExcelActiveSheetTitle($title);
	}
	
	public function setExcelActiveSheetTitle($title=null){
		$title=$title?$title:'Sheet';
		$this->report->getActiveSheet()->setTitle($title);
	}
	
	private function formCsvFile($writeMode="w"){
		$this->report = fopen($this->fileName[count($this->fileName)-1], $writeMode);
	}
	
	
	public function getReportName($key=null){
		if($key==null) $key=0;
		return $this->fileName[$key];
	}
	
	public function getReportURL($key=null){
		if($key==null) $key=0;
		return $this->reportURL[$key];
	}
		
	public function getMailFrom(){
		return $this->mailFrom;
	}	
	public function setMailFrom($from){
		$this->mailFrom = $from;
	}
	
	public function getMailFromName(){
		return $this->mailFromName;
	}	
	public function setMailFromName($fromName){
		$this->mailFromName = $fromName;
	}
	
	public function getMailTo(){
		return $this->mailTo;
	}	
	public function setMailTo($mailTo){
		$this->mailTo = $mailTo;
	}	
	
	public function getMailToName(){
		return $this->mailToName;
	}	
	public function setMailToName($mailToName){
		$this->mailToName = $mailToName;
	}
	
	public function setMailToByArray($mailToArray){
		$mail_to = false;
		$mail_to_name = false;
		foreach($mailToArray as $k=>$v){
			$mail_to .= $v[0].';';
			$mail_to_name .= $v[1].';';
		}
		$this->mailTo = $mail_to;
		$this->mailToName = $mail_to_name;
	}
	
	public function getMailTitle(){
		return $this->mailTitle;
	}	
	public function setMailTitle($title){
		$this->mailTitle = $title;
	}
	
	public function getMailContent(){
		return $this->mailContent;
	}	
	public function setMailContent($content){
		$this->mailContent = $content;
	}	

	public function getExcelCurrentColumn(){
		return $this->report->getActiveSheet()->getHighestColumn();
	}
	
	public function getExcelCurrentRow(){
		return $this->report->getActiveSheet()->getHighestRow();
	}
	
	public function getExcelCurrentCell(){
		return $this->getExcelCurrentColumn().$this->getExcelCurrentRow();
	}
	
	public function saveExcel(){
		$objWriter = PHPExcel_IOFactory::createWriter($this->report, 'Excel5');
		//store in folder report
		$objWriter->save($this->fileName[count($this->fileName)-1]);
	}
	
	public function write($content){
		switch($this->fileType){
			case 'xls':
				$current_cell_value =$this->report->getActiveSheet()->getCell($this->getExcelCurrentCell())->getValue();
				$this->report->getActiveSheet()->setCellValue($this->getExcelCurrentCell(),$current_cell_value.$content);
				break;
			default:
				fwrite($this->report, iconv('UTF-8', 'BIG5//TRANSLIT//IGNORE',($content)));
				break;
		}
	}
	
	public function writeLine($content){
		switch($this->fileType){
			case 'xls':
				$current_cell_value =$this->report->getActiveSheet()->getCell($this->getExcelCurrentCell())->getValue();
				$this->report->getActiveSheet()->setCellValue($this->getExcelCurrentCell(),$current_cell_value.$content);
				$this->report->getActiveSheet()->insertNewRowBefore($this->getExcelCurrentRow()+ 1, 1);
				break;
			default:
				fwrite($this->report, iconv('UTF-8', 'BIG5//TRANSLIT//IGNORE',$content)."\r\n");
				break;
		}
	}

	public function writeCleanWord($content){
		switch($this->fileType){
			case 'xls':
				$str = $content;
				self::write($str);
				break;
			default:
				$str = iconv('UTF-8', 'BIG5//TRANSLIT//IGNORE', str_replace(",","¡A",$content));
				self::write($str);
				break;
		}
	}
	
	public function writeCleanWordLine($content){
		switch($this->fileType){
			case 'xls':
				$str = $content;
				self::writeLine($str);
				break;
			default:
				$str = iconv('UTF-8', 'BIG5//TRANSLIT//IGNORE', str_replace(",","¡A",$content));
				self::writeLine($str);
				break;
		}
	}
	
	public function writeArrayLine($arr){
		switch($this->fileType){
			case 'xls':
				$row = $this->getExcelCurrentRow();
				$i=0;
				foreach($arr as $v){
					$ColumnAndRow = $i+(int)$this->getExcelCurrentColumn();
					$this->report->getActiveSheet()->setCellValueByColumnAndRow($ColumnAndRow, $row, $v);
					$i++;
				}
				if($i==(count($arr))) 
					$this->report->getActiveSheet()->insertNewRowBefore($row + 1, 1);
				break;
			default:
				$str = false;
				foreach($arr as $v){
					$str .= iconv('UTF-8', 'BIG5//TRANSLIT//IGNORE', str_replace(",","¡A",$v)).",";
				}		
				
				fwrite($this->report, $str."\r\n");
				break;
		}
	}
	
	public function clean_string($in_string){
		switch($this->fileType){
			case 'xls':
				$string = $in_string;
				break;
			default:
				$string = str_replace(",","¡A",$in_string);
				$string = str_replace("\r\n"," ",$string);
				$string = str_replace("\r"," ",$string);
				$string = str_replace("\n"," ",$string);
				break;
		}
		return $string;
	}
	
	public function fileExists(){
		foreach($this->fileName as $v){
			$rt = file_exists($v);
			if(!$rt){
				//debug($v);
				break;
				}
		}
		return $rt;
	}

	public function getFilesize($key=null){
		if($key==null) $key=0;
		if($this->fileName[$key]){
			$filesize = number_format(filesize($this->fileName[$key]) / 1000, 2 );
		}else{
			$filesize = 0;
		}		
		return $filesize;
	}
	
	public function send(){
		
		$sent = 0;
		if(self::fileExists()||$this->hasNoAttachment){
			require_once('Mail.php');
			
			if($this->mailFrom && $this->mailFromName && $this->mailTo && $this->mailToName && $this->mailTitle && $this->mailContent){
				$sent = Mail::sendMail( $this->mailFrom,
										$this->mailFromName,
										$this->mailTo,
										$this->mailToName, 
										$this->mailTitle,
										$this->mailContent,
										$this->fileName
										);
			}
		}
		return $sent;
	}
	
	public function forceNoAttachment($key=null){
		$this->delete();
		$this->hasNoAttachment = TRUE;
	}
	
	public function delete($key=null){
		if($key==null) $key=0;
		unlink($this->fileName[$key]);
	}
	
	public function close(){
		switch($this->fileType){
			case 'xls':
				$this->saveExcel();
				break;
			default:
				fclose($this->report);
				break;
		}
	}

}

?>