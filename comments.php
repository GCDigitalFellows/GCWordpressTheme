<?php
/*
comments page
*/

  if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
    die ('Please do not load this page directly. Thanks!');

  if ( post_password_required() ) { ?>
  	<div class="alert alert-info"><?php _e("This post is password protected. Enter the password to view comments.","wheniwasbad"); ?></div>
  <?php
    return;
  }
?>

<?php if ( have_comments() ) : ?>
	<?php if ( ! empty($comments_by_type['comment']) ) : ?>
	<h3 id="comments"><?php comments_number('<span>' . __("No","wheniwasbad") . '</span> ' . __("Responses","wheniwasbad") . '', '<span>' . __("One","wheniwasbad") . '</span> ' . __("Response","wheniwasbad") . '', '<span>%</span> ' . __("Responses","wheniwasbad") );?> <?php _e("to","wheniwasbad"); ?> &#8220;<?php the_title(); ?>&#8221;</h3>

	<nav id="comment-nav">
		<ul class="clearfix">
	  		<li><?php previous_comments_link( __("Older comments","wheniwasbad") ) ?></li>
	  		<li><?php next_comments_link( __("Newer comments","wheniwasbad") ) ?></li>
	 	</ul>
	</nav>
	
	<ol class="commentlist">
		<?php wp_list_comments('type=comment&callback=comments_layout'); ?>
	</ol>
	
	<?php endif; ?>
	
	<?php if ( ! empty($comments_by_type['pings']) ) : ?>
		<h3 id="pings">Trackbacks/Pingbacks</h3>
		
		<ol class="pinglist">
			<?php wp_list_comments('type=pings&callback=list_pings'); ?>
		</ol>
	<?php endif; ?>
	
	<nav id="comment-nav">
		<ul class="clearfix">
	  		<li><?php previous_comments_link( __("Older comments","wheniwasbad") ) ?></li>
	  		<li><?php next_comments_link( __("Newer comments","wheniwasbad") ) ?></li>
		</ul>
	</nav>
  
	<?php else : // this is displayed if there are no comments so far ?>

	<?php if ( comments_open() ) : ?>
    	<!-- If comments are open, but there are no comments. -->

	<?php else : // comments are closed 
	?>
	
	<?php
		global $wheniwasbad_options;
		$suppress_comments_message = $wheniwasbad_options['suppress_comments_message'];

		if (is_page() && $suppress_comments_message) :
	?>
			
		<?php else : ?>
		
			<!-- If comments are closed. -->
			<p class="alert alert-info"><?php _e("Comments are closed","wheniwasbad"); ?>.</p>
			
		<?php endif; ?>

	<?php endif; ?>

<?php endif; ?>


<?php if ( comments_open() ) : ?>

<section id="respond" class="respond-form">

	<h3 id="comment-form-title"><?php comment_form_title( __("Leave a Reply","wheniwasbad"), __("Leave a Reply to","wheniwasbad") . ' %s' ); ?></h3>

	<div id="cancel-comment-reply">
		<p class="small"><?php cancel_comment_reply_link( __("Cancel","wheniwasbad") ); ?></p>
	</div>

	<?php if ( get_option('comment_registration') && !is_user_logged_in() ) : ?>
  	<div class="help">
  		<p><?php _e("You must be","wheniwasbad"); ?> <a href="<?php echo wp_login_url( get_permalink() ); ?>"><?php _e("logged in","wheniwasbad"); ?></a> <?php _e("to post a comment","wheniwasbad"); ?>.</p>
  	</div>
	<?php else : ?>

	<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" class="form-horizontal" method="post" id="commentform" role="form">

	<?php if ( is_user_logged_in() ) : ?>

	<p class="comments-logged-in-as"><?php _e("Logged in as","wheniwasbad"); ?> <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo wp_logout_url(get_permalink()); ?>" title="<?php _e("Log out of this account","wheniwasbad"); ?>"><?php _e("Log out","wheniwasbad"); ?> <i class="glyphicon glyphicon-signout"></i></a></p>

	<?php else : ?>
		
		<div class="form-group">
		  <label for="author" class="col-lg-3 control-label"><?php _e("Name","wheniwasbad"); ?> <?php if ($req) echo "(required)"; ?></label>
		  <div class="input-group col-lg-6">
		  	<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span><input type="text" class="form-control" name="author" id="author" value="<?php echo esc_attr($comment_author); ?>" placeholder="<?php _e("Your Name","wheniwasbad"); ?>" tabindex="1" <?php if ($req) echo "aria-required='true'"; ?> />
		  </div>
	  	</div>

		<div class="form-group">
		  <label for="email" class="col-lg-3 control-label"><?php _e("Mail","wheniwasbad"); ?> <?php if ($req) echo "(required)"; ?></label>
		  <div class="input-group col-lg-6">
		  	<span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span><input type="email" class="form-control" name="email" id="email" value="<?php echo esc_attr($comment_author_email); ?>" placeholder="<?php _e("Your Email","wheniwasbad"); ?>" tabindex="2" <?php if ($req) echo "aria-required='true'"; ?> />
		  </div>
		  <span class="help-block">(<?php _e("will not be published","wheniwasbad"); ?>)</span>
	  	</div>

		<div class="form-group">
		  <label for="url" class="col-lg-3 control-label"><?php _e("Website","wheniwasbad"); ?></label>
		  <div class="input-group col-lg-6">
		  <span class="input-group-addon"><i class="glyphicon glyphicon-home"></i></span><input type="url" class="form-control" name="url" id="url" value="<?php echo esc_attr($comment_author_url); ?>" placeholder="<?php _e("Your Website","wheniwasbad"); ?>" tabindex="3" />
		  </div>
	  	</div>

	<?php endif; ?>
	
	<div class="form-group">
		<div class="col-lg-9 col-lg-push-3">
			<textarea class="form-control" rows="5" name="comment" id="comment" placeholder="<?php _e("Your Comment Here&hellip;","wheniwasbad"); ?>" tabindex="4"></textarea>
		</div>
	</div>
	<div class="form-group">
		<div class="col-lg-9 col-lg-push-3">
			<input class="btn btn-primary" name="submit" type="submit" id="submit" tabindex="5" value="<?php _e("Submit Comment","wheniwasbad"); ?>" />
			<?php comment_id_fields(); ?>
		</div>
	</div>

	
	<?php 
		//comment_form();
		do_action('comment_form()', $post->ID); 
	
	?>
	
	</form>
	
	<?php endif; // If registration required and not logged in ?>
</section>

<?php endif; // if you delete this the sky will fall on your head ?>
