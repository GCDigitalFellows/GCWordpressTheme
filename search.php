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

		<header class="page-header">

			<?php
			global $wp_query;
			$total_results = $wp_query->found_posts;
			?>

		    <h3 class="search_title h3">
		    	<?php echo __('Search Found ','gcwordpresstheme') . $total_results . __(' Matches for "','gcwordpresstheme') . '<em>' . get_search_query() . __('"','gcwordpresstheme') . "</em>"; ?>
		    </h3>
			
		</header> <!-- page header -->
			
		<div class="row clearfix">
			
			<section id="main" role="main" class="<?php echo $main_class; ?>">
				
				<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	
						<?php get_template_part( 'content', get_post_format() ); ?>

					<?php endwhile; // end of the loop. ?>

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
				
				<?php else : ?>

					<h3><?php _e('Sorry, there was nothing found matching those search terms.','gcwordpresstheme'); ?></h3>
		
				<?php endif; ?>
				
				<?php
				$sf = get_search_form(false);
				$sf = str_replace( '"form-search">','"form-control" style="margin: 18px 0;">',$sf);
				$sf = str_replace( 'input-sm', 'input-lg',$sf );
//				$sf = str_replace( '<input type="text"','<div class="controls"><input type="text"',$sf);
//				$sf = str_replace( '</button>','</div></button>',$sf);
				?>
				
				<div class="panel row clearfix">
				
					<div class="col-md-6 text-right"><h3>Search the site again:</h3></div>
				
					<div class="col-md-6"><?php echo $sf; ?></div>
				
				</div>

			</section><!-- main -->
			
			<?php if ($sidebar_class != ''): ?>
	
				<section class="<?php echo $sidebar_class; ?> clearfix">
	
					<?php get_sidebar($sidebar_widget_group); ?>
	
				</section>
	
			<?php endif; ?>		

		</div><!-- row -->
		
	</div> <!-- end #content -->

<?php get_footer(); ?>