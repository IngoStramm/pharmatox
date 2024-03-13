<?php

/**
 * Template part for displaying Reset Password page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Pharmatox
 */

?>

<?php
$redirect_to = get_home_url();
$pt_add_form_resetpassword_nonce = wp_create_nonce('pt_form_resetpassword_nonce');
$login = isset($_REQUEST['login']) ? $_REQUEST['login'] : null;
$key = isset($_REQUEST['key']) ? $_REQUEST['key'] : null;
$users_can_register = get_option('users_can_register');
if (!$login) {
    $_SESSION['pt_resetpassword_error_message'] = __('Usuário ausente. Utilize o link enviado por e-mail para acessar esta página.', 'pt');
}

if (!$key) {
    $_SESSION['pt_resetpassword_error_message'] = __('Chave de redefinição de senha ausente. Utilize o link enviado por e-mail para acessar esta página.', 'pt');
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <div class="container">
        <div class="row justify-content-md-center">

            <?php if (!is_user_logged_in()) { ?>

                <div class="col-md-6">

                    <?php
                    // Mensagens de erro de redefinição de senha 
                    if (isset($_SESSION['pt_resetpassword_error_message']) && $_SESSION['pt_resetpassword_error_message']) {
                        echo pt_alert_small('danger', $_SESSION['pt_resetpassword_error_message']);
                        unset($_SESSION['pt_resetpassword_error_message']);
                    }
                    ?>

                    <h3 class="mb-4"><?php _e('Redefinição de senha', 'pt'); ?></h3>
                    <p><?php _e('Digite sua nova senha abaixo.', 'pt') ?></p>

                    <?php
                    // Referência: @link: https://code.tutsplus.com/build-a-custom-wordpress-user-flow-part-3-password-reset--cms-23811t
                    ?>

                    <form name="resetpassword-form" id="resetpassword-form" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" class="needs-validation" novalidate>

                        <div class="row">
                            <div class="mb-3">
                                <label for="user_pass" class="form-label"><?php _e('Senha', 'pt'); ?></label>
                                <input type="password" class="form-control" name="user_pass" id="user_pass" autocomplete="off" aria-autocomplete="list" aria-label="Password" aria-describedby="passwordHelp" tabindex="4" required>
                                <div class="invalid-feedback"><?php _e('Campo obrigatório', 'pt'); ?></div>
                                <div class="password-meter">
                                    <div class="meter-section rounded me-2"></div>
                                    <div class="meter-section rounded me-2"></div>
                                    <div class="meter-section rounded me-2"></div>
                                    <div class="meter-section rounded"></div>
                                </div>
                                <div id="passwordHelp" class="form-text text-muted"><?php _e('Use 8 ou mais caracteres com uma mistura de letras, números e símbolos.', 'pt'); ?></div>
                            </div>

                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary"><?php _e('Salvar senha', 'pt'); ?></button>
                            </div>
                        </div>

                        <input type="hidden" name="pt_form_resetpassword_nonce" value="<?php echo $pt_add_form_resetpassword_nonce ?>" />
                        <input type="hidden" name="user_login" value="<?php echo $login; ?>" />
                        <input type="hidden" name="key" value="<?php echo $key; ?>" />
                        <input type="hidden" value="pt_resetpassword_form" name="action">
                    </form>

                    <?php if (pt_get_page_id('login') && pt_get_page_id('newuser')) { ?>
                        <div class="d-flex justify-content-between gap-2">

                            <a class="link-underline link-underline-opacity-50 link-offset-2" href="<?php echo pt_get_page_url('login'); ?>"><?php _e('Acessar', 'pt'); ?></a>

                            <?php if ($users_can_register) { ?>
                                <a class="link-underline link-underline-opacity-50 link-offset-2" href="<?php echo pt_get_page_url('newuser'); ?>"><?php _e('Cadastre-se', 'pt'); ?></a>
                            <?php } ?>

                        </div>
                    <?php } ?>

                </div>

            <?php } else {
                get_template_part('template-parts/content/login/content-already-logged-user');
            } ?>

        </div>
    </div>

</article><!-- #post-<?php the_ID(); ?> -->