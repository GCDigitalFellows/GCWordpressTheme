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

	$meta_affiliations = get_post_meta( $post->ID, 'gc_person_affiliations', false );
	$meta_email = get_post_meta( $post->ID, 'gc_person_email', true );
	$meta_website = get_post_meta( $post->ID, 'gc_person_website', true );
	$meta_social_links = get_post_meta( $post->ID, 'gc_person_social_links', false );

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

							<div class="panel panel-default">

								<div class="panel-heading"><span class="h2"><?php the_title(); ?></span>

								<?php if (is_array($meta_social_links)) : ?>

									<?php gc_person_print_social_links($meta_social_links); ?>

								<?php endif; ?>

								</div>

								<ul class="list-group">

								<?php if (is_array($meta_affiliations)) : ?>

									<li class="list-group-item">

									<?php foreach($meta_affiliations as $affiliation) : ?>

										<div><?php echo $affiliation['gc_person_title']; ?>, <?php echo $affiliation['gc_person_affiliation']; ?></div>

									<?php endforeach; ?>

									</li>

								<?php endif; ?>

									<li class="list-group-item">

										<?php the_content(); ?>

									</li>

								<?php if (isset($meta_email)) : ?>

									<li class="list-group-item"><span class="h3">Email: </span><a href="mailto:<?php echo $meta_email; ?>"><?php echo $meta_email; ?></a></li>

								<?php endif; ?>

								<?php if (isset($meta_website)) : ?>

									<li class="list-group-item"><span class="h3">Website: </span><a href="<?php echo $meta_website; ?>"><?php echo $meta_website; ?></a></li>

								<?php endif; ?>

								</ul>

							</div>

					<?php /*if (function_exists('page_navi')) : // if expirimental feature is active ?>

						<?php page_navi(); // use the page navi function ?>

					<?php else : // if it is disabled, display regular wp prev & next links ?>
						<nav class="wp-prev-next pagenavi">
							<ul class="clearfix">
								<li class="prev-link"><?php next_posts_link(_e('<i class="glyphicon glyphicon-chevron-sign-left"></i> Older Entries', "wheniwasbad")) ?></li>
								<li class="next-link"><?php previous_posts_link(_e('Newer Entries <i class="glyphicon glyphicon-chevron-sign-right"></i>', "wheniwasbad")) ?></li>
							</ul>
						</nav>
					<?php endif; */?>

				<?php endwhile; // end of the loop. ?>

				<?php else : ?>

					<? not_found(); ?>

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
