<?php

/**
  ReduxFramework Sample Config File
  For full documentation, please visit: https://docs.reduxframework.com
 * */

if (!class_exists('gctheme_Redux_Framework_config')) {

    class gctheme_Redux_Framework_config {

        public $args        = array();
        public $sections    = array();
        public $theme;
        public $ReduxFramework;

        public function __construct() {

            if (!class_exists('ReduxFramework')) {
                return;
            }

            // This is needed. Bah WordPress bugs.  ;)
            if ( true == Redux_Helpers::isTheme( __FILE__ ) ) {
                $this->initSettings();
            } else {
                add_action('plugins_loaded', array($this, 'initSettings'), 10);
            }

        }

        public function initSettings() {

            // Just for demo purposes. Not needed per say.
            $this->theme = wp_get_theme();

            // Set the default arguments
            $this->setArguments();

            // Set a few help tabs so you can see how it's done
            $this->setHelpTabs();

            // Create the sections and fields
            $this->setSections();

            if (!isset($this->args['opt_name'])) { // No errors please
                return;
            }

            // If Redux is running as a plugin, this will remove the demo notice and links
            add_action( 'redux/loaded', array( $this, 'remove_demo' ) );
            
            // Function to test the compiler hook and demo CSS output.
            // Above 10 is a priority, but 2 in necessary to include the dynamically generated CSS to be sent to the function.
            //add_filter('redux/options/'.$this->args['opt_name'].'/compiler', array( $this, 'compiler_action' ), 10, 2);
            
            // Change the arguments after they've been declared, but before the panel is created
            //add_filter('redux/options/'.$this->args['opt_name'].'/args', array( $this, 'change_arguments' ) );
            
            // Change the default value of a field after it's been set, but before it's been useds
            //add_filter('redux/options/'.$this->args['opt_name'].'/defaults', array( $this,'change_defaults' ) );
            
            // Dynamically add a section. Can be also used to modify sections/fields
            //add_filter('redux/options/' . $this->args['opt_name'] . '/sections', array($this, 'dynamic_section'));

            $this->ReduxFramework = new ReduxFramework($this->sections, $this->args);
        }

        /**

          This is a test function that will let you see when the compiler hook occurs.
          It only runs if a field   set with compiler=>true is changed.

         * */
        function compiler_action($options, $css) {
            //echo '<h1>The compiler hook has run!';
            //print_r($options); //Option values
            //print_r($css); // Compiler selector CSS values  compiler => array( CSS SELECTORS )

            /*
              // Demo of how to use the dynamic CSS and write your own static CSS file
              $filename = dirname(__FILE__) . '/style' . '.css';
              global $wp_filesystem;
              if( empty( $wp_filesystem ) ) {
                require_once( ABSPATH .'/wp-admin/includes/file.php' );
              WP_Filesystem();
              }

              if( $wp_filesystem ) {
                $wp_filesystem->put_contents(
                    $filename,
                    $css,
                    FS_CHMOD_FILE // predefined mode settings for WP files
                );
              }
             */
        }

        /**

          Custom function for filtering the sections array. Good for child themes to override or add to the sections.
          Simply include this function in the child themes functions.php file.

          NOTE: the defined constants for URLs, and directories will NOT be available at this point in a child theme,
          so you must use get_template_directory_uri() if you want to use any of the built in icons

         * */
        function dynamic_section($sections) {
            //$sections = array();
            $sections[] = array(
                'title' => __('Section via hook', Redux_TEXT_DOMAIN),
                'desc' => __('<p class="description">This is a section created by adding a filter to the sections array. Can be used by child themes to add/remove sections from the options.</p>', Redux_TEXT_DOMAIN),
                'icon' => 'el-icon-paper-clip',
                // Leave this as a blank section, no options just some intro text set above.
                'fields' => array()
            );

            return $sections;
        }

        /**

          Filter hook for filtering the args. Good for child themes to override or add to the args array. Can also be used in other functions.

         * */
        function change_arguments($args) {
            //$args['dev_mode'] = true;

            return $args;
        }

        /**

          Filter hook for filtering the default value of any given field. Very useful in development mode.

         * */
        function change_defaults($defaults) {
            $defaults['str_replace'] = 'Testing filter hook!';

            return $defaults;
        }

        // Remove the demo link and the notice of integrated demo from the redux-framework plugin
        function remove_demo() {

            // Used to hide the demo mode link from the plugin page. Only used when Redux is a plugin.
            if (class_exists('ReduxFrameworkPlugin')) {
                remove_filter('plugin_row_meta', array(ReduxFrameworkPlugin::instance(), 'plugin_metalinks'), null, 2);

                // Used to hide the activation notice informing users of the demo panel. Only used when Redux is a plugin.
                remove_action('admin_notices', array(ReduxFrameworkPlugin::instance(), 'admin_notices'));
            }
        }

        public function setSections() {

            /**
              Used within different fields. Simply examples. Search for ACTUAL DECLARATION for field examples
             * */
            // Background Patterns Reader
            $sample_patterns_path   = ReduxFramework::$_dir . '../sample/patterns/';
            $sample_patterns_url    = ReduxFramework::$_url . '../sample/patterns/';
            $sample_patterns        = array();

            if (is_dir($sample_patterns_path)) :

                if ($sample_patterns_dir = opendir($sample_patterns_path)) :
                    $sample_patterns = array();

                    while (( $sample_patterns_file = readdir($sample_patterns_dir) ) !== false) {

                        if (stristr($sample_patterns_file, '.png') !== false || stristr($sample_patterns_file, '.jpg') !== false) {
                            $name = explode('.', $sample_patterns_file);
                            $name = str_replace('.' . end($name), '', $sample_patterns_file);
                            $sample_patterns[]  = array('alt' => $name, 'img' => $sample_patterns_url . $sample_patterns_file);
                        }
                    }
                endif;
            endif;

            ob_start();

            $ct             = wp_get_theme();
            $this->theme    = $ct;
            $item_name      = $this->theme->get('Name');
            $tags           = $this->theme->Tags;
            $screenshot     = $this->theme->get_screenshot();
            $class          = $screenshot ? 'has-screenshot' : '';

            $customize_title = sprintf(__('Customize &#8220;%s&#8221;', Redux_TEXT_DOMAIN), $this->theme->display('Name'));
            
            ?>
            <div id="current-theme" class="<?php echo esc_attr($class); ?>">
            <?php if ($screenshot) : ?>
                <?php if (current_user_can('edit_theme_options')) : ?>
                        <a href="<?php echo wp_customize_url(); ?>" class="load-customize hide-if-no-customize" title="<?php echo esc_attr($customize_title); ?>">
                            <img src="<?php echo esc_url($screenshot); ?>" alt="<?php esc_attr_e('Current theme preview'); ?>" />
                        </a>
                <?php endif; ?>
                    <img class="hide-if-customize" src="<?php echo esc_url($screenshot); ?>" alt="<?php esc_attr_e('Current theme preview'); ?>" />
                <?php endif; ?>

                <h4><?php echo $this->theme->display('Name'); ?></h4>

                <div>
                    <ul class="theme-info">
                        <li><?php printf(__('By %s', Redux_TEXT_DOMAIN), $this->theme->display('Author')); ?></li>
                        <li><?php printf(__('Version %s', Redux_TEXT_DOMAIN), $this->theme->display('Version')); ?></li>
                        <li><?php echo '<strong>' . __('Tags', Redux_TEXT_DOMAIN) . ':</strong> '; ?><?php printf($this->theme->display('Tags')); ?></li>
                    </ul>
                    <p class="theme-description"><?php echo $this->theme->display('Description'); ?></p>
            <?php
            if ($this->theme->parent()) {
                printf(' <p class="howto">' . __('This <a href="%1$s">child theme</a> requires its parent theme, %2$s.') . '</p>', __('http://codex.wordpress.org/Child_Themes', Redux_TEXT_DOMAIN), $this->theme->parent()->display('Name'));
            }
            ?>

                </div>
            </div>

            <?php
            $item_info = ob_get_contents();

            ob_end_clean();

            $sampleHTML = '';
            if (file_exists(dirname(__FILE__) . '/info-html.html')) {
                /** @global WP_Filesystem_Direct $wp_filesystem  */
                global $wp_filesystem;
                if (empty($wp_filesystem)) {
                    require_once(ABSPATH . '/wp-admin/includes/file.php');
                    WP_Filesystem();
                }
                $sampleHTML = $wp_filesystem->get_contents(dirname(__FILE__) . '/info-html.html');
            }

            // ACTUAL DECLARATION OF SECTIONS
            $this->sections[] = array(
                'icon'      => 'el-icon-cogs',
                'title'     => __('General Settings', Redux_TEXT_DOMAIN),
                'fields'    => array(
                    array(
                        'title' => __('Hide empty widget areas', Redux_TEXT_DOMAIN),
                        'desc' => __('If selected, widget areas without any active widgets will be hidden and the rest of the content will take its place.', Redux_TEXT_DOMAIN),
                        'id' => 'hide_widgets',
                        'default' => '0',
                        'type' => 'checkbox'
                    ),
                    array(
                        'title' => __('"Comments are closed" message on pages', Redux_TEXT_DOMAIN),
                        'desc' => __('Suppress "Comments are closed" message', Redux_TEXT_DOMAIN),
                        'id' => 'suppress_comments_message',
                        'default' => '1',
                        'type' => 'checkbox'
                    ),
                    array(
                        'title'     => __('Custom favicon', Redux_TEXT_DOMAIN),
                        'subtitle'  => __('It is recommended to use a 16x16, 32x32, or 64x64 image in .ico, .gif, or .png formats.', Redux_TEXT_DOMAIN),
                        'desc'      => __('Upload a custom favicon image', Redux_TEXT_DOMAIN),
                        'id'        => 'favicon_url',
                        'default'   => '',
                        'type'      => 'media',
                        'url'       => true,
                        'readonly'  => false,
                        'default'   => array(
                            'url'   => get_template_directory_uri() . '/library/images/icons/favicon.png'
                        )
                    ),
                    array(
                        'id'        => 'opt-google-analytics',
                        'type'      => 'textarea',
                        'title'     => __('Tracking Code', Redux_TEXT_DOMAIN),
                        'subtitle'  => __('Paste your Google Analytics (or other) tracking code here. This will be added into the footer template of your theme.', Redux_TEXT_DOMAIN),
                        'validate'  => 'js',
                        'desc'      => 'Javascript tracking code; do not include the &lt;script&gt; tags.',
                    ),
                    array(
                        'id'        => 'opt-custom-css',
                        'type'      => 'ace_editor',
                        'title'     => __('CSS Code', Redux_TEXT_DOMAIN),
                        'subtitle'  => __('Add custom CSS code here. This will be added to the bottom of the page head and will override other CSS.', Redux_TEXT_DOMAIN),
                        'mode'      => 'css',
                        'theme'     => 'monokai',
                        'default'   => "#header{\n\tmargin: 0 auto;\n}"
                    ),
                    array(
                        'id'        => 'opt-custom-js',
                        'type'      => 'ace_editor',
                        'title'     => __('JS Code', Redux_TEXT_DOMAIN),
                        'subtitle'  => __('Add custom JS code here. This will be added to the page footer.', Redux_TEXT_DOMAIN),
                        'mode'      => 'javascript',
                        'theme'     => 'monokai',
                        'default'   => "jQuery(document).ready(function(){\n\n});"
                    ),
                    array(
                        'id'        => 'opt-footer-text',
                        'type'      => 'editor',
                        'title'     => __('Footer Text', Redux_TEXT_DOMAIN),
                        'subtitle'  => __('You can use the following shortcodes in your footer text: [wp-url] [site-url] [theme-url] [login-url] [logout-url] [site-title] [site-tagline] [current-year]', Redux_TEXT_DOMAIN),
                        'default'   => 'Powered by Redux Framework.',
                    )
                )
            );

            $this->sections[] = array(
                'icon'      => 'el-icon-tint',
                'title'     => __('Styling Options', Redux_TEXT_DOMAIN),
                'fields'    => array(
                    array(
                        'id'        => 'opt-background',
                        'type'      => 'background',
                        'output'    => array('body'),
                        'title'     => __('Body Background', Redux_TEXT_DOMAIN),
                        'subtitle'  => __('Body background with image, color, etc.', Redux_TEXT_DOMAIN),
                        //'default'   => '#FFFFFF',
                    ),
                    array(
                        'id'        => 'opt-footer-background',
                        'type'      => 'background',
                        'output'    => array('#page-footer'),
                        'title'     => __('Footer Background', Redux_TEXT_DOMAIN),
                        'subtitle'  => __('Footer background with image, color, etc.', Redux_TEXT_DOMAIN),
                        //'default'   => '#FFFFFF',
                    ),
                    array(
                        'id'        => 'opt-link-color',
                        'type'      => 'link_color',
                        'title'     => __('Links Color Option', Redux_TEXT_DOMAIN),
                        'subtitle'  => __('Primary link colors.', Redux_TEXT_DOMAIN),
                        //'desc'      => __('This is the description field, again good for additional info.', Redux_TEXT_DOMAIN),
                        //'regular'   => false, // Disable Regular Color
                        //'hover'     => false, // Disable Hover Color
                        //'active'    => false, // Disable Active Color
                        'visited'   => true,  // Enable Visited Color
                        'output'    => array('a'),
                        'default'   => array(
                            'regular'   => '#005daa',
                            'hover'     => '#00335e',
                            'active'    => '#005daa',
                            'visited'    => '#777777',
                        )
                    ),
                    array(
                        'id'        => 'opt-typography-body',
                        'type'      => 'typography',
                        'title'     => __('Body Font', Redux_TEXT_DOMAIN),
                        'subtitle'  => __('Specify the body font properties.', Redux_TEXT_DOMAIN),
                        'output'    => array('body'),
                        'google'    => true,
                        'font-backup'   => true,
                        'text-align'    => false,
                        /*'default'   => array(
                            'color'         => '#dd9933',
                            'font-size'     => '30px',
                            'font-family'   => 'Arial,Helvetica,sans-serif',
                            'font-weight'   => 'Normal',
                        ),*/
                    ),
                    array(
                        'id'        => 'opt-typography-h1',
                        'type'      => 'typography',
                        'title'     => __('H1 Font', Redux_TEXT_DOMAIN),
                        'subtitle'  => __('Specify the font properties for H1 heading tags.', Redux_TEXT_DOMAIN),
                        'output'    => array('h1','.h1'),
                        'google'    => true,
                        'font-backup'   => true,
                        'text-align'    => false,
                        /*'default'   => array(
                            'color'         => '#dd9933',
                            'font-size'     => '30px',
                            'font-family'   => 'Arial,Helvetica,sans-serif',
                            'font-weight'   => 'Normal',
                        ),*/
                    ),
                    array(
                        'id'        => 'opt-typography-h2',
                        'type'      => 'typography',
                        'title'     => __('H2 Font', Redux_TEXT_DOMAIN),
                        'subtitle'  => __('Specify the font properties for H2 heading tags.', Redux_TEXT_DOMAIN),
                        'output'    => array('h1','.h1'),
                        'google'    => true,
                        'font-backup'   => true,
                        'text-align'    => false,
                        /*'default'   => array(
                            'color'         => '#dd9933',
                            'font-size'     => '30px',
                            'font-family'   => 'Arial,Helvetica,sans-serif',
                            'font-weight'   => 'Normal',
                        ),*/
                    ),
                    array(
                        'id'        => 'opt-typography-h3',
                        'type'      => 'typography',
                        'title'     => __('H3 Font', Redux_TEXT_DOMAIN),
                        'subtitle'  => __('Specify the font properties for H3 heading tags.', Redux_TEXT_DOMAIN),
                        'output'    => array('h1','.h1'),
                        'google'    => true,
                        'font-backup'   => true,
                        'text-align'    => false,
                        /*'default'   => array(
                            'color'         => '#dd9933',
                            'font-size'     => '30px',
                            'font-family'   => 'Arial,Helvetica,sans-serif',
                            'font-weight'   => 'Normal',
                        ),*/
                    ),
                    array(
                        'id'        => 'opt-typography-h4',
                        'type'      => 'typography',
                        'title'     => __('H4 Font', Redux_TEXT_DOMAIN),
                        'subtitle'  => __('Specify the font properties for H4 heading tags.', Redux_TEXT_DOMAIN),
                        'output'    => array('h1','.h1'),
                        'google'    => true,
                        'font-backup'   => true,
                        'text-align'    => false,
                        /*'default'   => array(
                            'color'         => '#dd9933',
                            'font-size'     => '30px',
                            'font-family'   => 'Arial,Helvetica,sans-serif',
                            'font-weight'   => 'Normal',
                        ),*/
                    ),
                    array(
                        'id'        => 'opt-typography-h5',
                        'type'      => 'typography',
                        'title'     => __('H5 Font', Redux_TEXT_DOMAIN),
                        'subtitle'  => __('Specify the font properties for H5 heading tags.', Redux_TEXT_DOMAIN),
                        'output'    => array('h1','.h1'),
                        'google'    => true,
                        'font-backup'   => true,
                        'text-align'    => false,
                        /*'default'   => array(
                            'color'         => '#dd9933',
                            'font-size'     => '30px',
                            'font-family'   => 'Arial,Helvetica,sans-serif',
                            'font-weight'   => 'Normal',
                        ),*/
                    ),
                    array(
                        'id'        => 'opt-typography-h6',
                        'type'      => 'typography',
                        'title'     => __('H6 Font', Redux_TEXT_DOMAIN),
                        'subtitle'  => __('Specify the font properties for H6 heading tags.', Redux_TEXT_DOMAIN),
                        'output'    => array('h1','.h1'),
                        'google'    => true,
                        'font-backup'   => true,
                        'text-align'    => false,
                        /*'default'   => array(
                            'color'         => '#dd9933',
                            'font-size'     => '30px',
                            'font-family'   => 'Arial,Helvetica,sans-serif',
                            'font-weight'   => 'Normal',
                        ),*/
                    ),
                )
            );

            /**
             *  Note here I used a 'heading' in the sections array construct
             *  This allows you to use a different title on your options page
             * instead of reusing the 'title' value.  This can be done on any
             * section - kp
             */
            $this->sections[] = array(
                'icon'      => 'el-icon-website',
                'title'     => __('Header', Redux_TEXT_DOMAIN),
                'heading'   => __('Options for the page header.', Redux_TEXT_DOMAIN),
                //'desc'      => __('<p class="description">This is the Description. Again HTML is allowed2</p>', Redux_TEXT_DOMAIN),
                'fields'    => array(
                    array(
                        'id' => 'opt-header-scroll',
                        'type' => 'select',
                        'title' => __('Position', Redux_TEXT_DOMAIN),
                        'desc' => __('Affix the header at the top of the page or scroll with the page?', Redux_TEXT_DOMAIN),
                        'options' => array("scroll" => "Scroll","fixed" => "Fixed"),
                        'default' => 'scroll'
                    ),
                    array(
                        'title' => __('Header Background', Redux_TEXT_DOMAIN),
                        'desc' => __('Set the background for the header', Redux_TEXT_DOMAIN),
                        'id' => 'header_bg',
                        'type' => 'background',
                        'default' => array(
                            'background-color' => '#ffffff',
                            'background-repeat' => 'repeat',
                            'background-attachment' => 'scroll',
                            'background-position' => 'left-top',
                            'background-image' => get_template_directory_uri() . '/library/images/header_bg.gif',
                            'background-size' => 'inherit'
                        ),
                        'output' => array('#main_header')
                    ),
                    array(
                        'id'        => 'opt-header-border',
                        'type'      => 'border',
                        'title'     => __('Header Border Option', Redux_TEXT_DOMAIN),
                        'subtitle'  => __('Only color validation can be done on this field type', Redux_TEXT_DOMAIN),
                        'output'    => array('#main_header'), // An array of CSS selectors to apply this font style to
                        'desc'      => __('This is the description field, again good for additional info.', Redux_TEXT_DOMAIN),
                        'all'       => false,
                        'default'   => array(
                            'border-style'  => 'none'
                        )
                    ),
                    array(
                        'title' => __('Header text color', Redux_TEXT_DOMAIN),
                        'desc' => __('Color used on site title', Redux_TEXT_DOMAIN),
                        'id' => 'top_nav_text_color',
                        'default' => array (
                            'regular' => '#444444',
                            'hover' => '#777777',
                            'active' => '#777777',
                            'visited' => '#444444'
                        ),
                        'type' => 'link_color',
                        'output' => array('.navbar-default .navbar-brand'),
                        'validate' => 'color'
                    ),
                    array(
                        'title' => __('Menu font color', Redux_TEXT_DOMAIN),
                        'id' => 'top_nav_link_color',
                        'default' => array (
                            'regular' => '#444444',
                            'hover' => '#777777',
                            'active' => '#777777',
                            'visited' => '#444444'
                        ),
                        'type' => 'link_color',
                        'output' => array('.navbar-default .navbar-nav>li>a'),
                        'validate' => 'color'
                    ),

                    array(
                        'title' => __('Search bar', Redux_TEXT_DOMAIN),
                        'desc' => __('Show search bar in top nav', Redux_TEXT_DOMAIN),
                        'id' => 'search_bar',
                        'default' => '0',
                        'type' => 'checkbox'
                    ),
                    array(
                        'title' => __('Site Name', Redux_TEXT_DOMAIN),
                        'desc' => __('Display site name in top nav', Redux_TEXT_DOMAIN),
                        'id' => 'site_name',
                        'default' => '1',
                        'type' => 'checkbox'
                    ),
                    array(
                        'title' => __('Branding Logo', Redux_TEXT_DOMAIN),
                        'desc' => __('Select an image to use for site branding', Redux_TEXT_DOMAIN),
                        'id' => 'branding_logo',
                        'default' => '',
                        'type' => 'media'
                    ),
                    array(
                        'title' => __('University Branding', Redux_TEXT_DOMAIN),
                        'desc' => __('Choose a version of the CUNY/GC logo to include in the header', Redux_TEXT_DOMAIN),
                        'id' => 'cuny_branding_logo',
                        'type' => 'image_select',
                        'options' => array(
                            get_template_directory_uri() . '/library/images/GC_logo-blue.png' => array(
                                'title' => 'GC Blue',
                                'img' => get_template_directory_uri() . '/library/images/GC_logo-blue.png'
                            ),
                            get_template_directory_uri() . '/library/images/GC_logo-white.png' => array(
                                'title' => 'GC White',
                                'img' => get_template_directory_uri() . '/library/images/GC_logo-white.png'
                            ),
                            get_template_directory_uri() . '/library/images/GC_logo-black.png' => array(
                                'title' => 'GC Black',
                                'img' => get_template_directory_uri() . '/library/images/GC_logo-black.png'
                            ),
                            get_template_directory_uri() . '/library/images/GC_logo-gray.png' => array(
                                'title' => 'GC Gray',
                                'img' => get_template_directory_uri() . '/library/images/GC_logo-gray.png'
                            ),
                            get_template_directory_uri() . '/library/images/cuny_logo-blue.png' => array(
                                'title' => 'CUNY Blue',
                                'img' => get_template_directory_uri() . '/library/images/cuny_logo-blue.png'
                            ),
                            get_template_directory_uri() . '/library/images/cuny_logo-yellow.png' => array(
                                'title' => 'CUNY Yellow',
                                'img' => get_template_directory_uri() . '/library/images/cuny_logo-yellow.png'
                            ),
                            'none' => array(
                                'title' => 'None'
                            )
                        ),
                        'output' => array('background-image' => '#sitewide_logo'),
                        'width' => '52px',
                        'height' => '88px',
                        'default' => get_template_directory_uri() . '/library/images/GC_logo-blue.png'
                    ),
                    array(
                        'id'       => 'opt-university-branding-position',
                        'type'     => 'spacing',
                        'title'    => __('University Branding Position', Redux_TEXT_DOMAIN),
                        'subtitle' => __('Set the position offset from the upper right-hand corner of the page.', Redux_TEXT_DOMAIN),
                        'desc'     => __('Set the distance from the top and right of the page.', Redux_TEXT_DOMAIN),
                        'output'   => array('#sitewide_logo'),
                        'mode'     => 'absolute',
                        'units'    => array('px','em'),
                        'display_units' => true,
                        'units_extended' => false,
                        'top'      => true,
                        'right'    => true,
                        'bottom'   => false,
                        'left'     => false,
                        'default'  => array(
                            'top' => 8,
                            'right' => 0,
                            'units' => 'px'
                        )
                    ),
                )
            );

            $this->sections[] = array(
                'title'     => __('Homepage Settings', Redux_TEXT_DOMAIN),
                'desc'      => __('', Redux_TEXT_DOMAIN),
                'icon'      => 'el-icon-home',
                // 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                'fields'    => array(

                    array(
                        'title' => __('Slider on homepage', Redux_TEXT_DOMAIN),
                        'desc' => __('Display a bootstrap post slider on homepage page template. This uses the WordPress featured images.', Redux_TEXT_DOMAIN),
                        'id' => 'showhidden_slideroptions',
                        'default' => '0',
                        'type' => 'switch',
                        'on' => 'Enabled',
                        'off' => 'Disabled'
                    ),

                    array(
                        'title' => __('Slider options', Redux_TEXT_DOMAIN),
                        'desc'  => __('Number of posts to show.', Redux_TEXT_DOMAIN),
                        'id'    => 'slider_options',
                        'required'  => array('showhidden_slideroptions','equals', true),
                        'type'  => 'slider',
                        'min'   => 1,
                        'max'   => 10,
                        'step'  => 1,
                        'display_text'  => 'label',
                        'default'   => '5',
                    ),

                    array(
                        'title' => __('Homepage jumbotron background color', Redux_TEXT_DOMAIN),
                        'desc' => __('Default used if no color is selected.', Redux_TEXT_DOMAIN),
                        'id' => 'jumbotron_bg_color',
                        'default' => '',
                        'type' => 'color'
                    ),
                ),
            );

            $this->sections[] = array(
                'icon'      => 'el-icon-pencil-alt',
                'title'     => __('Blog/Archive Page Options', Redux_TEXT_DOMAIN),
                'desc'      => __('Customize the default "blog" page and post archive/category/tag pages.', Redux_TEXT_DOMAIN),
                'fields'    => array(
                    array(
                        'title' => __('Sidebar Visibility', Redux_TEXT_DOMAIN),
                        'desc' => __('Display sidebar widgets on the blog page?', Redux_TEXT_DOMAIN),
                        'id' => 'blog_sidebar',
                        'default' => 1,
                        'type' => 'switch',
                        'on' => 'Enabled',
                        'off' => 'Disabled'
                    ),
                    array(
                        'title' => __('Sidebar Position', Redux_TEXT_DOMAIN),
                        'desc' => __('Where should the sidebar be positioned relative to the list of posts?', Redux_TEXT_DOMAIN),
                        'id' => 'blog_sidebar_position',
                        'required' => array('blog_sidebar','equals', true),
                        'default' => 'left',
                        'type' => 'select',
                        'options' => array('left'=>'Left', 'right'=>'Right')
                    ),
                    array(
                        'title' => __('Sidebar Widget Group', Redux_TEXT_DOMAIN),
                        'desc' => __('Select a widget group to display on the archive page sidebar.', Redux_TEXT_DOMAIN),
                        'id' => 'blog_sidebar_widgets',
                        'required' => array('blog_sidebar','equals', true),
                        'default' => 'sidebar1',
                        'type' => 'select',
                        'options' => $sidebar_list
                    ),
                    array(
                        'title' => __('Blog page "jumbotron"', Redux_TEXT_DOMAIN),
                        'desc' => __('Display blog page jumbotron', Redux_TEXT_DOMAIN),
                        'id' => 'blog_jumbotron',
                        'default' => '1',
                        'type' => 'checkbox'
                    ),
                    array(
                        'title' => __('Blog page "jumbotron" content', Redux_TEXT_DOMAIN),
                        'desc' => __('Content to display in the blog page jumbotron (HTML OK)', Redux_TEXT_DOMAIN),
                        'id' => 'blog_jumbotron_content',
                        'default' => '',
                        'type' => 'editor'
                    ),
                    array(
                        'title' => __('Use excerpts on blog/archive/search pages', Redux_TEXT_DOMAIN),
                        'desc' => __('Show excerpts on all pages containing multiple posts; full posts only appear on single post pages', Redux_TEXT_DOMAIN),
                        'id' => 'use_excerpts',
                        'default' => '1',
                        'type' => 'checkbox'
                    )
                )
            );

            $theme_info  = '<div class="redux-framework-section-desc">';
            $theme_info .= '<p class="redux-framework-theme-data description theme-uri">' . __('<strong>Theme URL:</strong> ', Redux_TEXT_DOMAIN) . '<a href="' . $this->theme->get('ThemeURI') . '" target="_blank">' . $this->theme->get('ThemeURI') . '</a></p>';
            $theme_info .= '<p class="redux-framework-theme-data description theme-author">' . __('<strong>Author:</strong> ', Redux_TEXT_DOMAIN) . $this->theme->get('Author') . '</p>';
            $theme_info .= '<p class="redux-framework-theme-data description theme-version">' . __('<strong>Version:</strong> ', Redux_TEXT_DOMAIN) . $this->theme->get('Version') . '</p>';
            $theme_info .= '<p class="redux-framework-theme-data description theme-description">' . $this->theme->get('Description') . '</p>';
            $tabs = $this->theme->get('Tags');
            if (!empty($tabs)) {
                $theme_info .= '<p class="redux-framework-theme-data description theme-tags">' . __('<strong>Tags:</strong> ', Redux_TEXT_DOMAIN) . implode(', ', $tabs) . '</p>';
            }
            $theme_info .= '</div>';

            if (file_exists(dirname(__FILE__) . '/../README.md')) {
                $this->sections['theme_docs'] = array(
                    'icon'      => 'el-icon-list-alt',
                    'title'     => __('Documentation', Redux_TEXT_DOMAIN),
                    'fields'    => array(
                        array(
                            'id'        => '17',
                            'type'      => 'raw',
                            'markdown'  => true,
                            'content'   => file_get_contents(dirname(__FILE__) . '/../README.md')
                        ),
                    ),
                );
            }

                    
            $this->sections[] = array(
                'type' => 'divide',
            );

            $this->sections[] = array(
                'icon'      => 'el-icon-info-sign',
                'title'     => __('Theme Information', Redux_TEXT_DOMAIN),
                'desc'      => __('Visit the GitHub project for this theme: <a href="https://github.com/GCDigitalFellows/GCWordpressTheme">GC WordPress Theme on GitHub</a>', Redux_TEXT_DOMAIN),
                'fields'    => array(
                    array(
                        'id'        => 'opt-raw-info',
                        'type'      => 'raw',
                        'content'   => $item_info,
                    )
                ),
            );

            if (file_exists(trailingslashit(dirname(__FILE__)) . 'README.html')) {
                $tabs['docs'] = array(
                    'icon'      => 'el-icon-book',
                    'title'     => __('Documentation', Redux_TEXT_DOMAIN),
                    'content'   => nl2br(file_get_contents(trailingslashit(dirname(__FILE__)) . 'README.html'))
                );
            }
        }

        public function setHelpTabs() {

            // Custom page help tabs, displayed using the help API. Tabs are shown in order of definition.
            $this->args['help_tabs'][] = array(
                'id'        => 'redux-help-tab-1',
                'title'     => __('Theme Information 1', Redux_TEXT_DOMAIN),
                'content'   => __('<p>This is the tab content, HTML is allowed.</p>', Redux_TEXT_DOMAIN)
            );

            $this->args['help_tabs'][] = array(
                'id'        => 'redux-help-tab-2',
                'title'     => __('Theme Information 2', Redux_TEXT_DOMAIN),
                'content'   => __('<p>This is the tab content, HTML is allowed.</p>', Redux_TEXT_DOMAIN)
            );

            // Set the help sidebar
            $this->args['help_sidebar'] = __('<p>This is the sidebar content, HTML is allowed.</p>', Redux_TEXT_DOMAIN);
        }

        /**

          All the possible arguments for Redux.
          For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments

         * */
        public function setArguments() {

            $theme = wp_get_theme(); // For use with some settings. Not necessary.

            $this->args = array (
  'opt_name' => 'gctheme_options',
  'admin_bar' => '1',
  'allow_sub_menu' => '1',
  'customizer' => '1',
  'default_mark' => '*',
  'footer_text' => '<p>Theme options built using <a href="http://reduxframework.com/">ReduxFramework</a></p>',
  'google_api_key' => 'AIzaSyCOMpFLoX3Eh7Sma70MwR3iWKKWQ81oceA',
  'hints' => 
  array (
    'icon' => 'el-icon-question-sign',
    'icon_position' => 'right',
    'icon_size' => 'normal',
    'tip_style' => 
    array (
      'color' => 'light',
      'rounded' => '1',
      'style' => 'bootstrap',
    ),
    'tip_position' => 
    array (
      'my' => 'top left',
      'at' => 'bottom right',
    ),
    'tip_effect' => 
    array (
      'show' => 
      array (
        'effect' => 'fade',
        'duration' => '500',
        'event' => 'mouseover',
      ),
      'hide' => 
      array (
        'effect' => 'fade',
        'duration' => '500',
        'event' => 'mouseleave unfocus',
      ),
    ),
  ),
  'intro_text' => '<p>Customize the GC Theme for your site!</p>',
  'menu_title' => 'GC Theme Options',
  'menu_type' => 'submenu',
  'output' => '1',
  'output_tag' => '1',
  'page_icon' => 'icon-themes',
  'page_parent' => 'themes.php',
  'page_parent_post_type' => 'your_post_type',
  'page_permissions' => 'manage_options',
  'page_slug' => 'gctheme_options',
  'page_title' => 'GC Theme Options',
  'save_defaults' => '1',
  'show_import_export' => '1',
  'update_notice' => '1',
);

            $theme = wp_get_theme(); // For use with some settings. Not necessary.
            $this->args["display_name"] = $theme->get("Name");
            $this->args["display_version"] = $theme->get("Version");

// SOCIAL ICONS -> Setup custom links in the footer for quick links in your panel footer icons.
            $this->args['share_icons'][] = array(
                'url'   => 'https://github.com/ReduxFramework/ReduxFramework',
                'title' => 'Visit us on GitHub',
                'icon'  => 'el-icon-github'
                //'img'   => '', // You can use icon OR img. IMG needs to be a full URL.
            );
            $this->args['share_icons'][] = array(
                'url'   => 'https://www.facebook.com/pages/Redux-Framework/243141545850368',
                'title' => 'Like us on Facebook',
                'icon'  => 'el-icon-facebook'
            );
            $this->args['share_icons'][] = array(
                'url'   => 'http://twitter.com/reduxframework',
                'title' => 'Follow us on Twitter',
                'icon'  => 'el-icon-twitter'
            );
            $this->args['share_icons'][] = array(
                'url'   => 'http://www.linkedin.com/company/redux-framework',
                'title' => 'Find us on LinkedIn',
                'icon'  => 'el-icon-linkedin'
            );

        }

    }
    
    global $reduxConfig;
    $reduxConfig = new gctheme_Redux_Framework_config();
}

/**
  Custom function for the callback referenced above
 */
if (!function_exists('gctheme_my_custom_field')):
    function gctheme_my_custom_field($field, $value) {
        print_r($field);
        echo '<br/>';
        print_r($value);
    }
endif;

/**
  Custom function for the callback validation referenced above
 * */
if (!function_exists('gctheme_validate_callback_function')):
    function gctheme_validate_callback_function($field, $value, $existing_value) {
        $error = false;
        $value = 'just testing';

        /*
          do your validation

          if(something) {
            $value = $value;
          } elseif(something else) {
            $error = true;
            $value = $existing_value;
            $field['msg'] = 'your custom error message';
          }
         */

        $return['value'] = $value;
        if ($error == true) {
            $return['error'] = $field;
        }
        return $return;
    }
endif;
