<?php 
if (! is_home() && ! is_front_page() ){
$author = get_the_author();
$author = ($author == '') ? get_the_author_meta('user_nicename') : $author;
?>

<div class="entry-meta muted pull-right">
		<i class="glyphicon glyphicon-pencil"></i> <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php echo $author; ?></a>&nbsp;&bull;&nbsp;
		<i class="glyphicon glyphicon-bookmark"></i> <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute( array( 'before' => 'Permalink to: ', 'after' => '' ) ); ?>" rel="bookmark">Permalink</a>
		<?php edit_post_link( __( 'Edit', 'gcwordpresstheme' ), ' &nbsp;&bull;&nbsp; ', '' ); ?>
	<?php if ( comments_open() && get_comments_number() ) : ?>
		&nbsp;&bull;&nbsp;<i class="glyphicon glyphicon-comment"></i> <?php comments_popup_link( __("Leave a comment","wheniwasbad"), __( "One Comment", "wheniwasbad"), __( "% Comments", "wheniwasbad" ) ); ?>
	<?php endif; // comments_open() ?>
</div>
<?php } ?>