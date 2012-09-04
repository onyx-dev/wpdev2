<?php
/**
 * OnyxNS Navigation Menu template functions
 *
 *
 * @since 1.1
 * @uses Walker_Nav_Menu
 */

/**
 * Format wp_nav_menu output for bootstrap
 * 
 * @author AlexK
 * @since 1.1
 *
 */
class Ons_Walker_Nav_Menu extends Walker_Nav_Menu {
	function __construct() {
		
		if(is_callable('parent::__construct')) {
				parent::__construct();
		}
		
		// Add dropdown class to <li> items with subs	 
		add_filter('wp_nav_menu_objects', function ($items) {
			$hasSub = function ($menu_item_id, &$items) {
				foreach ($items as $item) {
					if ($item->menu_item_parent && $item->menu_item_parent==$menu_item_id) {
						return true;
					}
				}
				return false;
			};
		
			foreach ($items as &$item) {
				if ($hasSub($item->ID, &$items)) {
					$item->classes[] = 'dropdown';
				}
			}
			return $items;
		});
		
	}
	/**
	 * @see Walker_Nav_Menu::start_lvl()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int $depth Depth of page. Used for padding.
	 */
	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat("\t", $depth);
		$output .= "\n$indent<ul class=\"sub-menu dropdown-menu\">\n";
	}
	
	/**
	 * @see Walker_Nav_Menu::end_lvl()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int $depth Depth of page. Used for padding.
	 */
	function end_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat("\t", $depth);
		$output .= "$indent</ul>\n";
	}
	
	/**
	 * @see Walker_Nav_Menu::start_el()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $item Menu item data object.
	 * @param int $depth Depth of menu item. Used for padding.
	 * @param int $current_page Menu item ID.
	 * @param object $args
	 */
	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		global $wp_query;
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
	
		$class_names = $value = '';
	
		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;
	
		$is_dropdown = false;
		if( in_array('dropdown', $classes) ) $is_dropdown=true;
		$intersect_result = array_intersect(array('current-menu-item','current_page_item','current-menu-parent'), $classes);
		if( !empty( $intersect_result )) $classes[] = 'active';
		
		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';
	
		$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';
	
		$output .= $indent . '<li' . $id . $value . $class_names .'>';
	
		$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
		$attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
		$attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
		$attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
	
		$item_output = $args->before;
		$item_output .= '<a';
		if($is_dropdown) $item_output .=' class="dropdown-toggle" data-toggle="dropdown" ';
		$item_output .= $attributes .'>';
		$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
		if($is_dropdown) $item_output .= '<b class="caret"></b>';
		$item_output .= '</a>';
		$item_output .= $args->after;
	
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
	
	/**
	 * @see Walker_Nav_Menu::end_el()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $item Page data object. Not used.
	 * @param int $depth Depth of page. Not Used.
	 */
	function end_el( &$output, $item, $depth = 0, $args = array() ) {
		$output .= "</li>\n";
	}
	
}

/**
 * Format wp_nav_menu output as <select> control
 *
 * @author AlexK
 * @since 1.1
 *
 */
class Ons_Walker_Nav_Menu_Select extends Walker_Nav_Menu {
	function __construct() {

		if(is_callable('parent::__construct')) {
			parent::__construct();
		}

		add_filter('wp_nav_menu', function ($nav_menu) {
		
			return $nav_menu . "<script type='text/javascript'>
				jQuery(function(){
					jQuery('.select-dropdown-menu').each( function(){
							this.onchange = function(){ 
								if ( this.value != '' ){ window.location.href = this.value; }
							}
						});
				});
			</script>";
		
		});
		
		// Add dropdown class to <li> items with subs
		add_filter('wp_nav_menu_objects', function ($items) {
			$hasSub = function ($menu_item_id, &$items) {
				foreach ($items as $item) {
					if ($item->menu_item_parent && $item->menu_item_parent==$menu_item_id) {
						return true;
					}
				}
				return false;
			};
		
			foreach ($items as &$item) {
				if ($hasSub($item->ID, &$items)) {
					$item->classes[] = 'dropdown';
				}
			}
			return $items;
		});

	}
	/**
	 * @see Walker_Nav_Menu::start_lvl()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int $depth Depth of page. Used for padding.
	 */
	function start_lvl( &$output, $depth = 0, $args = array() ) {
	}

	/**
	 * @see Walker_Nav_Menu::end_lvl()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int $depth Depth of page. Used for padding.
	 */
	function end_lvl( &$output, $depth = 0, $args = array() ) {
	}

	/**
	 * @see Walker_Nav_Menu::start_el()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $item Menu item data object.
	 * @param int $depth Depth of menu item. Used for padding.
	 * @param int $current_page Menu item ID.
	 * @param object $args
	 */
	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		global $wp_query;
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		$class_names = $value = '';

		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;
		
		$selected = '';
		if( in_array('current-menu-item', $classes) ) $selected = ' selected="selected" ';
		
		$is_dropdown = false;
		if( in_array('dropdown', $classes) ) $is_dropdown = true;

		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

		$value = !empty( $item->url ) && !$is_dropdown  ? ' value="'   . esc_attr( $item->url        ) .'"' : ' value="" disabled="disabled"';
		
		$output .= $indent . '<option' . $id . $value . $class_names .$selected;
		if($is_dropdown) $output .= ' style="font-weight:bold; font-style:italic;"';
		$output .= '>';

		$item_output .= $args->link_before;
		$item_output .= str_repeat('- ', $depth);
		$item_output .= apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
		

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
		$output .= "</option>\n";
	}

	/**
	 * @see Walker_Nav_Menu::end_el()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $item Page data object. Not used.
	 * @param int $depth Depth of page. Not Used.
	 */
	function end_el( &$output, $item, $depth = 0, $args = array() ) {
	}

}

