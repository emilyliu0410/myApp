<?php
defined( 'UFM_RUN' ) or die( 'No direct script access allowed.' );

class BatchResizeimgController extends UController
{
	
	function actionIndex()
	{
		$arr = array(
			'/cms/spots_photo/original' => '/cms/images/spot',
			// '/cms/tour_photo/site_photo/original' => '/cms/images/tour',
			// '/cms/tour_photo/theme' => '/cms/images/tour',
		);
		
		set_time_limit(1000);
		
		foreach ($arr as $from => $to)
		{
			$this->batch($from, $to);
		}
		
		exit();		
	}
	function batch($from,$to)
	{
		$skip = '<span style="color:#00f;">skip!</span>';
		$ok = '<span style="color:#090;">OK</span>';
		$fail = '<span style="color:#f00;">Failed</span>';
		
		$srcDir = UAPP_BASE_DIR.$from;
		
		if(!is_dir($srcDir))
		{
			$this->show($srcDir, ' <span style="color:#f00;"> not exists! </span> ');
			return;
		}
		$handle = opendir($srcDir);
		if (!$handle)
		{
			debug('Can not open the dir :'.$srcDir);
		}
		else 
		{
			
			// $dstDir = UAPP_BASE_DIR.$to.'/1024/ut';
			// if(!is_dir($dstDir)) mkdir($dstDir,0755,true);
			// while (false !== ($file = readdir($handle))) 
			// {
				// if ($file != "." && $file != "..") 
				// {
					// $dstFile = $dstDir.'/'.$file;
					// $result = $skip;
					// if(!is_file($dstFile))
					// {
						// $result = copy($srcDir.'/'.$file,$dstFile) ? $ok : $fail;
					// }
					
					// $this->show($file, 'copy '.$result);
					
				// }
			// }
			
			// rewinddir($handle);
			// $dstDir = UAPP_BASE_DIR.$to.'/w600/ut';
			// if(!is_dir($dstDir)) mkdir($dstDir,0755,true);
			// while (false !== ($file = readdir($handle))) 
			// {
				// if ($file != "." && $file != "..") 
				// {
					// $dstFile = $dstDir.'/'.$file;
					// $result = $skip;
					// if(!is_file($dstFile))
					// {
						// $result = uScaleResize($srcDir.'/'.$file,$dstDir.'/'.$file,600) ? $ok : $fail;
					// }
					// $this->show($file, 'uScaleResize '.$result);
					
				// }
			// }
			
			// rewinddir($handle);
			// $dstDir = UAPP_BASE_DIR.$to.'/300x200/ut';
			// if(!is_dir($dstDir)) mkdir($dstDir,0755,true);
			// while (false !== ($file = readdir($handle))) 
			// {
				// if ($file != "." && $file != "..") 
				// {
					// $dstFile = $dstDir.'/'.$file;
					// $result = $skip;
					// if(!is_file($dstFile))
					// {
						// $result = uCenterResize($srcDir.'/'.$file,$dstDir.'/'.$file,300,200) ? $ok : $fail;
					// }
					// $this->show($file, 'uCenterResize '.$result);
					
				// }
			// }
			
			rewinddir($handle);
			$dstDir = UAPP_BASE_DIR.$to.'/120x120/ut';
			if(!is_dir($dstDir)) mkdir($dstDir,0755,true);
			while (false !== ($file = readdir($handle))) 
			{
				if ($file != "." && $file != "..") 
				{
					$dstFile = $dstDir.'/'.$file;
					$result = $skip;
					if(!is_file($dstFile))
					{
						$result = uCenterResize($srcDir.'/'.$file,$dstDir.'/'.$file,120,120) ? $ok : $fail;
					}
					$this->show($file, 'uCenterResize '.$result);
					
				}
			}
			
			closedir($handle);
		}
	}
	function show($file,$result)
	{
		echo "<p>$file : $result </p>";
	}
/*
	function actionIndex()
	{
		$skip = '<span style="color:#00f;">SKIP</span>';
		$ok = '<span style="color:#090;">OK</span>';
		$fail = '<span style="color:#f00;">Failed</span>';
		
		$srcDir = UAPP_BASE_DIR.'/cms/spots_photo/original';
		
		$handle = opendir($srcDir);
		if (!$handle)
		{
			debug('Can not open the dir :'.$srcDir);
		}
		else 
		{
			
			$dstDir = UAPP_BASE_DIR.'/cms/images/spot/1024';
			if(!is_dir($dstDir)) mkdir($dstDir,0755,true);
			while (false !== ($file = readdir($handle))) 
			{
				if ($file != "." && $file != "..") 
				{
					$dstFile = $dstDir.'/'.$file;
					$result = $skip;
					if(!is_file($dstFile))
					{
						$result = copy($srcDir.'/'.$file,$dstFile) ? $ok : $fail;
					}
					
					$this->show($file, 'copy '.$result);
					
				}
			}
			
			rewinddir($handle);
			$dstDir = UAPP_BASE_DIR.'/cms/images/spot/w600';
			if(!is_dir($dstDir)) mkdir($dstDir,0755,true);
			while (false !== ($file = readdir($handle))) 
			{
				if ($file != "." && $file != "..") 
				{
					$dstFile = $dstDir.'/'.$file;
					$result = $skip;
					if(!is_file($dstFile))
					{
						$result = uScaleResize($srcDir.'/'.$file,$dstDir.'/'.$file,600) ? $ok : $fail;
					}
					$this->show($file, 'uScaleResize '.$result);
					
				}
			}
			
			rewinddir($handle);
			$dstDir = UAPP_BASE_DIR.'/cms/images/spot/300x200';
			if(!is_dir($dstDir)) mkdir($dstDir,0755,true);
			while (false !== ($file = readdir($handle))) 
			{
				if ($file != "." && $file != "..") 
				{
					$dstFile = $dstDir.'/'.$file;
					$result = $skip;
					if(!is_file($dstFile))
					{
						$result = uCenterResize($srcDir.'/'.$file,$dstDir.'/'.$file,300,200) ? $ok : $fail;
					}
					$this->show($file, 'uCenterResize '.$result);
					
				}
			}
			
			closedir($handle);
		}
		exit();		
	}
 */
	
}