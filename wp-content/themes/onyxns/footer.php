<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package WordPress
 * @subpackage OnyxNS
 * @since OnyxNS 1.0
 */
?>

	</div><!-- #main -->

	<footer id="colophon" role="contentinfo">

			<?php
				/* A sidebar in the footer? Yep. You can can customize
				 * your footer with three columns of widgets.
				 */
				if ( ! is_404() )
					//get_sidebar( 'footer' );
			?>

			
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>
<script src="<?php echo get_template_directory_uri(); ?>/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
 <script src="<?php echo get_template_directory_uri(); ?>/js/main.js" type="text/javascript"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/preloader.js" type="text/javascript"></script>
</body>
</html>
