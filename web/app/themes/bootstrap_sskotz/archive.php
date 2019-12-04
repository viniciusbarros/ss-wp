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

/**
 * Returns all posts of type 'cosmos' with custom fields and organized (only needed)
 *
 * @return array
 */

function getArchiveCosmos()
{
	$cosmos = [];
	$args = array(
		'post_type' => 'cosmos',
		'post_satus' => 'publish',
		'numberposts' => -1
	);
	$cosmos_posts = Timber::get_posts($args);
	foreach ($cosmos_posts as $cosmo) {
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
		$cosmos[] = array_merge($info);
	}
	return $cosmos;
}

/**
 * Returns all posts of type 'cosmos' with custom fields and organized (only needed)
 *
 * @return array
 */

function getArchiveCharacters()
{
	$characters = [];
	$args = array(
		'post_type' => 'characters',
		'post_satus' => 'publish',
		'numberposts' => -1
	);
	$characters_posts = Timber::get_posts($args);
	foreach ($characters_posts as $saint) {
		$data = get_fields($saint->ID);
		$info = [
			'character_name' => $data["character_name"],
			'character_rarity' => $data["character_rarity"],
			'character_thumb' => $data["character_thumb"]["url"],
			'character_tier' => $data["character_tier"],
			'character_link' => get_permalink($saint->ID),
		];
		$characters[] = array_merge($info);
	};
	return $characters;
}

/**
 * Returns category of archives
 *
 * @param [type] $archive
 * @return array
 */

function getArchiveCategoryPost($archive)
{
	if ($archive != 'news') {
		$category = get_the_category();
		if (!empty($category)) {
			return $category[0]->name;
		}
	} else {
		return 'Publicações';
	}
}

/**
 * Returns post of category or subcategory given
 * @param [type] $query
 * @return array
 */

function getArchivePosts($archive)
{
	$posts = [];
	if ($archive != 'news') {
		$category = get_the_category();
		if (!empty($category)) {
			$args = array(
				'post_type' => 'post',
				'post_satus' => 'publish',
				'posts_per_page' => 15,
				'orderby' => 'publish_date',
				'cat' => $category[0]->cat_ID,
			);
		}
	} else {
		$args = array(
			'post_type' => 'post',
			'post_satus' => 'publish',
			'posts_per_page' => 15,
			'orderby' => 'publish_date',
		);
	}
	if (!empty($args)) {
		$context['news'] = new Timber\PostQuery($args);
		foreach ($context['news'] as $post) {
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
	return $posts;
}

// Verifica o post type
if ($timber_post->post_type == 'cosmos') {
	// Caso seja 'cosmos', buscar todos os posts com post type cosmos
	$context['posts'] = getArchiveCosmos();
} else if ($timber_post->post_type == 'characters') {
	// Caso seja 'characters', buscar todos os posts com post type characters
	$context['posts'] = getArchiveCharacters();
} else {
	// Caso não seja nenhum dos outros, verificar o parametro passado
	$context['posts'] = getArchivePosts($archive);
	$context['categorypage'] = getArchiveCategoryPost($archive);
}

Timber::render(array('archive-' . $timber_post->post_type . '.twig', 'archive.twig'), $context);
