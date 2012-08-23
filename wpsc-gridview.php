<?php
/*
Plugin Name: WP e-Commerce Grid View LITE
Description:  WP e-Commerce Grid View Lite automatically activates the WP e-Commerce grid view feature.
Version: 1.0.1
Author: A3 Revolution Software Development team
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

/*
== Changelog ==

= 1.0.0 - 12/07/2012 =
* First working release of the modification
*/
?>
<?php
define( 'WPSC_GRID_VIEW_FILE_PATH', dirname(__FILE__) );
define( 'WPSC_GRID_VIEW_DIR_NAME', basename(WPSC_GRID_VIEW_FILE_PATH) );
define( 'WPSC_GRID_VIEW_FOLDER', dirname(plugin_basename(__FILE__)) );
define( 'WPSC_GRID_VIEW_NAME', plugin_basename(__FILE__) );
define( 'WPSC_GRID_VIEW_URL', WP_CONTENT_URL.'/plugins/'.WPSC_GRID_VIEW_FOLDER );
define( 'WPSC_GRID_VIEW_DIR', WP_CONTENT_DIR.'/plugins/'.WPSC_GRID_VIEW_FOLDER );

include 'classes/class-wpsc-gridview-hook-filter.php';
include 'admin/wpsc-gridview-admin.php';
?>
