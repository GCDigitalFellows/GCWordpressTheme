<?php

// shortcodes

// add a link to shortcodes to the page editor
	add_action( 'edit_form_after_editor', 'shortcode_edit_form_after_editor' );
	function shortcode_edit_form_after_editor() {
	    echo '<strong>Note:</strong> For a list of shortcodes built into the theme, visit the "Shortcodes" tab in the <a href="/wp-admin/themes.php?page=gctheme_options">GC Theme Options</a>.';
	}

// Gallery shortcode

// Override the standard gallery shortcode
// originally from https://github.com/retlehs/roots
// modified to support blueimp gallery http://blueimp.github.io/Gallery/
function bootstrap_gallery($attr) {
	wp_enqueue_style('blueimp-gallery-css');
	wp_enqueue_script('blueimp-gallery-js');
	wp_enqueue_script('blueimp-gallery-init-js');
	wp_enqueue_script('shuffle');

	$post = get_post();

	static $instance = 0;

	$instance++;

	if (!empty($attr['ids'])) {
		if (empty($attr['orderby'])) {
			$attr['orderby'] = 'post__in';
		}
		$attr['include'] = $attr['ids'];
	}

	$output = apply_filters('post_gallery', '', $attr);

	if ($output != '') {
		return $output;
	}

	if (isset($attr['orderby'])) {
		$attr['orderby'] = sanitize_sql_orderby($attr['orderby']);
		if (!$attr['orderby']) {
			unset($attr['orderby']);
		}
	}

	$attr = shortcode_atts(array(
		'order'      => 'ASC',
		'orderby'    => 'menu_order ID',
		'id'         => $post->ID,
		'itemtag'    => '',
		'icontag'    => '',
		'captiontag' => '',
		'columns'    => 3,
		'size'       => 'thumbnail',
		'include'    => '',
		'exclude'    => '',
		'showcontrols'	=> 'true',
		'showtooltips'	 => 'true',
		'showcaptions'	=> 'true'
	), $attr);
	extract($attr);
	$attr = json_encode($attr);

	$id = intval($id);

	if ($order === 'RAND') {
		$orderby = 'none';
	}

	if (!empty($include)) {
		$_attachments = get_posts(array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby));
		$attachments = array();
		foreach ($_attachments as $key => $val) {
		  $attachments[$val->ID] = $_attachments[$key];
		}
	} elseif (!empty($exclude)) {
		$attachments = get_children(array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby));
	} else {
		$attachments = get_children(array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby));
	}

	if (empty($attachments)) {
		return '';
	}

	if (is_feed()) {
		$output = "\n";
		foreach ($attachments as $att_id => $attachment) {
		  $output .= wp_get_attachment_link($att_id, $size, true) . "\n";
		}
		return $output;
	}

	$col_width = 100/$columns;
	$i=1;

	$gallery_id = 'blueimp_gallery_'.rand();
	$links_id = 'links-'.$gallery_id;

	$mosaic = '<div id="'.$links_id.'" class="gridalicious clearfix">';
	foreach ($attachments as $id => $attachment) {
		//$img_lg = wp_get_attachment_image_src($id,'large',false);
		$img_sm = wp_get_attachment_image_src($id,'full',false);

		$img = '<img src="' . $img_sm[0] . '" alt="' . $attachment->post_title . '" />';
		$mosaic .= '<div class="gallery-brick" style="width: '.$col_width.'%;" data-groups="[\'image\']">';
		$mosaic .= '<a href="' . $img_sm[0] . '" data-gallery="#' . $gallery_id . '">';

		$mosaic .= '<span style="background-image: url(\'http://upload.wikimedia.org/wikipedia/commons/c/ce/Transparent.gif\'");z-index: 1;position:absolute;width:100%;height:100%;top:0;left:0;"></span></a>';

		$mosaic .= "\n" . $img . "\n</a>\n";
		$the_excerpt = wptexturize($attachment->post_excerpt);
		if ($the_excerpt) {
			$mosaic .= '<div class="gallery-caption"><h1>'. $attachment->post_title .'</h1><p>' . $the_excerpt . "</p></div>\n";
			$mosaic .= '<p class="caption hidden">' . $the_excerpt . "</p>\n";
		}
		$mosaic .= "</div>";

		//$link = isset($attr['link']) && 'file' == $attr['link'] ? wp_get_attachment_url($id) : get_attachment_link($id);
	}

	$mosaic .= "</div>\n";
	$mosaic .= <<<EOD
<script type='text/javascript'>
	jQuery( document ).ready( function() {
		blueimpGalleryInit('$gallery_id','$attr');
		jQuery('#$links_id.galleryitem').each(function(){
			jQuery(this).width(jQuery('#$links_id').width()/$columns);
		});

		var pinterest_list = jQuery('#$links_id'),
			sizer = (jQuery('#$links_id').width()/$columns)
		pinterest_list.shuffle({
			itemSelector: '.gallery-brick',
			sizer: sizer,
			gutterWidth: 0
		});
	});

    jQuery('.gallery-brick').hover(
        function(){
            jQuery(this).find('.gallery-caption').slideDown(250); //.fadeIn(250)
        },
        function(){
            jQuery(this).find('.gallery-caption').slideUp(250); //.fadeOut(205)
        }
    );
</script>
EOD;

	return $mosaic;
}
remove_shortcode('gallery','gallery_shortcode');
add_shortcode('gallery', 'bootstrap_gallery');


// Buttons
function buttons( $atts, $content = null ) {
	extract( shortcode_atts( array(
	'type' => 'default', /* primary, default, info, success, danger, warning, inverse, orange, yellow, green-light, green, green-dark, blue-light, blue, blue-dark, pink, gray */
	'size' => 'default', /* xs, sm, md = default, lg */
	'url'  => '',
	'target' => '_self', /* _blank, _parent, _top */
	'block' => false,
	'active' => false,
	'text' => '',
	), $atts ) );

	if($type == "default"){
		$type = "";
	}
	else{
		$type = " btn-" . $type;
	}

	if($size == "default" || $size == "md"){
		$size = "";
	}
	else{
		$size = " btn-" . $size;
	}

	if($block == false) {
		$block = "";
	}
	else{
		$block = " btn-block";
	}

	if($active == false) {
		$active = "";
	}
	else{
		$active = " active";
	}

	$output = '<a href="' . $url . '" class="btn'. $type . $size . $block . $active .'" target="' . $target . '">';
	$output .= $text;
	$output .= '</a>';

	return $output;
}

add_shortcode('button', 'buttons');

// Alert
function alert_messages( $atts, $content = null ) {
	extract( shortcode_atts( array(
	'type' => 'info', /* info, success, warning, error */
	'close' => 'false', /* display close link */
	), $atts ) );

	$output = '';
	$dismissable = '';

	if($close == 'true') {

		$dismissable = 'alert-dismissable';
		$output = '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
	}

	// add alert link class
	// currently will delete existing classes. need to fix the regex...
	$pattern1 = '/(<a [^>]*) class=([\'"])[^\2]+\2([^>]*>)/is';
	$replace1 = '\1\3';
	$pattern2 = '/(<a [^>]*href=([\'"])[^\2]+\2)([^>]*)/is';
	$replace2 = '\1 class="alert-link"\3';
	$content = preg_replace($pattern1, $replace1, $content);
	$content = preg_replace($pattern2, $replace2, $content);

	$output = '<div class="alert alert-'. $type . ' ' . $dismissable . '">' . $output . $content . '</div>';

	return $output;
}

add_shortcode('alert', 'alert_messages');

// Block Messages
function blockquotes( $atts, $content = null ) {
	extract( shortcode_atts( array(
	'float' => '', /* left, right */
	'cite' => '', /* text for cite */
	), $atts ) );

	$output = '<blockquote';
	if($float == 'left') {
		$output .= ' class="pull-left"';
	}
	elseif($float == 'right'){
		$output .= ' class="pull-right"';
	}
	$output .= '><p>' . $content . '</p>';

	if($cite){
		$output .= '<small>' . $cite . '</small>';
	}

	$output .= '</blockquote>';

	return $output;
}

add_shortcode('blockquote', 'blockquotes');


// list project custom post types
function list_projects_shortcode( $atts ) {

	// Attributes
	$args = shortcode_atts(
		array(
			'project_categories' => '',
			'project_tags'		=> '',
			'posts_per_page'	=> 10,
			'offset'	=> 0,
			'orderby'	=> 'post_date',
			'order'		=> 'DESC',
			'include'	=> '',
			'exclude'	=> '',
			'meta_key'	=> '',
			'meta_value'	=> '',
			'post_mime_type'	=> '',
			'post_parent'		=> '',
			'post_status'		=> 'publish',
			'suppress_filters'	=> true
		), $atts );

	// Code
	$args = array_merge($args, array('post_type' => 'gc_project'));

	$proj_query = new WP_Query( $args );

	$output = '';

	while ( $proj_query->have_posts() ) {

		$proj_query->the_post();

		$output .= '<div class="col-xs-6 col-sm-4 col-lg-3"><div class="gc_person_image">';

		if ( has_post_thumbnail() ) {

			$image_url = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'thumbnail');
			$image_url = $image_url[0];

		} else {

			$image_url = get_template_directory_uri() . '/library/images/icon-user-default.png';

		}

		$output .= '<img src="' . $image_url .'" alt="' . the_title_attribute('echo=0') . '" class="gc_person_thumb" />';

		$output .= '<div class="gc_person_info_wrapper"><div class="gc_person_info"><div class="gc_person_info_inner">';

		$output .= '<p>' . get_the_excerpt() . '</p>';

		$output .= '<a href="' . get_the_permalink() . '" title="' . the_title_attribute('echo=0') . '" class="btn btn-default btn-xs">Details</a>';

		$output .= '</div></div></div></div><div class="caption text-center">';

		$output .= '<h3>' . get_the_title() . '</h3></div></div>';

	} // end of the loop.

return $output;
}
add_shortcode( 'list_projects', 'list_projects_shortcode' );


// list people custom post types
function list_people_shortcode( $atts ) {

	// Attributes
	$args = shortcode_atts(
		array(
			'people_categories' => '',
			'posts_per_page'	=> 10,
			'offset'	=> 0,
			'orderby'	=> 'post_date',
			'order'		=> 'DESC',
			'include'	=> '',
			'exclude'	=> '',
			'meta_key'	=> '',
			'meta_value'	=> '',
			'post_mime_type'	=> '',
			'post_parent'		=> '',
			'post_status'		=> 'publish',
			'suppress_filters'	=> true
		), $atts );

	// Code
	$args = array_merge($args, array('post_type' => 'gc_person'));

	$proj_query = new WP_Query( $args );

	$output = '';

	while ( $proj_query->have_posts() ) {

		$proj_query->the_post();

		$output .= '<div class="col-xs-6 col-sm-4 col-lg-3"><div class="gc_person_image">';

		if ( has_post_thumbnail() ) {

			$image_url = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'thumbnail');
			$image_url = $image_url[0];

		} else {

			$image_url = get_template_directory_uri() . '/library/images/icon-user-default.png';

		}

		$output .= '<img src="' . $image_url .'" alt="' . the_title_attribute('echo=0') . '" class="gc_person_thumb" />';

		$output .= '<div class="gc_person_info_wrapper"><div class="gc_person_info"><div class="gc_person_info_inner">';

		$output .= '<p>' . get_the_excerpt() . '</p>';

		$output .= '<a href="' . get_the_permalink() . '" title="' . the_title_attribute('echo=0') . '" class="btn btn-default btn-xs">Details</a>';

		$output .= '</div></div></div></div><div class="caption text-center">';

		$output .= '<h3>' . get_the_title() . '</h3></div></div>';

	} // end of the loop.

return $output;
}
add_shortcode( 'list_people', 'list_people_shortcode' );

?>
