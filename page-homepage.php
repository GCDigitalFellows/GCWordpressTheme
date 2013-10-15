<?php 
/*
Template Name: Homepage
*/
?>

<?php get_header(); ?>

<div id="content" class="content-no-margin clearfix">
			
<?php
	global $wheniwasbad_options;
	$use_carousel = $wheniwasbad_options['showhidden_slideroptions'];
	if ($use_carousel) : ?>
		
		<div id="myCarousel" class="carousel">

		    <!-- Carousel items -->
		    <div class="carousel-inner">

		    	<?php
				global $post;
				$tmp_post = $post;
				$show_posts = $wheniwasbad_options['slider_options'];
				$args = array( 'numberposts' => $show_posts ); // set this to how many posts you want in the carousel
				$myposts = get_posts( $args );
				$post_num = 0;
				foreach( $myposts as $post ) :	setup_postdata($post);
					$post_num++;
					$post_thumbnail_id = get_post_thumbnail_id();
					$featured_src = wp_get_attachment_image_src( $post_thumbnail_id, 'wpbs-featured-carousel' );
				?>

			    <div class="<?php if($post_num == 1){ echo 'active'; } ?> item">
			    	<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_post_thumbnail( 'wpbs-featured-carousel' ); ?></a>

				   	<div class="carousel-caption">

		                <h4><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h4>
		                <p>
		                	<?php
		                		$excerpt_length = 100; // length of excerpt to show (in characters)
		                		$the_excerpt = get_the_excerpt(); 
		                		if($the_excerpt != ""){
		                			$the_excerpt = substr( $the_excerpt, 0, $excerpt_length );
		                			echo $the_excerpt . '... ';
		                	?>
		                	<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>" class="btn btn-primary">Read more &rsaquo;</a>
		                	<?php } ?>
		                </p>

	                </div>
			    </div>

			    <?php endforeach; ?>
				<?php $post = $tmp_post; ?>

			    </div>

			    <!-- Carousel nav -->
			    <a class="carousel-control left" href="#myCarousel" data-slide="prev">&lsaquo;</a>
			    <a class="carousel-control right" href="#myCarousel" data-slide="next">&rsaquo;</a>
		    </div>
		
		</div> <!-- container for carousel -->

<?php endif; // end carousel ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		
	<?php 
		$post_thumbnail_id = get_post_thumbnail_id();
		$featured_src = wp_get_attachment_image_src( $post_thumbnail_id, 'wpbs-featured-home' );
		$jumbotron_contents = get_post_meta($post->ID, 'jumbotron_contents' , true);
		$jumbotron_bg_color = get_post_meta($post->ID, 'jumbotron_bg_color' , true);
		$jumbotron_bg_image = get_post_meta($post->ID, 'jumbotron_bg_image' , true);

		$sidebar_position = get_post_meta($post->ID, 'sidebar_position' , true);
		$sidebar_widget_group = get_post_meta($post->ID, 'sidebar_widgets' , true);
		$hide_empty_sidebar = $wheniwasbad_options['hide_widgets'];
		if ( is_active_sidebar($sidebar_widget_group) && ! $hide_empty_sidebar ) {
			if ( $sidebar_position == 'left' ) {
				$main_class = "col-md-9 col-md-push-3";
				$sidebar_class = "col-md-3 col-md-pull-9";
			} elseif ( $sidebar_position == 'right' ) {
				$main_class = "col-md-9 col-md-pull-3";
				$sidebar_class = "col-md-3 col-md-push-9";
			}
		} else {
			$main_class = "col-md-12";
			$sidebar_class = "";
		}
		
	?>
	
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
	
	<div class="container clearfix">
		
		<div class="row clearfix">
	
			<section id="main" class="<?php echo $content_class; ?> clearfix" role="main">
		
				<?php get_template_part( 'content' ); ?>
							
			</section> <!-- end main -->
			
			<?php if ($sidebar_class != ''): ?>
		
				<section class="<?php echo $sidebar_class; ?> clearfix">
		
					<?php get_sidebar('sidebar2'); // sidebar 2 ?>
		
				</section>
		
			<?php endif; ?>					
			
		</div> <!-- row -->
	</div> <!-- container -->
				
<?php endwhile; ?>

<?php else : ?>
		
	<?php not_found(); ?>				
	
<?php endif; ?>
			    
</div> <!-- end #content -->

<?php get_footer(); ?>