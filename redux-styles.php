<style type="text/css">
/* CSS Generated from theme options */
<?php
	global $wheniwasbad_options;

	$theme_options_styles = '';
/*
      $heading_typography = $wheniwasbad_options['heading_typography'];
      if ( $heading_typography['face'] != 'Default' ) {
        $theme_options_styles .= '
        h1, h2, h3, h4, h5, h6{
          font-family: ' . $heading_typography['face'] . ';
          font-weight: ' . $heading_typography['style'] . ';
          color: ' . $heading_typography['color'] . ';
        }';
      }

      $main_body_typography = $wheniwasbad_options['main_body_typography'];
      if ( $main_body_typography['face'] != 'Default' ) {
        $theme_options_styles .= '
        body{
          font-family: ' . $main_body_typography['face'] . ';
          font-weight: ' . $main_body_typography['style'] . ';
          color: ' . $main_body_typography['color'] . ';
        }';
      }
*/
      $link_color = $wheniwasbad_options['link_color'];
      if ($link_color) {
        $theme_options_styles .= '
		a{
			color: ' . $link_color . ';
		}';
      }

      $link_hover_color = $wheniwasbad_options['link_hover_color'];
      if ($link_hover_color) {
        $theme_options_styles .= '
		a:hover{
			color: ' . $link_hover_color . ';
		}';
      }

      $link_active_color = $wheniwasbad_options['link_active_color'];
      if ($link_active_color) {
        $theme_options_styles .= '
		a:active{
			color: ' . $link_active_color . ';
		}';
      }

      $topbar_bg_color = $wheniwasbad_options['top_nav_bg_color'];
      if ( $topbar_bg_color ) {
        $theme_options_styles .= '
		.navbar .fill {
			background-color: '. $topbar_bg_color . ';
			background-image: none;
		}';
      }

      $topbar_link_color = $wheniwasbad_options['top_nav_link_color'];
      if ( $topbar_link_color ) {
        $theme_options_styles .= '
		.navbar .nav li a {
			color: '. $topbar_link_color . ';
		}';
      }

      $topbar_link_hover_color = $wheniwasbad_options['top_nav_link_hover_color'];
      if ( $topbar_link_hover_color ) {
        $theme_options_styles .= '
		.navbar .nav li a:hover {
			color: '. $topbar_link_hover_color . ';
		}';
      }

      $topbar_link_hover_bg = $wheniwasbad_options['top_nav_link_background_color'];
      if ( $topbar_link_hover_bg ) {
        $theme_options_styles .= '
		.navbar .nav > li > a:focus,
		.navbar .nav > li > a:hover,
		.navbar .nav .active > a,
		.navbar .nav .active > a:hover,
		.navbar .nav .active > a:focus,
		.navbar .nav li.dropdown.open > .dropdown-toggle,
		.navbar .nav li.dropdown.active > .dropdown-toggle,
		.navbar .nav li.dropdown.open.active > .dropdown-toggle {
			background-color: '. $topbar_link_hover_bg . ';
		  }';
      }

      $topbar_dropdown_hover_bg_color = $wheniwasbad_options['top_nav_dropdown_hover_bg'];
      if ( $topbar_dropdown_hover_bg_color ) {
        $theme_options_styles .= '
		.dropdown-menu li > a:hover,
		.dropdown-menu .active > a,
		.dropdown-menu .active > a:hover {
			background-color: ' . $topbar_dropdown_hover_bg_color . ';
		}
        ';
      }

      $topbar_dropdown_item_color = $wheniwasbad_options['top_nav_dropdown_item'];
      if ( $topbar_dropdown_item_color ){
        $theme_options_styles .= '
          .dropdown-menu a{
            color: ' . $topbar_dropdown_item_color . ' !important;
          }
        ';
      }

      $jumbotron_bg_color = $wheniwasbad_options['jumbotron_bg_color'];
      if ( $jumbotron_bg_color ) {
        $theme_options_styles .= '
        .jumbotron {
          background-color: '. $jumbotron_bg_color . ';
        }';
      }
/*
      $suppress_comments_message = $wheniwasbad_options['suppress_comments_message'];
      if ( $suppress_comments_message ){
        $theme_options_styles .= '
        #main article {
          border-bottom: none;
        }';
      }
 */
      $additional_css = $wheniwasbad_options['wpbs_css'];
      if( $additional_css ){
        $theme_options_styles .= '
		'.$additional_css;
      }

      if( $theme_options_styles ){
		  // ensure consistent line endings
		  $s = str_replace("\r\n", "\n", $theme_options_styles);
		  $s = str_replace("\r", "\n", $s);
		  // Don't allow out-of-control blank lines
		  $s = preg_replace("/\n{2,}/", "\n\n", $s);
		  echo $s;
      }
/* bootswatch themes were turned off for now in preparation for Bootstrap v3
      $bootstrap_theme = $wheniwasbad_options['wpbs_theme'];
      $use_theme = $wheniwasbad_options['showhidden_themes'];

      if( $bootstrap_theme && $use_theme ){
        if( $bootstrap_theme == 'default' ){}
        else {
          echo '@include("' . get_template_directory_uri() . '/admin/themes/' . $bootstrap_theme . '.css")';
        }
      }
*/
?>
</style>