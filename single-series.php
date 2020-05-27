<?php
/*
This is the custom post type post template.
If you edit the post type name, you've got
to change the name of this template to
reflect that name change.

i.e. if your custom post type is called
register_post_type( 'bookmarks',
then your single template should be
single-bookmarks.php

*/
?>

<?php get_header(); ?>

			<div id="content">

				<div id="inner-content" class="wrap clearfix">

						<div id="main" class="clearfix singlepaged" role="main">
							<?php function bones_single_series_post($query){
								if ($query->have_posts()) : while ($query->have_posts()) : $query->the_post();
								// Get text fields
								$client = get_field('client');

							
							?>
								<article id="post-<?php the_ID(); ?>" <?php post_class( 'clearfix section' ); ?> slug="<?php echo $query->post->post_name; ?>" role="article" title="<?php htmlentities(the_title()); ?>">
									<section class="entry-content clearfix">
										<h1><?php the_title(); ?></h1>
										<p><?php echo $client; ?></p>
									</section>
									<section class="next-link clearfix" onclick="$.fn.fullpage.moveSectionDown();">
										<p>Next project</p>
										<span class="genericon genericon-expand"></span>
									</section>
								<?php
								// If video, show first
								if (get_field("video-active")):
								?>
									<section class="video slide clearfix">
										<div class="innersection">
										<?php
											// get iframe HTML
											$iframe = get_field('video');
											$iframe = do_shortcode($iframe);

											preg_match('/src="(.+?)"/', $iframe, $matches);
											$src = $matches[1];
											
											// add extra params to iframe src
											$params = array(
												// Youtube-specific
											    'hd' => 1,
											    'autoplay' => 0,
											    'disablekb' => 1,
											    'enablejsapi' => 1,
											    'iv_load_policy' => 3,
											    'modestbranding' => 0,
											    'origin' => $_SERVER['SERVER_NAME'],
											    'playerapiid' => 'video-'.get_the_ID(),
											    'playsinline' => 1,
											    'rel' => 0,
											    'showinfo' => 0,
											    'theme' => 'light',
											    // Vimeo-specific
											    'badge' => 0,
											    'byline' => 0,
											    'player_id' => 'video-'.get_the_ID(),
											    'portrait' => 0,
											    'title' => 0
											);
											
											$new_src = add_query_arg($params, $src);
											$iframe = str_replace($src, $new_src, $iframe);
											
											$iframe = str_replace('src="', 'data="', $iframe);

											// add extra attributes to iframe html
											$attributes = 'frameborder="0"';
											$iframe = str_replace('></iframe>', ' ' . $attributes . '></iframe>', $iframe);
											// echo $iframe
											echo $iframe;
										 ?>
										</div>
									</section>
								<?php
								endif;
									
								// Iterate photos, creating <article> elements
								$photos = get_field('photos');
								shuffle($photos);
								if ($photos):
									foreach($photos as $photo):
										$photo = $photo['photo'];
								?>
									<section class="photo slide clearfix">
										<div class="innersection"><figure>
										<?php
										$image = '<img src="'. get_template_directory_uri() .'/library/images/transparent.gif" class="lazy resizefix" data-src="'. $photo['sizes']['bones-thumb-1920'] .
												'" data-src-retina="'. $photo['sizes']['bones-thumb-2560'] .
												'" data-src-mobile="'. $photo['sizes']['bones-thumb-456'] .
												'" data-src-mobile-retina="'. $photo['sizes']['bones-thumb-912'] .
												'" width="'. $photo['sizes']['bones-thumb-1920-width'] .
												'" height="'. $photo['sizes']['bones-thumb-1920-height'] .'" >';
										 echo $image;
										 ?>
									     </figure></div>
									</section>
									<?php endforeach; endif; ?>
								</article>

							<?php endwhile; endif; ?>
							<?php } ?>

							<?php 
							// Load _all_ series, move requested to front
							bones_single_series_post($wp_query);		
							$series = new WP_Query(array(
								'post_type' => 'series',
								'orderby' => 'menu_order',
								'order' => 'ASC',
								'posts_per_page' => -1,
								'post__not_in' => array($wp_query->queried_object_id)));
							bones_single_series_post($series);		
							?>
								
							
							<?php bones_page_navi(); ?>

						</div>

				</div>

			</div>

<?php get_footer(); ?>
