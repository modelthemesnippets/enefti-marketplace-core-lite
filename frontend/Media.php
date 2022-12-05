<?php
/*--------------------------------------------------
Author Dashboard Custom field [image]
--------------------------------------------------*/

namespace NFT_Marketplace_Core\Frontend;

use NFT_Marketplace_Core\Engine\Base;

/**
 * Class wrapper for Front End Media example
 */
class Media extends Base {

    public function initialize()
    {
        parent::initialize();

        add_action( 'init', array( $this, 'init' ) );
    }

	function init() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		// CHECK USER ROLE
		$user = wp_get_current_user();
		$allowed_roles = array('editor', 'customer', 'author', 'contributor');
		if( array_intersect($allowed_roles, $user->roles ) ) {
			add_filter( 'ajax_query_attachments_args', 'filter_media', 10, 1 );
		}

		// add_filter( 'ajax_query_attachments_args', array( $this, 'filter_media' ) );
		add_shortcode( 'frontend-button', array( $this, 'frontend_shortcode' ) );
	}
	/**
	 * Call wp_enqueue_media() to load up all the scripts we need for media uploader plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-nft-marketplace-core-i18n.php';
	 */
	function enqueue_scripts() {
		wp_enqueue_media();
		wp_enqueue_script( 'media-lib-uploader-js', plugin_dir_url(__DIR__) . 'assets/js/media-lib-uploader.js', array('jquery'), '1.0.0', true );
	}
	/**
	 * This filter insures users only see their own media
	 */
	function filter_media( $query = array() ) {
	    $user_id = get_current_user_id();
	    if( $user_id ) {
	        $query['customer'] = $user_id;
	    }
	    return $query;

	}
	function frontend_shortcode( $args ) {
	    $current_user = wp_get_current_user();
		$allowed_roles = array('editor', 'administrator', 'author', 'customer');
		if( array_intersect($allowed_roles, $current_user->roles ) ) {
			$str = esc_html__( 'Select Image', NFT_MARKETPLACE_CORE_TEXTDOMAIN );
			return '
			
			<div class="form-group">  
				<div class="upload-images main-uploader col-md-12">
					<label>'.esc_html__('Profile Banner Image',NFT_MARKETPLACE_CORE_TEXTDOMAIN).'</label>
					<div class="spacer-upload">
						<div class="text">' . $str . '</div>
					</div>			
				</div>
				<div class="clearfix"></div>
				<div class="group_pictures_holder row">
                    <div class="col-md-4 single-featured-media" id="nft-marketplace-core-to-replace-image" name="53">
                        <img class="imges" name="65" src="'.esc_attr($args["image"]).'">
                    </div>
                    <input type="hidden" id="nft_marketplace_core_banner_img" name="nft_marketplace_core_banner_img" value="" />
                </div>
			</div>
			<div class="clearfix"></div>';
		}
	}
}
