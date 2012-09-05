<?php
/**
 * OnyxNS functions and definitions
 *
 * @package WordPress
 * @subpackage OnyxNS
 * @since OnyxNS 1.1
 */

/**
 *  Some Hacks for your Security
 */
remove_action('wp_head', 'wp_generator');


/**
 * Tell WordPress to run onyxns_setup() when the 'after_setup_theme' hook is run.
 * this function can be overriden in child theme
 */
add_action( 'after_setup_theme', 'onyxns_setup' );

if ( ! function_exists( 'onyxns_setup' ) ):
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * To override onyxns_setup() in a child theme, add your own onyxns_setup to your child theme's
 * functions.php file.
 *
 * @uses load_theme_textdomain() For translation/localization support.
 * @uses add_editor_style() To style the visual editor.
 * @uses add_theme_support() To add support for post thumbnails, automatic feed links, custom headers
 * 	and backgrounds, and post formats.
 * @uses register_nav_menus() To add support for navigation menus.
 * @uses register_default_headers() To register the default custom header images provided with the theme.
 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
 *
 */
function onyxns_setup() {

	/* Make OnyxNS available for translation.
	 * Translations can be added to the /languages/ directory.
	 */
	
	//not supported yet
	//load_theme_textdomain( 'onyxns', get_template_directory() . '/languages' );

	// This theme styles the visual editor with editor-style.css to match the theme style.
	//not supported yet
	//add_editor_style();

	// Load up our theme options page and related code.
	//require( get_template_directory() . '/inc/theme-options.php' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menu( 'top_primary', __( 'Primary Top Menu', 'onyxns' ) );

	// Add support for a variety of post formats
	//add_theme_support( 'post-formats', array( 'aside', 'link', 'gallery', 'status', 'quote', 'image' ) );


	// This theme uses Featured Images (also known as post thumbnails) for per-post/per-page Custom Header images
	add_theme_support( 'post-thumbnails' );


	// We'll be using post thumbnails for custom header images on posts and pages.
	// We want them to be the size of the header image that we just defined
	// Larger images will be auto-cropped to fit, smaller ones will be ignored. See header.php.
	set_post_thumbnail_size( 250, 200, true );
	
	/***********************************************
	 * Setup custom posts
	***********************************************/
	require_once ('lib/ons_custom_posts.php');
	
	/***********************************************
	 * Setup Meta boxes
	***********************************************/
	require_once ('lib/ons_meta_box.php');
	
	/***********************************************
	 * Setup shortcodes
	***********************************************/
	require_once ('lib/shortcodes/shortcodes.php');
	$gallery = new Ons_Gallery();
	
	/***********************************************
	 * include custom menu walker
	***********************************************/
	require_once ('lib/ons_nav_menu_walker.php');


}
endif; // onyxns_setup

if ( ! function_exists( 'onyxns_style' ) ) :
/**
 * Custom styling of a page/post
 *
 * @since Onyxns 1.0
 */
function onyxns_style() {
	?>
	<style type="text/css">
	
	</style>
	<?php
}
endif; // onyxns_style


/**
 * Sets the post excerpt length to 40 words.
 *
 * To override this length in a child theme, remove the filter and add your own
 * function tied to the excerpt_length filter hook.
 */
function onyxns_excerpt_length( $length ) {
	return 40;
}
add_filter( 'excerpt_length', 'onyxns_excerpt_length' );


/**
 * Register our sidebars and widgetized areas. 
 *
 * @since OnyxNS 1.0
 */
/*
function onyxns_widgets_init() {

	register_widget( 'Twenty_Eleven_Ephemera_Widget' );

	register_sidebar( array(
		'name' => __( 'Main Sidebar', 'twentyeleven' ),
		'id' => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => __( 'Showcase Sidebar', 'twentyeleven' ),
		'id' => 'sidebar-2',
		'description' => __( 'The sidebar for the optional Showcase Template', 'twentyeleven' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => __( 'Footer Area One', 'twentyeleven' ),
		'id' => 'sidebar-3',
		'description' => __( 'An optional widget area for your site footer', 'twentyeleven' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => __( 'Footer Area Two', 'twentyeleven' ),
		'id' => 'sidebar-4',
		'description' => __( 'An optional widget area for your site footer', 'twentyeleven' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => __( 'Footer Area Three', 'twentyeleven' ),
		'id' => 'sidebar-5',
		'description' => __( 'An optional widget area for your site footer', 'twentyeleven' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
}
add_action( 'widgets_init', 'onyxns_widgets_init' );
*/
if ( ! function_exists( 'onyxns_content_nav' ) ) :
/**
 * Display pagination or next/prev navigation when applicable
 * Pagination will be displayed when list of post is quered
 * next/prev will be displayed on single pages
 * @since Onyxns 1.1
 */
function onyxns_content_nav( $nav_id, $range=3 ) {
	global $wp_query;
	$pages = $wp_query->max_num_pages;
	if(!empty($pages) && $pages>1)
	{
	?>
		<nav id="<?php echo $nav_id; ?>">
		<h3 class="assistive-text"><?php _e( 'Post navigation', 'onyxns' ); ?></h3>
		<div class="pagination">
		<ul>
			<?php  
		    global $paged;
		    if(empty($paged)) $paged = 1;	    
		    
		    $left_border=$right_border=$range;
		    if($paged < $range+1){
		    	$left_border=$paged-1;
		    	$right_border+=($range-$left_border);
		    }
		    if($paged > $pages-$range){		    	
		    	$right_border=$pages-$paged;
		    	$left_border+=($range-$right_border);
		    }
		    
		    for ($i=1; $i <= $pages; $i++)
		    {
		    	if( $paged-$left_border <= $i && $i <= $paged+$right_border)
		         	echo ($paged == $i)? "<li class='active'><a href='#'>".$i."</a></li>":"<li><a href='".get_pagenum_link($i)."' >".$i."</a></li>";
		    	elseif($paged-$left_border-1 >= 1 && $i == $paged-$left_border-1)
		    		echo "<li> <a href='".get_pagenum_link($i)."'>&lsaquo;</a></li>";
		    	elseif($paged+$right_border+1 >= pages && $i == $paged+$right_border+1)
		    		echo "<li> <a href='".get_pagenum_link($i)."'>&rsaquo;</a></li>";
		    }
		    ?>
		</ul>
		</div> <!-- .pagination -->
		</nav><!-- #<?php echo $nav_id;?> -->
		<?php 
		}elseif(is_single()){
			//display links to next and previous post in the same categoriess
			global $post;
			
			$cat_ids = $pages = array();
			
			foreach((get_the_category()) as $category) {
				$cat_ids[] = $category->cat_ID;
			}
			$categories = implode(",", $cat_ids);
			
			$args = array(
					'numberposts' 		=> -1,
					'order'          	=> 'ASC',
					'category'			=>  $categories);
			
			$list_of_posts = get_posts($args);
		
			foreach ($list_of_posts as $product_post) {
				$pages[] += $product_post->ID;
			}
		
			$current = array_search($post->ID, $pages);
			$prevID = $pages[$current-1];
			$nextID = $pages[$current+1];
			?>
				
				<nav id="<?php echo $nav_id; ?>" class="nav-single">
					<ul class="pager">
						<?php if (!empty($prevID)) { ?>
							<li class="nav-previous previous">
							<a href="<?php echo get_permalink($prevID); ?>"
							  title="<?php echo get_the_title($prevID); ?>"> <?php echo __( '&larr; Previous', 'onyxns' );?></a>
							</li>
						<?php }
						if (!empty($nextID)) { ?>
							<li class="nav-next next">
							<a href="<?php echo get_permalink($nextID); ?>" 
							 title="<?php echo get_the_title($nextID); ?>"><?php echo __( 'Next &rarr;', 'onyxns' ); ?></a>
							</li>
						<?php } ?>
					</ul>
				</nav><!-- .navigation -->
				
	<?php }
}
endif; // onyxns_content_nav



if ( ! function_exists( 'onyxns_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * To override this walker in a child theme without modifying the comments template
 * simply create your own onyxns_comment(), and that function will be used instead.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @since OnyxNS 1.0
 */
function onyxns_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', 'onyxns' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( 'Edit', 'onyxns' ), '<span class="edit-link">', '</span>' ); ?></p>
	<?php
			break;
		default :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			<footer class="comment-meta">
				<div class="comment-author vcard">
					<?php
						$avatar_size = 68;
						if ( '0' != $comment->comment_parent )
							$avatar_size = 39;

						echo get_avatar( $comment, $avatar_size );

						/* translators: 1: comment author, 2: date and time */
						printf( __( '%1$s on %2$s <span class="says">said:</span>', 'onyxns' ),
							sprintf( '<span class="fn">%s</span>', get_comment_author_link() ),
							sprintf( '<a href="%1$s"><time pubdate datetime="%2$s">%3$s</time></a>',
								esc_url( get_comment_link( $comment->comment_ID ) ),
								get_comment_time( 'c' ),
								/* translators: 1: date, 2: time */
								sprintf( __( '%1$s at %2$s', 'onyxns' ), get_comment_date(), get_comment_time() )
							)
						);
					?>

					<?php edit_comment_link( __( 'Edit', 'onyxns' ), '<span class="edit-link">', '</span>' ); ?>
				</div><!-- .comment-author .vcard -->

				<?php if ( $comment->comment_approved == '0' ) : ?>
					<em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'onyxns' ); ?></em>
					<br />
				<?php endif; ?>

			</footer>

			<div class="comment-content"><?php comment_text(); ?></div>

			<div class="reply">
				<?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply <span>&darr;</span>', 'onyxns' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			</div><!-- .reply -->
		</article><!-- #comment-## -->

	<?php
			break;
	endswitch;
}
endif; // ends check for onyxns_comment()

if ( ! function_exists( 'onyxns_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 * Create your own twentyeleven_posted_on to override in a child theme
 *
 * @since OnyxNS 1.0
 */
function onyxns_posted_on() {
	printf( __( '<span class="sep">Posted on </span><a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s" pubdate>%4$s</time></a><span class="by-author"> <span class="sep"> by </span> <span class="author vcard"><a class="url fn n" href="%5$s" title="%6$s" rel="author">%7$s</a></span></span>', 'onyxns' ),
		esc_url( get_permalink() ),
		esc_attr( get_the_time() ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		esc_attr( sprintf( __( 'View all posts by %s', 'onyxns' ), get_the_author() ) ),
		get_the_author()
	);
}
endif;



  
if ( ! function_exists( 'onyxns_comment' ) ) :
/**
* Template for comments and pingbacks.
*
* To override this walker in a child theme without modifying the comments template
* simply create your own onyxns_comment(), and that function will be used instead.
*
* Used as a callback by wp_list_comments() for displaying the comments.
*
* @since )nyxns 1.0
*/
function onyxns_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
	case 'pingback' :
	case 'trackback' :
		?>
  		<li class="post pingback">
  			<p><?php _e( 'Pingback:', 'onyxns' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( 'Edit', 'onyxns' ), '<span class="edit-link">', '</span>' ); ?></p>
  		<?php
  				break;
  			default :
  		?>
  		<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
  			<article id="comment-<?php comment_ID(); ?>" class="comment">
  				<footer class="comment-meta">
  					<div class="comment-author vcard">
  						<?php
  							$avatar_size = 68;
  							if ( '0' != $comment->comment_parent )
  								$avatar_size = 39;
  	
  							echo get_avatar( $comment, $avatar_size );
  	
  							/* translators: 1: comment author, 2: date and time */
  							printf( __( '%1$s on %2$s <span class="says">said:</span>', 'onyxns' ),
  								sprintf( '<span class="fn">%s</span>', get_comment_author_link() ),
  								sprintf( '<a href="%1$s"><time pubdate datetime="%2$s">%3$s</time></a>',
  									esc_url( get_comment_link( $comment->comment_ID ) ),
  									get_comment_time( 'c' ),
  									/* translators: 1: date, 2: time */
  									sprintf( __( '%1$s at %2$s', 'onyxns' ), get_comment_date(), get_comment_time() )
  								)
  							);
  						?>
  	
  						<?php edit_comment_link( __( 'Edit', 'onyxns' ), '<span class="edit-link">', '</span>' ); ?>
  					</div><!-- .comment-author .vcard -->
  	
  					<?php if ( $comment->comment_approved == '0' ) : ?>
  						<em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'onyxns' ); ?></em>
  						<br />
  					<?php endif; ?>
  	
  				</footer>
  	
  				<div class="comment-content"><?php comment_text(); ?></div>
  	
  				<div class="reply">
  					<?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply <span>&darr;</span>', 'onyxns' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
  				</div><!-- .reply -->
  			</article><!-- #comment-## -->
  	
	<?php
			break;
	endswitch;
}
endif; // ends check for onyxns_comment()  	  


/**
 * 
 * Hide admin_bar for subscribers and restrict Dashboard access
 * 
 */
if ( is_user_logged_in() && !current_user_can('edit_posts') ) show_admin_bar( false );

add_action("admin_init","subscriber_redirect");
function subscriber_redirect(){
	if (!current_user_can('edit_posts')) {
		header( 'Location: ' . get_bloginfo('url') ) ;
	}
}

/**
 * 
 * Load meta_media_insert script in admin
 * 
*/
function my_admin_scripts() {
	wp_enqueue_script('media-upload');
	wp_enqueue_script('thickbox');

	wp_register_script('ons_meta_media_insert', get_bloginfo('template_url').'/js/ons_meta_media_insert.js', array('jquery','media-upload','thickbox'));
	wp_enqueue_script('ons_meta_media_insert');
}

function my_admin_styles() {
	wp_enqueue_style('thickbox');
}

add_action('admin_print_scripts', 'my_admin_scripts');
add_action('admin_print_styles', 'my_admin_styles');

/**
 *
 *Load Front End scripts
 *
 */
function my_frontend_scripts() {
	wp_register_script('ons-preloader', get_template_directory_uri() . '/js/preloader.js', array('jquery') );
	wp_enqueue_script ('ons-preloader');
	wp_localize_script('ons-preloader', 'OnsTheme', array( 'theme_url' => get_template_directory_uri() ) );
	
}
add_action('wp_enqueue_scripts', 'my_frontend_scripts');
