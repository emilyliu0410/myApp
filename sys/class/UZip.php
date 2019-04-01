<?php
class UZip
{
    
    public $successFiles = array();
    public $faildFiles = array();
    /**
     * Unzip the source_file in the destination dir
     *
     * @param   string      The path to the ZIP-file.
     * @param   string      The path where the zipfile should be unpacked, if false the directory of the zip-file is used
     * @param   boolean     Indicates if the files will be unpacked in a directory with the name of the zip-file (true) or not (false) (only if the destination directory is set to false!)
     * @param   boolean     Overwrite existing files (true) or not (false)
     *
     * @return  boolean     Succesful or not
     */
    function unzip($src_file, $dest_dir = false, $create_zip_name_dir = true, $overwrite = true)
    {
        $rt = false;
        if (!function_exists("zip_open")) 
		{
			if (version_compare(phpversion(), "5.2.0", "<")) $infoVersion = "(use PHP 5.2.0 or later)";
            
			$this->error = "You need to install/enable the php_zip.dll extension $infoVersion";

		}
		else
		{
            
            if (!is_resource($zip = zip_open($src_file))) 
			{
                $this->error = "File not exits or  isn't Zip Archive :" . $src_file;
            } 
			else 
			{
                $splitter = ($create_zip_name_dir === true) ? "." : "/";
                if ($dest_dir === false)
                    $dest_dir = substr($src_file, 0, strrpos($src_file, $splitter)) . "/";
                
                // Create the directories to the destination dir if they don't already exist
                $this->createDirs($dest_dir);
                
				$this->successFiles = array();
                // For every file in the zip-packet
                while ($zip_entry = zip_read($zip)) {
                    // Now we're going to create the directories in the destination directories
                    
                    // If the file is not in the root dir
                    $pos_last_slash = strrpos(zip_entry_name($zip_entry), "/");
                    if ($pos_last_slash !== false) {
                        // Create the directory where the zip-entry should be saved (with a "/" at the end)
                        $this->createDirs($dest_dir . substr(zip_entry_name($zip_entry), 0, $pos_last_slash + 1));
                    }
                    
                    // Open the entry
                    if (zip_entry_open($zip, $zip_entry, "r")) {
                        
                        // The name of the file to save on the disk
                        $file_name = $dest_dir . zip_entry_name($zip_entry);
                        
                        // Check if the files should be overwritten or not
                        if ($overwrite === true || $overwrite === false && !is_file($file_name)) {
                            // Get the content of the zip entry
                            $fstream = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
                            
                            if (!is_dir($file_name))
                                file_put_contents($file_name, $fstream);
                            // Set the rights
                            if (file_exists($file_name)) {
                                chmod($file_name, 0777);
                                $this->successFiles[] 	= 	$file_name;
                            } else {
                                $this->faildFiles[] 	=	$file_name;
                            }
                        }
                        
                        // Close the entry
                        zip_entry_close($zip_entry);
                    }
                }
                // Close the zip-file
                zip_close($zip);
				$rt = true;
            }
        } 
		return $rt;
    }
    function createDirs($path)
    {
        if (!is_dir($path)) {
            $directory_path = "";
            $directories    = explode("/", $path);
            array_pop($directories);
            
            foreach ($directories as $directory) {
                $directory_path .= $directory . "/";
                if (!is_dir($directory_path)) {
                    mkdir($directory_path);
                    chmod($directory_path, 0777);
                }
            }
        }
    }
    
    function lisDir($start_dir = '.', $exclude = false)
    {
        $files = array();
        if (is_dir($start_dir)) {
            $fh = opendir($start_dir);
            while (($file = readdir($fh)) !== false) {
                if (strcmp($file, '.') == 0 || strcmp($file, '..') == 0)
                    continue;
                //echo $start_dir.$file.'<br>';
                if ($exclude && in_array($start_dir . $file, $exclude)) {
                    echo $start_dir . $file . '<br>';
                    continue;
                }
                $filepath = $start_dir . '/' . $file;
                if (is_dir($filepath))
                    $files = array_merge($files, $this->lisDir($filepath));
                else
                    array_push($files, $filepath);
                /*
                 */
            }
            closedir($fh);
        } else {
            $files = false;
        }
        return $files;
    }
    
}