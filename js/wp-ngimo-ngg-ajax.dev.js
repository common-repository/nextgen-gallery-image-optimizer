jQuery(function($) {

	if (typeof(wp_ngimo_options) == 'undefined') return;

        if (wp_ngimo_options.position) {
                jQuery("#submit_ngg_optimize").attr("value", "Resume");
                jQuery("#message").html('<strong>Paused</strong></div>');
                progressBar(Math.round(wp_ngimo_options.position / wp_ngimo_options.max * 100) , jQuery('#progressBar'));
                jQuery("#message").show();
                jQuery("#progressBar").show();
        }

	function progressBar(percent, $element) {
		var progressBarWidth = percent * $element.width() / 100;
		$element.find('div').animate({ width: progressBarWidth }, 500).html(percent + "%&nbsp;");
	}

	jQuery("#ngg_terms_of_use_agreement").click(function() {
		jQuery("#submit_ngg_optimize").attr("disabled", !this.checked);
		if (wp_ngimo_options.position)
			jQuery("#submit_ngg_optimize_reset").attr("disabled", !this.checked);
	});

	if (wp_ngimo_options.position) {
		jQuery("#submit_ngg_optimize_reset").click(function() {
			jQuery.ajax({
				url: wp_ngimo_options.url + "?action=wp_ngimo_ngg_reset_position",
				type: "POST",
				cache: false,
				beforeSend: function() {
					jQuery("#message").html('<strong>Reseting Status ...</strong></div>');
				},
                                success: function(result) {
					jQuery("#message").html('<strong>Reseting Status ... [DONE]</strong></div>');
					window.location.href = window.location.href;
				},
				error: function(request, status, error) {
					jQuery("#message").html('<strong>ERROR: There was an error in status update</strong></div>');
					jQuery("#submit_media_optimize").attr("disabled", false);
				},
                        });
                        return false;
                });
        }

	jQuery("#submit_ngg_optimize").click(function() {
        	jQuery("#terms_of_use_agreement").attr("disabled", true);
		jQuery("#message").show();
		jQuery("#progressBar").show();
		OptimizeNGG();
		return false;
	});

	function OptimizeNGG() {
                if (wp_ngimo_options.position == wp_ngimo_options.max) {
                        progressBar(Math.round(wp_ngimo_options.position / wp_ngimo_options.max * 100) , jQuery('#progressBar'));
                        jQuery("#message").html('<strong>Optimization Complete</strong></div>');
                        jQuery("#submit_ngg_optimize").attr("value", "Start Optimization");
                        jQuery("#terms_of_use_agreement").attr("disabled", false);
                        jQuery("#submit_ngg_optimize").attr("disabled", false);
                        return;
		}

		jQuery.ajax({
			url: ajaxurl,
			type: "POST",
			dataType : 'json',
			data: "action=nextgen_optimize_ngg_image&pid=" + wp_ngimo_options.next,
			success: function(result) {
				wp_ngimo_options.next = result.next;
				if (wp_ngimo_options.next != null)
					progressBar(Math.round(++wp_ngimo_options.position / wp_ngimo_options.max * 100) , jQuery('#progressBar'));
				jQuery("#message").html('<strong>Optimizing:</strong> ' + result.file + '</div>');
				OptimizeNGG();
				},
				error: function(request, status, error) {
					jQuery("#message").html('<strong>ERROR: Optimization Interrupted</strong></div>');
				}
			});
	}
});
