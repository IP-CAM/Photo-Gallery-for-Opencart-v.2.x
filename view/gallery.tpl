<!DOCTYPE html>
<html>
<head>
	<title>Gallery</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css" integrity="sha256-l85OmPOjvil/SOvVt3HnSSjzF1TUMyT9eV0c2BzEGzU=" crossorigin="anonymous" />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
	<link rel="stylesheet" type="text/css" href="/css/main.css">
</head>
<body>
	<div class="notifications"></div>
	<header class="header">
		<div class="container">
			<div class="header__upload">
				<a class="header__link button_upload" href="#">Upload</a>
			</div>			
			<div class="search form__input-group">
				<input type="text" name="search" placeholder="Search">
			</div>
			<div class="header__delete">
				<a class="header__link button_delete hidden" href="#">Delete selected</a>
			</div>
			<div class="header__logout">
				<a class="header__link button_logout" href="/account/logout">Log out</a>
			</div>		
		</div>
	</header>
	<main>
		<div class="container images">
		<?php if($images) { ?>		
			<?php foreach($images as $image) { ?>
			<div class="image" data-image="<?php echo $image['image_id']; ?>">			
				<div class="image__thumb-wrapper">
					<input type="checkbox" name="delete_<?php echo $image['image_id']; ?>" class="image__delete">
					<img src="<?php echo $image['src']; ?>" class="image__thumb">
					<a href="#" class="image__toggle">View</a>
				</div>				
				<h2 class="image__title"><?php echo $image['title']; ?></h2>
				<p class="image__description"><?php echo $image['description']; ?></p>
				
			</div>
			<?php } ?>		
		<?php } else { ?>
		</div>
		<div class="container no-images">
			<p>There are no images yet.</p>
		</div>		
		<?php } ?>
	</main>
	<div class="hidden">
		<form method="POST" enctype="multipart/form-data" name="upload" action="/gallery/upload">
			<input type="file" name="images[]" multiple id="upload__file">			
		</form>
		<div class="modal__image-wrapper">
			<div class="modal__image">
				<div class="modal__thumb-wrapper">				
					<img src="" class="modal__thumb">
				</div>			
				<div class="modal__content">
					<h2 class="modal__title"></h2>
					<p class="modal__description"></p>
					<div class="modal__actions">
						<a href="#" class="modal__edit form__button">Edit info</a>
						<a href="#" class="modal__delete form__button">Delete image</a>
					</div>
				</div>
				<div class="modal__editor">
					<form>
						<input type="hidden" name="image_id">
						<div class="form__input-group">
							<label for="modal__title">Title:</label>
							<input type="text" name="title" id="modal__title">
						</div>
						<div class="form__input-group">
							<label for="modal__description">Description:</label>
							<textarea name="description" id="modal__description"></textarea>
						</div>					
						<div class="modal__actions">
							<a href="#" class="modal__save form__button">Save</a>
						</div>
					</form>
				</div>
			</div>
		</div>
		
	</div>
	<script
	  src="https://code.jquery.com/jquery-3.4.1.min.js"
	  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
	  crossorigin="anonymous" defer></script>
	<script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js" defer></script>
	<script type="text/javascript" src="/js/scripts.js" defer></script>
</body>
</html>