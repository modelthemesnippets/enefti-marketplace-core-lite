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

/**
 * Get the settings of the plugin in a filterable way
 *
 * @since 2.0.0
 * @return array
 */
function nft_marketplace_core_get_settings() {
	return apply_filters( 'nft_marketplace_core_get_settings', get_option( 'nft-marketplace-core-lite' . '-settings' ) );
}

