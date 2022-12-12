<?php

/**
 * @package   NFT_Marketplace_Core_Lite
 * @author    ModelTheme <support@modeltheme.com>
 * @copyright Copyright (C) 2012-2022, ModelTheme, support@modeltheme.com
 * @license   GPL v3
 * @link      https://modeltheme.com
 *
 * Plugin Name:     Enefti - NFT Marketplace Core Lite
 * Plugin URI:      https://modeltheme.com/enefti-lite-vs-enefti-core/
 * Description:     Light version of a NFT Marketplace in WordPress has never been this easy.
 * Version:         1.0.0
 * Author:          ModelTheme
 * Author URI:      https://modeltheme.com
 * Text Domain:     nft-marketplace-core-lite
 * License:         GPL v3
 * License URI:     http://www.gnu.org/licenses/gpl-3.0.txt
 * Domain Path:     /languages
 * Requires PHP:    7.0
 * WordPress-Plugin-Boilerplate-Powered: v3.3.0
 */

// If this file is called directly, abort.
if ( !defined( 'ABSPATH' ) ) {
	die( 'We\'re sorry, but you can not directly access this file.' );
}

define( 'NFT_MARKETPLACE_CORE_VERSION', '1.0.0' );
define( 'NFT_MARKETPLACE_CORE_NAME', 'NFT Marketplace Core' );
define( 'NFT_MARKETPLACE_CORE_PLUGIN_ROOT', plugin_dir_path( __FILE__ ) );
define( 'NFT_MARKETPLACE_CORE_PLUGIN_ABSOLUTE', __FILE__ );
define( 'NFT_MARKETPLACE_CORE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'NFT_MARKETPLACE_CORE_PLUGIN_LOCATION', str_replace(WP_PLUGIN_DIR."/", "", __DIR__) );
define( 'NFT_MARKETPLACE_CORE_MIN_PHP_VERSION', '7.0' );
define( 'NFT_MARKETPLACE_CORE_WP_VERSION', '5.3' );

// WPBPGen{{#if language-files}}
add_action(
	'init',
	static function () {
		load_plugin_textdomain( 'nft-marketplace-core-lite', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}
);

// {{/if}}
if ( version_compare( PHP_VERSION, NFT_MARKETPLACE_CORE_MIN_PHP_VERSION, '<=' ) ) {
	add_action(
		'admin_init',
		static function() {
			deactivate_plugins( plugin_basename( __FILE__ ) );
		}
	);
	add_action(
		'admin_notices',
		static function() {
			echo wp_kses_post(
			sprintf(
				'<div class="notice notice-error"><p>%s</p></div>',
				__( '"NFT Marketplace Core" requires PHP 5.6 or newer.', 'nft-marketplace-core-lite' )
			)
			);
		}
	);

	// Return early to prevent loading the plugin.
	return;
}

$nft_marketplace_core_libraries = require NFT_MARKETPLACE_CORE_PLUGIN_ROOT . 'vendor/autoload.php'; //phpcs:ignore

require_once NFT_MARKETPLACE_CORE_PLUGIN_ROOT . 'functions/functions.php';
// WPBPGen{{#if libraries_wpbp__debug}}
require_once NFT_MARKETPLACE_CORE_PLUGIN_ROOT . 'functions/debug.php';
// {{/if}}

// Add your new plugin on the wiki: https://github.com/WPBP/WordPress-Plugin-Boilerplate-Powered/wiki/Plugin-made-with-this-Boilerplate

// WPBPGen{{#if libraries_micropackage__requirements}}
$requirements = new \Micropackage\Requirements\Requirements(
	'NFT Marketplace Core',
	array(
		'php'            => NFT_MARKETPLACE_CORE_MIN_PHP_VERSION,
		'php_extensions' => array( 'mbstring' ),
		'wp'             => NFT_MARKETPLACE_CORE_WP_VERSION,
	)
);

if ( ! $requirements->satisfied() ) {
	$requirements->print_notice();

	return;
}

// {{/if}}

if ( ! wp_installing() ) {
	register_activation_hook( 'nft-marketplace-core-lite' . '/' . 'nft-marketplace-core-lite' . '.php', array( new \NFT_Marketplace_Core_Lite\Backend\ActDeact, 'activate' ) );
	register_deactivation_hook( 'nft-marketplace-core-lite' . '/' . 'nft-marketplace-core-lite' . '.php', array( new \NFT_Marketplace_Core_Lite\Backend\ActDeact, 'deactivate' ) );
	add_action(
		'plugins_loaded',
		static function () use ( $nft_marketplace_core_libraries ) {
			new \NFT_Marketplace_Core_Lite\Engine\Initialize( $nft_marketplace_core_libraries );
		}
	);
}

require_once NFT_MARKETPLACE_CORE_PLUGIN_ROOT . 'internals/shortcodes/shortcodes.php';
