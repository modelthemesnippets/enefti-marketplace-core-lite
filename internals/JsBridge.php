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

namespace NFT_Marketplace_Core\Internals;

use NFT_Marketplace_Core\Engine\Base;

/**
 * Provides important variables for the frontend. (both at the user end and at admin end)
 *
 * @package   NFT Marketplace Core
 * @author    ModelTheme <support@modeltheme.com>
 * @copyright Copyright (C) 2012-2022, Modeltheme, support@modeltheme.com
 * @license   GPL-3.0
 * @link      https://modeltheme.com
 */
class JsBridge extends Base
{
    /**
     * Initialize the class and get the plugin settings
     *
     * @return bool
     */
    public function initialize()
    {
        parent::initialize();

        add_action('admin_head', [$this, "provideVariables"]);
        add_action('wp_head', [$this, "provideVariables"]);

        add_action('admin_footer', [$this, "loadDev"]);
        add_action('wp_footer', [$this, "loadDev"]);
    }

    /**
     * Provide data for frontend
     * @return void
     */
    public function provideVariables()
    {
        $isLoggedInWithMetamask = function_exists("mtm_metamask_current_user_has_metamask") && is_user_logged_in() && mtm_metamask_current_user_has_metamask();
        $isLoggedInWithWalletConnect = function_exists("mtwc_walletconnect_current_user_has_walletconnect") && is_user_logged_in() && mtwc_walletconnect_current_user_has_walletconnect();
        $infuraId = function_exists("mtwc_walletconnect_current_user_has_walletconnect") ? "\"".get_option( 'mtwc_auth_option_name' )['infura_id']."\"" : 'undefined';
        ?>
        <script type="text/javascript" id="nft-marketplace-core-vars">
            window.nftMarketplaceCore = {};
            window.nftMarketplaceCore.isLoggedInWithMetamask = <?php echo var_export($isLoggedInWithMetamask || $isLoggedInWithWalletConnect, true) ?>;
            window.nftMarketplaceCore.rpcUrls = <?php echo self::getRPCList() ?>;
            window.nftMarketplaceCore.hasMultipleWalletsInstalled =  <?php echo var_export(function_exists("mtwc_walletconnect_current_user_has_walletconnect") && function_exists("mtm_metamask_current_user_has_metamask"), true) ?>;
            window.nftMarketplaceCore.apiLocation = "<?php echo get_rest_url(); ?>";
            window.nftMarketplaceCore.pluginLocation = "<?php echo plugin_dir_url(__DIR__); ?>";
            window.nftMarketplaceCore.nonce = "<?php echo wp_create_nonce('wp_rest'); ?>";
            window.nftMarketplaceCore.infuraId = <?php echo $infuraId;  ?>;
        </script>
        <?php
    }

    /**
     * Returns all RPCs as a list.
     *
     * Used mostly for interacting with WalletConnect.
     * @TODO use for wallet switching.
     *
     * @return false|string
     * @since 2.0.0
     */
    public static function getRPCList()
    {
        $rpc_list = [];

        $terms = get_terms(array(
            'taxonomy' => 'nft_listing_blockchains',
            'hide_empty' => false,
        ));

        foreach ($terms as $term) {
            $rpc_url = get_term_meta($term->term_id, "nft_marketplace_core_taxonomy_blockchain_currency_rpc_url", true);
            $chainid = get_term_meta($term->term_id, "nft_marketplace_core_taxonomy_blockchain_currency_chainid", true);
            $rpc_list[hexdec($chainid)] = $rpc_url;
        }

        return json_encode($rpc_list);
    }


    /**
     * Load dev environment.
     * @return void
     */
    public function loadDev()
    {
        $path = plugin_dir_path(dirname(__DIR__)) . "dev-do-not-upload/Vite.php";
        if (file_exists($path)) {
            require_once $path;
            $vite = new \Vite();
            echo $vite;
        }
    }
}
