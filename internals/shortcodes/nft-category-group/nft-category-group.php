<?php
if (!defined('ABSPATH')) {
    die('-1');
}

function nft_marketplace_core_shortcode_for_wpbakery_nft_collectors_group($params, $content)
{
    extract(shortcode_atts(
        array(
            'page_builder' => '',
            'number' => '4',
            'featured_image_size' => '',
            'image_shape' => '',
            'list_image' => '',
            'collectors_groups' => '',
            'member_name' => '',
            'member_position' => '',
            'member_description' => '',
            'title_color' => '',
            'position_color' => '',
            'description_color' => '',
            'description_size' => '',
            'quote_testimonial' => '',
            'quote_color' => '',
            'quote_size' => '',
            'quote_image' => '',
            // carousel
            'autoplay' => '',
            'delay' => '',
            'items_desktop' => '4',
            'items_mobile' => '',
            'items_tablet' => '',
            'space_items' => '',
            'touch_move' => '',
            'effect' => '',
            'grab_cursor' => '',
            'infinite_loop' => '',
            'carousel' => '',
            'columns' => '',
            'layout' => 'carousel',
            'centered_slides' => '',
            'select_navigation' => '',
            'navigation_position' => '',
            'nav_style' => '',
            'navigation_color' => '',
            'navigation_bg_color' => '',
            'navigation_bg_color_hover' => '',
            'navigation_color_hover' => '',
            'pagination_color' => '',
            'navigation' => 'true',
            'pagination' => '',
            // end carousel
            'collector_style_var' => 'collector_style_1',
            'author_status' => ''


        ), $params));

    $title_color_style = '';
    if ($title_color) {
        $title_color_style = 'color:' . $title_color . ';';
    }
    $position_color_style = '';
    if ($position_color) {
        $position_color_style = 'color:' . $position_color . ';';
    }
    $description_color_style = '';
    if ($description_color) {
        $description_color_style = 'color:' . $description_color . ';';
    }
    if ($collector_style_var == 'collector_style_2') {
        $style_var_value = 'mt-addons-collector-style-2';
    } else {
        $style_var_value = '';
    }

    wp_enqueue_style('collectors-group-css', plugins_url('../../shortcodes/assets/css/collectors-group.css', __FILE__));

    if ($page_builder == 'elementor') {
        $collectors_groups = modeltheme_addons_core_for_wpbakery_param_group_parse_atts($collectors_groups);
    } else {
        $collectors_groups = vc_param_group_parse_atts($params['collectors_groups']);
    }

    $id = 'mt-addons-carousel-' . uniqid();
    $carousel_item_class = $columns;
    $carousel_holder_class = '';
    $swiper_wrapped_start = '';
    $swiper_wrapped_end = '';
    $swiper_container_start = '';
    $swiper_container_end = '';
    $html_post_swiper_wrapper = '';

    if ($layout == "carousel" or $layout == "") {
        $carousel_holder_class = 'mt-addons-swipper swiper';
        $carousel_item_class = 'swiper-slide';

        $swiper_wrapped_start = '<div class="swiper-wrapper">';
        $swiper_wrapped_end = '</div>';

        $swiper_container_start = '<div class="mt-addons-swiper-container">';
        $swiper_container_end = '</div>';

        if ($page_builder == 'elementor' && $navigation == "yes") {
            // next/prev
            $html_post_swiper_wrapper .= '
        <i class="far fa-arrow-left swiper-button-prev ' . $nav_style . ' ' . $navigation_position . '" style="color:' . $navigation_color . '; background:' . $navigation_bg_color . ';"></i>
        <i class="far fa-arrow-right swiper-button-next ' . $nav_style . ' ' . $navigation_position . '" style="color:' . $navigation_color . '; background:' . $navigation_bg_color . ';"></i>';
        } else {
            if ($navigation == "true") {
                $html_post_swiper_wrapper .= '
          <i class="far fa-arrow-left swiper-button-prev ' . $nav_style . ' ' . $navigation_position . '" style="color:' . $navigation_color . '; background:' . $navigation_bg_color . ';"></i>
          <i class="far fa-arrow-right swiper-button-next ' . $nav_style . ' ' . $navigation_position . '" style="color:' . $navigation_color . '; background:' . $navigation_bg_color . ';"></i>';
            }
        }
        if ($page_builder == 'elementor' && $pagination == "yes") {
            // next/prev
            $html_post_swiper_wrapper .= '<div class="swiper-pagination"></div>';
        } else {
            if ($pagination == "true") {
                // next/prev
                $html_post_swiper_wrapper .= '<div class="swiper-pagination"></div>';
            }
        }
        // SWIPER SLIDER
        wp_enqueue_style('swiper-bundle', plugins_url('../../shortcodes/assets/css/swiperjs/swiper-bundle.min.css', __FILE__));
        wp_enqueue_script('swipper', plugins_url('../../shortcodes/assets/js/swiper.js', __FILE__));
        wp_enqueue_script('swipper-bundle-min', plugins_url('../../shortcodes/assets/js/swiper-bundle.min.js', __FILE__));

    }

    ob_start(); ?>
    <?php //swiper container start ?>
    <?php echo wp_kses_post($swiper_container_start); ?>
    <div class="mt-swipper-carusel-position" style="position:relative;">

        <div id="<?php echo esc_attr($id); ?>"
            <?php nft_marketplace_core_shortcode_swiper_attributes($id, $autoplay, $delay, $items_desktop, $items_mobile, $items_tablet, $space_items, $touch_move, $effect, $grab_cursor, $infinite_loop, $centered_slides); ?>
             class="mt-addons-collectors-carusel <?php echo esc_attr($carousel_holder_class); ?> <?php echo esc_attr($style_var_value); ?>">

            <?php //swiper wrapped start ?>
            <?php echo wp_kses_post($swiper_wrapped_start); ?>

            <?php if ($collectors_groups) { ?>
                <?php foreach ($collectors_groups as $collector) {
                    if (!array_key_exists('category', $collector)) {
                        $category = 'Uncategorized';
                    } else {
                        $category = $collector['category'];
                    }
                    $cat = get_term_by('slug', $category, 'nft-listing-category');
                    if ($cat) {
                        $cat_link = get_term_link($category, 'nft-listing-category');
                        $cat_img_id = get_term_meta($cat->term_id, 'category-image-id', true);
                        $category_src = wp_get_attachment_image_src($cat_img_id, 'enefti_post_widget_pic70x70');
                        $args_prods = array(
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
                        $prods = get_posts($args_prods);
                        $image_size = 'enefti_collections149x100';
                        if ($featured_image_size) {
                            $image_size = $featured_image_size;
                        }
                        ?>

                        <div class="<?php echo esc_attr($carousel_item_class); ?>">
                            <div class="mt-addons-images-wrapper <?php echo esc_attr($number); ?>">
                                <?php foreach ($prods as $prod) {
                                    $thumbnail_src = wp_get_attachment_image_src(get_post_thumbnail_id($prod->ID), $image_size);
                                    if ($thumbnail_src) {
                                        $post_img = '<img class="portfolio_post_image ' . $number . '" src="' . esc_url($thumbnail_src[0]) . '" alt="' . $prod->post_title . '" />';
                                    } else {
                                        $post_img = '<img class="portfolio_post_image ' . $number . '" src="http://via.placeholder.com/144x100" alt="' . $prod->post_title . '" />';
                                    } ?>
                                    <a class="modeltheme_media_image" title="<?php echo esc_attr($prod->post_title); ?>"
                                       href="<?php echo esc_url(get_permalink($prod->ID)); ?>"><?php echo $post_img; ?></a>
                                <?php } ?>
                            </div>
                            <div class="mt-addons-collector-wrapper">
                                <?php if ($category_src) { ?>
                                    <img class="cat-image" alt="cat-image" src="<?php echo $category_src[0]; ?>">
                                <?php } else { ?>
                                    <img class="cat-image" alt="cat-image" src="http://via.placeholder.com/70x70">
                                <?php } ?>
                            </div>
                            <div class="mt-addons-info-wrapper">
                                <a class="#categoryid_<?php echo esc_attr($cat->term_id); ?>"
                                   href="<?php echo esc_url($cat_link); ?>"><span
                                            class="mt-addons-title"><?php echo esc_attr($cat->name); ?></span></a>
                                <span class="mt-addons-subtitle"><strong><?php echo esc_attr($cat->count); ?> items</strong></span><br>
                                <?php if ($author_status == "true") { ?>
                                    <div class="mt-addons-collector-author">
                                        <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'), get_the_author_meta('user_nicename'))); ?>"><?php echo esc_html(get_the_author()); ?></a>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                <?php } ?>
            <?php } ?>
            <?php //swiper wrapped end ?>
            <?php echo wp_kses_post($swiper_wrapped_end); ?>
            <?php //pagination/navigation ?>
            <?php echo wp_kses_post($html_post_swiper_wrapper); ?>
        </div>
        <?php //swiper container end ?>
        <?php echo wp_kses_post($swiper_container_end); ?>


        <style type="text/css" media="screen">
            .swiper-button-prev:hover,
            .swiper-button-next:hover {
                background: <?php echo esc_attr($navigation_bg_color_hover);?> !important;
                color: <?php echo esc_attr($navigation_color_hover); ?> !important;
                opacity: 1;
            }

            .swiper-pagination-bullet {
                background: <?php echo esc_attr($pagination_color);?> !important;
            }
        </style>
    </div>
    <?php
    return ob_get_clean();
}

add_shortcode('mt-addons-nft-collectors-group', 'nft_marketplace_core_shortcode_for_wpbakery_nft_collectors_group');
if (!function_exists('modeltheme_addons_core_for_wpbakery_param_group_parse_atts')) {
    function modeltheme_addons_core_for_wpbakery_param_group_parse_atts($string = '')
    {

        $collectors_groups = array();

        if ($string) {
            $collectors_groups_exploded = explode(',', $string);
            if ($collectors_groups_exploded) {
                foreach ($collectors_groups_exploded as $collectors_groups_item) {
                    $item = array('category' => $collectors_groups_item);
                    array_push($collectors_groups, $item);
                }
            }
        }
        return $collectors_groups;
    }
}
if (!function_exists('nft_marketplace_core_shortcode_collector_group')) {
    add_action('init', 'nft_marketplace_core_shortcode_collector_group');
    function nft_marketplace_core_shortcode_collector_group()
    {

        if (function_exists('vc_map')) {

            $product_category_tax = get_terms('nft-listing-category');
            $product_category = array();
            foreach ($product_category_tax as $term) {
                $product_category[$term->name . ' (' . $term->count . ')'] = $term->slug;

            }
            $params = array(
                array(
                    "type" => "dropdown",
                    "heading" => esc_attr__("Number of items", NFT_MARKETPLACE_CORE_TEXTDOMAIN),
                    "param_name" => "number",
                    "value" => array(
                        esc_attr__('Select', NFT_MARKETPLACE_CORE_TEXTDOMAIN) => '',
                        esc_attr__('1 Item', NFT_MARKETPLACE_CORE_TEXTDOMAIN) => 'one-item',
                        esc_attr__('2 Items', NFT_MARKETPLACE_CORE_TEXTDOMAIN) => '2',
                        esc_attr__('4 Items', NFT_MARKETPLACE_CORE_TEXTDOMAIN) => '4',
                    ),
                    "holder" => "div",
                    "class" => ""
                ),
                array(
                    "type" => "dropdown",
                    "holder" => "div",
                    "class" => "",
                    "heading" => esc_attr__("Featured Image size", NFT_MARKETPLACE_CORE_TEXTDOMAIN),
                    "param_name" => "featured_image_size",
                    "std" => 'full',
                    "value" => nft_marketplace_core_shortcode_image_sizes_array()
                ),
                array(
                    "type" => "dropdown",
                    "heading" => esc_attr__("Style Collector", NFT_MARKETPLACE_CORE_TEXTDOMAIN),
                    "param_name" => "collector_style_var",
                    "value" => array(
                        esc_attr__("Select", NFT_MARKETPLACE_CORE_TEXTDOMAIN) => '',
                        esc_attr__("Collector Image Floating", NFT_MARKETPLACE_CORE_TEXTDOMAIN) => 'collector_style_1',
                        esc_attr__("Collector Image In Wrapper", NFT_MARKETPLACE_CORE_TEXTDOMAIN) => 'collector_style_2'
                    ),
                    "holder" => "div",
                    "class" => ""
                ),
                array(
                    "type" => "checkbox",
                    "class" => "",
                    "heading" => esc_attr__("Author", NFT_MARKETPLACE_CORE_TEXTDOMAIN),
                    "param_name" => "author_status",
                    "dependency" => array(
                        'element' => 'collector_style_var',
                        'value' => "collector_style_2",
                    ),
                ),
                array(
                    'type' => 'param_group',
                    'value' => '',
                    'param_name' => 'collectors_groups',
                    // Note params is mapped inside param-group:
                    'params' => array(
                        array(
                            "type" => "dropdown",
                            "holder" => "div",
                            "class" => "",
                            "heading" => esc_attr__("Category", NFT_MARKETPLACE_CORE_TEXTDOMAIN),
                            "param_name" => "category",
                            "description" => esc_attr__("Select Category", NFT_MARKETPLACE_CORE_TEXTDOMAIN),
                            "std" => 'Select',
                            "value" => $product_category
                        )
                    ),
                ),
            );

            $swiper_fields_array = nft_marketplace_core_shortcode_swiper_vc_fields();
            if ($swiper_fields_array) {
                foreach ($swiper_fields_array as $swiper_fields) {
                    $params[] = $swiper_fields;
                }
            }
            vc_map(
                array(
                    "name" => esc_attr__("NFT: Collections Group", NFT_MARKETPLACE_CORE_TEXTDOMAIN),
                    "base" => "mt-addons-nft-collectors-group",
                    "category" => esc_attr__('Enefti Core', NFT_MARKETPLACE_CORE_TEXTDOMAIN),
                    "icon" => plugins_url('images/product-grid.svg', __FILE__),
                    "params" => $params,
                ));
        }
    }
}
