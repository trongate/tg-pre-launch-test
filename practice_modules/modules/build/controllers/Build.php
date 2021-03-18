<?php
class Build extends Trongate {

	function index() {
		$this->view('index');
	}

	function clear_members_stuff() {
		//delete the table
		$sql = 'drop table members';
	    $this->model->query($sql);

		echo 'done<br><br><br>';
		echo anchor('build', 'Go home');
		
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

function chmod_r($path) {
    $dir = new DirectoryIterator($path);
    foreach ($dir as $item) {
        chmod($item->getPathname(), 0777);
        if ($item->isDir() && !$item->isDot()) {
            $this->chmod_r($item->getPathname());
        }
    }
}






}