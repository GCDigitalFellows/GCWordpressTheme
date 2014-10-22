<?php
wp_enqueue_style('blueimp-gallery-css');
wp_enqueue_script('blueimp-gallery-js');
wp_enqueue_script('blueimp-gallery-init-js');
$gallery_id = 'blueimp_gallery_'.rand();
$links_id = 'links-'.$gallery_id;
$attr = array(
			'order'      => 'ASC',
			'orderby'    => 'menu_order ID',
			'id'         => $post->ID,
			'itemtag'    => '',
			'icontag'    => '',
			'captiontag' => '',
			'columns'    => 3,
			'size'       => 'large',
			'include'    => implode($meta_images,','),
			'exclude'    => '',
			'showcontrols'	=> true,
			'showtooltips'	 => true,
			'showcaptions'	=> false
		);
?>

					<header class="page-header">

						<h1><?php the_title(); ?></h1>

					</header>

					<article id="post-<?php the_ID(); ?>" <?php post_class("clearfix"); ?> role="article">

					<?php if ( is_array($meta_images) ) : // display images ?>

							<?php echo bootstrap_gallery($attr); ?>
						
						<div class="col-md-4 col-sm-6 col-xs-12">

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