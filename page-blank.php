<?php
/*
Template Name: Blank Page
*/
?>

<?php get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	


<div id="content" class="content-no-margin clearfix">
	<?php the_content(); ?>
</div>												


<?php endwhile; ?>

<?php else : ?>

	<?php not_found(); ?>
		
<?php endif; ?>


<?php get_footer(); ?>