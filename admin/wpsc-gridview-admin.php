<?php
/**
 * Load languages file
 */
function wpsc_gridview_init() {
	load_plugin_textdomain( 'wpsc_gridview', false, WPSC_GRID_VIEW_FOLDER.'/languages' );
}
// Add language
add_action('init', 'wpsc_gridview_init');

// Notices upgrade to PRO version to all pages in dashboard
add_action('admin_notices', array('WPSC_GridView_Hook_Filter', 'wpsc_gridview_upgrade_notice') );

add_action( 'wp_head', array('WPSC_GridView_Hook_Filter','wpsc_grid_view_styles') );
add_action( 'wp_head', array('WPSC_GridView_Hook_Filter','wpsc_grid_custom_styles'), 9 );

if ( !function_exists( 'product_display_list' ) ){
	function product_display_list( $product_list, $group_type, $group_sql = '', $search_sql = '' ) {
		WPSC_GridView_Hook_Filter::product_display_list( $product_list, $group_type, $group_sql , $search_sql );
	}
}

if ( !function_exists( 'product_display_grid' ) ){
	function product_display_grid( $product_list, $group_type, $group_sql = '', $search_sql = '' ) {
		WPSC_GridView_Hook_Filter::product_display_grid( $product_list, $group_type, $group_sql , $search_sql );
	}
}
?>