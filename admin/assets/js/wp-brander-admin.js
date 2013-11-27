WpBranderAdmin = ( function ($, window, document) {

	var PointerCollections = alpha.pointers;
           
    $.each( PointerCollections.pointers, function(i) {
        initializePointer(0);
    });
    
    function initializePointer(i) {

    	var pointer, options, closeBtn;

        pointer = PointerCollections.pointers[i];

        options = $.extend( pointer.options, {
            close: function() {
            	initializePointer(++i);
                $.post( alpha.ajaxurl, {
                    pointer: '0',
                    action: 'dismiss-wp-pointer'
                });
            },
            buttons: function (event, t) {
            			var button;
						button = jQuery('<div class="btns-set"><a id="pointer-close" style="margin-left:5px" class="button-secondary">' + pointer.closeBtn + '</a><a id="pointer-next" class="button-primary">Next</a></div>');
						button.find("#pointer-close").bind('click.pointer', function () {
							t.element.pointer('close');
						});
						return button;
					},
            next: function () {

            }
        });
        $(pointer.target).pointer( options ).pointer('open');
    }
	
}(jQuery, this, document));
