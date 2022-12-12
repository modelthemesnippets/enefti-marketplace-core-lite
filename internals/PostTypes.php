<?php

/**
 * NFT_Marketplace_Core_Lite
 *
 * @package   NFT_Marketplace_Core_Lite
 * @author    ModelTheme <support@modeltheme.com>
 * @copyright Copyright (C) 2012-2022, ModelTheme, support@modeltheme.com
 * @license   GPL v3
 * @link      https://modeltheme.com
 */

namespace NFT_Marketplace_Core_Lite\Internals;

use NFT_Marketplace_Core_Lite\Engine\Base;

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
				'label' => esc_html__('NFT Listings', 'nft-marketplace-core-lite'),
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
					'name' => esc_html__('NFT Listings', 'nft-marketplace-core-lite'),
					'singular_name' => esc_html__('NFT Listings', 'nft-marketplace-core-lite'),
					'all_items' => esc_html__('NFT Listings', 'nft-marketplace-core-lite'),
					'menu_name' => esc_html__('NFT Marketplace Core', 'nft-marketplace-core-lite'),
					'add_new' => esc_html__('Add NFT', 'nft-marketplace-core-lite'),
					'add_new_item' => esc_html__('Add New NFT', 'nft-marketplace-core-lite'),
					'edit' => esc_html__('Edit', 'nft-marketplace-core-lite'),
					'edit_item' => esc_html__('Edit NFT', 'nft-marketplace-core-lite'),
					'new_item' => esc_html__('New NFT', 'nft-marketplace-core-lite'),
					'view' => esc_html__('View NFT', 'nft-marketplace-core-lite'),
					'view_item' => esc_html__('View NFT', 'nft-marketplace-core-lite'),
					'search_items' => esc_html__('Search NFT', 'nft-marketplace-core-lite'),
					'not_found' => esc_html__('No NFT Found', 'nft-marketplace-core-lite'),
					'not_found_in_trash' => esc_html__('No NFT Found in Trash', 'nft-marketplace-core-lite'),
					'parent' => esc_html__('Parent NFT', 'nft-marketplace-core-lite'),
				)
			)
		);

		// Create Custom Taxonomy https://github.com/johnbillion/extended-taxos
		$labels = array(
			'name' => esc_html_x('Collections', 'Taxonomy General Name', 'nft-marketplace-core-lite'),
			'singular_name' => esc_html_x('Collections', 'Taxonomy Singular Name', 'nft-marketplace-core-lite'),
			'menu_name' => esc_html__('Collections', 'nft-marketplace-core-lite'),
			'all_items' => esc_html__('All Items', 'nft-marketplace-core-lite'),
			'parent_item' => esc_html__('Parent Item', 'nft-marketplace-core-lite'),
			'parent_item_colon' => esc_html__('Parent Item:', 'nft-marketplace-core-lite'),
			'new_item_name' => esc_html__('New Item Name', 'nft-marketplace-core-lite'),
			'add_new_item' => esc_html__('Add New Item', 'nft-marketplace-core-lite'),
			'edit_item' => esc_html__('Edit Item', 'nft-marketplace-core-lite'),
			'update_item' => esc_html__('Update Item', 'nft-marketplace-core-lite'),
			'view_item' => esc_html__('View Item', 'nft-marketplace-core-lite'),
			'separate_items_with_commas' => esc_html__('Separate items with commas', 'nft-marketplace-core-lite'),
			'add_or_remove_items' => esc_html__('Add or remove items', 'nft-marketplace-core-lite'),
			'choose_from_most_used' => esc_html__('Choose from the most used', 'nft-marketplace-core-lite'),
			'popular_items' => esc_html__('Popular Items', 'nft-marketplace-core-lite'),
			'search_items' => esc_html__('Search Items', 'nft-marketplace-core-lite'),
			'not_found' => esc_html__('Not Found', 'nft-marketplace-core-lite'),
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
            'name' => esc_html_x('Custom Currency', 'Taxonomy General Name', 'nft-marketplace-core-lite'),
            'singular_name' => esc_html_x('Currency', 'Taxonomy Singular Name', 'nft-marketplace-core-lite'),
            'menu_name' => esc_html__('Currencies', 'nft-marketplace-core-lite'),
            'all_items' => esc_html__('All Items', 'nft-marketplace-core-lite'),
            'parent_item' => esc_html__('Parent Item', 'nft-marketplace-core-lite'),
            'parent_item_colon' => esc_html__('Parent Item:', 'nft-marketplace-core-lite'),
            'new_item_name' => esc_html__('New Item Name', 'nft-marketplace-core-lite'),
            'add_new_item' => esc_html__('Add New Item', 'nft-marketplace-core-lite'),
            'edit_item' => esc_html__('Edit Item', 'nft-marketplace-core-lite'),
            'update_item' => esc_html__('Update Item', 'nft-marketplace-core-lite'),
            'view_item' => esc_html__('View Item', 'nft-marketplace-core-lite'),
            'separate_items_with_commas' => esc_html__('Separate items with commas', 'nft-marketplace-core-lite'),
            'add_or_remove_items' => esc_html__('Add or remove items', 'nft-marketplace-core-lite'),
            'choose_from_most_used' => esc_html__('Choose from the most used', 'nft-marketplace-core-lite'),
            'popular_items' => esc_html__('Popular Items', 'nft-marketplace-core-lite'),
            'search_items' => esc_html__('Search Items', 'nft-marketplace-core-lite'),
            'not_found' => esc_html__('Not Found', 'nft-marketplace-core-lite'),
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
			'name' => esc_html_x('Blockchains', 'Taxonomy General Name', 'nft-marketplace-core-lite'),
			'singular_name' => esc_html_x('Blockchain', 'Taxonomy Singular Name', 'nft-marketplace-core-lite'),
			'menu_name' => esc_html__('Blockchains', 'nft-marketplace-core-lite'),
			'all_items' => esc_html__('All Blockchains', 'nft-marketplace-core-lite'),
			'parent_item' => esc_html__('Parent Item (leave empty)', 'nft-marketplace-core-lite'),
			'parent_item_colon' => esc_html__('Parent Item (leave empty):', 'nft-marketplace-core-lite'),
			'new_item_name' => esc_html__('New Item Name', 'nft-marketplace-core-lite'),
			'add_new_item' => esc_html__('Add New Blockchain', 'nft-marketplace-core-lite'),
			'edit_item' => esc_html__('Edit Blockchain', 'nft-marketplace-core-lite'),
			'update_item' => esc_html__('Update Blockchain', 'nft-marketplace-core-lite'),
			'view_item' => esc_html__('View Blockchains', 'nft-marketplace-core-lite'),
			'separate_items_with_commas' => esc_html__('Separate items with commas', 'nft-marketplace-core-lite'),
			'add_or_remove_items' => esc_html__('Add or remove items', 'nft-marketplace-core-lite'),
			'choose_from_most_used' => esc_html__('Choose from the most used', 'nft-marketplace-core-lite'),
			'popular_items' => esc_html__('Popular Items', 'nft-marketplace-core-lite'),
			'search_items' => esc_html__('Search Items', 'nft-marketplace-core-lite'),
			'not_found' => esc_html__('Not Found', 'nft-marketplace-core-lite'),
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
			'name' => esc_html_x('Categories', 'Taxonomy General Name', 'nft-marketplace-core-lite'),
			'singular_name' => esc_html_x('Category', 'Taxonomy Singular Name', 'nft-marketplace-core-lite'),
			'menu_name' => esc_html__('Categories', 'nft-marketplace-core-lite'),
			'all_items' => esc_html__('All Categories', 'nft-marketplace-core-lite'),
			'parent_item' => esc_html__('Parent Item (leave empty)', 'nft-marketplace-core-lite'),
			'parent_item_colon' => esc_html__('Parent Item (leave empty):', 'nft-marketplace-core-lite'),
			'new_item_name' => esc_html__('New Category Name', 'nft-marketplace-core-lite'),
			'add_new_item' => esc_html__('Add Category Item', 'nft-marketplace-core-lite'),
			'edit_item' => esc_html__('Edit Category', 'nft-marketplace-core-lite'),
			'update_item' => esc_html__('Update Category', 'nft-marketplace-core-lite'),
			'view_item' => esc_html__('View Category', 'nft-marketplace-core-lite'),
			'separate_items_with_commas' => esc_html__('Separate Categories with commas', 'nft-marketplace-core-lite'),
			'add_or_remove_items' => esc_html__('Add or remove Categories', 'nft-marketplace-core-lite'),
			'choose_from_most_used' => esc_html__('Choose from the most used', 'nft-marketplace-core-lite'),
			'popular_items' => esc_html__('Popular Categories', 'nft-marketplace-core-lite'),
			'search_items' => esc_html__('Search Categories', 'nft-marketplace-core-lite'),
			'not_found' => esc_html__('Not Found', 'nft-marketplace-core-lite'),
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


}
