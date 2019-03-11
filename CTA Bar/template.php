<?php

$content = '';

if (get_sub_field('title')){
	$content .= '<h2>'.esc_html(get_sub_field('title')).'</h2>';
}

if ($cta = get_sub_field('cta')){
	$content .= '<p><a href="'.esc_url($cta['url']).'" class="button secondary">'.esc_html($cta['title']).'</a></p>';
}


if ($content){
	echo '<section class="cta-bar bg-highlight text-center">'.
			'<div class="content-width horiz centered margins-off">'.$content.'</div>'.
		'</section>';
}

?>
