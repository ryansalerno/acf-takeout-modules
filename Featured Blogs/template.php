<?php

// edit this number as you like
$post_count = 3;

// init
$found_posts = 0;
$args = array(
	'post_type' => 'post',
	'posts_per_page' => $post_count,
	'ignore_sticky_posts' => 1,
);

// check for manually chosen posts (returned as IDs) that should come first
$stuck_ids = get_sub_field('sticky_posts');
if ($stuck_ids){
	$args['post__in'] = $stuck_ids; // just get the posts in question
	$args['orderby'] = 'post__in'; // and order them implicitly
	$sticky_posts = new WP_Query($args);

	$found_posts = $sticky_posts->post_count;

	// clean up the $args we just added
	unset($args['post__in']);
	unset($args['orderby']);

	// make sure future queries get the right number...
	$args['posts_per_page'] = $post_count - $found_posts;
	// ...and don't include the one(s) we just grabbed
	$args['post__not_in'] = $stuck_ids;
}

// this will run whether there were stuck posts or not...
if ($found_posts < $post_count){
	// overload the query with explicit category if one has been chosen
	if (get_sub_field('rest') === 'category'){
		$cat = get_sub_field('category');
		if ($cat){
			$args['cat'] = $cat;
		}
	}
	$extra_posts = new WP_Query($args);

	if ($found_posts){
		// if we have both stuck posts and queried posts, combine their contents into one new shell of a WP_Query
		$featured_blogs = new WP_Query;
		$featured_blogs->posts = array_merge($sticky_posts->posts, $extra_posts->posts);
		$featured_blogs->post_count = count($featured_blogs->posts);
	} else {
		// cool, we're all done
		$featured_blogs = $extra_posts;
	}
} else {
	// ...unless the set number of posts were all explicitly chosen, in which case we're done
	$featured_blogs = $sticky_posts;
}

// now the posts are all set up for normal loop() usage, so output what you need
$content = array();
if ( $featured_blogs->have_posts() ) {
	while ( $featured_blogs->have_posts() ) : $featured_blogs->the_post();

		$content[] = '<li><a class="featured-blog fake-button" href="'.get_the_permalink().'" title="'.esc_attr(get_the_title()).'" rel="bookmark">'.
			'<h3>'.esc_html(get_the_title()).'</h3>'.
			'<p>'.get_the_date().' <span class="moving-caret">Read More</span></p>'.
		'</a></li>';

	endwhile;
	wp_reset_postdata();
}

if ($content){
	$classes = array('section', 'featured-blogs', 'content-width');

	$title = get_sub_field('title');
	if (!$title){ $title = 'What\'s New?'; }

	$maybe_subtitle = '';
	if (get_sub_field('subtitle')){
		$maybe_subtitle = '<h4 class="text-left subtitle alt-color">'.esc_html(get_sub_field('subtitle')).'</h4>';
	}

	$cta = get_sub_field('cta');
	if (!isset($cta['title']) || !isset($cta['url']) || !$cta['title'] || !$cta['url']){
		$cta = array(
			'title' => 'See All Blogs',
			'url' => get_permalink(get_option('page_for_posts')),
		);
	}

	echo '<section class="'.implode(' ', $classes).'">'.
			'<h2 class="text-left section-header">'.esc_html($title).'</h2>'.
			$maybe_subtitle.
			'<ul class="no-bullets">'.implode('',$content).'<li><a href="'.esc_url($cta['url']).'" class="button">'.esc_html($cta['title']).'</a></li></ul>'.
		'</section>';
}

?>
