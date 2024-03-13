<?php if (has_nav_menu('footer')) : ?>
    <?php // pt_debug('footer-nav'); ?>
    <?php
    wp_nav_menu(
        array(
            'theme_location'    => 'footer',
            'walker'            => new Wt_Walker_Nav_Menu(),
            'menu_class'        => 'navbar-nav',
            'fallback_cb'       => false,
            'container'         => false
        )
    );
    ?>
<?php endif; ?>