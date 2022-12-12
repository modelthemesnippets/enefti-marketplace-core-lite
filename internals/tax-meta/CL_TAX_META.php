<?php
namespace NFT_Marketplace_Core_Lite\Internals\TaxMeta;

use NFT_Marketplace_Core_Lite\Engine\Base;

/**
 * Plugin class
 **/
class CL_TAX_META extends Base
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
        if (($pagenow == 'edit-tags.php' || $pagenow == 'term.php' || $pagenow == 'admin-ajax.php') && (isset($_GET['taxonomy']) && $_GET['taxonomy'] == 'nft-listing-collection')) {

            add_action('nft-listing-collection_add_form_fields', array($this, 'add_collection_image'), 10, 2);
            add_action('nft-listing-collection_edit_form_fields', array($this, 'update_collection_image'), 10, 2);
            add_action('admin_footer', array($this, 'add_script'));

        }
        add_action('created_nft-listing-collection', array($this, 'save_collection_image'), 10, 2);
        add_action('edited_nft-listing-collection', array($this, 'updated_collection_image'), 10, 2);
    }


    /*
     * Add a form field in the new collection page
     * @since 1.0.0
    */
    public function add_collection_image($taxonomy)
    { ?>
        <div class="form-field term-group">
            <label for="collection-image-id-"><?php esc_html_e('Image', 'nft-marketplace-core-lite'); ?></label>
            <input type="hidden" id="collection-image-id" name="collection-image-id" class="custom_media_url" value="">
            <div id="collection-image-wrapper"></div>
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
    public function save_collection_image($term_id, $tt_id)
    {
        global $pagenow;
        if (isset($_POST['collection-image-id']) && '' !== $_POST['collection-image-id']) {
            $image = sanitize_text_field($_POST['collection-image-id']);
            add_term_meta($term_id, 'collection-image-id', $image, true);
        }

        add_term_meta(
            $term_id,
            'nft_marketplace_core_taxonomy_collection_author',
            get_current_user_id()
        );
    }

    /*
     * Edit the form field
     * @since 1.0.0
    */
    public function update_collection_image($term, $taxonomy)
    { ?>
        <tr class="form-field term-group-wrap">
            <th scope="row">
                <label for="collection-image-id"><?php esc_html_e('Image', 'nft-marketplace-core-lite'); ?></label>
            </th>
            <td>
                <?php $image_id = get_term_meta($term->term_id, 'collection-image-id', true); ?>
                <input type="hidden" id="collection-image-id" name="collection-image-id"
                       value="<?php echo esc_attr($image_id); ?>">
                <div id="collection-image-wrapper">
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
    public function updated_collection_image($term_id, $tt_id)
    {
        global $pagenow;

        if (isset($_POST['collection-image-id']) && '' !== $_POST['collection-image-id']) {
            $image = sanitize_text_field($_POST['collection-image-id']);
            update_term_meta($term_id, 'collection-image-id', $image);
        } else {
            update_term_meta($term_id, 'collection-image-id', '');
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
                                $('#collection-image-id').val(attachment.id);
                                $('#collection-image-wrapper').html('<img class="custom_media_image" src="" style="margin:0;padding:0;max-height:100px;float:none;" />');
                                $('#collection-image-wrapper .custom_media_image').attr('src', attachment.sizes.thumbnail.url).css('display', 'block');
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
                    $('#collection-image-id').val('');
                    $('#collection-image-wrapper').html('<img class="custom_media_image" src="" style="margin:0;padding:0;max-height:100px;float:none;" />');
                });
                // Thanks: http://stackoverflow.com/questions/15281995/wordpress-create-collection-ajax-response
                $(document).ajaxComplete(function (event, xhr, settings) {
                    var queryStringArr = settings.data.split('&');
                    if ($.inArray('action=add-tag', queryStringArr) !== -1) {
                        var xml = xhr.responseXML;
                        $response = $(xml).find('term_id').text();
                        if ($response != "") {
                            // Clear the thumb image
                            $('#collection-image-wrapper').html('');
                        }
                    }
                });
            });
        </script>
    <?php }

    public function handleFrontendSubmission($post, $files)
    {
        if (isset($post["submit"]) && $post["submit"] !== "") {

            $name = $post["tag-name"];
            $slug = $post["slug"];
            $description = $post["description"];

            $term = wp_insert_term(
                $name,
                "nft-listing-collection",
                [
                    "description" => $description,
                    "slug" => $slug,
                ]
            );

            if (is_wp_error($term)) {
                return '<div class="nft-marketplace-core-alert container"><strong>' . esc_html($term->get_error_message()) . '</strong></div>';
            }

            if (!$this->uploadFileFrontend($files, $term["term-id"])) {
                return '<div class="nft-marketplace-core-alert container"><strong>' . esc_html__("Cold not upload the image", 'nft-marketplace-core-lite') . '</strong></div>';
            }

            return '<div class="nft-marketplace-core-alert success container"><strong>' . esc_html__("Collection created successfully.", 'nft-marketplace-core-lite') . '</strong></div>';
        }
    }

    private function uploadFileFrontend($files, $term)
    {
        if (isset($files["collection-image-id"])) {
            if (!function_exists('wp_generate_attachment_metadata')) {
                require_once(ABSPATH . "wp-admin" . '/includes/image.php');
                require_once(ABSPATH . "wp-admin" . '/includes/file.php');
                require_once(ABSPATH . "wp-admin" . '/includes/media.php');
            }
            foreach ($files as $file => $array) {
                if ($array['error'] !== UPLOAD_ERR_OK) {
                    return false;
                }

                $attach_id = media_handle_upload($file, 0);
                $oldFile = get_term_meta($term["term_id"], "collection-image-id", true);

                if ($oldFile) {
                    wp_delete_attachment($oldFile, true);
                }

                add_term_meta($term["term_id"], "collection-image-id", $attach_id);
            }

            return true;
        }
        return true;
    }

    public function handleFrontendReSubmission($term, $post, $files)
    {

        if (is_wp_error($term)) {
            return '<div class="nft-marketplace-core-alert container"><strong>' . esc_html($term->get_error_message() ). '</strong></div>';
        }

        if (isset($post["submit"]) && $post["submit"] !== "") {

            $name = $post["tag-name"];
            $slug = $post["slug"];
            $description = $post["description"];

            $term = wp_update_term(
                $term->term_id,
                "nft-listing-collection",
                [
                    'name' => $name,
                    "description" => $description,
                    "slug" => $slug,
                ]
            );

            if (is_wp_error($term)) {
                return '<div class="nft-marketplace-core-alert container"><strong>' . esc_html($term->get_error_message() ). '</strong></div>';
            }

            if (!$this->uploadFileFrontend($files, $term["term-id"])) {
                return '<div class="nft-marketplace-core-alert container"><strong>' . esc_html__("Cold not upload the image", 'nft-marketplace-core-lite') . '</strong></div>';
            }

            return '<div class="nft-marketplace-core-alert success container"><strong>' . esc_html__("Collection created successfully.", 'nft-marketplace-core-lite') . '</strong></div>';
        }
    }
}

