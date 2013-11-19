jQuery(document).ready(function($) {

	var custom_file_frame, testImage, produceImage;

	$('.media-uploader').on('click', function (event) {

		var dis = $(this),
			field = dis.closest('td').find('.media-uploader-field'),
			imageHolder = '<div class="img-holder"></div>';


		event.preventDefault();
		//If the frame already exists, reopen it
	    if (typeof(custom_file_frame)!=="undefined") {
	        custom_file_frame.close();
	    }

	    custom_file_frame = wp.media.frames.customHeader = wp.media({
	         //Title of media manager frame
	         title: "Sample title of WP Media Uploader Frame",
	         library: {
	            type: 'image'
	         },
	         button: {
	            //Button text
	            text: "Set as favicon"
	         },
	         //Do not allow multiple files, if you want multiple, set true
	         multiple: false
	      });

	      //callback for selected image
	    custom_file_frame.on('select', function() {

	        var attachment = custom_file_frame.state().get('selection').first().toJSON();
	        field.val(attachment.url);
	        
	    });

	    //Open modal
	    custom_file_frame.open();

	});


	testImage =  function (url, callback, timeout) {
	    timeout = timeout || 5000;
	    var timedOut = false, timer;
	    var img = new Image();
	    img.onerror = img.onabort = function() {
	        if (!timedOut) {
	            clearTimeout(timer);
	            callback(url, "error");
	        }
	    };
	    img.onload = function() {
	        if (!timedOut) {
	            clearTimeout(timer);
	            callback(url, "success");
	        }
	    };
	    img.src = url;
	    timer = setTimeout(function() {
	        timedOut = true;
	        callback(url, "timeout");
	    }, timeout); 
	};

	pruduceImage = function (url, result) {
	    document.body.innerHTML += "<span class='" + result + "'>" + 
	        result + ": " + url + "</span><br>";
	}   
});

	


