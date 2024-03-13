<?php

/**
 * The header.
 *
 * This is the template that displays all of the <head> section and everything up until main.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @subpackage Pharmatox
 */
?>

<!DOCTYPE html>
<html <?php language_attributes(); ?> <?php pt_the_html_classes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>

<body>
    <?php do_action('pt_modal'); ?>
    <main>
        <?php get_template_part('template-parts/header/site-header'); ?>