<?php 
namespace Model;

class File extends Model
{	
	function __construct()
	{
		parent::__construct();
	}
	
	public function addFile($file)
	{
		$hash = sha1_file($file['tmp_name']);
		$file_id = $this->getFileByHash($hash);
		if($file_id) {
			return $file_id;
		}
		$upload_dir = DIR_IMAGE;
		$name =  basename($file['name']);
		$folder = $upload_dir . $this->generateFolderName();
		if (!file_exists($folder)) {
		    mkdir($folder);       
		}
		$upload_file = $folder . $name;
		if (file_exists($upload_file)) {
		    return $this->addFile($file);
		}
		
		if (move_uploaded_file($file['tmp_name'], $upload_file)) {
		    $this->db->query("INSERT INTO file (name, path) VALUES ('" . $this->db->escape($name) . "', '" . $this->db->escape($upload_file) . "')");
		    $file_id = $this->db->getLastId();		    
		    $this->db->query("INSERT INTO file_hash (file_id, hash) VALUES ('" . (int)$file_id . "', '" . $this->db->escape($hash) . "')");
		    return $file_id;
		}
	}

	public function validateImage($file) {
		$finfo = finfo_open(FILEINFO_MIME_TYPE);
		$myme_type = finfo_file($finfo, $file['tmp_name']);		
		finfo_close($finfo);
		if(strrpos($myme_type, 'image') !== false) {
			return true;
		}		
	}

	private function getFileByHash($hash)
	{
		$query = $this->db->query("SELECT file_id FROM file_hash WHERE hash = '" . $this->db->escape($hash) . "'");
		if($query->num_rows) {
			return $query->row['file_id'];
		}
	}

	private function generateFolderName()
	{
		$permitted_chars = 'abcdefghijklmnopqrstuvwxyz';
		return substr(str_shuffle($permitted_chars), 0, 5) . '/';
	}
}