<?php
/**
 * The NFT creator/edit form
 *
 * @link       https://modeltheme.com/
 * @since      1.0.0
 *
 * @package    NFT_Marketplace_Core_Lite
 * @subpackage NFT NFT_Marketplace_Core_Lite Core/includes
 */

/**
 * The NFT creator/edit form
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    NFT Marketplace Core
 * @subpackage NFT Marketplace Core/includes
 * @author     ModelTheme <support@modeltheme.com>
 */

namespace NFT_Marketplace_Core_Lite\Integrations;

use WP_Error;

class NFTForm
{
    private $isEdit;

    /**
     * Initialize the class and set its properties.
     *
     * @param bool $isEdit
     * @since    1.0.0
     */
    public function __construct(bool $isEdit = false)
    {
        $this->isEdit = $isEdit;
    }

    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in nft_marketplace_core_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The nft_marketplace_core_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in nft_marketplace_core_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The nft_marketplace_core_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

    }

    // yes/no dropdown for options
    public function render_yesno_field($args)
    {
        echo '<select id="' . esc_attr($args['name']) . '" name="' . esc_attr($args['name']) . '">';
        echo '<option value="no" ' . ($args['value'] == 'no' ? 'selected' : '') . '>' . esc_html__('No', 'nft-marketplace-core-lite') . '</option>';
        echo '<option value="yes" ' . ($args['value'] == 'yes' ? 'selected' : '') . '>' . esc_html__('Yes', 'nft-marketplace-core-lite') . '</option>';
        echo '</select>';
    }

    function cmb2_after_form_do_js_validation($post_id, $cmb)
    {

        static $added = false;
        // Only add this to the page once (not for every metabox)
        if ($added) {
            return;
        }
        $added = true;

        ?>

        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                "use strict"
                let $form = $(document.getElementById('post'));
                let $htmlbody = $('html, body');
                let $toValidate = $('[data-validation]');

                if (!$toValidate.length) {
                    return;
                }

                function checkValidation(evt) {
                    var labels = [];
                    var $first_error_row = null;
                    var $row = null;

                    function add_required($row) {
                        $row.css({'background-color': 'rgb(255, 170, 170)'});
                        $first_error_row = $first_error_row ? $first_error_row : $row;
                        labels.push($row.find('.cmb-th label').text());
                    }

                    function remove_required($row) {
                        $row.css({background: ''});
                    }

                    $toValidate.each(function () {
                        var $this = $(this);
                        var val = $this.val();
                        $row = $this.parents('.cmb-row');

                        if ($this.is('[type="button"]') || $this.is('.cmb2-upload-file-id')) {
                            return true;
                        }

                        if ('required' === $this.data('validation')) {
                            if ($row.is('.cmb-type-file-list')) {
                                var has_LIs = $row.find('ul.cmb-attach-list li').length > 0;

                                if (!has_LIs) {
                                    add_required($row);
                                } else {
                                    remove_required($row);
                                }
                            } else {
                                if (!val) {
                                    add_required($row);
                                } else {
                                    remove_required($row);
                                }
                            }
                        }
                    });

                    if ($first_error_row) {
                        evt.preventDefault();
                        alert('<?php esc_html_e('The following fields are required and highlighted below:', 'nft-marketplace-core-lite'); ?> ' + labels.join(', '));
                        $htmlbody.animate({
                            scrollTop: ($first_error_row.offset().top - 200)
                        }, 1000);
                    }
                }

                $form.on('submit', checkValidation);
            });
        </script>
        <?php
    }

    function nft_marketplace_core_nft_listing_title($postID = null)
    {
        $cmb = new_cmb2_box(array(
            'id' => 'nft_marketplace_core_nft_listing_title',
            'title' => esc_html__('NFT Title', 'nft-marketplace-core-lite'),
            'object_types' => array('nft-listing'),
        ));
        $cmb->add_field(array(
            'name' => esc_html__('NFT Title', 'nft-marketplace-core-lite'),
            'id' => 'nft_marketplace_core_nft_listing_name',
            'type' => 'text',
            'default' => $this->isEdit ? get_post($postID)->post_title : '',
        ));

    }

    function nft_marketplace_core_nft_listing_form_submission($cmb, $post_data = array())
    {
        // If no form submission, bail
        if (empty($_POST)) {
            return false;
        }

        // check required $_POST variables and security nonce
        if (
            !isset($_POST['submit-cmb'], $_POST['object_id'], $_POST[$cmb->nonce()])
            || !wp_verify_nonce($_POST[$cmb->nonce()], $cmb->nonce())
        ) {
            return new WP_Error('security_fail', esc_html__('Security check failed.', 'nft-marketplace-core-lite'));
        }

        if (empty($_POST['nft_marketplace_core_nft_listing_name'])) {
            return new WP_Error('post_data_missing', esc_html__('New post requires a title.', 'nft-marketplace-core-lite'));
        }

        $args = array(
            'ID' => sanitize_text_field($_POST['object_id']),
            'post_title' => sanitize_text_field($_POST['nft_marketplace_core_nft_listing_name']),
        );
        wp_update_post($args);

        // Do WordPress insert_post stuff
        return 0;
    }

    public function nft_marketplace_core_render_form($postID)
    {

        if ($this->isEdit) {
            $this->nft_marketplace_core_nft_listing_title($postID);
            $this->nft_marketplace_core_nft_listing_form_submission(cmb2_get_metabox('nft_marketplace_core_nft_listing_title'));
        }

        $this->nft_marketplace_core_nft_listing_repeatable_stats();
        $this->nft_marketplace_core_nft_listing_repeatable_properties();
        $this->nft_marketplace_core_nft_listing_repeatable_levels();
        $this->nft_marketplace_core_nft_listing_price();

        $this->nft_marketplace_core_nft_listing_nft_collection();

        echo '<form class="cmb-form" method="post" id="wpcasa-listing-form" enctype="multipart/form-data" encoding="multipart/form-data"><input type="hidden" name="object_id" value="' . esc_attr($postID) . '">';
        $args = array('form_format' => '%3$s');
        foreach (['nft_marketplace_core_nft_listing_title', 'nft_marketplace_core_nft_listing_price_group', 'nft_marketplace_core_nft_listing_collection', 'nft_marketplace_core_nft_listing_group', 'nft_marketplace_core_nft_listing_level_group', 'nft_marketplace_core_nft_listing_properties_group'] as $metabox) { // loop over config array
            cmb2_metabox_form($metabox, $postID, $args);
        }
        echo '<input type="submit" name="submit-cmb" value="Save" class="button-primary single_nft_button button alt"></form>';
    }

    /**
     * Hook in and add a metabox to add fields to taxonomy terms
     */

    function nft_marketplace_core_nft_listing_repeatable_stats()
    {
        $fields_group = new_cmb2_box(array(
            'id' => 'nft_marketplace_core_nft_listing_group',
            'title' => esc_html__('NFT’s Stats', 'nft-marketplace-core-lite'),
            'object_types' => array('nft-listing'),
        ));

        // $fields_group_id is the field id string, so in this case: 'yourprefix_group_demo'
        $fields_group_id = $fields_group->add_field(array(
            'id' => 'nft_marketplace_core_nft_listing_group',
            'type' => 'group',
            'options' => array(
                'group_title' => esc_html__('Stat', 'nft-marketplace-core-lite'), // {#} gets replaced by row number
                'add_button' => esc_html__('Add New Stat', 'nft-marketplace-core-lite'),
                'remove_button' => esc_html__('Remove', 'nft-marketplace-core-lite'),
                'sortable' => true,
                'closed' => true, // true to have the groups closed by default
                'remove_confirm' => esc_html__('Are you sure you want to delete this Stat?', 'nft-marketplace-core-lite'), // Performs confirmation before removing group.
            ),
        ));

        $fields_group->add_group_field($fields_group_id, array(
            'name' => esc_html__('Name', 'nft-marketplace-core-lite'),
            'id' => 'nft_marketplace_core_nft_listing_stat_name',
            'type' => 'text'
        ));

        $fields_group->add_group_field($fields_group_id, array(
            'name' => esc_html__('Rate', 'nft-marketplace-core-lite'),
            'id' => 'nft_marketplace_core_nft_listing_stat_rate',
            'type' => 'text'

        ));

    }

    function nft_marketplace_core_nft_listing_nft_data()
    {
        $cmb = new_cmb2_box(array(
            'id' => 'nft_marketplace_core_nft_listing_socials_group',
            'title' => esc_html__('Contract Data', 'nft-marketplace-core-lite'),
            'object_types' => array('nft-listing'),
        ));

        $cmb->add_field(array(
            'name' => esc_html__('NFT\'s Contract Address', 'nft-marketplace-core-lite'),
            'id' => 'nft_marketplace_core_nft_listing_address',
            'type' => 'text',
            'attributes' => array(
                'data-validation' => 'required',
            ),
            'desc' => 'The address this NFT was created on.'
        ));

        $cmb->add_field(array(
            'name' => esc_html__('NFT\'s Token ID', 'nft-marketplace-core-lite'),
            'id' => 'nft_marketplace_core_nft_listing_token_id',
            'type' => 'text',
            'attributes' => array(
                'data-validation' => 'required',
            ),
            'desc' => 'The ID of the NFT, you get this id by minting the nft.'

        ));
    }

    function nft_marketplace_core_nft_listing_nft_collection()
    {
        $cmb = new_cmb2_box(array(
            'id' => 'nft_marketplace_core_nft_listing_collection',
            'title' => esc_html__('Collection', 'nft-marketplace-core-lite'),
            'object_types' => array('nft-listing'),
        ));

        $terms = get_terms([
            'taxonomy' => "nft-listing-collection",
            'hide_empty' => false,
        ]);

        if($terms) {
            $cmb->add_field(array(
                'name' => esc_html__("Collection", 'nft-marketplace-core-lite'),
                'id' => 'nft_marketplace_core_nft_listing_collection',
                'taxonomy' => 'nft-listing-collection', // Enter Taxonomy Slug
                'type' => 'taxonomy_select',
                // Optional :
                'text' => array(
                    'no_terms_text' => esc_html__('Sorry, no terms could be found. Create some from inside the NFTs Manager', 'nft-marketplace-core-lite') // Change default text. Default: "No terms"
                ),
                'remove_default' => 'true', // Removes the default metabox provided by WP core.
                // Optionally override the args sent to the WordPress get_terms function.
                'query_args' => array(
                    // 'orderby' => 'slug',
                    // 'hide_empty' => true,
                ),
            ));
        }

        $terms = get_terms([
            'taxonomy' => "nft-listing-category",
            'hide_empty' => false,
        ]);

        if($terms) {
            $cmb->add_field(array(
                'name' => esc_html__("Category", 'nft-marketplace-core-lite'),
                'id' => 'nft_marketplace_core_nft_listing_category',
                'taxonomy' => 'nft-listing-category', // Enter Taxonomy Slug
                'type' => 'taxonomy_select',
                // Optional :
                'text' => array(
                    'no_terms_text' => esc_html__('Sorry, no terms could be found. Create some from inside the NFTs Manager', 'nft-marketplace-core-lite') // Change default text. Default: "No terms"
                ),
                'remove_default' => 'true', // Removes the default metabox provided by WP core.
                // Optionally override the args sent to the WordPress get_terms function.
                'query_args' => array(
                    // 'orderby' => 'slug',
                    // 'hide_empty' => true,
                ),
            ));
        }
    }

    function nft_marketplace_core_nft_listing_price()
    {
        $cmb = new_cmb2_box(array(
            'id' => 'nft_marketplace_core_nft_listing_price_group',
            'title' => esc_html__('General Information', 'nft-marketplace-core-lite'),
            'object_types' => array('nft-listing'),
        ));

        $cmb->add_field(array(
            'name' => esc_html__('NFT Description', 'nft-marketplace-core-lite'),
            'id' => 'nft_marketplace_core_nft_listing_description_meta',
            'type' => 'textarea'
        ));

        $cmb->add_field(array(
            'name' => esc_html__('Explicit Content', 'nft-marketplace-core-lite'),
            'id' => 'nft_marketplace_core_nft_listing_explicit_meta',
            'type' => 'checkbox',
            'desc' => esc_html__('Is this item something that can be very sensitive for a group of people?', 'nft-marketplace-core-lite')
        ));

        $cmb->add_field(array(
            'name' => esc_html__('Unlockable Content', 'nft-marketplace-core-lite'),
            'id' => 'nft_marketplace_core_nft_listing_content_meta',
            'type' => 'checkbox',
            'desc' => esc_html__('Include unlockable content that can only be revealed by the owner of the item.', 'nft-marketplace-core-lite')
        ));

        $cmb->add_field(array(
            'name' => esc_html__('Content', 'nft-marketplace-core-lite'),
            'id' => 'nft_marketplace_core_nft_listing_content',
            'type' => 'text',
            'desc' => esc_html__('The content you want to hide.', 'nft-marketplace-core-lite')
        ));

    }


    function nft_marketplace_core_nft_listing_socials()
    {
        $cmb = new_cmb2_box(array(
            'id' => 'nft_marketplace_core_nft_listing_socials_group',
            'title' => esc_html__('Socials', 'nft-marketplace-core-lite'),
            'object_types' => array('nft-listing'),
        ));

        $cmb->add_field(array(
            'name' => esc_html__('Slug', 'nft-marketplace-core-lite'),
            'id' => 'nft_marketplace_core_nft_listing_slug',
            'type' => 'text'
        ));

        $cmb->add_field(array(
            'name' => esc_html__('Telegram Url', 'nft-marketplace-core-lite'),
            'id' => 'nft_marketplace_core_nft_listing_telegram_url',
            'type' => 'text'
        ));

        $cmb->add_field(array(
            'name' => esc_html__('Twitter', 'nft-marketplace-core-lite'),
            'id' => 'nft_marketplace_core_nft_listing_twitter_url',
            'type' => 'text'
        ));

        $cmb->add_field(array(
            'name' => esc_html__('Instagram', 'nft-marketplace-core-lite'),
            'id' => 'nft_marketplace_core_nft_listing_instagram_url',
            'type' => 'text'
        ));

        $cmb->add_field(array(
            'name' => esc_html__('Wiki', 'nft-marketplace-core-lite'),
            'id' => 'nft_marketplace_core_nft_listing_wiki_url',
            'type' => 'text'
        ));
    }


    function nft_marketplace_core_nft_listing_crypto_prices()
    {
        $cmb = new_cmb2_box(array(
            'id' => 'nft_marketplace_core_nft_listing_crypto_prices_group',
            'title' => esc_html__('Crypto Prices', 'nft-marketplace-core-lite'),
            'object_types' => array('nft-listing'),
        ));

        $cmb->add_field(array(
            'name' => esc_html__('Price', 'nft-marketplace-core-lite'),
            'id' => 'nft_marketplace_core_nft_listing_currency_price',
            'type' => 'text',
            'desc' => esc_html__('Complete this field only if you are reimporting an already existing NFT on the marketplace.', 'nft-marketplace-core-lite')
        ));
    }

    function nft_marketplace_core_nft_listing_repeatable_properties()
    {
        $fields_group = new_cmb2_box(array(
            'id' => 'nft_marketplace_core_nft_listing_properties_group',
            'title' => esc_html__('NFT’s Properties', 'nft-marketplace-core-lite'),
            'object_types' => array('nft-listing'),
        ));


        // $fields_group_id is the field id string, so in this case: 'yourprefix_group_demo'
        $fields_group_id = $fields_group->add_field(array(
            'id' => 'nft_marketplace_core_nft_listing_properties_group',
            'type' => 'group',
            'options' => array(
                'group_title' => esc_html__('Property', 'nft-marketplace-core-lite'), // {#} gets replaced by row number
                'add_button' => esc_html__('Add New Property', 'nft-marketplace-core-lite'),
                'remove_button' => esc_html__('Remove', 'nft-marketplace-core-lite'),
                'sortable' => true,
                'closed' => true, // true to have the groups closed by default
                'remove_confirm' => esc_html__('Are you sure you want to delete this Property?', 'nft-marketplace-core-lite'), // Performs confirmation before removing group.
            ),
        ));


        $fields_group->add_group_field($fields_group_id, array(
            'name' => esc_html__('Name', 'nft-marketplace-core-lite'),
            'id' => 'nft_marketplace_core_nft_listing_property_name',
            'type' => 'text'
        ));

        $fields_group->add_group_field($fields_group_id, array(
            'name' => esc_html__('Value', 'nft-marketplace-core-lite'),
            'id' => 'nft_marketplace_core_nft_listing_property_value',
            'type' => 'text'
        ));

    }


    function nft_marketplace_core_nft_listing_repeatable_levels()
    {
        $fields_group = new_cmb2_box(array(
            'id' => 'nft_marketplace_core_nft_listing_level_group',
            'title' => esc_html__('NFT’s Levels', 'nft-marketplace-core-lite'),
            'object_types' => array('nft-listing'),
        ));

        // $fields_group_id is the field id string, so in this case: 'yourprefix_group_demo'
        $fields_group_id = $fields_group->add_field(array(
            'id' => 'nft_marketplace_core_nft_listing_level_group',
            'type' => 'group',
            'options' => array(
                'group_title' => esc_html__('Level', 'nft-marketplace-core-lite'), // {#} gets replaced by row number
                'add_button' => esc_html__('Add New Level', 'nft-marketplace-core-lite'),
                'remove_button' => esc_html__('Remove', 'nft-marketplace-core-lite'),
                'sortable' => true,
                'closed' => true, // true to have the groups closed by default,
                'class' => "single_nft_button",
                'remove_confirm' => esc_html__('Are you sure you want to delete this Level?', 'nft-marketplace-core-lite'), // Performs confirmation before removing group.
            ),
        ));


        $fields_group->add_group_field($fields_group_id, array(
            'name' => esc_html__('Name', 'nft-marketplace-core-lite'),
            'id' => 'nft_marketplace_core_nft_listing_level_name',
            'type' => 'text',
            'default' => ''
        ));

        $fields_group->add_group_field($fields_group_id, array(
            'name' => esc_html__('Rate', 'nft-marketplace-core-lite'),
            'id' => 'nft_marketplace_core_nft_listing_level_rate',
            'type' => 'text',
            'default' => ''
        ));

    }

    function nft_marketplace_core_author_fields()
    {
        $cmb = new_cmb2_box(array(
            'id' => 'nft_marketplace_core_user_extra_fields',
            'object_types' => array('user'),
        ));

        $cmb->add_field(array(
            'name' => esc_html__('NFT Marketplace Core Extra Fields', 'nft-marketplace-core-lite'),
            'id' => 'nft_marketplace_core_user_extra_fields',
            'type' => 'title'
        ));

        $cmb->add_field(array(
            'name' => esc_html__('Profile Banner Image', 'nft-marketplace-core-lite'),
            'id' => 'nft_marketplace_core_banner_img',
            'type' => 'file',
            'options' => array(
                'url' => false,
            ),
            'text' => array(
                'add_upload_file_text' => 'Add File'
            ),
            // query_args are passed to wp.media's library query.
            'query_args' => array(
                'type' => array(
                    'image/gif',
                    'image/jpeg',
                    'image/png',
                ),
            ),
            'preview_size' => 'large',
        ));

        $cmb->add_field(array(
            'name' => esc_html__('Address Hash', 'nft-marketplace-core-lite'),
            'id' => 'nft_marketplace_core_user_address',
            'type' => 'text'
        ));


        $cmb->add_field(array(
            'name' => esc_html__('Facebook link', 'nft-marketplace-core-lite'),
            'id' => 'nft_marketplace_core_user_facebook',
            'type' => 'text'
        ));


        $cmb->add_field(array(
            'name' => esc_html__('Instagram link', 'nft-marketplace-core-lite'),
            'id' => 'nft_marketplace_core_user_instagram',
            'type' => 'text'
        ));

        $cmb->add_field(array(
            'name' => esc_html__('Youtube link', 'nft-marketplace-core-lite'),
            'id' => 'nft_marketplace_core_user_youtube',
            'type' => 'text'
        ));

    }

}
