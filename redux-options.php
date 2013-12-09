<?php

/**
	ReduxFramework Sample Config File
	For full documentation, please visit http://reduxframework.com/docs/
**/


/*
 *
 * Custom function for filtering the sections array. Good for child themes to override or add to the sections.
 * Simply include this function in the child themes functions.php file.
 *
 * NOTE: the defined constansts for URLs, and directories will NOT be available at this point in a child theme,
 * so you must use get_template_directory_uri() if you want to use any of the built in icons
 *
 */
/*
function add_another_section($sections){
    //$sections = array();
    $sections[] = array(
        'title' => __('A Section added by hook', 'redux-framework'),
        'desc' => __('<p class="description">This is a section created by adding a filter to the sections array. Can be used by child themes to add/remove sections from the options.</p>', 'redux-framework'),
		'icon' => 'paper-clip',
		'icon_class' => 'icon-large',
        // Leave this as a blank section, no options just some intro text set above.
        'fields' => array()
    );

    return $sections;
}
add_filter('redux-opts-sections-redux-sample', 'add_another_section');
*/

/*
 * 
 * Custom function for filtering the args array given by a theme, good for child themes to override or add to the args array.
 *
 */
/*
function change_framework_args($args){
    //$args['dev_mode'] = false;
    
    return $args;
}
//add_filter('redux-opts-args-redux-sample-file', 'change_framework_args');
*/

/*
 *
 * Most of your editing will be done in this section.
 *
 * Here you can override default values, uncomment args and change their values.
 * No $args are required, but they can be over ridden if needed.
 *
 */
function setup_framework_options(){
    
    ReduxFramework::$_url = get_template_directory_uri().'/library/ReduxFramework/ReduxCore/';

    $args = array();


    // For use with a tab below
		$tabs = array();

		ob_start();

		$ct = wp_get_theme();
        $theme_data = $ct;
        $item_name = $theme_data->get('Name'); 
		$tags = $ct->Tags;
		$screenshot = $ct->get_screenshot();
		$class = $screenshot ? 'has-screenshot' : '';

		$customize_title = sprintf( __( 'Customize &#8220;%s&#8221;' ), $ct->display('Name') );

		?>
		<div id="current-theme" class="<?php echo esc_attr( $class ); ?>">
			<?php if ( $screenshot ) : ?>
				<?php if ( current_user_can( 'edit_theme_options' ) ) : ?>
				<a href="<?php echo wp_customize_url(); ?>" class="load-customize hide-if-no-customize" title="<?php echo esc_attr( $customize_title ); ?>">
					<img src="<?php echo esc_url( $screenshot ); ?>" alt="<?php esc_attr_e( 'Current theme preview' ); ?>" />
				</a>
				<?php endif; ?>
				<img class="hide-if-customize" src="<?php echo esc_url( $screenshot ); ?>" alt="<?php esc_attr_e( 'Current theme preview' ); ?>" />
			<?php endif; ?>

			<h4>
				<?php echo $ct->display('Name'); ?>
			</h4>

			<div>
				<ul class="theme-info">
					<li><?php printf( __('By %s'), $ct->display('Author') ); ?></li>
					<li><?php printf( __('Version %s'), $ct->display('Version') ); ?></li>
					<li><?php echo '<strong>'.__('Tags', 'redux-framework').':</strong> '; ?><?php printf( $ct->display('Tags') ); ?></li>
				</ul>
				<p class="theme-description"><?php echo $ct->display('Description'); ?></p>
				<?php if ( $ct->parent() ) {
					printf( ' <p class="howto">' . __( 'This <a href="%1$s">child theme</a> requires its parent theme, %2$s.' ) . '</p>',
						__( 'http://codex.wordpress.org/Child_Themes' ),
						$ct->parent()->display( 'Name' ) );
				} ?>
				
			</div>

		</div>

		<?php
		$item_info = ob_get_contents();
		    
		ob_end_clean();


	if( file_exists( dirname(__FILE__).'/info-html.html' )) {
		global $wp_filesystem;
		if (empty($wp_filesystem)) {
			require_once(ABSPATH .'/wp-admin/includes/file.php');
			WP_Filesystem();
		}  		
		$sampleHTML = $wp_filesystem->get_contents(dirname(__FILE__).'/info-html.html');
	}


    // Setting dev mode to true allows you to view the class settings/info in the panel.
    // Default: true
    $args['dev_mode'] = false;

	// Set the icon for the dev mode tab.
	// If $args['icon_type'] = 'image', this should be the path to the icon.
	// If $args['icon_type'] = 'iconfont', this should be the icon name.
	// Default: info-sign
	$args['dev_mode_icon'] = 'info-sign';

	// Set the class for the dev mode tab icon.
	// This is ignored unless $args['icon_type'] = 'iconfont'
	// Default: null
    $args['dev_mode_icon_class'] = 'icon-large';

    // Set a custom option name. Don't forget to replace spaces with underscores!
    $args['opt_name'] = 'wheniwasbad_options';

    // Setting system info to true allows you to view info useful for debugging.
    // Default: true
    // $args['system_info'] = false;

    
	// Set the icon for the system info tab.
	// If $args['icon_type'] = 'image', this should be the path to the icon.
	// If $args['icon_type'] = 'iconfont', this should be the icon name.
	// Default: info-sign
	//$args['system_info_icon'] = 'info-sign';

	// Set the class for the system info tab icon.
	// This is ignored unless $args['icon_type'] = 'iconfont'
	// Default: null
	$args['system_info_icon_class'] = 'icon-large';

	$theme = wp_get_theme();

	$args['display_name'] = $theme->get('Name');
	//$args['database'] = "theme_mods_expanded";
	$args['display_version'] = $theme->get('Version');

    // If you want to use Google Webfonts, you MUST define the api key.
    $args['google_api_key'] = 'AIzaSyAX_2L_UzCDPEnAHTG7zhESRVpMPS4ssII';

    // Define the starting tab for the option panel.
    // Default: '0';
    //$args['last_tab'] = '0';

    // Define the option panel stylesheet. Options are 'standard', 'custom', and 'none'
    // If only minor tweaks are needed, set to 'custom' and override the necessary styles through the included custom.css stylesheet.
    // If replacing the stylesheet, set to 'none' and don't forget to enqueue another stylesheet!
    // Default: 'standard'
    //$args['admin_stylesheet'] = 'standard';

    // Setup custom links in the footer for share icons
	/*
    $args['share_icons']['twitter'] = array(
        'link' => 'http://twitter.com/ghost1227',
        'title' => 'Follow me on Twitter', 
        'img' => REDUX_URL . 'assets/img/social/Twitter.png'
    );
    $args['share_icons']['linked_in'] = array(
        'link' => 'http://www.linkedin.com/profile/view?id=52559281',
        'title' => 'Find me on LinkedIn', 
        'img' => REDUX_URL . 'assets/img/social/LinkedIn.png'
    );
	*/

    // Enable the import/export feature.
    // Default: true
    $args['show_import_export'] = true;

	// Set the icon for the import/export tab.
	// If $args['icon_type'] = 'image', this should be the path to the icon.
	// If $args['icon_type'] = 'iconfont', this should be the icon name.
	// Default: refresh
	$args['import_icon'] = 'refresh';

	// Set the class for the import/export tab icon.
	// This is ignored unless $args['icon_type'] = 'iconfont'
	// Default: null
	$args['import_icon_class'] = 'icon-large';

    // Set a custom menu icon.
    //$args['menu_icon'] = '';

    // Set a custom title for the options page.
    // Default: Options
    $args['menu_title'] = __('Theme Options', 'redux-framework');

    // Set a custom page title for the options page.
    // Default: Options
    $args['page_title'] = __('Theme Options', 'redux-framework');

    // Set a custom page slug for options page (wp-admin/themes.php?page=***).
    // Default: redux_options
    $args['page_slug'] = 'theme_options';

    $args['default_show'] = true;
    $args['default_mark'] = '*';

    // Set a custom page capability.
    // Default: manage_options
    //$args['page_cap'] = 'manage_options';

    // Set the menu type. Set to "menu" for a top level menu, or "submenu" to add below an existing item.
    // Default: menu
    //$args['page_type'] = 'submenu';

    // Set the parent menu.
    // Default: themes.php
    // A list of available parent menus is available at http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
    //$args['page_parent'] = 'options_general.php';

    // Set a custom page location. This allows you to place your menu where you want in the menu order.
    // Must be unique or it will override other items!
    // Default: null
    //$args['page_position'] = null;

    // Set a custom page icon class (used to override the page icon next to heading)
    //$args['page_icon'] = 'icon-themes';

	// Set the icon type. Set to "iconfont" for Font Awesome, or "image" for traditional.
	// Redux no longer ships with standard icons!
	// Default: iconfont
	//$args['icon_type'] = 'image';

    // Disable the panel sections showing as submenu items.
    // Default: true
    //$args['allow_sub_menu'] = false;
        
    // Set ANY custom page help tabs, displayed using the new help tab API. Tabs are shown in order of definition.
	/*
    $args['help_tabs'][] = array(
        'id' => 'redux-opts-1',
        'title' => __('Theme Information 1', 'redux-framework'),
        'content' => __('<p>This is the tab content, HTML is allowed.</p>', 'redux-framework')
    );
    $args['help_tabs'][] = array(
        'id' => 'redux-opts-2',
        'title' => __('Theme Information 2', 'redux-framework'),
        'content' => __('<p>This is the tab content, HTML is allowed.</p>', 'redux-framework')
    );

    // Set the help sidebar for the options page.                                        
    $args['help_sidebar'] = __('<p>This is the sidebar content, HTML is allowed.</p>', 'redux-framework');
*/

    // Add HTML before the form.
    if (!isset($args['global_variable']) || $args['global_variable'] !== false ) {
    	if (!empty($args['global_variable'])) {
    		$v = $args['global_variable'];
    	} else {
    		$v = str_replace("-", "_", $args['opt_name']);
    	}
    	$args['intro_text'] = __('<p>Customize the theme by changing these options. Options are accessed by using the global variable "' . $v .'"</p>', 'redux-framework');
    } else {
    	$args['intro_text'] = __('<p>Customize the theme by changing these options</p>', 'redux-framework');
    }

    // Add content after the form.
    $args['footer_text'] = __('<p>For more information about this theme, visit <a href="http://keithmiyake.info/wheniwasbad">http://keithmiyake.info/wheniwasbad</a></p>', 'redux-framework');

    // Set footer/credit line.
    //$args['footer_credit'] = __('<p>This text is displayed in the options panel footer across from the WordPress version (where it normally says \'Thank you for creating with WordPress\'). This field accepts all HTML.</p>', 'redux-framework');


    $sections = array();              

    //Background Patterns Reader
    $sample_patterns_path = ReduxFramework::$_dir . 'sample/patterns/';
    $sample_patterns_url  = ReduxFramework::$_dir . 'sample/patterns/';
    $sample_patterns      = array();

    if ( is_dir( $sample_patterns_path ) ) :
    	
      if ( $sample_patterns_dir = opendir( $sample_patterns_path ) ) :
      	$sample_patterns = array();

        while ( ( $sample_patterns_file = readdir( $sample_patterns_dir ) ) !== false ) {

          if( stristr( $sample_patterns_file, '.png' ) !== false || stristr( $sample_patterns_file, '.jpg' ) !== false ) {
          	$name = explode(".", $sample_patterns_file);
          	$name = str_replace('.'.end($name), '', $sample_patterns_file);
          	$sample_patterns[] = array( 'alt'=>$name,'img' => $sample_patterns_url . $sample_patterns_file );
          }
        }
      endif;
    endif;


    $sections[] = array(
		'icon' => 'font',
		'icon_class' => 'icon-large',
        'title' => __('Typography', Redux_TEXT_DOMAIN),
        'desc' => __('<p class="description">Typography and font options</p>', Redux_TEXT_DOMAIN),
        'fields' => array(
            array(
                'id' => 'link_color',
                'type' => 'color',
                'title' => __('Link Color', Redux_TEXT_DOMAIN), 
                'desc' => __('Default used if no color is selected.', Redux_TEXT_DOMAIN),
                'default' => ''
            ),
            array(
                'id' => 'link_hover_color',
                'type' => 'color',
                'title' => __('Link:hover Color', Redux_TEXT_DOMAIN), 
                'desc' => __('Default used if no color is selected.', Redux_TEXT_DOMAIN),
                'default' => ''
            ),
        )
    );
	
    $sections[] = array(
		'icon' => 'list-alt',
		'icon_class' => 'icon-large',
        'title' => __('Navigation Bar', Redux_TEXT_DOMAIN),
        'desc' => __('<p class="description">Configure the top (or bottom) navigation bar layout and styles</p>', Redux_TEXT_DOMAIN),
        'fields' => array(
            array(
                'id' => 'nav_position',
                'type' => 'select',
                'title' => __('Position', Redux_TEXT_DOMAIN),
                'desc' => __('Fixed will stay in place, static scrolls with the page.', Redux_TEXT_DOMAIN),
				'options' => array("scroll" => "Scroll","fixed" => "Fixed (top)","fixed-bottom" => "Fixed (bottom)"),
                'default' => 'scroll'
			),
			array(
				'title' => __('Alignment',Redux_TEXT_DOMAIN),
				'desc' => __('Pull the nav menu to the left or right side of the page.', Redux_TEXT_DOMAIN),
				"id" => "nav_alignment",
				"default" => "right",
				"type" => "select",
				"options" => array('left'=>'Align Left', 'right'=>'Align Right')
			),
			array( 
				'title' => __('Use inverted colors on homepage nav bar?', Redux_TEXT_DOMAIN),
				'desc' => __('Swap the font and background color on the navbar for the homepage template.', Redux_TEXT_DOMAIN),
				'id' => 'navbar_style_inverted',
				'default' => 'on',
				'type' => 'switch'
			),
			array( 'title' => __('Check to use a gradient for top nav background', Redux_TEXT_DOMAIN),
					'desc' => __('Use gradient', Redux_TEXT_DOMAIN),
					'id' => 'showhidden_gradient',
					'default' => 0,
					'type' => 'switch'
			),
			array( 'title' => __('Background gradient', Redux_TEXT_DOMAIN),
					'desc' => __('Top nav gradient colors.', Redux_TEXT_DOMAIN),
					'id' => 'top_nav_gradient_color',
					'required' => array('showhidden_gradient','equals', true),
					'default' => '',
					'type' => 'color_gradient'
			),
			array( 
				'title' => __('Top nav background color', Redux_TEXT_DOMAIN),
				'desc' => __('Default used if no color is selected.', Redux_TEXT_DOMAIN),
				'id' => 'top_nav_bg_color',
				'required' => array('showhidden_gradient','equals', false),
				'default' => '',
				'type' => 'color'
			),	
			array( 'title' => __('Top nav item hover color', Redux_TEXT_DOMAIN),
					'desc' => __('Link hover color.', Redux_TEXT_DOMAIN),
					'id' => 'top_nav_link_hover_color',
					'default' => '',
					'type' => 'color'
			),			
			array( 'title' => __('Top nav item hover background', Redux_TEXT_DOMAIN),
					'desc' => __('Link hover background color.', Redux_TEXT_DOMAIN),
					'id' => 'top_nav_link_background_color',
					'default' => '',
					'type' => 'color'
			),											
			array( 'title' => __('Top nav dropdown item color', Redux_TEXT_DOMAIN),
					'desc' => __('Dropdown item color.', Redux_TEXT_DOMAIN),
					'id' => 'top_nav_dropdown_item',
					'default' => '',
					'type' => 'color'
			),
			array( 'title' => __('Top nav dropdown item hover bg color', Redux_TEXT_DOMAIN),
					'desc' => __('Background of dropdown item hover color.', Redux_TEXT_DOMAIN),
					'id' => 'top_nav_dropdown_hover_bg',
					'default' => '',
					'type' => 'color'
			),			
			array( 'title' => __('Search bar', Redux_TEXT_DOMAIN),
					'desc' => __('Show search bar in top nav', Redux_TEXT_DOMAIN),
					'id' => 'search_bar',
					'default' => '0',
					'type' => 'checkbox'
			),
			array( 'title' => __('Site Name', Redux_TEXT_DOMAIN),
					'desc' => __('Display site name in top nav', Redux_TEXT_DOMAIN),
					'id' => 'site_name',
					'default' => '1',
					'type' => 'checkbox'
			),
			array( 'title' => __('Branding Logo', Redux_TEXT_DOMAIN),
					'desc' => __('Select an image to use for site branding', Redux_TEXT_DOMAIN),
					'id' => 'branding_logo',
					'default' => '',
	        		'type' => 'media'
			)
		)
	);
	
	/*$sections[] = array(
			'icon' => 'list-alt',
			'icon_class' => 'icon-large',
	        'title' => __('Navigation Bar', Redux_TEXT_DOMAIN),
	        'desc' => __('<p class="description">Configure the top (or bottom) navigation bar layout and styles</p>', Redux_TEXT_DOMAIN),
	        'fields' => array(
				array( 'title' => __('Bootswatch.com Themes', Redux_TEXT_DOMAIN),
							'desc' => __('Use theme from bootswatch.com. Note: This may override other styles set in the theme options panel.', Redux_TEXT_DOMAIN),
							'id' => 'showhidden_themes',
							'default' => '0',
							'type' => 'checkbox'
				),
						
				array( 'title' => __('Select a theme', Redux_TEXT_DOMAIN),
							'id' => 'wpbs_theme',
							'default' => 'default',
							'class' => 'hidden',
							'type' => 'images',
							'options' => $theList
				),
						
				array( 'title' => __('Refresh themes from Bootswatch', Redux_TEXT_DOMAIN),
							'type' => 'themecheck',
							'id' => 'themecheck'
				)
			)
		);
*/
	    $sections[] = array(
			'icon' => 'level-down',
			'icon_class' => 'icon-large',
	        'title' => __('Footer', Redux_TEXT_DOMAIN),
	        'desc' => __('<p class="description">Configure the top (or bottom) navigation bar layout and styles</p>', Redux_TEXT_DOMAIN),
	        'fields' => array(
				array( 'title' => __('Footer Text', Redux_TEXT_DOMAIN),
							'desc' => __('Custom footer text (HTML OK)', Redux_TEXT_DOMAIN),
							'id' => 'footer_text',
							'default' => '',
							'type' => 'editor'
				),
				array( 'title' => __('Footer Menu', Redux_TEXT_DOMAIN),
							'desc' => __('Check to display a navigation menu in the footer area (configure using WordPress "Menus")', Redux_TEXT_DOMAIN),
							'id' => 'show_footer_menu',
							'default' => '0',
							'type' => 'checkbox'
				)
			)
		);
/*
		//this should get the list of all available sidebars, but the sidebars are registered after this file is loaded. tried to delay loading this but then it breaks...
		foreach($GLOBALS['wp_registered_sidebars'] as $key => $val) {
			$sidebar_list[$key] = $val['name'];
		}
*/
		$sidebar_list = array('sidebar1'=>'Sidebar 1', 'sidebar2'=>'Sidebar 2');
	    $sections[] = array(
			'icon' => 'keyboard',
			'icon_class' => 'icon-large',
	        'title' => __('Archive Pages', Redux_TEXT_DOMAIN),
	        'desc' => __('<p class="description">Settings for archive/blog pages (lists of posts).</p>', Redux_TEXT_DOMAIN),
	        'fields' => array(	
				array( 'title' => __('Sidebar Visibility', Redux_TEXT_DOMAIN),
							'desc' => __('Display sidebar widgets on the blog page?', Redux_TEXT_DOMAIN),
							'id' => 'blog_sidebar',
							'default' => 1,
							'type' => 'switch',
							'on' => 'Enabled',
							'off' => 'Disabled'
				),
				array( 'title' => __('Sidebar Position', Redux_TEXT_DOMAIN),
							'desc' => __('Where should the sidebar be positioned relative to the list of posts?', Redux_TEXT_DOMAIN),
							'id' => 'blog_sidebar_position',
							'required' => array('blog_sidebar','equals', true),
							"default" => "left",
							"type" => "select",
							"options" => array('left'=>'Left', 'right'=>'Right')
				),
				array( 'title' => __('Sidebar Widget Group', Redux_TEXT_DOMAIN),
							'desc' => __('Select a widget group to display on the archive page sidebar.', Redux_TEXT_DOMAIN),
							'id' => 'blog_sidebar_widgets',
							'required' => array('blog_sidebar','equals', true),
							"default" => "sidebar1",
							"type" => "select",
							"options" => $sidebar_list
				),
				array( 'title' => __('Blog page "jumbotron"', Redux_TEXT_DOMAIN),
							'desc' => __('Display blog page jumbotron', Redux_TEXT_DOMAIN),
							'id' => 'blog_jumbotron',
							'default' => '1',
							'type' => 'checkbox'
				),			
				array( 'title' => __('Blog page "jumbotron" content', Redux_TEXT_DOMAIN),
							'desc' => __('Content to display in the blog page jumbotron (HTML OK)', Redux_TEXT_DOMAIN),
							'id' => 'blog_jumbotron_content',
							'default' => '',
							'type' => 'editor'
				),
				array( 'title' => __('Use excerpts on blog/archive/search pages', Redux_TEXT_DOMAIN),
							'desc' => __('Show excerpts on all pages containing multiple posts; full posts only appear on single post pages', Redux_TEXT_DOMAIN),
							'id' => 'use_excerpts',
							'default' => '1',
							'type' => 'checkbox'
				)
			)
		);					


	    $sections[] = array(
			'icon' => 'wrench',
			'icon_class' => 'icon-large',
	        'title' => __('Other Settings', Redux_TEXT_DOMAIN),
	        'desc' => __('<p class="description">Miscellaneous settings</p>', Redux_TEXT_DOMAIN),
	        'fields' => array(
				array( 'title' => __('Slider carousel on homepage', Redux_TEXT_DOMAIN),
							'desc' => __('Display the bootstrap slider carousel on homepage page template. This uses the WordPress featured images.', Redux_TEXT_DOMAIN),
							'id' => 'showhidden_slideroptions',
							'default' => '0',
							'type' => 'switch',
							'on' => 'Enabled',
							'off' => 'Disabled'
				),			
				array( 'title' => __('Slider options', Redux_TEXT_DOMAIN),
							'desc' => __('Number of posts to show.', Redux_TEXT_DOMAIN),
							'id' => 'slider_options',
							'required' => array('showhidden_slideroptions','equals', true),
							'default' => '5',
							'type' => 'text'
				),
				array( 'title' => __('Hide empty widget areas', Redux_TEXT_DOMAIN),
							'desc' => __('If selected, widget areas without any active widgets will be hidden and the rest of the content will take its place.', Redux_TEXT_DOMAIN),
							'id' => 'hide_widgets',
							'default' => '0',
							'type' => 'checkbox'
				),
				array( 'title' => __('Homepage page template jumbotron background color', Redux_TEXT_DOMAIN),
							'desc' => __('Default used if no color is selected.', Redux_TEXT_DOMAIN),
							'id' => 'jumbotron_bg_color',
							'default' => '',
							'type' => 'color'
				),
				array( 'title' => __('"Comments are closed" message on pages', Redux_TEXT_DOMAIN),
							'desc' => __('Suppress "Comments are closed" message', Redux_TEXT_DOMAIN),
							'id' => 'suppress_comments_message',
							'default' => '1',
							'type' => 'checkbox'
				),
				array( 'title' => __('Custom favicon', Redux_TEXT_DOMAIN),
							'desc' => __('URL for a valid .ico favicon', Redux_TEXT_DOMAIN),
							'id' => 'favicon_url',
							'default' => '',
							'type' => 'text'
				),
				array( 'title' => __('CSS', Redux_TEXT_DOMAIN),
							'desc' => __('Additional CSS', Redux_TEXT_DOMAIN),
							'id' => 'wpbs_css',
							'default' => '',
							'type' => 'textarea'
				)
			)
		);
		
	$tabs = array();

	if (function_exists('wp_get_theme')){
	$theme_data = wp_get_theme();
	$theme_uri = $theme_data->get('ThemeURI');
	$description = $theme_data->get('Description');
	$author = $theme_data->get('Author');
	$version = $theme_data->get('Version');
	$tags = $theme_data->get('Tags');
	}else{
	$theme_data = get_theme_data(trailingslashit(get_stylesheet_directory()).'style.css');
	$theme_uri = $theme_data['URI'];
	$description = $theme_data['Description'];
	$author = $theme_data['Author'];
	$version = $theme_data['Version'];
	$tags = $theme_data['Tags'];
	}	

	$theme_info = '<div class="redux-framework-section-desc">';
	$theme_info .= '<p class="redux-framework-theme-data description theme-uri">'.__('<strong>Theme URL:</strong> ', 'redux-framework').'<a href="'.$theme_uri.'" target="_blank">'.$theme_uri.'</a></p>';
	$theme_info .= '<p class="redux-framework-theme-data description theme-author">'.__('<strong>Author:</strong> ', 'redux-framework').$author.'</p>';
	$theme_info .= '<p class="redux-framework-theme-data description theme-version">'.__('<strong>Version:</strong> ', 'redux-framework').$version.'</p>';
	$theme_info .= '<p class="redux-framework-theme-data description theme-description">'.$description.'</p>';
	$theme_info .= '<p class="redux-framework-theme-data description theme-tags">'.__('<strong>Tags:</strong> ', 'redux-framework').implode(', ', $tags).'</p>';
	$theme_info .= '</div>';

	if(file_exists(dirname(__FILE__).'/README.md')){
	$tabs['theme_docs'] = array(
				'icon' => ReduxFramework::$_url.'assets/img/glyphicons/glyphicons_071_book.png',
				'title' => __('Documentation', 'redux-framework'),
				'content' => file_get_contents(dirname(__FILE__).'/README.md')
				);
	}//if 

    $tabs['item_info'] = array(
		'icon' => 'info-sign',
		'icon_class' => 'icon-large',
        'title' => __('Theme Information', 'redux-framework'),
        'content' => $item_info
    );
    
    if(file_exists(trailingslashit(dirname(__FILE__)) . 'README.html')) {
        $tabs['docs'] = array(
			'icon' => 'book',
			'icon_class' => 'icon-large',
            'title' => __('Documentation', 'redux-framework'),
            'content' => nl2br(file_get_contents(trailingslashit(dirname(__FILE__)) . 'README.html'))
        );
    }

    global $ReduxFramework;
    $ReduxFramework = new ReduxFramework($sections, $args, $tabs);

}
add_action('init', 'setup_framework_options', 0);


/*
	This is a test function that will let you see when the compiler hook occurs. 
	It only runs if a field	set with compiler=>true is changed.
*/
function testCompiler() {
	//echo "Compiler hook!";
}
add_action('redux-compiler-redux-sample-file', 'testCompiler');



/**
	Use this function to hide the activation notice telling users about a sample panel.
**/
function removeReduxAdminNotice() {
	delete_option('REDUX_FRAMEWORK_PLUGIN_ACTIVATED_NOTICES');
}
add_action('redux_framework_plugin_admin_notice', 'removeReduxAdminNotice');
