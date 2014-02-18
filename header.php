<?php
/*
* header.php
* inserts document header information and the page header/top nav to every page
*/
?>
<?php global $wheniwasbad_options; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<!--[if lt IE 7]><html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if (IE 7)&!(IEMobile)]><html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8"><![endif]-->
<!--[if (IE 8)&!(IEMobile)]><html <?php language_attributes(); ?> class="no-js lt-ie9"><![endif]-->
<!--[if gt IE 8]><!--> <html <?php language_attributes(); ?> class="no-js"><!--<![endif]-->
	<head>
		<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
		<title><?php bloginfo('name'); ?><?php is_front_page() ? bloginfo('description') : wp_title('|'); ?></title>

		<!-- icons & favicons -->

		<!-- Opera Speed Dial Favicon -->
		  <link rel="icon" type="image/png" href="<?php echo get_template_directory_uri(); ?>/library/images/icons/speeddial-160px.png" />

		<!-- For Apple displays: -->
		<link rel="apple-touch-icon" href="<?php echo get_template_directory_uri(); ?>/library/images/icons/touch-icon-iphone.png">
		<link rel="apple-touch-icon" sizes="76x76" href="<?php echo get_template_directory_uri(); ?>/library/images/icons/touch-icon-ipad.png">
		<link rel="apple-touch-icon" sizes="120x120" href="<?php echo get_template_directory_uri(); ?>/library/images/icons/touch-icon-iphone-retina.png">
		<link rel="apple-touch-icon" sizes="152x152" href="<?php echo get_template_directory_uri(); ?>/library/images/icons/touch-icon-ipad-retina.png">

		<!-- For Nokia -->
		<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/library/images/icons/touch-icon-iphone.png">
		<!-- For everything else -->

		<?php $favicon_url = ($wheniwasbad_options['favicon_url']!='') ? $wheniwasbad_options['favicon_url'] : get_template_directory_uri() . '/library/images/icons/favicon.png'; ?>
		<link rel="icon" type="image/png" href="<?php echo $favicon_url; ?>">

		<!-- or, set /favicon.ico for IE10 win -->
		<meta name="msapplication-TileColor" content="#f01d4f">

  		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">

		<!-- WordPress head functions -->
		<?php wp_head(); ?>
	</head>

	<?php
	$nav_position = $wheniwasbad_options['nav_position'];
	$navbar_style = $wheniwasbad_options['nav_style'];

	$navheader_class='navbar-default ';

	if ($navbar_style_inverted)
		$navheader_class = 'navbar-inverted ';

	switch ($nav_position) {
		case 'fixed':
			$navheader_class .= 'navbar-fixed-top';
			$body_style = 'navbar-fixed-offset';
			break;
		case 'fixed-bottom':
			$navheader_class .= 'navbar-fixed-bottom';
			$body_style = 'navbar-no-offset';
			break;
		case 'scroll':
		default:
			$navheader_class .= 'navbar-static-top';
			$body_style = 'navbar-no-offset';
	}

	if ($wheniwasbad_options['header_bg_image'] != '') {
		$navheader_styles = 'style="background-image: url(\'' . $wheniwasbad_options['header_bg_image'] . '\');"';
	} else {
		$navheader_styles = 'style="background-image: url(\'' . get_template_directory_uri() . '/library/theme/images/header_bg.gif' . '\');"';
	}
	?>

	<body <?php body_class($body_style); ?> data-navpos="<?php echo $nav_position; ?>">

		<header class="navbar <?php echo $navheader_class; ?> clearfix" role="banner" <?php echo $navheader_styles; ?>>

			<div class="container" style="position: relative;">

				<div class="row" style="margin-bottom: 0;">

				<?php if ( $wheniwasbad_options['search_bar'] ) : ?>

					<div class="col-sm-8 col-md-10">

				<?php else : ?>

					<div class="col-xs-12">

				<?php endif; ?>

					<?php if ( has_nav_menu('service_nav') ) : ?>

						<div class="navbar-header pull-left">

						    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-service-collapse">
						      <span class="sr-only">Toggle navigation</span>
						      <span class="icon-bar"></span>
						      <span class="icon-bar"></span>
						      <span class="icon-bar"></span>
						    </button>

						</div>

						<?php wp_nav_menu( array(
					    		'menu' => 'service_nav',
					    		'menu_class' => 'nav navbar-nav',
					    		'menu_id' => 'service-nav-menu',
					    		'theme_location' => 'service_nav', /* where in the theme it's assigned */
					    		'depth' => 1,
					    		'container' => 'nav',
								'container_class'   => 'collapse navbar-collapse navbar-service-collapse pull-left',
					    		'container_id' => 'service-nav',
					    		'fallback_cb' => 'wp_bootstrap_navwalker::fallback', /* menu fallback */
					    		'walker' => new wp_bootstrap_navwalker()
						)); ?>

					<?php endif; ?>

					<?php if ( ($wheniwasbad_options['branding_logo'] && $wheniwasbad_options['branding_logo']['url']) || ($wheniwasbad_options['site_name'] && get_bloginfo()) ): ?>

					    <a class="navbar-brand" id="logo" title="<?php echo get_bloginfo('description'); ?>" href="<?php echo home_url(); ?>">

							<?php if($wheniwasbad_options['branding_logo'] && $wheniwasbad_options['branding_logo']['url']) : ?>

								<img id="branding-logo" src="<?php echo $wheniwasbad_options['branding_logo']['url']; ?>" alt="<?php echo get_bloginfo('description'); ?>" />

							<?php else: ?>

								<img id="branding-logo" src="<?php echo get_template_directory_uri() . '/library/theme/images/gcdi_logo-circle.svg'; ?>" alt="<?php echo get_bloginfo('description'); ?>" />

							<?php endif; ?>

							<?php if($wheniwasbad_options['site_name'] && get_bloginfo()) bloginfo('name'); ?>

						</a>

					<?php endif; ?>

					</div>

				<?php if ( $wheniwasbad_options['search_bar'] ) : ?>

					<div class="col-xs-6 col-sm-4 col-md-2">
						<form class="navbar-form navbar-right" role="search" method="get" id="searchform" action="<?php echo home_url( '/' ); ?>">
							<div class="input-group input-group-sm">
								<span class="input-group-btn">
									<button class="btn btn-default" type="button"><span class="glyphicon glyphicon-search"></span></button>
								</span>
								<input name="s" id="s" type="text" class="form-control" autocomplete="off" placeholder="<?php _e('Search','gcwordpress'); ?>" >
							</div>
						</form>
					</div>

				<?php endif; ?>

					<div id="main-nav-container">

						<?php wp_nav_menu( array(
					    		'menu' => 'main_nav',
					    		'menu_class' => 'nav navbar-nav',
					    		'menu_id' => 'main-nav-menu',
					    		'theme_location' => 'main_nav', /* where in the theme it's assigned */
					    		'depth' => 2, /* Bootstrap 3.0 doesn't support additional depths */
					    		'container' => 'nav',
								'container_class'   => 'collapse navbar-collapse navbar-main-collapse',
					    		'container_id' => 'main-nav',
					    		'fallback_cb' => 'wp_bootstrap_navwalker::fallback', /* menu fallback */
					    		'walker' => new wp_bootstrap_navwalker()
						)); ?>

					    <div class="navbar-header">
						    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-main-collapse">
						      <span class="sr-only">Toggle navigation</span>
						      <span class="icon-bar bg-orange"></span>
						      <span class="icon-bar bg-green"></span>
						      <span class="icon-bar bg-blue-light"></span>
						    </button>
						  </div>

					</div>

					<a title="The Graduate Center, City University of New York" href="http://www.gc.cuny.edu">
						<img id="sitewide-logo" src="<?php echo get_template_directory_uri() . '/library/theme/images/GC_logo-white.svg'; ?>" alt="The CUNY Graduate Center" />
					</a>

				</div>

			</div> <!-- end container -->

		</header> <!-- end header -->
