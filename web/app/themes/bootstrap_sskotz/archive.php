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

$paginated_posts = [];
$pagination_links = '';
$post_type = '';
define('COSMO_POST_TYPE', 'cosmos');
define('CHARACTER_POST_TYPE', 'characters');
$templates = array('archive.twig', 'index.twig');
$context = Timber::context();
$uri = $_SERVER['REQUEST_URI'];

if (have_posts()){
	// Start the Loop.
	while (have_posts()){
		the_post();
		$current_post = (Array) get_post();
		$custom_fields = get_fields($current_post['ID']) ?: [];
		$extra = [];

		switch ($current_post['post_type']){
			case COSMO_POST_TYPE:
				$extra = [
					'cosmo_type' => get_the_terms($current_post['ID'], 'cosmo_type')[0]->slug,
					'cosmo_link' => get_permalink($current_post['ID'])
				];
				break;
			case CHARACTER_POST_TYPE:
				// Nothing speciall here so far
				break;
			default:
				$context['categorypage'] = 'News';
				if(strpos($uri, 'news') < 0) {
					$category = get_the_category();
					$context['categorypage'] = !empty($category) ? $category[0]->name : 'Publicações';
				}
				
				// echo '<pre>';
				// print_r($current_post); die;
				$extra = [
					'post_author_name' => get_the_author_meta($current_post['post_author']),
					'post_author_avatar' => get_avatar_url($current_post['post_author']),
					'post_category' => get_the_category($current_post['ID']),
					'post_desc' => isset($custom_fields["post_desc"]) ? mb_strimwidth($custom_fields["post_desc"], 0, 100, "...") : '',
					'post_date' => date("d/m/Y H:i:s", strtotime($current_post['post_date_gmt']))
				];

				break;	
		}
		$paginated_posts[] = array_merge($current_post, $custom_fields, $extra);

	}

	$pagination_links = get_the_posts_pagination(
		array(
			'mid_size'  => 2,
			'prev_text' => sprintf(
				'%s <span class="nav-prev-text">%s</span>',
				'<',
				'Mais Recentes'
			),
			'next_text' => sprintf(
				'<span class="nav-next-text">%s</span> %s',
				'>',
				'Mais Antigos'
			),
		)
	);
}

$context['posts'] = $paginated_posts;
$context['next_page_links'] = $pagination_links;

Timber::render(array('archive-' . $paginated_posts[0]['post_type'] . '.twig', 'archive.twig'), $context);