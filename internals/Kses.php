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

use NFT_Marketplace_Core\Engine\Base;

class Kses extends Base
{
    /**
     * Initialize the class.
     *
     */
    public function initialize() {
        parent::initialize();

        add_action( 'wp_kses_allowed_html', [$this, 'nft_marketplace_core_kses_allowed_html'], 10, 2);
    }

    /**
     * List of allowed html.
     *
     * @param $tags
     * @param $context
     * @return \array[][]
     * @since 1.0.0
     */
    function nft_marketplace_core_kses_allowed_html($tags, $context): array
    {
        switch($context) {
            case 'html':
                return array(
                    'div' => array(
                        'class' =>[],
                        'style' => [],
                        'id' => []
                    ),
                    'i' => array(
                        'class' =>[],
                    ),
                    'span' => array(
                        'class' =>[],
                    ),
                    'a'	  => array(
                        'class'	=>[],
                        'data-post_id' =>[],
                        'id' =>[],
                        "href"=>[]
                    ),
                    'h2' => [
                        'class' => []
                    ],
                    'input' => [
                        'class' => [],
                        'name' => [],
                        'id' => [],
                        'type' => [],
                        'value' => [],
                        'placeholder' => [],
                        'checked' => []
                    ],
                    'select' => [
                        'class' => [],
                        'name' => [],
                        'id' => [],
                    ],
                    'option' => [
                        'class' => [],
                        'name' => [],
                        'id' => [],
                        'type' => [],
                        'value' => [],
                        'placeholder' => [],
                    ],
                    'fieldset' => [
                        'class' => []
                    ],
                    'label' => [
                        'class' => [],
                        'for' => [],
                        'id' => [],
                    ],
                    'strong' => [
                        'class' => []
                    ],
                    'p' => [
                        'class' => []
                    ]
                );
                break;
            case 'link':
                return array(
                    'a' => array(
                        'href' =>[],
                        'class' =>[],
                        'title' =>[],
                        'target' =>[],
                        'rel' =>[],
                        'data-commentid' =>[],
                        'data-postid' =>[],
                        'data-belowelement' =>[],
                        'data-respondelement' =>[],
                        'data-replyto' =>[],
                        'aria-label' =>[],
                    ),
                    'img' => array(
                        'src' =>[],
                        'alt' =>[],
                        'style' =>[],
                        'height' =>[],
                        'width' =>[],
                    ),
                );
                break;
            case 'icon':
                return array(
                    'i' => array(
                        'class' =>[],
                    ),
                );
                break;
            case 'categories':
                return array(
                    'a' => array(
                        'href' =>[],
                        'class' =>[],
                        'title' =>[],
                        'target' =>[],
                        'rel' =>[],
                    ),
                );
                break;
            case 'vite':
                return array(
                    'script' => array(
                        'src' =>[],
                        'type' =>[],
                        'crossorigin' =>[],
                    ),
                    "link" => [
                        'href' =>[],
                        'rel' =>[],
                    ]
                );
                break;
            default:
                return $tags;
        }
    }

}
