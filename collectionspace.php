<?php
/*
Plugin Name: CollectionSpace
Description: Integrate a CollectionSpace collections browser into your site.
License: ECL-2.0
*/

const POST_TYPE = 'collectionspace';
const POST_TYPE_SLUG = 'collection';

function init() {
	register_post_type(POST_TYPE,	array(
		'labels' => array(
			'name' => __('CollectionSpace Browsers'),
			'singular_name' => __('CollectionSpace Browser'),
		),
		'menu_icon' => plugins_url('images/icon.svg', __FILE__),
		'public' => true,
		'exclude_from_search' => true,
		'show_in_nav_menus' => true,
		'show_ui' => true,
		'supports' => array('title', 'custom-fields'),
		'rewrite' => array('slug' => POST_TYPE_SLUG),
	));
}

add_action('init', 'init');

function single_template($single_template) {
	if (get_post_type() == POST_TYPE) {
		return (dirname(__FILE__) . '/views/' . POST_TYPE . '.php');
	}

	return $single_template;
}

add_filter('single_template', 'single_template');

function save_post($post_id) {
	$posts = get_posts(array(
		'post_type'=> POST_TYPE,
		'posts_per_page' => -1,
	));

	if ($posts) {
		foreach ($posts as $post) {
			$base_url = home_url() . '/';
			$base_url_len = strlen($base_url);
			$post_url = get_permalink($post->ID);

			if (substr($post_url, 0, $base_url_len) == $base_url) {
				$path = substr($post_url, $base_url_len);

				add_rewrite_rule($path  . '.+', 'index.php?post_type=' . POST_TYPE . '&name=' . $post->post_name, 'top');
			}
		}
	}

	flush_rewrite_rules();
}

add_action('save_post_' . POST_TYPE, 'save_post' );

function request($qvars) {
	foreach(array_keys($qvars) as $key) {
		if ($key != 'name' && $key != 'post_type') {
			unset($qvars[$key]);
		}
	}

	return $qvars;
}

add_filter('request', 'request');
