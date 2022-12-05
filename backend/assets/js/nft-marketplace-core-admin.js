(function( $ ) {

	'use strict';
	 jQuery(document).ready(function(){
		jQuery('input#nft_marketplace_core_nft_listing_content_meta').on( "click", function(){
			jQuery('.cmb2-id-nft-marketplace-core-nft-listing-content').hide();
		   	if(jQuery(this).prop('checked')) {
		   		jQuery('.cmb2-id-nft-marketplace-core-nft-listing-content').show();
		   	}else{
		   		jQuery('.cmb2-id-nft-marketplace-core-nft-listing-content').hide();
		   	}
		});
	});
	jQuery('input#nmcps-nft_marketplace_core_panel_styling\[nft_marketplace_core_dark\]').removeAttr('checked');

	jQuery(document).ready(function(){
		if(jQuery('body').hasClass('nft-listing_page_nft-marketplace-core-panel')){
			
			function isScrolledIntoView(elem)
			{
			    var docViewTop = $(window).scrollTop();
			    var docViewBottom = docViewTop + $(window).height();
			    var elemTop = $(elem).offset().top;
			    return ((elemTop <= docViewBottom) && (elemTop >= docViewTop));
			}

			jQuery(window).scroll(function(){
			   if (isScrolledIntoView($('.ant-col'))) {
			      	jQuery(document).ready(function(){
			      		jQuery('#menu-posts-nft-listing ul li').removeClass("current");
						jQuery('#menu-posts-nft-listing ul li:last-child').addClass("current");
					});
				} else if (isScrolledIntoView($('.nft-panel-wrapper'))){
					jQuery(document).ready(function(){
						jQuery('#menu-posts-nft-listing ul li:last-child').removeClass("current");
						jQuery('#menu-posts-nft-listing ul li:nth-last-child(2)').addClass("current");
					});
				}
			})
		}
	})

})( jQuery );