<?php

/**
 * NFT_Marketplace_Core
 *
 * @package   NFT_Marketplace_Core
 * @author    ModelTheme <support@modeltheme.com>
 * @copyright Copyright (C) 2012-2022, ModelTheme, support@modeltheme.com
 * @license   GPL v3
 * @link      https://modeltheme.com
 */

namespace NFT_Marketplace_Core\Engine;

/**
 * Base skeleton of the plugin
 */
class Base {

	/**
	 * @var array The settings of the plugin.
	 */
	public $settings = array();

	/**
	 * Initialize the class and get the plugin settings
	 *
	 * @return bool
	 */
	public function initialize() {
		$this->settings = \nft_marketplace_core_get_settings();

		return true;
	}

}
