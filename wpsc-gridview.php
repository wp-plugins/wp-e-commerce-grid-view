<?php
/*
Plugin Name: WP e-Commerce Grid View LITE
Plugin URI: http://a3rev.com/shop/wp-e-commerce-grid-view/
Description: WP e-Commerce Grid View Pro automatically activates the WP e-Commerce grid view feature. It also scales all product thumbnail images in grid view for a flawless product category page presentation.
Version: 1.0.4.1
Author: A3 Revolution
Author URI: http://www.a3rev.com/
License: GPLv2 or later
*/

/*
	WP e-Commerce Grid View. Plugin for the WP e-Commerce PRO plugin.
	Copyright © 2011 A3 Revolution Software Development team
	
	A3 Revolution Software Development team
	admin@a3rev.com
	PO Box 1170
	Gympie 4570
	QLD Australia
*/
?>
<?php
define( 'WPSC_GRID_VIEW_FILE_PATH', dirname(__FILE__) );
define( 'WPSC_GRID_VIEW_DIR_NAME', basename(WPSC_GRID_VIEW_FILE_PATH) );
define( 'WPSC_GRID_VIEW_FOLDER', dirname(plugin_basename(__FILE__)) );
define( 'WPSC_GRID_VIEW_NAME', plugin_basename(__FILE__) );
define( 'WPSC_GRID_VIEW_URL', untrailingslashit( plugins_url( '/', __FILE__ ) ) );
define( 'WPSC_GRID_VIEW_DIR', WP_CONTENT_DIR.'/plugins/'.WPSC_GRID_VIEW_FOLDER );
define( 'WPSC_GRID_VIEW_IMAGES_URL',  WPSC_GRID_VIEW_URL . '/assets/images' );

include 'classes/class-wpsc-gridview-hook-filter.php';
include 'admin/wpsc-gridview-admin.php';

/**
* Call when the plugin is activated
*/
register_activation_hook(__FILE__,'wpsc_gridview_install');
?>