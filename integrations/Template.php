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

namespace NFT_Marketplace_Core_Lite\Integrations;

use NFT_Marketplace_Core_Lite\Engine\Base;

/**
 * Load custom template files
 */
class Template extends Base
{
    /**
     * Initialize the class.
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        //FILTERS
        add_filter('single_template', [$this, 'single_template_from_directory_nft_listing']);
        add_filter('taxonomy_template', [$this, 'taxonomy_template_from_directory_nft_listing']);
        add_filter('template_redirect', [$this, 'views_count']);
        add_filter('template_include', [$this, 'search_template']);
        add_filter('template_include', [$this, 'author_template']);
        add_filter('template_include', [$this, 'collection_template']);
        add_filter('template_include', [$this, 'category_template']);
        add_filter('page_template', [$this, 'listings_template']);

        // EDIT NFT Page
        add_filter('single_template', [$this, 'edit_template_from_directory'],10);
        // List NFT Page
        add_filter('single_template', [$this, 'list_nft_template_from_directory'],10);
        // User Panel
        add_filter('page_template', [$this, 'manage_nft_panel_template_from_directory'],10);

        add_filter('single_template', [$this, 'unlock_nft_template_from_directory'],10);
    }

    function list_nft_template_from_directory($single)
    {
        global $wp_query, $post;
        $theme_files = 'nft-marketplace-core/list-nft-listing.php';
        $exists_in_theme = locate_template($theme_files, false);
        /* Checks for single template by post type */
        if ($post->post_type === 'nft-listing' && isset($_GET['list-nft']) && is_single()) {
            if ($exists_in_theme != '') {
                // Try to locate in theme first
                $single = $exists_in_theme;
            } else {
                $single = plugin_dir_path(__DIR__) . 'templates/list-nft-listing.php';
            }
        }
        return $single;
    }

    function unlock_nft_template_from_directory($single)
    {
        global $wp_query, $post;
        $theme_files = 'nft-marketplace-core/list-nft-listing.php';
        $exists_in_theme = locate_template($theme_files, false);
        /* Checks for single template by post type */
        if ($post->post_type === 'nft-listing' && isset($_GET['unlock-nft-content']) && is_single()) {
            if ($exists_in_theme != '') {
                // Try to locate in theme first
                $single = $exists_in_theme;
            } else {
                $single = plugin_dir_path(__DIR__) . 'templates/unlock-nft-content.php';
            }
        }
        return $single;
    }


    function edit_template_from_directory($single)
    {
        global $wp_query, $post;
        $theme_files = 'nft-marketplace-core/edit-nft-single.php';
        $exists_in_theme = locate_template($theme_files, false);
        /* Checks for single template by post type */
        if ($post->post_type === 'nft-listing' && isset($_GET['edit-nft']) && is_single()) {
            if ($exists_in_theme != '') {
                // Try to locate in theme first
                $single = $exists_in_theme;
            } else {
                if (file_exists(plugin_dir_path(__DIR__) . 'templates/edit-nft-single.php')) {
                    $single = plugin_dir_path(__DIR__) . 'templates/edit-nft-single.php';
                }
            }
        }
        return $single;
    }

    function manage_nft_panel_template_from_directory($template)
    {
        if (is_page_template('nft-marketplace-core-manage-nft-page.php')) {
            $template = plugin_dir_path(__DIR__) . 'templates/nft-marketplace-core-manage-nft-page.php';
        }
        return $template;
    }

    function taxonomy_template_from_directory_nft_listing($template)
    {
        $taxonomy_array = array('nft-listing-category');
        $theme_files = 'nft-marketplace-core/taxonomy-nft-listing.php';
        $exists_in_theme = locate_template($theme_files, false);
        foreach ($taxonomy_array as $taxonomy_single) {
            if (is_tax($taxonomy_single)) {
                if ($exists_in_theme != '') {
                    $template = $exists_in_theme;
                } else {
                    $template = plugin_dir_path(__DIR__) . 'templates/taxonomy-nft-listing.php';
                }
            }
        }
        return $template;
    }


    /* Filter the single_template with our custom function*/
    function single_template_from_directory_nft_listing($single)
    {
        global $post;
        $theme_files = 'nft-marketplace-core/single-nft-listing.php';
        $exists_in_theme = locate_template($theme_files, false);
        /* Checks for single template by post type */
        if ($post->post_type == 'nft-listing' && is_single()) {
            if ($exists_in_theme != '') {
                $single = $exists_in_theme;
            } else {
                if (file_exists(plugin_dir_path(__DIR__) . 'templates/single-nft-listing.php')) {
                    $single = plugin_dir_path(__DIR__) . 'templates/single-nft-listing.php';
                }
            }
        }
        return $single;
    }

    //Overwrite search theme template
    function search_template($template)
    {
        global $wp_query;
        if (!$wp_query->is_search)
            return $template;
        return plugin_dir_path(__DIR__) . 'templates/search.php';
    }


    //Overwrite author theme template
    function author_template($template)
    {
        global $wp_query;
        if (!$wp_query->is_author)
            return $template;
        return plugin_dir_path(__DIR__) . 'templates/author.php';
    }

    //Overwrite author collection template
    function collection_template($template)
    {
        global $wp_query;
        if (array_key_exists("nft-listing-category", $wp_query->query))
            return plugin_dir_path(__DIR__) . 'templates/collection.php';

        return $template;
    }

    //Overwrite author category template
    function category_template($template)
    {
        global $wp_query;
        if (array_key_exists("category_name", $wp_query->query))
            return plugin_dir_path(__DIR__) . 'templates/category.php';

        return $template;
    }

    //Overwrite Page NFT Listings
    function listings_template($template)
    {
        global $wp_query;
        $theme_files = 'nft-marketplace-core/template-nft-listings.php';
        $exists_in_theme = locate_template($theme_files, false);
        if (!$wp_query->is_page('nft-listings')) {
            return $template;
        } else {
            if ($exists_in_theme != '') {
                $template = $exists_in_theme;
            } else {
                $template = plugin_dir_path(__DIR__) . 'templates/template-nft-listings.php';
            }
            return $template;
        }
    }

    //SET POST VIEW
    function views_count()
    {
        $countKey = 'nft_marketplace_core_post_views_count';
        $postID = get_the_ID();
        if (get_post_type() == 'nft-listing' && is_singular()) {
            $count = get_post_meta($postID, $countKey, true);
            if ($count == '') {
                $count = 0;
                update_post_meta($postID, $countKey, '0');
            } else {
                $count++;
                update_post_meta($postID, $countKey, $count);
            }
        }
    }

}
