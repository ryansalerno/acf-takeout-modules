<?php

$content = $buttons = '';
$classes = array('section', 'basic-content', 'text-center');

if (get_sub_field('background') && get_sub_field('background') !== 'white'){
	$classes[] = 'bg-'.get_sub_field('background');
}

if (get_sub_field('title')){
	$content .= '<h2 class="section-header">'.esc_html(get_sub_field('title')).'</h2>';
}
if (get_sub_field('subtitle')){
	$content .= '<h4 class="alt-color">'.esc_html(get_sub_field('subtitle')).'</h4>';
}

$cols = array();
foreach ((array)get_sub_field('columns') as $column){
	$col = '';

	if (isset($column['icon']) && $column['icon']){
		$col .= '<figure class="biggest icon">'.zen_inline_if_svg($column['icon']).'</figure>';
	}

	if (isset($column['title']) && $column['title']){
		$col .= '<h4 class="alt-color">'.esc_html($column['title']).'</h4>';
	}

	if (isset($column['content']) && $column['content']){
		$col .= '<div class="post-content text-left">'.apply_filters('the_content', $column['content']).'</div>';
	}

	if ($col){
		$cols[] = '<div class="column">'.$col.'</div>';
	}
}

if ($cols){
	$col_classes = array();
	$col_count = count($cols);

	if ($col_count > 1){
		$col_classes[] = 'horiz centered';
		switch ($col_count) {
			case '2':
			case '3':
			case '4':
				$col_classes[] = 'x'.$col_count;
				break;

			default:
				$col_classes[] = 'many';
				break;
		}

		$content .= '<div class="'.implode(' ', $col_classes).'">'.implode('', $cols).'</div>';
	} else {
		$content .= $cols[0];
	}
}

if ($cta1 = get_sub_field('cta_primary', $page_id)){
	$buttons .= '<a href="'.esc_url($cta1['url']).'" class="button">'.esc_html($cta1['title']).'</a>';
}
if ($cta2 = get_sub_field('cta_secondary', $page_id)){
	$buttons .= '<a href="'.esc_url($cta2['url']).'" class="button secondary">'.esc_html($cta2['title']).'</a>';
}
if ($buttons){
	$content .= '<p class="button-group">'.$buttons.'</p>';
}


if ($content){
	echo '<section class="'.implode(' ', $classes).'">'.
			'<div class="content-width">'.$content.'</div>'.
		'</section>';
}

?>
