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

namespace NFT_Marketplace_Core_Lite\Integrations;

use NFT_Marketplace_Core_Lite\Engine\Base;

/**
 * Handles integration with the NFT Creator.
 *
 * @package   NFT Marketplace Core Lite
 * @author    ModelTheme <support@modeltheme.com>
 * @copyright Copyright (C) 2012-2022, ModelTheme, support@modeltheme.com
 * @license   GPL-3.0
 * @link      https://modeltheme.com
 */
class Creator extends Base
{
    /**
     * Initialize the class.
     *
     * @return void
     */
    public function initialize() {
        parent::initialize();

        add_filter('wpnc_event_after_creation_filter', [$this, 'add_nft_from_creator']);
        add_filter('wpnc_event_before_creation_filter', [$this, 'validate_from_creator']);
    }

    /**
     * Before publishing nft to blockchain.
     * @param $request \WP_REST_Request
     * @return \WP_REST_Response
     */
    public function validate_from_creator(\WP_REST_Request $request): \WP_REST_Response
    {
        $data = $request->get_param("formData");

        $required = array('image', 'levels', 'name', 'properties', 'stats');

        if (count(array_intersect_key(array_flip($required), $data)) !== count($required)) {
            return new \WP_REST_Response(["success" => false, "message" => esc_html__("Core: Something went wrong!",'nft-marketplace-core-lite')], 401);
        }

        if(!is_array($data["image"])) {
            return new \WP_REST_Response(["success" => false, "message" => esc_html__("Core: Not a valid image!",'nft-marketplace-core-lite')], 401);
        }

        return new \WP_REST_Response(["success" => true, "message" => esc_html__("Core: Everything went alright!",'nft-marketplace-core-lite')], 201);
    }


    /**
     * After publishing nft to blockchain.
     * @param $request \WP_REST_Request
     * @return \WP_REST_Response
     */
    public function add_nft_from_creator(\WP_REST_Request $request): \WP_REST_Response
    {
        $data = $request->get_param("formData");

        $vr = $this->validate_from_creator($request);

        if($vr->get_data()["success"] === false) {
            return $vr;
        }

        $transaction = $request->get_param("transaction");
        $uploadData = $request->get_param("uploadData");
        $args = array(
            'hide_empty' => false, // also retrieve terms which are not used yet
            'meta_query' => array(
                array(
                    'key'       => 'nft_marketplace_core_taxonomy_blockchain_currency_chainid',
                    'value'     => '0x'.dechex($transaction["chainId"]),
                )
            ),
            'taxonomy'  => 'nft_listing_blockchains',
        );
        $term = get_terms( $args )[0];

        $args = array(
            'meta_query' => array(
                array(
                    'key' => 'nft_marketplace_core_nft_listing_token_id',
                    'value' => 1
                ),
                array(
                    'key' => 'nft_marketplace_core_nft_listing_address',
                    'value' => $transaction["to"]
                )
            ),
            'post_type' => 'nft-listing',
            'posts_per_page' => -1,
        );
        $posts = get_posts($args);
        if(count($posts) === 0) {
            $my_post = array(
                'post_title' => wp_strip_all_tags($data["name"]),
                'post_content' => isset($data["desc"]) ? $data["desc"] : "",
                'post_status' => 'publish',
                'post_author' => get_current_user_id(),
                'post_type' => "nft-listing",
                'comment_status' => "closed",
            );

            $my_post['meta_input'] = [
                "nft_marketplace_core_nft_listing_description_meta" => isset($data["desc"]) ? $data["desc"] : "",
                "nft_marketplace_core_nft_listing_content_meta" => isset($data["unlockable"]) ? $data["unlockable"] : "",
                "nft_marketplace_core_nft_listing_content" => isset($data["unlockableContent"]) ? $data["unlockableContent"] : "",
                "nft_marketplace_core_nft_listing_explicit_meta" => isset($data["explicit"]) ? $data["explicit"] : false,
                "nft_marketplace_core_nft_listing_owner" => $transaction["from"],
                "nft_marketplace_core_nft_listing_currency_price" => "0",
                "nft_marketplace_core_nft_listing_address" => $transaction["to"],
                "nft_marketplace_core_nft_listing_token_id" => 1,
                "nft_marketplace_core_nft_listing_deployed_blockchain_id" => '0x' . dechex($transaction["chainId"])
            ];

            foreach ($data["stats"] as $stat) {
                if ($stat["name"] === "" || !isset($stat["value"]) || $stat["value"]["from"] === "" || $stat["value"]["to"] === "" || !is_array($stat) || !is_array($stat["value"])) {
                    break;
                }
                $my_post['meta_input']["nft_marketplace_core_nft_listing_group"][] = [
                    "nft_marketplace_core_nft_listing_stat_name" => esc_html($stat["name"]),
                    "nft_marketplace_core_nft_listing_stat_rate" => esc_html($stat["value"]["from"]),
                    "nft_marketplace_core_nft_listing_stat_out_of" => esc_html($stat["value"]["to"]),
                ];
            }

            foreach ($data["properties"] as $properties) {
                if ($properties["name"] === "" || $properties["type"] === "" || !is_array($properties)) {
                    break;
                }
                $my_post['meta_input']["nft_marketplace_core_nft_listing_properties_group"][] = [
                    "nft_marketplace_core_nft_listing_property_name" => esc_html($properties["type"]),
                    "nft_marketplace_core_nft_listing_property_value" => esc_html($properties["name"]),
                ];
            }

            foreach ($data["levels"] as $levels) {
                if ($levels["name"] === "" || !isset($levels["value"]) || $levels["value"]["from"] === "" || $levels["value"]["to"] === "" || !is_array($levels) || !is_array($levels["value"])) {
                    break;
                }

                $my_post['meta_input']["nft_marketplace_core_nft_listing_level_group"][] = [
                    "nft_marketplace_core_nft_listing_level_name" => esc_html($levels["name"]),
                    "nft_marketplace_core_nft_listing_level_rate" => esc_html($levels["value"]["from"]),
                    "nft_marketplace_core_nft_listing_level_out_of" => esc_html($levels["value"]["to"]),
                ];
            }

            for ($i = 0; $i < count($uploadData); $i++) {
                if(!isset($uploadData[$i]["id"])) {
                    $type = get_headers($uploadData[$i]["path"], 1)["Content-Type"];

                    if (strpos($type, 'application/json') === 0) {
                        $my_post['meta_input']["nft_marketplace_core_nft_listing_meta"] = $uploadData[$i]["path"];
                    } else {
                        $my_post['meta_input']["nft_marketplace_core_nft_listing_external_image"][] = $uploadData[$i]["path"];
                    }

                } else {
                    $type = get_post_mime_type($uploadData[$i]["id"]);
                    if (strpos($type, 'application/json') === 0) {
                        $my_post['meta_input']["nft_marketplace_core_nft_listing_meta"] = $uploadData[$i]["path"];
                    } else {
                        $my_post['meta_input']["nft_marketplace_core_nft_listing_assets"][] = $uploadData[$i]["id"];
                    }
                }
            }

            // Insert the post into the database
            $post = wp_insert_post($my_post);

            if (!isset($uploadData[0]["path"])) {
                set_post_thumbnail($post, $uploadData[0]["id"]);
            }

            if ($term !== false) {
                wp_set_post_terms($post, [$term->term_id], 'nft_listing_blockchains');
            }

            return new \WP_REST_Response(["success" => true, "payload" => [
                "image" => $uploadData["ipfs"] ?? $uploadData[0]["source_url"],
                "permalink" => get_permalink($post)
            ]], 200);
        }

        return new \WP_REST_Response(["success" => false, "payload" => [
            "message" => esc_html__("Post already exists",'nft-marketplace-core-lite')
        ]], 401);
    }
}
