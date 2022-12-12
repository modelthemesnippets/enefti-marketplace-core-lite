<?php

namespace NFT_Marketplace_Core_Lite\Frontend\Extras;

use NFT_Marketplace_Core_Lite\Engine\Base;

/**
 *
 * @package   NFT Marketplace Core Lite
 * @author    ModelTheme <support@modeltheme.com>
 * @copyright Copyright (C) 2012-2022, Modeltheme, support@modeltheme.com
 * @license   GPL-3.0
 * @link      https://modeltheme.com
 */
class PageSingleNFT extends PageGlobal
{
    public function initialize()
    {
        if (!isset(get_option('nft_marketplace_core_panel_single_nft')['nft_marketplace_core_views']) || isset(get_option('nft_marketplace_core_panel_single_nft')['nft_marketplace_core_views']) && get_option('nft_marketplace_core_panel_single_nft')['nft_marketplace_core_views'] == 'on') {
            add_action('nft_marketplace_core_single_nft_before_title', [$this, 'display_views_count'], 20);
        }

        add_action('nft_marketplace_core_single_nft_before_title', [$this, 'displayTags'], 20);

        add_action('nft_marketplace_core_single_nft_before_title', [$this, 'display_owner'], 10);
        add_action('nft_marketplace_core_single_nft_after_button', [$this, 'single_nft_meta']);

        if (!isset(get_option('nft_marketplace_core_panel_single_nft')['nft_marketplace_core_related']) || isset(get_option('nft_marketplace_core_panel_single_nft')['nft_marketplace_core_related']) && get_option('nft_marketplace_core_panel_single_nft')['nft_marketplace_core_related'] == 'on') {
            add_action('nft_marketplace_core_single_nft_related', [$this, 'single_nft_related']);
        }

        if (isset(get_option('nft_marketplace_core_panel_single_nft')['nft_marketplace_core_likes']) || isset(get_option('nft_marketplace_core_panel_single_nft')['nft_marketplace_core_likes']) && get_option('nft_marketplace_core_panel_single_nft')['nft_marketplace_core_likes'] == 'on') {
            add_action('nft_marketplace_core_single_nft_before_title', [$this, 'display_love_button'], 30);
        }

        add_action('nft_marketplace_core_single_nft_before_author_tab', [$this, 'single_nft_tab_stats'], 10);
        add_action('nft_marketplace_core_single_nft_before_author_tab', [$this, 'single_nft_tab_properties'], 20);
        add_action('nft_marketplace_core_single_nft_before_author_tab', [$this, 'single_nft_tab_levels'], 30);
        add_action('nft_marketplace_core_single_nft_after_metas', [$this, 'single_nft_tabs_wrapper']);
        add_action('nft_marketplace_core_single_nft_before_related', [$this, 'single_nft_tabs_wrapper']);
        add_action('nft_marketplace_core_single_nft_after_levels_tab', [$this, 'single_nft_tab_auth'], 10);

        add_action('nft_marketplace_core_single_nft_after_title_category', [$this, 'single_nft_single_category']);
        add_action('nft_marketplace_core_single_nft_before_collection_text', [$this, 'single_nft_category']);
        add_action('nft_marketplace_core_single_nft_after_title_collection', [$this, 'single_nft_single_collection']);
        add_action('nft_marketplace_core_single_nft_after_levels_tab', [$this, 'social_profiles'], 20);
    }

    /* NFT LISTING : CALL CATEGORY*/
    function single_nft_category() {
        $all_categories = get_the_term_list( get_the_ID(), 'nft-listing-category', '', ' / ' );
        if ($all_categories) {
            echo wp_kses($all_categories, 'categories');
        }
    }

    /* NFT LISTING : CALL SINGLE CATEGORY*/
    function single_nft_single_collection() {
        $all_categories = get_the_terms( get_the_ID(), 'nft-listing-collection');
        if($all_categories) {
            $category = $all_categories[0];
            $term_link = get_term_link($category);
            echo '<a href="'.esc_url($term_link).'">'.esc_html($category->name).'</a>';
        }
    }

    function displayTags() {
        $isExplicit = get_post_meta( get_the_ID(), 'nft_marketplace_core_nft_listing_explicit_meta', true);

        if ($isExplicit) {
            echo '<div class="nft-listing-views-counter"><i class="fa fa-eye-slash" aria-hidden="true"></i><span>'.esc_html__('Explicit','nft-marketplace-core-lite').'</span></div>';
        }

        $hasUnlockableContent = get_post_meta( get_the_ID(), 'nft_marketplace_core_nft_listing_content_meta', true);

        if ($hasUnlockableContent) {
            echo '<div class="nft-listing-views-counter"><i class="fa fa-archive" aria-hidden="true"></i><span>'.esc_html__('Unlockable','nft-marketplace-core-lite').'</span></div>';
        }
    }

    /* NFT LISTING : CALL SINGLE CATEGORY*/
    function single_nft_single_category() {
        $all_categories = get_the_terms( get_the_ID(), 'nft-listing-category');
        if($all_categories) {
            $category = $all_categories[0];
            $term_link = get_term_link($category);
            echo '<a href="'.esc_url($term_link).'">'.esc_html($category->name).'</a>';
        }
    }

    //SINGLE NFT : META INFO
    function single_nft_meta(){
        $nft_marketplace_core_nft_listing_sales = get_post_meta(get_the_ID(), 'nft_marketplace_core_nft_listing_sales', true);
        echo '<div class="nft-listing-meta">';
        echo '<div class="meta-id">';
        echo '<span>'.apply_filters('nft_marketplace_core_id_text', esc_html__('ID', 'nft-marketplace-core-lite')).': </span>'.get_the_ID().'';
        echo '</div>';
        echo '<div class="meta-category">';
        echo '<span>'.apply_filters('nft_marketplace_core_category_text', esc_html__('Category', 'nft-marketplace-core-lite')).': </span>';
        do_action('nft_marketplace_core_single_nft_before_collection_text');
        echo '</div>';
        echo '<div class="meta-category">';
        echo '<span>'.apply_filters('nft_marketplace_core_presale_text', esc_html__('Presale', 'nft-marketplace-core-lite')).': </span>';
        echo 'Yes';
        echo '</div>';
        if($nft_marketplace_core_nft_listing_sales) {
            echo '<div class="meta-category">';
            echo '<span>'.apply_filters('nft_marketplace_core_sales_text', esc_html__('Sales', 'nft-marketplace-core-lite')).': </span>';
            echo esc_attr($nft_marketplace_core_nft_listing_sales);
            echo '</div>';
        }
        echo'</div>';
    }


    //SINGLE NFT : STATS TAB
    function single_nft_tab_stats() {
        $nft_marketplace_core_nft_listing_group = get_post_meta(get_the_ID(), 'nft_marketplace_core_nft_listing_group', true);
        echo '<section class="nft-listing-stats" id="tab-stats">';
        if($nft_marketplace_core_nft_listing_group) {
            echo '<ol>';
            foreach (array_reverse($nft_marketplace_core_nft_listing_group) as $nft_listing) {
                echo '<li>';
                if(!empty($nft_listing['nft_marketplace_core_nft_listing_stat_name'])){
                    echo '<span>'.esc_html( $nft_listing['nft_marketplace_core_nft_listing_stat_name'] ).'</span>';
                }
                echo '<span>';
                if(!empty($nft_listing['nft_marketplace_core_nft_listing_stat_rate'])){
                    echo esc_html( $nft_listing['nft_marketplace_core_nft_listing_stat_rate'] );
                }
                echo esc_html__(' out of ','nft-marketplace-core-lite');
                if(!empty($nft_listing['nft_marketplace_core_nft_listing_stat_out_of'])){
                    echo esc_html( $nft_listing['nft_marketplace_core_nft_listing_stat_out_of'] );
                }
                echo '</span>';
                echo '</li>';
            }
            echo '</ol>';
        } else {
            echo '<p>'.apply_filters('nft_marketplace_core_tab_no_text', esc_html__('This NFT Listing does not have', 'nft-marketplace-core-lite')).' '.apply_filters('nft_marketplace_core_stats_tab_text', esc_html__('Stats', 'nft-marketplace-core-lite')).'</p>';
        }
        echo '</section>';
    }


    //SINGLE NFT : PROPERTIES TAB
    function single_nft_tab_properties() {
        $nft_marketplace_core_nft_listing_properties_group = get_post_meta(get_the_ID(), 'nft_marketplace_core_nft_listing_properties_group', true);
        echo '<section class="nft-listing-properties" id="tab-properties">';
        if($nft_marketplace_core_nft_listing_properties_group) {
            foreach (array_reverse($nft_marketplace_core_nft_listing_properties_group) as $nft_listing) {
                echo '<div class="properties-item">';
                if(!empty($nft_listing['nft_marketplace_core_nft_listing_property_name'])){
                    echo '<span class="properties-item-name">'.esc_html( $nft_listing['nft_marketplace_core_nft_listing_property_name'] ).'</span>';
                }
                if(!empty($nft_listing['nft_marketplace_core_nft_listing_property_value'])){
                    echo '<span class="properties-item-value">'.esc_html( $nft_listing['nft_marketplace_core_nft_listing_property_value'] ).'</span>';
                }
                echo '</div>';
            }
        } else {
            echo '<p>'.apply_filters('nft_marketplace_core_tab_no_text', esc_html__('This NFT Listing does not have', 'nft-marketplace-core-lite')).' '.apply_filters('nft_marketplace_core_properties_tab_text', esc_html__('Properties', 'nft-marketplace-core-lite')).'</p>';
        }
        echo '</section>';
    }


    //SINGLE NFT : LEVELS TAB
    function single_nft_tab_levels() {
        $nft_marketplace_core_nft_listing_level_group = get_post_meta(get_the_ID(), 'nft_marketplace_core_nft_listing_level_group', true);
        echo '<section class="nft-listing-levels" id="tab-levels">';
        if($nft_marketplace_core_nft_listing_level_group) {
            echo '<ol>';
            foreach (array_reverse($nft_marketplace_core_nft_listing_level_group) as $nft_listing) {
                echo '<li>';
                if(!empty($nft_listing['nft_marketplace_core_nft_listing_level_name'])){
                    echo '<span>'.esc_html( $nft_listing['nft_marketplace_core_nft_listing_level_name'] ).'</span>';
                }
                echo '<span>';
                if($nft_listing['nft_marketplace_core_nft_listing_level_rate'] == 0 || !empty($nft_listing['nft_marketplace_core_nft_listing_level_rate'])){
                    echo esc_html( $nft_listing['nft_marketplace_core_nft_listing_level_rate'] );
                }
                echo esc_html__(' out of ','nft-marketplace-core-lite');
                if($nft_listing['nft_marketplace_core_nft_listing_level_out_of'] == 0 || !empty($nft_listing['nft_marketplace_core_nft_listing_level_out_of'])){
                    echo esc_html( $nft_listing['nft_marketplace_core_nft_listing_level_out_of'] );
                }
                echo '</span>';
                echo '</li>';
            }
            echo '</ol>';
        } else {
            echo '<p>'.apply_filters('nft_marketplace_core_tab_no_text', esc_html__('This NFT Listing does not have', 'nft-marketplace-core-lite')).' '.apply_filters('nft_marketplace_core_levels_tab_text', esc_html__('Levels', 'nft-marketplace-core-lite')).'</p>';
        }
        echo '</section>';
    }


    //SINGLE NFT : AUTHOR TAB
    function single_nft_tab_auth() {
        $author_id = get_post_field( 'post_author', get_the_ID() );
        $author    = get_the_author_meta( 'display_name', $author_id );
        $description    = get_the_author_meta( 'description', $author_id );
        $link = get_author_posts_url($author_id);
        echo '<h4>'.esc_html__('About','nft-marketplace-core-lite').'<a href="'.esc_url($link).'"> '.esc_attr($author).'</a></h4>';
        echo get_avatar( $author_id, 96);
        echo '<p>'.esc_html($description).'</p>';
    }


    //SINGLE NFT : AUTHOR TAB
    function single_nft_tabs_wrapper() {
        $author_id = get_post_field( 'post_author', get_the_ID() );
        $author    = get_the_author_meta( 'display_name', $author_id );
        $nft_marketplace_core_nft_listing_description_meta = get_post_meta(get_the_ID(), 'nft_marketplace_core_nft_listing_description_meta', true);
        echo '<div class="nft-listing-tabs">';
        echo '<ul class="nft-tab-list">';
        if (!empty($nft_marketplace_core_nft_listing_description_meta)) {
            echo '<li class="description_tab"><a href="#tab-description">'.apply_filters('nft_marketplace_core_description_tab_text', esc_html__('Description', 'nft-marketplace-core-lite')).'</a></li>';
        }
        echo '<li class="stats_tab"><a href="#tab-stats">'.apply_filters('nft_marketplace_core_stats_tab_text', esc_html__('Stats', 'nft-marketplace-core-lite')).'</a></li>';
        echo '<li class="properties_tab"><a href="#tab-properties">'.apply_filters('nft_marketplace_core_properties_tab_text', esc_html__('Properties', 'nft-marketplace-core-lite')).'</a></li>';
        echo '<li class="levels_tab"><a href="#tab-levels">'.apply_filters('nft_marketplace_core_levels_tab_text', esc_html__('Levels', 'nft-marketplace-core-lite')).'</a></li>';
        echo '<li class="author_tab"><a href="#tab-author">'.apply_filters('nft_marketplace_core_author_tab_text', esc_html__('About Author', 'nft-marketplace-core-lite')).'</a></li>';
        echo '</ul>';
        if (!empty($nft_marketplace_core_nft_listing_description_meta)) {
            echo '<section class="nft-listing-crypto-description" id="tab-description">';
            echo '<p>'.esc_html__('Created by ','nft-marketplace-core-lite').' <strong>'.esc_attr($author).'</strong></p>';
            echo '<p>'.esc_html($nft_marketplace_core_nft_listing_description_meta).'</p>';
            echo '</section>';
        }
        do_action('nft_marketplace_core_single_nft_before_author_tab');
        echo '<section class="nft-listing-author" id="tab-author">';
        do_action('nft_marketplace_core_single_nft_after_levels_tab');
        echo '</section>';
        echo '</div>';
    }


    //SINGLE NFT : RELATED NFTS
    function single_nft_related() {

        $args=array(
            'post__not_in'          => array(get_the_ID()),
            'posts_per_page'        => 4,
            'ignore_sticky_posts'   => 1,
            'post_status' => 'publish',
            'post_type'             => 'nft-listing'
        );
        $posts = get_posts( $args );

        if($posts) {
            echo '<section class="related nft-listing">';
            echo '<h3>' . apply_filters('nft_marketplace_core_related_nfts', esc_html__('Related NFTs', 'nft-marketplace-core-lite')) . '</h3>';
            echo '<div class="row">';
            foreach ($posts as $post) {
                echo '<div class="col-md-3 post">';
                $this->single_nft_block($post->ID);
                echo '</div>';
            }
            echo '</div>';
            echo '</section>';
        }
    }


    //SINGLE NFT : COLLECTION BLOCK
    function single_nft_collection_block() {
        echo '<div class="nft-listing-collection">';
        $nft_category = get_the_term_list( get_the_ID(), 'nft-listing-category', '', ' / ' );
        if ($nft_category) {
            $term = get_term_by( 'slug', $nft_category, 'nft-listing-category');
            echo '<p>'.apply_filters('nft_marketplace_core_collection_title', esc_html__('Collection', 'nft-marketplace-core-lite')).'</p>';
            $img_id = '';
            if(!empty($img_id)) {
                $img_id = get_term_meta( $term->term_id, 'category-image-id', true );
                $thumbnail_src = wp_get_attachment_image_src( $img_id, 'thumbnail' );
                if($thumbnail_src) {
                    echo '<img class="nft-listing-collection-icon" alt="'.esc_attr("cat-image").'" src="'.esc_url($thumbnail_src[0]).'">';
                }
            }
            do_action('nft_marketplace_core_single_nft_before_collection_text');
        }
        echo '</div>';
    }



    // SINGLE NFT : DISPLAY VIEW COUNT
    function display_views_count() {
        $post_id = get_the_ID();
        $views_count = get_post_meta($post_id, "nft_marketplace_core_post_views_count", true);
        echo '<div class="nft-listing-views-counter"><i class="fa fa-eye"></i><span>'.esc_html($views_count).' '.esc_html__('views','nft-marketplace-core-lite').'</span></div>';
    }


    // SINGLE NFT : DISPLAY OWNER
    function display_owner() {
        $nft_marketplace_core_nft_listing_owner = get_post_meta(get_the_ID(), 'nft_marketplace_core_nft_listing_owner', true);
        if($nft_marketplace_core_nft_listing_owner) {
            echo '<div class="nft-listing-owner">'.esc_html__('Owned by ','nft-marketplace-core-lite').' '.esc_attr($nft_marketplace_core_nft_listing_owner).'</div>';
        }
    }
}
