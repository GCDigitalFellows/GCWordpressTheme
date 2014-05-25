<?php
if( ! function_exists( 'gc_project_create_post_type' ) ) :
	function gc_project_create_post_type() {
		$labels = array(
			'name' => __( 'Projects' ),
			'singular_name' => __( 'Project' ),
			'add_new' => __( 'Add Project' ),
			'all_items' => __( 'All Projects' ),
			'add_new_item' => __( 'Add New Project' ),
			'edit_item' => __( 'Edit Project' ),
			'new_item' => __( 'New Project' ),
			'view_item' => __( 'View Project' ),
			'search_items' => __( 'Search Projects' ),
			'not_found' => __( 'No projects found' ),
			'not_found_in_trash' => __( 'Trash does not contain any projects' ),
			'parent_item_colon' => __( 'Parent Project:' ),
			'menu_name' => __( 'Projects' )
		);
		$args = array(
			'label' => __( 'projects_post_type' ),
			'description'			=> __( 'Custom Post Type for Projects' ),
			'labels'				=> $labels,
			'public'				=> true,
			'show_ui'				=> true,
			'show_in_menu'			=> true,
			'show_in_nav_menus'		=> true,
			'show_in_admin_bar'		=> true,
			'menu_position'			=> 20,
			'menu_icon'				=> 'dashicons-hammer',
			'can_export'			=> true,
			'has_archive'			=> true,
			'exclude_from_search'	=> false,
			'publicly_queryable'	=> true,
			'capability_type'		=> 'page',
			'query_var' 			=> true,
			'rewrite' 				=> array(
				'slug' 			=> 'projects',
				'with_front' 	=> false),
			'hierarchical' 			=> false,
			'supports'				=> array(
				'title',
				'editor',
				'excerpt',
				'thumbnail',
				//'author',
				//'trackbacks',
				//'custom-fields',
				//'comments',
				'revisions',
				//'page-attributes', // (menu order, hierarchical must be true to show Parent option)
				//'post-formats',
			),
			'taxonomies' 			=> array( 'project_categories', 'project_tags' ),
			//'register_meta_box_cb' => 'gc_project_add_post_type_metabox'
		);
		register_post_type( 'gc_project', $args );
		//flush_rewrite_rules();

		register_taxonomy(
			'project_categories', // register custom taxonomy - gc_project category
			'gc_project',
			array(
				'hierarchical' => true,
				'label' => __( 'Project Categories' ),
				'query_var' => true,
				'rewrite' => array(
					'slug' => 'project_categories',
					'with_front' => false
				)
			)
		);
		register_taxonomy( 'project_tag', // register custom taxonomy - gc_project tag
			'gc_project',
			array( 'hierarchical' => false,
				'label' => __( 'Project Tags' ),
				'query_var'	=> true,
				'rewrite'	=> array(
					'slug'	=> 'project_tags',
					'with_front'	=> false
				)
			)
		);

	}
	add_action( 'init', 'gc_project_create_post_type' );

	// add some descriptive text below the title
	add_action( 'edit_form_after_title', 'gc_project_edit_form_after_title' );
	function gc_project_edit_form_after_title() {
		$scr = get_current_screen();
  		if ( $scr->post_type !== 'gc_project' )
    		return;
	    echo '<h2>Instructions:</h2><p>Enter a full description in the main content. The excerpt should be a short description for overview pages. Be sure to include a featured image and an external URL.</p>';
	}

endif; // end of function_exists()

?>