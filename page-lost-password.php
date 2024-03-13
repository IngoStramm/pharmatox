<?php

/**
 * Template Name: Lost Password Page
 * 
 * The template for Lost Password page
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package WordPress
 * @subpackage Pharmatox
 */

get_header();

/* Start the Loop */
while (have_posts()) :
    the_post();
    get_template_part('template-parts/content/login/content-lost-password-page');

endwhile; // End of the loop.

get_footer();
