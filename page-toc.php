<?php
/*
Template Name: TOC Page Template
*/
?>

<?php get_header(); ?>

<?php 
	wp_enqueue_script('tocify-js');
	wp_enqueue_style('tocify-css');
?>


<?php if (have_posts()) : while ( have_posts() ) : the_post(); ?>

	<?php 
		global $gctheme_options;
		$sidebar_position = get_post_meta($post->ID, 'sidebar_position' , true);
		$sidebar_widget_group = get_post_meta($post->ID, 'sidebar_widgets' , true);
		$hide_empty_sidebar = $gctheme_options['hide_widgets'];
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
	
	<div id="content" class="container clearfix">

		<div class="row clearfix">

			<section id="main" class="<?php echo $main_class; ?> clearfix" role="main">
	
				<div class="container">

					<div class="row" id="cv-content">
						
						<div class="col-md-3 col-sm-4 hidden-xs hidden-print" id="toc-wrap">
							
							<div id="toc" class="affix" data-spy="affix"></div>
						</div>

						<div class="col-md-9 col-sm-8 col-xs-12">

							<header class="page-header">
	
								<h1><?php the_title(); ?></h1>
								
								<?php get_template_part('postmeta-horizontal'); ?>
						
							</header>

							<?php get_template_part( 'content' ); ?>
						</div>
					</div>
				</div>
						
			</section> <!-- end #main -->
	
			<?php if ($sidebar_class != ''): ?>
	
				<section class="<?php echo $sidebar_class; ?> clearfix">
	
					<?php get_sidebar($sidebar_widget_group); ?>
	
				</section>
	
			<?php endif; ?>		

		</div>

	</div> <!-- end #content -->

<script>//<![CDATA[
jQuery( document ).ready(function( $ ) {
	var position = $('body').position();
	var offsetTop = position.top;
	$("#toc").tocify( { context: "#cv-content", selectors: "h2,h3,h4", scrollTo: offsetTop, showEffect: 'slideDown' } );
	$('#toc').width($('#toc-wrap').width());
	$('#toc').affix({ offset: offsetTop });
	
	setTimeout( function () { 
	 	var $sideBar = $('#toc');
      	$sideBar.affix({
      		offset: {
      			top: function () {
		            var offsetTop      = $sideBar.offset().top;
		            var sideBarMargin  = parseInt($sideBar.children(0).css('margin-top'), 10);
		            var navOuterHeight = $('.navbar-static-top').height();
		            return (this.top = offsetTop - navOuterHeight - sideBarMargin);
          		}, 
          		bottom: function () { 
          			return (this.bottom = $('#page-footer').outerHeight(true));
          		}
          	}
        })
    }, 100);

    setTimeout(function () { 
    	$('.bs-top').affix() 
    }, 100);

});
//]]></script>
	
<?php endwhile; // end of the loop. ?>

<?php else : ?>

	<?php not_found(); ?>

<?php endif; ?>

<?php get_footer(); ?>
