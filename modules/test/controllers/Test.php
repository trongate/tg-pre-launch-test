<?php
class Test extends Trongate {

	function json() {
		$this->view('sample_json');
	}

	function restart() {
		echo '<title>restart</title>';
		echo date('l jS \o\f F Y \a\t H:i:sa', time());
		echo '<br><br><br>';
		$donor_drivers = '../practice_modules/new_modules/drivers';
		$new_drivers_path = '../modules/drivers';

		$donor_licenses = '../practice_modules/new_modules/licenses';
		$new_licenses_path = '../modules/licenses';

		$files_to_clear[] = $new_drivers_path;
		$files_to_clear[] = $new_licenses_path;
		foreach ($files_to_clear as $file_to_clear) {
			$this->rrmdir($file_to_clear);
		}

		$this->copy_directory($donor_drivers, $new_drivers_path);
		$this->copy_directory($donor_licenses, $new_licenses_path);

		//display 'done/finished' message
		$sql_path = $new_drivers_path.'/drives_and_licenses_setup.sql';
		$sql = file_get_contents($sql_path);
		$this->run_sql($sql);

		//now delete the sql file
		unlink($sql_path);

		//delete the module relations dir
		$module_relations_dir = '../modules/module_relations';
		$this->rrmdir($module_relations_dir);

		echo 'Finished<br><br>';
		echo anchor('test/restart', 'restart now');
	}

	function run_sql($sql) {
        $this->model->exec($sql);
    }



	function copy_directory($src,$dst) {
	    $dir = opendir($src);
	    @mkdir($dst);
	    while(false !== ( $file = readdir($dir)) ) {
	        if (( $file != '.' ) && ( $file != '..' )) {
	            if ( is_dir($src . '/' . $file) ) {
	                $this->recurse_copy($src . '/' . $file,$dst . '/' . $file);
	            }
	            else {
	                copy($src . '/' . $file,$dst . '/' . $file);
	            }
	        }
	    }
	    closedir($dir);
	}

 function rrmdir($dir) { 
   if (is_dir($dir)) { 
     $objects = scandir($dir);
     foreach ($objects as $object) { 
       if ($object != "." && $object != "..") { 
         if (is_dir($dir. DIRECTORY_SEPARATOR .$object) && !is_link($dir."/".$object))
           $this->rrmdir($dir. DIRECTORY_SEPARATOR .$object);
         else
           unlink($dir. DIRECTORY_SEPARATOR .$object); 
       } 
     }
     rmdir($dir); 
   } 
 }

	function recurse_copy($src,$dst, $childFolder='') { 

	    $dir = opendir($src); 
	    mkdir($dst);
	    if ($childFolder!='') {
	        mkdir($dst.'/'.$childFolder);

	        while(false !== ( $file = readdir($dir)) ) { 
	            if (( $file != '.' ) && ( $file != '..' )) { 
	                if ( is_dir($src . '/' . $file) ) { 
	                    $this->recurseCopy($src . '/' . $file,$dst.'/'.$childFolder . '/' . $file); 
	                } 
	                else { 
	                    copy($src . '/' . $file, $dst.'/'.$childFolder . '/' . $file); 
	                }  
	            } 
	        }
	    }else{
	            // return $cc; 
	        while(false !== ( $file = readdir($dir)) ) { 
	            if (( $file != '.' ) && ( $file != '..' )) { 
	                if ( is_dir($src . '/' . $file) ) { 
	                    $this->recurseCopy($src . '/' . $file,$dst . '/' . $file); 
	                } 
	                else { 
	                    copy($src . '/' . $file, $dst . '/' . $file); 
	                }  
	            } 
	        } 
	    }
	    
	    closedir($dir); 
	}

	function _prep_path($path, $remove_apppath=null) {

		if (isset($remove_apppath)) {
			$path = str_replace(APPPATH, '', $path);
		}


		$ditch = '/';
		$replace = '\\';
		$new_path = str_replace($ditch, $replace, $path);
		$new_path = str_replace($replace, $ditch, $path); //forwardslashes

		return $new_path;
	}

	function restartX() {
		echo anchor('test/delete', 'restart now');
	}

	function delete() {
		$dir = APPPATH.'modules/module_relations';
   		$this->_ditch($dir);
   		redirect('test/restart');
    }

	function _ditch($dir) { 
		if (is_dir($dir)) { 
			$objects = scandir($dir);
				foreach ($objects as $object) { 
				if ($object != "." && $object != "..") { 
				 if (is_dir($dir. DIRECTORY_SEPARATOR .$object) && !is_link($dir."/".$object))
				   $this->_ditch($dir. DIRECTORY_SEPARATOR .$object);
				 else
				   unlink($dir. DIRECTORY_SEPARATOR .$object); 
				} 
			}
			rmdir($dir); 
		} 
	}



function xcopy($source, $dest, $permissions = 0777)
{
    $sourceHash = $this->hashDirectory($source);
    // Check for symlinks
    if (is_link($source)) {
        return symlink(readlink($source), $dest);
    }

    // Simple copy for a file
    if (is_file($source)) {
        return copy($source, $dest);
    }

    // Make destination directory
    if (!is_dir($dest)) {
        mkdir($dest, $permissions);
    }

    // Loop through the folder
    $dir = dir($source);
    while (false !== $entry = $dir->read()) {
        // Skip pointers
        if ($entry == '.' || $entry == '..') {
            continue;
        }

        // Deep copy directories
        if($sourceHash != hashDirectory($source."/".$entry)){
             $this->xcopy("$source/$entry", "$dest/$entry", $permissions);
        }
    }

    // Clean up
    $dir->close();
    return true;
}

// In case of coping a directory inside itself, there is a need to hash check the directory otherwise and infinite loop of coping is generated

function hashDirectory($directory){
    if (! is_dir($directory)){ return false; }

    $files = array();
    $dir = dir($directory);

    while (false !== ($file = $dir->read())){
        if ($file != '.' and $file != '..') {
            if (is_dir($directory . '/' . $file)) { $files[] = hashDirectory($directory . '/' . $file); }
            else { $files[] = md5_file($directory . '/' . $file); }
        }
    }

    $dir->close();

    return md5(implode('', $files));
}


}