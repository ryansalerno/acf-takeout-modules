<?php

/*

Add this to custom-functions.php:

//======================================================================
// oEmbeds are easy, so let's make them complicated!
//======================================================================
// the intentionally terrible function names help (a little)
function ounembed($embed){
	preg_match('/src="(.+?)"/', $embed, $matches);
	if (isset($matches[1])){
		return $matches[1];
	}
	return '';
}
function deoembed_url_remaker($url){
	// https://www.youtube.com/embed/yRHwWGO_A48?feature=oembed
	if (strpos($url, 'youtube.com') !== false){
		return str_replace(array('embed/', '?feature=oembed'), array('watch?v=', ''), $url);
	}
	// https://player.vimeo.com/video/45105236?app_id=122963
	if (strpos($url, 'vimeo.com') !== false){
		preg_match('/video\/(\d+)\?/', $url, $id);
		if (isset($id[1])){ return $id[1]; }
	}
	return false;
}
function oembed_poster($url){
	// https://www.youtube.com/embed/yRHwWGO_A48?feature=oembed
	if (strpos($url, 'youtube.com') !== false){
		if ($id = strrchr($url, '/')){
			$id = substr(str_replace('?feature=oembed', '', $id), 1);
			$poster = 'https://i.ytimg.com/vi/'.$id.'/sddefault.jpg';
			$headers = @get_headers($poster);
			if (!$headers || strpos($headers[0], '404') !== false) {
				$poster = 'https://i.ytimg.com/vi/'.$id.'/hqdefault.jpg';
			}
			return $poster;
		}
	}
	// https://player.vimeo.com/video/45105236?app_id=122963
	if (strpos($url, 'vimeo.com') !== false){
		$json = file_get_contents('https://vimeo.com/api/oembed.json?width=800&url='.rawurlencode($url));
		if ($json){
			$response = json_decode($json);
			return $response->thumbnail_url;
		}
	}
	return false;
}
function clickable_oembed_linker_thing($link, $embed_url){
	return '<div class="videoholder clickable-embed"><a class="embed-content bg-img" style="background-image: url(\''.oembed_poster($embed_url).'\');" href="'.esc_url($link).'" data-oembed="'.$embed_url.'&autoplay=1"><figure class="play-button">'.zen_svg_icon('play-button', '').'</figure></a></div>';
}

*/

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

	if (isset($row['image']['type'])){
		if ($row['image']['type'] === 'video'){
			$embed_url = ounembed($row['image']['video']);
			$original_url = deoembed_url_remaker($embed_url);

			$img = clickable_oembed_linker_thing($original_url, $embed_url);
		} else if ($row['image']['type'] === 'image'){
			$img = '<figure>'.zen_inline_if_svg($row['image']['image'], 'large').'</figure>';
		}

		if (isset($img)){
			$back .= '<div class="back">'.$img.'</div>';
		}
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
