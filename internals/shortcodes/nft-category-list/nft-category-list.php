<?php
if (!defined('ABSPATH')) {
    die('-1');
}

function modeltheme_addons_for_wpbakery_nft_collectors_list($params, $content)
{
    extract(shortcode_atts(
        array(
            'page_builder' => '',
            'number' => '',
            'number_of_columns' => '',
            'title_color' => '',
            'subtitle_color' => '',
            'color_number' => '',

        ), $params));

    wp_enqueue_style('mt-author-list', plugins_url('../../shortcodes/assets/css/collectors-list.css', __FILE__));

    ob_start();
    $count_number = 01; ?>

    <div class="mt-categories-wrapper row">
        <?php
        $args = array(
            'role' => 'author',
            'orderby' => 'user_nicename',
            'order' => 'ASC',
            'number' => $number,
            'post_status' => array('publish'),

        );
        $users = get_users($args);
        ?>
        <?php foreach ($users as $user) {
            $avatar = get_avatar_url($user->ID); ?>
            <div class="mt-single-category <?php echo $number_of_columns; ?>">
                <div class="mt-single-category-wrap">
                    <a href="<?php echo get_author_posts_url($user->ID); ?>">
                        <?php if ($avatar) { ?>
                            <img class="cat-image" alt="cat-image" src="<?php echo esc_url($avatar); ?>">
                        <?php } else { ?>
                            <img class="cat-image" alt="cat-image" src="http://via.placeholder.com/70x70">
                        <?php } ?>
                    </a>


                    <div class="mt-single-category-info">
                        <a href="<?php echo get_author_posts_url($user->ID); ?>"><?php echo esc_html__($user->display_name); ?></a>

                        <span style="color:<?php echo esc_attr($subtitle_color); ?>"><?php //echo count_user_posts($author[1]);?><?php // echo esc_html__('items','nft-marketplace-core-lite'); ?></span>
                    </div>
                    <span class="mt-count-number"><?php echo $count_number; ?></span>
                </div>
            </div>
            <?php $count_number++;
        } ?>
    </div>
    <?php return ob_get_clean();
}

add_shortcode('mt-addons-nft-collectors-list', 'modeltheme_addons_for_wpbakery_nft_collectors_list');


add_action('init', 'mt_addons_nft_collectors_list');
function mt_addons_nft_collectors_list()
{
    if (function_exists('vc_map')) {
        $users = get_users(['role__in' => ['author']]);
        $users_array = array();
        foreach ($users as $user) {
            if ($user) {
                $users_array[$user->display_name] = $user->display_name;
            }
        }

        vc_map(
            array(
                "name" => esc_attr__("NFT: Owner Profile List", 'nft-marketplace-core-lite'),
                "base" => "mt-addons-nft-collectors-list",
                "category" => esc_attr__('Enefti Core', 'nft-marketplace-core-lite'),
                "icon" => plugins_url('images/product-list.svg', __FILE__),
                "params" => array(
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
                        "heading" => esc_attr__("Columns", 'nft-marketplace-core-lite'),
                        "param_name" => "number_of_columns",
                        "default" => 'col-md-4',
                        "value" => array(
                            'Select' => '',
                            'One' => 'col-md-12',
                            'Two' => 'col-md-6',
                            'Three' => 'col-md-4',
                            'Four' => 'col-md-3'
                        ),
                    ),
                    array(
                        "group" => "Style",
                        "type" => "colorpicker",
                        "heading" => esc_attr__("Title Color", 'nft-marketplace-core-lite'),
                        "param_name" => "title_color",
                        "std" => '',
                        "holder" => "div",
                        "class" => ""
                    ),
                    array(
                        "group" => "Style",
                        "type" => "colorpicker",
                        "heading" => esc_attr__("Subtitle Color", 'nft-marketplace-core-lite'),
                        "param_name" => "subtitle_color",
                        "std" => '',
                        "holder" => "div",
                        "class" => ""
                    ),
                    array(
                        "group" => "Style",
                        "type" => "colorpicker",
                        "heading" => esc_attr__("Number Color", 'nft-marketplace-core-lite'),
                        "param_name" => "color_number",
                        "std" => '',
                        "holder" => "div",
                        "class" => ""
                    ),
                )
            ));
    }
}
