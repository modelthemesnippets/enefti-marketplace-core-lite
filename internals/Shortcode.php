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

use DecodeLabs\Tagged as Html;
use NFT_Marketplace_Core\Engine\Base;

/**
 * Shortcodes of this plugin
 */
class Shortcode extends Base {

	/**
	 * Initialize the class.
	 *
	 * @return void
     */
	public function initialize() {
		parent::initialize();

		\add_shortcode( 'foobar', array( $this, 'foobar_func' ) );

        //SHORTCODES
        add_shortcode( 'nft_marketplace_core_edit_author_form_shortcode', [$this, 'nft_marketplace_core_edit_form_shortcode'] );
        add_shortcode( 'nft_marketplace_core_update_author_form_shortcode', [$this, 'nft_marketplace_core_update_form_shortcode'] );
        add_shortcode( 'nft_marketplace_core_submit_author_form_shortcode', [$this, 'nft_marketplace_core_submit_form_shortcode'] );
        add_shortcode( 'nft_archive_search_shortcode', [$this, 'nft_marketplace_core_archive_search_shortcode']);
        add_shortcode( 'nft_archive_category_shortcode', [$this, 'nft_marketplace_core_archive_sidebar_category_shortcode']);
    }

    /* SHORTCODE : Update form */
    function nft_marketplace_core_update_form_shortcode($params, $content): string
    {
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        $current_user = wp_get_current_user();
        $html = '';
        if (isset($_POST['add-listing'])) {
            $my_listing = array(
                'ID' => $current_user->ID
            );
            $pid = wp_update_user($my_listing);
            if (isset($_POST['nft_marketplace_core_user_facebook'])) {
                update_user_meta($pid, 'nft_marketplace_core_user_facebook', $_POST['nft_marketplace_core_user_facebook']);
            }
            if (isset($_POST['nft_marketplace_core_user_instagram'])) {
                update_user_meta($pid, 'nft_marketplace_core_user_instagram', $_POST['nft_marketplace_core_user_instagram']);
            }
            if (isset($_POST['nft_marketplace_core_user_youtube'])) {
                update_user_meta($pid, 'nft_marketplace_core_user_youtube', $_POST['nft_marketplace_core_user_youtube']);
            }
            if (isset($_POST['nft_marketplace_core_banner_img'])) {
                $nft_marketplace_core_banner_img_string = $_POST['nft_marketplace_core_banner_img'];
                $nft_marketplace_core_banner_img_array = explode(',', $nft_marketplace_core_banner_img_string);
                $count = 0;
                $gallery_media_links =[];
                foreach ($nft_marketplace_core_banner_img_array as $picture_id) {
                    if ($count == 0) {
                        $image_name = wp_get_attachment_url($picture_id);
                        $filetype = wp_check_filetype(basename($image_name), null);
                        $wp_upload_dir = wp_upload_dir();
                        $attachment = array(
                            'guid' => $wp_upload_dir['url'] . '/' . basename($image_name),
                            'post_mime_type' => $filetype['type'],
                            'post_title' => preg_replace('/\.[^.]+$/', '', basename($image_name)),
                            'post_content' => '',
                            'post_status' => 'inherit'
                        );
                        $attach_id = $picture_id;
                        update_option('option_image', $attach_id);
                        update_user_meta($pid, 'nft_marketplace_core_banner_img', $image_name);
                        $attach_data = wp_generate_attachment_metadata($attach_id, $wp_upload_dir['path'] . '/' . basename($image_name));
                        wp_update_attachment_metadata($attach_id, $attach_data);
                        set_post_thumbnail($pid, $attach_id);
                    }
                }
                update_user_meta($pid, 'dfiFeatured', $gallery_media_links);
            }
        }
        return $html;
    }


    /* SHORTCODE : Display edit form*/
    function nft_marketplace_core_edit_form_shortcode($params, $content): string
    {
        $author = get_user_by('slug', get_query_var('author_name'));

        if($author === false) {
            $author = wp_get_current_user();
        }

        $html = '';
        $html .= '<div class="form-group">
		            <label for="nft_marketplace_core_user_facebook">' . esc_html__('Facebook link', NFT_MARKETPLACE_CORE_TEXTDOMAIN) . '</label>';
        $nft_marketplace_core_user_facebook = get_user_meta($author->ID, 'nft_marketplace_core_user_facebook', true);
        $html .= '<input type="text" class="form-control" name="nft_marketplace_core_user_facebook" value="' . $nft_marketplace_core_user_facebook . '" placeholder="' . $nft_marketplace_core_user_facebook . '">
	    		</div>';
        $html .= '<div class="form-group">
		            <label for="nft_marketplace_core_user_youtube">' . esc_html__('Youtube link', NFT_MARKETPLACE_CORE_TEXTDOMAIN) . '</label>';
        $nft_marketplace_core_user_youtube = get_user_meta($author->ID, 'nft_marketplace_core_user_youtube', true);
        $html .= '<input type="text" class="form-control" name="nft_marketplace_core_user_youtube" value="' . $nft_marketplace_core_user_youtube . '" placeholder="' . $nft_marketplace_core_user_youtube . '">
	    		</div>';
        $html .= '<div class="form-group">
	        		<label for="nft_marketplace_core_user_instagram">' . esc_html__('Instagram link', NFT_MARKETPLACE_CORE_TEXTDOMAIN) . '</label>';
        $nft_marketplace_core_user_instagram = get_user_meta($author->ID, 'nft_marketplace_core_user_instagram', true);
        $html .= '<input type="text" class="form-control" name="nft_marketplace_core_user_instagram" value="' . $nft_marketplace_core_user_instagram . '" placeholder="' . $nft_marketplace_core_user_instagram . '">
	    		</div>';
        wp_reset_postdata();
        return $html;
    }


    /* SHORTCODE : Display edit form*/
    function nft_marketplace_core_submit_form_shortcode($params, $content)
    {
        $html = '';
        $html .= '<div class="form-group pull-left">
	                    <button type="submit" class="button-listing" name="add-listing" class="btn btn-success">' . esc_html__('Save Changes', NFT_MARKETPLACE_CORE_TEXTDOMAIN) . '</button>
	                </div>';
        wp_reset_postdata();
        return $html;
    }


    // SHORTCODE: SIDEBAR SEARCH
    function nft_marketplace_core_archive_search_shortcode($params, $content): string
    {
        $html  = '';
        $html .= '<div class="nft-marketplace-search-bar">';
        $html .= '<h3 class="nft-sidebar-title">';
        $html .=  apply_filters('nft_marketplace_core_search_text', esc_html__('Search', NFT_MARKETPLACE_CORE_TEXTDOMAIN));
        $html .= '</h3>';
        $html .= '<form role="search" method="get" id="nft_marketplace_core_searchform" autocomplete="off" class="clearfix" action="'.esc_url(get_site_url()).'">';
        $html .= '<input type="hidden" name="post_type" value="nft-listing">';
        $html .= '<input placeholder="'.apply_filters('nft_marketplace_core_search_text', esc_html__('Search', NFT_MARKETPLACE_CORE_TEXTDOMAIN)).'" type="text" name="s" id="nft_marketplace_core_keyword">';
        $html .= '<button type="submit" id="nft_marketplace_core_searchsubmit"> <i class="fa fa-search"></i></button>';
        $html .= '</form>';
        $html .= '<div id="mtkb_datafetch"></div>';
        $html .= '</div>';
        return $html;
    }


    // SHORTCODE: CATEGORY SIDEBAR
    function nft_marketplace_core_archive_sidebar_category_shortcode($params, $content): string
    {
        $html  = '';
        $html .= '<div class="nft-marketplace-sidebar-category">';
        $html .= '<h3 class="nft-sidebar-title">';
        $html .= apply_filters('nft_marketplace_core_category_text', esc_html__('Category', NFT_MARKETPLACE_CORE_TEXTDOMAIN));
        $html .= '</h3>';
        $html .= '<div class="nft-marketplace-categories">';
        $terms_c = get_terms( array(
            'taxonomy' => 'nft-listing-category'
        ) );
        if($terms_c){
            foreach ($terms_c as $term) {
                if ($term->parent == 0) {
                    $html .= '<li>';
                    $html .= '<a href="'.get_term_link( $term->slug, 'nft-listing-category' ).'">'.$term->name.'</a>';
                    $html .= '</li>';
                }
            }
        }
        $html .= '</div>';
        $html .= '</div>';
        return $html;
    }
}
