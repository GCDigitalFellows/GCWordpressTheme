<?php
/**
 * The WordPress template hierarchy first checks for any
 * MIME-types and then looks for the attachment.php file.
 *
 * @link codex.wordpress.org/Template_Hierarchy#Attachment_display 
 */ 

get_header(); ?>

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
			
	<div id="content" class="clearfix container">

		<div id="main" role="main">

		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	
			<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">

				<header class="page-header"> 

					<h1 class="single-title" itemprop="headline">

						<?php if ($post->post_parent) : ?>

							<a href="<?php echo get_permalink($post->post_parent); ?>" rev="attachment"><?php echo get_the_title($post->post_parent); ?></a> <i class="glyphicon glyphicon-chevron-right"></i>

						<?php endif; ?>

						<?php the_title(); ?>

					</h1>

				</header> <!-- end article header -->
			
				<section class="post_content clearfix" itemprop="articleBody">
					<div class="row">

						<div class="col-md-9">

							<div class="well">
								<?php the_excerpt(); ?>
								<p><i class="glyphicon glyphicon-download-alt"></i> Download <a href="<?php echo wp_get_attachment_url($post->ID); ?>"><?php the_title(); ?></a></p>
							</div>
							<?php the_content(); ?>

						</div>
						
						<div class="col-md-3">

							<?php get_template_part('postmeta','attachment'); ?>

						</div>
						
					</div>

				</section> <!-- end article section -->

				<?php comments_template(); ?>
								
			</article> <!-- end article -->
			
		<?php endwhile; ?>			
			
		<?php else : ?>
		
			<?php not_found('attachment'); ?>
		
		<?php endif; ?>	
	
		</div> <!-- end #main -->

	</div> <!-- end #content -->

<?php get_footer(); ?>