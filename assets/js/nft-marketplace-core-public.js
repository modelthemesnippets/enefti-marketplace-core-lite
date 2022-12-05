(function( $ ) {

	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	jQuery(document).ready(function(){
		// Style the select fields

        try{
            jQuery('.nft-marketplace-ordering select').niceSelect();
        }catch(e){
        }

		//nft_listing taxonomy page anchor link
		jQuery(document).on('click', 'a.anchor-link', function (event) {
	    	event.preventDefault();
	    	var top_offset = jQuery(jQuery.attr(this, 'href')).offset().top - 30;
	    	jQuery('html, body').animate({
	        	scrollTop: top_offset
	    	}, 500);
	    	jQuery('a.anchor-link').removeClass('scrolled');
	    	jQuery(this).addClass('scrolled');
	    	window.location.hash = jQuery.attr(this, 'href');
		});

		jQuery("#nft-marketplace-core-nsfw-remove-button").on("click", () => {
			jQuery("#nft-marketplace-core-hide-content-nsfw").removeClass("nft-marketplace-core-hide-content-nsfw");
		})

		jQuery("#nft_marketplace_core_nft_listing_currency_price").on("keyup", () => {
			const fee = jQuery("#nft-marketplace-core-table-fee")[0].innerText.replace("%","")
			const price = jQuery("#nft_marketplace_core_nft_listing_currency_price").val();
			const priceAfterCut = price - (fee * (price / 100));
			jQuery("#nft-marketplace-core-your-cut")[0].innerText  = priceAfterCut.toFixed(3);
		})

		function computeDatePeriod(startTimestampForm, durationInMinutes) {
			if (durationInMinutes === "") {
				return;
			}

			const tNow = new Date(Date.now());
			let endTimestamp = new Date(Date.now());

			let startTimestamp = tNow;

            if(startTimestampForm !== "") {
                startTimestamp = new Date(startTimestampForm);
                startTimestamp.setHours(tNow.getHours())
                startTimestamp.setMinutes(tNow.getMinutes())
                startTimestamp.setSeconds(tNow.getSeconds())
				endTimestamp = new Date(startTimestamp);
            }

            endTimestamp.setMinutes(endTimestamp.getMinutes() + parseInt(durationInMinutes))
            jQuery("#nft-marketplace-core-available-purchase")[0].innerHTML = "Start Time: <br/>" + startTimestamp.toLocaleString('en-US') + "<br/><br/> End Time:  <br/>" + endTimestamp.toLocaleString('en-US')
		}

		jQuery("#nft_marketplace_core_nft_listing_timestamp").on("change", () => {
			const startTimestamp = jQuery("#nft_marketplace_core_nft_listing_timestamp").val();
			const durationInMinutes = jQuery("#nft_marketplace_core_nft_listing_duration").val();

			computeDatePeriod(startTimestamp, durationInMinutes)
		})

		jQuery("#nft_marketplace_core_nft_listing_duration").on("keyup", () => {
			const startTimestamp = jQuery("#nft_marketplace_core_nft_listing_timestamp").val();
			const durationInMinutes = jQuery("#nft_marketplace_core_nft_listing_duration").val();

			computeDatePeriod(startTimestamp, durationInMinutes)
		})

		jQuery('#mtkb_knowledge_searchform #mtkb_keyword').on("keyup", function() {
			jQuery.ajax({
		        url: ajax_search.url,
		        type: 'post',
		        data: { action: 'data_fetch', mtkb_keyword: jQuery('#mtkb_keyword').val() },
		        success: function(data) {
		            jQuery('#mtkb_datafetch').html( data );
		        }
		    });
		})


		/*  Single NFT Tabs */
		jQuery(document).ready(function () {
		    jQuery('.nft-tab-list a').on('click', function (event) {
		        event.preventDefault();
		        jQuery('.tab-active').removeClass('tab-active');
		        jQuery(this).parent().addClass('tab-active');
		        jQuery('.nft-listing-tabs section').hide();
		        jQuery(jQuery(this).attr('href')).show();
		    });


		    jQuery('.nft-tab-list a:first').trigger('click');
		});


		//love button
		jQuery(".nft-listing-wishlist a").on('click', function() {
            var love_button = jQuery(this);
            var post_id = love_button.data("post_id");

            jQuery.ajax({
                type: "post",
                url: ajax_var.url,
                data: "action=nft_marketplace_core_love_post&_wpnonce=" + ajax_var.nonce + "&nft_marketplace_core_love_post=&post_id=" + post_id,
                success: function(response) {
                    jQuery(love_button).parent().toggleClass("nft-listing-wishlist-loved")
                    jQuery(love_button).siblings("span").html(response.count);
                    jQuery(love_button).html(response.message);
                },
            });
            return false;
        })

	});

})( jQuery );



