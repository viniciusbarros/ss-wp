<?php

/**
 *
 * Template Name: Personagens
 * 
 * @package  WordPress
 * @subpackage  Timber
 * @since   Timber 0.1
 * 
 */

$context          = Timber::context();
$timber_post     = new Timber\Post();
$args = array(
    'post_type' => 'characters',
    'post_satus' => 'publish',
    'numberposts' => -1
);

$context['post'] = Timber::get_posts($args);
foreach ($context['post'] as $saint) {
    $data = get_fields($saint->ID);
    $info = [
        'character_name' => $data["character_name"],
        'character_rarity' => $data["character_rarity"],
        'character_thumb' => $data["character_thumb"]["url"],
        'character_tier' => $data["character_tier"],
        'character_link' => get_permalink($saint->ID),
    ];
    $saints[] = array_merge($info);
};
$context['characters'] = $saints;
$templates = array('characters.twig');
Timber::render($templates, $context);
