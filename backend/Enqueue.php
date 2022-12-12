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

namespace NFT_Marketplace_Core_Lite\Backend;

use Inpsyde\Assets\Asset;
use Inpsyde\Assets\AssetManager;
use Inpsyde\Assets\Script;
use Inpsyde\Assets\Style;
// {{/if}}
use NFT_Marketplace_Core_Lite\Engine\Base;

/**
 * This class contain the Enqueue stuff for the backend
 */
class Enqueue extends Base {

	/**
	 * Initialize the class.
	 *
	 * @return void|bool
	 */
	public function initialize() {
		if ( !parent::initialize() ) {
			return;
		}

		\add_action( AssetManager::ACTION_SETUP, array( $this, 'enqueue_assets' ) );
        \add_filter('script_loader_tag', [$this, 'addTypeAttribute'], 10, 3);
    }

	/**
	 * Enqueue assets with Inpyside library https://inpsyde.github.io/assets
	 *
	 * @param \Inpsyde\Assets\AssetManager $asset_manager The class.
	 * @return void
	 */
	public function enqueue_assets( AssetManager $asset_manager ) {
		$assets = $this->enqueue_admin_styles();

		if ( !empty( $assets ) ) {
			foreach ( $assets as $asset ) {
				$asset_manager->register( $asset );
			}
		}

		$assets = $this->enqueue_admin_scripts();

		if ( !empty( $assets ) ) {
			foreach ( $assets as $asset ) {
				$asset_manager->register( $asset );
			}
		}
	}

	/**
	 * Register and enqueue admin-specific style sheet.
	 *
	 * @since 2.0.0
	 * @return array
	 */
	public function enqueue_admin_styles() {
		$styles     = array();

		wp_enqueue_style(NFT_MARKETPLACE_CORE_NAME, plugin_dir_url(__FILE__) . 'assets/css/nft-marketplace-core-admin.css', array(), NFT_MARKETPLACE_CORE_VERSION, 'all');
		wp_enqueue_style(NFT_MARKETPLACE_CORE_NAME, plugin_dir_url(__FILE__) . 'assets/css/nft-marketplace-core-panel.css', array(), NFT_MARKETPLACE_CORE_VERSION, 'all');

		return $styles;
	}

	/**
	 * Register and enqueue admin-specific JavaScript.
	 *
	 * @since 2.0.0
	 * @return array
	 */
	public function enqueue_admin_scripts() {
		$scripts    = array();
		wp_enqueue_script(NFT_MARKETPLACE_CORE_NAME, plugin_dir_url(__FILE__) . 'assets/js/nft-marketplace-core-admin.js', array('jquery'), NFT_MARKETPLACE_CORE_VERSION, false);

        return $scripts;
	}

	// {{/if}}
    function addTypeAttribute($tag, $handle, $src)
    {
        // if not your script, do nothing and return original $tag
        if ('nft-marketplace-core-lite' . "-market" !== $handle) {
            return $tag;
        }
        // change the script tag by adding type="module" and return it.
        return '<script type="module" src="' . esc_url($src) . '" id="'.'nft-marketplace-core-lite' . "-market".'" ></script>';
    }
}
