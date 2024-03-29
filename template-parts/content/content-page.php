<?php

/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Pharmatox
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <div class="container">
        <div class="row">
            <div class="col">

                <div class="entry-content">
                    <?php the_content(); ?>
                </div><!-- .entry-content -->
                
            </div>
        </div>
    </div>

</article><!-- #post-<?php the_ID(); ?> -->