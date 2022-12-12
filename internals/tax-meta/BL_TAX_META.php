<?php
/**
 * Plugin class
 **/
namespace NFT_Marketplace_Core_Lite\Internals\TaxMeta;

use NFT_Marketplace_Core_Lite\Engine\Base;


class BL_TAX_META extends Base
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
        if (($pagenow == 'edit-tags.php' || $pagenow == 'term.php' || $pagenow == 'admin-ajax.php') && (isset($_GET['taxonomy']) && $_GET['taxonomy'] == 'nft_listing_blockchains')) {

            add_action('nft_listing_blockchains_add_form_fields', array($this, 'add_term_fields'), 10, 2);
            add_action('nft_listing_blockchains_edit_form_fields', array($this, 'update_term_fields'), 10, 2);
            add_action('admin_footer', array($this, 'add_script'));

        }
        add_action('created_nft_listing_blockchains', array($this, 'save_blockchain_image'), 10, 2);
        add_action('edited_nft_listing_blockchains', array($this, 'updated_blockchain_image'), 10, 2);
    }

    /*
     * Add a form field in the new blockchain page
     * @since 1.0.0
    */
    public function add_blockchain_image()
    { ?>
        <div class="form-field term-group">
            <label for="nft_marketplace_core_taxonomy_blockchain_currency_image-"><?php esc_html_e('Image', 'nft-marketplace-core-lite'); ?></label>
            <input type="hidden" id="nft_marketplace_core_taxonomy_blockchain_currency_image"
                   name="nft_marketplace_core_taxonomy_blockchain_currency_image" class="custom_media_url" value="">
            <div id="blockchain-image-wrapper"></div>
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
    public function save_blockchain_image($term_id, $tt_id)
    {
        add_term_meta(
            $term_id,
            'nft_marketplace_core_taxonomy_blockchain_currency_rpc_url',
            sanitize_text_field($_POST['nft_marketplace_core_taxonomy_blockchain_currency_rpc_url'])
        );
        add_term_meta(
            $term_id,
            'nft_marketplace_core_taxonomy_blockchain_currency_symbol',
            sanitize_text_field($_POST['nft_marketplace_core_taxonomy_blockchain_currency_symbol'])
        );
        add_term_meta(
            $term_id,
            'nft_marketplace_core_taxonomy_blockchain_currency_chainid',
            sanitize_text_field('0x' . dechex($_POST['nft_marketplace_core_taxonomy_blockchain_currency_chainid']))
        );
        if (isset($_POST['nft_marketplace_core_taxonomy_blockchain_currency_image']) && '' !== $_POST['nft_marketplace_core_taxonomy_blockchain_currency_image']) {
            $image = sanitize_text_field($_POST['nft_marketplace_core_taxonomy_blockchain_currency_image']);
            add_term_meta($term_id, 'nft_marketplace_core_taxonomy_blockchain_currency_image', $image, true);
        }
    }

    /*
     * Edit the form field
     * @since 1.0.0
    */
    public function update_blockchain_image($term, $taxonomy)
    { ?>
        <tr class="form-field term-group-wrap">
            <th scope="row">
                <label for="nft_marketplace_core_taxonomy_blockchain_currency_image"><?php esc_html_e('Image', 'nft-marketplace-core-lite'); ?></label>
            </th>
            <td>
                <?php $image_id = get_term_meta($term->term_id, 'nft_marketplace_core_taxonomy_blockchain_currency_image', true); ?>
                <input type="hidden" id="nft_marketplace_core_taxonomy_blockchain_currency_image"
                       name="nft_marketplace_core_taxonomy_blockchain_currency_image"
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

    function update_term_fields($term)
    { ?>
        <tr class="form-field term-group-wrap">
            <th scope="row">
                <label for="nft_marketplace_core_taxonomy_blockchain_currency_symbol"><?php esc_html_e('Currency Symbol', 'nft-marketplace-core-lite'); ?></label>
            </th>
            <td>
                <?php $symbol = get_term_meta($term->term_id, 'nft_marketplace_core_taxonomy_blockchain_currency_symbol', true); ?>
                <input aria-required="true" name="nft_marketplace_core_taxonomy_blockchain_currency_symbol"
                       id="nft_marketplace_core_taxonomy_blockchain_currency_symbol" type="text"
                       value="<?php echo esc_attr($symbol) ?>"/>
                <p class="description"><?php echo esc_html__("Default currency symbol for this blockchain. Ex: ETH, MATIC etc.", 'nft-marketplace-core-lite'); ?></p>
            </td>
        </tr>
        <tr class="form-field term-group-wrap">
            <th scope="row">
                <label for="nft_marketplace_core_taxonomy_blockchain_currency_chainid"><?php esc_html_e('Chain ID', 'nft-marketplace-core-lite'); ?></label>
            </th>
            <td>
                <?php $symbol = get_term_meta($term->term_id, 'nft_marketplace_core_taxonomy_blockchain_currency_chainid', true); ?>
                <input aria-required="true" name="nft_marketplace_core_taxonomy_blockchain_currency_chainid"
                       id="nft_marketplace_core_taxonomy_blockchain_currency_chainid" type="text"
                       value="<?php echo esc_attr($symbol) ?>"/>
                <p class="description"><?php esc_html_e("The value will be automatically converted to the right format (hex).", 'nft-marketplace-core-lite'); ?></p>
            </td>
        </tr>
        <tr class="form-field term-group-wrap">
            <th scope="row">
                <label for="nft_marketplace_core_taxonomy_blockchain_currency_rpc_url"><?php esc_html_e('RPC Url', 'nft-marketplace-core-lite'); ?></label>
            </th>
            <td>
                <?php $symbol = get_term_meta($term->term_id, 'nft_marketplace_core_taxonomy_blockchain_currency_rpc_url', true); ?>
                <input aria-required="true" name="nft_marketplace_core_taxonomy_blockchain_currency_rpc_url"
                       id="nft_marketplace_core_taxonomy_blockchain_currency_rpc_url" type="text"
                       value="<?php echo esc_attr($symbol) ?>"/>
            </td>
        </tr>
        <tr class="form-field term-group-wrap">
            <th scope="row">
                <label for="nft_marketplace_core_taxonomy_blockchain_currency_image"><?php esc_html_e('Currency Image', 'nft-marketplace-core-lite'); ?></label>
            </th>
            <td>
                <?php $image_id = get_term_meta($term->term_id, 'nft_marketplace_core_taxonomy_blockchain_currency_image', true); ?>
                <input type="hidden" id="nft_marketplace_core_taxonomy_blockchain_currency_image"
                       name="nft_marketplace_core_taxonomy_blockchain_currency_image"
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
    function updated_blockchain_image($term_id)
    {
        update_term_meta(
            $term_id,
            'nft_marketplace_core_taxonomy_blockchain_currency_rpc_url',
            sanitize_text_field($_POST['nft_marketplace_core_taxonomy_blockchain_currency_rpc_url'])
        );
        // delete later
        function startsWith($string, $startString)
        {
            $len = strlen($startString);
            return (substr($string, 0, $len) === $startString);
        }

        if (startsWith($_POST['nft_marketplace_core_taxonomy_blockchain_currency_chainid'], "0x")) {
            update_term_meta(
                $term_id,
                'nft_marketplace_core_taxonomy_blockchain_currency_chainid',
                sanitize_text_field($_POST['nft_marketplace_core_taxonomy_blockchain_currency_chainid'])
            );
        } else {
            update_term_meta(
                $term_id,
                'nft_marketplace_core_taxonomy_blockchain_currency_chainid',
                sanitize_text_field('0x' . dechex($_POST['nft_marketplace_core_taxonomy_blockchain_currency_chainid']))
            );
        }

        update_term_meta(
            $term_id,
            'nft_marketplace_core_taxonomy_blockchain_currency_symbol',
            sanitize_text_field($_POST['nft_marketplace_core_taxonomy_blockchain_currency_symbol'])
        );

        if (isset($_POST['nft_marketplace_core_taxonomy_blockchain_currency_image']) && '' !== $_POST['nft_marketplace_core_taxonomy_blockchain_currency_image']) {
            $image = sanitize_text_field($_POST['nft_marketplace_core_taxonomy_blockchain_currency_image']);
            update_term_meta($term_id, 'nft_marketplace_core_taxonomy_blockchain_currency_image', $image);
        } else {
            update_term_meta($term_id, 'nft_marketplace_core_taxonomy_blockchain_currency_image', '');
        }
    }

    function add_term_fields($term)
    {
        echo '
            <div class="form-field form-required term-slug-wrap">
                <label for="nft_marketplace_core_taxonomy_blockchain_currency_symbol">' . esc_html__("Symbol", 'nft-marketplace-core-lite') . '</label>
                <input required name="nft_marketplace_core_taxonomy_blockchain_currency_symbol" id="nft_marketplace_core_taxonomy_blockchain_currency_symbol" type="text" value="" />
                <p class="description">' . esc_html__("Ex: ETH, MATIC etc.", 'nft-marketplace-core-lite') . '</p>
            </div>
            <div class="form-field form-required term-slug-wrap">
                <label for="nft_marketplace_core_taxonomy_blockchain_currency_chainid">' . esc_html__("Chain ID", 'nft-marketplace-core-lite') . '</label>
                <input required name="nft_marketplace_core_taxonomy_blockchain_currency_chainid" id="nft_marketplace_core_taxonomy_blockchain_currency_chainid" type="text" value="" />
                <p class="description">' . esc_html__("The value will be automatically converted to hex.", 'nft-marketplace-core-lite') . '</p>
            </div>
            <div class="form-field form-required term-slug-wrap">
                <label for="nft_marketplace_core_taxonomy_blockchain_currency_rpc_url">' . esc_html__("RPC Url", 'nft-marketplace-core-lite') . '</label>
                <input required name="nft_marketplace_core_taxonomy_blockchain_currency_rpc_url" id="nft_marketplace_core_taxonomy_blockchain_currency_rpc_url" type="text" value="" />
                <p class="description"></p>
            </div>
            ' . $this->add_blockchain_image() . '
           <ul>
           <p class="description">
           ' . esc_html__("Learn how to add more networks on the websites below (skip to point 3):", 'nft-marketplace-core-lite') . '
            </p>
                <li><a href="https://autofarm.gitbook.io/autofarm-network/how-tos/binance-smart-chain-bsc/metamask-add-binance-smart-chain-bsc-network">' . esc_html__("BSC", 'nft-marketplace-core-lite') . '</a></li>
                <li><a href="https://autofarm.gitbook.io/autofarm-network/how-tos/polygon-chain-matic/metamask-add-polygon-matic-network">' . esc_html__("MATIC (Polygon)", 'nft-marketplace-core-lite') . '</a></li>
            </ul>
        ';
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
                                $('#nft_marketplace_core_taxonomy_blockchain_currency_image').val(attachment.id);
                                $('#blockchain-image-wrapper').html('<img class="custom_media_image" src="" style="margin:0;padding:0;max-height:100px;float:none;" />');
                                $('#blockchain-image-wrapper .custom_media_image').attr('src', attachment.sizes.thumbnail.url).css('display', 'block');
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
                    $('#nft_marketplace_core_taxonomy_blockchain_currency_image').val('');
                    $('#blockchain-image-wrapper').html('<img class="custom_media_image" src="" style="margin:0;padding:0;max-height:100px;float:none;" />');
                });
                // Thanks: http://stackoverflow.com/questions/15281995/wordpress-create-blockchain-ajax-response
                $(document).ajaxComplete(function (event, xhr, settings) {
                    var queryStringArr = settings.data.split('&');
                    if ($.inArray('action=add-tag', queryStringArr) !== -1) {
                        var xml = xhr.responseXML;
                        $response = $(xml).find('term_id').text();
                        if ($response != "") {
                            // Clear the thumb image
                            $('#blockchain-image-wrapper').html('');
                        }
                    }
                });
            });
        </script>
    <?php }

}
