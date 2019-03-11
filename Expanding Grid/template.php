<?php

wp_enqueue_script('expanding-grid');

$content = $grid = $bgimg = '';
$classes = array('section', 'expanding-grid');
$uniqid = uniqid('expanding-grid-');

if (get_sub_field('background') && get_sub_field('background') !== 'white'){
	if (get_sub_field('background') === 'img' && get_sub_field('background_image')){
		$classes[] = 'dark-overlay';
		$bgimg = ' '.zen_bg_srcset(get_sub_field('background_image'), 'large', 'hero');
	}

	$classes[] = 'bg-'.get_sub_field('background');
}

if (get_sub_field('title')){
	$content .= '<h2 class="section-header text-center">'.esc_html(get_sub_field('title')).'</h2>';
}

$checked = ' checked';
foreach(get_sub_field('grid') as $item){
	$item_id = uniqid('grid-');

	$label = '<figure class="bigger one-color icon">'.zen_inline_if_svg($item['image'], 'medium').'</figure><span class="h4 moving-caret">'.esc_html($item['title']).'</span>';
	if (isset($item['subtitle']) && $item['subtitle']){
		$label .= '<span class="subtitle h5 alt-color">'.esc_html($item['subtitle']).'</span>';
	}

	$grid .= '<li class="text-center">
		<input type="radio" name="'.$uniqid.'" id="'.$item_id.'" class="toggler"'.$checked.' />
		<label for="'.$item_id.'" class="toggler">'.$label.'</label>
		<aside class="toggle-target column-wrap x2">'.
			wp_kses($item['expanded_content'], 'post').'
		</aside>
	</li>';
	$checked = '';
}

if ($grid){
	$col_count = 3;
	if (get_sub_field('columns') === '4'){ $col_count = 4; }

	$mod = count(get_sub_field('grid')) % $col_count;
	if ($mod){
		$grid .= str_repeat('<li class="spacer"></li>', $col_count - $mod);
	}

	$content .= '<ul class="no-bullets horiz x'.$col_count.' no-grow">'.$grid.'</ul>';
}

if ($content){
	echo '<section class="'.implode(' ', $classes).'"'.$bgimg.'>'.
			'<div class="content-width">'.$content.'</div>'.
		'</section>';
}

?>
