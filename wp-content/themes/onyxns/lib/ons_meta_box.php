<?php
/**
 * Create meta box for admin pages and posts in WordPress
 *
 * Compatible with custom post types since WordPress 3.0
 * Support input types: text, textarea, checkbox, checkbox list, radio box, select
 *
 * @author: Alex K
 * @url: http://www.onyxns.com
 * @version: 1.0
 * 
 * USAGE:
$meta_box = new Ons_Meta_Box(
    array(
	'id' => 'my_meta_box',							// meta box id, unique per meta box
	'title' => 'My Meta Box',		        		// meta box title
	'pages' => array('post', 'page', 'custom'),		// post types, accept custom post types as well
	'context' => 'normal',							// the part of the page where the edit screen section should be shown ('normal', 'advanced', or 'side')
	'priority' => 'default',                        // the priority within the context where the boxes should show ('high', 'core', 'default' or 'low')
);
$meta_box->add_field(
    array(
	'name' => 'My Field',				    		// field name
	'description' => 'Description',	                // optional: field description
	'id' => 'my-field',			            		// field id
	'type' => 'text',				    			// supported field types: text, textarea, select, select_multiple, radio, checkbox, checkbox_group, file
        'options' => array(				    		// optional: array of key => value pairs used with select or checkbox group
				's' => 'Small',
				'm' => 'Medium',
				'l' => 'Large'
			),
	'default' => '',			            		// optional: default value	can be a key from options array
	'save_as_taxonomy' => 'taxonomy',		     	// save data as taxomony, if not specified save as post_meta
	'validate' => function($value){}, 				// optional: validate callback function
	'separator' => '<div class="hr"></div>'			//optional separator between feild items
));
 */
class Ons_Meta_Box {        
    protected $_meta_box;
    protected $_fields;
    //initialize meta box
    function __construct($meta_box) {
        if (!is_admin()) return;
        
        //save init values
        $this->_meta_box = $meta_box;	
        $this->_fields=array();
        $this->error = '';
        //register functions to add and save meta box
        add_action( 'add_meta_boxes', array( &$this, 'add_meta' ) );
        add_action( 'save_post', array( &$this, 'save_meta' ) );
                
    }
    
/*****************************************************************
* Add fields
******************************************************************/
    function add_field($field_settings){
        $default_settings = array(
            'name' => 'My Field',   // field name
            'description' => '',           // field description
            'id' => null,	    // field id
            'type' => 'text',	    // supported field types: text, textarea, select, select_multiple, radio, checkbox, checkbox_group, file
            'options' => null,	    // array of key => value pairs 			
            'default' => '',	    // default value, optional
	    'save_as_taxonomy' => '',//set default behavior of meta_box
            'validate' => null,    // validate callback function
	    'separator' => '<div class="hr"></div>');//optional field separator
        //merge default setting and user settings, user settings will overwrite default settings and keep only fields defined in default settings
        $field_settings = array_intersect_key($field_settings + $default_settings, $default_settings);
	//determine if we are working with single or multiple values
        if($field_settings['type'] == 'checkbox_group' || $field_settings['type']=='select_multiple')
	    $field_settings['single']=false;
	else
	    $field_settings['single']=true;
	    
        $this->_fields[] = $field_settings;

    }

/*****************************************************************
* Add meta box to pages and specify function to draw it
******************************************************************/
    function add_meta(){
        foreach ($this->_meta_box['pages'] as $page) {
	    add_meta_box($this->_meta_box['id'],
                        $this->_meta_box['title'],
                        array(&$this, 'show_meta'),//callback
                        $page,
                        $this->_meta_box['context'],//The part of the page where the edit screen section should be shown ('normal', 'advanced', or 'side')
                        $this->_meta_box['priority']);//The priority within the context where the boxes should show ('high', 'core', 'default' or 'low') 
	}
    }
/*****************************************************************
* Draw meta box
******************************************************************/
    function show_meta($post) {        
        // Use nonce for verification
        wp_nonce_field(basename(__FILE__), 'ons_meta_box_nonce');
	?>
	<style type="text/css">
	    .meta-box div.hr{
		border:none;
		border-top: 1px dotted #bbb;
		width: 100%;
		margin: 0;
		padding: 0;
	    }
	    .meta-box-item{
		    width: auto;
		    padding: 5px;
		    overflow: hidden;
	    }
	    .meta-box-item label{
		    float: left;
		    width: 100px;		    
		    text-align: left;
		    clear: left;
	    }
	    .meta-box-item .field{
		    float: left;
		    width: auto;
		    margin-left:10px;		
	    }
	    .meta-box-item .field .input-text, .meta-box-item .field textarea{
		width: 300px;
	    }
	    .meta-box-item .field textarea{
		height: 100px;
	    }
	    .meta-box-item .description{
		clear: both;
		color: #999;
		padding-top: 10px;
		margin-left: 110px;
		
	    }
	</style>
        <?php
	echo "<div class='meta-box {$this->_meta_box['id']}'>";

	foreach ($this->_fields as $field) {
	    $meta='';
	    
	    if($field['save_as_taxonomy']=='')
	    {
		//retrieve data as post_meta
		$meta = get_post_meta($post->ID, $field['id'], $field['single']);
	    }else{
		//retrieve data as taxonomy			    
		if($terms = get_the_terms($post->ID,$field['save_as_taxonomy']))
		{
		    foreach ( $terms as $term ) {
			$meta = $term->name;
		    }
		}
	    }
	    
	    //debug( "\r\n \t {$field['name']} : meta->".print_r($meta, true));
	    $meta = !empty($meta) ? $meta : $field['default'];
            
            // call separated methods for displaying each type of field
	    echo "<div class='meta-box-item'>";
            call_user_func(array(&$this, 'draw_field_' . $field['type']), $field, $meta);
	    echo "</div>";
	    echo $field['separator'];
        }
	echo '</div><!--ENDOF meta box-->';
    }

/*****************************************************************
* Draw fields in meta box
******************************************************************/   
    function before_field($field, $meta) {
        //print label
	echo "<label for='{$field['id']}'>{$field['name']}</label> <div class='field'>";
    }

    function after_field($field, $meta) {
	echo "</div>";
        //print description
        if ($field['description'] != '')
            echo "<div class='description'>{$field['description']} </div>";
    }

    function draw_field_text($field, $meta) {
	$this->before_field($field, $meta);
	echo "<input type='text' name='{$field['id']}' id='{$field['id']}' value='$meta' class='input-text' />";
	$this->after_field($field, $meta);
    }

    function draw_field_textarea($field, $meta) {
	$this->before_field($field, $meta);
	echo "<textarea name='{$field['id']}' id='{$field['id']}'>$meta</textarea>";
	$this->after_field($field, $meta);
    }

    function draw_field_select($field, $meta) {
    	if (!is_array($meta)) $meta = (array) $meta;
        $this->before_field($field, $meta);
	echo "<select name='{$field['id']}'>";
        foreach ($field['options'] as $key => $value) {
            echo "<option value='$key'" . selected(in_array($key, $meta), true, false) . ">$value</option>";
	}
	echo "</select>";
	$this->after_field($field, $meta);
    }
    function draw_field_select_multiple($field, $meta) {
    	if (!is_array($meta)) $meta = (array) $meta;
        $this->before_field($field, $meta);
	echo "<select name='{$field['id']}[]' multiple='multiple' style='height:auto'>";
        foreach ($field['options'] as $key => $value) {
            echo "<option value='$key'" . selected(in_array($key, $meta), true, false) . ">$value</option>";
	}
	echo "</select>";
	$this->after_field($field, $meta);
    }

    function draw_field_radio($field, $meta) {
        $this->before_field($field, $meta);
	foreach ($field['options'] as $key => $value) {
            echo "<input type='radio' name='{$field['id']}' id='{$field['id']}' value='$key'" . checked($meta, $key, false) . " /> $value ";
	}
	$this->after_field($field, $meta);
    }
    
    function draw_field_checkbox_group($field, $meta) {
	if (!is_array($meta)) $meta = (array) $meta;
        $this->before_field($field, $meta);
	foreach ($field['options'] as $key => $value) {
            echo "<input type='checkbox' name='{$field['id']}[]' id='{$field['id']}' value='$key'" . checked(in_array($key, $meta), true, false) . " /> $value ";
	}
	$this->after_field($field, $meta);
    }

    function draw_field_checkbox($field, $meta) {
	$this->before_field($field, $meta);
	echo "<input type='checkbox' name='{$field['id']}' id='{$field['id']}'" . checked(!empty($meta), true, false) . " />";
        $this->after_field($field, $meta);
    }
    function draw_field_file($field, $meta) {
	$this->before_field($field, $meta);
	echo "<input type='text' name='{$field['id']}' id='upload_image_{$field['id']}_url' value='$meta' class='input-text ons-media-upload-url' readonly='readonly' style='width:450px;' />";	
	echo "<input id='upload_image_{$field['id']}' type='button' value='Upload' class='ons-media-upload-btn btn'/>";

	$this->after_field($field, $meta);
    }
    
/*****************************************************************
* Save meta box data
******************************************************************/
    function save_meta($post_id){            
        // verify if this is an auto save routine. 
        // If it is our form has not been submitted, so we dont want to do anything
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
            return;
      
        // verify this came from the our screen and with proper authorization,
        // because save_post can be triggered at other times                
        if ( !wp_verify_nonce( $_POST['ons_meta_box_nonce'], basename( __FILE__ ) ) )
            return;
              
        // Check permissions
        if ( 'page' == $_POST['post_type'] ) 
        {
          if ( !current_user_can( 'edit_page', $post_id ) )
              return;
        }
        else
        {
          if ( !current_user_can( 'edit_post', $post_id ) )
              return;
        }
               
        //Start saving        
        foreach ($this->_fields as $field) {
	    $name = $field['id'];
            $type = $field['type'];
	    
	    //retrieve old data
	    if($field['save_as_taxonomy']=='')	    
		$old = get_post_meta($post_id, $name, $field['single']);
	    else{
		//retrieve data as taxonomy
		if($terms = get_the_terms($post_id,$field['save_as_taxonomy']))
		{
		    foreach ( $terms as $term ) {
			$meta = $term->name;
		    }
		}
	    }
	    
	    //retrieve new data	    
	    $new = isset($_POST[$name]) ? $_POST[$name] : ($field['single'] ? '' : array());
                        

	    // validate data through custom vatidate method is present
            $validate = $field['validate'];
            if (is_callable($validate)) {
		$new = $validate($new);
	    }	    
	    
	    //sanitize user input
            if($field['single'])
	    {
		$new = htmlspecialchars($new);
	    }else{
		foreach ($new as $item)
		    $item = htmlspecialchars($item);
	    }	    
	    
	    if($field['save_as_taxonomy']=='')
	    {
		if($field['single'])
		{
		    //single value
		    if ('' != $new && $new != $old) {		    
			update_post_meta($post_id, $name, $new);		    
		    } elseif ('' == $new) {
			delete_post_meta($post_id, $name, $old);
		    }
		}else{
		    // multiple values
		    // get new values that need to add and get old values that need to delete
		    $add = array_diff($new, $old);
		    $delete = array_diff($old, $new);
		    
		    foreach ($add as $add_new) {			
			    $return = add_post_meta($post_id, $name, $add_new, false);
			    debug( "\r\n \t $name : add_new->$add_new ; return -> $return");
		    }
		    foreach ($delete as $delete_old) {
			    $return = delete_post_meta($post_id, $name, $delete_old);
			    debug( "\r\n \t $name : delete_old -> $delete_old ; return -> $return");
		    }
		}
	    }else{
		//save data as taxonomy
		wp_set_post_terms($post_id,$new,$field['save_as_taxonomy'],false);
		
	    }
	}
        
    }

}

