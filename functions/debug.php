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

$nft_marketplace_core_debug = new WPBP_Debug( __( 'NFT Marketplace Core', 'nft-marketplace-core-lite' ) );

/**
 * Log text inside the debugging plugins.
 *
 * @param string $text The text.
 * @return void
 */
function nft_marketplace_core_log( string $text ) {
	global $nft_marketplace_core_debug;
	$nft_marketplace_core_debug->log( $text );
}
