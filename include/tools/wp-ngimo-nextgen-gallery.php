<?php function wp_ngimo_tools_nextgen() { ?>
<div class="wrap">
	<h2>NextGEN Gallery Image Optimizer</h2>
	<p>Use this tool if you want to optimize all images from your NextGEN Gallery</p>
	<p><strong>Important note #1:</strong> Any filenames with special chars will not be optimised (allowed are [a-zA-z] and -)</p>
        <p><strong>Important note #2:</strong> Please just in case, make backup of your images</p>
	<div id="progressBar" class="bulk-progress"><div></div></div>
	<form method="post" action="">
		<table class="form-table">
		<tbody>
		<tr valign="top">
			<td id="message"></td>
		</tr>
		<tr valign="top">
			<td><input type="checkbox" id="ngg_terms_of_use_agreement" name="users_can_register"> I made backup of the images</a></td>
		</tr>
		</tbody>
		</table>
		<p class="submit">
			<input type="submit" disabled="disabled" value="Start Optimization" class="button button-primary" id="submit_ngg_optimize" name="submit">
<?php
	global $wp_ngimo_options;
        if ($wp_ngimo_options['nextgen_position'] != '0') {
?>
                <input type="submit" disabled="disabled" value="RESET" class="button button-primary" id="submit_ngg_optimize_reset" name="submit">
<?php } ?>
		</p>
	</form>
</div>
<p><strong>Do you need to optimize images in Media Library ? Theme images ? Watch the video:</strong></p>
<p><object width="600" height="337"><param name="allowfullscreen" value="true" /><param name="allowscriptaccess" value="always" /><param name="movie" value="http://vimeo.com/moogaloop.swf?clip_id=69928349&amp;force_embed=1&amp;server=vimeo.com&amp;show_title=0&amp;show_byline=0&amp;show_portrait=0&amp;color=013061&amp;fullscreen=1&amp;autoplay=0&amp;loop=0" /><embed src="http://vimeo.com/moogaloop.swf?clip_id=69928349&amp;force_embed=1&amp;server=vimeo.com&amp;show_title=0&amp;show_byline=0&amp;show_portrait=0&amp;color=013061&amp;fullscreen=1&amp;autoplay=0&amp;loop=0" type="application/x-shockwave-flash" allowfullscreen="true" allowscriptaccess="always" width="600" height="337"></embed></object></p>
<p><strong>Do you like the project ? Support it. Visit <a href="http://www.ngimo.com" target="_blank">http://www.ngimo.com</a> and subscribe or donate</strong></p>
<p><form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="G6YJGKHKVZ9KN">
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form></p>
<?php wp_ngimo_init_ngg(); } ?>
