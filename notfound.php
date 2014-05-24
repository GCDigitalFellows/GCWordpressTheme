<?php 
function not_found($content_type = null) { ?>

	<article id="post-not-found" class="clearfix">
	
		<header>

			<div class="jumbotron">
		
				<h1><?php _e("Oops!","wheniwasbad"); ?></h1>
				<p><?php _e("This is embarassing. We can't find what you were looking for.","wheniwasbad"); ?></p>
										
			</div>
								
		</header> <!-- end article header -->

		<section class="post_content">

			<?php
			switch($content_type) {
				case 'index':
 					_e("We could not locate any posts","wheniwasbad");
 					break;
				case 'day':
					_e("We could not locate any posts published on ","wheniwasbad");
					_e(the_time('l, F j, Y').".");
					break;
				case 'month':
					_e("We could not locate any posts published in ","wheniwasbad");
					_e(the_time('F, Y').".");
					break;
				case 'year':
					_e("We could not locate any posts published in ","wheniwasbad");
					_e(the_time('Y').".");
					break;
				case 'author':
					_e("We could not locate any posts written by ","wheniwasbad");
					$curauth = (get_query_var('author_name')) ? get_user_by('slug', get_query_var('author_name')) : get_userdata(get_query_var('author'));
					$google_profile = get_the_author_meta( 'google_profile', $curauth->ID );
					if ( $google_profile ) {
						echo '<a href="' . esc_url( $google_profile ) . '" rel="me">' . $curauth->display_name . '</a>.'; 
					} else {
						echo get_the_author_meta('display_name', $curauth->ID) . ".";
					}
					break;
				case 'category':
					_e("We could not locate any posts filed under '","wheniwasbad");
					_e(single_cat_title()."'.");
					break;
				case 'tag':
					_e("We could not locate any posts tagged with '","wheniwasbad");
					_e(single_tag_title()."'.");
					break;
				case 'people':
					_e("We could not locate any people in the database'","wheniwasbad");
					break;
				default:
					_e("Sorry, whatever you were looking for was not found.","wheniwasbad");
			}
			?>
		
			<p><?php _e("Try searching the site using the form below.","wheniwasbad"); ?></p>

			<div class="row">
				<div class="col-md-12">
					<?php get_search_form(); ?>
				</div>
			</div>

		</section> <!-- end article section -->
	
		<footer>
		
		</footer> <!-- end article footer -->

	</article> <!-- end article -->
<?php } ?>