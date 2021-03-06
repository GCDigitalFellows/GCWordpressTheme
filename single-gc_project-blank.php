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

							<?php the_content(); ?>

							<?php if ($meta_website !== '') : ?>

								<a href="<?php echo $meta_website; ?>" class="btn btn-sm btn-info" title="Visit <?php the_title_attribute(); ?>" target="_blank">

									Visit <?php the_title_attribute(); ?> <span class="glyphicon glyphicon-new-window"></span>

								</a>

							<?php endif; ?>

						</div>

					</article>