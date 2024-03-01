<?php
/*
 * Plugin Name: CollectionSpace
 * Plugin URI:  https://github.com/collectionspace/wp-collectionspace
 * Description: Add a CollectionSpace collections browser to your site.
 * Version:     dev
 * License:     ECL-2.0
 * Update URI:  https://github.com/collectionspace/wp-collectionspace
 */

class CollectionSpace {
	const POST_TYPE = 'collectionspace';
	const POST_TYPE_SLUG = 'collection';

	private static $hooks_initialized = false;

	public static function init() {
		register_post_type(self::POST_TYPE,	array(
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
			'rewrite' => array('slug' => self::POST_TYPE_SLUG),
		));

		self::add_rewrite_rules();

		if (!self::$hooks_initialized) {
			self::init_hooks();
		}
	}

	private static function init_hooks() {
		self::$hooks_initialized = true;

		add_filter('single_template', array('CollectionSpace', 'single_template'));
		add_filter('request', array('CollectionSpace', 'request'));
		add_filter('body_class', array('CollectionSpace', 'body_class'));
	}

	public static function single_template($single_template) {
		if (get_post_type() == self::POST_TYPE) {
			return (dirname(__FILE__) . '/views/' . self::POST_TYPE . '.php');
		}

		return $single_template;
	}

	public static function add_rewrite_rules() {
		$posts = get_posts(array(
			'post_type'=> self::POST_TYPE,
			'posts_per_page' => -1,
		));

		if ($posts) {
			foreach ($posts as $post) {
				$base_url = home_url() . '/';
				$base_url_len = strlen($base_url);
				$post_url = get_permalink($post->ID);

				if (substr($post_url, 0, $base_url_len) == $base_url) {
					$path = substr($post_url, $base_url_len);

					add_rewrite_rule($path  . '.+', 'index.php?post_type=' . self::POST_TYPE . '&name=' . $post->post_name, 'top');
				}
			}
		}

		flush_rewrite_rules();
	}

	public static function request($qvars) {
		if (($qvars['post_type'] ?? '') == self::POST_TYPE) {
			foreach(array_keys($qvars) as $key) {
				if ($key != 'name' && $key != 'post_type') {
					unset($qvars[$key]);
				}
			}
		}

		return $qvars;
	}

	public static function get_browser_script_url() {
		return get_post_meta(get_the_ID(), 'script location', true);
	}

	public static function get_browser_config() {
		$config = get_post_meta(get_the_ID(), 'config', true);

		// Configure the basename.

		$path = parse_url(get_permalink(get_the_ID()), PHP_URL_PATH);

		if (substr($path, -1, 1) == '/') {
			$path = substr($path, 0, -1);
		}

		$config = preg_replace('/^\s*{/', "{\n  \"basename\": \"$path\",", $config, 1);

		return $config;
	}

	public static function body_class($classes) {
		$post = get_post();

		if (get_post_type() == self::POST_TYPE) {
			if (($key = array_search('has-sidebar', $classes)) !== false) {
				unset($classes[$key]);
			}
		}

		return $classes;
	}
}

add_action('init', array('CollectionSpace', 'init'));
