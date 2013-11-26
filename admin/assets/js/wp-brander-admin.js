WpBranderAdmin = ( function () {

	console.log(pointerVars);

	jQuery('.media-uploader-field').pointer({
			content    : pointerVars.pointerObj,
			position: {
				'align' : 'midle',
				'edge'  : 'top'
			},
			close: function(){
				$.post( pointerVars.ajaxUrl, {
                    pointer: pointer.pointer_id,
                    action: 'dismiss-wp-pointer'
                });
			}
			
		}).pointer('open');
}());
