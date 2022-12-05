<?php

/**
 * NFT_Marketplace_Core
 *
 * @package   NFT_Marketplace_Core
 * @author    ModelTheme <support@modeltheme.com>
 * @copyright Copyright (C) 2012-2022, ModelTheme, support@modeltheme.com
 * @license   GPL v3
 * @link      https://modeltheme.com
 */

namespace NFT_Marketplace_Core\Internals;

use NFT_Marketplace_Core\Engine\Base;

/**
 * Post Types and Taxonomies
 */
class PostTypes extends Base {

	/**
	 * Initialize the class.
	 *
	 * @return void|bool
	 */
	public function initialize() { // phpcs:ignore
		parent::initialize();

		// WPBPGen{{#if libraries_johnbillion__extended-cpts}}
		\add_action( 'init', array( $this, 'load_cpts' ) );
		// {{/if}}
	}

	// WPBPGen{{#if frontend_cpt-search-support}}
	/**
	 * Add support for custom CPT on the search box
	 *
	 * @param \WP_Query $query WP_Query.
	 * @since 2.0.0
	 * @return \WP_Query
	 */
	public function filter_search( \WP_Query $query ) {
		if ( $query->is_search && !\is_admin() ) {
			$post_types = $query->get( 'post_type' );

			if ( 'post' === $post_types ) {
				$post_types = array( $post_types );
				$query->set( 'post_type', \array_push( $post_types, array( 'demo' ) ) );
			}
		}

		return $query;
	}
	// {{/if}}

	// WPBPGen{{#if libraries_johnbillion__extended-cpts}}
	/**
	 * Load CPT and Taxonomies on WordPress
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function load_cpts() { //phpcs:ignore
		// Create Custom Post Type https://github.com/johnbillion/extended-cpts/wiki

        \register_post_type(
				'nft-listing',
			array(
				'label' => esc_html__('NFT Listings', NFT_MARKETPLACE_CORE_TEXTDOMAIN),
				'description' => '',
				'public' => true,
				'show_ui' => true,
				'show_in_menu' => true,
				'capability_type' => 'post',
				'map_meta_cap' => true,
				'hierarchical' => false,
				'publicly_queryable' => true,
				'rewrite' => array('slug' => 'nft', 'with_front' => true),
				'query_var' => true,
				'menu_position' => '1',
				'menu_icon' => plugin_dir_url( __DIR__ ).'/backend/assets/images/nmc-logo.svg',
				'supports' => array('title', 'thumbnail', 'author'),
				'labels' => array(
					'name' => esc_html__('NFT Listings', NFT_MARKETPLACE_CORE_TEXTDOMAIN),
					'singular_name' => esc_html__('NFT Listings', NFT_MARKETPLACE_CORE_TEXTDOMAIN),
					'all_items' => esc_html__('NFT Listings', NFT_MARKETPLACE_CORE_TEXTDOMAIN),
					'menu_name' => esc_html__('NFT Marketplace Core', NFT_MARKETPLACE_CORE_TEXTDOMAIN),
					'add_new' => esc_html__('Add NFT', NFT_MARKETPLACE_CORE_TEXTDOMAIN),
					'add_new_item' => esc_html__('Add New NFT', NFT_MARKETPLACE_CORE_TEXTDOMAIN),
					'edit' => esc_html__('Edit', NFT_MARKETPLACE_CORE_TEXTDOMAIN),
					'edit_item' => esc_html__('Edit NFT', NFT_MARKETPLACE_CORE_TEXTDOMAIN),
					'new_item' => esc_html__('New NFT', NFT_MARKETPLACE_CORE_TEXTDOMAIN),
					'view' => esc_html__('View NFT', NFT_MARKETPLACE_CORE_TEXTDOMAIN),
					'view_item' => esc_html__('View NFT', NFT_MARKETPLACE_CORE_TEXTDOMAIN),
					'search_items' => esc_html__('Search NFT', NFT_MARKETPLACE_CORE_TEXTDOMAIN),
					'not_found' => esc_html__('No NFT Found', NFT_MARKETPLACE_CORE_TEXTDOMAIN),
					'not_found_in_trash' => esc_html__('No NFT Found in Trash', NFT_MARKETPLACE_CORE_TEXTDOMAIN),
					'parent' => esc_html__('Parent NFT', NFT_MARKETPLACE_CORE_TEXTDOMAIN),
				)
			)
		);

		// Create Custom Taxonomy https://github.com/johnbillion/extended-taxos
		$labels = array(
			'name' => esc_html_x('Collections', 'Taxonomy General Name', NFT_MARKETPLACE_CORE_TEXTDOMAIN),
			'singular_name' => esc_html_x('Collections', 'Taxonomy Singular Name', NFT_MARKETPLACE_CORE_TEXTDOMAIN),
			'menu_name' => esc_html__('Collections', NFT_MARKETPLACE_CORE_TEXTDOMAIN),
			'all_items' => esc_html__('All Items', NFT_MARKETPLACE_CORE_TEXTDOMAIN),
			'parent_item' => esc_html__('Parent Item', NFT_MARKETPLACE_CORE_TEXTDOMAIN),
			'parent_item_colon' => esc_html__('Parent Item:', NFT_MARKETPLACE_CORE_TEXTDOMAIN),
			'new_item_name' => esc_html__('New Item Name', NFT_MARKETPLACE_CORE_TEXTDOMAIN),
			'add_new_item' => esc_html__('Add New Item', NFT_MARKETPLACE_CORE_TEXTDOMAIN),
			'edit_item' => esc_html__('Edit Item', NFT_MARKETPLACE_CORE_TEXTDOMAIN),
			'update_item' => esc_html__('Update Item', NFT_MARKETPLACE_CORE_TEXTDOMAIN),
			'view_item' => esc_html__('View Item', NFT_MARKETPLACE_CORE_TEXTDOMAIN),
			'separate_items_with_commas' => esc_html__('Separate items with commas', NFT_MARKETPLACE_CORE_TEXTDOMAIN),
			'add_or_remove_items' => esc_html__('Add or remove items', NFT_MARKETPLACE_CORE_TEXTDOMAIN),
			'choose_from_most_used' => esc_html__('Choose from the most used', NFT_MARKETPLACE_CORE_TEXTDOMAIN),
			'popular_items' => esc_html__('Popular Items', NFT_MARKETPLACE_CORE_TEXTDOMAIN),
			'search_items' => esc_html__('Search Items', NFT_MARKETPLACE_CORE_TEXTDOMAIN),
			'not_found' => esc_html__('Not Found', NFT_MARKETPLACE_CORE_TEXTDOMAIN),
		);

		$args = array(
			'hierarchical' => false,
			'labels' => $labels,
			'show_ui' => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => true,
			'query_var' => true,
			'rewrite' => array('slug' => 'collection'),
			'public' => true,
		);
		register_taxonomy('nft-listing-collection', array('nft-listing'), $args);

        // Create Custom Taxonomy https://github.com/johnbillion/extended-taxos
        $labels = array(
            'name' => esc_html_x('Custom Currency', 'Taxonomy General Name', NFT_MARKETPLACE_CORE_TEXTDOMAIN),
            'singular_name' => esc_html_x('Currency', 'Taxonomy Singular Name', NFT_MARKETPLACE_CORE_TEXTDOMAIN),
            'menu_name' => esc_html__('Currencies', NFT_MARKETPLACE_CORE_TEXTDOMAIN),
            'all_items' => esc_html__('All Items', NFT_MARKETPLACE_CORE_TEXTDOMAIN),
            'parent_item' => esc_html__('Parent Item', NFT_MARKETPLACE_CORE_TEXTDOMAIN),
            'parent_item_colon' => esc_html__('Parent Item:', NFT_MARKETPLACE_CORE_TEXTDOMAIN),
            'new_item_name' => esc_html__('New Item Name', NFT_MARKETPLACE_CORE_TEXTDOMAIN),
            'add_new_item' => esc_html__('Add New Item', NFT_MARKETPLACE_CORE_TEXTDOMAIN),
            'edit_item' => esc_html__('Edit Item', NFT_MARKETPLACE_CORE_TEXTDOMAIN),
            'update_item' => esc_html__('Update Item', NFT_MARKETPLACE_CORE_TEXTDOMAIN),
            'view_item' => esc_html__('View Item', NFT_MARKETPLACE_CORE_TEXTDOMAIN),
            'separate_items_with_commas' => esc_html__('Separate items with commas', NFT_MARKETPLACE_CORE_TEXTDOMAIN),
            'add_or_remove_items' => esc_html__('Add or remove items', NFT_MARKETPLACE_CORE_TEXTDOMAIN),
            'choose_from_most_used' => esc_html__('Choose from the most used', NFT_MARKETPLACE_CORE_TEXTDOMAIN),
            'popular_items' => esc_html__('Popular Items', NFT_MARKETPLACE_CORE_TEXTDOMAIN),
            'search_items' => esc_html__('Search Items', NFT_MARKETPLACE_CORE_TEXTDOMAIN),
            'not_found' => esc_html__('Not Found', NFT_MARKETPLACE_CORE_TEXTDOMAIN),
        );

        $args = array(
            'hierarchical' => false,
            'labels' => $labels,
            'show_ui' => true,
            'show_admin_column' => true,
            'show_in_nav_menus' => true,
            'query_var' => true,
            'rewrite' => false,
            'public' => false,
        );

        register_taxonomy('nft-listing-currency', array('nft-listing'), $args);

		$labels = array(
			'name' => esc_html_x('Blockchains', 'Taxonomy General Name', NFT_MARKETPLACE_CORE_TEXTDOMAIN),
			'singular_name' => esc_html_x('Blockchain', 'Taxonomy Singular Name', NFT_MARKETPLACE_CORE_TEXTDOMAIN),
			'menu_name' => esc_html__('Blockchains', NFT_MARKETPLACE_CORE_TEXTDOMAIN),
			'all_items' => esc_html__('All Blockchains', NFT_MARKETPLACE_CORE_TEXTDOMAIN),
			'parent_item' => esc_html__('Parent Item (leave empty)', NFT_MARKETPLACE_CORE_TEXTDOMAIN),
			'parent_item_colon' => esc_html__('Parent Item (leave empty):', NFT_MARKETPLACE_CORE_TEXTDOMAIN),
			'new_item_name' => esc_html__('New Item Name', NFT_MARKETPLACE_CORE_TEXTDOMAIN),
			'add_new_item' => esc_html__('Add New Blockchain', NFT_MARKETPLACE_CORE_TEXTDOMAIN),
			'edit_item' => esc_html__('Edit Blockchain', NFT_MARKETPLACE_CORE_TEXTDOMAIN),
			'update_item' => esc_html__('Update Blockchain', NFT_MARKETPLACE_CORE_TEXTDOMAIN),
			'view_item' => esc_html__('View Blockchains', NFT_MARKETPLACE_CORE_TEXTDOMAIN),
			'separate_items_with_commas' => esc_html__('Separate items with commas', NFT_MARKETPLACE_CORE_TEXTDOMAIN),
			'add_or_remove_items' => esc_html__('Add or remove items', NFT_MARKETPLACE_CORE_TEXTDOMAIN),
			'choose_from_most_used' => esc_html__('Choose from the most used', NFT_MARKETPLACE_CORE_TEXTDOMAIN),
			'popular_items' => esc_html__('Popular Items', NFT_MARKETPLACE_CORE_TEXTDOMAIN),
			'search_items' => esc_html__('Search Items', NFT_MARKETPLACE_CORE_TEXTDOMAIN),
			'not_found' => esc_html__('Not Found', NFT_MARKETPLACE_CORE_TEXTDOMAIN),
		);

		$args = array(
			'hierarchical' => false,
			'labels' => $labels,
			'show_ui' => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => true,
			'query_var' => true,
			'rewrite' => array('slug' => 'blockchain'),
			'public' => true,
		);

		register_taxonomy('nft_listing_blockchains', array('nft-listing'), $args);

		$labels = array(
			'name' => esc_html_x('Categories', 'Taxonomy General Name', NFT_MARKETPLACE_CORE_TEXTDOMAIN),
			'singular_name' => esc_html_x('Category', 'Taxonomy Singular Name', NFT_MARKETPLACE_CORE_TEXTDOMAIN),
			'menu_name' => esc_html__('Categories', NFT_MARKETPLACE_CORE_TEXTDOMAIN),
			'all_items' => esc_html__('All Categories', NFT_MARKETPLACE_CORE_TEXTDOMAIN),
			'parent_item' => esc_html__('Parent Item (leave empty)', NFT_MARKETPLACE_CORE_TEXTDOMAIN),
			'parent_item_colon' => esc_html__('Parent Item (leave empty):', NFT_MARKETPLACE_CORE_TEXTDOMAIN),
			'new_item_name' => esc_html__('New Category Name', NFT_MARKETPLACE_CORE_TEXTDOMAIN),
			'add_new_item' => esc_html__('Add Category Item', NFT_MARKETPLACE_CORE_TEXTDOMAIN),
			'edit_item' => esc_html__('Edit Category', NFT_MARKETPLACE_CORE_TEXTDOMAIN),
			'update_item' => esc_html__('Update Category', NFT_MARKETPLACE_CORE_TEXTDOMAIN),
			'view_item' => esc_html__('View Category', NFT_MARKETPLACE_CORE_TEXTDOMAIN),
			'separate_items_with_commas' => esc_html__('Separate Categories with commas', NFT_MARKETPLACE_CORE_TEXTDOMAIN),
			'add_or_remove_items' => esc_html__('Add or remove Categories', NFT_MARKETPLACE_CORE_TEXTDOMAIN),
			'choose_from_most_used' => esc_html__('Choose from the most used', NFT_MARKETPLACE_CORE_TEXTDOMAIN),
			'popular_items' => esc_html__('Popular Categories', NFT_MARKETPLACE_CORE_TEXTDOMAIN),
			'search_items' => esc_html__('Search Categories', NFT_MARKETPLACE_CORE_TEXTDOMAIN),
			'not_found' => esc_html__('Not Found', NFT_MARKETPLACE_CORE_TEXTDOMAIN),
		);

		$args = array(
			'hierarchical' => false,
			'labels' => $labels,
			'show_ui' => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => true,
			'query_var' => true,
			'rewrite' => array('slug' => 'category'),
			'public' => true,
		);

		register_taxonomy('nft-listing-category', array('nft-listing'), $args);
	}
	// {{/if}}

	// WPBPGen{{#if backend_bubble-notification-pending-cpt}}
	/**
	 * Bubble Notification for pending cpt<br>
	 * NOTE: add in $post_types your cpts<br>
	 *
	 *        Reference:  http://wordpress.stackexchange.com/questions/89028/put-update-like-notification-bubble-on-multiple-cpts-menus-for-pending-items/95058
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function pending_cpt_bubble() {
		global $menu;

		$post_types = array( 'demo' );

		foreach ( $post_types as $type ) {
			if ( !\post_type_exists( $type ) ) {
				continue;
			}

			// Count posts
			$cpt_count = \wp_count_posts( $type );

			if ( !$cpt_count->pending ) {
				continue;
			}

			// Locate the key of
			$key = self::recursive_array_search_php( 'edit.php?post_type=' . $type, $menu );

			// Not found, just in case
			if ( !$key ) {
				return;
			}

			// Modify menu item
			$menu[ $key ][ 0 ] .= \sprintf( //phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
				'<span class="update-plugins count-%1$s"><span class="plugin-count">%1$s</span></span>',
				$cpt_count->pending
			);
		}
	}

	/**
	 * Required for the bubble notification<br>
	 *
	 *  Reference:  http://wordpress.stackexchange.com/questions/89028/put-update-like-notification-bubble-on-multiple-cpts-menus-for-pending-items/95058
	 *
	 * @param string $needle First parameter.
	 * @param array  $haystack Second parameter.
	 * @since 2.0.0
	 * @return string|bool
	 */
	private function recursive_array_search_php( string $needle, array $haystack ) {
		foreach ( $haystack as $key => $value ) {
			$current_key = $key;

			if (
				$needle === $value ||
				( \is_array( $value ) &&
				false !== self::recursive_array_search_php( $needle, $value ) )
			) {
				return $current_key;
			}
		}

		return false;
	}
	// {{/if}}

}
