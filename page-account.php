<?php

/**
 * Template Name: Página Minha Conta
 * 
 * The template for User Account Page
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package WordPress
 * @subpackage Pharmatox
 */

get_header();

do_action('account_announces');

/* Start the Loop */
while (have_posts()) :
    the_post();
    get_template_part('template-parts/content/account/content-account-page');

endwhile; // End of the loop.

get_footer();
