<?php 
//get unique id
$up_id = uniqid();
define ("FORM_MAX_NUM_UPLOAD_FILES",5);
define ("FORM_DEBUG_ON",true);
?>

<?php 
//process the forms and upload the files
if (!empty($_POST)) :?>
	
<?php
function sanitize_form_filename($file){
	$file = strtolower(preg_replace("/[^\w\.]/s", '' , $file) );
	return empty($file) ? rand() : $file;
}
	//retreive WP uploads directory
	$upload_dir = wp_upload_dir();
	$upload_dir = $upload_dir['basedir'];
	 
	//retreive and sanitize name from the form and use it for upload subdirectory
	$userdir = isset ($_POST['upload-form1-fname']) ? sanitize_form_filename( $_POST['upload-form1-fname'] ) : "";
	$userdir .= isset ($_POST['upload-form1-lname']) ? '_'.sanitize_form_filename( $_POST['upload-form1-lname'] ) : "";
	
	 
	//get date for upload subdirectory
	$date = getdate();
	$date_path = "{$date['mon']}.{$date['mday']}.{$date['year']}";
	$userdir = empty($userdir) ? rand()  : $userdir;
	 
	//create dir if not existent
	$upload_dir.= "/customer_web_uploads/$date_path/$userdir/";
	$upload_dir = preg_replace("/[\\/]/s", DIRECTORY_SEPARATOR , $upload_dir);
	if( !is_dir($upload_dir) ) mkdir($upload_dir, 0770, true);
	
	$upload_error = "";
	$files_array = array();
	for($i=1; $i <= FORM_MAX_NUM_UPLOAD_FILES; $i++)
	{
		$filename = 'upload-form1-file'.$i;
		if( !isset ($_FILES[$filename]) ) continue;
		
		if ($_FILES[$filename]['error'] > 0)
		  {
		    
		    switch ($_FILES[$filename]['error'])
		    {
		      case 1:	$upload_error .= "File '$filename': exceeded upload_max_filesize <br />";
			  			break;
		      case 2:	$upload_error .= "File '$filename': exceeded max_file_size <br />";
			  			break;
		      case 3:	$upload_error .= "File '$filename': only partially uploaded <br />";
			  			break;
		      case 4:	$upload_error .= "File '$filename': No file uploaded <br />";
			  			break;
			  case 6:   $upload_error .= "File '$filename': Cannot upload file, No temp directory specified <br />";
			  			break;
			  case 7:   $upload_error .= "File '$filename': Upload failed, Cannot write to disk <br />";
			  			break;
		    }
		    continue;
		  }
			
		  // put the file where we'd like it
		  $upfile = $upload_dir . sanitize_form_filename($_FILES[$filename]['name']);
		  
		  if (is_uploaded_file($_FILES[$filename]['tmp_name']))
		  {
		  	if (!move_uploaded_file($_FILES[$filename]['tmp_name'], $upfile))
		  	{
		  		$upload_error .= "File '$filename':Could not move file to destination directory<br />";
		  	}
		  	$files_array[]=sanitize_form_filename($_FILES[$filename]['name']);
		  }
		  else
		  {
		  	$upload_error .= "File '$filename':Possible file upload attack<br />";
		  }
	  
	}//ENDOF for($i=1; $i <= FORM_MAX_NUM_UPLOAD_FILES; $i++)
		
	  if(!empty($upload_error) && FORM_DEBUG_ON)
	  	echo "<p style='color:red;'>Uploading errors: </p> <p style='color:red;'>$upload_error</p>";
	  else {
	  		$file_count = count($files_array);
			$sub_str = $file_count > 1 ? "s were" : " was";
		  	echo "<p>$file_count file$sub_str uploaded successfully:<br />";
		  	foreach ($files_array as $file){
		  		echo "<i>$file</i><br />";
		  	}
		  	echo "</p>";
		}
	  
	  ?>
	  
<?php else:?>

<script type="text/javascript">
jQuery(document).ready(function() { 
//
	//show iframe on form submit
    jQuery("#upload-form1").submit(function(){
			//form validation
			val_status=true;
			if(jQuery('#upload-form1-fname').val() == '') {
					jQuery('#upload-form1-fname').parents('div').addClass('error');
					val_status = false;
				}
			if(jQuery('#upload-form1-lname').val() == '') {
				jQuery('#upload-form1-lname').parents('div').addClass('error');
				val_status = false;
			}
			if(jQuery('#upload-form1-email').val() == '') {
				jQuery('#upload-form1-email').parents('div').addClass('error');
				val_status = false;
			}
			if(val_status == false) return false;
        
    	   var url = '<?php echo get_template_directory_uri();?>/lib/upload/upload-status.php?up_id=<?php echo $up_id; ?>&theme_url=<?php echo urlencode( get_template_directory_uri() );?>'; 

    	   jQuery('#upload_frame').show();
    	   jQuery('#upload-form1').hide();
    	   jQuery('.progress-info').html('<i>Loading. Please wait.</i>');
    	   
           function set () {
        	   jQuery('#upload_frame').attr('src',url);
           }
           setTimeout(set,300); 
           
     
       return true;
    	
    });

    //javascript to fix input[type=file] visual
    //onchange copy extract and copy file name to visible text field
	pretty_file_helper = function(item){
		jQuery('#upload-form1-file'+item).change(function() {
		    var filename = jQuery(this).val();
			filename = filename.replace(/\\/g, '/');
	    	var index = filename.lastIndexOf('/') + 1;
	    	filename = filename.substr(index);
	    	jQuery('#upload-form1-file'+item+'-copy').val(filename);
		});
    }
	pretty_file_helper('1');

	//add file upload field if needed
	var file_field_counter=1;
	jQuery('#upload-form1-add-file').click(function(event) {
			file_field_counter++;
			upload_file_field  = '<div class="input-append" style="position:relative;">';
			upload_file_field += '<input name="upload-form1-file'+file_field_counter+'" type="file" id="upload-form1-file'+file_field_counter+'" style="position:absolute; top:0; left:80px; z-index:10; opacity:0; filter:alpha(opacity: 0);" class="upload-form1-file-field">';
			upload_file_field += '<input class="span3" type="text" id="upload-form1-file'+file_field_counter+'-copy">';
			upload_file_field += '<button class="btn"  type="button" style="margin-left:-1px;">Browse</button></div>';
			jQuery('.upload-form1-file-wrap').append(jQuery(upload_file_field));
			pretty_file_helper(file_field_counter);
			if(file_field_counter >= <?php echo FORM_MAX_NUM_UPLOAD_FILES; ?>) jQuery(this).css("display","none");
			
		});


});

</script>


<div id='upload-wrap'>
  <form action="#" method="post" enctype="multipart/form-data" name="upload-form1" id="upload-form1">
    
    <div class="control-group"> 
	    <label>First Name: <i>(required)</i></label>
	    <input name="upload-form1-fname" type="text" id="upload-form1-fname" class="span3" />
    </div>
  	
  	<div class="control-group"> 
		<label>Last Name: <i>(required)</i></label>
	    <input name="upload-form1-lname" type="text" id="upload-form1-lname" class="span3" />
	</div>
	 
	<div class="control-group"> 
	    <label>Your email address: <i>(required)</i></label>
	    <input name="upload-form1-email" type="text" id="upload-form1-email" class="span3" />
   	</div>
    
<!--APC hidden field-->
    <input type="hidden" name="APC_UPLOAD_PROGRESS" id="progress_key" value="<?php echo $up_id; ?>"/>
<!---->
    <div class="upload-form1-file-wrap">
		<label>Choose a file to upload:</label>
		<div class="input-append" style="position:relative;">
			<input name="upload-form1-file1" type="file" id="upload-form1-file1" style="position:absolute; top:0; left:80px; z-index:10; opacity:0; filter:alpha(opacity: 0);" class="upload-form1-file-field">
		   	<input class="span3" type="text" id="upload-form1-file1-copy">
		   	<button class="btn"  type="button" style="margin-left:-5px;">Browse</button>
		</div>
	</div><!-- ENDOF .upload-form1-file-wrap -->
	<br />
	<button id="upload-form1-add-file" class="btn" type="button"> + Add More Files</button>
	<br />
	<br />
    <input name="Submit" type="submit" id="submit" value="Submit" class="btn btn-primary" />
  </form>
  <div class="progress-info"></div>
  <!--Include the progress abr-->  
    <iframe id="upload_frame" name="upload_frame" frameborder="0" border="0" src="" scrolling="no" scrollbar="no" style="display:none; height:50px; width:400px;"> </iframe> 
<!---->
 </div><!-- ENDOF #upload-wrap -->
 <?php endif;?>