<?php get_header(); ?>

<?php
	global $gctheme_options;
	$hide_empty_sidebar = $gctheme_options['hide_widgets'];
	$show_blog_sidebar = $gctheme_options['blog_sidebar'];
	$sidebar_widget_group = $gctheme_options['blog_sidebar_widgets'];
	$sidebar_position = $gctheme_options['blog_sidebar_position'];
	if ( is_active_sidebar($sidebar_widget_group) && ! $hide_empty_sidebar && $show_blog_sidebar ) {
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

?>

	<div id="content" class="container clearfix">

		<div class="row clearfix">

			<section id="main" role="main" class="<?php echo $main_class; ?>">

				<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

					<header class="page-header">

						<h1><?php the_title(); ?></h1>

					</header>

					<article id="post-<?php the_ID(); ?>" <?php post_class("clearfix"); ?> role="article">

					<?php if ( has_post_thumbnail() ) : // display person's image ?>

						<div class="col-md-4 col-xs-6">

							<?php $large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'large'); ?>

						    <a href="<?php echo $large_image_url[0]; ?>" title="<?php the_title_attribute();?>">

								<?php echo get_the_post_thumbnail($post->ID, 'thumbnail',array('class' => 'thumbnail', 'width' => '100%')); ?>

							</a>

						</div>

						<div class="col-md-8 col-sm-6 col-xs-12">

					<?php else : //no thumbmail, just generate the full-width div ?>

						<div class="col-md-12">

					<?php endif; ?>

						<?php the_content(); ?>

						<?php if ($meta_website !== '') : ?>

							<a href="<?php echo $meta_website; ?>" class="btn btn-sm btn-info" title="Visit <?php the_title_attribute(); ?>" target="_blank">

								Visit <?php the_title_attribute(); ?> <span class="glyphicon glyphicon-new-window"></span>

							</a>

						<?php endif; ?>

						</div>

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
