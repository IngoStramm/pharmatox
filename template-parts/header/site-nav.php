<?php if (has_nav_menu('primary')) : ?>
    <?php
    wp_nav_menu(
        array(
            'theme_location'    => 'primary',
            'walker'            => new Wt_Walker_Nav_Menu(),
            'menu_class'        => 'navbar-nav me-auto mb-2 mb-lg-0',
            'fallback_cb'       => false,
            'container'         => false
        )
    );
    ?>
<?php endif; ?>