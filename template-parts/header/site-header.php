<?php
$wrapper_classes  = 'site-header mb-5 sticky-top';
$wrapper_classes .= has_custom_logo() ? ' has-logo' : '';
$wrapper_classes .= (true === get_theme_mod('display_title_and_tagline', true)) ? ' has-title-and-tagline' : '';
$wrapper_classes .= has_nav_menu('primary') ? ' has-menu' : '';
$account_page_id = pt_get_page_id('account');
$login_page_id = pt_get_page_id('login');
?>
<header class="<?php echo esc_attr($wrapper_classes); ?>">
    <nav class="navbar navbar-expand-md bg-body-tertiary">
        <div class="container">

            <?php get_template_part('template-parts/header/site-branding'); ?>

            <button class=" navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <?php do_action('pt_user_icon_notification') ?>
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">

                <?php get_template_part('template-parts/header/site-nav'); ?>

                <ul class="nav my-2 justify-content-between justify-content-md-center my-md-0 text-small align-items-center gap-1">

                    <?php if (is_user_logged_in()) { ?>
                        <?php $user = wp_get_current_user(); ?>
                        <?php if ($account_page_id) { ?>
                            <li>
                                <a href="<?php echo pt_get_page_url('account'); ?>" class="nav-link d-block text-center px-2">

                                    <i class="bi bi-person-circle d-block fs-3  nav-user-icon">
                                        <?php do_action('pt_user_icon_notification') ?>
                                    </i>
                                    <small class="d-block"><?php echo $user->display_name; ?></small>
                                </a>
                            </li>
                        <?php } ?>
                        <li>
                            <a class="nav-link d-block text-center px-2" href="<?php echo wp_logout_url(); ?>">
                                <i class="bi bi-box-arrow-left d-block fs-3"></i>
                                <small class="d-block"><?php _e('Sair', 'pt'); ?></small>
                            </a>
                        </li>
                    <?php } else { ?>
                        <?php if ($login_page_id) { ?>
                            <?php
                            $login_page_url = pt_get_page_url('login');
                            ?>
                            <li>
                                <div class="d-grid d-md-block">
                                    <a class="btn btn-secondary btn-sm" href="<?php echo $login_page_url; ?>"><?php _e('Entrar', 'pt'); ?></a>
                                </div>
                            </li>
                        <?php } ?>
                    <?php } ?>

                </ul>
            </div>
        </div>
    </nav>
</header>