<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

  function __construct() {
      parent::__construct();
      $this->load->helper('url');
  }

	public function index()
	{
		$this->load->view('dashboard');
	}

  public function diff()
    {
        $dir1 = 'v1';
        $dir2 = 'v2';

        if(!is_dir($dir1) || !is_dir($dir2)) {
            return array('status' => true, 'message' => 'Folder should be a directory');
        }
        //get all files in folder in full path
        $files_dir1 = $this->getfolderTreeView($dir1);
        $files_dir2 = $this->getfolderTreeView($dir2);

        //files available in both version
        $files = array_intersect($files_dir1, $files_dir2);

        //files exist in version 1 but not in version 2 - deleted files
        $added_files = array_values(array_diff($files_dir2,$files));

        //files exist in version 2 but not in version 1 - added files
        $deleted_files = array_values(array_diff($files_dir1,$files));

        // check files in two folder and check both files differs
        $fileswithdiff = $this->getFilesWithDifference($files, $dir1, $dir2);

        $changed_files =  array();
        foreach ($fileswithdiff as $file) {
            $changed_files[] = $this->getFileDiff($file, $dir1, $dir2);
        }
        $result  = array ('added' => $added_files , 'deleted' => $deleted_files, 'changed' =>$changed_files );
        echo json_encode($result);
    }

    private function getfolderTreeView($dir) {
        $files = [];
        foreach(array_diff(scandir($dir), array('..', '.')) as $filename) { // .. & . to avoid current and parent directory
            $filePath = $dir.'/'.$filename;
            if (is_file($filePath)) {
                $files[] = $filename;
                continue;
            }
            if (is_dir($filePath)) {
                foreach ($this->getfolderTreeView($filePath) as $subfilename) { // recursive call for subdirectory
                    $files[] = $filename .'/'.$subfilename;
                }
            }
        }
        return $files;
    }

    private function getFilesWithDifference($files, $dir1, $dir2)
    {
        $fileswithdiff = array();
        foreach ($files as $file) {
            // take hash of two files in both dir to check is same or not
            if(hash_file('crc32', $dir1.'/'.$file) != hash_file('crc32', $dir2.'/'.$file) ){
                $fileswithdiff[] = $file;
            }
        }
        return $fileswithdiff;
    }

    private function getFileDiff($file, $dir1, $dir2)
    {
        $file_v1 = file($dir1.'/'.$file, FILE_IGNORE_NEW_LINES);
        $file_v2 = file($dir2.'/'.$file, FILE_IGNORE_NEW_LINES);
        $addedDiff = array_diff($file_v2,$file_v1);
        $deletedDiff = array_diff($file_v1,$file_v2);
        return array('file' => $file, 'added' => $addedDiff, 'deleted' => $deletedDiff);
    }
}
