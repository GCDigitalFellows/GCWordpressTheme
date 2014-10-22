		<footer id="page-footer" role="contentinfo">

			<div id="inner-footer" class="container clearfix">

	        	<div id="widget-footer" class="row clearfix">

		            <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('footer1') ) : ?>
		            <?php endif; ?>
		            <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('footer2') ) : ?>
		            <?php endif; ?>
		            <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('footer3') ) : ?>
		            <?php endif; ?>

		        </div>

				<?php global $gctheme_options; 
				print_r($gcthemeoptions);?>

				<?php if ($gctheme_options['opt-footer-text'] !== "") {
					echo $gctheme_options['opt-footer-text'];
				} ?>

			</div> <!-- end #inner-footer -->

		</footer> <!-- end footer -->

	<?php wp_footer(); ?>
	<script src="//localhost:35729/livereload.js"></script>

	</body>

</html>