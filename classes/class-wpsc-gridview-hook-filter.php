<?php
/**
 * WPSC_GridView_Hook_Filter
 *
 * Class Function into WP e-Commerce plugin
 *
 * Table Of Contents
 *
 * wpsc_grid_view_styles();
 * wpsc_grid_custom_styles();
 * product_display_list();
 * product_display_grid();
 * wpsc_gridview_upgrade_notice();
 * plugin_extra_links()
 */
class WPSC_GridView_Hook_Filter
{
	
	public static function wpsc_grid_view_styles() {
		global $wp_query;
		if ( 'wpsc-product' == $wp_query->post->post_type && !is_archive() && $wp_query->post_count <= 1 ) return;
		if ( !is_products_page() && !is_tax( 'wpsc_product_category' ) && !is_tax('product_tag') ) return;
		
		$wpsc_gc_view_mode = get_option('product_view');
		//wp_enqueue_style( 'wpsc-gold-cart', WPSC_GRID_VIEW_URL . '/assets/css/gold_cart.css' );
		if ( $wpsc_gc_view_mode == 'grid' ){
			wp_register_style( 'wpsc-grid-view', WPSC_GRID_VIEW_URL . '/assets/css/gridview.css' );
        	wp_enqueue_style( 'wpsc-grid-view' );
		}
	}
	
	public static function wpsc_grid_custom_styles() {
		global $wp_query;
		if ( 'wpsc-product' == $wp_query->post->post_type && !is_archive() && $wp_query->post_count <= 1 ) return;
		if ( !is_products_page() && !is_tax( 'wpsc_product_category' ) && !is_tax('product_tag') ) return;
		
		$items_per_row = get_option( 'grid_number_per_row' );
		$wpsc_gc_view_mode = get_option('product_view');
		if ( $wpsc_gc_view_mode != 'grid' ) return;
		if ( $items_per_row ) {
			// roughly calculate the percentage, this will be corrected with JS later
			$percentage = floor( 100 / $items_per_row ) - 4;
			$percentage = apply_filters( 'wpsc_grid_view_column_width', $percentage, $items_per_row ); // themes can override this calculation
			?>
			<style type="text/css">
				.product_grid_display .product_grid_item {
					width:<?php echo $percentage; ?>%;
				}
				.product_grid_display .item_image a {
					display: inline-block;
					height: <?php echo get_option('product_image_height'); ?>px;
					line-height: <?php echo (get_option('product_image_height') - 4); ?>px;
					width: 100%;
					text-align:center;
					vertical-align: middle;
				}
			</style>
			<?php
		}
	}
	
	public static function product_display_grid($product_list, $group_type, $group_sql = '', $search_sql = '') {
		global $wpdb;
		/*
		All this does is sit here so that it can be detected by the gold files to turn grid view on.
		*/  
	}  
	public static function product_display_list($product_list, $group_type, $group_sql = '', $search_sql = '') {
		global $wpdb;
		$siteurl = get_option('siteurl');
		
		if ( (float)WPSC_VERSION < 3.8 )
			$images_dir = 'images';
		else
			$images_dir = 'wpsc-core/images';
		  
		if(get_option('permalink_structure') != '') {
			$seperator ="?";
		} else {
			$seperator ="&amp;";
		}
		
		$product_listing_data = wpsc_get_product_listing($product_list, $group_type, $group_sql, $search_sql);
		
		$product_list = $product_listing_data['product_list'];
		
		$output .= $product_listing_data['page_listing'];
		if($product_listing_data['category_id']) {
			$category_nice_name = $wpdb->get_var("SELECT `nice-name` FROM `".WPSC_TABLE_PRODUCT_CATEGORIES."` WHERE `id` ='".(int)$product_listing_data['category_id']."' LIMIT 1");
		} else {
			$category_nice_name = '';
		}
		  
		if($product_list != null) {
			$output .= "<table class='list_productdisplay $category_nice_name'>";
			$i=0;
			foreach($product_list as $product) {
				$num++;
				if ($i%2 == 1) {
					$output .= "    <tr class='product_view_{$product['id']}'>";
				} else {
					$output .= "    <tr class='product_view_{$product['id']}' style='background-color:#EEEEEE'>";
				}
				$i++;
				$output .= "      <td style='width: 9px;'>";
				if($product['description'] != null) {
					$output .= "<a href='#' class='additional_description_link' onclick='return show_additional_description(\"list_description_".$product['id']."\",\"link_icon".$product['id']."\");'>";
					$output .= "<img style='margin-top:3px;' id='link_icon".$product['id']."' src='$siteurl/wp-content/plugins/".WPSC_DIR_NAME."/".$images_dir."/icon_window_expand.gif' title='".$product['name']."' alt='".$product['name']."' />";
					$output .= "</a>";
				}
				$output .= "      </td>\n\r";
				$output .= "      <td width='55%'>";
			
				if($product['special'] == 1) {
					$special = "<strong class='special'>".TXT_WPSC_SPECIAL." - </strong>";
				} else {
					$special = "";
				}
				$output .= "<a href='".wpsc_product_url($product['id'])."' class='wpsc_product_title' ><strong>" . stripslashes($product['name']) . "</strong></a>";
				$output .= "      </td>";
				$variations_procesor = new nzshpcrt_variations;
	
				$variations_output = $variations_procesor->display_product_variations($product['id'],false, false, true);
				if($variations_output[1] !== null) {
					$product['price'] = $variations_output[1];
				}
				$output .= "      <td width='10px' style='text-align: center;'>";
				if(($product['quantity'] < 1) && ($product['quantity_limited'] == 1)) {
					$output .= "<img style='margin-top:5px;' src='$siteurl/wp-content/plugins/".WPSC_DIR_NAME."/".$images_dir."/no_stock.gif' title='No' alt='No' />";
				} else {
					$output .= "<img style='margin-top:4px;' src='$siteurl/wp-content/plugins/".WPSC_DIR_NAME."/".$images_dir."/yes_stock.gif' title='Yes' alt='Yes' />";
				}
				$output .= "      </td>";
				$output .= "      <td width='10%'>";
				if(($product['special']==1) && ($variations_output[1] === null)) {
					$output .= nzshpcrt_currency_display(($product['price'] - $product['special_price']), $product['notax'],false,$product['id']) . "<br />";
				} else {
					$output .= "<span id='product_price_".$product['id']."'>".nzshpcrt_currency_display($product['price'], $product['notax'])."</span>";
				}
				$output .= "      </td>";
	
				$output .= "      <td width='20%'>";
				if (get_option('addtocart_or_buynow') == '0'){
					$output .= "<form name='$num'  id='product_".$product['id']."'  method='POST' action='".get_option('product_list_url').$seperator."category=".$_GET['category']."' onsubmit='submitform(this);return false;' >";
				}
				if(get_option('list_view_quantity') == 1) {
					$output .= "<input type='text' name='quantity' value='1' size='3' maxlength='3'>&nbsp;";
				}
				$output .= $variations_output[0];
				$output .= "<input type='hidden' name='item' value='".$product['id']."' />";
				$output .= "<input type='hidden' name='prodid' value='".$product['id']."'>";
				if (get_option('wpsc_selected_theme')=='iShop') {
					if (get_option('addtocart_or_buynow') == '0') {
						if(($product['quantity_limited'] == 1) && ($product['quantity'] < 1)) {
							$output .= "<input disabled='true' type='submit' value='' name='Buy' class='wpsc_buy_button'/>";
						} else {
							$output .= "<input type='submit' name='Buy' value='' class='wpsc_buy_button'/>";
						}
					} else {
						if(!(($product['quantity_limited'] == 1) && ($product['quantity'] < 1))){
							$output .= google_buynow($product['id']);
						}
					}
				} else {
					if (get_option('addtocart_or_buynow') == '0') {
						if(($product['quantity_limited'] == 1) && ($product['quantity'] < 1)) {
							$output .= "<input disabled='true' type='submit' name='Buy' class='wpsc_buy_button'  value='".TXT_WPSC_ADDTOCART."'  />";
						} else {
							$output .= "<input type='submit' name='Buy' class='wpsc_buy_button'  value='".TXT_WPSC_ADDTOCART."'  />";
						}
					} else {
						if(!(($product['quantity_limited'] == 1) && ($product['quantity'] < 1))){
							$output .= google_buynow($product['id']);
						}
					}
				}
				$output .= "</form>";
				$output .= "      </td>\n\r";
				$output .= "    </tr>\n\r";
				
				$output .= "    <tr class='list_view_description'>\n\r";
				$output .= "      <td colspan='5'>\n\r";
				$output .= "        <div id='list_description_".$product['id']."'>\n\r";
				$output .= $product['description'];
				$output .= "        </div>\n\r";
				$output .= "      </td>\n\r";
				$output .= "    </tr>\n\r";
			}
			$output .= "</table>";
		} else {
			$output .= "<p>".TXT_WPSC_NOITEMSINTHIS." ".$group_type.".</p>";
		}
		return $output;
	}
	
	public static function wpsc_gridview_upgrade_notice() {
		session_start();
		if (isset($_GET['hide-wpsc-gridview-upgrade-notice'])) 
	    	$_SESSION['hide-wpsc-gridview-upgrade-notice'] = 1 ;
			
		if (!isset($_SESSION['hide-wpsc-gridview-upgrade-notice'])) {
			$html = '<style>#wpsc_gridview_upgrade_notice { line-height:30px; text-shadow: 0 1px 0 rgba(255, 255, 255, 0.8); padding:8px 18px 8px 6px;position:relative;}#wpsc_gridview_upgrade_notice a.hide{color:#FF0808;float:right;text-decoration:none;position:absolute;top:0;right:0;line-height:24px;padding:2px 8px;font-size:20px;text-align:center}</style>';
		
			$html .= '<div class="updated"><div id="wpsc_gridview_upgrade_notice"><a href="http://a3rev.com/shop/" target="_blank" style="float:left; margin-right:10px;"><img src="'.WPSC_GRID_VIEW_IMAGES_URL.'/logo_a3blue.png" /></a>'.__("Grid View Lite is active. We've detected that your product image thumbnails are not scaled to size. Upgrade to", 'wpsc_gridview').' <a target="_blank" href="http://a3rev.com/shop/wp-e-commerce-grid-view/">'.__(' Grid View PRO', 'wpsc_gridview').'</a> '.__('to fix that.', 'wpsc_gridview').' <a class="hide" href="'.add_query_arg('hide-wpsc-gridview-upgrade-notice', 'true').'">&times;</a></div></div>';
			echo $html;	
		}
	}
	
	public static function plugin_extra_links($links, $plugin_name) {
		if ( $plugin_name != WPSC_GRID_VIEW_NAME) {
			return $links;
		}
		$links[] = '<a href="http://docs.a3rev.com/user-guides/wp-e-commerce/wpec-grid-view/" target="_blank">'.__('Documentation', 'wpsc_gridview').'</a>';
		$links[] = '<a href="http://wordpress.org/support/plugin/wp-e-commerce-grid-view/" target="_blank">'.__('Support', 'wpsc_gridview').'</a>';
		return $links;
	}
}
?>