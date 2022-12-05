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

/**
 * Get the settings of the plugin in a filterable way
 *
 * @since 2.0.0
 * @return array
 */
function nft_marketplace_core_get_settings() {
	return apply_filters( 'nft_marketplace_core_get_settings', get_option( NFT_MARKETPLACE_CORE_TEXTDOMAIN . '-settings' ) );
}

/**
 *
 * Helper function to prepare valid HTML input required for the frontend to call one of the contract's function
 *
 * @param $id string ID attribute, must be unique.
 * @param $functionName string The function we want to call on the contract.
 * @param $label string Label name.
 * @param $desc string Description.
 * @param $input string The HTML Input element.
 *
 * @return void
 * @since 1.0.0
 */
function nft_marketplace_core_create_input($id, $functionName, $label, $desc, $input) {
	echo '
        <div class="form-field form-required term-slug-wrap"
             data-function-name="'.$functionName.'"
             data-changed="false"
        >
            <label for="'.$id.'">'.$label.'</label>
    ';

	echo str_replace("<input", "<input name='$id' id='$id' ", $input);

	echo '
            <p class="description">'.$desc.'</p>
        </div>
    ';
}

/**
 *
 * Generate the dataset required for the Wallet to check and switch the Blockchain to the one that we need.
 * @param $termid string|string The Blockchain id
 *
 * @return string
 * @since 1.0.0
 */
function nft_marketplace_core_switch_dataset_attribute($termid) {
	return 'data-force-switch-to="'.esc_attr(json_encode(get_term_meta($termid),JSON_UNESCAPED_SLASHES)).'"';
}
