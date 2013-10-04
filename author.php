<?php get_header(); ?>
			
	<div id="content" class="container clearfix">

	<?php if (have_posts()) : ?>
		
		<?php
			$curauth = (get_query_var('author_name')) ? get_user_by('slug', get_query_var('author_name')) : get_userdata(get_query_var('author'));
		?>

		<header class="page-header">

			<h1 class="archive_title h1">
			
				<?php 
					// If google profile field is filled out on author profile, link the author's page to their google+ profile page
					$google_profile = get_the_author_meta( 'google_profile', $curauth->ID );
					if ( $google_profile ) {
						echo '<a href="' . esc_url( $google_profile ) . '" rel="me">' . $curauth->display_name . '</a>'; 
				?>
				<?php 
					} else {
						echo get_the_author_meta('display_name', $curauth->ID);
					}
				?>
				
			</h1>
			
		</header> <!-- page header -->
			
		<div id="wrapper" class="row clearfix">
				
			<div class="col-md-3">	
			
				<? // replace this with the user's profile?! ?>
				<?php get_sidebar(); // sidebar 1 ?>
			
			</div>
			
			<div id="main" role="main" class="col-md-9">
				
				<ul class="nav nav-tabs" id="myTab">
					<li class="active"><a href="#posts">Posts</a></li>
					<li><a href="#comments">Comments</a></li>
					<!--
						<li><a href="#profile">Profile</a></li>
						<li><a href="#contact">Contact</a></li>
					-->
				</ul>
				
				<div class="tab-content">
					<div class="tab-pane active" id="posts">
						<?php while (have_posts()) : the_post(); ?>
	
							<?php get_template_part( 'content', get_post_format() ); ?>

							<?php comments_template( '', true ); ?>

						<?php endwhile; // end of the loop. ?>
						<?php wp_link_pages( $args ); ?>
						<?php if (function_exists('page_navi')) : // if expirimental feature is active ?>
			
							<?php page_navi(); // use the page navi function ?>

						<?php else : // if it is disabled, display regular wp prev & next links ?>
							<nav class="wp-prev-next pagenavi">
								<ul class="clearfix">
									<li class="prev-link"><?php next_posts_link(_e('<i class="glyphicon glyphicon-chevron-sign-left"></i> Older Entries', "wheniwasbad")) ?></li>
									<li class="next-link"><?php previous_posts_link(_e('Newer Entries <i class="glyphicon glyphicon-chevron-sign-right"></i>', "wheniwasbad")) ?></li>
								</ul>
							</nav>
						<?php endif; ?>
					</div>
					
					<div class="tab-pane" id="comments">
					<?php
						$args = array('user_id' => $curauth->ID, 'status' => 'approve');
						$comments = get_comments($args);
						foreach($comments as $comment) : ?>
					 	<div class="row">
							<div class="col-md-9">
								<header class="entry-header">
									<h3><a href="<?php echo get_permalink($comment->comment_post_ID); ?>" title="On: '<?php echo get_the_title($comment->comment_post_ID); ?>'" rel="bookmark">On "<?php echo get_the_title($comment->comment_post_ID); ?>"</a></h3>
								</header>
								<section class="post_content clearfix">
									<?php echo($comment->comment_content); ?>
								</section>
							</div>
							<div class="col-md-3">
								<aside class="entry-meta muted<?php echo $extra_classes; ?>">
									<?php $original_post = get_post($comment->comment_post_ID); ?>
									<p><i class="glyphicon glyphicon-pencil"></i> Original post by <a href="<?php echo get_author_posts_url($original_post->post_author); ?>" title="Author page for <?php echo get_the_author_meta('display_name', $original_post->post_author); ?>"><?php echo get_the_author_meta('display_name', $original_post->post_author); ?></a></p>
									<p><i class="glyphicon glyphicon-calendar-empty"></i> <time datetime="<?php echo get_comment_date(DATE_W3C,$comment->comment_ID); ?>" ><?php echo get_comment_date(get_option('date_format'),$comment->comment_ID); ?></time></p>
									<p><i class="glyphicon glyphicon-bookmark"></i> <a href="<?php echo get_permalink($comment->comment_post_ID); ?>" title="On: '<?php echo get_the_title($comment->comment_post_ID); ?>'" rel="bookmark">Permalink</a>
									<?php edit_comment_link( __( 'Edit', 'wheniwasbad' ), ' &nbsp;&bull;&nbsp; ', '' ); ?></p>
								</aside>
			 				</div>
						</div>
						<?php endforeach; ?>
					</div>
				<!--
					<div class="tab-pane" id="profile">
						
					</div>

					<div class="tab-pane" id="contact">
						
					</div>
				-->
			</div><!-- col-md-9 articles -->

		</div><!-- #main -->
		
	<?php else : ?>
			
		<?php not_found('author'); ?>
		
	<?php endif; ?>

	</div> <!-- end #content -->

<script>
jQuery('#myTab a').click(function (e) {
  e.preventDefault();
  jQuery(this).tab('show');
})
</script>
<?php get_footer(); ?>
