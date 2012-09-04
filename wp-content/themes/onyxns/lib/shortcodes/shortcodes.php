<?php
//shortcodes

/*
 * ons_gallery shortcode
 * 
 * Register shortcode and admin generator
 */


class Ons_Gallery{
	protected $random_num;
	
	function __construct() {
		//register shortcodes
		//add_shortcode( 'ons_gallery_item', array( &$this, 'do_ons_gallery_item' ) );
		add_shortcode( 'ons_gallery', array( &$this, 'do_ons_gallery' ) );
		add_shortcode( 'ons_gallery_item', array( &$this, 'do_ons_gallery_item' ) );
		
		//add shortcode generator
		add_action( 'add_meta_boxes', array( &$this, 'ons_gallery_meta' ) );
		//include javascript and css
		add_action('admin_print_scripts', array( &$this, 'admin_scripts'));
		add_action('admin_print_styles', array( &$this, 'admin_styles'));
		
		//add ajax request handler
		add_action( 'wp_ajax_get_image_url_by_id', array( &$this,'ajax_get_image_url_by_id') );
		
		$random_num = 0;
	}
	
	//do short code ons_gallery
	function do_ons_gallery( $atts, $content = null ) {
		extract( shortcode_atts( array(
		'future' => 'future',
		), $atts ) );
		$random_num = rand();
		return '<div class="ons-gallery">' . do_shortcode($content) . '</div>';
	}
	
	//do short code ons_gallery_item
	function do_ons_gallery_item( $atts, $content = null ) {
		extract( shortcode_atts( array(
		'thumb_id' => '',
		'link_id' => '',
		'title' => '',
		), $atts ) );
		
		
		if( empty($thumb_id) ) return;
		
		$thumb_image = wp_get_attachment_image($thumb_id,'post-thumbnail');
		
		
		if(empty($link_id)){
			$link_url = wp_get_attachment_image_src($thumb_id,'full');
		}else{ 
			$link_url = wp_get_attachment_image_src($link_id,'full');
		}
		
		if($link_url) $link_url = $link_url[0];
		
		$output = '<a href="' . $link_url . '"';
		if( !empty($title) ) $output .= ' title="' . $title . '"';
		$output .= ' rel="lightbox[ons_gallery' . $random_num . ']">'. $thumb_image . '</a>';
	
		return $output;
	}
	
	//add meta box
	function ons_gallery_meta(){
		add_meta_box(
		'ons_gallery_metaid',
		__( 'OnyxNS Gallery Shortcode Editor', 'onyxns' ),
		array( &$this, 'ons_gallery_meta_show'),
		'product'
				);
		add_meta_box(
		'ons_gallery_metaid',
		__( 'OnyxNS Gallery Shortcode Editor', 'onyxns' ),
		array( &$this, 'ons_gallery_meta_show'),
		'page'
				);
		add_meta_box(
		'ons_gallery_metaid',
		__( 'OnyxNS Gallery Shortcode Editor', 'onyxns' ),
		array( &$this, 'ons_gallery_meta_show'),
		'post'
				);
	}
	
	//display meta box
	function ons_gallery_meta_show( $post ) {
	?>
		<div class='ons-gallery'>
			<div class='ons-gallery-items'></div>
			<br />
			<p>Shortcode:</p>
			<textarea class='ons-gallery-shortcode'></textarea>
			<input type='button' value='+ Add more images' id='ons-gallery-add-images' />
			<input type='button' value='Generate Shortcode' id='ons-gallery-generate-shortcode' />
			<input type='button' value='Send Shortcode to Editor' id='ons-gallery-send-shortcode' />
			<input type='button' value='Read Shortcode' id='ons-gallery-read-shortcode' />
		</div>
	<?php 
	}
	
	//register admin js
	function admin_scripts(){
		wp_register_script('ons_gallery_shortcode', get_bloginfo('template_url').'/js/ons_gallery_shortcode.js', array('jquery'));
		wp_enqueue_script('ons_gallery_shortcode');
		wp_localize_script( 'ons_gallery_shortcode', 'MyAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ), 'theme_url' => get_template_directory_uri() ) );
	}
	
	//register admin css
	function admin_styles(){
		wp_register_style('ons_gallery_shortcode', get_bloginfo('template_url').'/css/ons_gallery_shortcode.css');
		wp_enqueue_style('ons_gallery_shortcode');
	}
	
	
	function ajax_get_image_url_by_id() {
		// get the submitted parameters
		$attachment_id = isset($_POST['attachment_id']) ? intval($_POST['attachment_id']) : 0;
		$image_size = isset($_POST['image_size']) ? $_POST['image_size'] : '';
	
		
		$link_url = wp_get_attachment_image_src($attachment_id,$image_size);
		if($link_url) $link_url = $link_url[0];
		
		echo $link_url;
		exit;
	}
}