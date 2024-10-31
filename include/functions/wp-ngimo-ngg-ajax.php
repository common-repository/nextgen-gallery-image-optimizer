<?php

add_action('wp_ajax_wp_ngimo_ngg_reset_position', 'wp_ngimo_ngg_reset_position');
add_action('wp_ajax_nextgen_optimize_ngg_image', 'nextgen_optimize_ngg_image');
add_action('wp_ajax_wp_ngimo_optimize_ngg_thumb', 'wp_ngimo_optimize_ngg_thumb');

function wp_ngimo_ngg_reset_position() {
	global $wp_ngimo_options;
        $wp_ngimo_options['nextgen_next_file'] = '0';
        $wp_ngimo_options['nextgen_next_pid'] = '0';
        $wp_ngimo_options['nextgen_position'] = '0';
	update_option('wp_ngimo_options', $wp_ngimo_options);
}

function nextgen_optimize_ngg_image() {
	global $wpdb;
	global $wp_ngimo_options;

	$pid = absint($_POST['pid']);

	$sql = "SELECT {$wpdb->prefix}ngg_pictures.pid, {$wpdb->prefix}ngg_gallery.path, {$wpdb->prefix}ngg_pictures.filename from {$wpdb->prefix}ngg_pictures left join {$wpdb->prefix}ngg_gallery on {$wpdb->prefix}ngg_pictures.galleryid = {$wpdb->prefix}ngg_gallery.gid where {$wpdb->prefix}ngg_pictures.pid >= $pid limit 2;";
	$img = $wpdb->get_results($wpdb->prepare($sql), ARRAY_A);

	$opt_file = ABSPATH . $img[0]['path'] . '/' . $img[0]['filename'];
	$opt_file_thumb = ABSPATH . '/' . $img[0]['path'] . '/thumbs/' . 'thumbs_' . $img[0]['filename'];

	$r = array();
	$r['file'] = $img[0]['filename'];
	$r['next'] = $img[1]['pid'];

        $wp_ngimo_options['nextgen_next_file'] = $r['file'];
        $wp_ngimo_options['nextgen_next_pid'] = $r['next'];

	if ($wp_ngimo_options['nextgen_position'] >= $wp_ngimo_options['nextgen_max'])
		$wp_ngimo_options['nextgen_position'] = '0';
	else $wp_ngimo_options['nextgen_position'] += 1;

	update_option('wp_ngimo_options', $wp_ngimo_options);

	wp_ngimo_core_optimize_image_file($opt_file);
	wp_ngimo_core_optimize_image_file($opt_file_thumb);

	echo json_encode($r);
	die();
}

function wp_ngimo_optimize_ngg_thumb() {

	global $wpdb;

        $pid = absint($_POST['pid']);

        $sql = "SELECT {$wpdb->prefix}ngg_pictures.pid, {$wpdb->prefix}ngg_gallery.path, {$wpdb->prefix}ngg_pictures.filename from {$wpdb->prefix}ngg_pictures LEFT JOIN {$wpdb->prefix}ngg_gallery ON {$wpdb->prefix}ngg_pictures.galleryid = {$wpdb->prefix}ngg_gallery.gid WHERE {$wpdb->prefix}ngg_pictures.pid = $pid LIMIT 1;";

	$img = $wpdb->get_results($wpdb->prepare($sql), ARRAY_A);

        $opt_file_thumb = ABSPATH . '/' . $img[0]['path'] . '/thumbs/' . 'thumbs_' . $img[0]['filename'];

        if (wp_ngimo_core_optimize_image_file($opt_file_thumb) === FALSE) {
		echo 'error';
		die();
	}
        echo 'success';
        die();
}
?>
