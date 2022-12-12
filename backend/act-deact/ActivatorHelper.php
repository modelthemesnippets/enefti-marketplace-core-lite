<?php

namespace NFT_Marketplace_Core_Lite\Backend\ActDeact;

/**
 * Fired during plugin activation
 *
 * @link       https://modeltheme.com/
 * @since      1.0.0
 *
 * @package    NFT_Marketplace_Core_Lite
 * @subpackage NFT_Marketplace_Core_Lite/backend/act-deact
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    NFT_Marketplace_Core_Lite
 * @subpackage NFT_Marketplace_Core_Lite/backend/act-deact
 * @author     ModelTheme <support@modeltheme.com>
 */
class ActivatorHelper
{

    public static function run()
    {
        self::createRole();
        self::removeCapabilites();
    }

    /**
     * Create the NFT Vendor Role.
     * @return void
     */
    public static function createRole()
    {
        if (!is_null(get_role("nft_vendor"))) {
            return;
        }

        // NFT Vendor role.
        add_role(
            'nft_vendor',
            'NFT Vendor',
            array(
                'upload_files' => true,
                'publish_posts' => true,
                'edit_published_posts' => true,
            )
        );

        update_option("default_role", "nft_vendor");
    }

    /**
     * Handle the capabilities updates of the NFT Vendor role
     * @return void
     */
    public static function removeCapabilites()
    {
        $role = get_role('nft_vendor');

        if ($role === null) {
            return;
        }

        $capsToRemove = [
            'level_4' => true,
            'level_3' => true,
            'level_2' => true,
            'level_1' => true,
            'level_0' => true,
            'read' => true,
            'read_private_pages' => true,
            'read_private_posts' => true,
            'edit_posts' => true,
            'edit_pages' => true,
            'edit_published_pages' => true,
            'edit_private_pages' => true,
            'edit_private_posts' => true,
            'edit_others_posts' => true,
            'edit_others_pages' => true,
            'publish_pages' => true,
            'delete_posts' => true,
            'delete_pages' => true,
            'delete_private_pages' => true,
            'delete_private_posts' => true,
            'delete_published_pages' => true,
            'delete_published_posts' => true,
            'delete_others_posts' => true,
            'delete_others_pages' => true,
            'manage_categories' => true,
            'manage_links' => true,
            'moderate_comments' => true,
            'export' => true,
            'import' => true,
            'list_users' => true,
        ];


        foreach ($capsToRemove as $key => $value) {
            $role->remove_cap($key);
        }
    }
}
