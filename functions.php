<?php
/*
Author: Keith Miyake
URL: htp://keithmiyake.info/wheniwasbad/
Version: 2.0
Modified: 20130905
*/

/************* DEBUGGING TOOLS ****************/
// const WPBS_DEBUGGING turns on or off the theme's development/debugging tools
define("WPBS_DEBUGMODE",true);

if (WPBS_DEBUGMODE) {
	include_once('library/debugging.php');
}

// Redux Options
//add_action( 'init', 'initialize_redux_framework', 1000 );
//function initialize_redux_framework() {
	if ( ! class_exists( 'ReduxFramework' ) ){
	    require_once(dirname(__FILE__) . '/library/ReduxFramework/ReduxCore/framework.php');
	}
	require_once('redux-options.php');
//}

// Shortcodes
require_once('library/shortcodes.php');

// Custom Metaboxes and Fields (https://github.com/jaredatch/Custom-Metaboxes-and-Fields-for-WordPress)
add_action( 'init', 'initialize_cmb_meta_boxes', 9999 );
function initialize_cmb_meta_boxes() {
	if ( !class_exists( 'cmb_Meta_Box' ) ) {
		require_once( 'library/cmb/init.php' );
	}
}

// custom function for displaying page not found info
add_action( 'init', 'initialize_not_found', 9999 );
function initialize_not_found() {
	if ( !function_exists( 'not_found' ) ) {
		require_once('notfound.php');
	}
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
	set_post_thumbnail_size(125, 125, true);   // default thumb size
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
			'main_nav' => 'The Main Menu',   // main nav in header
			'footer_links' => 'Footer Links' // secondary nav in footer
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
function login_css() { wp_enqueue_style( 'login_css', get_template_directory_uri() . '/library/css/login.css', false ); }
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
    	'before_title' => '<h4 class="widgettitle">',
    	'after_title' => '</h4>',
    ));
    
    register_sidebar(array(
    	'id' => 'sidebar2',
    	'name' => 'Homepage Sidebar',
    	'description' => 'Used only on the homepage page template.',
    	'before_widget' => '<div id="%1$s" class="widget %2$s">',
    	'after_widget' => '</div>',
    	'before_title' => '<h4 class="widgettitle">',
    	'after_title' => '</h4>',
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
	return ' [&hellip;]</p><p><a class="read-more btn pull-right" href="'. get_permalink( get_the_ID() ) . '">Read more <i class="icon-chevron-sign-right"></i></a>';
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
					<?php edit_comment_link(__('Edit','wheniwasbad'),'<span class="edit-comment btn btn-sm btn-info"><i class="icon-white icon-pencil"></i>','</span>') ?>
                    
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
        <li id="comment-<?php comment_ID(); ?>"><i class="icon icon-share-alt"></i>&nbsp;<?php comment_author_link(); ?>
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

/******** Custom Metaboxes ********/
function cmb_add_metaboxes( array $meta_boxes ) {
	$prefix = '_cmb_';
	
	$meta_boxes[] = array(
		'id'         => 'jumbotron_meta_box',
		'title'      => 'Jumbotron',
		'pages'      => array( 'page', ), // Post type
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true, // Show field names on the left
		'show_on' => array( 'key' => 'page-template', 'value' => array('page-homepage.php','page-jumbotron.php') ),
		'fields' => array(
		    array(  
		        'name'=> 'Jumbotron Contents',  
		        'desc'  => 'Displayed in place of a page title. Only used on homepage and jumbotron templates. HTML can be used.',  
		        'id'    => 'jumbotron_contents',  
		        'type'  => 'wysiwyg',
				'options' => array(
					    'wpautop' => true, // use wpautop?
					    'media_buttons' => true, // show insert/upload button(s)
					    //'textarea_name' => $editor_id, // set the textarea name to something different, square brackets [] can be used here
					    'textarea_rows' => get_option('default_post_edit_rows', 10), // rows="..."
					    'tabindex' => '',
					    'editor_css' => '', // intended for extra styles for both visual and HTML editors buttons, needs to include the <style> tags, can use "scoped".
					    'editor_class' => '', // add extra class(es) to the editor textarea
					    'teeny' => false, // output the minimal editor config used in Press This
					    'dfw' => false, // replace the default fullscreen with DFW (needs specific css)
					    'tinymce' => true, // load TinyMCE, can be used to pass settings directly to TinyMCE using an array()
					    'quicktags' => true // load Quicktags, can be used to pass settings directly to Quicktags using an array()	
					), 
		    )
		)
	);
	
	foreach($GLOBALS['wp_registered_sidebars'] as $key => $val) {
		$sidebar_list[] = array(
			'value' => $key,
			'name' => $val['name']
		);
	}
	$meta_boxes[] = array(
		'id'         => 'sidebar_options_meta_box',
		'title'      => 'Sidebar',
		'pages'      => array( 'page', ), // Post type
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true, // Show field names on the left
		//'show_on' => array( 'key' => 'page-template', 'value' => array('page-homepage.php','page-jumbotron.php','page.php') ),
		'fields' => array(
		    array(  
		        'name'=> 'Sidebar Position',  
		        'desc'  => 'Select the visibility and position of sidebars for this page.',  
		        'id'    => 'sidebar_position',
		        'type'  => 'select',
				'options' => array(
					array('value' => 'none', 'name' => 'No Sidebar'),
					array('value' => 'left', 'name' => 'Left Sidebar'), 
					array('value' => 'right', 'name' => 'Right Sidebar')
				)
		    ),
			array(  
		        'name'=> 'Widget Group',  
		        'desc'  => 'Select a widget group to display in the sidebar on this page.',  
		        'id'    => 'sidebar_widgets',  
		        'type'  => 'select',
				'options' => $sidebar_list
		    )
		)
	);

	$effect_speed = array(
		array('name' => 'Slow', 'value' => 'slow'),
		array('name' => 'Medium', 'value' => 'medium'),
		array('name' => 'Fast', 'value' => 'fast'),
	);

	$meta_boxes[] = array(
		'id'         => 'toc_meta_box',
		'title'      => 'Table of Contents Options',
		'pages'      => array( 'page', ), // Post type
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true, // Show field names on the left
		'show_on' => array( 'key' => 'page-template', 'value' => array('page-toc.php') ),
		'fields' => array(
		    array(  
		        'name'=> 'DOM Selector',
		        'desc'  => 'Comma separated list of header elements to include in the TOC, in hierarchical order. Default is "h1,h2,h3".',  
		        'id'    => 'toc_selector',  
		        'type'  => 'text_small'
			),
			array(  
		        'name'=> 'TOC Context',
		        'desc'  => 'The container element that holds all of the elements used to generate the TOC. Can be any valid jQuery selector. Default is the container around the main content.',  
		        'id'    => 'toc_context',  
		        'type'  => 'text_small'
			),
			array(  
		        'name'=> 'Show and Hide Child Menu Levels',
		        'desc'  => 'When checked, nested child items are hidden until the parent level is displayed; when unchecked, all items are displayed.',  
		        'id'    => 'toc_showAndHide',  
		        'type'  => 'checkbox'
			),
			array(  
		        'name'=> 'Show Effect',
		        'desc'  => 'Animation effect used to display nested TOC items',  
		        'id'    => 'toc_showEffect',
		        'type'  => 'select',
		        'options' => array(
		        	array('name' => 'None', 'value' => 'none'),
		        	array('name' => 'Fade In', 'value' => 'fadeIn'),
		        	array('name' => 'Show', 'value' => 'show'),
		        	array('name' => 'Slide Down', 'value' => 'slideDown')
		        )
			),
			array(  
		        'name'=> 'Show Effect Speed',
		        'desc'  => 'Animation speed used to display nested TOC items',  
		        'id'    => 'toc_showEffectSpeed',
		        'type'  => 'select',
		        'options' => $effect_speed
			),
			array(  
		        'name'=> 'Hide Effect',
		        'desc'  => 'Animation effect used to hide nested TOC items',  
		        'id'    => 'toc_hideEffect',
		        'type'  => 'select',
		        'options' => array(
		        	array('name' => 'None', 'value' => 'none'),
		        	array('name' => 'Fade Out', 'value' => 'fadeOut'),
		        	array('name' => 'Hide', 'value' => 'hide'),
		        	array('name' => 'Slide Up', 'value' => 'slideUp')
		        )
			),
			array(  
		        'name'=> 'Hide Effect Speed',
		        'desc'  => 'Animation speed used to hide nested TOC items',  
		        'id'    => 'toc_hideEffectSpeed',
		        'type'  => 'select',
		        'options' => $effect_speed
			),
			array(  
		        'name'=> 'Smooth Scroll',
		        'desc'  => 'Animates the page scroll when specific table of content items are clicked and the page moves up or down.',  
		        'id'    => 'toc_smoothScroll',  
		        'type'  => 'checkbox'
			),
			array(  
		        'name'=> 'Smooth Scroll Speed',
		        'desc'  => 'Animation speed for the smooth scrolling effect.',  
		        'id'    => 'toc_smoothScrollSpeed',  
		        'type'  => 'select',
		        'options' => $effect_speed
			),
			array(  
		        'name'=> 'Item Scroll Offset',
		        'desc'  => 'Offset (in pixels) from the top of the page and the selected TOC item when jumping to that item.',  
		        'id'    => 'toc_scrollTo',  
		        'type'  => 'text_small'
			),
			array(  
		        'name'=> 'Highlight on Scroll',
		        'desc'  => 'When checked, current TOC item will be highlighted (different background color) while scrolling.',  
		        'id'    => 'toc_highlightOnScroll',  
		        'type'  => 'checkbox'
			),
			array(  
		        'name'=> 'Highlight Offset',
		        'desc'  => 'Offset (in pixels) from the top of the page and the selected TOC item when jumping to that item.',  
		        'id'    => 'toc_scrollTo',  
		        'type'  => 'text_small'
			),
			array(  
		        'name'=> 'Ignore Selector',
		        'desc'  => 'jQuery selector for items that should not appear in the TOC.',  
		        'id'    => 'toc_ignoreSelector',  
		        'type'  => 'text_small'
			)			
		)
	);
	
	return $meta_boxes;
}
add_filter( 'cmb_meta_boxes', 'cmb_add_metaboxes' );


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
        //wp_register_style( 'bootstrap-css', get_template_directory_uri() . '/library/theme/css/bootstrap-themed.css', array(), '3.0.0', 'all' );

		// only enqueue the following styles when needed, but register them here to centralize updates.
		wp_register_style( 'blueimp-gallery-css', get_template_directory_uri() . '/library/Gallery/css/blueimp-gallery.min.css', array(), '2.9.0', 'all' );
		wp_register_style('tocify-css',get_template_directory_uri() . '/library/jquery.tocify.js/src/stylesheets/jquery.tocify.css', array(), '1.9.0', 'screen');
        
        //wp_enqueue_style( 'bootstrap-css' );
        wp_enqueue_style( 'theme-base');
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
		wp_register_script('bootstrap-js', '//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js', array('jquery'), '3.0.0', true);
		wp_enqueue_script('bootstrap-js');

	    wp_register_script( 'wpbs-scripts', get_template_directory_uri() . '/library/js/scripts.js',array('jquery'),'1.2', true );
	    wp_register_script( 'modernizr', get_template_directory_uri() . '/library/js/modernizr.custom.min.js', array(), '2.5.3', true );

		// only enqueue the following scripts when needed, but register them here to centralize updates.
		wp_register_script( 'bs-tooltips',get_template_directory_uri() . '/library/js/bs-tooltips.js',array('jquery','bootstrap'),'3.0', true );
		wp_register_script('blueimp-gallery-js', get_template_directory_uri() . '/library/Gallery/js/jquery.blueimp-gallery.min.js', array(jquery), '1.2.0', true);
		wp_register_script('blueimp-gallery-init-js', get_template_directory_uri() . '/library/js/gallery_init.js', array('jquery','blueimp-gallery-js'), false, true);
		wp_register_script('jquery-ui','//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js', array('jquery'), '1.10.3', true);
		wp_register_script('tocify-js',get_template_directory_uri() . '/library/jquery.tocify.js/src/javascripts/jquery.tocify.min.js', array('jquery','jquery-ui'), '1.9.0', true);
	
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

/************* SEARCH FORM LAYOUT *****************/

// Search Form
function bs_wpsearch($form) {
	$form = '<form action="' . home_url( '/' ) . '" method="get" class="form-inline">
		<div class="clearfix input-group input-group-sm">
			<input type="text" name="s" id="search" class="form-control" placeholder="' . __("Search","wheniwasbad") . '" value="' . get_search_query() . '" /><span class="input-group-btn"><button type="submit" class="btn btn-primary" title="' . __("Search","wheniwasbad") . '" ><i class="icon-search"></i></button></span>
        </div>
</form>';
	return $form;
}
add_filter( 'get_search_form', 'bs_wpsearch' );


/************* NAVIGATION MENUS **************/
function main_nav() {
	// display the primary menu
    wp_nav_menu( 
    	array( 
    		'menu' => 'main_nav', /* menu name */
    		'menu_class' => 'nav navbar-nav',
    		'theme_location' => 'main_nav', /* where in the theme it's assigned */
    		'container' => 'false', /* container class */
			/*'container-class' => '', */
    		'fallback_cb' => 'nav_menu_fallback', /* menu fallback */
    		'depth' => '2', /* Bootstrap 3.0 doesn't support additional depths */
    		'walker' => new wp_bootstrap_navwalker()
    	)
    );
}

function footer_links() { 
	// display the footer menu
    wp_nav_menu(
    	array(
    		'menu' => 'footer_links', /* menu name */
			'menu_class' => 'nav nav-pills dropup',
    		'theme_location' => 'footer_links', /* where in the theme it's assigned */
    		'container' => 'div',
			'container_class' => 'pull-right', /* container class */
    		'fallback_cb' => 'nav_menu_fallback', /* menu fallback */
			'depth' => '2', /* Bootstrap 3.0 doesn't support additional depths */
			'walker' => new Bootstrap_Walker()
    	)
	);
}


// this is the fallback for header menu
function nav_menu_fallback() { 
	// Figure out how to make this output bootstrap-friendly html
	wp_page_menu(
		array(
			'show_home' => 'Home',
			'menu_class' => 'nav',
			'sort_column' => 'menu_order, post_title',
			'depth' => 0
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