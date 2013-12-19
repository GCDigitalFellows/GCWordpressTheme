<?php
/*
Author: Keith Miyake
URL: htp://keithmiyake.info/gcwordpresstheme/
Version: 0.1
Modified: 20131003
*/

/************* DEBUGGING TOOLS ****************/
// const WPBS_DEBUGGING turns on or off the theme's development/debugging tools
define("WPBS_DEBUGMODE",false);


// Redux Options
if ( ! class_exists( 'ReduxFramework' ) ){
    require_once(dirname(__FILE__) . '/library/ReduxFramework/ReduxCore/framework.php');
}
require_once('redux-options.php');

// Custom Metaboxes and Fields (https://github.com/jaredatch/Custom-Metaboxes-and-Fields-for-WordPress)
if ( !class_exists( 'cmb_Meta_Box' ) ) {
	define('CMB_URL',get_template_directory_uri().'/library/Custom-Meta-Boxes/');
	require_once( 'library/Custom-Meta-Boxes/custom-meta-boxes.php' );
	require_once( 'library/metaboxes.php' );
}

// Shortcodes
require_once('library/shortcodes.php');


// custom function for displaying page not found info
if ( !function_exists( 'not_found' ) ) {
	require_once('notfound.php');
}

// Menu output mod for bootstrap
require_once('library/wp-bootstrap-navwalker/wp_bootstrap_navwalker.php');
	
/************** General Theme Setup *****************/

// Clean up unwanted stuff from header
function clean_header() {
	// EditURI link
	remove_action( 'wp_head', 'rsd_link' );
	// windows live writer
	remove_action( 'wp_head', 'wlwmanifest_link' );
}
add_action('init', 'clean_header');

// Adding WP 3+ Functions & Theme Support
function theme_setup() {
	add_theme_support('post-thumbnails');      // wp thumbnails (sizes handled in functions.php)
	set_post_thumbnail_size(200, 200, true);   // default thumb size
	add_theme_support( 'custom-background' );  // wp custom background
	add_theme_support('automatic-feed-links'); // rss thingy
//	add_theme_support('bootstrap-gallery'); //TODO: add theme support to turn off custom galleries
	add_theme_support( 'post-formats',      // post formats
		array( 
			'aside',   // title less blurb
			'gallery', // gallery of images
			'link',    // quick link to other site
			'image',   // an image
			'quote',   // a quick quote
			'status',  // a Facebook like status update
			'video',   // video 
			'audio',   // audio
			'chat'     // chat transcript 
		)
	);	
	add_theme_support( 'menus' );            // wp menus
	register_nav_menus(                      // wp3+ menus
		array(
			'service_nav' => 'Services Menu in Header',
			'main_nav' => 'Primary Menu in Header', 
			'footer_links' => 'Footer Links'
		)
	);
	add_theme_support( 'infinite-scroll', array(
		'container'			=> 'main',
		'footer_widgets'	=> array( 'sidebar-3', 'sidebar-4', 'sidebar-5' ),
        'footer'			=> 'content',
		'wrapper'			=> false
	) );
}

add_action('after_setup_theme','theme_setup');	

// clean up gallery output in wp
add_filter('use_default_gallery_style', '__return_null');

/************* CUSTOM LOGIN PAGE *****************/

// custom login css
function login_css() { wp_enqueue_style( 'login_css', get_template_directory_uri() . '/library/theme/css/login.css', false ); }
function login_url() {  return home_url(); }
function login_title() { return get_option('blogname'); }

// only call on the login page
add_action( 'login_enqueue_scripts', 'login_css', 10 );
add_filter('login_headerurl', 'login_url');
add_filter('login_headertitle', 'login_title');

/************* THUMBNAIL SIZE OPTIONS *************/

// Thumbnail sizes
add_image_size( 'wpbs-featured', 638, 300, true );
add_image_size( 'wpbs-featured-home', 970, 311, true);
add_image_size( 'wpbs-featured-carousel', 970, 400, true);

/* 
to add more sizes, simply copy a line from above 
and change the dimensions & name. As long as you
upload a "featured image" as large as the biggest
set width or height, all the other sizes will be
auto-cropped.

To call a different size, simply change the text
inside the thumbnail function.

For example, to call the 300 x 300 sized image, 
we would use the function:
<?php the_post_thumbnail( 'bones-thumb-300' ); ?>
for the 600 x 100 image:
<?php the_post_thumbnail( 'bones-thumb-600' ); ?>

You can change the names and dimensions to whatever
you like. Enjoy!
*/

/************* ACTIVE SIDEBARS ********************/

// Sidebars & Widgetizes Areas
function wpbs_register_sidebars() {
    register_sidebar(array(
    	'id' => 'sidebar1',
    	'name' => 'Main Sidebar',
    	'description' => 'Used on every page BUT the homepage page template.',
    	'before_widget' => '<div id="%1$s" class="widget %2$s">',
    	'after_widget' => '</div>',
    	'before_title' => '<h3 class="widgettitle">',
    	'after_title' => '</h3>',
    ));
    
    register_sidebar(array(
    	'id' => 'sidebar2',
    	'name' => 'Homepage Sidebar',
    	'description' => 'Used only on the homepage page template.',
    	'before_widget' => '<div id="%1$s" class="widget %2$s">',
    	'after_widget' => '</div>',
    	'before_title' => '<h3 class="widgettitle">',
    	'after_title' => '</h3>',
    ));
	
    register_sidebar(array(
      'id' => 'sidebarnav',
      'name' => 'Navigation Sidebar',
      'before_widget' => '<div id="%1$s" class="widget %2$s">',
      'after_widget' => '</div>',
      'before_title' => '<h3 class="widgettitle tree-header">',
      'after_title' => '</h3>',
    ));
    
    register_sidebar(array(
      'id' => 'footer1',
      'name' => 'Footer 1',
      'before_widget' => '<div id="%1$s" class="widget col-md-4 %2$s">',
      'after_widget' => '</div>',
      'before_title' => '<h4 class="widgettitle">',
      'after_title' => '</h4>',
    ));

    register_sidebar(array(
      'id' => 'footer2',
      'name' => 'Footer 2',
      'before_widget' => '<div id="%1$s" class="widget col-md-4 %2$s">',
      'after_widget' => '</div>',
      'before_title' => '<h4 class="widgettitle">',
      'after_title' => '</h4>',
    ));

    register_sidebar(array(
      'id' => 'footer3',
      'name' => 'Footer 3',
      'before_widget' => '<div id="%1$s" class="widget col-md-4 %2$s">',
      'after_widget' => '</div>',
      'before_title' => '<h4 class="widgettitle">',
      'after_title' => '</h4>',
    ));
    
    
    /* 
    to add more sidebars or widgetized areas, just copy
    and edit the above sidebar code. In order to call 
    your new sidebar just use the following code:
    
    Just change the name to whatever your new
    sidebar's id is, for example:
    
    To call the sidebar in your template, you can just copy
    the sidebar.php file and rename it to your sidebar's name.
    So using the above example, it would be:
    sidebar-sidebar2.php
    
    */
} // don't remove this bracket!
add_action( 'widgets_init', 'wpbs_register_sidebars' );

/************* Excerpts *********************/
function new_excerpt_more( $more ) {
	return ' [&hellip;]</p><p><a class="read-more btn btn-primary pull-right" href="'. get_permalink( get_the_ID() ) . '">Read more <i class="glyphicon glyphicon-chevron-right"></i></a>';
}
add_filter( 'excerpt_more', 'new_excerpt_more' );


/************* Custom Get_Avatar *********************/
function bs_get_avatar($id_or_email,$size='96',$default='',$alt=false,$class='') {
	$ret = get_avatar($id_or_email,$size,$default,$alt);
	if (! empty($class)) {
		return str_replace("class='avatar", "class='avatar ".$class." ", $ret) ;
	}
	return $ret;
}

/************* COMMENT LAYOUT *********************/
		
// Comment Layout
function comments_layout($comment, $args, $depth) {
   $GLOBALS['comment'] = $comment; ?>
	<li <?php comment_class(); ?>>
		<article id="comment-<?php comment_ID(); ?>" class="clearfix">
			<div class="comment-author vcard row clearfix">
				<div class="avatar col-md-2">
					<?php echo get_avatar( $comment, $size='75' ); ?>
				</div>
				<div class="col-md-10 comment-text">
					<?php printf('<h4>%s</h4>', get_comment_author_link()) ?>
					<?php edit_comment_link(__('Edit','wheniwasbad'),'<span class="edit-comment btn btn-sm btn-info"><i class="glyphicon glyphicon-white glyphicon-edit"></i>','</span>') ?>
                    
                    <?php if ($comment->comment_approved == '0') : ?>
       					<div class="alert-message success">
          				<p><?php _e('Your comment is awaiting moderation.','wheniwasbad') ?></p>
          				</div>
					<?php endif; ?>
                    
                    <?php comment_text() ?>
                    
                    <time datetime="<?php echo comment_time('Y-m-j'); ?>"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>"><?php comment_time('F jS, Y'); ?> </a></time>
                    
					<?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
                </div>
			</div>
		</article>
    <!-- </li> is added by WordPress automatically -->
<?php
} // don't remove this bracket!

// Display trackbacks/pings callback function
function list_pings($comment, $args, $depth) {
       $GLOBALS['comment'] = $comment;
?>
        <li id="comment-<?php comment_ID(); ?>"><i class="glyphicon glyphicon-share"></i>&nbsp;<?php comment_author_link(); ?>
<?php 

}

// Only display comments in comment count (which isn't currently displayed in wp-bootstrap, but i'm putting this in now so i don't forget to later)
add_filter('get_comments_number', 'comment_count', 0);
function comment_count( $count ) {
	if ( ! is_admin() ) {
		global $id;
	    $comments_by_type = separate_comments(get_comments('status=approve&post_id=' . $id));
	    return count($comments_by_type['comment']);
	} else {
	    return $count;
	}
}

/****************** password protected post form *****/

add_filter( 'the_password_form', 'custom_password_form' );

function custom_password_form() {
	global $post;
	$label = 'pwbox-'.( empty( $post->ID ) ? rand() : $post->ID );
	$o = '<div class="clearfix"><form class="protected-post-form" action="' . get_option('siteurl') . '/wp-login.php?action=postpass" method="post">
	' . '<p>' . __( "This post is password protected. To view it please enter your password below:" ,'wheniwasbad') . '</p>' . '
	<label for="' . $label . '">' . __( "Password:" ,'wheniwasbad') . ' </label><div class="input-group"><input name="post_password" id="' . $label . '" type="password" size="20" /><input type="submit" name="Submit" class="btn btn-primary" value="' . esc_attr__( "Submit",'wheniwasbad' ) . '" /></div>
	</form></div>
	';
	return $o;
}

/*********** update standard wp tag cloud widget so it looks better ************/

add_filter( 'widget_tag_cloud_args', 'my_widget_tag_cloud_args' );

function my_widget_tag_cloud_args( $args ) {
	$args['number'] = 20; // show less tags
	$args['largest'] = 9.75; // make largest and smallest the same - i don't like the varying font-size look
	$args['smallest'] = 9.75;
	$args['unit'] = 'px';
	return $args;
}

// filter tag cloud output so that it can be styled by CSS
function add_tag_class( $taglinks ) {
    $tags = explode('</a>', $taglinks);
    $regex = "#(.*tag-link[-])(.*)(' title.*)#e";
    $term_slug = "(get_tag($2) ? get_tag($2)->slug : get_category($2)->slug)";

        foreach( $tags as $tag ) {
        	$tagn[] = preg_replace($regex, "('$1$2 label tag-'.$term_slug.'$3')", $tag );
        }

    $taglinks = implode('</a>', $tagn);

    return $taglinks;
}

add_action( 'wp_tag_cloud', 'add_tag_class' );

add_filter( 'wp_tag_cloud','wp_tag_cloud_filter', 10, 2) ;

function wp_tag_cloud_filter( $return, $args )
{
  return '<div id="tag-cloud">' . $return . '</div>';
}

// Enable shortcodes in widgets
add_filter( 'widget_text', 'do_shortcode' );


// Remove height/width attributes on images so they can be responsive
add_filter( 'post_thumbnail_html', 'remove_thumbnail_dimensions', 10 );
add_filter( 'image_send_to_editor', 'remove_thumbnail_dimensions', 10 );

function remove_thumbnail_dimensions( $html ) {
    $html = preg_replace( '/(width|height)=\"\d*\"\s/', "", $html );
    return $html;
}


/******* Misc. Filters *********/

// Add thumbnail class to thumbnail links
function add_class_attachment_link( $html ) {
    $postid = get_the_ID();
    $html = str_replace( '<a','<a class="img-thumbnail"',$html );
    return $html;
}
add_filter( 'wp_get_attachment_link', 'add_class_attachment_link', 10, 1 );

// Filter post title for tumblr-style "link" post format
function sd_link_filter($link, $post) {
     if (has_post_format('link', $post)) {
		 if (get_post_meta($post->ID, 'LinkFormatURL', true)) {
	          $link = get_post_meta($post->ID, 'LinkFormatURL', true);
	     } else {
		 // look for the first URL in the post content
		 	if (preg_match_all('#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#', $post->post_content, $match)) {
				$link = $match[0][0];
			}
		 }
	 }
     return $link;
}
add_filter('post_link', 'sd_link_filter', 10, 2);


// Add Twitter Bootstrap's standard 'active' class name to the active nav link item
add_filter('nav_menu_css_class', 'add_active_class', 10, 2 );

function add_active_class($classes, $item) {
	if( $item->menu_item_parent == 0 && in_array('current-menu-item', $classes) ) {
    $classes[] = "active";
	}
  
  return $classes;
}

// enqueue styles
if( !function_exists("theme_styles") ) {  
    function theme_styles() { 
		if (!is_admin()){
        wp_register_style( 'bootstrap', get_template_directory_uri() . '/library/theme/css/bootstrap-themed.css', array(), '3.0.3.3', 'all' );
        wp_register_style( 'fonts', get_template_directory_uri() . '/library/theme/css/fonts.css', array(), '1.0.0', 'all' );
        wp_register_style('font-awesome', '//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css', array(), '4.0.3', 'all');

		// only enqueue the following styles when needed, but register them here to centralize updates.
		wp_register_style( 'blueimp-gallery-css', get_template_directory_uri() . '/library/Gallery/css/blueimp-gallery.min.css', array(), '2.12.1', 'all' );
        
        wp_enqueue_style( 'bootstrap' );
		wp_enqueue_style( 'fonts');
		wp_enqueue_style( 'font-awesome');
		}
    }
}
add_action( 'wp_enqueue_scripts', 'theme_styles' );	
add_action( 'wp_head', function() {
	include('redux-styles.php');
});

// enqueue javascript
if( !function_exists( "theme_js" ) ) {  
  function theme_js(){
	  if (!is_admin()) {
	  //use CDN for loading Bootstrap
		wp_register_script('bootstrap-js', '//netdna.bootstrapcdn.com/bootstrap/3.0.3/js/bootstrap.min.js', array('jquery'), '3.0.3', true);
		wp_enqueue_script('bootstrap-js');

	    wp_register_script( 'wpbs-scripts', get_template_directory_uri() . '/library/js/scripts.js',array('jquery'),'0.1.1', true );
	    wp_register_script( 'modernizr', get_template_directory_uri() . '/library/js/modernizr.custom.min.js', array(), '2.5.3', true );

		// only enqueue the following scripts when needed, but register them here to centralize updates.
		wp_register_script('jquery-ui','//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js', array('jquery'), '1.10.3', true);
		wp_register_script( 'blueimp-gallery-js', get_template_directory_uri() . '/library/Gallery/js/jquery.blueimp-gallery.min.js', array(jquery), '2.12.1', true );
		wp_register_script( 'blueimp-gallery-init-js', get_template_directory_uri() . '/library/js/gallery_init.js', array('jquery','blueimp-gallery-js'), '2.12.1', true );
		wp_register_script( 'shuffle', get_template_directory_uri() . '/library/js/jquery.shuffle.js', array('jquery'), '1.03', true);

		wp_enqueue_script('wpbs-scripts');
	    wp_enqueue_script('modernizr');
	}
  }
}
add_action( 'wp_enqueue_scripts', 'theme_js' );	
// IE js hacks
add_action( 'wp_head', function() {
	echo '<!--[if lt IE 9]>
	<script src="//css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
	<script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->';
} );
add_action( 'wp_footer', function() {
	echo '<!--[if lt IE 7 ]>
	<script src="//ajax.googleapis.com/ajax/libs/chrome-frame/1.0.3/CFInstall.min.js"></script>
	<script>window.attachEvent(\'onload\',function(){CFInstall.check({mode:\'overlay\'})})</script>
<![endif]-->';
} );

/************* DEBUGGING TOOLS ****************/
if (WPBS_DEBUGMODE) {
	// use runtime-compiled less instead of compiled css
	wp_dequeue_style( 'bootstrap' );
	wp_register_style( 'bootstrap', get_template_directory_uri() . '/library/theme/less/bootstrap-themed.less', array(), '3.0.1', 'all' ); // only for wp-less enabled site, for dev.
	wp_enqueue_style( 'bootstrap' );
	
	include_once('debugging.php');
}

/************* SEARCH FORM LAYOUT *****************/

// Search Form
function bs_wpsearch($form) {
	$form = '<form action="' . home_url( '/' ) . '" method="get" class="form-inline">
		<div class="clearfix input-group input-group-sm">
			<input type="text" name="s" id="search" class="form-control" placeholder="' . __("Search","wheniwasbad") . '" value="' . get_search_query() . '" /><span class="input-group-btn"><button type="submit" class="btn btn-primary" title="' . __("Search","wheniwasbad") . '" ><i class="glyphicon glyphicon-search"></i></button></span>
        </div>
</form>';
	return $form;
}
add_filter( 'get_search_form', 'bs_wpsearch' );


/************* NAVIGATION MENUS **************/
function footer_links() { 
	// display the footer menu
    wp_nav_menu(
    	array(
    		'menu' => 'footer_links', /* menu name */
			'menu_class' => 'nav nav-pills dropup',
    		'theme_location' => 'footer_links', /* where in the theme it's assigned */
    		'container' => 'div',
			'container_class' => 'pull-right', /* container class */
    		'fallback_cb' => 'wp_bootstrap_navwalker::fallback', /* menu fallback */
			'depth' => '2', /* Bootstrap 3.0 doesn't support additional depths */
			'walker' => new wp_bootstrap_navwalker()
    	)
	);
}

/************* Page Display Classes **************/

function page_navi() {
	global $wp_query;
	$bignum = 999999999;
	if ( $wp_query->max_num_pages <= 1 )
		return;

	echo '<nav class="pagination text-center">';
  
	echo paginate_links( array(
		'base'       => str_replace( $bignum, '%#%', esc_url( get_pagenum_link($bignum) ) ),
		'format'     => '',
		'current'     => max( 1, get_query_var('paged') ),
		'total'     => $wp_query->max_num_pages,
		'prev_text'   => '&larr; Previous',
		'next_text'   => 'Next &rarr;',
		'type'      => 'list',
		'end_size'    => 3,
		'mid_size'    => 3
	) );

	echo '</nav>';
}

// remove the p from around imgs (http://css-tricks.com/snippets/wordpress/remove-paragraph-tags-from-around-images/)
function filter_ptags_on_images($content){
   return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
}

add_filter('the_content', 'filter_ptags_on_images');


?>
