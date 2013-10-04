<?php
/**
 * The WordPress template hierarchy first checks for any
 * MIME-types and then looks for the attachment.php file.
 *
 * @link codex.wordpress.org/Template_Hierarchy#Attachment_display 
 */ 

get_header(); ?>

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
?>

			
	<div id="content" class="container clearfix">
	
		<div id="main" class="clearfix" role="main">

			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			
			<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">

				<header> 
				
					<div class="page-header">
						<h1 class="single-title" itemprop="headline">
							<?php if ($post->post_parent) : ?>
								<a href="<?php echo get_permalink($post->post_parent); ?>" rev="attachment"><?php echo get_the_title($post->post_parent); ?></a> <i class="glyphicon glyphicon-caret-right"></i>
							<?php endif; ?>
							<?php the_title(); ?>
						</h1>
					</div>

				</header> <!-- end article header -->
				
				<div class="row">
										
					<div class="col-md-9">
					
						<section class="post_content clearfix" itemprop="articleBody">
			
							<!-- To display current image in the photo gallery -->
							<div class="attachment-img well">
							      <a href="<?php echo wp_get_attachment_url($post->ID); ?>" class="img-thumbnail" >
			      							      
							      <?php 
							      	$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'large' );
								      if ($image) : 
										  $alt_text = get_post_meta( $post->ID, '_wp_attachment_image_alt', true); ?>
								          <img src="<?php echo $image[0]; ?>" alt="<?php echo $alt_text; ?>" />
								      <?php endif; ?>
			      
							      </a>
								<?php if ( !empty($post->post_excerpt) ) { ?> 
								<p class="caption" style="word-break: break-all; word-wrap: break-word;"><?php echo get_the_excerpt(); ?></p>
								<?php } ?>
								  
							</div>
							
							<!-- To display thumbnail of previous and next image in the photo gallery -->
							<ul id="gallery-nav" class="clearfix">
								<li class="previous pull-left"><?php previous_image_link() ?></li>
								<li class="next pull-right"><?php next_image_link() ?></li>
							</ul>
			
						</section> <!-- end article section -->
						
						<?php comments_template(); ?>

					</div>

					<div class="col-md-3">

						<?php get_template_part('postmeta','attachment'); ?>
						
						<div class=" well">
		
							<h3><?php _e("Image metadata","wheniwasbad"); ?></h3>
								<!-- Using WordPress functions to retrieve the extracted EXIF information from database -->					
							   <?php
								$imgmeta = wp_get_attachment_metadata( $id );
								if ( empty($imagemeta) ) {
									_e("This image does not contain any metadata.","wheniwasbad");
								} else {
									// Convert the shutter speed retrieve from database to fraction
									if ($imgmeta['image_meta']['shutter_speed']) {
										if ((1 / $imgmeta['image_meta']['shutter_speed']) > 1) {
											if ((number_format((1 / $imgmeta['image_meta']['shutter_speed']), 1)) == 1.3
											or number_format((1 / $imgmeta['image_meta']['shutter_speed']), 1) == 1.5
											or number_format((1 / $imgmeta['image_meta']['shutter_speed']), 1) == 1.6
											or number_format((1 / $imgmeta['image_meta']['shutter_speed']), 1) == 2.5){
										    	$pshutter = "1/" . number_format((1 / $imgmeta['image_meta']['shutter_speed']), 1, '.', '') . " second";
										 	} else {
												$pshutter = "1/" . number_format((1 / $imgmeta['image_meta']['shutter_speed']), 0, '.', '') . " second";
										 	}
										} else {
											$pshutter = $imgmeta['image_meta']['shutter_speed'] . " seconds";
										}
									}

									// Start to display EXIF and IPTC data of digital photograph
									if ( $imgmeta['image_meta']['created_timestamp'] ) { 
										echo __("Date Taken","wheniwasbad") . ": " . date("d-M-Y H:i:s", $imgmeta['image_meta']['created_timestamp'])."<br />"; }
									if ( $imgmeta['image_meta']['copyright'] ) { 
										echo __("Copyright","wheniwasbad") . ": " . $imgmeta['image_meta']['copyright']."<br />"; }
									if ( $imgmeta['image_meta']['credit'] ) { 
										echo __("Credit","wheniwasbad") . ": " . $imgmeta['image_meta']['credit']."<br />"; }
									if ( $imgmeta['image_meta']['title'] ) { 
										echo __("Title","wheniwasbad") . ": " . $imgmeta['image_meta']['title']."<br />"; }
									if ( $imgmeta['image_meta']['caption'] ) { 
										echo __("Caption","wheniwasbad") . ": " . $imgmeta['image_meta']['caption']."<br />"; }
									if ( $imgmeta['image_meta']['camera'] ) { 
										echo __("Camera","wheniwasbad") . ": " . $imgmeta['image_meta']['camera']."<br />"; }
									if ( $imgmeta['image_meta']['focal_length'] ) { 
										echo __("Focal Length","wheniwasbad") . ": " . $imgmeta['image_meta']['focal_length']."mm<br />"; }
									if ( $imgmeta['image_meta']['aperture'] ) { 
										echo __("Aperture","wheniwasbad") . ": f/" . $imgmeta['image_meta']['aperture']."<br />"; }
									if ( $imgmeta['image_meta']['iso'] ) { 
										echo __("ISO","wheniwasbad") . ": " . $imgmeta['image_meta']['iso']."<br />"; }
									if ( $pshutter ) { 
										echo __("Shutter Speed","wheniwasbad") . ": " . $pshutter . "<br />"; }
								} ?>
					   
						</div> <!-- image metadata -->

					</div>

				</div>
									
			</article> <!-- end article -->
			
			<?php endwhile; ?>			
			
		<?php else : ?>
		
			<?php not_found('attachment'); ?>
		
		<?php endif; ?>
			
	
		</div> <!-- end #main -->

	</div> <!-- end #content -->

<?php get_footer(); ?>