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
class PageNFTListings extends PageGlobal
{
    public function initialize()
    {
        //NFT Listings template
        add_action('nft_marketplace_core_archive_listing_query_template', [$this, 'single_nft_block']);
        add_action('nft_marketplace_core_archive_before_grid_template', [$this, 'archive_listings_count_template'], 10);
        add_action('nft_marketplace_core_archive_before_grid_template', [$this, 'archive_ordering_template'], 20);
    }

    // TEMPLATE NFT LISTING: QUERY LISTING COUNT
    function archive_listings_count_template() {
        global $wp_query;
        $nfts_query_arg = array(
            'post_type' => 'nft-listing',
            'posts_per_page'  => -1,
            'post_status' => 'publish',
        );
        $nfts_query = new \WP_Query( $nfts_query_arg );
        echo '<div class="nft-marketplace-taxonomy-count col-md-9">';
        $query_page = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
        $posts_per_page = $nfts_query->found_posts;
        $last_page = $posts_per_page * $query_page;
        $first_page = $last_page - $posts_per_page + 1;
        $total = $nfts_query->found_posts;
        echo esc_html__('Showing '.esc_attr($first_page).' - '.esc_attr($last_page).' of '.esc_attr($total).' results','nft-marketplace-core-lite');
        echo '</div>';
    }

    // TEMPLATE NFT LISTING: ORDERING
    function archive_ordering_template(){
        $term = get_queried_object();
        $taxonomy = $term->slug;
        echo '<div class="nft-marketplace-taxonomy-ordering col-md-3">';
        echo '<form class="nft-marketplace-ordering" method="get">'; ?>
        <select onchange="if(this.value != '') document.location ='?orderby=' + this.value">
            <option value=""><?php echo esc_html__('Default Sorting'); ?></option>
            <option value="title"<?php if(isset($_GET['order_by']) && $_GET['order_by'] == 'title') echo ' selected="selected"'; ?>><?php echo esc_html__('Sort by Title','nft-marketplace-core-lite');?></option>
            <option value="date"<?php if(isset($_GET['order_by']) && $_GET['order_by'] == 'date') echo ' selected="selected"'; ?>><?php echo esc_html__('Sort by Date','nft-marketplace-core-lite');?></option>
        </select><?php
        echo '<input type="hidden" name="paged" value="1">';
        echo '</form>';
        echo '</div>';
    }
}
