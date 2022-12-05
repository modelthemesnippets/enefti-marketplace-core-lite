<?php
// Silence is golden.
namespace NFT_Marketplace_Core\Integrations;

use NFT_Marketplace_Core\Engine\Base;

class WooCommerce extends Base
{
    /**
     * Initialize the class.
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        add_action("woocommerce_account_dashboard", [$this, "myAccountDashboardInformation"]);
        add_filter("woocommerce_account_menu_items", [$this, "addItemToWoocommerceMenuItems"], 99, 1);

    }

    /**
     * Add more information inside the WooCommerce My Account first page's content, to explain where the Enefti Core Dashboard is located.
     *
     * @return void
     * @since 2.0.0
     */
    public function myAccountDashboardInformation()
    {
        echo "
        <p>
            " . esc_html__('This is the Woocommerce My account page, if you are searching for the Enefti Core Dashboard page click on ', NFT_MARKETPLACE_CORE_TEXTDOMAIN) . " <a href='" . esc_url(site_url("manage-your-nfts")) . "'>" . esc_html__('this', NFT_MARKETPLACE_CORE_TEXTDOMAIN) . "</a> " . esc_html__(' link.', NFT_MARKETPLACE_CORE_TEXTDOMAIN) . "
        </p>
        <a class='single_nft_button button alt' href='" . esc_url(site_url("manage-your-nfts")) . "'>" . esc_html__('Enefti Core Dashboard', NFT_MARKETPLACE_CORE_TEXTDOMAIN) . "</a>
        ";
    }

    /**
     * Add a new link where the Enefti Core Dashboard is located inside the WooCommerce menu
     *
     * @return void
     * @since 2.0.0
     */
    function addItemToWoocommerceMenuItems($items): array
    {
        $my_items = array(
            'manage-your-nfts' => __('Enefti Core Dashboard', NFT_MARKETPLACE_CORE_TEXTDOMAIN),
        );

        return array_slice($items, 0, 1, true) +
            $my_items +
            array_slice($items, 1, count($items), true);
    }
}
