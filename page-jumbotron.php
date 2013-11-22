<?php
/*
Template Name: Full-width Jumbotron
*/
?>

<?php get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	
<?php 
	$post_thumbnail_id = get_post_thumbnail_id();
	$featured_src = wp_get_attachment_image_src( $post_thumbnail_id, 'wpbs-featured-home' );
	
	global $wheniwasbad_options;
	$sidebar_position = get_post_meta($post->ID, 'sidebar_position' , true);
	$sidebar_widget_group = get_post_meta($post->ID, 'sidebar_widgets' , true);
	$hide_empty_sidebar = $wheniwasbad_options['hide_widgets'];

	$jumbotron_contents = get_post_meta($post->ID, 'jumbotron_contents' , true);
	$jumbotron_bg_color = get_post_meta($post->ID, 'jumbotron_bg_color' , true);
	$jumbotron_bg_image = get_post_meta($post->ID, 'jumbotron_bg_image' , true);

	if ( ! is_active_sidebar($sidebar_widget_group) && $hide_empty_sidebar) {
		$main_class = "col-md-12";
		$sidebar_class = "";
	} else {
		if ( $sidebar_position == 'left' ) {
			$main_class = "col-md-9 col-md-push-3";
			$sidebar_class = "col-md-3 col-md-pull-9";
		} elseif ( $sidebar_position == 'right' ) {
			$main_class = "col-md-9";
			$sidebar_class = "col-md-3";
		}
	}
?>

<div id="content" class="content-no-margin clearfix">
			
	<header>
	
	<?php if ($jumbotron_contents != ''): ?>

		<?php
			if ($jumbotron_bg_image) {
				$jumbotron_style = 'style="background-image: url(\'' . wp_get_attachment_url($jumbotron_bg_image) . '\'); background-repeat: no-repeat; background-position: 0 0; background-size: cover;\'"';
			} elseif ($jumbotron_bg_color) {
				$jumbotron_style = 'style="background-color: ' . $jumbotron_bg_color . '"';
			}
		?>
	
		<div class="jumbotron" <?php echo $jumbotron_style; ?>>
	
			<?php echo $jumbotron_contents;?>

		</div>

	<?php else: ?>

		<div class="container clearfix">
			<header class="page-header">
			
				<h1><?php single_post_title(); ?></h1>
			
				<?php get_template_part('postmeta-horizontal'); ?>
		
			</header> <!-- end page header -->

		</div>

	<?php endif;?>
	
	</header>
	
	<div class="container">

		<div class="row clearfix">

			<section id="main" class="<?php echo $main_class; ?> clearfix" role="main">
							
				<?php the_content(); ?>
												
			</section> <!-- main -->
			
			<?php if ($sidebar_class != ''): ?>
		
				<section class="<?php echo $sidebar_class; ?> clearfix" role="aside">
		
					<?php get_sidebar($sidebar_widget_group); ?>
					
				</section>
		
			<?php endif; ?>	
								
		</div>
    
	</div> <!-- end #content -->

<?php endwhile; ?>

<?php else : ?>

	<?php not_found(); ?>
		
<?php endif; ?>


<?php get_footer(); ?>