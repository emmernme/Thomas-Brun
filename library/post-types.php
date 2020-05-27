<?php
/* Bones Custom Post Type Example
This page walks you through creating 
a custom post type and taxonomies. You
can edit this one or copy the following code 
to create another one. 

I put this in a separate file so as to 
keep it organized. I find it easier to edit
and change things if they are concentrated
in their own file.

Developed by: Eddie Machado
URL: http://themble.com/bones/
*/

// Flush rewrite rules for custom post types
add_action( 'after_switch_theme', 'bones_flush_rewrite_rules' );

// Flush your rewrite rules
function bones_flush_rewrite_rules() {
	flush_rewrite_rules();
}

// let's create the function for the custom type
function bones_series() { 
	// creating (registering) the custom type 
	register_post_type( 'series', /* (http://codex.wordpress.org/Function_Reference/register_post_type) */
		// let's now add all the options for this post type
		array( 'labels' => array(
			'name' => __( 'Serier', 'bonestheme' ), /* This is the Title of the Group */
			'singular_name' => __( 'Serie', 'bonestheme' ), /* This is the individual type */
			'all_items' => __( 'Alle serier', 'bonestheme' ), /* the all items menu item */
			'add_new' => __( 'Legg til ny', 'bonestheme' ), /* The add new menu item */
			'add_new_item' => __( 'Legg til ny serie', 'bonestheme' ), /* Add New Display Title */
			'edit' => __( 'Endre', 'bonestheme' ), /* Edit Dialog */
			'edit_item' => __( 'Endre serie', 'bonestheme' ), /* Edit Display Title */
			'new_item' => __( 'Ny serie', 'bonestheme' ), /* New Display Title */
			'view_item' => __( 'Se serie', 'bonestheme' ), /* View Display Title */
			'search_items' => __( 'Søk i serier', 'bonestheme' ), /* Search Custom Type Title */ 
			'not_found' =>  __( 'Ingenting funnet.', 'bonestheme' ), /* This displays if there are no entries yet */ 
			'not_found_in_trash' => __( 'Ingenting funnet i søpla', 'bonestheme' ), /* This displays if there is nothing in the trash */
			'parent_item_colon' => ''
			), /* end of arrays */
			'description' => __( 'Dette er alle bildeseriene. En serie kan kobles til flere bilder.', 'bonestheme' ), /* Custom Type Description */
			'public' => true,
			'publicly_queryable' => true,
			'exclude_from_search' => false,
			'show_ui' => true,
			'query_var' => true,
			'menu_position' => 8, /* this is what order you want it to appear in on the left hand side menu */ 
			'rewrite'	=> array( 'slug' => 'series', 'with_front' => false ), /* you can specify its url slug */
			'has_archive' => false, /* you can rename the slug here */
			'capability_type' => 'post',
			'hierarchical' => false,
			/* the next one is important, it tells what's enabled in the post editor */
			'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'trackbacks', 'custom-fields', 'comments', 'revisions', 'sticky')
		) /* end of options */
	); /* end of register post type */
	
	/* this adds your post categories to your custom post type */
//	register_taxonomy_for_object_type( 'category', 'series' );
	/* this adds your post tags to your custom post type */
//	register_taxonomy_for_object_type( 'post_tag', 'series' );
	
}

// adding the function to the Wordpress init
add_action( 'init', 'bones_series');
?>
