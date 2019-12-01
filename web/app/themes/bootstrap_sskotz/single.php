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

$context         = Timber::context();
$timber_post     = Timber::query_post();
$context['post'] = $timber_post;

if ($context['post']->post_type == 'characters') {
	$context['post'] = get_fields($context['post']);
	$legendary = [];
	$solar = [];
	$lunar = [];
	$star = [];
	$cosmos = [];
	$character = [
		'character_name' => $context['post']['character_name'],
		'character_rarity' => $context['post']['character_rarity'],
		'character_pv' => $context['post']['character_pv'],
		'character_pv_rank' => $context['post']['character_pv_rank'],
		'character_atq_f' => $context['post']['character_atq_f'],
		'character_atq_f_rank' => $context['post']['character_atq_f_rank'],
		'character_atq_c' => $context['post']['character_atq_c'],
		'character_atq_c_rank' => $context['post']['character_atq_c_rank'],
		'character_def_f' => $context['post']['character_def_f'],
		'character_def_f_rank' => $context['post']['character_def_f_rank'],
		'character_def_c' => $context['post']['character_def_c'],
		'character_def_c_rank' => $context['post']['character_def_c_rank'],
		'character_speed' => $context['post']['character_speed'],
		'character_speed_rank' => $context['post']['character_speed_rank'],
		'character_avatar' => $context['post']['character_avatar'],
	];
	foreach ($context['post']['character_cosmo_legendary'] as $cosmo) {
		$legendary[] = get_fields($cosmo->ID);
	}
	foreach ($context['post']['character_cosmo_solar'] as $cosmo) {
		$solar[] = get_fields($cosmo->ID);
	}
	foreach ($context['post']['character_cosmo_lunar'] as $cosmo) {
		$lunar[] = get_fields($cosmo->ID);
	}
	foreach ($context['post']['character_cosmo_star'] as $cosmo) {
		$star[] = get_fields($cosmo->ID);
	}


	$skills = get_fields($context['post']['character_skills']->ID);
	unset($skills['skill_qnt']);
	$cosmos['legendary'] = array_merge($legendary);
	$cosmos['solar'] = array_merge($solar);
	$cosmos['lunar'] = array_merge($lunar);
	$cosmos['star'] = array_merge($star);

	$character['cosmos'] = array_merge($cosmos);
	$character['skills'] = array_merge($skills);
	$context['post'] = $character;
} else if ($context['post']->post_type == 'post') {
	$context['cats'] = get_the_category($context['post']->ID);
}

if (post_password_required($timber_post->ID)) {
	Timber::render('single-password.twig', $context);
} else {
	Timber::render(array('single-' . $timber_post->ID . '.twig', 'single-' . $timber_post->post_type . '.twig', 'single-' . $timber_post->slug . '.twig', 'single.twig'), $context);
}
