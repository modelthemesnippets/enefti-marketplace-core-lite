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

use NFT_Marketplace_Core_Lite\Engine\Base;
use NFT_Marketplace_Core_Lite\Backend\ActDeact\DeactivatorHelper;
use NFT_Marketplace_Core_Lite\Backend\ActDeact\ActivatorHelper;

/**
 * Activate and deactivate method of the plugin and relates.
 */
class ActDeact extends Base {

	/**
	 * Initialize the class.
	 *
	 * @return void|bool
	 */
	public function initialize() {
		if ( !parent::initialize() ) {
			return;
		}

        // Activate plugin when new blog is added
		\add_action( 'wpmu_new_blog', array( $this, 'activate_new_site' ) );

		// WPBPGen{{#if system_upgrade-procedure}}
		\add_action( 'admin_init', array( $this, 'upgrade_procedure' ) );
		// {{/if}}
	}

	/**
	 * Fired when a new site is activated with a WPMU environment.
	 *
	 * @param int $blog_id ID of the new blog.
	 * @since 2.0.0
	 * @return void
	 */
	public function activate_new_site( int $blog_id ) {
		if ( 1 !== \did_action( 'wpmu_new_blog' ) ) {
			return;
		}

		\switch_to_blog( $blog_id );
		self::single_activate();
		\restore_current_blog();
	}

	/**
	 * Fired when the plugin is activated.
	 *
	 * @param bool $network_wide True if active in a multiste, false if classic site.
	 * @since 2.0.0
	 * @return void
	 */
	public static function activate( bool $network_wide ) {
		if ( \function_exists( 'is_multisite' ) && \is_multisite() ) {
			if ( $network_wide ) {
				// Get all blog ids
				/** @var array<\WP_Site> $blogs */
				$blogs = \get_sites();

				foreach ( $blogs as $blog ) {
					\switch_to_blog( (int) $blog->blog_id );
					self::single_activate();
					\restore_current_blog();
				}

				return;
			}
		}
		self::single_activate();
	}

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @param bool $network_wide True if WPMU superadmin uses
	 * "Network Deactivate" action, false if
	 * WPMU is disabled or plugin is
	 * deactivated on an individual blog.
	 * @since 2.0.0
	 * @return void
	 */
	public static function deactivate( bool $network_wide ) {
		if ( \function_exists( 'is_multisite' ) && \is_multisite() ) {
			if ( $network_wide ) {
				// Get all blog ids
				/** @var array<\WP_Site> $blogs */
				$blogs = \get_sites();

				foreach ( $blogs as $blog ) {
					\switch_to_blog( (int) $blog->blog_id );
					self::single_deactivate();
					\restore_current_blog();
				}

				return;
			}
		}

		self::single_deactivate();
	}


	// WPBPGen{{#if system_upgrade-procedure}}
	/**
	 * Upgrade procedure
	 *
	 * @return void
	 */
	public static function upgrade_procedure() {
		$version = \strval( \get_option( 'nft-marketplace-core-version' ) );

		if ( !\version_compare( NFT_MARKETPLACE_CORE_VERSION, $version, '>' ) ) {
			return;
		}

        ActivatorHelper::run();


        \update_option( 'nft-marketplace-core-version', NFT_MARKETPLACE_CORE_VERSION );
		\delete_option( 'nft-marketplace-core-lite' . '_fake-meta' );
	}
	// {{/if}}

	/**
	 * Fired for each blog when the plugin is activated.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	private static function single_activate() {

        ActivatorHelper::run();

		// WPBPGen{{#if system_upgrade-procedure}}
		self::upgrade_procedure();
		// {{/if}}
		// Clear the permalinks
		\flush_rewrite_rules();
	}

	/**
	 * Fired for each blog when the plugin is deactivated.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	private static function single_deactivate() {
        DeactivatorHelper::run();
		// Clear the permalinks
		\flush_rewrite_rules();
	}

}
