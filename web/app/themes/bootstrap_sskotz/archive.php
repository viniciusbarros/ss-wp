<?php

/**
 * The template for displaying Archive pages.
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since   Timber 0.2
 */

$templates = array('archive.twig', 'index.twig');
$context = Timber::context();
$posts = [];
global $paged;
if (!isset($paged) || !$paged) {
	$paged = 1;
}
$tag = str_replace("/", "", $_SERVER["REQUEST_URI"]);
if ($tag != "news") {
	$args = array(
		'post_type' => 'post',
		'post_satus' => 'publish',
		'posts_per_page' => 15,
		'orderby' => 'publish_date',
		'tag' => $tag,
		'paged' => $paged
	);
} else {
	$args = array(
		'post_type' => 'post',
		'post_satus' => 'publish',
		'posts_per_page' => 15,
		'orderby' => 'publish_date',
		'paged' => $paged
	);
}

$context['posts'] = new Timber\PostQuery($args);

foreach ($context['posts'] as $post) {
	$data = get_fields($post->ID);
	$public = [
		'post_title' => $post->post_title,
		'post_img' => $data["post_thumb"]['url'],
		'post_author_name' => get_the_author_meta($post->post_author),
		'post_author_avatar' => get_avatar_url($post->post_author),
		'post_tag' => get_the_tags($post->ID),
		'post_desc' => mb_strimwidth($data["post_desc"], 0, 100, "..."),
		'post_date' => date("d/m/Y H:i:s", strtotime($post->post_date_gmt)),
		'post_link' => get_permalink($post->ID),
	];
	$posts[] = array_merge($public);
}
$context['posts'] = $posts;

Timber::render($templates, $context);
