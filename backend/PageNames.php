<?php
namespace NFT_Marketplace_Core\Backend;

use NFT_Marketplace_Core\Engine\Base;

/**
 * NFT Marketplace Core
 *
 * @package   NFT Marketplace Core
 * @author    ModelTheme <support@modeltheme.com>
 * @copyright Copyright (C) 2012-2022, Modeltheme, support@modeltheme.com
 * @license   GPL-3.0
 * @link      https://modeltheme.com
 */
class PageNames extends Base {

	/**
	 * Initialize the class.
	 *
	 * @return void|bool
	 */
	public function initialize() {
		if ( ! parent::initialize() ) {
			return;
		}

		\add_action( 'display_post_states', array( $this, 'show_page_descriptions' ), 10, 2  );
	}

	public function show_page_descriptions($post_states, $post) {
		if( $post->post_name == "create-digital-asset" ) {
			$post_states[] = 'Create NFTs Page (Enefti)';
		} elseif($post->post_name == "nft-listings" ) {
			$post_states[] = 'All NFTs Page (Enefti)';
		} elseif($post->post_name == "manage-your-nfts" ) {
			$post_states[] = 'Vendor Panel Page (Enefti)';
		} elseif($post->post_name == "connect-your-wallet" ) {
			$post_states[] = 'Login/Register Page (Enefti)';
		}
		return $post_states;
	}
}
