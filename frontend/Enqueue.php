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

namespace NFT_Marketplace_Core\Frontend;

// WPBPGen{{#if libraries_inpsyde__assets}}
use Inpsyde\Assets\Asset;
use Inpsyde\Assets\AssetManager;
use Inpsyde\Assets\Script;
use Inpsyde\Assets\Style;

// {{/if}}
use NFT_Marketplace_Core\Engine\Base;

/**
 * Enqueue stuff on the frontend
 */
class Enqueue extends Base
{

    /**
     * Initialize the class.
     *
     * @return void|bool
     */
    public function initialize()
    {
        parent::initialize();

        // WPBPGen{{#if libraries_inpsyde__assets}}
        \add_action(AssetManager::ACTION_SETUP, array($this, 'enqueue_assets'));
        // {{/if}}
        \add_action('wp_enqueue_scripts', [$this, 'nft_marketplace_core_dynamic_css']);
        \add_filter('script_loader_tag', [$this, 'addTypeAttribute'], 10, 3);

    }

    // WPBPGen{{#if libraries_inpsyde__assets}}

    /**
     * Enqueue assets with Inpyside library https://inpsyde.github.io/assets
     *
     * @param \Inpsyde\Assets\AssetManager $asset_manager The class.
     * @return void
     */
    public function enqueue_assets(AssetManager $asset_manager)
    {
        // Load public-facing style sheet and JavaScript.
        // WPBPGen{{#if public-assets_css}}
        $assets = $this->enqueue_styles();

        if (!empty($assets)) {
            foreach ($assets as $asset) {
                $asset_manager->register($asset);
            }
        }

        // {{/if}}
        // WPBPGen{{#if public-assets_js}}
        $assets = $this->enqueue_scripts();

        if (!empty($assets)) {
            foreach ($assets as $asset) {
                $asset_manager->register($asset);
            }
        }
        // {{/if}}
    }
    // {{/if}}

    // WPBPGen{{#if public-assets_css}}
    /**
     * Register and enqueue public-facing style sheet.
     *
     * @return array
     * @since 2.0.0
     */
    public function enqueue_styles()
    {
        $styles = array();

        wp_enqueue_style('bootstrap-grid', plugin_dir_url(__DIR__) . 'assets/css/bootstrap-grid.css', [], NFT_MARKETPLACE_CORE_VERSION, 'all');
        wp_enqueue_style('font-awesome5', plugin_dir_url(__DIR__) . 'assets/css/fonts/font-awesome/all.min.css', [], '5.15.4', 'all');
        wp_enqueue_style(NFT_MARKETPLACE_CORE_TEXTDOMAIN . "-alerts", plugin_dir_url(__DIR__) . 'assets/css/nft-marketplace-core-alerts.css', [], NFT_MARKETPLACE_CORE_VERSION, 'all');
        wp_enqueue_style(NFT_MARKETPLACE_CORE_TEXTDOMAIN, plugin_dir_url(__DIR__) . 'assets/css/nft-marketplace-core-public.css', [], NFT_MARKETPLACE_CORE_VERSION, 'all');
        wp_enqueue_style(NFT_MARKETPLACE_CORE_TEXTDOMAIN . "-loader", plugin_dir_url(__DIR__) . 'assets/css/nft-marketplace-core-loader.css', [], NFT_MARKETPLACE_CORE_VERSION, 'all');
        if (isset(get_option('nft_marketplace_core_panel_styling')['nft_marketplace_core_dark']) && get_option('nft_marketplace_core_panel_styling')['nft_marketplace_core_dark'] == 'on') {
            wp_enqueue_style('dark-mode', plugin_dir_url(__DIR__) . 'assets/css/nft-marketplace-core-dark-mode.css', [], NFT_MARKETPLACE_CORE_VERSION, 'all');
        }
        return $styles;
    }

    // {{/if}}
    // WPBPGen{{#if public-assets_js}}

    /**
     * Register and enqueues public-facing JavaScript files.
     *
     * @return array
     * @since 2.0.0
     */
    public static function enqueue_scripts()
    {
        $scripts = array();

        wp_enqueue_script(NFT_MARKETPLACE_CORE_TEXTDOMAIN, plugin_dir_url(__DIR__) . 'assets/js/nft-marketplace-core-public.js', array('jquery'), NFT_MARKETPLACE_CORE_VERSION, false);

        wp_localize_script(NFT_MARKETPLACE_CORE_TEXTDOMAIN, 'ajax_search', array(
            'url' => admin_url('admin-ajax.php'),
        ));
        wp_localize_script(NFT_MARKETPLACE_CORE_TEXTDOMAIN, 'ajax_var', array(
            'url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('ajax-nonce')
        ));

        $scripts[0] = new Script(NFT_MARKETPLACE_CORE_TEXTDOMAIN . "-market", plugin_dir_url(__DIR__) . 'assets/js/blockchain-interaction/assets/main.78435a22.js', Asset::FRONTEND);
        $scripts[0]->withAttributes(["type" => "module"]);
        $scripts[0]->withTranslation(NFT_MARKETPLACE_CORE_TEXTDOMAIN . "-market", NFT_MARKETPLACE_CORE_TEXTDOMAIN);
        $scripts[0]->withLocalize('nftMarketplaceCorelocation', array(
            'location' => plugin_dir_url(__DIR__),
        ));

        return $scripts;
    }

    // {{/if}}

    function addTypeAttribute($tag, $handle, $src)
    {
        // if not your script, do nothing and return original $tag
        if (NFT_MARKETPLACE_CORE_TEXTDOMAIN . "-market" !== $handle) {
            return $tag;
        }
        // change the script tag by adding type="module" and return it.
        $tag = '<script type="module" src="' . esc_url($src) . '" id="'.NFT_MARKETPLACE_CORE_TEXTDOMAIN . "-market".'" ></script>';
        return $tag;
    }

    //Custom Colors
    function nft_marketplace_core_dynamic_css()
    {
        $html = '';
        wp_enqueue_style(
            'nft-marketplace-core-css',
            plugin_dir_url(__DIR__) . 'assets/css/nft-marketplace-core-custom-editor.css'
        );
        if (isset(get_option('nft_marketplace_core_panel_styling')['nft_marketplace_core_main_text_color']) && get_option('nft_marketplace_core_panel_styling')['nft_marketplace_core_main_text_color'] != '') {
            $nft_links_color = get_option('nft_marketplace_core_panel_styling')['nft_marketplace_core_main_text_color'];
        } else {
            $nft_links_color = '#D01498';
        }
        if (isset(get_option('nft_marketplace_core_panel_styling')['nft_marketplace_core_main_hover_color']) && get_option('nft_marketplace_core_panel_styling')['nft_marketplace_core_main_hover_color'] != '') {
            $nft_hover_links_color = get_option('nft_marketplace_core_panel_styling')['nft_marketplace_core_main_hover_color'];
        } else {
            $nft_hover_links_color = '#D01498';
        }
        if (isset(get_option('nft_marketplace_core_panel_styling')['nft_marketplace_core_main_button_bg']) && get_option('nft_marketplace_core_panel_styling')['nft_marketplace_core_main_button_bg'] != '') {
            $nft_button_bg = get_option('nft_marketplace_core_panel_styling')['nft_marketplace_core_main_button_bg'];
        } else {
            $nft_button_bg = '#D01498';
        }
        if (isset(get_option('nft_marketplace_core_panel_styling')['nft_marketplace_core_main_button_bg_hover']) && get_option('nft_marketplace_core_panel_styling')['nft_marketplace_core_main_button_bg_hover'] != '') {
            $nft_button_bg_hover = get_option('nft_marketplace_core_panel_styling')['nft_marketplace_core_main_button_bg_hover'];
        } else {
            $nft_button_bg_hover = '#222';
        }
        $html .= '
		    .author-details-wrapper a.btn-edit,
		    .author-details-wrapper .author-edit-title a,
		    body .nft-listing-collection a,
		    .author-socials li a,
		    body .single-collection-title a {
				color: ' . esc_html($nft_links_color) . ';
			}
			.nft-listing-meta .meta-category a:hover,
			.nft-listing-collection a:hover,
			.nft-listing-name a:hover,
			#sidebar .nft-marketplace-categories li a:hover,
			.author-details-wrapper a.btn-edit:hover,
			.author-details-wrapper .author-edit-title a:hover,
			.author-socials li a:hover {
				color: ' . esc_html($nft_hover_links_color) . ' !important;
			}
			.nft-listing .nft-listing-image a.button,
			.nft-entry-summary button.single_nft_button,
			.author-edit-fields-wrapper button.button-listing {
				background: ' . esc_html($nft_button_bg) . ' !important;
			}
			.nft-listing .nft-listing-image a.button:hover,
			.nft-entry-summary button.single_nft_button:hover,
			.author-edit-fields-wrapper button.button-listing:hover {
				background: ' . esc_html($nft_button_bg_hover) . ' !important;
			}
		';
        wp_add_inline_style('nft-marketplace-core-css', $html);
    }
}
