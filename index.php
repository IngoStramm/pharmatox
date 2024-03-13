<?php

/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Pharmatox
 */

get_header(); ?>

<div class="container">
    <div class="row">
        <div class="col">
            <?php do_action('create_pdf_messages'); ?>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col">
            <header class="page-header alignwide">
                <?php echo pt_breadcrumbs('relatorios'); ?>
            </header><!-- .page-header -->
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col">
            <header class="page-header alignwide">
                <?php get_template_part('template-parts/sort-filters-form'); ?>
            </header><!-- .page-header -->
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <?php if (have_posts()) { ?>
            <?php $post_type = get_post_type(); ?>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col"><?php _e('#', 'pt') ?></th>
                            <th scope="col"><?php _e('Médico', 'pt') ?></th>
                            <th scope="col"><?php _e('Paciente', 'pt') ?></th>
                            <th scope="col"><?php _e('Fornecedor', 'pt') ?></th>
                            <th scope="col"><?php _e('Data', 'pt') ?></th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while (have_posts()) {
                            the_post();
                            get_template_part('template-parts/content/content', null, array('post_id' => get_the_ID()));
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        <?php } else { ?>
            <div class="col">
                <?php get_template_part('template-parts/content/content-none'); ?>
            </div>
        <?php } ?>
        <?php pt_paging_nav(); ?>
    </div>
</div>

<?php
get_footer();
