<?php
/**
 * The main template file.
 *
 * @package WordPress
 * @subpackage OnyxNS
 */

get_header(); ?>
		<div id="primary">
			<div id="content" role="main">
			<?php if ( current_user_can(read) ):?>
			<?php if ( have_posts() ) : ?>


				<?php /* Start the Loop */ ?>
				<?php while ( have_posts() ) : the_post(); ?>
				
					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
						
						<h1 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'onyxns' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h1>
						
						<div class="entry-content">
							<?php the_content(); ?>
						</div><!-- .entry-content -->
						
						<footer class="entry-meta">
							<?php edit_post_link( __( 'Edit', 'onyxns' ), '<span class="edit-link">', '</span>' ); ?>
						</footer><!-- #entry-meta -->

					</article>

				<?php endwhile; ?>
				
				<?php require_once ( get_template_directory() . '/lib/upload/upload-form.php'); ?>

			<?php else : ?>

				<article id="post-0" class="post no-results not-found">
					<header class="entry-header">
						<h1 class="entry-title"><?php _e( 'Nothing Found', 'onyxns' ); ?></h1>
					</header><!-- .entry-header -->

					<div class="entry-content">
						<p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'onyxns' ); ?></p>
						<?php get_search_form(); ?>
					</div><!-- .entry-content -->
				</article><!-- #post-0 -->

			<?php endif; ?>
			<?php else: // if ( current_user_can(read) ):?>
			<h2>Please login to view this page</h2>
			<?php wp_login_form(array(
				'remember' => false,
			)); ?>
			<?php endif;// if ( current_user_can(read) ):?>

			</div><!-- #content -->
		</div><!-- #primary -->

<?php // get_sidebar(); ?>
<?php get_footer(); ?>