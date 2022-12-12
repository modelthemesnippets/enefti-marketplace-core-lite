<?php

namespace Elementor;

class nft_collectors_group extends Widget_Base
{
    public function get_name()
    {
        return 'mt-addons-nft-collectors-group';
    }

    public function get_title()
    {
        return 'NFT: Collections Group';
    }

    public function get_icon()
    {
        return 'eicon-product-categories';
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
                'label' => __('Content', 'nft-marketplace-core-lite'),
            ]
        );
        $this->add_control(
            'number',
            [
                'label' => __('Number of items', 'nft-marketplace-core-lite'),
                'label_block' => true,
                'type' => Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => __('Select Option', 'nft-marketplace-core-lite'),
                    'one-item' => __('1 Item', 'nft-marketplace-core-lite'),
                    '2' => __('2 Items', 'nft-marketplace-core-lite'),
                    '4' => __('4 Items', 'nft-marketplace-core-lite'),
                ]
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
            'collector_style_var',
            [
                'label' => __('Style Collector', 'nft-marketplace-core-lite'),
                'label_block' => true,
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => __('Select', 'nft-marketplace-core-lite'),
                    'collector_style_1' => __('Collector Image Floating', 'nft-marketplace-core-lite'),
                    'collector_style_2' => __('Collector Image In Wrapper', 'nft-marketplace-core-lite'),
                ]
            ]
        );
        $this->add_control(
            'author_status',
            [
                'label' => __('Author', 'nft-marketplace-core-lite'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'nft-marketplace-core-lite'),
                'label_off' => __('Hide', 'nft-marketplace-core-lite'),
                'return_value' => 'yes',
                'default' => 'no',
                'condition' => [
                    'collector_style_var' => 'collector_style_2',
                ],
            ]
        );
        $product_category_tax = get_terms('nft-listing-category');
        $product_category = array();
        foreach ($product_category_tax as $term) {
            $product_category[$term->slug] = $term->name . ' (' . $term->count . ')';
        }
        $repeater = new \Elementor\Repeater();

        $this->add_control(
            'category',
            [
                'label' => __('Select Category', 'elementor'),
                'type' => Controls_Manager::REPEATER,
                'fields' => [
                    [
                        'name' => 'category',
                        'label' => __('Select Products Category', 'elementor'),
                        'type' => Controls_Manager::SELECT,
                        'label_block' => true,
                        'options' => $product_category,
                    ],
                ],
            ],
		);
        $this->end_controls_section();
        $this->start_controls_section(
            'title_tab_carousel',
            [
                'label' => __('Carousel/Grid', 'nft-marketplace-core-lite'),
            ]
        );
        //carousel
        $this->add_control(
            'layout',
            [
                'label' => __('Layout', 'nft-marketplace-core-lite'),
                'label_block' => true,
                'type' => Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => __('Select Option', 'nft-marketplace-core-lite'),
                    'carousel' => __('Carousel', 'nft-marketplace-core-lite'),
                    'grid' => __('Grid', 'nft-marketplace-core-lite'),
                ]
            ]
        );
        $this->add_control(
            'items_desktop',
            [
                'label' => esc_html__('Visible Items (Desktop)', 'nft-marketplace-core-lite'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 4,
                'condition' => [
                    'layout' => 'carousel',
                ],
            ]
        );
        $this->add_control(
            'items_mobile',
            [
                'label' => esc_html__('Visible Items (Mobiles)', 'nft-marketplace-core-lite'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 1,
                'condition' => [
                    'layout' => 'carousel',
                ],
            ]
        );
        $this->add_control(
            'items_tablet',
            [
                'label' => esc_html__('Visible Items (Tablets)', 'nft-marketplace-core-lite'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 4,
                'condition' => [
                    'layout' => 'carousel',
                ],
            ]
        );
        $this->add_control(
            'autoplay',
            [
                'label' => __('AutoPlay', 'nft-marketplace-core-lite'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'nft-marketplace-core-lite'),
                'label_off' => __('Hide', 'nft-marketplace-core-lite'),
                'return_value' => 'yes',
                'default' => 'no',
                'condition' => [
                    'layout' => 'carousel',
                ],
            ]
        );
        $this->add_control(
            'delay',
            [
                'label' => esc_html__('Slide Speed (in ms)', 'nft-marketplace-core-lite'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 500,
                'max' => 10000,
                'step' => 100,
                'default' => 600,
                'condition' => [
                    'layout' => 'carousel',
                ],
            ]
        );
        $this->add_control(
            'navigation',
            [
                'label' => __('Navigation', 'nft-marketplace-core-lite'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'nft-marketplace-core-lite'),
                'label_off' => __('Hide', 'nft-marketplace-core-lite'),
                'return_value' => 'yes',
                'default' => 'no',
                'condition' => [
                    'layout' => 'carousel',
                ],
            ]
        );
        $this->add_control(
            'navigation_position',
            [
                'label' => __('Navigation Position', 'nft-marketplace-core-lite'),
                'label_block' => true,
                'type' => Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => __('Select Option', 'nft-marketplace-core-lite'),
                    'nav_above_left' => __('Above Slider Left', 'nft-marketplace-core-lite'),
                    'nav_above_center' => __('Above Slider Center', 'nft-marketplace-core-lite'),
                    'nav_above_right' => __('Above Slider Right', 'nft-marketplace-core-lite'),
                    'nav_top_left' => __('Top Left (In Slider)', 'nft-marketplace-core-lite'),
                    'nav_top_center' => __('Top Center (In Slider)', 'nft-marketplace-core-lite'),
                    'nav_top_right' => __('Top Right (In Slider)', 'nft-marketplace-core-lite'),
                    'nav_middle' => __('Middle (Left/Right)', 'nft-marketplace-core-lite'),
                    'nav_bottom_left' => __('Bottom Left (In Slider)', 'nft-marketplace-core-lite'),
                    'nav_bottom_center' => __('Bottom Center (In Slider)', 'nft-marketplace-core-lite'),
                    'nav_bottom_right' => __('Bottom Right (In Slider)', 'nft-marketplace-core-lite'),
                    'nav_below_left' => __('Below Slider Left', 'nft-marketplace-core-lite'),
                    'nav_below_center' => __('Below Slider Center', 'nft-marketplace-core-lite'),
                    'nav_below_right' => __('Below Slider Right', 'nft-marketplace-core-lite'),
                ],
                'condition' => [
                    'navigation' => 'yes',
                    'layout' => 'carousel',
                ],
            ]
        );
        $this->add_control(
            'nav_style',
            [
                'label' => __('Navigation Shape', 'nft-marketplace-core-lite'),
                'label_block' => true,
                'type' => Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => __('Select Option', 'nft-marketplace-core-lite'),
                    'nav-square' => __('Square', 'nft-marketplace-core-lite'),
                    'nav-rounde' => __('Rounded (5px Radius)', 'nft-marketplace-core-lite'),
                    'nav-round' => __('Round (50px Radius)', 'nft-marketplace-core-lite'),
                ],
                'condition' => [
                    'navigation' => 'yes',
                    'layout' => 'carousel',
                ],
            ]
        );
        $this->add_control(
            'navigation_color',
            [
                'type' => \Elementor\Controls_Manager::COLOR,
                'label' => __('Navigation color', 'nft-marketplace-core-lite'),
                'label_block' => true,
                'condition' => [
                    'navigation' => 'yes',
                    'layout' => 'carousel',
                ],
            ]
        );
        $this->add_control(
            'navigation_bg_color',
            [
                'type' => \Elementor\Controls_Manager::COLOR,
                'label' => __('Navigation Background color', 'nft-marketplace-core-lite'),
                'label_block' => true,
                'condition' => [
                    'navigation' => 'yes',
                    'layout' => 'carousel',
                ],
            ]
        );
        $this->add_control(
            'navigation_color_hover',
            [
                'type' => \Elementor\Controls_Manager::COLOR,
                'label' => __('Navigation Color Hover', 'nft-marketplace-core-lite'),
                'label_block' => true,
                'condition' => [
                    'navigation' => 'yes',
                    'layout' => 'carousel',
                ],
            ]
        );
        $this->add_control(
            'navigation_bg_color_hover',
            [
                'type' => \Elementor\Controls_Manager::COLOR,
                'label' => __('Navigation Background color - Hover', 'nft-marketplace-core-lite'),
                'label_block' => true,
                'condition' => [
                    'navigation' => 'yes',
                    'layout' => 'carousel',
                ],
            ]
        );
        $this->add_control(
            'pagination',
            [
                'label' => __('Pagination (dots)', 'nft-marketplace-core-lite'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'nft-marketplace-core-lite'),
                'label_off' => __('Hide', 'nft-marketplace-core-lite'),
                'return_value' => 'yes',
                'default' => 'no',
                'condition' => [
                    'layout' => 'carousel',
                ],
            ]
        );
        $this->add_control(
            'pagination_color',
            [
                'type' => \Elementor\Controls_Manager::COLOR,
                'label' => __('Pagination color', 'nft-marketplace-core-lite'),
                'label_block' => true,
                'condition' => [
                    'pagination' => 'yes',
                    'layout' => 'carousel',
                ],
            ]
        );
        $this->add_control(
            'space_items',
            [
                'label' => esc_html__('Space Between Items', 'nft-marketplace-core-lite'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 30,
                'condition' => [
                    'layout' => 'carousel',
                ],
            ]
        );
        $this->add_control(
            'touch_move',
            [
                'label' => __('Allow Touch Move', 'nft-marketplace-core-lite'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'nft-marketplace-core-lite'),
                'label_off' => __('Hide', 'nft-marketplace-core-lite'),
                'return_value' => 'yes',
                'default' => 'no',
                'condition' => [
                    'layout' => 'carousel',
                ],
            ]
        );
        $this->add_control(
            'grab_cursor',
            [
                'label' => __('Grab Cursor', 'nft-marketplace-core-lite'),
                'placeholder' => esc_html__('If checked, will show the mouse pointer over the carousel', 'nft-marketplace-core-lite'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'nft-marketplace-core-lite'),
                'label_off' => __('Hide', 'nft-marketplace-core-lite'),
                'return_value' => 'yes',
                'default' => 'no',
                'condition' => [
                    'layout' => 'carousel',
                ],
            ]
        );
        $this->add_control(
            'effect',
            [
                'label' => __('Carousel Effect', 'nft-marketplace-core-lite'),
                'placeholder' => esc_html__("See all availavble effects on <a target='_blank' href='https://swiperjs.com/demos#effect-fade'>swiperjs.com</a>", 'nft-marketplace-core-lite'),
                'label_block' => true,
                'type' => Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => __('Select Option', 'nft-marketplace-core-lite'),
                    'creative' => __('Creative', 'nft-marketplace-core-lite'),
                    'cards' => __('Cards', 'nft-marketplace-core-lite'),
                    'coverflow' => __('Coverflow', 'nft-marketplace-core-lite'),
                    'cube' => __('Cube', 'nft-marketplace-core-lite'),
                    'fade' => __('Fade', 'nft-marketplace-core-lite'),
                    'flip' => __('Flip', 'nft-marketplace-core-lite'),
                ],
                'condition' => [
                    'layout' => 'carousel',
                ],
            ]
        );
        $this->add_control(
            'infinite_loop',
            [
                'label' => __('Infinite Loop', 'nft-marketplace-core-lite'),
                'placeholder' => esc_html__('If checked, will show the numerical value of infinite loop', 'nft-marketplace-core-lite'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'nft-marketplace-core-lite'),
                'label_off' => __('Hide', 'nft-marketplace-core-lite'),
                'return_value' => 'yes',
                'default' => 'no',
                'condition' => [
                    'layout' => 'carousel',
                ],
            ]
        );
        $this->add_control(
            'centered_slides',
            [
                'label' => __('Centered Slides', 'nft-marketplace-core-lite'),
                'placeholder' => esc_html__('If checked, the left side and the right side will have a partial slide visible.', 'nft-marketplace-core-lite'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'nft-marketplace-core-lite'),
                'label_off' => __('Hide', 'nft-marketplace-core-lite'),
                'return_value' => 'yes',
                'default' => 'no',
                'condition' => [
                    'layout' => 'carousel',
                ],
            ]
        );
        $this->add_control(
            'columns',
            [
                'label' => __('Columns', 'nft-marketplace-core-lite'),
                'label_block' => true,
                'type' => Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => __('Select Option', 'nft-marketplace-core-lite'),
                    'col-md-12' => __('1 Column', 'nft-marketplace-core-lite'),
                    'col-md-6' => __('2 Columns', 'nft-marketplace-core-lite'),
                    'col-md-4' => __('3 Columns', 'nft-marketplace-core-lite'),
                    'col-md-3' => __('4 Columns', 'nft-marketplace-core-lite'),
                    'col-md-2' => __('6 Columns', 'nft-marketplace-core-lite'),
                ],
                'condition' => [
                    'layout' => 'grid',
                ],
            ]
        );
//end carousel
        if (!function_exists('modeltheme_addons_swiper_attributes')) {
            function modeltheme_addons_swiper_attributes($id = '', $autoplay = '', $delay = '', $items_desktop = '', $items_mobile = '', $items_tablet = '', $space_items = '', $touch_move = '', $effect = '', $grab_cursor = '', $infinite_loop = '', $centered_slides = '', $navigation = '', $pagination = '')
            {
                ?>
                data-swiper-id="<?php echo esc_attr($id); ?>"
                data-swiper-autoplay="<?php echo esc_attr($autoplay); ?>"
                data-swiper-delay="<?php echo esc_attr($delay); ?>"
                data-swiper-desktop-items="<?php echo esc_attr($items_desktop); ?>"
                data-swiper-mobile-items="<?php echo esc_attr($items_mobile); ?>"
                data-swiper-tablet-items="<?php echo esc_attr($items_tablet); ?>"
                data-swiper-space-between-items="<?php echo esc_attr($space_items); ?>"
                data-swiper-allow-touch-move="<?php echo esc_attr($touch_move); ?>"
                data-swiper-effect="<?php echo esc_attr($effect); ?>"
                data-swiper-grab-cursor ="<?php echo esc_attr($grab_cursor); ?>"
                data-swiper-infinite-loop ="<?php echo esc_attr($infinite_loop); ?>"
                data-swiper-centered-slides ="<?php echo esc_attr($centered_slides); ?>"
                data-swiper-navigation ="<?php echo esc_attr($navigation); ?>"
                data-swiper-pagination ="<?php echo esc_attr($pagination); ?>"
                <?php
            }
        }
        $this->end_controls_section();
    }

    protected function render()
    {
        // global $enefti_redux;
        $settings = $this->get_settings_for_display();
        $number = $settings['number'];
        $featured_image_size = $settings['featured_image_size'];
        $collector_style_var = $settings['collector_style_var'];
        $author_status = $settings['author_status'];
        $collectors_groups = $settings['category'];
        //carousel
        $autoplay = $settings['autoplay'];
        $delay = $settings['delay'];
        $items_desktop = $settings['items_desktop'];
        $items_mobile = $settings['items_mobile'];
        $items_tablet = $settings['items_tablet'];
        $space_items = $settings['space_items'];
        $touch_move = $settings['touch_move'];
        $effect = $settings['effect'];
        $grab_cursor = $settings['grab_cursor'];
        $infinite_loop = $settings['infinite_loop'];
        // $carousel 					= $settings['carousel'];
        $columns = $settings['columns'];
        $layout = $settings['layout'];
        $centered_slides = $settings['centered_slides'];
        // $select_navigation 			= $settings['select_navigation'];
        $navigation_position = $settings['navigation_position'];
        $nav_style = $settings['nav_style'];
        $navigation_color = $settings['navigation_color'];
        $navigation_bg_color = $settings['navigation_bg_color'];
        $navigation_bg_color_hover = $settings['navigation_bg_color_hover'];
        $navigation_color_hover = $settings['navigation_color_hover'];
        $pagination_color = $settings['pagination_color'];
        $navigation = $settings['navigation'];
        $pagination = $settings['pagination'];
        //end carousel
        $shortcode_content = '';

        $collectors_groups_string = '';
        if ($collectors_groups) {
            foreach ($collectors_groups as $key => $collector) {
                if ($key === array_key_last($collectors_groups)) {
                    $collectors_groups_string .= $collector['category'];
                } else {
                    $collectors_groups_string .= $collector['category'] . ',';
                }
            }
        }
        // echo '<pre>' . var_export($collectors_groups, true) . '</pre>';

        $shortcode_content .= do_shortcode('[mt-addons-nft-collectors-group page_builder="elementor" collectors_groups="' . $collectors_groups_string . '"  number="' . $number . '"  featured_image_size="' . $featured_image_size . '"  collector_style_var="' . $collector_style_var . '" author_status="' . $author_status . '" autoplay="' . $autoplay . '" delay="' . $delay . '" items_desktop="' . $items_desktop . '" items_mobile="' . $items_mobile . '" items_tablet="' . $items_tablet . '" space_items="' . $space_items . '" touch_move="' . $touch_move . '" effect="' . $effect . '" grab_cursor="' . $grab_cursor . '" infinite_loop="' . $infinite_loop . '" columns="' . $columns . '" layout="' . $layout . '" centered_slides="' . $centered_slides . '" navigation_position="' . $navigation_position . '" nav_style="' . $nav_style . '" navigation_color="' . $navigation_color . '" navigation_bg_color="' . $navigation_bg_color . '" navigation_color_hover="' . $navigation_color_hover . '" navigation_bg_color_hover="' . $navigation_bg_color_hover . '" pagination_color="' . $pagination_color . '" navigation="' . $navigation . '" pagination="' . $pagination . '" ]');

        echo $shortcode_content;

    }

    protected function content_template()
    {

    }
}
