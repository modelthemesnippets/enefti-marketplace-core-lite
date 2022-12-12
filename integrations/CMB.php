<?php

/**
 * NFT_Marketplace_Core_Lite
 *
 * @package   NFT_Marketplace_Core_Lite
 * @author    ModelTheme <support@modeltheme.com>
 * @copyright Copyright (C) 2012-2022, ModelTheme, support@modeltheme.com
 * @license   GPL v3
 * @link      https://modeltheme.com
 */

namespace NFT_Marketplace_Core_Lite\Integrations;

use NFT_Marketplace_Core_Lite\Engine\Base;
use NFT_Marketplace_Core_Lite\Integrations\NFTForm;

/**
 * All the CMB related code.
 */
class CMB extends Base {

	/**
	 * Initialize class.
	 *
	 * @return void
	 *@since 2.0.0
          */
	public function initialize() {
		parent::initialize();

		require_once NFT_MARKETPLACE_CORE_PLUGIN_ROOT . 'vendor/cmb2/init.php';
		// WPBPGen{{#if libraries_origgami__cmb2-grid}}
		require_once NFT_MARKETPLACE_CORE_PLUGIN_ROOT . 'vendor/cmb2-grid/Cmb2GridPluginLoad.php';
		// {{/if}}
        $form = new NFTForm();

        add_action( 'cmb2_after_form', [$form, 'cmb2_after_form_do_js_validation'], 10, 2 );
        add_action( 'cmb2_admin_init', [$form, 'nft_marketplace_core_author_fields'] );
        add_action( 'cmb2_admin_init', [$form, 'nft_marketplace_core_nft_listing_price'] );
        add_action( 'cmb2_admin_init', [$form, 'nft_marketplace_core_nft_listing_repeatable_stats'] );
        add_action( 'cmb2_admin_init', [$form, 'nft_marketplace_core_nft_listing_repeatable_properties'] );
        add_action( 'cmb2_admin_init', [$form, 'nft_marketplace_core_nft_listing_repeatable_levels'] );
        add_action( 'cmb2_admin_init', [$form, 'nft_marketplace_core_nft_listing_nft_data'] );
        add_action( 'cmb2_admin_init', [$form, 'nft_marketplace_core_nft_listing_crypto_prices'] );
    }

}
