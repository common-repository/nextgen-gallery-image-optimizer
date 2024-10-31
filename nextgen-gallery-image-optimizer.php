<?php
/*
Plugin Name: NextGEN Gallery Image Optimizer
Plugin URI: http://www.ngimo.com
Description: Improves your site's page speed score on Google pagespeed and load speed by serving optimized (lossless compressed) images
Author: CodeHub, Inc.
Version: 1.2
Author URI: http://www.codehub.us
License: GPL 2.0
*/

function wp_ngimo_scripts_init($page) {

	switch ($page) {
		case 'tools_page_nextgen-gallery-image-optimizer/include/tools/wp-ngimo-tools':
			wp_deregister_style('nextgen-gallery-image-optimizer-style');
			wp_register_style('nextgen-gallery-image-optimizer-style', plugins_url('/css/nextgen-gallery-image-optimizer.css', __FILE__), null, '1.0');
			wp_enqueue_style('nextgen-gallery-image-optimizer-style');

			if (!isset($_GET['tab']) || ($_GET['tab'] == 'nextgen')) {
				wp_deregister_script('nextgen-gallery-image-optimizer-ajax');
				wp_register_script('nextgen-gallery-image-optimizer-ajax', plugins_url('/js/wp-ngimo-ngg-ajax.js', __FILE__), null, '1.0', true);
				wp_enqueue_script('nextgen-gallery-image-optimizer-ajax');
			}
			break;
		case 'gallery_page_nggallery-add-gallery':
			wp_deregister_script('nextgen-gallery-image-optimizer-ajax-add');
			wp_register_script('nextgen-gallery-image-optimizer-ajax-add', plugins_url('/js/wp-ngimo-ngg-add.js', __FILE__), null, '1.0', true);
			wp_enqueue_script('nextgen-gallery-image-optimizer-ajax-add');
		break;
		case 'gallery_page_nggallery-manage-gallery':
			wp_deregister_script('nextgen-gallery-image-optimizer-ajax-manage');
			wp_register_script('nextgen-gallery-image-optimizer-ajax-manage', plugins_url('/js/wp-ngimo-ngg-manage.js', __FILE__), null, '1.0', true);
			wp_enqueue_script('nextgen-gallery-image-optimizer-ajax-manage');
		break;
        }
}

add_action('admin_enqueue_scripts', 'wp_ngimo_scripts_init');

define('SYS_NGIMO_PATH', WP_PLUGIN_DIR . '/nextgen-gallery-image-optimizer');

$wp_ngimo_options = get_option('nextgen_gallery_image_optimizer_options');

require_once SYS_NGIMO_PATH . '/include/functions/wp-ngimo-functions.php';
require_once SYS_NGIMO_PATH . '/include/functions/wp-ngimo-ngg-ajax.php';
require_once SYS_NGIMO_PATH . '/include/tools/wp-ngimo-tools.php';

register_activation_hook(__FILE__, 'system_install_nextgen_image_optimizer_plugin');
register_deactivation_hook( __FILE__, 'system_uninstall_nextgen_image_optimizer_plugin');

function system_install_nextgen_image_optimizer_plugin() {

	global $current_user;
	get_currentuserinfo();

	$wp_ngimo_options = array();
	$wp_ngimo_options['host'] = 'http://freeopt.ngimo.com';
	$wp_ngimo_options['timeout'] = '65';
	$wp_ngimo_options['file_size'] = '4096';

	$wp_ngimo_options['website'] = md5(home_url()); /* we don't want collect info about users, just use user-info to generate uniq md5 hash */
	$wp_ngimo_options['email'] = md5($current_user->user_email); /* we don't want to collect info about users. just use this for uniq md5 hash */

	$wp_ngimo_options['optimize85'] = '1';
	$wp_ngimo_options['progressive'] = '0';

	$wp_ngimo_options['nextgen_next_file'] = '0';
	$wp_ngimo_options['nextgen_next_pid'] = '0';
	$wp_ngimo_options['nextgen_position'] = '0';
	$wp_ngimo_options['nextgen_max'] = '0';

	add_option('nextgen_gallery_image_optimizer_options', $wp_ngimo_options, '', yes);
}

function system_uninstall_nextgen_image_optimizer_plugin() {
	delete_option('nextgen_gallery_image_optimizer_options');
}
?>
