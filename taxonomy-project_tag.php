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
?>

	<div id="content" class="container clearfix">

	<?php if (have_posts()) : ?>

		<header class="page-header">

			<?php $term = $wp_query->queried_object; ?>

			<h1 class="archive_title">Projects Tagged '<?php echo $term->name; ?>'</h1>

		</header> <!-- page header -->

		<div class="row clearfix">

			<section id="main" role="main" class="<?php echo $main_class; ?>">

				<?php while (have_posts()) : the_post(); ?>

					<div class="col-xs-6 col-sm-4 col-lg-3">

						<div class="gc_person_image">

						<?php 

						if ( has_post_thumbnail() ) {

							$image_url = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'thumbnail');
							$image_url = $image_url[0];

						} else {

							$image_url = get_template_directory_uri() . '/library/images/icon-user-default.png';

						}

						?>

							<img src="<?php echo $image_url; ?>" alt="<?php the_title_attribute();?>" class="gc_person_thumb" />

							<div class="gc_person_info_wrapper">

								<div class="gc_person_info">

									<div class="gc_person_info_inner">

										<?php $excerpt = get_the_excerpt(); ?>

										<p><?php echo $excerpt; ?></p>

										<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute();?>" class="btn btn-default btn-xs">Details</a>

									</div>

								</div>

							</div>

						</div>

						<div class="caption text-center">

							<h3><?php the_title(); ?></h3>

						</div>

					</div>

				<?php endwhile; // end of the loop. ?>

				<?php if (function_exists('page_navi')) : // if expirimental feature is active ?>

					<?php page_navi(); // use the page navi function ?>

				<?php else : // if it is disabled, display regular wp prev & next links ?>
					<nav class="wp-prev-next pagenavi">
						<ul class="clearfix">
							<li class="prev-link"><?php next_posts_link(_e('<i class="glyphicon glyphicon-chevron-left"></i> Older Entries', "wheniwasbad")) ?></li>
							<li class="next-link"><?php previous_posts_link(_e('Newer Entries <i class="glyphicon glyphicon-chevron-right"></i>', "wheniwasbad")) ?></li>
						</ul>
					</nav>
				<?php endif; ?>

			</section><!-- main -->

			<?php if ($sidebar_class != ''): ?>

				<section class="<?php echo $sidebar_class; ?> clearfix">

					<?php get_sidebar($sidebar_widget_group); ?>

				</section>

			<?php endif; ?>

		</div><!-- row -->

	<?php else : ?>

		<?php not_found('people'); ?>

	<?php endif; ?>

	</div> <!-- end #content -->

<?php get_footer(); ?>