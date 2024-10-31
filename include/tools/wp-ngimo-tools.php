<?php

require_once SYS_NGIMO_PATH . '/include/tools/wp-ngimo-nextgen-gallery.php';
require_once SYS_NGIMO_PATH . '/include/tools/wp-ngimo-image.php';
require_once SYS_NGIMO_PATH . '/include/tools/wp-ngimo-about.php';

add_filter('media_row_actions','my_action_row', 10, 2);
add_action('admin_menu', 'wp_lossless_admin_menu');

function wp_lossless_admin_menu() {
	add_management_page('ngimo Optmizer', 'NextGEN Gallery Image Optimizer', 'manage_options', __FILE__, 'wp_ngimo_tools_page');
}

function wp_ngimo_tools_tabs($current = 'media') {
	if (!class_exists('nggLoader')) {
		$tabs = array(
			'ngimoabout' => 'About'
		);
	}
	else {
		$tabs = array(
			'nextgen' => 'NextGEN Gallery',
			'image' => 'Settings',
			'ngimoabout' => 'About'
		);
	} ?>
		<div id="icon-tools" class="icon32"><br></div>
                <h2 class="nav-tab-wrapper"><?php
                foreach($tabs as $tab => $name) {
                        $class = ($tab == $current) ? ' nav-tab-active' : ''; ?>
                        <a class="nav-tab<?php echo $class;?>" href="?page=<?php echo __FILE__; ?>&tab=<?php echo $tab;?>"><?php echo $name;?></a>
            <?php } ?>
                </h2>
        <?php }

function wp_ngimo_tools_page() { ?>
<div class="wrap">
<?php
	global $pagenow;

	if (isset ($_GET['tab'])) wp_ngimo_tools_tabs($_GET['tab']);
		else wp_ngimo_tools_tabs('nextgen');

	if (isset ($_GET['tab'])) $tab = $_GET['tab'];
		else $tab = 'nextgen';

	switch ($tab) {
		case 'nextgen':
			wp_ngimo_tools_nextgen();
		break;
		case 'image':
			wp_ngimo_settings_image();
		break;
		case 'ngimoabout':
			nextgen_gallery_image_optimizer_about();
                break;
	}
}
?>
