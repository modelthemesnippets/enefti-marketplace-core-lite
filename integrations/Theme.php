<?php
// Silence is golden.
namespace NFT_Marketplace_Core\Integrations;

use NFT_Marketplace_Core\Engine\Base;

class Theme extends Base
{
    /**
     * Initialize the class.
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        add_action("enefti_after_vendor_dashboard_link", [$this, "accountMenuLinkToManageYourNFTs"]);
    }

    /**
     * Add a new menu item inside our theme's account dropdown.
     * @return void
     * @since 2.0.0
     */
    public function accountMenuLinkToManageYourNFTs()
    {
        echo '
            <li><a href="' . esc_url(site_url("manage-your-nfts")) . '"><i class="icon-diamond icons"></i> ' . esc_html__('Enefti Core Dashboard', NFT_MARKETPLACE_CORE_TEXTDOMAIN) . '</a></li>
        ';
    }

}
