jQuery(document).ready(function($){
  "use strict";

  $('.upload-images.main-uploader').on('click', (function(e) {

    var media_upload_clicked_main = $(this);
    var custom_uploader;


    e.preventDefault();
    //If the uploader object has already been created, reopen the dialog
    if (custom_uploader) {
      custom_uploader.open();
      return;
    }

    //Extend the wp.media object
    custom_uploader = wp.media.frames.file_frame = wp.media({
      title: 'Choose Image',
      button: {
        text: 'Choose Image'
      },
      multiple: true
    });
    custom_uploader.on('select', function() {
      var selection = custom_uploader.state().get('selection');
      selection.map( function( attachment ) {
        attachment = attachment.toJSON();


        // ================================================================================================================================
        // LIST UPLOADED IMAGES
        // ================================================================================================================================
        media_upload_clicked_main.parent().find(".group_pictures_holder").append("<div class='col-md-4 single-featured-media' name="+attachment.id+"><img class='imges' name="+attachment.id+" src="+attachment.url+"><div class='delete' name="+attachment.id+"><i class=\"fa fa-times\"></i></div></div>");
        media_upload_clicked_main.parent().find("#nft-marketplace-core-to-replace-image").remove();

        // ================================================================================================================================
        // SET THE HIDDEN VALUE
        // ================================================================================================================================
        // Empty current input value
        media_upload_clicked_main.parent().find( "#nft_marketplace_core_banner_img" ).val('');
        // get media ids
        var input_ids_uploaded = [];
        media_upload_clicked_main.parent().find('.single-featured-media').each(function() {
          // add ids to array
          input_ids_uploaded.push(jQuery(this).attr('name'));
        });
        // set final ids value to input
        media_upload_clicked_main.parent().find( "#nft_marketplace_core_banner_img" ).val(input_ids_uploaded);


        // ================================================================================================================================
        // DELETE MEDIA FROM THE LIST
        // ================================================================================================================================
        media_upload_clicked_main.parent().find('.delete').on("click" ,(function(e) {
          var selector_parent = media_upload_clicked_main.parent(),
              selector_child = jQuery(this).attr('name');
          let input_ids;
          input_ids = media_upload_clicked_main.parent().find('#nft_marketplace_core_banner_img[type=hidden]').val(),
              ifinal;

          // remove the image from the list
          $(this).parent().remove();

          var input_ids_array = input_ids.split(',');
          if(jQuery.inArray(selector_child, input_ids_array) !== -1){
            var ifinal = input_ids_array.splice( $.inArray(selector_child,input_ids_array) ,1 );
          }

          // get media ids
          var input_ids_uploaded_final = [];
          media_upload_clicked_main.parent().find('.single-featured-media').each(function() {
            // add ids to array
            input_ids_uploaded_final.push(jQuery(this).attr('name'));
          });


          // put the final value back to input's value
          if (input_ids_uploaded_final) {
            // console.log(input_ids_uploaded_final);
            media_upload_clicked_main.parent().find('#nft_marketplace_core_banner_img[type=hidden]').val(input_ids_uploaded_final);
          }

        }));



      });
    });
    custom_uploader.open();
  }));



  // ==============================================================================================================================================================
  // MEDIA UPLOADER FOR INSTRUCTIONS
  // ==============================================================================================================================================================
  jQuery( ".upload-images.instruction-media-uploader" ).each(function() {
    $(this).on("click",(function(e) {

      var media_upload_clicked = $(this);
      var custom_uploader_instructions;

      e.preventDefault();
      //If the uploader object has already been created, reopen the dialog
      if (custom_uploader_instructions) {
        custom_uploader_instructions.open();
        return;
      }
      //Extend the wp.media object
      custom_uploader_instructions = wp.media.frames.file_frame = wp.media({
        title: esc_html__('Choose Image','nft-marketplace-core-lite'),
        button: {
          text: esc_html__('Choose Image','nft-marketplace-core-lite')
        },
        multiple: true
      });
      custom_uploader_instructions.on('select', function() {
        var selection = custom_uploader_instructions.state().get('selection');
        selection.map( function( attachment ) {
          attachment = attachment.toJSON();


          // ================================================================================================================================
          // LIST UPLOADED IMAGES
          // ================================================================================================================================
          // Append uploaded images to mini the gallery
          media_upload_clicked.parent().find(".group_pictures_holder").append("<div class='single-instruction-media' name="+attachment.id+"><img class='imges' name="+attachment.id+" src="+attachment.url+"><div class='delete' name="+attachment.id+"><i class=\"fa fa-times\"></i></div></div>");


          // ================================================================================================================================
          // SET THE HIDDEN VALUE
          // ================================================================================================================================
          // Empty current input value
          media_upload_clicked.parent().find( "#group_pictures_instructions" ).val('');
          // get media ids
          var input_ids_uploaded = [];
          media_upload_clicked.parent().find('.single-instruction-media').each(function() {
            // add ids to array
            input_ids_uploaded.push(jQuery(this).attr('name'));
          });
          // set final ids value to input
          media_upload_clicked.parent().find( "#group_pictures_instructions" ).val(input_ids_uploaded);


          // ================================================================================================================================
          // DELETE MEDIA FROM THE LIST
          // ================================================================================================================================
          media_upload_clicked.parent().find('.delete').on("click",(function(e) {
            var selector_parent = media_upload_clicked.parent(),
                selector_child = jQuery(this).attr('name');
                input_ids = media_upload_clicked.parent().find('#group_pictures_instructions[type=hidden]').val(),
                ifinal;

            // remove the image from the list
            $(this).parent().remove();

            var input_ids_array = input_ids.split(',');
            if(jQuery.inArray(selector_child, input_ids_array) !== -1){
              var ifinal = input_ids_array.splice( $.inArray(selector_child,input_ids_array) ,1 );
            }

            // get media ids
            var input_ids_uploaded_final = [];
            media_upload_clicked.parent().find('.single-instruction-media').each(function() {
              // add ids to array
              input_ids_uploaded_final.push(jQuery(this).attr('name'));
            });


            // put the final value back to input's value
            if (input_ids_uploaded_final) {
              // console.log(input_ids_uploaded_final);
              media_upload_clicked.parent().find('#group_pictures_instructions[type=hidden]').val(input_ids_uploaded_final);
            }

          }));

        });
      });
      custom_uploader_instructions.open();
    }));
  });

});
