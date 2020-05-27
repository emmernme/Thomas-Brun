<?php get_header();
	
/*
* Isolated main loop
*
*
*/
function bones_front_loop($first){
	global $post;
	// Iterate photos, creating <article> elements
	if ($first){
		$featured = get_post_thumbnail_id();
		$photo = wp_get_attachment_image_src($featured, 'bones-thumb-456');
		$retina = wp_get_attachment_image_src($featured, 'bones-thumb-912');
		if (get_field("video-active")){
			$image = get_field('videothumbnail');
			if (!empty($image)){
				$featured = $image['id'];
				$photo[0] = $image['sizes']['bones-thumb-456'];
				$photo[1] = $image['sizes']['bones-thumb-456-width'];
				$photo[2] = $image['sizes']['bones-thumb-456-height'];
				$retina[0] = $image['sizes']['bones-thumb-912'];
			}
		}
	} else {
		$photos = get_field('photos');
		shuffle($photos);
		$featured = get_post_thumbnail_id();
		if ($photos[0]['photo']['id'] == $featured){
			array_shift($photos);
		}
		$photo = $photos[0];
		$photo = $photo['photo'];
	}
?><article id="photo-<?php echo ($first)?$featured:$photo['id']; ?>" <?php post_class( 'clearfix nomasonry grid '. (($first)?'first':'') ); ?> series="post-<?php echo $post->ID; ?>" onclick="document.location='<?php the_permalink() ?>'" role="article">
	<div class="inner">
		<div id="article-image"><?php
			if ($first){
				$image = '<img src="'. get_template_directory_uri() .'/library/images/transparent.gif" class="lazy" data-src="'. $photo[0] .
					'" data-src-retina="'. $retina[0] .
					'" width="'. $photo[1] .
					'" height="'. $photo[2] .'" />';
			} else {
				$image = '<img src="'. get_template_directory_uri() .'/library/images/transparent.gif" class="lazy" data-src="'. $photo['sizes']['bones-thumb-456'] .
					'" data-src-retina="'. $photo['sizes']['bones-thumb-912'] .
					'" width="'. $photo['sizes']['bones-thumb-456-width'] .
					'" height="'. $photo['sizes']['bones-thumb-456-height'] .'" />';
			}
			echo $image; 
		?></div>
		<section class="entry-content clearfix">
			<h1><?php the_title(); ?></h1>
			<p><?php the_field('client'); ?></p>
		</section>
	</div>
</article><?php } ?>

			<div id="content">
				<div id="inner-content" class="wrap clearfix">
						<div id="main" role="main"><div id="gutter"></div><?php
							// Run main loop twice
							if (have_posts()) : while (have_posts()) : the_post();
								bones_front_loop(true);
							endwhile; endif;
							if (have_posts()) : while (have_posts()) : the_post();
								bones_front_loop(false);
							endwhile;
						?></div><?php
						//bones_page_navi();
						endif;
				?></div>
			</div>
<?php get_footer(); ?>