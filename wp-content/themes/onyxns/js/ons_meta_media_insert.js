jQuery(document).ready(function() {
var flag=false;
var btn;

jQuery('body').delegate('.ons-media-upload-btn', 'click', function() {
    flag=true;
    //save upload button id for future use to find text field associated with the button
    btn=jQuery(this);
    tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
    return false;
});

window.original_send_to_editor = window.send_to_editor;
window.send_to_editor = function(html) {
    if(flag)
    {
    	//retreive image id from class attribute
    	img_id = html.match(/wp-image-\d+/gi);
    	img_id = img_id[0];
    	img_id = img_id.replace(/([a-z\-])/gi, '');
    	btn.siblings('.ons-media-upload-id').val(img_id).trigger('ons_change');
    	
        imgurl = jQuery('img',"<div>"+html+"</div>").attr('src'); 
        btn.siblings('.ons-media-upload-url').val(imgurl).trigger('ons_change');
        
        tb_remove();
        flag=false;
    } else {
	window.original_send_to_editor(html);
    }

}
 
});