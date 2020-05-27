<?php
/* Welcome to Bones :)
This is the core Bones file where most of the
main functions & features reside. If you have
any custom functions, it's best to put them
in the functions.php file.

Developed by: Eddie Machado
URL: http://themble.com/bones/
*/

/*********************
LAUNCH BONES
Let's fire off all the functions
and tools. I put it up here so it's
right up top and clean.
*********************/

// we're firing all out initial functions at the start
add_action( 'after_setup_theme', 'bones_ahoy', 16 );

function bones_ahoy() {

	// launching operation cleanup
	add_action( 'init', 'bones_head_cleanup' );
	// remove WP version from RSS
	add_filter( 'the_generator', 'bones_rss_version' );
	// remove pesky injected css for recent comments widget
	add_filter( 'wp_head', 'bones_remove_wp_widget_recent_comments_style', 1 );
	// clean up comment styles in the head
	add_action( 'wp_head', 'bones_remove_recent_comments_style', 1 );
	// clean up gallery output in wp
	add_filter( 'gallery_style', 'bones_gallery_style' );

	// enqueue base scripts and styles
	add_action( 'wp_enqueue_scripts', 'bones_scripts_and_styles', 999 );
	// ie conditional wrapper

	// launching this stuff after theme setup
	bones_theme_support();

	// adding sidebars to Wordpress (these are created in functions.php)
//	add_action( 'widgets_init', 'bones_register_sidebars' );
	// adding the bones search form (created in functions.php)
	add_filter( 'get_search_form', 'bones_wpsearch' );


} /* end bones ahoy */

//add_filter( 'jpeg_quality', create_function( '', 'return 100;' ) );

add_action('admin_menu','remove_default_post_type');

function remove_default_post_type() {
	remove_menu_page('edit.php');
}

/*********************
WP_HEAD GOODNESS
The default wordpress head is
a mess. Let's clean it up by
removing all the junk we don't
need.
*********************/

function bones_head_cleanup() {
	// category feeds
	// remove_action( 'wp_head', 'feed_links_extra', 3 );
	// post and comment feeds
	// remove_action( 'wp_head', 'feed_links', 2 );
	// EditURI link
	remove_action( 'wp_head', 'rsd_link' );
	// windows live writer
	remove_action( 'wp_head', 'wlwmanifest_link' );
	// index link
	remove_action( 'wp_head', 'index_rel_link' );
	// previous link
	remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
	// start link
	remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
	// links for adjacent posts
	remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
	// WP version
	remove_action( 'wp_head', 'wp_generator' );
	// remove WP version from css
	add_filter( 'style_loader_src', 'bones_remove_wp_ver_css_js', 9999 );
	// remove Wp version from scripts
	add_filter( 'script_loader_src', 'bones_remove_wp_ver_css_js', 9999 );

} /* end bones head cleanup */

// remove WP version from RSS
function bones_rss_version() { return ''; }

// remove WP version from scripts
function bones_remove_wp_ver_css_js( $src ) {
	if ( strpos( $src, 'ver=' ) )
		$src = remove_query_arg( 'ver', $src );
	return $src;
}

// remove injected CSS for recent comments widget
function bones_remove_wp_widget_recent_comments_style() {
	if ( has_filter( 'wp_head', 'wp_widget_recent_comments_style' ) ) {
		remove_filter( 'wp_head', 'wp_widget_recent_comments_style' );
	}
}

// remove injected CSS from recent comments widget
function bones_remove_recent_comments_style() {
	global $wp_widget_factory;
	if (isset($wp_widget_factory->widgets['WP_Widget_Recent_Comments'])) {
		remove_action( 'wp_head', array($wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style') );
	}
}

// remove injected CSS from gallery
function bones_gallery_style($css) {
	return preg_replace( "!<style type='text/css'>(.*?)</style>!s", '', $css );
}


/*********************
SCRIPTS & ENQUEUEING
*********************/

// loading modernizr and jquery, and reply script
function bones_scripts_and_styles() {
	global $wp_styles; // call global $wp_styles variable to add conditional wrapper around ie stylesheet the WordPress way
	if (!is_admin()) {

		// modernizr (without media query polyfill) and masonry
		wp_register_script( 'bones-modernizr', '//cdnjs.cloudflare.com/ajax/libs/modernizr/2.7.1/modernizr.dev.min.js', array(), '2.7.1', false );
		wp_register_script( 'bones-masonry', '//cdnjs.cloudflare.com/ajax/libs/masonry/3.1.2/masonry.pkgd.min.js', array(), '3.1.2', false );
		wp_register_script( 'bones-imagesloaded', '//cdnjs.cloudflare.com/ajax/libs/jquery.imagesloaded/3.0.4/jquery.imagesloaded.min.js', array(), '3.0.4', false );
		wp_register_script( 'bones-underscore', '//cdnjs.cloudflare.com/ajax/libs/underscore.js/1.6.0/underscore-min.js', array(), '1.6.0', false ); 
		wp_register_script( 'bones-backbone', '//cdnjs.cloudflare.com/ajax/libs/backbone.js/1.1.2/backbone-min.js', array(), '1.1.2', false ); 
		

		// register main stylesheet
		wp_register_style( 'bones-stylesheet', get_stylesheet_directory_uri() . '/library/css/style.css', array(), '', 'all' );
		wp_register_style( 'bones-fullpage', get_stylesheet_directory_uri() . '/library/css/jquery.fullPage277.css', array(), '', 'all' );
		wp_register_style( 'bones-genericons', get_stylesheet_directory_uri() . '/library/css/genericons.css', array(), '', 'all' );

		// ie-only style sheet
		wp_register_style( 'bones-ie-only', get_stylesheet_directory_uri() . '/library/css/ie.css', array(), '' );

		wp_deregister_script('jquery');
		wp_register_script( 'jquery', '//ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js', array(), '2.2.0', false );
		wp_enqueue_script( 'jquery' );
		wp_register_style( 'jqueryui', '//ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css', array(), '1.11.4', 'all' );
		wp_register_script( 'jqueryui', '//ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js', array(), '1.11.4', false );
	//	wp_enqueue_script( 'jqueryui' );
	//	wp_enqueue_style( 'jqueryui' );

		//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js

		//adding scripts file in the footer 
		wp_register_script( 'bones-js', get_stylesheet_directory_uri() . '/library/js/scripts.js', array( 'jquery' ), '', true );
		wp_register_script( 'bones-fullpage', get_stylesheet_directory_uri() . '/library/js/libs/jquery.fullPage277.js', array( 'jquery' ), '', true );
		wp_register_script( 'bones-unveil', get_stylesheet_directory_uri() . '/library/js/libs/jquery.unveil.min.js', array('jquery'), '', false );

		// enqueue styles and scripts
		wp_enqueue_script( 'bones-modernizr' );
		wp_enqueue_script( 'bones-masonry' );
		wp_enqueue_script( 'bones-imagesloaded' );
		wp_enqueue_script( 'bones-unveil' );
		wp_enqueue_script( 'bones-underscore' );
		wp_enqueue_script( 'bones-backbone' );
		wp_enqueue_style( 'bones-stylesheet' );
		wp_enqueue_style( 'bones-fullpage' );
		wp_enqueue_style( 'bones-genericons' );
		wp_enqueue_style( 'bones-ie-only' );

		$wp_styles->add_data( 'bones-ie-only', 'conditional', 'lt IE 9' ); // add conditional wrapper around ie stylesheet

		wp_enqueue_script( 'bones-js' );
		wp_enqueue_script( 'bones-fullpage' );

	}
}

/*********************
THEME SUPPORT
*********************/

// Adding WP 3+ Functions & Theme Support
function bones_theme_support() {

	// wp thumbnails (sizes handled in functions.php)
	add_theme_support( 'post-thumbnails', array( 'post', 'page', 'article', 'series' ));

	// default thumb size
	set_post_thumbnail_size(125, 125, true);

	// rss thingy
	add_theme_support('automatic-feed-links');

	// to add header image support go here: http://themble.com/support/adding-header-background-image-support/

	// wp menus
} /* end bones theme support */

/*********************
PAGE NAVI
*********************/

function bones_series_query( $query ) {
	if (!is_admin() && $query->is_main_query()){
		if (is_home()){
			$query->set('posts_per_page', -1);
			$query->set('post_type', 'series');
			$query->set('orderby', 'menu_order');
			$query->set('order', 'ASC');
		}
	}
}
add_action( 'pre_get_posts', 'bones_series_query' );

// Numeric Page Navi (built into the theme by default)
function bones_page_navi() {
	global $wp_query;
	$bignum = 999999999;
	if ( $wp_query->max_num_pages <= 1 )
		return;
	
	echo '<nav class="pagination">';
	
		echo paginate_links( array(
			'base' 			=> str_replace( $bignum, '%#%', esc_url( get_pagenum_link($bignum) ) ),
			'format' 		=> '',
			'current' 		=> max( 1, get_query_var('paged') ),
			'total' 		=> $wp_query->max_num_pages,
			'prev_text' 	=> '&larr;',
			'next_text' 	=> '&rarr;',
			'type'			=> 'list',
			'end_size'		=> 3,
			'mid_size'		=> 3
		) );
	
	echo '</nav>';
} /* end page navi */

?>
