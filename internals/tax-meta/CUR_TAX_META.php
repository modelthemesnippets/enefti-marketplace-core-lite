<?php
/**
 * Plugin class
 **/
namespace NFT_Marketplace_Core_Lite\Internals\TaxMeta;

use NFT_Marketplace_Core_Lite\Engine\Base;


class CUR_TAX_META extends Base
{

    /**
     * Initialize the class.
     *
     * @return void|bool
     */
    public function initialize()
    {
        global $pagenow;

        parent::initialize();

        if (($pagenow == 'edit-tags.php' || $pagenow == 'term.php' || $pagenow == 'admin-ajax.php') && (isset($_GET['taxonomy']) && $_GET['taxonomy'] == 'nft-listing-currency')) {

            add_action('nft-listing-currency_add_form_fields', array($this, 'add_term_fields'), 10, 2);
            add_action('nft-listing-currency_edit_form_fields', array($this, 'update_term_fields'), 10, 2);
            add_action('admin_footer', array($this, 'add_script'));

        }
        add_action('created_nft-listing-currency', array($this, 'save_currency'), 10, 2);
        add_action('edited_nft-listing-currency', array($this, 'updated_currency'), 10, 2);
    }

    /*
     * Initialize the class and start calling our hooks and filters
     * @since 1.0.0
    */
    public function init()
    {

    }

    /*
     * Add a form field in the new currency page
     * @since 1.0.0
    */
    public function add_term_fields($taxonomy)
    { ?>
        <div class="form-field form-required term-slug-wrap">
            <label for="nft_marketplace_core_taxonomy_currency_symbol"><?php esc_html_e("Symbol", 'nft-marketplace-core-lite') ?></label>
            <input required name="nft_marketplace_core_taxonomy_currency_symbol" id="nft_marketplace_core_taxonomy_currency_symbol" type="text" value="" />
            <p class="description"><?php esc_html_e("Ex: BUSD, USDC, SHIB etc.", 'nft-marketplace-core-lite') ?></p>
        </div>

        <div class="form-field form-required term-slug-wrap">
            <label for="nft_marketplace_core_taxonomy_currency_contract"><?php esc_html_e("Token Address", 'nft-marketplace-core-lite') ?></label>
            <input required name="nft_marketplace_core_taxonomy_currency_contract" id="nft_marketplace_core_taxonomy_currency_contract" type="text" value="" />
            <p class="description"><?php esc_html_e("The address of your currency's contract you wold like to support.", 'nft-marketplace-core-lite') ?></p>
        </div>

        <div class="form-field form-required term-slug-wrap">
            <label for="nft_marketplace_core_taxonomy_currency_blockchain"><?php esc_html_e("Blockchain", 'nft-marketplace-core-lite') ?></label>
            <?php wp_dropdown_categories(["taxonomy" => "nft_listing_blockchains", "name" => "nft_marketplace_core_taxonomy_currency_blockchain"]) ?>
            <p class="description"><?php esc_html_e("The blockchain that currency is deployed on.", 'nft-marketplace-core-lite') ?></p>
        </div>

        <div class="form-field term-group">
            <label for="currency-image-id"><?php esc_html_e('Image', 'nft-marketplace-core-lite'); ?></label>
            <input type="hidden" id="currency-image-id" name="currency-image-id" class="custom_media_url" value="">
            <div id="currency-image-wrapper"></div>
            <p>
                <input type="button" class="button button-secondary ct_tax_media_button" id="ct_tax_media_button"
                       name="ct_tax_media_button" value="<?php esc_html_e('Add Image', 'nft-marketplace-core-lite'); ?>"/>
                <input type="button" class="button button-secondary ct_tax_media_remove" id="ct_tax_media_remove"
                       name="ct_tax_media_remove" value="<?php esc_html_e('Remove Image', 'nft-marketplace-core-lite'); ?>"/>
            </p>
        </div>
        <?php
    }

    /*
     * Save the form field
     * @since 1.0.0
    */
    public function save_currency($term_id, $tt_id)
    {
        if (isset($_POST['currency-image-id']) && '' !== $_POST['currency-image-id']) {
            $image = sanitize_text_field($_POST['currency-image-id']);
            add_term_meta($term_id, 'currency-image-id', $image, true);
        }

        add_term_meta(
            $term_id,
            'nft_marketplace_core_taxonomy_currency_symbol',
            sanitize_text_field($_POST['nft_marketplace_core_taxonomy_currency_symbol'])
        );

        if (is_numeric($_POST['nft_marketplace_core_taxonomy_currency_blockchain']) && term_exists(intval($_POST['nft_marketplace_core_taxonomy_currency_blockchain']), "nft_listing_blockchains")) {
            add_term_meta(
                $term_id,
                'nft_marketplace_core_taxonomy_currency_blockchain',
                sanitize_text_field($_POST['nft_marketplace_core_taxonomy_currency_blockchain'])
            );
        }
        add_term_meta(
            $term_id,
            'nft_marketplace_core_taxonomy_currency_contract',
            sanitize_text_field($_POST['nft_marketplace_core_taxonomy_currency_contract'])
        );
    }

    /*
     * Edit the form field
     * @since 1.0.0
    */
    public function update_term_fields($term, $taxonomy)
    { ?>
        <tr class="form-field term-group-wrap">
            <th scope="row">
                <label for="nft_marketplace_core_taxonomy_currency_symbol"><?php esc_html_e('Symbol', 'nft-marketplace-core-lite'); ?></label>
            </th>
            <td>
                <?php $symbol = get_term_meta($term->term_id, 'nft_marketplace_core_taxonomy_currency_symbol', true); ?>
                <input aria-required="true" name="nft_marketplace_core_taxonomy_currency_symbol"
                       id="nft_marketplace_core_taxonomy_currency_symbol" type="text"
                       value="<?php echo esc_attr($symbol) ?>"/>
                <p class="description"><?php esc_html_e("Ex: BUSD, USDC, SHIB etc.", 'nft-marketplace-core-lite') ?></p>
            </td>
        </tr>
        <tr class="form-field term-group-wrap">
            <th scope="row">
                <label for="nft_marketplace_core_taxonomy_currency_contract"><?php esc_html_e("Token Address", 'nft-marketplace-core-lite') ?></label>
            </th>
            <td>
                <?php $symbol = get_term_meta($term->term_id, 'nft_marketplace_core_taxonomy_currency_contract', true); ?>
                <input aria-required="true" name="nft_marketplace_core_taxonomy_currency_contract"
                       id="nft_marketplace_core_taxonomy_currency_contract" type="text"
                       value="<?php echo esc_attr($symbol) ?>"/>
                <p class="description"><?php esc_html_e("The address of your currency's contract you wold like to support.", 'nft-marketplace-core-lite') ?></p>
            </td>
        </tr>
        <tr class="form-field term-group-wrap">
            <th scope="row">
                <label for="nft_marketplace_core_taxonomy_currency_blockchain"><?php esc_html_e('Blockchain', 'nft-marketplace-core-lite'); ?></label>
            </th>
            <td>
                <?php
                $symbol = get_term_meta($term->term_id, 'nft_marketplace_core_taxonomy_currency_blockchain', true);
                ?>
                <?php wp_dropdown_categories(["taxonomy" => "nft_listing_blockchains", "name" => "nft_marketplace_core_taxonomy_currency_blockchain"]) ?>
                <p class="description"><?php esc_html_e("The blockchain that currency is deployed on.", 'nft-marketplace-core-lite') ?></p>
            </td>
        </tr>
        <tr class="form-field term-group-wrap">
            <th scope="row">
                <label for="currency-image-id"><?php esc_html_e('Image', 'nft-marketplace-core-lite'); ?></label>
            </th>
            <td>
                <?php $image_id = get_term_meta($term->term_id, 'currency-image-id', true); ?>
                <input type="hidden" id="currency-image-id"
                       name="currency-image-id"
                       value="<?php echo esc_attr($image_id); ?>">
                <div id="blockchain-image-wrapper">
                    <?php if ($image_id) { ?>
                        <?php echo wp_get_attachment_image($image_id, 'thumbnail'); ?>
                    <?php } ?>
                </div>
                <p>
                    <input type="button" class="button button-secondary ct_tax_media_button" id="ct_tax_media_button"
                           name="ct_tax_media_button"
                           value="<?php esc_html_e('Add Image', 'nft-marketplace-core-lite'); ?>"/>
                    <input type="button" class="button button-secondary ct_tax_media_remove" id="ct_tax_media_remove"
                           name="ct_tax_media_remove"
                           value="<?php esc_html_e('Remove Image', 'nft-marketplace-core-lite'); ?>"/>
                </p>
            </td>
        </tr>
        <?php
    }

    /*
     * Update the form field value
     * @since 1.0.0
     */
    public function updated_currency($term_id, $tt_id)
    {
        global $pagenow;

        if (isset($_POST['currency-image-id']) && '' !== $_POST['currency-image-id']) {
            $image = sanitize_text_field($_POST['currency-image-id']);
            update_term_meta($term_id, 'currency-image-id', $image);
        } else {
            update_term_meta($term_id, 'currency-image-id', '');
        }

        update_term_meta(
            $term_id,
            'nft_marketplace_core_taxonomy_currency_symbol',
            sanitize_text_field($_POST['nft_marketplace_core_taxonomy_currency_symbol'])
        );

        if (is_numeric($_POST['nft_marketplace_core_taxonomy_currency_blockchain']) && term_exists(intval($_POST['nft_marketplace_core_taxonomy_currency_blockchain']), "nft_listing_blockchains")) {
            update_term_meta(
                $term_id,
                'nft_marketplace_core_taxonomy_currency_blockchain',
                sanitize_text_field($_POST['nft_marketplace_core_taxonomy_currency_blockchain'])
            );
        }
        update_term_meta(
            $term_id,
            'nft_marketplace_core_taxonomy_currency_contract',
            sanitize_text_field($_POST['nft_marketplace_core_taxonomy_currency_contract'])
        );
    }

    /*
     * Add script
     * @since 1.0.0
     */
    public function add_script()
    { ?>
        <script>
            jQuery(document).ready(function ($) {
                "use strict"

                function ct_media_upload(button_class) {
                    var _custom_media = true,
                        _orig_send_attachment = wp.media.editor.send.attachment;
                    $('body').on('click', button_class, function (e) {
                        var button_id = '#' + $(this).attr('id');
                        var send_attachment_bkp = wp.media.editor.send.attachment;
                        var button = $(button_id);
                        _custom_media = true;
                        wp.media.editor.send.attachment = function (props, attachment) {
                            if (_custom_media) {
                                $('#currency-image-id').val(attachment.id);
                                $('#currency-image-wrapper').html('<img class="custom_media_image" src="" style="margin:0;padding:0;max-height:100px;float:none;" />');
                                $('#currency-image-wrapper .custom_media_image').attr('src', attachment.sizes.thumbnail.url).css('display', 'block');
                            } else {
                                return _orig_send_attachment.apply(button_id, [props, attachment]);
                            }
                        }
                        wp.media.editor.open(button);
                        return false;
                    });
                }

                ct_media_upload('.ct_tax_media_button.button');
                $('body').on('click', '.ct_tax_media_remove', function () {
                    $('#currency-image-id').val('');
                    $('#currency-image-wrapper').html('<img class="custom_media_image" src="" style="margin:0;padding:0;max-height:100px;float:none;" />');
                });
                // Thanks: http://stackoverflow.com/questions/15281995/wordpress-create-currency-ajax-response
                $(document).ajaxComplete(function (event, xhr, settings) {
                    var queryStringArr = settings.data.split('&');
                    if ($.inArray('action=add-tag', queryStringArr) !== -1) {
                        var xml = xhr.responseXML;
                        $response = $(xml).find('term_id').text();
                        if ($response != "") {
                            // Clear the thumb image
                            $('#currency-image-wrapper').html('');
                        }
                    }
                });
            });
        </script>
    <?php }

}
