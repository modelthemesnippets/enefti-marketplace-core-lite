<?php
/**
 * The template for displaying archive pages.
 *
 */
get_header();

$current_user = wp_get_current_user();
$author = get_user_by( 'slug', get_query_var( 'author_name' ) );
$author_edit_link = get_home_url().'/manage-your-nfts/#tab-5';
$author_link = home_url($wp->request);

//Update Form
do_shortcode('[nft_marketplace_core_update_author_form_shortcode]');

$author = get_user_by( 'slug', get_query_var( 'author_name' ) );
$nft_marketplace_core_banner_img = get_user_meta($author->ID,'nft_marketplace_core_banner_img',true);
?>

<div class="author-fullwidth-banner" style="<?php if($nft_marketplace_core_banner_img){?>background-image:url(<?php echo esc_url($nft_marketplace_core_banner_img); ?>);<?php }else { ?>background:#ddd;<?php } ?>"></div>
<div class="high-padding nft-marketplace-core-author">
    <div class="container">
        <div class="row">
            <div class="author-details-wrapper col-md-12">
                <div class="author-title-image">
                    <div class="author-avatar">
                        <?php echo get_avatar( $author->ID, 96);?>
                    </div>
                    <div class="author-name">
                        <h3><?php echo esc_html($author->display_name); ?></h3>

                        <?php if($current_user->user_nicename == $author->user_nicename) { ?>
                            <p><a class="btn-edit" href="<?php echo esc_url($author_edit_link); ?>"><?php echo esc_html__('Edit Profile','nft-marketplace-core-lite'); ?></a></p>
                        <?php } ?>
                    </div>
                    <div class="author-description">
                        <?php if(function_exists('mtm_metamask_current_user_has_metamask') && mtm_metamask_current_user_has_metamask()) {?>
                            <p class="author-eth-address">
                                <img src="<?php echo NFT_MARKETPLACE_CORE_PLUGIN_URL; ?>assets/images/token.svg" alt="Ether" width="10" height="10">
                                <?php
                                global $wpdb;
                                $tablename = $wpdb->prefix.'metamask_accounts';
                                $row = $wpdb->get_row( "SELECT * FROM  $tablename WHERE `user_id` = '$current_user->ID' AND account_type = 'metamask' ",ARRAY_A );
                                echo esc_html($row["account_address"]);
                                ?>
                            </p>
                        <?php } ?>
                        <?php if(!empty($author->user_description)) {?>
                            <p><?php echo esc_html($author->user_description ); ?></p>
                        <?php } ?>
                    </div>
                </div>

                <?php do_action('nft_marketplace_core_author_header_right'); ?>

            </div>

            <div class="author-listing-wrapper">
                <div class="author-listings">
                    <?php
                    $nfts_query_arg = array(
                        'post_type' => 'nft-listing',
                        'author' => $author->ID,
                        'post_status' => 'publish',
                        'posts_per_page'  => 8,
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
                        <strong><?php echo esc_html__('There are no NFT Listings!','nft-marketplace-core-lite') ?></strong>
                    <?php endif; ?>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php get_footer(); ?>
