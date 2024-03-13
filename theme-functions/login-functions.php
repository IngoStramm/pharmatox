<?php

add_action('admin_post_pt_login_form', 'pt_login_form_handle');
add_action('admin_post_nopriv_pt_login_form', 'pt_login_form_handle');

/**
 * pt_login_form_handle
 *
 * @return void
 */
function pt_login_form_handle()
{
    nocache_headers();
    $login_page_id = pt_get_page_id('login');
    $login_page_url = $login_page_id ? pt_get_page_url('login') : get_home_url();
    unset($_SESSION['pt_login_error_message']);

    if (!isset($_POST['pt_form_login_nonce']) || !wp_verify_nonce($_POST['pt_form_login_nonce'], 'pt_form_login_nonce')) {

        $_SESSION['pt_login_error_message'] = __('Não foi possível validar a requisição.', 'pt');
        wp_safe_redirect($login_page_url);
        exit;
    }

    if (!isset($_POST['action']) || $_POST['action'] !== 'pt_login_form') {

        $_SESSION['pt_login_error_message'] = __('Formulário inválido.', 'pt');
        wp_safe_redirect($login_page_url);
        exit;
    }

    $login_result = wp_signon();

    if (is_wp_error($login_result)) {

        $error_string = $login_result->get_error_message() ? $login_result->get_error_message() : __('Login falhou. Verifique se os dados de login estão corretos e tente novamente.', 'pt');
        $_SESSION['pt_login_error_message'] = $error_string;
        wp_safe_redirect($login_page_url);
        exit;
    }

    $user = $login_result;

    $_SESSION['pt_login_success_message'] = wp_sprintf(__('Bem vindo de volta, %s!', 'pt'), $user->display_name);

    echo '<h3>' . __('Login feito com sucesso! Por favor, aguarde enquanto está sendo redicionando...', 'pt') . '</p>';

    wp_safe_redirect(get_home_url());
    exit;
}

add_action('wp_logout', 'pt_auto_redirect_after_logout');

/**
 * pt_auto_redirect_after_logout
 *
 * @return void
 */
function pt_auto_redirect_after_logout()
{
    wp_safe_redirect(get_home_url());
    exit;
}

/**
 * Recursive function to generate a unique username.
 *
 * If the username already exists, will add a numerical suffix which will increase until a unique username is found.
 *
 * @param string $username
 *
 * @return string The unique username.
 */
function pt_generate_unique_username($username)
{
    $username = sanitize_title($username);
    static $i;
    if (null === $i) {
        $i = 1;
    } else {
        $i++;
    }

    if (!username_exists($username)) {
        return $username;
    }

    $new_username = sprintf('%s-%s', $username, $i);

    if (!username_exists($new_username)) {
        return $new_username;
    } else {
        return call_user_func(__FUNCTION__, $username);
    }
}

add_action('login_user_messages', 'pt_login_error_message');

function pt_login_error_message()
{
    if (isset($_SESSION['pt_login_error_message']) && $_SESSION['pt_login_error_message']) {
        echo pt_alert_small('danger', $_SESSION['pt_login_error_message']);
        unset($_SESSION['pt_login_error_message']);
    }
}

add_action('login_user_messages', 'pt_resetpassword_error_message');

function pt_resetpassword_error_message()
{
    // Mensagens de erro de reset password 
    if (isset($_SESSION['pt_resetpassword_error_message']) && $_SESSION['pt_resetpassword_error_message']) {
        echo pt_alert_small('danger', $_SESSION['pt_resetpassword_error_message']);
        unset($_SESSION['pt_resetpassword_error_message']);
    }
}

add_action('login_user_messages', 'pt_lostpassword_success_message');

function pt_lostpassword_success_message()
{
    // Mensagens de successo de senha perdida
    if (isset($_SESSION['pt_lostpassword_success_message']) && $_SESSION['pt_lostpassword_success_message']) {
        echo pt_alert_small('success', $_SESSION['pt_lostpassword_success_message']);
        unset($_SESSION['pt_lostpassword_success_message']);
    }
}

add_action('login_user_messages', 'pt_resetpassword_success_message');

function pt_resetpassword_success_message()
{
    // Mensagens de successo de redefinição senha
    if (isset($_SESSION['pt_resetpassword_success_message']) && $_SESSION['pt_resetpassword_success_message']) {
        echo pt_alert_small('success', $_SESSION['pt_resetpassword_success_message']);
        unset($_SESSION['pt_resetpassword_success_message']);
    }
}
