<?php

/**
 * Template part for displaying a message that posts cannot be found
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Pharmatox
 */

?>
<div class="col">
    <header class="page-header alignwide">
        <?php echo pt_breadcrumbs('anuncios'); ?>
    </header><!-- .page-header -->
</div>

<div class="breakfix"></div>

<div class="col-md-4 col-lg-3 col-sm-12 mb-4">
    <?php if (is_author()) {
        get_sidebar('author');
    } else {
        get_sidebar();
    } ?>
</div>

<div class="col-md-8 col-lg-9 col-sm-12">
    <div class="row">
        <div class="col-md-12">
            <header class="page-header alignwide">
                <?php if (is_search()) : ?>

                    <h1 class="page-title">
                        <?php
                        printf(
                            /* translators: %s: Search term. */
                            esc_html__('Resultados para "%s"', 'pt'),
                            '<span class="page-description search-term">' . esc_html(get_search_query()) . '</span>'
                        );
                        ?>
                    </h1>

                <?php else : ?>

                    <h1 class="page-title"><?php esc_html_e('Nada aqui', 'pt'); ?></h1>

                <?php endif; ?>
            </header><!-- .page-header -->
        </div>
        <div class="col-md-12">
            <?php if (is_search()) : ?>

                <p><?php esc_html_e('Desculpe, mas nada corresponde aos seus termos de pesquisa.', 'pt'); ?></p>
                <?php // get_search_form(); ?>

            <?php else : ?>

                <p><?php esc_html_e('Parece que não conseguimos encontrar o que você procura.', 'pt'); ?></p>
                <?php // get_search_form(); ?>

            <?php endif; ?>
        </div>
    </div>
</div>