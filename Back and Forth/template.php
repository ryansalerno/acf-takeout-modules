<?php

$content = '';
$classes = array('section', 'back-and-forth');

if (get_sub_field('background') && get_sub_field('background') !== 'white'){
	$classes[] = 'bg-'.get_sub_field('background');
}

if (get_sub_field('title')){
	$content .= '<h2 class="section-header text-center">'.esc_html(get_sub_field('title')).'</h2>';
}
if (get_sub_field('subtitle')){
	$content .= '<h4 class="alt-color text-center">'.esc_html(get_sub_field('subtitle')).'</h4>';
}

foreach ((array)get_sub_field('rows') as $row){
	if (!isset($row['image']) || !isset($row['content'])){ continue; }

	$back = $forth = '';

	if (isset($row['image']['image'])){
		$img = '<figure>'.zen_inline_if_svg($row['image']['image'], 'large').'</figure>';
		$back .= '<div class="back">'.$img.'</div>';
	}

	if (isset($row['content']['title'])){
		$forth .= '<h3 class="section-header">'.esc_html($row['content']['title']).'</h3>';
	}

	if (isset($row['content']['content'])){
		$forth .= '<div class="post-content">'.wp_kses($row['content']['content'], 'post').'</div>';
	}

	if (isset($row['content']['cta']) && is_array($row['content']['cta']) && isset($row['content']['cta']['url'])){
		$forth .= '<p><a href="'.esc_url($row['content']['cta']['url']).'" class="button">'.esc_html($row['content']['cta']['title']).'</a></p>';
	}

	if ($back && $forth){
		$content .= '<div class="row horiz x2 no-padding">'.$back.'<div class="forth text-left">'.$forth.'</div></div>';
	}
}

if ($content){
	echo '<section class="'.implode(' ', $classes).'">'.
			'<div class="content-width">'.$content.'</div>'.
		'</section>';
}

?>
