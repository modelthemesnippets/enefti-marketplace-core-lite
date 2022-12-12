<?php
if (!defined('ABSPATH')) {
    die('-1');
}

function nft_marketplace_core_shortcode_for_wpbakery_listings_category($params, $content)
{
    extract(shortcode_atts(
        array(
            'page_builder' => '',
            'number' => '',
            'category' => '',
            'featured_image_size' => '',
            'columns' => '',
            'read_more_btn' => '',
            'text_color' => '',
            'bg_text_color' => '',
            'bg_btn_color' => '',
            'reserve_text_color' => '',
            'price_color' => '',

        ), $params));

    wp_enqueue_style('mt-addons-listings-category', plugins_url('../../shortcodes/assets/css/listings-category.css', __FILE__));

    ob_start(); ?>

    <div class="mt-addons-listings-big-parent row">

        <?php
        $cat_link = get_term_link($category, 'nft-listing-category');
        $args_posts = array(
            'posts_per_page' => $number,
            'order' => 'ASC',
            'post_type' => 'nft-listing',
            'tax_query' => array(
                array(
                    'taxonomy' => 'nft-listing-category',
                    'field' => 'slug',
                    'terms' => $category
                )),
            'post_status' => 'publish'
        );
        $posts = new WP_Query($args_posts);

        $image_size = 'enefti_collections149x100';
        if ($featured_image_size) {
            $image_size = $featured_image_size;
        }
        if ($read_more_btn) {
            $button_text_value = $read_more_btn;
        } else {
            $button_text_value = esc_html__('Buy Now', 'nft-marketplace-core-lite');
        }
        ?>

        <?php if ($posts->have_posts()) : ?>
            <?php /* Start the Loop */ ?>
            <?php
            while ($posts->have_posts()) : $posts->the_post();
                global $post;
                $nft_marketplace_core_nft_listing_price = get_post_meta(get_the_ID(), 'nft_marketplace_core_nft_listing_currency_price', true);
                $nft_marketplace_core_nft_listing_symbol = get_post_meta(get_the_ID(), 'nft_marketplace_core_nft_listing_currency_symbol', true);
                $image_size = 'full';
                if ($featured_image_size) {
                    $image_size = $featured_image_size;
                }

                $thumbnail_src = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), $image_size);
                ?>
                <div class="mt-addons-nft-listing-columns <?php echo esc_attr($columns); ?>">
                    <div class="mt-addons-listings-wrapper">
                        <div class="mt-addons-listing-image">
                            <div class="mt-addons-listing-overlay-container">
                                <div class="mt-addons-listing-hover-container">
                                    <div class="component add-to-cart">
                                        <a class="button"
                                           style="color:<?php echo esc_attr($bg_text_color); ?>;background:<?php echo $bg_btn_color; ?>;"
                                           href="<?php echo esc_url(get_the_permalink()) ?>"><?php echo esc_html($button_text_value); ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <?php if ($thumbnail_src) { ?>
                                <a class="mt-addons-listing-media"
                                   href="<?php echo esc_url(get_the_permalink()) ?>"><img alt="listing-media"
                                                                                          src="<?php echo esc_attr($thumbnail_src[0]); ?>"></a>
                            <?php } ?>
                        </div>
                        <div class="mt-addons-listing-title-metas">
                            <div class="mt-addons-title-wrapper">
                                <h3 class="mt-addons-listing-name">
                                    <a style="color:<?php echo esc_attr($text_color); ?>"
                                       href="<?php echo esc_url(get_the_permalink()) ?>"><?php echo esc_attr(get_the_title()); ?></a>
                                </h3>
                            </div>
                        </div>
                        <div class="mt-addons-details-container">
                            <span class="mt-addons-reserve-text"
                                  style="color:<?php echo esc_attr($reserve_text_color); ?>;"> <?php echo apply_filters('nft_marketplace_core_reserve_text', esc_html__('Reserve Price', 'nft-marketplace-core-lite')); ?></span>
                            <?php if (!empty($nft_marketplace_core_nft_listing_price)) { ?>
                                <span class="mt-addons-listing-price"
                                      style="color:<?php echo esc_attr($price_color); ?>"><img
                                            src="<?php echo nft_marketplace_core_get_url(); ?>includes/images/token.svg"
                                            alt="Ether" width="10"
                                            height="10"> <?php echo esc_attr($nft_marketplace_core_nft_listing_price . ' ' . $nft_marketplace_core_nft_listing_symbol); ?></span>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
            <?php wp_reset_postdata(); ?>
        <?php else : ?>
            <?php //no listings found ?>
        <?php endif; ?>
    </div>
    <?php
    return ob_get_clean();
}

add_shortcode('mt-addons-listings-category', 'nft_marketplace_core_shortcode_for_wpbakery_listings_category');

add_action('init', 'nft_marketplace_core_shortcode_listings_ategory_vc_map');
function nft_marketplace_core_shortcode_listings_ategory_vc_map()
{
    //VC Map
    if (function_exists('vc_map')) {
        $product_category_tax = get_terms('nft-listing-category');
        $product_category = array();
        foreach ($product_category_tax as $term) {
            $product_category[$term->name . ' (' . $term->count . ')'] = $term->slug;

        }
        $params = array(
            array(
                "type" => "dropdown",
                "holder" => "div",
                "class" => "",
                "heading" => esc_attr__("Featured Image size", 'nft-marketplace-core-lite'),
                "param_name" => "featured_image_size",
                "std" => 'full',
                "value" => nft_marketplace_core_shortcode_image_sizes_array()
            ),
            array(
                "type" => "vc_number",
                "holder" => "div",
                "class" => "",
                "heading" => esc_attr__("Number", 'nft-marketplace-core-lite'),
                "param_name" => "number"
            ),
            array(
                "type" => "dropdown",
                "holder" => "div",
                "class" => "",
                "heading" => esc_attr__("Category", 'nft-marketplace-core-lite'),
                "param_name" => "category",
                "std" => 'Default value',
                "value" => $product_category
            ),
            array(
                "type" => "dropdown",
                "holder" => "div",
                "class" => "",
                "heading" => esc_attr__('Columns', 'nft-marketplace-core-lite'),
                "param_name" => "columns",
                "value" => array(
                    'Select Option' => '',
                    '2 Columns' => 'col-md-6',
                    '3 Columns' => 'col-md-4',
                    '4 Columns' => 'col-md-3',
                ),
            ),
            array(
                "type" => "textfield",
                "holder" => "div",
                "class" => "",
                "heading" => esc_attr__("Buy Now Text", 'nft-marketplace-core-lite'),
                "param_name" => "read_more_btn"
            ),
            array(
                "group" => "Styling",
                "type" => "colorpicker",
                "holder" => "div",
                "class" => "",
                "heading" => esc_attr__("Title color", 'nft-marketplace-core-lite'),
                "param_name" => "text_color"
            ),
            array(
                "group" => "Styling",
                "type" => "colorpicker",
                "holder" => "div",
                "class" => "",
                "heading" => esc_attr__("Background Button Color", 'nft-marketplace-core-lite'),
                "param_name" => "bg_btn_color"
            ),
            array(
                "group" => "Styling",
                "type" => "colorpicker",
                "holder" => "div",
                "class" => "",
                "heading" => esc_attr__("Button Text Color", 'nft-marketplace-core-lite'),
                "param_name" => "bg_text_color"
            ),
            array(
                "group" => "Styling",
                "type" => "colorpicker",
                "holder" => "div",
                "class" => "",
                "heading" => esc_attr__("Reserve Text Color", 'nft-marketplace-core-lite'),
                "param_name" => "reserve_text_color"
            ),
            array(
                "group" => "Styling",
                "type" => "colorpicker",
                "holder" => "div",
                "class" => "",
                "heading" => esc_attr__("Price Text Color", 'nft-marketplace-core-lite'),
                "param_name" => "price_color"
            ),
        );
        vc_map(
            array(
                "name" => esc_attr__("NFT: Recent Category", 'nft-marketplace-core-lite'),
                "base" => "mt-addons-listings-category",
                "category" => esc_attr__('Enefti Core', 'nft-marketplace-core-lite'),
                "icon" => plugins_url('images/blog.svg', __FILE__),
                "params" => $params,
            ));
    }
}
