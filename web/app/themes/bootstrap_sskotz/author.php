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

define('ROLE_MAP', [
	'administrator' => 'Administrador',
	'author' => 'Autor',
	'contributor' => 'Contribuidor',
	'editor' => 'Editor',
	'subscriber' => 'Assinante',
	'design' => 'Designer',
	'developer' => 'Desenvolvedor',
	'publisher' => 'Publicador',
	'streamer' => 'Streamer'
]);

$context          = Timber::context();
$context['posts'] = new Timber\PostQuery();
$posts = [];

/**
 * Returns extra info about a given user
 *
 * @param [type] $person
 * @return array
 */

function getUserInfo($person)
{
	$user = [];
	foreach (['discord', 'twitter', 'facebook'] as $socialMedia) {
		$media = get_user_meta($person->ID, $socialMedia);
		if (isset($media[0]) && !empty($media[0])) {
			$user[$socialMedia] = $media[0];
		}
	}

	$live = get_user_meta($person->ID, 'live');

	if (isset($live[0]) && !empty($live[0])) {
		$user['live'] = $live[0];
		if (strpos($live[0], 'twitch') != '') {
			$user['plataform_live'] = 'twitch';
		} else if (strpos($live[0], 'youtube') != '') {
			$user['plataform_live'] = 'youtube';
		} else if (strpos($live[0], 'facebook') != '') {
			$user['plataform_live'] = 'facebook';
		} else {
			$user['plataform_live'] = 'other';
		}
	}

	return $user;
}

/**
 * Returns all roles as text
 *
 * @param [type] $user
 * @return string
 */


function getUserRolesAsText($user)
{
	$roles = [];
	foreach ($user->roles as $role) {
		$roles[] = ROLE_MAP[$role];
	}

	return implode(", ", $roles);
}

/**
 * Returns post of type 'post' with custom fields and organized (only needed)
 *
 * @param [type] $query
 * @return string
 */

function getPostsAuthor($query)
{
	if(!empty($query)){
		foreach ($query as $post) {
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
		return $posts;
	}
}

if (isset($wp_query->query_vars['author'])) {
	$author = new Timber\User($wp_query->query_vars['author']);
	$person = get_userdata($author->ID);
	$user = [
		'name' => $person->display_name,
		'avatar' => get_avatar_url($person->ID),
		'role' => getUserRolesAsText($person)
	];

	$extraInfo = getUserInfo($person);

	$context['author'] = array_merge($user, $extraInfo);
	$context['posts'] = getPostsAuthor($context['posts']);
}

Timber::render(array('author.twig', 'archive.twig'), $context);
