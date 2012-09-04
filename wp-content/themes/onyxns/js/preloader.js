jQuery(function(){
	
	jQuery.fn.preloader = function(options){
		
		var defaults = {
						 path_to_icon:"",
						 image_selector:"img",
			             delay:200,
						 preload_parent:"span",
						 check_timer:300,
						 ondone:function(){ },
						 oneachload:function(image){  },
						 fadein:500 
						};
		
		// variables declaration and precaching images and parent container
		 var options = jQuery.extend(defaults, options),
		 root = jQuery(this)  ,  timer , delaySum = options.delay;
		 
		 //retrieve path from injected variable
		 options.path_to_icon = OnsTheme.theme_url+'/imges/preloader.gif';
		 
		 //hide only images that are not loaded/cached
		 images = root.find(options.image_selector).filter(function(){
			 if(this.complete != true){ jQuery(this).css({"visibility":"hidden",opacity:0}); return true;}
			 return false;
		 });
		
		 
		 init = function(){

			 timer = setInterval(function(){
	
					if(images.size() == 0)
					{
						clearInterval(timer);
						options.ondone();
						return;
					}
					
					images = images.filter(function(){
						if(this.complete==true){
							options.oneachload(this);
							
							//delaySum = delaySum + options.delay;
							
							//jQuery(this).css("visibility","visible").delay(delaySum).animate({opacity:'1'},options.fadein,
							jQuery(this).css("visibility","visible").animate({opacity:'1'},options.fadein,
							function(){ jQuery(this).parent().removeClass("preloader");   });
							return false;
						}
						return true;
					});
			
				},options.check_timer) //ENDOF setInterval
			 } ;//ENDOF timer
		
		images.each(function(){
			
			image = jQuery(this);
			
			image.wrap("<"+ options.preload_parent +" class='preloader' />");
			
			wrap = image.parent();
			wrap.css({
				float : image.css('float'),
				display : image.css('display'),
				margin : image.css('margin')
			});
		});
		
		if(options.path_to_icon != ""){
			var icon = jQuery("<img />",{
				
				id : 'loadingicon' ,
				src : options.path_to_icon
				
				}).hide().appendTo("body").get(0);
			
			
			
			timer = setInterval(function(){
				
				if(icon.complete == true)
				{
					clearInterval(timer);
					init();
					jQuery(icon).remove();
					return;
				}
				
				},100);
		}else{
			init();
		}
		
		}//ENDOF jQuery.fn.preloader
});