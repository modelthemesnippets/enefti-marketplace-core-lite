<?php
/**
 * code
 *
 * @package   code
 * @author    ModelTheme <support@modeltheme.com>
 * @copyright Copyright (C) 2012-2022, Modeltheme, support@modeltheme.com
 * @license   GPL-3.0
 * @link      https://modeltheme.com
 */

namespace NFT_Marketplace_Core\Internals;

/**
 * Helper class to easily access all useful functions
 *
 * @package   NFT Marketplace Core
 * @author    ModelTheme <support@modeltheme.com>
 * @copyright Copyright (C) 2012-2022, ModelTheme, support@modeltheme.com
 * @license   GPL-3.0
 * @link      https://modeltheme.com
 */
class Helper
{
    public static function getCurrencyImageSrcByTermId($termId, $customSizes = [30, 30])
    {
        $nft_marketplace_core_nft_listing_blockchains_term_meta = get_term_meta($termId);

        if (isset($nft_marketplace_core_nft_listing_blockchains_term_meta["nft_marketplace_core_taxonomy_blockchain_currency_image"])) {
            $image = wp_get_attachment_image_src($nft_marketplace_core_nft_listing_blockchains_term_meta["nft_marketplace_core_taxonomy_blockchain_currency_image"][0], $customSizes, true);
            if (is_array($image)) {
                return "<img src='" . $image[0] . "' width='" . $customSizes[0] . "' height='" . $customSizes[1] . "' alt='" . esc_attr__("Blockchain Icon", NFT_MARKETPLACE_CORE_TEXTDOMAIN) . "'/>";
            }
            return $image;
        } elseif($nft_marketplace_core_nft_listing_blockchains_term_meta["nft_marketplace_core_taxonomy_currency_symbol"]) {
            $image = wp_get_attachment_image_src($nft_marketplace_core_nft_listing_blockchains_term_meta["currency-image-id"][0], $customSizes, true);
            if (is_array($image)) {
                return "<img src='" . $image[0] . "' width='" . $customSizes[0] . "' height='" . $customSizes[1] . "' alt='" . esc_attr__("Token Icon", NFT_MARKETPLACE_CORE_TEXTDOMAIN) . "'/>";
            }
            return $image;
        }

        return self::getDefaultThumbnailSrc();
    }

    public static function termIdToBlockchainSwitchDataset($termid)
    {
        return 'data-force-switch-to="' . esc_attr(json_encode(get_term_meta($termid), JSON_UNESCAPED_SLASHES)) . '"';
    }

    public static function getCurrencyByContractAddress(string $address)
    {
        return get_terms([
            'taxonomy' => "nft-listing-currency",
            'hide_empty' => false,
            'meta_query' => array(
                array(
                    'key' => 'nft_marketplace_core_taxonomy_currency_contract',
                    'value' => $address,
                    'compare' => '='
                )
            )
        ])[0];
    }

    public static function areAddressesEqual($address1, $address2): bool
    {

        if (str_contains($address1, "0x0")) {
            $address1 = str_replace("0x0", "0x", $address1);
        }

        if (str_contains($address2, "0x0")) {
            $address2 = str_replace("0x0", "0x", $address2);
        }

        return strtolower($address1) === strtolower($address2);
    }

    public static function calcCollectionPropertyOccurrence($collectionId) {
        $the_query = new \WP_Query( array(
            'post_type' => 'nft-listing',
            'tax_query' => array(
                array (
                    'taxonomy' => 'advert_tag',
                    'field' => 'slug',
                    'terms' => 'politics',
                )
            ),
        ) );

        while ( $the_query->have_posts() ) :
            $the_query->the_post();
            // Show Posts ...
        endwhile;

        /* Restore original Post Data
         * NB: Because we are using new WP_Query we aren't stomping on the
         * original $wp_query and it does not need to be reset.
        */
        wp_reset_postdata();
    }

    public static function getCurrencyDataByPostId($postID) {
        $customTokenAddress = get_post_meta($postID, "nft_marketplace_core_nft_listing_currency", true);
        $nft_marketplace_core_nft_listing_price = get_post_meta($postID, "nft_marketplace_core_nft_listing_currency_price", true);
        if ($customTokenAddress !== "0xeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee") {
            $currencyTermId = self::getCurrencyByContractAddress($customTokenAddress)->term_id;
            $currencyTermMeta = get_term_meta($currencyTermId, "nft_marketplace_core_taxonomy_currency_symbol", true);

            return [
                "token" => $customTokenAddress,
                "symbol" => $currencyTermMeta,
                "price" => $nft_marketplace_core_nft_listing_price / 1000000000000000000,
                "image" => self::getCurrencyImageSrcByTermId($currencyTermId, [30, 30])
            ];
        }
        $termData =  get_the_terms($postID,"nft_listing_blockchains")[0];
        $currencyImage = get_term_meta($termData->term_id, "nft_marketplace_core_taxonomy_blockchain_currency_symbol", true);
        self::getCurrencyImageSrcByTermId($termData->term_id, [30, 30]);

        return [
            "token" => $customTokenAddress,
            "symbol" => $currencyImage,
            "image" => self::getCurrencyImageSrcByTermId($termData->term_id, [30, 30]),
            "price" => $nft_marketplace_core_nft_listing_price / 1000000000000000000,
        ];
    }

    public static function clearOffers($postId) {
        global $wpdb;

        return $wpdb->delete($wpdb->prefix . "nft_marketplace_core_offers", ["nft_id" => $postId]);
    }

    public static function getCurrencyDisplayDataByPostId(int $postID)
    {
        $customTokenAddress = get_post_meta($postID, "nft_marketplace_core_nft_listing_currency", true);
        $nft_marketplace_core_nft_listing_price = get_post_meta($postID, "nft_marketplace_core_nft_listing_currency_price", true);

        if ($customTokenAddress !== "0xeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee") {
            $currencyTermId = self::getCurrencyByContractAddress($customTokenAddress)->term_id;
            $currencyTermMeta = get_term_meta($currencyTermId, "nft_marketplace_core_taxonomy_currency_symbol", true);
            echo self::getCurrencyImageSrcByTermId($currencyTermId, [30, 30]); ?>
            <b class="nft-importer-price"><?php echo esc_html(($nft_marketplace_core_nft_listing_price / 1000000000000000000)) . ' ' . esc_html($currencyTermMeta); ?></b>
            <?php
            return;
        }

        $termData =  get_the_terms($postID,"nft_listing_blockchains")[0];
        $currencyImage = get_term_meta($termData->term_id, "nft_marketplace_core_taxonomy_blockchain_currency_symbol", true);
        echo self::getCurrencyImageSrcByTermId($termData->term_id, [30, 30]); ?>
        <b class="nft-importer-price"><?php echo esc_html(($nft_marketplace_core_nft_listing_price / 1000000000000000000)) . ' ' . esc_html($currencyImage); ?></b>


    <?php
    }

    /**
     * @param $postId
     * @param $log_id
     * @param $args
     * @return void
     * @since 2.0.0
     */
    public static function saveLog($postId, $type, $args)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'nft_marketplace_core_logs';
        $table_name_values = $wpdb->prefix . 'nft_marketplace_core_logs_values';

        $wpdb->insert($table_name, [
            "nft_id" => $postId,
            "log_type" => $type
        ]);

        $logId = $wpdb->insert_id;

        foreach ($args as $key => $value) {
            $wpdb->insert($table_name_values, [
                "log_id" => $logId,
                "log_value" => $value,
                "log_string" => $key
            ]);
        }
    }

    public static function isItemOnSale($postID)
    {
        $nft_marketplace_core_nft_listing_start_time = get_post_meta($postID, 'nft_marketplace_core_nft_listing_start_time', true);
        $listingStarted = time() > $nft_marketplace_core_nft_listing_start_time;
        $hasItemLeftToSell = get_post_meta($postID, "nft_marketplace_core_nft_listing_quantity", true) > 0;
        $listingNotExpired = get_post_meta($postID, 'nft_marketplace_core_nft_listing_end_time', true) > time();

        return $listingStarted && $hasItemLeftToSell && $listingNotExpired;
    }

    /* Function to check whether the user who clicks the love button already loved the post */
    public static function isAlreadyLoved($post_id)
    {
        $user_id = get_current_user_id();
        $postmetadata_userIDs = get_post_meta($post_id, "nft_marketplace_core_user_loved", false);
        $users_loved = [];
        if (count($postmetadata_userIDs) != 0) {
            $users_loved = $postmetadata_userIDs[0];
        }
        if (!is_array($users_loved))
            $users_loved = [];
        if (in_array($user_id, $users_loved)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $nft_id
     * @return string[]
     * @since 2.0.0
     */
    public static function readLog($nft_id)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'nft_marketplace_core_logs';
        $table_name_values = $wpdb->prefix . 'nft_marketplace_core_logs_values';

        $log = $wpdb->get_results("SELECT * FROM $table_name WHERE `nft_id` = $nft_id");
        $log_values = $wpdb->get_results("SELECT * FROM $table_name_values WHERE `log_id` = $log->id");

        $text = "";

        // NFT Created
        if ($log->type === 1) {
            $text = esc_html__("The NFT was created by %address%.", NFT_MARKETPLACE_CORE_TEXTDOMAIN);
        } else if ($log->type === 2) {
            $text = esc_html__("The NFT was listed by %address%.", NFT_MARKETPLACE_CORE_TEXTDOMAIN);
        } else if ($log->type === 3) {
            $text = esc_html__("The NFT was sold to %address%.", NFT_MARKETPLACE_CORE_TEXTDOMAIN);
        } else if ($log->type === 4) {
            $text = esc_html__("The NFT was relisted by %address%.", NFT_MARKETPLACE_CORE_TEXTDOMAIN);
        }

        return [
            "log_type" => $log->type,
            "log_date" => $log->log_date,
            "log_message" => $text,
            "log_extra_data" => $log_values,
        ];
    }

    public static function getNFTDisplayAsset($postid, $size = "large")
    {
        $isExplicit = get_post_meta($postid, "nft_marketplace_core_nft_listing_explicit_meta", true);

        if ($isExplicit) {
            return self::getNFTDisplayAssetExplicit($postid, $size);
        }

        return self::getNFTDisplayAssetRAW($postid, $size);
    }

    private static function getNFTDisplayAssetExplicit($postid, $size = "large"): string
    {
        $largeSize = "";

        if ($size === "large") {
            $largeSize = "            
            <div class='overlay'>
                <h5>" . esc_html__('This content might be disturbing. Proceed with caution!', NFT_MARKETPLACE_CORE_TEXTDOMAIN) . "</h5>
                <button id='nft-marketplace-core-nsfw-remove-button'>" . esc_html__('View Regardless', NFT_MARKETPLACE_CORE_TEXTDOMAIN) . "</button>
            </div>";
        }

        return "
        <div id='nft-marketplace-core-hide-content-nsfw' class='nft-marketplace-core-hide-content-nsfw'>
            <div>
                " . self::getNFTDisplayAssetRAW($postid, $size) . "
            </div>
            $largeSize
        </div>
        ";
    }

    private static function getNFTDisplayAssetRAW($postid, $size = "large")
    {

        $sizeT = [
            "image" => "width:360px;height:360px;object-fit: scale-down;",
            "default" => [360, 360]
        ];

        if ($size == 'thumbnail') {
            $sizeT = [
                "image" => "width: 80px; height: 80px;object-fit: scale-down;",
            ];
        } elseif ($size == 'medium') {
            $sizeT = [
                "image" => "width: 260px; height: 260px;object-fit: scale-down;",
            ];
        }

        $external = get_post_meta($postid, "nft_marketplace_core_nft_listing_external_image", true);
        $assets = get_post_meta($postid, "nft_marketplace_core_nft_listing_assets", true);

//        if ($external === "" && (!is_array($assets) || !isset($assets[0]))) {
//            return esc_html__("Could not determine the location of the assets.",NFT_MARKETPLACE_CORE_TEXTDOMAIN);
//        }

        if (is_array($assets) && strpos(wp_check_filetype(wp_get_attachment_url($assets[0]))["type"], 'image/') !== 0 && !function_exists("nft_marketplace_core_media_types_addon_media")) {
            return esc_html__("You need to have the Media Types for NFT Marketplace Core Plugin to display assets of this type.", NFT_MARKETPLACE_CORE_TEXTDOMAIN);
        }

        $filter = apply_filters("nft_marketplace_core_process_display_asset", false, $postid, $assets, $external, $size);

        if ($filter) {
            return $filter;
        }

        if (!is_array($external) && $external != "") {
            return '<img  src="' . $external . '"   style="' . $sizeT['image'] . '"  alt=""/>';
        }

        $thumbnail = wp_get_attachment_image(get_post_thumbnail_id($postid), $size);

        if ($thumbnail) {
            return $thumbnail;
        }

        return self::getDefaultThumbnailSrc(360, 360);
    }

    public static function getDefaultThumbnailSrc($width = 30, $height = 30)
    {
        $defaultThumbnailUrl = plugin_dir_url(__DIR__) . "assets/images/placeholder.png";
        return "<img src='$defaultThumbnailUrl' width='$width' height='$height' />";
    }

    public static function getPlaceholderLocation()
    {
        return plugin_dir_url(__DIR__) . "assets/images/placeholder.png";
    }
}
