<?php 
namespace Controller;

class Gallery extends Controller
{	
	function __construct()
	{
		parent::__construct();
		$model_user = new \Model\User();
		if(!$model_user->isLogged()) {
			header('Location: /account/login');
		}
		$this->user_id = $model_user->getUserId();
	}
	public function index()
	{
		$model_image = new \Model\Image();
		$user_id = $this->user_id;

		$images = [];
		$images = $model_image->getImages($user_id);
		if($images) {
			$images = array_map(function($image){
				$image['src'] = str_replace(DIR_IMAGE, '/image/', $image['path']);
				return $image;
			}, $images);	
		}
		

		$this->data['user_id'] = $this->user_id;
		$this->data['images'] = $images;
		$this->render('gallery');
	}

	public function upload()
	{	
		$model_file = new \Model\File();
		$model_image = new \Model\Image();

		$results = [];
		$errors = [];			
		$images = [];
		$files = [];
		if (!empty($_FILES)) {
			foreach ($_FILES['images']['name'] as $index => $file) {
				if(!$_FILES['images']['size'][$index]) {
					$errors[] = $_FILES['images']['name'][$index] . ' is empty';
					continue;
				}
				if($_FILES['images']['error'][$index]) {
					$errors[] = $_FILES['images']['name'][$index] . ' - error uploading file';
					continue;
				}

				$files[] = [
					'name'     => $_FILES['images']['name'][$index],
					'type'     => $_FILES['images']['type'][$index],
					'tmp_name' => $_FILES['images']['tmp_name'][$index],
					'size'     => $_FILES['images']['size'][$index]
				];
			}
			foreach ($files as $file) {
				if(!$model_file->validateImage($file)) {
					$errors[] = $file['name'] . ' is not an image';
					continue;
				}
				$file_id = $model_file->addFile($file);
				if($file_id) {
					$images[] = [
						'name' => $file['name'],
						'file_id' => $file_id
					];
				} else {
					$errors[] = 'cannot upload file';
				}
			}
			foreach ($images as $image) {
				$image_id = $model_image->addImage($image['file_id'], $this->user_id);
				$results[] = $image_id;
			}
		}
		if($results) {
			$results = array_map(function($result) use ($model_image){
				$data = $model_image->getImage($result);
				$image = [
					'image_id' => $data['image_id'],
					'src' => str_replace(DIR_IMAGE, '/image/', $data['path']),
					'title' => strval($data['title']),
					'description' => strval($data['description'])
				];
				
				return $image;
			}, $results);	
		}
		$this->data['errors'] = $errors;
		$this->data['images'] = $results;
		$this->toJson();
	}
	public function edit()
	{
		$model_image = new \Model\Image();
		$errors = [];
		$notifications = [];
		$image = false;

		if(!empty($_POST['image_id']) && $model_image->validateUserImage($this->user_id, $_POST['image_id'])) {
			$image_id = $_POST['image_id'];
			$title = '';
			$description = '';

			if(!empty($_POST['title'])) {
				$title = $_POST['title'];
			}

			if(!empty($_POST['description'])) {
				$description = $_POST['description'];
			}
			$model_image->editImage($image_id, $title, $description);
			$data = $model_image->getImage($image_id);
			$image = [
				'image_id' => $data['image_id'],
				'src' => str_replace(DIR_IMAGE, '/image/', $data['path']),
				'title' => strval($data['title']),
				'description' => strval($data['description'])
			];

			$notifications[] = 'Image updated';
		} else {
			$errors[] = 'Wrong image data';
		}
		$this->data['image'] = $image;
		$this->data['errors'] = $errors;
		$this->data['notifications'] = $notifications;

		$this->toJson();
	}

	public function delete()
	{
		$model_image = new \Model\Image();
		$errors = [];
		$notifications = [];

		if(!empty($_POST['images'])) {
			foreach ($_POST['images'] as $image_id) {
				if($model_image->validateUserImage($this->user_id, $image_id)) {
					$model_image->deleteImage($image_id);
					$notifications[] = 'Image deleted';
				} else {
					$errors[] = 'Permission denied';
				}				
			}
		}

		$this->data['errors'] = $errors;
		$this->data['notifications'] = $notifications;

		$this->toJson();
	}

	public function search()
	{
		$model_image = new \Model\Image();
		$errors = [];
		$notifications = [];
		$images = [];
		$data = [];

		if(!empty(trim($_GET['search']))) {
			$data['search'] = trim($_GET['search']);						
		}

		$results = $model_image->getImages($this->user_id, $data);
		if($results) {
			foreach ($results as $result) {
				$images[] = (int) $result['image_id'];
			}
		}
		
		$this->data['images'] = $images;
		$this->data['errors'] = $errors;
		$this->data['notifications'] = $notifications;

		$this->toJson();
	}
}