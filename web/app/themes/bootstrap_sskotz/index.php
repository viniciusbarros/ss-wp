<?php

/**
 * The main template file
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since   Timber 0.1
 */

$context          = Timber::context();
$context['posts'] = new Timber\PostQuery();

// Arrays para cada postagem
$publics = [];
$events = [];
$activitys = [];
$banners = [];
$atts = [];
$characters = [];
$team = [];

// Buscar postagens para aba de ultimos personagens lançados
$context['character'] = Timber::get_posts([
	'post_type' => 'characters',
	'post_satus' => 'publish',
	'numberposts' => 4,
	'orderby' => 'publish_date',
]);

// Filtrar os campos dos posts buscados
foreach ($context['character'] as $post) {
	$data = get_fields($post->ID);
	$saint = [
		'character_name' => $post->character_name,
		'character_thumb' => $data['character_thumb']['url'],
		'character_link' => get_the_permalink($post->ID),
	];
	$characters[] = array_merge($saint);
}

// Buscar todas as novidades recentes
$context['public'] = Timber::get_posts([
	'post_type' => 'post',
	'post_satus' => 'publish',
	'numberposts' => 3,
	'orderby' => 'publish_date',
]);

// Filtrar os campos dos posts buscados
foreach ($context['public'] as $post) {
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
	$publics[] = array_merge($public);
}

// Buscar todas os eventos recentes
$context['event'] = Timber::get_posts([
	'post_type' => 'post',
	'post_satus' => 'publish',
	'numberposts' => 3,
	'orderby' => 'publish_date',
	'category_name' => 'event'
]);

// Filtrar os campos dos posts buscados
foreach ($context['event'] as $post) {
	$data = get_fields($post->ID);
	$event = [
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
	$events[] = array_merge($event);
}

// Buscar todas as atividades recentes
$context['activity'] = Timber::get_posts([
	'post_type' => 'post',
	'post_satus' => 'publish',
	'numberposts' => 3,
	'orderby' => 'publish_date',
	'category_name' => 'activity'
]);

// Filtrar os campos dos posts buscados
foreach ($context['activity'] as $post) {
	$data = get_fields($post->ID);
	$activity = [
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
	$activitys[] = array_merge($activity);
}

// Buscar todos os banners recentes
$context['banner'] = Timber::get_posts([
	'post_type' => 'post',
	'post_satus' => 'publish',
	'numberposts' => 3,
	'orderby' => 'publish_date',
	'category_name' => 'banner'
]);

// Filtrar os campos dos posts buscados
foreach ($context['banner'] as $post) {
	$data = get_fields($post->ID);
	$banner = [
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
	$banners[] = array_merge($banner);
}

// Buscar todos as atualizações recentes
$context['atts'] = Timber::get_posts([
	'post_type' => 'post',
	'post_satus' => 'publish',
	'numberposts' => 3,
	'orderby' => 'publish_date',
	'category_name' => 'update'
]);

// Filtrar os campos dos posts buscados
foreach ($context['atts'] as $post) {
	$data = get_fields($post->ID);
	$att = [
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
	$atts[] = array_merge($att);
}

// Buscar os membros da equipe
$users = get_users([
	'post_per_page' => 6,
	'orderby' => 'rand',
]);

// Filtrar os campos dos posts buscados
foreach ($users as $user) {
	$person = get_userdata($user->ID);
	$caps = wp_roles();
	$roles = '';
	foreach ($person->roles as $role) {
		if ($role == 'administrator') {
			$roles = $roles . 'Administrador' . ', ';
		} else if ($role == 'author') {
			$roles = $roles . 'Autor' . ', ';
		} else if ($role == 'contributor') {
			$roles = $roles . 'Contribuidor' . ', ';
		} else if ($role == 'editor') {
			$roles = $roles . 'Editor' . ', ';
		} else if ($role == 'subscriber') {
			$roles = $roles . 'Assinante' . ', ';
		}
	}
	$roles = rtrim($roles, ', ');
	$user = [
		'name' => $person->name,
		'avatar' => get_avatar_url($person->ID),
		'role' => $roles,
	];
	$discord = get_user_meta($person->ID, 'discord');
	$twitter = get_user_meta($person->ID, 'twitter');
	$facebook = get_user_meta($person->ID, 'facebook');
	$live = get_user_meta($person->ID, 'live');
	if ($discord[0] != '') {
		$user['discord'] = $discord[0];
	}
	if ($facebook[0] != '') {
		$user['facebook'] = $facebook[0];
	}
	if ($twitter[0] != '') {
		$user['twitter'] = $twitter[0];
	}
	if ($live[0] != '') {
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
	$team[] = array_merge($user);
}


// Passando os arrays para dentro do context
$context['author'] = $team;
$context['public'] = $publics;
$context['event'] = $events;
$context['activity'] = $activitys;
$context['banner'] = $banners;
$context['att'] = $atts;
$context['character'] = $characters;
$context['team'] = $team;

$templates = array('index.twig');
Timber::render($templates, $context);
