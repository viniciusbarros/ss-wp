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
$timber_post = new Timber\Post();
// Pegar o parametro do URL

$archive = str_replace('/', '', $_SERVER['REQUEST_URI']);
$posts = [];

// Verifica o post type
if ($timber_post->post_type == 'cosmos') {
	// Caso seja 'cosmos', buscar todos os posts com post type cosmos

	$cosmos = [];
	$args = array(
		'post_type' => 'cosmos',
		'post_satus' => 'publish',
		'numberposts' => -1
	);
	// Buscar todos os posts
	$context['cosmos'] = Timber::get_posts($args);

	// Filtrar os campos dos posts buscados
	foreach ($context['cosmos'] as $cosmo) {
		$data = get_fields($cosmo->ID);
		$info = [
			'cosmo_name' => $data["cosmo_name"],
			'cosmo_bonus' => $data["cosmo_bonus"],
			'cosmo_qntstatus' => $data["cosmo_qntstatus"],
			'cosmo_img' => $data["cosmo_img"]['url'],
			'cosmo_type' => get_the_terms($cosmo->ID, 'cosmo_type')[0]->slug,
			'cosmo_link' => get_permalink($cosmo->ID),
			'cosmo_status1_tipo' => $data['cosmo_status1']['tipo'],
			'cosmo_status1_max' => $data['cosmo_status1']['max'],
		];
		if ($data['cosmo_qntstatus'] > 1) {
			$info['cosmo_status2_tipo'] = $data['cosmo_status2']['tipo'];
			$info['cosmo_status2_max'] = $data['cosmo_status2']['max'];
		}
		$posts[] = array_merge($info);
	};
} else if ($timber_post->post_type == 'characters') {
	// Caso seja 'characters', buscar todos os posts com post type characters
	$args = array(
		'post_type' => 'characters',
		'post_satus' => 'publish',
		'numberposts' => -1
	);
	// Buscar todos os posts
	$context['post'] = Timber::get_posts($args);

	// Filtrar os campos dos posts buscados
	foreach ($context['post'] as $saint) {
		$data = get_fields($saint->ID);
		$info = [
			'character_name' => $data["character_name"],
			'character_rarity' => $data["character_rarity"],
			'character_thumb' => $data["character_thumb"]["url"],
			'character_tier' => $data["character_tier"],
			'character_link' => get_permalink($saint->ID),
		];
		$posts[] = array_merge($info);
	};
} else {
	// Caso não seja nenhum dos outros, verificar o parametro passado
	if ($archive != 'news') {
		// Se o parametro não for 'news', buscar a subcategoria
		$category = get_the_category();
		//Verificar se a subcategoria está vázia
		if (!empty($category)) {
			//Caso não esteja, buscar todas as publicaçãoes dessa subcategoria
			$context['categorypage'] = $category[0]->name;
			$args = array(
				'post_type' => 'post',
				'post_satus' => 'publish',
				'posts_per_page' => 15,
				'orderby' => 'publish_date',
				'cat' => $category[0]->cat_ID,
			);
		}
	} else {
		// Se o parametro for 'news', buscar todas as publicações	
		$context['categorypage'] = 'Publicações';
		$args = array(
			'post_type' => 'post',
			'post_satus' => 'publish',
			'posts_per_page' => 15,
			'orderby' => 'publish_date',
		);
	}
	// Verificar se a variavel args está vázio
	if (!empty($args)) {
		// Caso não esteja, filtrar os campos
		$context['news'] = new Timber\PostQuery($args);
		foreach ($context['posts'] as $post) {
			$data = get_fields($post->ID);
			$public = [
				'post_title' => $post->post_title,
				'post_img' => $data["post_thumb"]['url'],
				'post_author_name' => get_the_author_meta($post->post_author),
				'post_author_avatar' => get_avatar_url($post->post_author),
				'post_category' => get_the_category($post->ID),
				'post_desc' => mb_strimwidth($data["post_desc"], 0, 100, "..."),
				'post_date' => date("d/m/Y H:i:s", strtotime($post->post_date_gmt)),
				'post_link' => get_permalink($post->ID),
			];
			$posts[] = array_merge($public);
		}
	}
}

$context['posts'] = $posts;

Timber::render(array('archive-' . $timber_post->post_type . '.twig', 'archive.twig'), $context);
