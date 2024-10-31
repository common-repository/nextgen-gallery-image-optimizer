jQuery(document).ajaxSuccess(function(event, request, settings) {

	if (settings.data) {
		var wp_ngimo_ngg = JSON.parse('{"' + decodeURI(settings.data.replace(/&/g, "\",\"").replace(/=/g,"\":\"")) + '"}');

		if ((wp_ngimo_ngg.action == 'createNewThumb') || (wp_ngimo_ngg.action == 'rotateImage')) {
			jQuery.ajax({
				url: ajaxurl + "?action=wp_ngimo_optimize_ngg_thumb",
				type: "POST",
				cache: false,
				data: "pid=" + wp_ngimo_ngg.id,
				success: function(result) {
					if (result == 'error')
						jQuery("#thumbMsg").html('<strong>Optimizing Error</strong>');
					else jQuery("#thumbMsg").html('<font color="#21759b"><strong>ngimo:</strong> optimized</font>');
				},
				error: function(request, status, error) {
					jQuery("#thumbMsg").html('<strong>Optimizing Error</strong>');
				}
			});
		}
	}
});
