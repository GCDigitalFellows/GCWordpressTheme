					<header class="page-header">

						<h1><?php the_title(); ?></h1>

					</header>

					<article id="post-<?php the_ID(); ?>" <?php post_class("clearfix"); ?> role="article">

						<div id="myCarousel" class="carousel slide" data-ride="carousel" data-interval="5000" data-pause="hover">

							<!-- Indicators -->
							<ol class="carousel-indicators">
							<?php for ($c=0;$c<$post_num;$c++){ ?>
						    	<li data-target="#myCarousel" data-slide-to="<?php echo $c; ?>"<?php if ($c==0) echo 'class="active"'; ?>></li>
						    <?php } ?>
							</ol>
							
							<!-- Carousel items -->
							<div class="carousel-inner">

								<?php $post_num = 0; ?>

								<?php foreach( $meta_images as $img_id ) : $post_num++; ?>

								    <div class="item <?php if($post_num == 1){ echo 'active'; } ?>" style="overflow:hidden;">

										<a href="<?php echo wp_get_attachment_url($img_id) ?>" rel="bookmark" title="<?php echo get_the_title($img_id); ?>">
											
											<img src="<?php echo wp_get_attachment_url($img_id); ?>" class="wpbs-featured-carousel" alt="<?php echo get_post_meta($img_id, '_wp_attachment_image_alt', true); ?>" style="width:100%; height: auto;" >
										
										</a>

										<div class="carousel-caption"></div>

									</div>

								<?php endforeach;	?>

							</div> <!-- Carousel Inner -->

						    <!-- Carousel nav -->
						    <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
								<span class="glyphicon glyphicon-chevron-left"></span>
							</a>
							<a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
								<span class="glyphicon glyphicon-chevron-right"></span>
							</a>
						
						</div> <!-- container for carousel -->

						<div class="col-md-8 col-sm-6 col-xs-12">

							<?php the_content(); ?>

							<?php if ($meta_website !== '') : ?>

								<a href="<?php echo $meta_website; ?>" class="btn btn-sm btn-info" title="Visit <?php the_title_attribute(); ?>" target="_blank">

									Visit <?php the_title_attribute(); ?> <span class="glyphicon glyphicon-new-window"></span>

								</a>

							<?php endif; ?>

						</div>

					</article>