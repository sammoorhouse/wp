/* global progressally_admin_notice_data_object */

jQuery(document).ready(function($) {
	function fade_remove_element($elem) {
		if (typeof $elem.animate === 'function') {
			$elem.animate({opacity:0}, 500, function() {$elem.remove();});
		} else {
			$elem.remove();
		}
	}
	$('[progressally-admin-notice]').on('click', function() {
		var $this = $(this),
			plug = $this.attr('progressally-admin-notice'),
			duration = $this.attr('notice-duration'),
			data = {
				action: 'progressally_admin_notice_close',
				plug: plug,
				duration: duration
			};

		$.ajax({
			type: "POST",
			url: progressally_admin_notice_data_object.ajax_url,
			data: data,
			success: false
		});
		fade_remove_element($this.parent('.progressally-admin-notice'));
	});
});