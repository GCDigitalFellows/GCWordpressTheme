<?php
	
function add_css_admin_bar_link() {
	global $wp_admin_bar;
	if ( !is_super_admin() || !is_admin_bar_showing() )
		return;
	$wp_admin_bar->add_menu( array(
		'href' => '#',
		'parent' => 'false',
		'id' => 'css_link',
		'title' => __( 'Toggle GC CSS'),
		'meta' => array(
			'onclick' => 'togglejscssfile("cuny-all.css","css","'.get_template_directory_uri().'/orig/css/cuny-all.css")'
		),
	) );
}
add_action('admin_bar_menu', 'add_css_admin_bar_link',999);
	
?>