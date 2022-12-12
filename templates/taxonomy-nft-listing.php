<?php

/**
 * The template for displaying taxonomy pages.
 */
global $wp_query;
get_header();

/**
 * Theme Panel Setting for number of NFT listings on row
 * since version 1.0
 */
$class= '';
if(!isset(get_option( 'nft_marketplace_core_panel_shop_page' )['nft_marketplace_core_items_per_row'])){
    $class= 'col-md-4';
} else {
    $class= get_option( 'nft_marketplace_core_panel_shop_page' )['nft_marketplace_core_items_per_row'];
}


/**
 * Theme Panel Setting for number of NFT listings on Shop
 * since version 1.0
 */
$nr_prod= '';
if(!isset(get_option( 'nft_marketplace_core_panel_shop_page' )['nft_marketplace_core_items_per_page'])){
    $nr_prod= '9';
} else {
    $nr_prod= get_option( 'nft_marketplace_core_panel_shop_page' )['nft_marketplace_core_items_per_page'];
}


/**
 * Theme Panel Setting for sidebar display
 * since version 1.0
 */
$main_content = '';
if(isset(get_option( 'nft_marketplace_core_panel_shop_page' )['nft_marketplace_core_items_per_page'])){
    $sidebar = get_option( 'nft_marketplace_core_panel_shop_page' )['nft_marketplace_core_select_sidebar'];
}
$nft_listing_single_cat = wp_get_post_terms(get_the_ID(), 'nft-listing-category');


/**
 * Plugin Breadcrumbs
 * since version 1.0
 */
do_action('nft_marketplace_core_before_main_content');


/**
 * Main Content : Start of the page
 * since version 1.0
 */
 ?>
<div class="high-padding">
    <div class="container blog-posts content-area">
        <div class="row">
            <?php if(isset(get_option( 'nft_marketplace_core_panel_shop_page' )['nft_marketplace_core_sidebar'] ) && get_option( 'nft_marketplace_core_panel_shop_page' )['nft_marketplace_core_sidebar'] == 'on'){ ?>
                <?php $main_content = 'col-md-9'; ?>
                <?php if (is_active_sidebar( $sidebar )) { ?>
                    <div id="sidebar" class="col-md-3 ">
                        <?php dynamic_sidebar( $sidebar ); ?>
                    </div>
                <?php } ?>
            <?php } else {
                $main_content = 'col-md-12';
            } ?>
            <div class="<?php echo esc_attr($main_content); ?>">

                <?php
                /**
                * Hook: Before The NFT Listings (Set: Ordering, Count)
                */
                do_action('nft_marketplace_core_archive_before_grid'); ?>

                <?php if($nft_listing_single_cat) {?>
                    <div class="main-content">
                        <?php
                        $args = array(
                            'posts_per_page' => $nr_prod,
                            'post_type'      => 'nft-listing',
                            'post_status' => 'publish',
                            'tax_query'        => array(
                                array(
                                    'taxonomy' => 'nft-listing-category',
                                    'field'    => 'id',
                                    'terms'    => $nft_listing_single_cat[0]->term_id
                                )
                            )
                        );
                        $my_query = new wp_query( $args ); ?>
                        <?php if ( $my_query->have_posts() ) : ?>
                            <?php
                            while ( $my_query->have_posts() ) : $my_query->the_post(); ?>
                                <article id="post-<?php the_ID(); ?>" class="nft-listing <?php echo esc_attr($class);?>">

                                    <?php
                                    /**
                                    * Hook: NFT Listing query (Set: Single NFT Block)
                                    */
                                    do_action('nft_marketplace_core_archive_listing_query'); ?>

                                </article>
                            <?php endwhile; ?>
                            <?php wp_reset_query();

                            /**
                             * Hook: After The NFT Listings (Set: Pagination)
                             */
                            do_action('nft_marketplace_core_archive_after_grid_template'); ?>

                        <?php else : ?>
                            <?php echo apply_filters('nft_marketplace_core_no_listings_found', esc_html__('There are no NFT Listings', 'nft-marketplace-core-lite')); ?>
                        <?php endif; ?>
                    </div>
                <?php } else { ?>
                    <?php echo apply_filters('nft_marketplace_core_no_listings_found', esc_html__('There are no NFT Listings', 'nft-marketplace-core-lite')); ?>
                <?php } ?>

            </div>
        </div>
    </div>
</div>

<?php
/**
 * Main Content : End of the page
 * since version 1.0
 */
get_footer(); ?>
