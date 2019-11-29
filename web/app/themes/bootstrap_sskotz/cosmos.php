<?php

/**
 *
 * Template Name: Cosmos
 * 
 * @package  WordPress
 * @subpackage  Timber
 * @since   Timber 0.1
 * 
 */

$context          = Timber::context();
$timber_post     = new Timber\Post();
$args = array(
    'post_type' => 'cosmos',
    'post_satus' => 'publish',
    'numberposts' => -1
);

$context['post'] = Timber::get_posts($args);


foreach ($context['post'] as $cosmo) {
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
};
$context['cosmos'] = $cosmos;
$templates = array('cosmos.twig');
Timber::render($templates, $context);
