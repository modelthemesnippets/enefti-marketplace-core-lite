<?php
/**
 * The template for displaying search results pages.
 *
 */
get_header(); 

if(isset(get_option( 'nft_marketplace_core_panel_shop_page' )['nft_marketplace_core_select_sidebar'])){
    $sidebar = get_option( 'nft_marketplace_core_panel_shop_page' )['nft_marketplace_core_select_sidebar'];
}
$main_content = '';

// Breadcrumbs //
do_action('nft_marketplace_core_before_main_content');
?>

<!-- Page content -->
<div class="high-padding nft-search-page">
    <!-- Blog content -->
    <div class="container blog-posts">
        <div class="row">
            <?php if (get_post_type() == 'nft-listing') { ?>
                <?php if(isset(get_option( 'nft_marketplace_core_panel_shop_page' )['nft_marketplace_core_sidebar']) && get_option( 'nft_marketplace_core_panel_shop_page' )['nft_marketplace_core_sidebar'] == 'on'){ ?>
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
                    
                    <?php do_action('nft_marketplace_core_archive_before_grid'); ?>

                    <div class="row">
                        <?php if ( have_posts() ) : ?>
                            <?php /* Start the Loop */ ?>
                            <?php while ( have_posts() ) : the_post(); ?>
                                <article id="post-<?php the_ID(); ?>" class="nft-listing col-md-4">
                                    <?php do_action('nft_marketplace_core_search_listing_query'); ?>
                                </article>
                            <?php endwhile; ?>
                            <div class="modeltheme-pagination-holder col-md-12">
                                <div class="modeltheme-pagination pagination">  
                                    <?php do_action('nft_marketplace_core_archive_pagination'); ?>
                                </div>
                            </div>
                        <?php else : ?>
                            <?php get_template_part( 'content', 'none' ); ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php } else { ?>
            <?php if (  class_exists( 'ReduxFrameworkPlugin' ) ) { ?>
                <?php if ( $enefti_redux['enefti_blog_layout'] == 'enefti_blog_left_sidebar' && is_active_sidebar( $sidebar )) { ?>
                    <div class="col-md-4 sidebar-content">
                        <?php dynamic_sidebar( $sidebar ); ?>
                    </div>
                <?php } ?>
            <?php } ?>
            <div class="<?php echo esc_attr($class); ?> main-content">
                <div class="row">
                    <?php if ( have_posts() ) : ?>
                        <?php /* Start the Loop */ ?>
                        <?php while ( have_posts() ) : the_post(); ?>
                            <?php
                            /**
                             * Run the loop for the search to output the results.
                             * If you want to overload this in a child theme then include a file
                             * called content-search.php and that will be used instead.
                             */
                            get_template_part( 'content', get_post_format() );
                            ?>
                        <?php endwhile; ?>
                        <div class="enefti-pagination pagination">             
                            <?php do_action('nft_marketplace_core_archive_pagination'); ?>
                        </div>
                    <?php else : ?>
                        <?php get_template_part( 'content', 'none' ); ?>
                    <?php endif; ?>
                </div>
            </div>
            <?php if (  class_exists( 'ReduxFrameworkPlugin' ) ) { ?>
                <?php if ( $enefti_redux['enefti_blog_layout'] == 'enefti_blog_right_sidebar' && is_active_sidebar( $sidebar )) { ?>
                    <div class="col-md-4 sidebar-content sidebar-content-right-side">
                        <?php  dynamic_sidebar( $sidebar ); ?>
                    </div>
                <?php } ?>
            <?php }else{ ?>
                <?php if ( is_active_sidebar( $sidebar )) { ?>
                    <div class="col-md-4 sidebar-content sidebar-content-right-side">
                        <?php  dynamic_sidebar( $sidebar ); ?>
                    </div>
                <?php } ?>                    
            <?php } ?>
         <?php } ?>
        </div>
    </div>
</div>
<?php get_footer(); ?>