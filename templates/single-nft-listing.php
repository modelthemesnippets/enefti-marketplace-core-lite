<?php
/**
 * The template for single NFT Listing pages.
 */

$postID = get_the_ID();

global $wpdb;

/**
 * Define single NFT price,symbol,regular price
 */
$nft_marketplace_core_nft_listing_token_id = get_post_meta($postID, 'nft_marketplace_core_nft_listing_token_id', true);
$nft_marketplace_core_nft_listing_address = get_post_meta($postID, 'nft_marketplace_core_nft_listing_address', true);
$nft_marketplace_core_nft_listing_blockchains_term = get_the_terms($postID, 'nft_listing_blockchains');
$nft_marketplace_core_nft_listing_price = get_post_meta($postID, 'nft_marketplace_core_nft_listing_currency_price', true);
$nft_marketplace_core_nft_listing_usd_price = null;

$author = get_post($postID)->post_author;


$nft_marketplace_core_nft_marketplace_id = get_post_meta($postID, 'nft_marketplace_core_nft_marketplace_id', true);
$nft_marketplace_core_nft_listing_start_time = get_post_meta($postID, 'nft_marketplace_core_nft_listing_start_time', true);
$listingStarted = time() > $nft_marketplace_core_nft_listing_start_time;
$hasItemLeftToSell = get_post_meta($postID, "nft_marketplace_core_nft_listing_quantity", true) > 0;
$listingNotExpired = get_post_meta($postID, 'nft_marketplace_core_nft_listing_end_time', true) > time();

$isItemOnSale = $listingStarted && $hasItemLeftToSell && $listingNotExpired;

/**
 * Main Content:Start of Page
 */

get_header();


/**
 * Main Content: Breadcrumbs
 */
do_action('nft_marketplace_core_before_main_content');
?>
<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">
        <div class="high-padding">
            <div class="container blog-posts single-nft">
                <div class="row">
                    <div class="col-md-12 main-content row">
                        <div class="col-md-6 nft-thumbnails">
                            <div>
                                <?php echo NFT_Marketplace_Core_Lite\Internals\Helper::getNFTDisplayAsset(get_the_ID()); ?>
                            </div>
                        </div>
                        <div class="summary nft-entry-summary col-md-6">
                            <?php //Wishlist button // ?>
                            <div class="nft-listing-infos-wrapper">

                                <?php
                                /**
                                 * Hook: Before Title (Set: Author, Likes, Views)
                                 */
                                do_action('nft_marketplace_core_single_nft_before_title'); ?>

                            </div>
                            <h2 itemprop="name"
                                class="nft_title entry-title"><?php echo esc_html(get_the_title()); ?></h2>
                            <?php
                            if (get_the_terms($postID, 'nft-listing-category')) {
                                ?>
                                <div class="single-collection-title">

                                    <?php
                                    /**
                                     * Hook: After Title (Set Category name)
                                     */

                                    ?>
                                    <?php do_action('nft_marketplace_core_single_nft_after_title_category'); ?>

                                    <span><?php echo apply_filters('nft_marketplace_core_category_title', esc_html__('Category', 'nft-marketplace-core-lite')); ?></span>

                                </div>
                            <?php }

                            if (get_the_terms($postID, 'nft-listing-collection')) {
                                ?>
                                <div class="single-collection-title">

                                    <?php
                                    /**
                                     * Hook: After Title (Set Collection name)
                                     */

                                    ?>
                                    <?php do_action('nft_marketplace_core_single_nft_after_title_collection'); ?>

                                    <span><?php echo apply_filters('nft_marketplace_core_collection_title', esc_html__('Collection', 'nft-marketplace-core-lite')); ?></span>

                                </div>

                            <?php } ?>

                            <div class="product_meta">
                                <?php if ($isItemOnSale) { ?>
                                    <span><?php esc_html_e("Quantity", 'nft-marketplace-core-lite'); ?>: <span><?php esc_html_e(get_post_meta($postID, "nft_marketplace_core_nft_listing_quantity", true)) ?></span></span>
                                <?php } ?>
                                <span><?php esc_html_e("Listing Type", 'nft-marketplace-core-lite'); ?>: <span><?php esc_html_e(ucfirst(get_post_meta($postID, "nft_marketplace_core_nft_listing_type", true))) ?></span></span>
                                <span><?php esc_html_e("NFT's Contract Address", 'nft-marketplace-core-lite'); ?>: <span><?php esc_html_e($nft_marketplace_core_nft_listing_address) ?></span></span>
                                <span><?php esc_html_e("Token ID", 'nft-marketplace-core-lite'); ?>: <span><?php esc_html_e($nft_marketplace_core_nft_listing_token_id) ?></span></span>

                                <?php if ($isItemOnSale) { ?>
                                    <span><?php esc_html_e(
                                            "Ending", 'nft-marketplace-core-lite'); ?>: <span><?php esc_html_e(wp_date('l, F jS, Y \a\t g:i:s A', get_post_meta($postID, 'nft_marketplace_core_nft_listing_end_time', true))) ?></span></span>
                                <?php } ?>
                            </div>

                                <?php
                            if (!isset(get_option('nft_marketplace_core_panel_single_nft')['nft_marketplace_core_tabs']) || get_option('nft_marketplace_core_panel_single_nft')['nft_marketplace_core_tabs'] == 'top') { ?>
                                <?php do_action('nft_marketplace_core_single_nft_after_metas'); ?>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <?php
                /**
                 * Hook: After Metas (Set Tabs Below)
                 */
                ?>
                <?php if (isset(get_option('nft_marketplace_core_panel_single_nft')['nft_marketplace_core_tabs']) && get_option('nft_marketplace_core_panel_single_nft')['nft_marketplace_core_tabs'] == 'bellow') { ?>
                    <?php do_action('nft_marketplace_core_single_nft_before_related'); ?>
                <?php } ?>

                <?php
                /**
                 * Hook: Related NFT after content
                 */
                ?>
                <?php do_action('nft_marketplace_core_single_nft_related'); ?>

            </div>
        </div>
    </main>
</div>
<?php
/**
 * Main Content: End of page
 */
get_footer(); ?>
