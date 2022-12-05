<?php

class My_Elementor_Core_Widgets
{

    protected static $instance = null;

    public static function get_instance()
    {
        if (!isset(static::$instance)) {
            static::$instance = new static;
        }

        return static::$instance;
    }

    protected function __construct()
    {
        require_once('widgets/nft-category-group.php');
        require_once('widgets/nft-category-list.php');
        require_once('widgets/listings-category.php');


        add_action('elementor/widgets/widgets_registered', [$this, 'register_widgets']);
    }

    public function register_widgets()
    {
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \Elementor\nft_collectors_group());
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \Elementor\nft_author_list());
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \Elementor\nft_listings_category_list());
    }

}

add_action('init', 'my_elementor_core_init');
function my_elementor_core_init()
{
    My_Elementor_Core_Widgets::get_instance();
}

function add_elementor_core_widget_categories($elements_manager)
{

    $elements_manager->add_category(
        'addons-widgets',
        [
            'title' => __('NFT', 'modeltheme'),
            'icon' => 'fa fa-plug',
        ]
    );

}

add_action('elementor/elements/categories_registered', 'add_elementor_core_widget_categories');
