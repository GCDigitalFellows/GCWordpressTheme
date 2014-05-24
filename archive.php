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

		<?php if (is_category()) : ?>
			<h1 class="archive_title h1">
				<span><?php _e("Posts Categorized:", "wheniwasbad"); ?></span> <?php single_cat_title(); ?>
			</h1>
		<?php elseif (is_tag()) : ?>
			<h1 class="archive_title h1">
				<span><?php _e("Posts Tagged:", "wheniwasbad"); ?></span> <?php single_tag_title(); ?>
			</h1>
		<?php elseif (is_author()) : ?>
			<h1 class="archive_title h1">
				<span><?php _e("Posts By:", "wheniwasbad"); ?></span>
					<?php
						// If google profile field is filled out on author profile, link the author's page to their google+ profile page
						$curauth = (get_query_var('author_name')) ? get_user_by('slug', get_query_var('author_name')) : get_userdata(get_query_var('author'));
						$google_profile = get_the_author_meta( 'google_profile', $curauth->ID );
						if ( $google_profile ) {
							echo '<a href="' . esc_url( $google_profile ) . '" rel="me">' . $curauth->display_name . '</a>';
					?>
					<?php
						} else {
							echo get_the_author_meta('display_name', $curauth->ID);
						}
					?>
			</h1>
		<?php elseif (is_day()) : ?>
			<h1 class="archive_title h1">
				<span><?php _e("Daily Archives", "wheniwasbad"); ?>:</span> <?php the_time('l, F j, Y'); ?>
			</h1>
		<?php elseif (is_month()) : ?>
		    <h1 class="archive_title h1">
		    	<span><?php _e("Monthly Archives", "wheniwasbad"); ?>:</span> <?php the_time('F Y'); ?>
		    </h1>
		<?php elseif (is_year()) : ?>
		    <h1 class="archive_title h1">
		    	<span><?php _e("Yearly Archives", "wheniwasbad"); ?>:</span> <?php the_time('Y'); ?>
		    </h1>
		<?php endif; ?>

		</header> <!-- page header -->

		<div class="row clearfix">

			<section id="main" role="main" class="<?php echo $main_class; ?>">

				<?php while (have_posts()) : the_post(); ?>

					<?php get_template_part( 'content', get_post_format() ); ?>

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

		<?php $archive_type = (is_category() ? 'category' : (is_tag() ? 'tag' : (is_author() ? 'author' : (is_day() ? 'day' : (is_month() ? 'month' : (is_year() ? 'year' : 'default')))))); ?>

		<?php not_found($archive_type); ?>

	<?php endif; ?>

	</div> <!-- end #content -->

<?php get_footer(); ?>