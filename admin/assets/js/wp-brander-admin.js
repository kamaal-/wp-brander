WpBranderAdmin = ( function ($, window, document) {

	var WPHelpPointer = alpha.pointers;
           
    $.each(WPHelpPointer.pointers, function(i) {
        wp_help_pointer_open(0);
    });
    function retur(){
    	
    }
    function wp_help_pointer_open(i) {

        pointer = WPHelpPointer.pointers[i];
        
        options = $.extend( pointer.options, {
            close: function() {
            	wp_help_pointer_open(++i);
                $.post( alpha.ajaxurl, {
                    pointer: '0',
                    action: 'dismiss-wp-pointer'
                });
            },
            buttons: function (event, t) {
						button = jQuery('<div class="btns-set"><a id="pointer-close" style="margin-left:5px" class="button-secondary">Close</a><a id="pointer-next" class="button-primary">Next</a></div>');
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
