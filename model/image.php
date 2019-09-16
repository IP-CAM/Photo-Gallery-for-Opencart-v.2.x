<?php 
namespace Model;

class Image extends Model
{	
	function __construct()
	{
		parent::__construct();
	}
	
	public function addImage($file_id, $user_id)
	{
		$this->db->query("INSERT INTO image (file_id, user_id, status) VALUES ('" . (int)$file_id . "', '" . (int)$user_id . "', 1)");
		$image_id = $this->db->getLastId();
		$this->db->query("INSERT INTO image_description (image_id) VALUES ('" . (int)$image_id . "')");
		return $image_id;
	}
	
	public function getImage($image_id)
	{
		$query = $this->db->query("SELECT i.*, id.title, id.description, f.* FROM image i LEFT JOIN image_description id ON (i.image_id = id.image_id) LEFT JOIN file f ON (i.file_id = f.file_id) WHERE  i.image_id = '". (int) $image_id ."' AND i.status = 1");
		if($query->num_rows) {
			return $query->row;
		}
	}

	public function getImages($user_id, $data = [])
	{
		$sql = "SELECT i.*, id.title, id.description, f.* FROM image i LEFT JOIN image_description id ON (i.image_id = id.image_id) LEFT JOIN file f ON (i.file_id = f.file_id) WHERE  i.user_id = '". (int) $user_id ."' AND i.status = 1 ";
		if(!empty($data['search'])) {
			$sql .= " AND (id.title LIKE '%" . $data['search'] . "%' OR id.description LIKE '%" . $data['search'] . "%') ";
		}
		$sql .= " ORDER BY i.image_id DESC ";
		$query = $this->db->query($sql);
		if($query->num_rows) {
			return $query->rows;
		}
	}
	
	public function editImage($image_id, $title, $description)
	{
		$this->db->query("UPDATE image_description SET title = '" . $this->db->escape($title) . "', description = '" . $this->db->escape($description) . "' WHERE image_id = ". (int) $image_id ."");
		return true;
	}
	
	public function deleteImage($image_id)
	{
		$this->db->query("UPDATE image SET status = 0 WHERE image_id = ". (int) $image_id ."");
		return true;
	}

	public function validateUserImage($user_id, $image_id) {
		$query = $this->db->query("SELECT * FROM image WHERE  image_id = '". (int) $image_id ."' AND user_id = '". (int) $user_id ."'");
		if($query->num_rows) {
			return true;
		}
	}
}