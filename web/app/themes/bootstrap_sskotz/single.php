<?php

/**
 * The Template for displaying all single posts
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since    Timber 0.1
 */


define('ROLE_MAP', [
	'administrator' => 'Administrador',
	'author' => 'Autor',
	'contributor' => 'Contribuidor',
	'editor' => 'Editor',
	'subscriber' => 'Assinante'
]);
$context         = Timber::context();
$timber_post     = Timber::query_post();
$context['post'] = $timber_post;

/**
 * Returns extra info about a given user
 *
 * @param [type] $person
 * @return array
 */

function getAuthorPost($author)
{
	$person = get_userdata($author->ID);
	$user = [
		'name' => $person->display_name,
		'avatar' => get_avatar_url($person->ID),
		'roles' => getUserRolesAsText($person),
		'link' => get_author_posts_url($person->ID),
	];
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
 * Returns post of type 'character' with custom fields and organized (only needed)
 *
 * @param [type] $query
 * @return array
 */

function getPostCharacter($query)
{
	$post = get_fields($query);
	$legendary = [];
	$solar = [];
	$lunar = [];
	$star = [];
	$cosmos = [];
	$character = [
		'character_name' => $post['character_name'],
		'character_rarity' => $post['character_rarity'],
		'character_pv' => $post['character_pv'],
		'character_pv_rank' => $post['character_pv_rank'],
		'character_atq_f' => $post['character_atq_f'],
		'character_atq_f_rank' => $post['character_atq_f_rank'],
		'character_atq_c' => $post['character_atq_c'],
		'character_atq_c_rank' => $post['character_atq_c_rank'],
		'character_def_f' => $post['character_def_f'],
		'character_def_f_rank' => $post['character_def_f_rank'],
		'character_def_c' => $post['character_def_c'],
		'character_def_c_rank' => $post['character_def_c_rank'],
		'character_speed' => $post['character_speed'],
		'character_speed_rank' => $post['character_speed_rank'],
		'character_avatar' => $post['character_avatar'],
	];
	$count = 0;
	foreach ($post['character_cosmo_legendary'] as $cosmo) {
		$legendary[$count] = get_fields($cosmo->ID);
		$legendary[$count]['link'] = get_permalink($post['character_cosmo_legendary'][$count]->ID);
		$count++;
	}
	$count = 0;
	foreach ($post['character_cosmo_solar'] as $cosmo) {
		$solar[$count] = get_fields($cosmo->ID);
		$solar[$count]['link'] = get_permalink($post['character_cosmo_solar'][$count]->ID);
		$count++;
	}
	$count = 0;
	foreach ($post['character_cosmo_lunar'] as $cosmo) {
		$lunar[$count] = get_fields($cosmo->ID);
		$lunar[$count]['link'] = get_permalink($post['character_cosmo_lunar'][$count]->ID);
		$count++;
	}
	$count = 0;
	foreach ($post['character_cosmo_star'] as $cosmo) {
		$star[$count] = get_fields($cosmo->ID);
		$star[$count]['link'] = get_permalink($post['character_cosmo_star'][$count]->ID);
		$count++;
	}
	$skills = get_fields($post['character_skills']->ID);
	unset($skills['skill_qnt']);
	$cosmos['legendary'] = array_merge($legendary);
	$cosmos['solar'] = array_merge($solar);
	$cosmos['lunar'] = array_merge($lunar);
	$cosmos['star'] = array_merge($star);
	$character['cosmos'] = array_merge($cosmos);
	$character['skills'] = array_merge($skills);
	return $character;
}

/**
 * Returns post of type 'cosmo' with custom fields and organized (only needed)
 *
 * @param [type] $query
 * @return array
 */

function getPostCosmo($query)
{
	$data = get_fields($query->ID);
	$cosmo = [
		'cosmo_name' => $data["cosmo_name"],
		'cosmo_bonus' => $data["cosmo_bonus"],
		'cosmo_qntstatus' => $data["cosmo_qntstatus"],
		'cosmo_img' => $data["cosmo_img"]['url'],
		'cosmo_type' => get_the_terms($query->ID, 'cosmo_type')[0]->slug,
		'cosmo_link' => get_permalink($query->ID),
		'cosmo_drop_location' => $data['cosmo_drop_location'],
	];
	$drop_days = $data['cosmo_drop_days'];
	$days = "( ";
	foreach ($drop_days as $day) {
		$days = $days . $day . ', ';
	}
	$days = rtrim($days, ', ');
	$days .= " )";
	$cosmo['cosmo_drop_days'] = $days;
	$cosmo['cosmo_status1_tipo'] = $data['cosmo_status1']['tipo'];
	$cosmo['cosmo_status1_max'] = $data['cosmo_status1']['max'];
	if ($data['cosmo_qntstatus'] > 1) {
		$cosmo['cosmo_status2_tipo'] = $data['cosmo_status2']['tipo'];
		$cosmo['cosmo_status2_max'] = $data['cosmo_status2']['max'];
	}
	return $cosmo;
}

// Verifcar qual é o post type da publicação
if ($context['post']->post_type == 'characters') {
	// Caso seja 'chracters', buscar campo personalizados e filtrar
	$context['post'] = getPostCharacter($context['post']);
} else if ($context['post']->post_type == 'post') {
	// Caso seja 'post', buscar campo personalizados e filtrar
	$context['author'] = getAuthorPost($context['post']->author);
	$context['cats'] = get_the_category($context['post']->ID);
} else if ($context['post']->post_type == 'cosmos') {
	// Caso seja 'cosmo', buscar campo personalizados e filtrar
	$context['post'] = getPostCosmo($context['post']);
}

if (post_password_required($timber_post->ID)) {
	Timber::render('single-password.twig', $context);
} else {
	Timber::render(array('single-' . $timber_post->ID . '.twig', 'single-' . $timber_post->post_type . '.twig', 'single-' . $timber_post->slug . '.twig', 'single.twig'), $context);
}
