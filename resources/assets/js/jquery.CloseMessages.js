(function($) {
	$.fn.CloseMessages = function() {
		// Show link
		var messages = this.children("div.alert");
		messages.each(function() {
			var lnk = $('<button type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
			$(this).prepend(lnk);
		});

		// Handler
		this.on("click", "div.alert > button.close", function() {
			var msg = $(this).parent();
			msg.animate({
				opacity: '0'
            }, 100, function() {
				msg.slideUp(100, function() {
					msg.remove();
				});
			});
		});
	};

	jQuery(document).ready(function () {
		$('div#message-centre').CloseMessages();
	});
})(jQuery);