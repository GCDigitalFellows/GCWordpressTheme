<?php 
global $wheniwasbad_options;
$show_blog_sidebar = $wheniwasbad_options['blog_sidebar'];
if (is_page()) {
	$sidebar_position = get_post_meta($post->ID, 'sidebar_position' , true);
	$content_class = 'row';
} else {
	if ($show_blog_sidebar) {
		$sidebar_position = $wheniwasbad_options['blog_sidebar_position'];	
	} else {
		$sidebar_position = 'left'; // default so postmeta is positioned on the right
	}
}

if ( ! is_singular() || ! is_page() ) {
	$content_class = 'media ';
	if ( $sidebar_position == 'right' ) {
		$postmeta_class = 'col-md-3 col-md-pull-9';
		$content_class .= 'col-md-9 col-md-push-3';
	} else {
		$postmeta_class = 'col-md-3';
		$content_class .= 'col-md-9';
	} 
} else {
	$postmeta_class = '';
	$content_class = 'col-md-12';
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class("clearfix"); ?> role="article">
	<div class="<?php echo $content_class; ?>">
		<?php if ( ! is_singular() && has_post_thumbnail()) {
		   $large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'large');
		   echo '<a href="' . $large_image_url[0] . '" title="' . the_title_attribute('echo=0') . '" class="pull-left">';
		   echo get_the_post_thumbnail($post->ID, 'thumbnail',array('class' => 'media-object')); 
		   echo '</a>';
		 }?>
		<?php if ( ! is_singular() || ! is_page() ) { ?>
			<div class="media-body">
		<?php } else { ?>
			<div>
		<?php } ?>
			<?php if ( ! is_singular() && get_the_title() != '' ) : ?>
				<?php if ( ! is_singular() || ! is_page() ) { ?>
					<header class="entry-header media-heading">
				<?php } else { ?>
					<header class="entry-header">
				<?php } ?>
					<h3><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute( array( 'before' => 'Permalink to: ', 'after' => '' ) ); ?>" rel="bookmark"><?php the_title(); ?>
						<?php if (get_post_format() == 'link') : ?>
							<i class="glyphicon glyphicon-external-link"></i>
						<?php endif; ?>
					</a></h3>
				</header>
			<?php endif; ?>
			<section class="post_content clearfix">
			<?php 
				if ( ! is_singular()) {
					if ( $wheniwasbad_options['use_excerpts'] ) {
						the_excerpt();
					} else {
						the_content( __("<span class='btn btn-primary pull-right'>Read more <i class='glyphicon glyphicon-chevron-sign-right'></i></span>","wheniwasbad") );
					}	
				} else {
					the_content();
					wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'wheniwasbad' ), 'after' => '</div>' ) );
					if ( ! is_front_page() )
						comments_template( '', true );
				} 
			?>
			</section> <!-- post-content -->
		</div>
	</div>

	<?php if ($postmeta_class) : ?>

		<div class="<?php echo $postmeta_class; ?>">

			<?php get_template_part('postmeta',get_post_type()); ?>

		</div>
	
	<?php endif; ?>

</article>