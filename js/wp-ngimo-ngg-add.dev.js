jQuery(document).ajaxSuccess(function(event, request, settings) {

	if (settings.data) {
		var wp_ngimo_ngg = JSON.parse('{"' + decodeURI(settings.data.replace(/&/g, "\",\"").replace(/=/g,"\":\"")) + '"}');
		if ((wp_ngimo_ngg.action == 'ngg_ajax_operation') && (wp_ngimo_ngg.operation == 'create_thumbnail')) {
			jQuery.ajax({
				url: ajaxurl + "?action=nextgen_optimize_ngg_image",
				type: "POST",
				cache: false,
				dataType: 'json',
				data: {
                                	pid: wp_ngimo_ngg.image
                        	},
                        	success: function(result) {
					if (((jQuery("#message").text().search(/ngimo/i)) < 0))
						jQuery("#message p").append(' | <strong>ngimo</strong> optimized image(s): <font color="#21759b">'+ wp_ngimo_ngg.image +'</font>');
					else jQuery("#message p").append(' <font color="#21759b">' + wp_ngimo_ngg.image + '</font>');
                        	},
                        	error: function(request, status, error) {
					jQuery("#message p").append(' ('+ wp_ngimo_ngg.image + ': error)');
				}
			});
		}
	}
});