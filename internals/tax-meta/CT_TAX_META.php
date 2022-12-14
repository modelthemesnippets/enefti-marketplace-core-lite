<?php
/**
 * Plugin class
 **/
namespace NFT_Marketplace_Core_Lite\Internals\TaxMeta;

use NFT_Marketplace_Core_Lite\Engine\Base;


class CT_TAX_META extends Base
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

        if (($pagenow == 'edit-tags.php' || $pagenow == 'term.php' || $pagenow == 'admin-ajax.php') && (isset($_GET['taxonomy']) && $_GET['taxonomy'] == 'nft-listing-category')) {

            add_action('nft-listing-category_add_form_fields', array($this, 'add_category_image'), 10, 2);
            add_action('nft-listing-category_edit_form_fields', array($this, 'update_category_image'), 10, 2);
            add_action('admin_footer', array($this, 'add_script'));

        }
        add_action('created_nft-listing-category', array($this, 'save_category_image'), 10, 2);
        add_action('edited_nft-listing-category', array($this, 'updated_category_image'), 10, 2);
    }

    /*
     * Initialize the class and start calling our hooks and filters
     * @since 1.0.0
    */
    public function init()
    {

    }

    /*
     * Add a form field in the new category page
     * @since 1.0.0
    */
    public function add_category_image($taxonomy)
    { ?>
        <div class="form-field term-group">
            <label for="category-image-id-"><?php esc_html_e('Image', 'nft-marketplace-core-lite'); ?></label>
            <input type="hidden" id="category-image-id" name="category-image-id" class="custom_media_url" value="">
            <div id="category-image-wrapper"></div>
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
    public function save_category_image($term_id, $tt_id)
    {
        global $pagenow;
        if (isset($_POST['category-image-id']) && '' !== $_POST['category-image-id']) {
            $image = sanitize_text_field($_POST['category-image-id']);
            add_term_meta($term_id, 'category-image-id', $image, true);
        }
    }

    /*
     * Edit the form field
     * @since 1.0.0
    */
    public function update_category_image($term, $taxonomy)
    { ?>
        <tr class="form-field term-group-wrap">
            <th scope="row">
                <label for="category-image-id"><?php esc_html_e('Image', 'nft-marketplace-core-lite'); ?></label>
            </th>
            <td>
                <?php $image_id = get_term_meta($term->term_id, 'category-image-id', true); ?>
                <input type="hidden" id="category-image-id" name="category-image-id"
                       value="<?php echo esc_attr($image_id); ?>">
                <div id="category-image-wrapper">
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
    public function updated_category_image($term_id, $tt_id)
    {
        global $pagenow;

        if (isset($_POST['category-image-id']) && '' !== $_POST['category-image-id']) {
            $image = sanitize_text_field($_POST['category-image-id']);
            update_term_meta($term_id, 'category-image-id', $image);
        } else {
            update_term_meta($term_id, 'category-image-id', '');
        }
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
                                $('#category-image-id').val(attachment.id);
                                $('#category-image-wrapper').html('<img class="custom_media_image" src="" style="margin:0;padding:0;max-height:100px;float:none;" />');
                                $('#category-image-wrapper .custom_media_image').attr('src', attachment.sizes.thumbnail.url).css('display', 'block');
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
                    $('#category-image-id').val('');
                    $('#category-image-wrapper').html('<img class="custom_media_image" src="" style="margin:0;padding:0;max-height:100px;float:none;" />');
                });
                // Thanks: http://stackoverflow.com/questions/15281995/wordpress-create-category-ajax-response
                $(document).ajaxComplete(function (event, xhr, settings) {
                    var queryStringArr = settings.data.split('&');
                    if ($.inArray('action=add-tag', queryStringArr) !== -1) {
                        var xml = xhr.responseXML;
                        $response = $(xml).find('term_id').text();
                        if ($response != "") {
                            // Clear the thumb image
                            $('#category-image-wrapper').html('');
                        }
                    }
                });
            });
        </script>
    <?php }

}
