<?php get_header(); ?>

<?php 	
	global $wheniwasbad_options;
	$hide_empty_sidebar = $wheniwasbad_options['hide_widgets'];
	$show_blog_sidebar = $wheniwasbad_options['blog_sidebar'];
	$sidebar_widget_group = $wheniwasbad_options['blog_sidebar_widgets'];
	$sidebar_position = $wheniwasbad_options['blog_sidebar_position'];
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
	
	$blog_jumbotron = $wheniwasbad_options['blog_jumbotron']; 
	$jumbotron_content = $wheniwasbad_options['blog_jumbotron_content'];
	?>

	<?php if ( $blog_jumbotron && $jumbotron_content ) : ?>
	
	<div class="jumbotron">
		
		<?php echo $jumbotron_content; ?>
	
	</div>

	<?php endif; ?>
	
	<div id="content" class="container clearfix">
			
		<div class="row clearfix">
				
			<section id="main" role="main" class="<?php echo $main_class; ?>">

				<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		
					<?php get_template_part( 'content', get_post_format() ); ?>

				<?php endwhile; // end of the loop. ?>
		
				<?php else : ?>
			
					<? not_found(); ?>
					
				<?php endif; ?>

				<?php if (function_exists('page_navi')) : // if expirimental feature is active ?>
			
					<?php page_navi(); // use the page navi function ?>
			
				<?php else : // if it is disabled, display regular wp prev & next links ?>
					<nav class="wp-prev-next pagenavi">
						<ul class="clearfix">
							<li class="prev-link"><?php next_posts_link(_e('<i class="glyphicon glyphicon-chevron-sign-left"></i> Older Entries', "wheniwasbad")) ?></li>
							<li class="next-link"><?php previous_posts_link(_e('Newer Entries <i class="glyphicon glyphicon-chevron-sign-right"></i>', "wheniwasbad")) ?></li>
						</ul>
					</nav>
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