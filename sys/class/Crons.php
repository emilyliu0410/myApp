<?php
class Crons{

	const ERROR_FROM_EMAIL = 'utbsupport@hket.com';
	const ERROR_FROM_NAME = 'UTB Support Team';
	const ERROR_TO_EMAIL = 'hosiukei@hket.com';
	const ERROR_TO_NAME = 'Thomas Ho';
	
	private $taskName;
	private $controlId;
	private $logFile;
	
	public function __construct($taskName){
		$this->taskName = $taskName;
		$this->controlId = 0;		
	}
	
	public function start($lockProcess){
		$success = 0;
		if(self::allowExecute()){
			$log_dir = LOG_PATH.'crons/'.$this->taskName;
			if (!is_dir($log_dir)){
				mkdir($log_dir);
			}
			$file_pre=$this->taskName."-";
			$this->logFile = fopen("$log_dir/$file_pre".date("Y-m-d").".log","a");	
		
			self::log("Start Time of ".$this->taskName.": ".date("H:i:s, d-m-Y"));
			if(!$lockProcess){
				$success = 1;
			}else{
				$control_id = self::lockProcess();
				if($control_id){
					$this->controlId = $control_id;
					$success = 1;
				}else{
					self::log("Failed to get control ID, program skipped.");					
				}
			}
		}
		return $success;
	}
	
	
	public function log($content,$echo=0,$printTime=0){
		if($printTime){
			$content = $content." @ ".date("H:i:s, d-m-Y");
		}
		//write_file_log('crons/'.$this->taskName, $content, $this->taskName);
		fwrite($this->logFile, $content."\r\n");
		if($echo){
			echo $content."<br>";
		}
	}
	
	public function getLogPath($task=false){
		if(!$task){
			$task = $this->taskName;
		}
		$path = dirname(dirname(dirname(__FILE__).'..').'..').'/log/'.$task;
		return $path;
	}
		
	private function allowExecute(){
		$allow = 0;
		if($_GET["exe"]=="start"){
			$allow = 1;
		}else{
			self::log('Attempt execution failed. IP:'.GetIP().', time:'.date("H:i:s d-m-Y"),0);
		}
		return $allow;
	}

	private function lockProcess(){
		$control_id = 0;
		$task_name = $this->taskName;
		
		$sql = "SELECT	control_id
				FROM	tbl_crons_control
				WHERE	task_name = '$task_name'
					AND is_running = 1
				";
		$rs = dbQuery($sql);
		if(dbNumRows($rs) == 0){
			$insert_sql = "	INSERT INTO tbl_crons_control (task_name, start_time) VALUES ('$task_name', NOW())";
			dbQuery($insert_sql);
			$control_id = dbInsertId();
		}else{
			$message = "FAILED to get cron process lock! task: $task_name, control ID: $control_id";			
			self::sendErrorAlert($message);
		}		
		
		return $control_id;
	}
	
	private function unlockProcess(){		
		$unlock_ok = 1;
		$task_name = $this->taskName;
		$control_id = $this->controlId;
		
		$sql = "UPDATE	tbl_crons_control
				SET		is_running = 0,
						end_time = NOW()
				WHERE	control_id = $control_id
					AND task_name = '$task_name'
				";
		$rs = dbQuery($sql);
		if (dbAffectedRows() == 0){
			$message = "FAILED to unlock cron process! task: $task_name, control ID: $control_id";			
			self::sendErrorAlert($message);
			$unlock_ok = 0;
		}
		
		return $unlock_ok;
	}
	
	public function sendErrorAlert($message,$log_file=null){
		require_once('Mail.php');
		
		$taskName = $this->taskName;		
		$subject = "Cron Job Error Alert: $taskName (".date("d/m/Y H:i:s").")";
		$content = $message;		
		
		Mail::sendMail(	self::ERROR_FROM_EMAIL,
						self::ERROR_FROM_NAME,
						self::ERROR_TO_EMAIL,
						self::ERROR_TO_NAME,
						$subject,$content,$log_file);
	}
	
	public function readCsvToArray($filePath, $separator){		
		$openedFile = fopen($filePath,"r");
		$fileData = array();
		if($openedFile){
			while(!feof($openedFile)){				
				$line = fgets($openedFile);
				$items = explode($separator, $line);
				$fileData[] = $items;
			}
			fclose($openedFile);
		}			
		return $fileData;
	}	
	
	public function end(){
		if($this->controlId){
			self::unlockProcess();
		}
		self::log("End Time of ".$this->taskName.": ".date("H:i:s, d-m-Y"));
		self::log('---');
		
		fclose($this->logFile);
		require_once(dirname(dirname(dirname(__FILE__).'..').'..').'/bottom.php');
	}
}

?>