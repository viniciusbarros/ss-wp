<?php

/**
 * The template for displaying Author Archive pages
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since    Timber 0.1
 */

global $wp_query;

$context          = Timber::context();
$context['posts'] = new Timber\PostQuery();
$posts = [];
$user = [];
if (isset($wp_query->query_vars['author'])) {
	$author = new Timber\User($wp_query->query_vars['author']);
	$roles = '';
	foreach ($author->roles as $role) {
		$roles = $roles . $role . ', ';
	}
	$roles = rtrim($roles, ', ');
	$user = [
		'name' => $author->name,
		'avatar' => get_avatar_url($author->ID),
		'role' => $roles,
	];
	if ($author->discord != '') {
		$user['discord'] =  $author->discord;
	}
	if ($author->facebook != '') {
		$user['facebook'] = $author->facebook;
	}
	if ($author->twitter != '') {
		$user['twitter'] = $author->twitter;
	}
	if ($author->live != '') {
		$user['live'] = $author->live;
		if (strpos($author->live, 'twitch') != '') {
			$user['plataform_live'] = 'twitch';
		} else if (strpos($author->live, 'youtube') != '') {
			$user['plataform_live'] = 'youtube';
		} else if (strpos($author->live, 'facebook') != '') {
			$user['plataform_live'] = 'facebook';
		} else {
			$user['plataform_live'] = 'other';
		}
	}

	foreach ($context['posts'] as $post) {
		$data = get_fields($post->ID);
		$public = [
			'post_title' => $post->post_title,
			'post_img' => $data["post_thumb"]['url'],
			'post_author_name' => get_the_author_meta('display_name', $post->post_author),
			'post_author_avatar' => get_avatar_url($post->post_author),
			'post_author_link' => get_author_posts_url($post->post_author),
			'post_tag' => get_the_category($post->ID),
			'post_desc' => mb_strimwidth($data["post_desc"], 0, 100, "..."),
			'post_date' => date("d/m/Y H:i:s", strtotime($post->post_date_gmt)),
			'post_link' => get_permalink($post->ID),
		];
		$posts[] = array_merge($public);
	}
	$context['posts'] = $posts;
	$context['author'] = $user;
}

Timber::render(array('author.twig', 'archive.twig'), $context);
