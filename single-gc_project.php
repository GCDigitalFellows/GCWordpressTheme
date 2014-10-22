<?php get_header(); ?>

<?php
	global $gctheme_options;
	$hide_empty_sidebar = $gctheme_options['hide_widgets'];
	$show_blog_sidebar = $gctheme_options['blog_sidebar'];
	$sidebar_widget_group = $gctheme_options['blog_sidebar_widgets'];
	$sidebar_position = $gctheme_options['blog_sidebar_position'];
	if ( is_active_sidebar($sidebar_widget_group) && ! $hide_empty_sidebar && $meta_sidebars) {
		if ( $sidebar_position == 'left' ) {
			$main_class = "col-md-9 col-md-push-3";
			$sidebar_class = "col-md-3 col-md-pull-9";
		} elseif ( $sidebar_position == 'right' ) {
			$main_class = "col-md-9";
			$sidebar_class = "col-md-3";
		}
	} else {
		$main_class = "col-md-12";
		$sidebar_class = "";
	}

	$meta_website = get_post_meta( $post->ID, 'gc_project_url', true );
	$meta_images = get_post_meta( $post->ID, 'gc_project_images', false );
	$meta_layout = get_post_meta( $post->ID, 'gc_project_layout', true );
	$meta_sidebars = get_post_meta( $post->ID, 'gc_project_sidebars', true );

?>

	<div id="content" class="container clearfix">

		<div class="row clearfix">

			<section id="main" role="main" class="<?php echo $main_class; ?>">

				<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

					<?php if ($meta_layout == 'slider') {
						include 'single-gc_project-slider.php';
					} elseif ($meta_layout == 'adjacent') {
						include 'single-gc_project-adjacent.php';
					} elseif ($meta_layout == 'gallery') {
						include 'single-gc_project-gallery.php';
					} else {
						include 'single-gc_project-blank.php';
					}

				?>

				<?php endwhile; // end of the loop. ?>

				<?php else : ?>

					<?php not_found(); ?>

				<?php endif; ?>

			</section> <!-- end #main -->

			<?php if ($sidebar_class != ''): ?>

				<section class="<?php echo $sidebar_class; ?> clearfix">

					<?php get_sidebar($sidebar_widget_group); ?>

				</section>

			<?php endif; ?>

		</div> <!-- row -->

	</div> <!-- end #content -->

<?php get_footer(); ?>
