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

/**
 *
 * @package   NFT Marketplace Core Lite
 * @author    ModelTheme <support@modeltheme.com>
 * @copyright Copyright (C) 2012-2022, Modeltheme, support@modeltheme.com
 * @license   GPL-3.0
 * @link      https://modeltheme.com
 */
class PageAuthor extends PageGlobal
{
    public function initialize()
    {
        add_action( 'nft_marketplace_core_author_after_grid', [$this, 'listings_pagination']);
        add_action( 'nft_marketplace_core_author_listing_query', [$this, 'single_nft_block']);
        add_action( 'nft_marketplace_core_author_header_right', [$this, 'social_profiles']);
    }


    // AUTHOR LISTING PAGINATION //
    function listings_pagination() {
        echo '<div class="modeltheme-pagination-holder col-md-12">';
        $author = get_user_by( 'slug', get_query_var( 'author_name' ) );
        $nfts_query_arg = array(
            'post_type' => 'nft-listing',
            'author' => $author->ID,
            'post_status' => 'publish',
            'posts_per_page'  => 8,
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
        echo '</div>';
    }
}
