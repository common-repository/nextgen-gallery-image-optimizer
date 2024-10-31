<?php function wp_ngimo_settings_image() {
	global $wp_ngimo_options;
?><h2>Image Optimization Settings</h2>
<?php
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$wp_ngimo_options['optimize85'] = isset($_POST['quality']) ? '1' : '0';
		$wp_ngimo_options['progressive'] = isset($_POST['progressive']) ? '1' : '0';
		update_option('nextgen_gallery_image_optimizer_options', $wp_ngimo_options);
	?>
		<div class="updated" id="message-saved"><p>Image Setings Saved</p></div>
<?php
	};
?>
<form action="<?php echo admin_url('tools.php?page=nextgen-gallery-image-optimizer/include/tools/wp-ngimo-tools.php&tab=image'); ?>" method="post">
<?php wp_nonce_field("wp-ngimo-settings-image"); ?>
<table class="form-table">
<tbody>
<tr valign="top">
	<th scope="row"><label for="quality">Image quality </label></th>
        <td><input type="checkbox" name="quality" id="quality" <?php if ($wp_ngimo_options['optimize85'] == '1') echo 'checked'; ?>>Optimize to 85/100 image quality</td>
</tr>
<tr valign="top">
        <th scope="row"><label for="progressive">Make images progressive</label></th>
	<td><input type="checkbox" name="progressive" id="progressive" <?php if ($wp_ngimo_options['progressive'] == '1') echo 'checked'; ?>>Yes</td>
</tr>
</tbody>
</table>
<p class="submit"><input type="submit" value="Save Settings" class="button button-primary" id="submit" name="submit"></p></form>
<?php } ?>
