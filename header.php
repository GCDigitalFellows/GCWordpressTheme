<?php
/*
* header.php
* adds header information to every page
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
			
		<!-- Standard Favicon -->
		  <link rel="icon" type="image/x-icon" href="/favicon.ico" />

		<!-- For iPhone 4 Retina display: -->
		  <link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo get_template_directory_uri(); ?>/library/images/icons/apple-touch-icon-114x114-precomposed.png">

		<!-- For iPad: -->
		  <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo get_template_directory_uri(); ?>/library/images/icons/apple-touch-icon-72x72-precomposed.png">

		<!-- For iPhone: -->
		  <link rel="apple-touch-icon-precomposed" href="<?php echo get_template_directory_uri(); ?>/library/images/icons/apple-touch-icon-57x57-precomposed.png">




		<!-- For iPhone 4 -->
		<link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo get_template_directory_uri(); ?>/library/images/icons/h/apple-touch-icon.png">
		<!-- For iPad 1-->
		<link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo get_template_directory_uri(); ?>/library/images/icons/m/apple-touch-icon.png">
		<!-- For iPhone 3G, iPod Touch and Android -->
		<link rel="apple-touch-icon-precomposed" href="<?php echo get_template_directory_uri(); ?>/library/images/icons/l/apple-touch-icon-precomposed.png">
		<!-- For Nokia -->
		<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/library/images/icons/l/apple-touch-icon.png">
		<!-- For everything else -->
		
		<?php $favicon_url = ($wheniwasbad_options['favicon_url']!='') ? $wheniwasbad_options['favicon_url'] : get_template_directory_uri() . '/favicon.ico'; ?>
		<link rel="shortcut icon" href="<?php echo $favicon_url; ?>">
		
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
	?>
		
	<body <?php body_class($body_style); ?>>
				
		<header class="navbar <?php echo $navheader_class; ?>" role="banner">
			<div class="container">
				<div class="row">
				<div class="pull-right">
					<a class="brand" id="logo" title="The Graduate Center, City University of New York" href="http://www.gc.cuny.edu">
						<img src="http://www.gc.cuny.edu/shared/images/shared/CUNY-GC-logo.png" />
					</a>
				</div>
					<div class="col-md-10">
						<div class="row">
							<div class="col-md-9">
								<div class="navbar-header pull-left">
								    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-service-collapse">
								      <span class="sr-only">Toggle navigation</span>
								      <span class="icon-bar"></span>
								      <span class="icon-bar"></span>
								      <span class="icon-bar"></span>
								    </button>
								  </div>
								<?php 
								    wp_nav_menu( array(
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
								    ) );
							    ?>
							</div>
							<div class="col-md-3">
								<form class="navbar-form navbar-right" role="search" method="get" id="searchform" action="<?php echo home_url( '/' ); ?>">
									<div class="input-group input-group-sm">
										<span class="input-group-btn">
											<button class="btn btn-default" type="button"><span class="glyphicon glyphicon-search"></span></button>
										</span>
										<input name="s" id="s" type="text" class="form-control" autocomplete="off" placeholder="<?php _e('Search','gcwordpress'); ?>" >
									</div>
								</form>
							</div>
						</div> <!-- top nav row -->
						<div class="row">
							<div class="col-md-12">
								<div class="navbar-header">
								    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-main-collapse">
								      <span class="sr-only">Toggle navigation</span>
								      <span class="icon-bar"></span>
								      <span class="icon-bar"></span>
								      <span class="icon-bar"></span>
								    </button>
								  </div>
								<?php 
								    wp_nav_menu( array(
							    		'menu' => 'main_nav',
							    		'menu_class' => 'nav navbar-nav',
							    		'menu_id' => 'main-nav-menu',
							    		'theme_location' => 'main_nav', /* where in the theme it's assigned */
							    		'depth' => 2, /* Bootstrap 3.0 doesn't support additional depths */
							    		'container' => 'nav',
										'container_class'   => 'collapse navbar-collapse navbar-main-collapse pull-right',
							    		'container_id' => 'main-nav',
							    		'fallback_cb' => 'wp_bootstrap_navwalker::fallback', /* menu fallback */
							    		'walker' => new wp_bootstrap_navwalker()
								    ) );
							    ?>
							</div>
						</div>
					</div><!-- main nav row -->
					
				</div> 
					
			</div> <!-- end container -->		
		</header> <!-- end header -->
