$('#form__register').click(function(e){
	e.preventDefault();
	form = $(this).closest('form');
	$.ajax({
		type: 'POST',
		url: "/account/register",
		data: form.serialize(),
		dataType: 'json',
		success: function(json){
			window.json = json;
			if(json.errors) {
				$(json.errors).each(function(i, error){
					push_notification(error, 'error');
				});				
			}
			if(!json.errors && json.redirect) {
				document.location.href = json.redirect;
			}
		},
        error: function (xhr, ajaxOptions, thrownError) {
			push_notification('Connection error', 'error');
		}
	});    
});
$('#form__login').click(function(e){
	e.preventDefault();
	form = $(this).closest('form');
	$.ajax({
		type: 'POST',
		url: "/account/login",
		data: form.serialize(),
		dataType: 'json',
		success: function(json){
			window.json = json;
			if(json.errors) {
				$(json.errors).each(function(i, error){
					push_notification(error, 'error');
				});				
			}
			if(!json.errors && json.redirect) {
				document.location.href = json.redirect;
			}
		},
        error: function (xhr, ajaxOptions, thrownError) {
			push_notification('Connection error', 'error');
		}
	});    
});
$('.button_upload').click(function(e){
	e.preventDefault();
	$('#upload__file').click();
});
$('.button_delete').click(function(e){
	e.preventDefault();
	images_to_delete = [];
	$('.image__delete:checked').each(function(){
		image = $(this).closest('.image');
		image_id = image.data('image');
		images_to_delete.push(image_id);
		image.remove();
	});
	if(images_to_delete) {
		$.ajax({
	        url: '/gallery/delete',
	        type: 'POST',
	        data: {images: images_to_delete},	        
	        dataType: "json",
	        beforeSend: function() {
	        	notification = push_notification('Deleting files');
	        },
	        success: function (json) {
	        	notification.slideUp();	        	
	        	if(json.errors) {
					$(json.errors).each(function(i, error){
						push_notification(error, 'error');
					});				
				}
				if(json.notifications) {
					$(json.notifications).each(function(i, notification){
						push_notification(notification);
					});				
				}
	            check_delete();
	        },
	        error: function (xhr, ajaxOptions, thrownError) {
				push_notification('Connection error', 'error');
			}
	    });
	}
	
});
$('#upload__file').change(function(){
	var notification;
	form = $(this).closest('form')[0];
	files_input = $(this)[0];
	if(files_input.files.length) {
		var formData = new FormData(form);
		$.ajax({
	        url: '/gallery/upload',
	        type: 'POST',
	        data: formData,
	        processData: false,
	        contentType: false,
	        dataType: "json",
	        beforeSend: function() {
	        	notification = push_notification('Uploading files');
	        },
	        success: function (json) {
	        	notification.slideUp();
	        	files_input.value = "";
	        	if(json.errors) {
					$(json.errors).each(function(i, error){
						push_notification(error, 'error');
					});				
				}
				if(json.images) {
					$(json.images).each(function(i, image){
						push_image(image);
					});
				}
	            
	        },
	        error: function (xhr, ajaxOptions, thrownError) {
				push_notification('Connection error', 'error');
			}
	    });
	}
});
$(document).on('click', '.image__toggle', function(){
	image = $(event.target).closest('.image');
	image_id = image.data('image');
	image_src = image.find('.image__thumb').attr('src');
	image_title = image.find('.image__title').text();
	image_description = image.find('.image__description').text();
	modal = $('.modal__image-wrapper');
	modal.find('.modal__image').removeClass('_editor');
	modal.find('[name=image_id]').val(image_id);
	modal.find('[name=title]').val(image_title);
	modal.find('[name=description]').val(image_description);	
	modal.find('.modal__thumb').attr('src', image_src);
	modal.find('.modal__title').text(image_title);
	modal.find('.modal__description').text(image_description);
	$.fancybox.open({
		src  : modal,
		type : 'inline',
		opts: {
			clickContent: false
		}	
	});
});
$(document).on('click', '.modal__edit', function(){
	event.preventDefault();
	modal = $(event.target).closest('.modal__image');
	modal.addClass('_editor');
});
$(document).on('click', '.modal__save', function(){
	event.preventDefault();
	modal = $(event.target).closest('.modal__image');
	form = modal.find('form');
	$.ajax({
		type: 'POST',
		url: "/gallery/edit",
		data: form.serialize(),
		dataType: 'json',
		success: function(json){
			window.json = json;
			if(json.errors) {
				$(json.errors).each(function(i, error){
					push_notification(error, 'error');
				});				
			}
			if(json.notifications) {
				$(json.notifications).each(function(i, notifications){
					push_notification(notifications);
				});				
			}
			if(json.image) {				
				wrapper = $('.image[data-image='+json.image.image_id+']');
				wrapper.find('.image__title').text(json.image.title);
				wrapper.find('.image__description').text(json.image.description);
				modal.find('.modal__title').text(json.image.title);
				modal.find('.modal__description').text(json.image.description);
				modal.removeClass('_editor');
			}		
		},
        error: function (xhr, ajaxOptions, thrownError) {
			push_notification('Connection error', 'error');
		}
	});  
});
$(document).on('click', '.modal__delete', function(){
	event.preventDefault();
	images_to_delete = [];
	modal = $(event.target).closest('.modal__image');
	image_id = modal.find('[name=image_id]').val();
	$('.image[data-image='+image_id+']').remove();
	images_to_delete.push(image_id);	
	$.fancybox.close();

	if(images_to_delete) {
		$.ajax({
	        url: '/gallery/delete',
	        type: 'POST',
	        data: {images: images_to_delete},	        
	        dataType: "json",
	        beforeSend: function() {
	        	notification = push_notification('Deleting files');
	        },
	        success: function (json) {
	        	notification.slideUp();	        	
	        	if(json.errors) {
					$(json.errors).each(function(i, error){
						push_notification(error, 'error');
					});				
				}
				if(json.notifications) {
					$(json.notifications).each(function(i, notification){
						push_notification(notification);
					});				
				}
	            check_delete();
	        },
	        error: function (xhr, ajaxOptions, thrownError) {
				push_notification('Connection error', 'error');
			}
	    });
	}
});
$(document).on('change', '.image__delete', function(){
	check_delete();
});

function push_notification(text, type = 'common') {
	notification = $('<div class="notification '+type+'" style="display: none;">'+text+'</div>').appendTo('.notifications').slideDown('fast').click(function(){
		$(this).slideUp();
	});
	return notification;
}
function push_image(image) {
	html = '<div class="image" data-image="'+image.image_id+'">';
	html += '<div class="image__thumb-wrapper">';
	html += '<input type="checkbox" name="delete_'+image.image_id+'" class="image__delete">';
	html += '<img src="'+image.src+'" class="image__thumb">';
	html += '<a href="#" class="image__toggle">View</a>';
	html += '</div>			';
	html += '<h2 class="image__title">'+image.title+'</h2>';
	html += '<p class="image__description">'+image.description+'</p>';
	html += '</div>';
	$('.images').prepend(html);
	$('.no-images').hide();
}
function check_delete() {
	if($('.image__delete:checked').length) {
		$('.button_delete').removeClass('hidden');
	} else {
		$('.button_delete').addClass('hidden');
	}
}
$('[name=search]').keyup(function(){
	search = $(this).val();
	if(!search) {
		$('.image').show();
	} else {
		$.ajax({
	        url: '/gallery/search?search=' + search,
	        type: 'GET',
	        dataType: "json",        
	        success: function (json) {
	        	if(json.errors) {
					$(json.errors).each(function(i, error){
						push_notification(error, 'error');
					});				
				}
				if(json.notifications) {
					$(json.notifications).each(function(i, notification){
						push_notification(notification);
					});				
				}
				if(json.images) {
					$('.image').each(function(i, image) {

						image_id = $(image).data('image');
						if(json.images.includes(image_id)) {
							$(image).show();
						} else {
							$(image).hide();
						}
					});
				} else {
					$('.image').hide();
				}
	        },
	        error: function (xhr, ajaxOptions, thrownError) {
				push_notification('Connection error', 'error');
			}
	    });
	}	
});