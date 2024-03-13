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
$pt_add_form_register_new_user_nonce = wp_create_nonce('pt_form_register_new_user_nonce');
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <div class="container">
        <div class="row justify-content-md-center">

            <?php if (!is_user_logged_in()) { ?>

                <div class="col-md-6">

                    <?php
                    // Mensagens de erro de registro de novo usuário
                    if (isset($_SESSION['pt_register_new_user_error_message']) && $_SESSION['pt_register_new_user_error_message']) {
                        echo pt_alert_small('danger', $_SESSION['pt_register_new_user_error_message']);
                        unset($_SESSION['pt_register_new_user_error_message']);
                    }
                    ?>

                    <h3 class="mb-4"><?php _e('Cadastrar novo usuário', 'pt'); ?></h3>

                    <form name="register-new-user-form" id="register-new-user-form" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" class="needs-validation" novalidate>
                        <div class="row">
                            <div class="mb-3">
                                <label for="user_name" class="form-label"><?php _e('Nome', 'pt'); ?></label>
                                <input type="text" class="form-control" id="user_name" name="user_name" tabindex="1" required>
                                <div class="invalid-feedback"><?php _e('Campo obrigatório', 'pt'); ?></div>
                            </div>

                            <div class="mb-3">
                                <label for="user_surname" class="form-label"><?php _e('Sobrenome', 'pt'); ?></label>
                                <input type="text" class="form-control" id="user_surname" name="user_surname" tabindex="2" required>
                                <div class="invalid-feedback"><?php _e('Campo obrigatório', 'pt'); ?></div>
                            </div>

                            <div class="mb-3">
                                <label for="user_email" class="form-label"><?php _e('E-mail', 'pt') ?></label>
                                <input type="email" class="form-control" id="user_email" name="user_email" tabindex="3" required>
                                <div class="invalid-feedback"><?php _e('Campo obrigatório', 'pt'); ?></div>
                            </div>

                            <div class="mb-3">
                                <label for="user_whatsapp" class="form-label"><?php _e('WhatsApp', 'pt') ?></label>
                                <input type="tel" maxlength="15" minlength="15" class="form-control phone-input" id="user_whatsapp" name="user_whatsapp" tabindex="3" required>
                                <div class="invalid-feedback"><?php _e('Campo obrigatório', 'pt'); ?></div>
                            </div>

                            <div class="mb-3">
                                <label for="user_phone" class="form-label"><?php _e('Telefone', 'pt') ?></label>
                                <input type="tel" maxlength="15" minlength="14" class="form-control phone-input" id="user_phone" name="user_phone" tabindex="3" required>
                                <div class="invalid-feedback"><?php _e('Campo obrigatório', 'pt'); ?></div>
                            </div>

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
                                <label class="mb-2"><?php _e('Tipo de usuário', 'pt'); ?></label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="user_type" value="comprador" id="user-comprador" checked required>
                                    <label class="form-check-label" for="user-comprador">
                                        <?php _e('Comprador', 'pt'); ?>
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="user_type" value="vendedor" id="user-vendedor" required>
                                    <label class="form-check-label" for="user-vendedor">
                                        <?php _e('Vendedor', 'pt'); ?>
                                    </label>
                                </div>
                            </div>

                            <!-- <div class="mb-3">
                                <label for="user_avatar" class="form-label"><?php _e('Avatar', 'pt') ?></label>
                                <input type="file" class="form-control" id="user_avatar" name="user_avatar" tabindex="5">
                            </div> -->

                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary" tabindex="6"><?php _e('Cadastrar', 'pt'); ?></button>

                            </div>
                        </div>

                        <input type="hidden" name="pt_form_register_new_user_nonce" value="<?php echo $pt_add_form_register_new_user_nonce ?>" />
                        <input type="hidden" value="pt_register_new_user_form" name="action">
                        <input type="hidden" value="<?php echo esc_attr($redirect_to); ?>" name="redirect_to">
                    </form>

                    <?php if (pt_get_page_id('login') && pt_get_page_id('lostpassword')) { ?>
                        <div class="d-flex justify-content-between gap-2">

                            <a class="link-underline link-underline-opacity-50 link-offset-2" href="<?php echo pt_get_page_url('login'); ?>"><?php _e('Acessar', 'pt'); ?></a>

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