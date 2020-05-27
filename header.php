<!doctype html>

<!--[if lt IE 7]><html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8 lt-ie7"><![endif]-->
<!--[if (IE 7)&!(IEMobile)]><html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8"><![endif]-->
<!--[if (IE 8)&!(IEMobile)]><html <?php language_attributes(); ?> class="no-js lt-ie9"><![endif]-->
<!--[if gt IE 8]><!--> <html <?php language_attributes(); ?> class="no-js"><!--<![endif]-->

	<head>
		<meta charset="utf-8">

		<?php // Google Chrome Frame for IE ?>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

		<title><?php echo get_bloginfo( 'name' ); ?><?php echo wp_title('•'); ?></title>

		<?php // mobile meta (hooray!) ?>
		<meta name="HandheldFriendly" content="True">
		<meta name="MobileOptimized" content="320">
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>

		<?php // icons & favicons (for more: http://www.jonathantneal.com/blog/understand-the-favicon/) ?>
		<link rel="apple-touch-icon-precomposed" href="<?php echo get_template_directory_uri(); ?>/library/images/apple-touch-icon.png" />
		
		<link rel="icon" href="<?php echo get_template_directory_uri(); ?>/library/images/favicon.png">
		<!--[if IE]>
			<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico">
		<![endif]-->
		<?php // or, set /favicon.ico for IE10 win ?>
		<meta name="msapplication-TileColor" content="#ffffff">
		<meta name="msapplication-TileImage" content="<?php echo get_template_directory_uri(); ?>/library/images/largetile.png">
	    <meta name="msapplication-square70x70logo" content="<?php echo get_template_directory_uri(); ?>/library/images/smalltile.png" />
	    <meta name="msapplication-square150x150logo" content="<?php echo get_template_directory_uri(); ?>/library/images/mediumtile.png" />
	    <meta name="msapplication-square310x310logo" content="<?php echo get_template_directory_uri(); ?>/library/images/largetile.png" />

		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">

		<?php // wordpress head functions ?>
		<?php wp_head(); ?>
		<?php // end of wordpress head ?>

		<?php // drop Google Analytics Here ?>
		<?php // end analytics ?>
</head>

	<body <?php body_class(); ?>>

		<div id="container">

			<header class="header" role="banner">

				<div id="inner-header" class="wrap clearfix">
					<div id="backbutton" class="headerbutton"><a href="<?php echo home_url(); ?>">Back to index</a></div>
					<div id="logo"><a href="<?php echo home_url(); ?>" rel="nofollow"><img src="<?php echo get_template_directory_uri(); ?>/library/images/ThomasBrun_logo.png" alt="" /></a></div>
					<div id="infobutton" class="headerbutton"><span id="infospan">About</span><span class="genericon genericon-close-alt"></span></div>
				</div>
				<div id="inner-info" class="wrap superclearfix"><div id="info-container">								
					<div id="info-left" class="sevencol first">
						<div id="info-big"><?php the_field('big_text', 2); ?></div>
						<div class="info-separator"></div>
						<div id="info-contact" class="superclearfix">
							<div class="contact fourcol first"><?php the_field('contact', 2); ?></div>
							<div class="contact fourcol"><?php the_field('address', 2); ?></div>
							<div class="contact fourcol last"><!--<span class="genericon genericon-facebook-alt first"></span><span class="genericon genericon-twitter"></span><span class="genericon genericon-instagram last"></span>--><?php the_field('social', 2); ?></div>
						</div>
						<div class="info-separator"></div>
						<div id="info-content"><?php the_field('right_text', 2); ?></div>
					</div>
					<div id="info-right" class="fivecol last"><img src="<?php the_field('about_photo', 2); ?>"></div>
				</div></div>

			</header>
