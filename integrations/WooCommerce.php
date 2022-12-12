<?php
// Silence is golden.
namespace NFT_Marketplace_Core_Lite\Integrations;

use NFT_Marketplace_Core_Lite\Engine\Base;

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
            " . esc_html__('This is the Woocommerce My account page, if you are searching for the Enefti Core Dashboard page click on ', 'nft-marketplace-core-lite') . " <a href='" . esc_url(site_url("manage-your-nfts")) . "'>" . esc_html__('this', 'nft-marketplace-core-lite') . "</a> " . esc_html__(' link.', 'nft-marketplace-core-lite') . "
        </p>
        <a class='single_nft_button button alt' href='" . esc_url(site_url("manage-your-nfts")) . "'>" . esc_html__('Enefti Core Dashboard', 'nft-marketplace-core-lite') . "</a>
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
            'manage-your-nfts' => __('Enefti Core Dashboard', 'nft-marketplace-core-lite'),
        );

        return array_slice($items, 0, 1, true) +
            $my_items +
            array_slice($items, 1, count($items), true);
    }
}
