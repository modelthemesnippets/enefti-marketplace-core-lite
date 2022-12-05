<?php
/**
 * The template for displaying archive pages.
 *
 */
get_header();

$current_user = wp_get_current_user();
$term = get_term_by( 'slug', get_query_var( 'category_name' ), 'nft-listing-category' );
$nft_marketplace_core_avatar = get_term_meta($term->term_id, 'category-image-id', true);
$nft_marketplace_core_banner_img = false;
?>
<div class="author-fullwidth-banner" style="<?php if($nft_marketplace_core_banner_img){?>background-image:url(<?php echo esc_url($nft_marketplace_core_banner_img); ?>);<?php }else { ?>background:#ddd;<?php } ?>"></div>
<div class="high-padding nft-marketplace-core-author">
    <div class="container">
        <div class="row">
            <div class="author-details-wrapper col-md-12">
                <div class="author-title-image">
                    <div class="author-avatar">
                        <img class="avatar avatar-96 photo avatar-default" src="<?php echo wp_get_attachment_image_url($nft_marketplace_core_avatar);?>"/>
                    </div>
                    <div class="author-name">
                        <h3><?php echo esc_html($term->name); ?></h3>
                    </div>
                    <div class="author-description">
                        <p><?php echo esc_html($term->description ); ?></p>
                    </div>
                </div>

                <?php do_action('nft_marketplace_core_author_header_right'); ?>

            </div>

            <div class="author-listing-wrapper">
                <div class="author-listings">
                    <?php

                    $nfts_query_arg = array(
                        'post_type' => 'nft-listing',
                        'posts_per_page'  => 8,
                        'tax_query' => array(
                            array (
                                'taxonomy' => 'nft-listing-category',
                                'field' => 'slug',
                                'terms' => $term->slug,
                            )
                        ),
                    );
                    $nfts_query = new WP_Query( $nfts_query_arg );

                    if( $nfts_query->have_posts() ) : ?>
                        <?php while ( $nfts_query->have_posts() ) : $nfts_query->the_post(); ?>
                            <article id="post-<?php the_ID(); ?>" class="nft-listing col-md-3">
                                <?php do_action('nft_marketplace_core_author_listing_query'); ?>
                            </article>
                        <?php endwhile; ?>
                        <?php wp_reset_postdata(); ?>

                        <div class="clearfix"></div>

                        <?php do_action('nft_marketplace_core_author_after_grid'); ?>

                    <?php else : ?>
                        <strong><?php echo esc_html__('There are no NFT Listings!',NFT_MARKETPLACE_CORE_TEXTDOMAIN) ?></strong>
                    <?php endif; ?>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php get_footer(); ?>
