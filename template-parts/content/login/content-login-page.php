<?php

/**
 * Template part for displaying login page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Pharmatox
 */

?>

<?php
$redirect_to = get_home_url();
$pt_add_form_login_nonce = wp_create_nonce('pt_form_login_nonce');
$users_can_register = get_option('users_can_register');
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <div class="container">
        <div class="row justify-content-md-center">

            <?php if (!is_user_logged_in()) { ?>

                <div class="col-md-6">

                    <?php do_action('login_user_messages'); ?>

                    <h3 class="mb-4"><?php _e('Login', 'pt'); ?></h3>

                    <form name="loginform" id="loginform" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" class="needs-validation" novalidate>

                        <div class="row">
                            <div class="mb-3">
                                <label for="user_login" class="form-label">E-mail</label>
                                <input type="text" class="form-control" id="user_login" name="log" required>
                                <div class="invalid-feedback"><?php _e('Campo obrigatório', 'pt'); ?></div>
                            </div>

                            <div class="mb-3">
                                <label for="user_pass" class="form-label"><?php _e('Senha', 'pt'); ?></label>
                                <input type="password" class="form-control" name="pwd" id="user_pass" required>
                                <div class="invalid-feedback"><?php _e('Campo obrigatório', 'pt'); ?></div>
                            </div>

                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary"><?php _e('Entrar', 'pt'); ?></button>
                            </div>
                        </div>

                        <input type="hidden" name="pt_form_login_nonce" value="<?php echo $pt_add_form_login_nonce ?>" />
                        <input type="hidden" value="pt_login_form" name="action">
                        <input type="hidden" value="<?php echo esc_attr($redirect_to); ?>" name="redirect_to">
                    </form>

                    <?php if (pt_get_page_id('newuser') && pt_get_page_id('lostpassword')) { ?>
                        <div class="d-flex justify-content-between gap-2">

                            <?php if ($users_can_register) { ?>
                                <a class="link-underline link-underline-opacity-50 link-offset-2" href="<?php echo pt_get_page_url('newuser'); ?>"><?php _e('Cadastre-se', 'pt'); ?></a>
                            <?php } ?>

                            <a class="link-underline link-underline-opacity-50 link-offset-2" href="<?php echo pt_get_page_url('lostpassword'); ?>"><?php _e('Perdeu a senha?', 'pt'); ?></a>

                        </div>
                    <?php } ?>

                </div>

            <?php } else {
                get_template_part('template-parts/content/login/content-already-logged-user');
            } ?>

        </div>
    </div>

</article><!-- #post-<?php the_ID(); ?> -->