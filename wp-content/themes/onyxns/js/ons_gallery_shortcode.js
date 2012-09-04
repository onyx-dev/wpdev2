jQuery(document).ready(function(){
	var image_item_counter = 0;
	var item = "\
		<div class='ons-gallery-item' id='ons-gallery-item${counter}'>\
			<div class='item-header'>\
				<span class='item-counter'>Image ${counter}</span>\
				<a href='#' class='item-remove'>remove image</a>\
			</div>\
			<div class='thumbnail-image-group'>\
				<div class='image-wrap ons-media-upload-btn clearfix'>\
					<img src='${thumb_url}' id='thumb-img${thumb_id}' alt='Thumbnail Image' class='img-sample' style='display:none; width:250px; height:200px;' />\
					<p class='field-description'>Thumbnail image <i style='color:#888;'>(Required)</i></p>\
				</div>\
				<input type='hidden' name='thumburl' value='${thumb_url}' class='ons-media-upload-url thumbnail-img' />\
				<input type='hidden' name='id' value='${thumb_id}' class='ons-media-upload-id thumbnail-img' />\
			</div>\
			<div class='link-image-group'>\
				<div class='image-wrap ons-media-upload-btn clearfix'>\
					<img src='${link_url}' id='link-img${link_id}' alt='Link Image' class='img-sample' style='display:none; width:250px; height:200px;' />\
					<p class='field-description'>Link image <i style='color:#888;'>(Optional)</i></p>\
				</div>\
				<input type='hidden' name='linkurl' value='${link_url}' class='ons-media-upload-url link-img' />\
				<input type='hidden' name='id' value='${link_id}' class='ons-media-upload-id link-img' />\
			</div>\
			<div class='title-image-group'>\
				<label for='title-img${counter}'>Title:</label>\
				<input type='text' name='titleimg$1' id='title-img${counter}' value='${title}' class='title-img' style='width:100%;' />\
			</div>\
			</div>";
	
	add_item = function(options){
		image_item_counter++;
		var defaults = {
	             counter:image_item_counter,
				 thumb_url:'',
				 thumb_id:'',
				 link_url:'',
				 link_id:'',
				 title:''
				};

		// variables declaration and precaching images and parent container
		var options = jQuery.extend(defaults, options),

		temp_item = item;//.replace(/\$1/gi, image_item_counter);
		
		for (arg in options){
			regexp=new RegExp('\\$\\{' + arg + '\\}',"gi");
			
			temp_item = temp_item.replace(regexp, options[arg]);
			//alert(temp_item)
		}

		jQuery('.ons-gallery-items').append(jQuery(temp_item));
	}
	//register item-remove handler
	jQuery('body').delegate('.item-remove','click',function(){
		jQuery(this).parent().parent().remove();
		jQuery('#ons-gallery-generate-shortcode').trigger('click');
		return false;
	});
	
	//register ons-gallery-add-item handler
    jQuery('#ons-gallery-add-images').click(function(){
    	add_item();
    });
    
    //register change event handler triggered by file selection
    jQuery('body').delegate('.ons-media-upload-url', 'ons_change', function(){
    	group = jQuery(this).parent();
    	jQuery('.img-sample', group).attr('src',jQuery(this).val()).fadeIn('slow');
    	jQuery('#ons-gallery-generate-shortcode').trigger('click');
    });
    
    //register focusout event for input.title-img
    jQuery('body').delegate('input.title-img', 'focusout',function(){
    	jQuery('#ons-gallery-generate-shortcode').trigger('click');
    });
    
    generate_shortcode = function(){
    	var shortcode='[ons_gallery]';
    	jQuery('.ons-gallery-item').each(function(){
    		
    		//thumb_url = jQuery('.ons-media-upload-url.thumbnail-img',this).val();
    		thumb_id = jQuery('.ons-media-upload-id.thumbnail-img',this).val();
    		//link_url = jQuery('.ons-media-upload-url.link-img',this).val();
    		link_id = jQuery('.ons-media-upload-id.link-img',this).val();
    		title = jQuery('.title-img',this).val();
    		
    		if(thumb_id) 	shortcode +='[ons_gallery_item';
    		else			return;
    		
    		if(link_id){
    			//shortcode += ' thumb_url="' + thumb_url + '"';
    			shortcode += ' thumb_id="' + thumb_id + '"';
    			//shortcode += ' link_url="' + link_url + '"';
    			shortcode += ' link_id="' + link_id + '"';
    		}else{
    			//shortcode += ' thumb_url="' + thumb_url + '"';
    			shortcode += ' thumb_id="' + thumb_id + '"';
    		}
    		
    		if(title){
    			title = title.replace(/\s*$/g,'')
    			title = title.replace(/"/g,"'")
    			shortcode += ' title="' + title + '"';
    		}
    			
    		shortcode += ']';
    	});
    	shortcode +='[/ons_gallery]';
    	
    	return shortcode;
    }
    
    //generate shortcode
    jQuery('#ons-gallery-generate-shortcode').click(function(){
    	jQuery('.ons-gallery-shortcode').val(generate_shortcode());
    });
    
    //send to editor
    jQuery('#ons-gallery-send-shortcode').click(function(){
    	window.send_to_editor(generate_shortcode());
    });
    
    //read shortcode
    jQuery('#ons-gallery-read-shortcode').click(function(){
    	jQuery('.ons-gallery-items').empty();
    	image_item_counter = 0;
    	
    	shortcode = jQuery('.ons-gallery-shortcode').val();
    	shortcode = shortcode.match(/\[ons_gallery_item([^\]]+)\]/gi);
    	if(!shortcode) return;
    	for (var i = 0; i < shortcode.length; i++) {
    		thumb_id = shortcode[i].match(/thumb_id="(\d+)"/)[1];
    		if(!thumb_id) continue;
    		
    		link_id = shortcode[i].match(/link_id="(\d+)"/);
    		if(link_id) link_id = link_id[1];
    		else link_id='';
    		
    		title = shortcode[i].match(/title="([^"]*)"/);
    		if(title) title = title[1];
    		else title='';
    		
    		add_item({
    			thumb_id : thumb_id,
    			link_id : link_id,
    			title : title
    			});
    		//load images with ajax
    		jQuery('#thumb-img' + thumb_id).after(jQuery('<img src="'+ MyAjax.theme_url+'/images/preloader.gif" alt="" class="preloader" />')).parent().css('backgroundPosition', '-999px center');
    		//jQuery('#thumb-img' + thumb_id).parent().css('background', 'none');
    		get_image_url_by_id(thumb_id,'post-thumbnail',function(response, id){
    			jQuery('#thumb-img' + id).attr('src', response).fadeIn('slow').parent().css('backgroundPosition', 'center center');
    			jQuery('#thumb-img' + id).siblings('.preloader').remove();
    		})
    		if(link_id){
    			jQuery('#link-img' + link_id).after(jQuery('<img src="'+ MyAjax.theme_url+'/images/preloader.gif" alt="" class="preloader" />')).parent().css('backgroundPosition', '-999px center');
    			//jQuery('#link-img' + thumb_id).parent().css('background', 'none');
    			get_image_url_by_id(link_id,'post-thumbnail',function(response, id){
        			jQuery('#link-img' + id).attr('src', response).fadeIn('slow').parent().css('backgroundPosition', 'center center');
        			jQuery('#link-img' + id).siblings('.preloader').remove();
        		})
    		}
    	}
    	//alert (asd);
    	
    });
    
    get_image_url_by_id = function (id, img_size, call_back){
    	jQuery.post(
    		    // see tip #1 for how we declare global javascript variables
    		    MyAjax.ajaxurl,
    		    {
    		        // here we declare the parameters to send along with the request
    		        // this means the following action hooks will be fired:
    		        // wp_ajax_nopriv_myajax-submit and wp_ajax_myajax-submit
    		        action : 'get_image_url_by_id',
    		 
    		        // other parameters can be added along with "action"
    		        attachment_id : id,
    		        image_size : img_size
    		    },
    		    function( response ) {
    		        call_back( response, id );
    		    }
    		);
    }
    //add first item to ons-gallery-items
	add_item();
	
});