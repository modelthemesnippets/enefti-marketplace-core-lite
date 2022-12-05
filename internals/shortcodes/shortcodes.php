<?php

// HELPERS:
require_once __DIR__ . "/helpers.swiper.nft.php";

if (class_exists('Elementor\Core\Admin\Admin')) {
    require_once __DIR__ . "/elementor/functions-core-elementor.php";
}

function nft_marketplace_core_shortcode_image_sizes_array()
{
    $items_array = array();

    $all_image_sizes = get_intermediate_image_sizes();

    if ($all_image_sizes) {
        foreach ($all_image_sizes as $image_size) {
            $items_array[$image_size] = $image_size;
        }
    }
}


// SHORTCODES:
require_once __DIR__ . "/listings-category/listings-category.php";
require_once __DIR__ . "/nft-category-group/nft-category-group.php";
require_once __DIR__ . "/nft-category-list/nft-category-list.php";
