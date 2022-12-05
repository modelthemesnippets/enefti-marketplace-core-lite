<?php

namespace Elementor;

class nft_listings_category_list extends Widget_Base
{

    public function get_name()
    {
        return 'nft-category-list';
    }

    public function get_title()
    {
        return 'NFT: Category List';
    }

    public function get_icon()
    {
        return 'eicon-editor-list-ul';
    }

    public function get_categories()
    {
        return ['addons-widgets'];
    }

    protected function register_controls()
    {

        $this->start_controls_section(
            'section_title',
            [
                'label' => __('Title', 'modeltheme-addons-for-wpbakery'),
            ]
        );
        $this->add_control(
            'featured_image_size',
            [
                'label' => __('Featured Image size', 'modeltheme'),
                'label_block' => true,
                'type' => Controls_Manager::SELECT,
                'options' => nft_marketplace_core_shortcode_image_sizes_array(),
            ]
        );

        $this->add_control(
            'number',
            [
                'label' => esc_html__('Number', 'modeltheme-addons-for-wpbakery'),
                'type' => \Elementor\Controls_Manager::NUMBER,
            ]
        );
        $product_category_tax = get_terms('nft-listing-category');
        $product_category = array();
        foreach ($product_category_tax as $term) {
            $product_category[$term->slug] = $term->name . ' (' . $term->count . ')';

        }
        $this->add_control(
            'category',
            [
                'label' => __('Select Products Category', 'modeltheme-addons-for-wpbakery'),
                'label_block' => true,
                'type' => Controls_Manager::SELECT,
                'options' => $product_category,
            ]
        );
        $this->add_control(
            'columns',
            [
                'label' => __('Columns', 'modeltheme-addons-for-wpbakery'),
                'label_block' => true,
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => __('Select', 'modeltheme-addons-for-wpbakery'),
                    'col-md-6' => __('2 Columns', 'modeltheme-addons-for-wpbakery'),
                    'col-md-4' => __('3 Columns', 'modeltheme-addons-for-wpbakery'),
                    'col-md-3' => __('4 Columns', 'modeltheme-addons-for-wpbakery'),
                ]
            ]
        );
        $this->add_control(
            'read_more_btn',
            [
                'label' => __('Buy Now Text', 'modeltheme'),
                'label_block' => true,
                'type' => Controls_Manager::TEXT,
            ]
        );
        $this->add_control(
            'text_color',
            [
                'type' => \Elementor\Controls_Manager::COLOR,
                'label' => __('Title color', 'modeltheme-addons-for-wpbakery'),
                'label_block' => true,
            ]
        );
        $this->add_control(
            'bg_btn_color',
            [
                'type' => \Elementor\Controls_Manager::COLOR,
                'label' => __('Background Button Color', 'modeltheme-addons-for-wpbakery'),
                'label_block' => true,
            ]
        );
        $this->add_control(
            'bg_text_color',
            [
                'type' => \Elementor\Controls_Manager::COLOR,
                'label' => __('Button Text Color ', 'modeltheme-addons-for-wpbakery'),
                'label_block' => true,
            ]
        );
        $this->add_control(
            'reserve_text_color',
            [
                'type' => \Elementor\Controls_Manager::COLOR,
                'label' => __('Reserve Text Color', 'modeltheme-addons-for-wpbakery'),
                'label_block' => true,
            ]
        );
        $this->add_control(
            'price_color',
            [
                'type' => \Elementor\Controls_Manager::COLOR,
                'label' => __('Price Text Color', 'modeltheme-addons-for-wpbakery'),
                'label_block' => true,
            ]
        );
        $this->end_controls_section();

    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $featured_image_size = $settings['featured_image_size'];
        $category = $settings['category'];
        $number = $settings['number'];
        $read_more_btn = $settings['read_more_btn'];
        $columns = $settings['columns'];
        $text_color = $settings['text_color'];
        $bg_btn_color = $settings['bg_btn_color'];
        $bg_text_color = $settings['bg_text_color'];
        $reserve_text_color = $settings['reserve_text_color'];
        $price_color = $settings['price_color'];

        echo do_shortcode('[mt-addons-listings-category page_builder="elementor" featured_image_size="' . $featured_image_size . '" category="' . $category . '" number="' . $number . '" read_more_btn="' . $read_more_btn . '" columns="' . $columns . '" text_color="' . $text_color . '" bg_btn_color="' . $bg_btn_color . '" bg_text_color="' . $bg_text_color . '" reserve_text_color="' . $reserve_text_color . '" price_color="' . $price_color . '"]');


    }

    protected function content_template()
    {

    }
}
