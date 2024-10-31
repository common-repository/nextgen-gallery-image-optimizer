<?php

add_filter('http_request_timeout', 'wp_ngimo_core_timeout_time');

function wp_ngimo_init_ngg() {

	global $wp_ngimo_options;
	global $wpdb;

	$file = $wp_ngimo_options['nextgen_next_file'];
	$next = $wp_ngimo_options['nextgen_next_pid'];
	$position = $wp_ngimo_options['nextgen_position'];

	if (($next == 0) || ($position == 0)) {
		$sql = "SELECT DISTINCT pid,filename FROM {$wpdb->prefix}ngg_pictures ORDER BY pid ASC LIMIT 1;";
		$number = $wpdb->get_results($sql, ARRAY_A);
		$next = $number[0]['pid'];
		$file = $number[0]['filename'];
	}

	$sql = "SELECT DISTINCT COUNT(pid) FROM {$wpdb->prefix}ngg_pictures LIMIT 1;";
	$number = $wpdb->get_results($sql, ARRAY_A);
	$max = $number[0]['COUNT(pid)'];
	$wp_ngimo_options['nextgen_max'] = $max;
	update_option('wp_ngimo_options', $wp_ngimo_options);
?>
	<script>
	var wp_ngimo_options = {
		url: "<?php echo admin_url('admin-ajax.php'); ?>",
		file: "<?php echo $file; ?>",
		next: <?php echo $next; ?>,
		position: <?php echo $position; ?>,
		max: <?php echo $max; ?>
	}
	</script>
<?php
}

function wp_ngimo_core_timeout_time($time) {
	global $wp_ngimo_options;
	$time = $wp_ngimo_options['timeout'];
	return $time;
}

function wp_ngimo_core_upload_file($file_path) {

	global $wp_ngimo_options;
	$local_file = $file_path;
	$boundary = wp_generate_password(24);

	$headers = array(
		'user-agent' => 'WPImageOptimizer',
		'content-type' => 'multipart/form-data; boundary=' . $boundary,
		'timeout' => 45,
		'redirection' => 5,
		'httpversion' => '1.0',
		'blocking' => false
	);

	$post_fields = array(
	        'website' => $wp_ngimo_options["website"],
	        'email' => $wp_ngimo_options["email"],
		'optimize85' => $wp_ngimo_options['optimize85'],
		'progressive' => $wp_ngimo_options['progressive']
	);

	$payload = '';

	foreach ($post_fields as $name => $value) {
        	$payload .= '--' . $boundary;
	        $payload .= "\r\n";
	        $payload .= 'Content-Disposition: form-data; name="' . $name .'"' . "\r\n\r\n";
	        $payload .= $value;
	        $payload .= "\r\n";
	}

	$payload .= '--' . $boundary;
	$payload .= "\r\n";
	$payload .= 'Content-Disposition: form-data; name="file"; filename="' . basename($local_file) . '"' . "\r\n";
	$payload .= 'Content-Type: image/jpeg' . "\r\n";
	$payload .= "\r\n";
	$payload .= file_get_contents($local_file);
	$payload .= "\r\n";
	$payload .= '--' . $boundary;
	$payload .= 'Content-Disposition: form-data; name="submitHandler"' . "\r\n";
	$payload .= "\r\n";
	$payload .= "Upload\r\n";
	$payload .= '--' . $boundary . '--';

	$res = wp_remote_post($wp_ngimo_options['host'], array(
		'headers' => $headers,
		'body' => $payload)
	);
	if (!$res || is_wp_error($res)) {
		$data = FALSE;
	} else {
		$data = wp_remote_retrieve_body($res);
	}
	return $data;
}

function wp_ngimo_core_optimize_image_file($file_path) {

	global $wp_ngimo_options;

	if (file_exists($file_path) === FALSE || is_file($file_path) === FALSE) {
		return TRUE;
		die('invalid file');
        }

	if (is_writable($file_path) === FALSE) {
		die('file not writable');
	}

	$file_size = filesize($file_path);

	if ($file_size > (1024 * 1024 * $wp_ngimo_options['file_size'])) {
		die('file size');
	}

	$r = wp_ngimo_core_upload_file($file_path);

	if ($r === FALSE) return FALSE;

	$temp_file = download_url($r);

	if (is_wp_error($temp_file) ) {
		@unlink($temp_file);
		return FALSE;
	}
	@rename($temp_file, $file_path);
	return TRUE;
}

function wp_ngimo_core_process_images($meta, $attachment_ID) {

	$attachment_file = get_attached_file($attachment_ID);
	$attachment_type = get_post_mime_type($attachment_ID);
	$base_dir = dirname($attachment_file) . '/';

	if (isset($meta['sizes'])) {
		switch ($attachment_type) {
			case 'image/jpeg':
			case 'image/png':
			case 'image/gif':
				/* optimize file */
				wp_ngimo_core_optimize_image_file($attachment_file);
				/* optimize theme thumbnails */
				foreach($meta['sizes'] as $size => $data) {
					$opt_path = $base_dir . $meta['sizes'][$size]['file'];
					wp_ngimo_core_optimize_image_file($opt_path);
				}
			break;
		}
	}
	return $meta;
}

function wp_ngimo_check_server() {

	global $wp_ngimo_options;

	$response = wp_remote_get($wp_ngimo_options['host'] . '/status');

	if (is_wp_error($response)) echo 'Unknown';
		else echo $response['body'];
}
?>
