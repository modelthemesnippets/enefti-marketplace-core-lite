<?php
/**
 * code
 *
 * @package   code
 * @author    ModelTheme <support@modeltheme.com>
 * @copyright Copyright (C) 2012-2022, Modeltheme, support@modeltheme.com
 * @license   GPL-3.0
 * @link      https://modeltheme.com
 */

namespace NFT_Marketplace_Core_Lite\Frontend\Extras;

use NFT_Marketplace_Core_Lite\Engine\Base;

/**
 *
 * @package   NFT Marketplace Core Lite
 * @author    ModelTheme <support@modeltheme.com>
 * @copyright Copyright (C) 2012-2022, Modeltheme, support@modeltheme.com
 * @license   GPL-3.0
 * @link      https://modeltheme.com
 */
class PageCategory extends PageGlobal
{
    public function initialize()
    {
        add_action('nft_marketplace_core_archive_before_grid', [$this, 'archive_ordering'], 20);
        add_action('nft_marketplace_core_archive_before_grid', [$this, 'archive_listings_count'], 10);
        add_action('nft_marketplace_core_archive_after_grid_template', [$this, 'archive_pagination']);
        add_action('nft_marketplace_core_archive_listing_query', [$this, 'single_nft_block']);
    }

    //ARCHIVE NFT : PAGINATION
    function archive_pagination() {
        echo '<div class="modeltheme-pagination-holder col-md-12">';
        $nft_listing_single_cat = wp_get_post_terms(get_the_ID(), 'nft-listing-category');
        $nr_prod= '';
        if(isset(get_option( 'nft_marketplace_core_panel_shop_page' )['nft_marketplace_core_items_per_page'])){
            $nr_prod= get_option( 'nft_marketplace_core_panel_shop_page' )['nft_marketplace_core_items_per_page'];
        } else {
            $nr_prod= '9';
        }
        if($nft_listing_single_cat) {
            $nfts_query_arg = array(
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
            $nfts_query = new \WP_Query( $nfts_query_arg );
            echo '<div class="modeltheme-pagination pagination">';
            echo paginate_links( array(
                'base'         => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
                'total'        => $nfts_query->max_num_pages,
                'current'      => max( 1, get_query_var( 'paged' ) ),
                'format'       => '?paged=%#%',
                'show_all'     => false,
                'type'         => 'plain',
                'end_size'     => 2,
                'mid_size'     => 1,
                'prev_next'    => false,
                'add_args'     => false,
                'add_fragment' => '',
            ) );
            echo '</div>';
        }
        echo '</div>';
    }


    // ARCHIVE NFT : QUERY LISTING COUNT
    function archive_listings_count()
    {
        global $wp_query;
        echo '<div class="nft-marketplace-taxonomy-count col-md-9">';
        $query_page = (get_query_var('paged')) ? get_query_var('paged') : 1;
        $posts_per_page = get_query_var('posts_per_page');
        $last_page = $posts_per_page * $query_page;
        $first_page = $last_page - $posts_per_page + 1;
        $total = $wp_query->found_posts;
        echo esc_html__('Showing ' . esc_attr($first_page) . ' - ' . esc_attr($last_page) . ' of ' . esc_attr($total) . ' results', 'nft-marketplace-core-lite');
        echo '</div>';
    }


    // ARCHIVE NFT : ORDERING
    function archive_ordering(){
        if(!is_search()) {
            global $wp;
            $location = home_url( $wp->request );
            echo '<div class="nft-marketplace-taxonomy-ordering col-md-3">';
            echo '<form class="nft-marketplace-ordering" method="get">'; ?>
            <select onchange="if(this.value !== '') document.location = '<?php echo esc_attr($location); ?>/?orderby=' + this.value">
                <option value=""><?php echo esc_html__('Default Sorting'); ?></option>
                <option value="title"<?php if(isset($_GET['order_by']) && $_GET['order_by'] == 'title') echo ' selected="selected"'; ?>><?php echo esc_html__('Sort by Title','nft-marketplace-core-lite');?></option>
                <option value="date"<?php if(isset($_GET['order_by']) && $_GET['order_by'] == 'date') echo ' selected="selected"'; ?>><?php echo esc_html__('Sort by Date','nft-marketplace-core-lite');?></option>
            </select><?php
            echo '<input type="hidden" name="paged" value="1">';
            echo '</form>';
            echo '</div>';
        }
    }
}
