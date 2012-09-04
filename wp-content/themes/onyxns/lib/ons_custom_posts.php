<?php
/***********************************************
 * Custom "product" Post
 ***********************************************/
//Register Custom post Product
add_action( 'init', 'custom_post_init' );
function custom_post_init() {
  $labels = array(
    'name' => 'Products',
    'singular_name' => 'Product',
    'add_new' => 'Add New',
    'add_new_item' => 'Add New Product',
    'edit_item' =>'Edit Product',
    'new_item' => 'New Product',
    'all_items' => __('All Products'),
    'view_item' => __('View Product'),
    'search_items' => __('Search Products'),
    'not_found' =>  __('No Products found'),
    'not_found_in_trash' => __('No Products found in Trash'), 
    'parent_item_colon' => '',
    'menu_name' => 'Products'

  );
  $args = array(
    'labels' => $labels,
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true, 
    'show_in_menu' => true, 
    'query_var' => true,
    'rewrite' => true,
    'capability_type' => 'post',
    'has_archive' => 'product', 
    'hierarchical' => false,
    'menu_position' => null,
    'rewrite' => true,
    'supports' => array('title','editor', 'thumbnail'),
  	//'taxonomies' => array('category', 'post_tag')
  ); 
  register_post_type('product',$args);
}



//Display  columns for custom post
add_filter('manage_product_posts_columns' , 'set_edit_product_columns');
add_action( 'manage_product_posts_custom_column' , 'custom_product_column', 10, 2 );

function custom_product_column( $column, $post_id ) {
    switch ( $column ) {
    	case 'sku':
	        $status = get_post_meta( $post_id , 'product_sku' , true );
	        echo $status;
	        break;
	    case 'product':
	        $status = get_post_meta( $post_id , 'product_tag' , true );
	        echo $status;
	        break;

    }
}

function set_edit_product_columns($columns) {
    return array(
        'cb' => '<input type="checkbox" />',
        'title' => __('Title'),
        'date' => __('Date'),
        'sku' => __('SKU'),
    	'product' => __('Product Type'),
    );
}

/* 
 * Register the column as sortable
 */
function product_sortable( $columns ) {
	$columns['product'] = 'product';

	return $columns;
}
add_filter( 'manage_edit-product_sortable_columns', 'product_sortable' );

function product_column_orderby( $vars ) {
	if(  is_admin() ){
		if ( isset( $vars['orderby'] ) && 'product' == $vars['orderby'] ) {
			$vars = array_merge( $vars, array(
					'meta_key' => 'product_tag',
					'orderby' => 'meta_value'
			) );
		}
	}
	return $vars;
}
add_filter( 'request', 'product_column_orderby' );
// or can use add_action( 'pre_get_posts', 'product_column_orderby' );


/************************************************************
 * Register Custom "ad" post
 ************************************************************/
add_action( 'init', 'custom_ad_init' );
function custom_ad_init() {
	$labels = array(
			'name' => 'Ads',
			'singular_name' => 'Ad',
			'add_new' => 'Add New',
			'add_new_item' => 'Add New Ad',
			'edit_item' =>'Edit Ad',
			'new_item' => 'New Ad',
			'all_items' => __('All Ads'),
			'view_item' => __('View Ad'),
			'search_items' => __('Search Ads'),
			'not_found' =>  __('No Ads found'),
			'not_found_in_trash' => __('No Ads found in Trash'),
			'parent_item_colon' => '',
			'menu_name' => 'Ads'

	);
	$args = array(
			'labels' => $labels,
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'query_var' => true,
			'rewrite' => true,
			'capability_type' => 'post',
			'has_archive' => 'product',
			'hierarchical' => false,
			'menu_position' => null,
			'rewrite' => true,
			'supports' => array('title','editor')
	);
	register_post_type('ad',$args);
}

//Display  columns for custom post
add_filter('manage_ad_posts_columns' , 'set_edit_ad_columns');
add_action( 'manage_ad_posts_custom_column' , 'custom_ad_column', 10, 2 );

function custom_ad_column( $column, $post_id ) {
	switch ( $column ) {
		case 'status':
			$status = get_post_meta( $post_id , 'ad-status' , true );
			if($status =='yes') echo 'Active';
			else echo 'Not active';
			break;
		

	}
}

function set_edit_ad_columns($columns) {
	return array(
			'cb' => '<input type="checkbox" />',
			'title' => __('Title'),
			'date' => __('Date'),
			'status' => __('Ad Status'),
	);
}
