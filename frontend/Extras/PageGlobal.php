<?php
/**
 * code
 *
 * @package   code
 * @author    ModelTheme <support@modeltheme.com>
 * @copyright Copyright (C) 2012-2022, Modeltheme, support@modeltheme.com
 * @license   GPL-3.0
 * @link      https://modeltheme.com
 */

namespace NFT_Marketplace_Core_Lite\Frontend\Extras;

use NFT_Marketplace_Core_Lite\Engine\Base;
use NFT_Marketplace_Core_Lite\Internals\Helper;

/**
 *
 * @package   NFT Marketplace Core Lite
 * @author    ModelTheme <support@modeltheme.com>
 * @copyright Copyright (C) 2012-2022, Modeltheme, support@modeltheme.com
 * @license   GPL-3.0
 * @link      https://modeltheme.com
 */
class PageGlobal extends Base
{
    public function initialize()
    {
        parent::initialize();

        add_action( 'nft_marketplace_core_before_main_content', [$this, 'breadcrumbs'], 10 , 2);
        add_action( 'nft_marketplace_core_search_listing_query', [$this, 'single_nft_block']);
        add_action( 'nft_marketplace_core_related_listing_query', [$this, 'single_nft_block']);
        add_action( 'nft_marketplace_core_single_nft_block_after_title', [$this, 'display_love_button']);
    }


    /* Function to display the love button on the front-end below every post */
    function display_love_button() {
        // Total counts for the post
        $post_id = get_the_ID();
        $love_count = get_post_meta($post_id, "nft_marketplace_core_love_count", true);
        $count      = (empty($love_count) || $love_count == "0") ? '0' : $love_count;
        // Prepare button html
        if(is_user_logged_in() && get_post_type() == 'nft-listing') {
            if (!Helper::isAlreadyLoved($post_id)) {
                echo '<div class="nft-listing-wishlist"><a href="#" data-post_id="' . esc_attr($post_id) . '"> <i class="fa fa-heart-o"></i></a><span>'.esc_html($count).'</span></div>';
            } else {
                echo '<div class="nft-listing-wishlist nft-listing-wishlist-loved"><a href="#" data-post_id="' .esc_attr($post_id) . '"><i class="fa fa-heart"></i></a><span>'.esc_html($count).'</span></div>';
            }
        } elseif(!is_user_logged_in() && get_post_type() == 'nft-listing') {
            if($count != '0') {
                echo '<div class="nft-listing-wishlist nft-listing-wishlist-loved"><a href="#" data-post_id="'.esc_attr($post_id).'"><i class="fa fa-heart-o"></i></a><span>'. esc_html($count).'</span></div>';
            } elseif($count == '0') {
                echo '<div class="nft-listing-wishlist"><a href="#" data-post_id="'.esc_attr($post_id).'"><i class="fa fa-heart-o"></i></a><span>'.esc_html($count).'</span></div>';
            }
        }
    }

    //GET HEADER TITLE/BREADCRUMBS AREA
    function breadcrumbs($actionBefore = "", $title="Explore All NFTs"){
        $term = get_queried_object();
        echo '<div class="nft_marketplace_core-breadcrumbs">';
        echo '<div class="container">';
        echo '<div class="row">';
        echo '<div class="col-md-12">';
        if (is_singular('nft-listing')) {
            echo '<h1>'. esc_html($actionBefore . get_the_title()).'</h1>';
        } else if(is_search()) {
            echo '<h1>'.esc_html__('Search results: ','nft-marketplace-core-lite').'</h1>';
        } else if (is_tax()) {
            $taxonomy = $term->taxonomy;
            $taxonomy_slug = $term->slug;
            if(is_tax($taxonomy, $taxonomy_slug)) {
                $term = get_queried_object();
                $taxonomy = $term->taxonomy;
                $taxonomy_slug = $term->slug;
                $taxonomy_name = $term->name;
                echo '<h1>'.esc_html($taxonomy_name).'</h1>';
            }
        } else {
            echo '<h1>'.apply_filters('nft_marketplace_core_taxonomy_heading', esc_html__($title, 'nft-marketplace-core-lite')).'</h1>';
        }
        echo'</div>';
        echo '<div class="col-md-12">';
        echo '<ol class="breadcrumb">';
        $delimiter = '';
        $name = esc_html__("Home", 'nft-marketplace-core-lite');
        if ((!is_home() && !is_front_page()) || is_paged()) {
            global $post;
            global $product;
            $home = home_url();
            echo '<li><a href="' . esc_url($home) . '">' . esc_html($name) . '</a></li> ' . esc_html($delimiter) . '';
            echo  '<li class="active">';
            the_title();
            echo  '</li>';
        }
        echo '</ol>';
        echo '</div>';
        echo'</div>';
        echo'</div>';
        echo'</div>';
    }

    // AUTHOR SOCIAL PROFILES//
    function social_profiles() {
        echo '<div class="author-socials">';
        $author = '';
        if(is_author()) {
            $author = get_user_by( 'slug', get_query_var( 'author_name' ) );
            $nft_marketplace_core_user_facebook = get_user_meta($author->ID,'nft_marketplace_core_user_facebook',true);
            $nft_marketplace_core_user_instagram = get_user_meta($author->ID,'nft_marketplace_core_user_instagram',true);
            $nft_marketplace_core_user_youtube = get_user_meta($author->ID,'nft_marketplace_core_user_youtube',true);
        } else {
            $author= get_post_field ('post_author', get_the_ID());
            $nft_marketplace_core_user_facebook = get_user_meta($author,'nft_marketplace_core_user_facebook',true);
            $nft_marketplace_core_user_instagram = get_user_meta($author,'nft_marketplace_core_user_instagram',true);
            $nft_marketplace_core_user_youtube = get_user_meta($author,'nft_marketplace_core_user_youtube',true);
        }
        echo '<ul class="listed-author-info">';
        if(!empty($author->user_url) ) {
            echo '<li><a href="'.esc_html($author->user_url).'"><i class="fa fa-globe" aria-hidden="true"></i></a></li>';
        }
        if(!empty($nft_marketplace_core_user_facebook)) {
            echo '<li><a href="'.esc_url($nft_marketplace_core_user_facebook).'"><i class="fa fa-facebook-square"></i></a></li>';
        }
        if(!empty($nft_marketplace_core_user_instagram)) {
            echo '<li><a href="'.esc_url($nft_marketplace_core_user_instagram).'"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>';
        }
        if(!empty($nft_marketplace_core_user_youtube)) {
            echo '<li><a href="'.esc_url($nft_marketplace_core_user_youtube).'"><i class="fa fa-youtube" aria-hidden="true"></i></a></li>';
        }
        echo '</ul>';
        echo '</div>';
    }


    /* SINGLE BLOCK NFT LISTING*/
    function single_nft_block($postId = null)
    {
        if(!is_numeric($postId)) {
            $postId = get_the_ID();
        }

        $text = esc_html__('View NFT', 'nft-marketplace-core-lite');
        echo '<div class="nft-listing-wrapper">';
        echo '<div class="nft-listing-image">';
        echo '<div class="overlay-container">';
        echo '<div class="hover-container">';
        echo '<div class="component add-to-cart">';
        echo '<a class="button" href="'.get_permalink($postId).'">' . apply_filters('nft_marketplace_core_bid_button', $text) . '</a>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '
            		<a class="nft-listing-media" href="' . esc_url(get_the_permalink()) . '">
                        '.Helper::getNFTDisplayAsset($postId, 'medium').'
            		</a>';
        echo '</div>';
        echo '<div class="nft-listing-title-metas">';
        echo '<div class="title-wrapper">';
        echo '<h3 class="nft-listing-name">';
        echo '<a href="' . esc_url(get_the_permalink()) . '">';
        the_title();
        echo '</a>';
        echo '</h3>';
        do_action('nft_marketplace_core_single_nft_block_after_title');
        echo '</div>';
        echo '<div class="details-container">';

        echo '</div>';

        echo '</div>';
        echo '</div>';
    }


    // COLLECTION NFT : QUERY LISTING COUNT
    // ARCHIVE NFT : CATEGORY SIDEBAR
    function archive_sidebar_category()
    {
        echo '<div class="nft-marketplace-sidebar-category">';
        echo '<h3 class="nft-sidebar-title">';
        echo apply_filters('nft_marketplace_core_category_text', esc_html__('Category', 'nft-marketplace-core-lite'));
        echo '</h3>';
        echo '<div class="nft-marketplace-categories">';
        $terms_c = get_terms(array(
            'taxonomy' => 'nft-listing-category'
        ));
        if ($terms_c) {
            foreach ($terms_c as $term) {
                if ($term->parent == 0) {
                    echo '<li>';
                    echo '<a href="' . esc_url(get_term_link($term->slug, 'nft-listing-category')) . '">' . esc_html($term->name) . '</a>';
                    echo '</li>';
                }
            }
        }
        echo '</div>';
        echo '</div>';
    }
}
