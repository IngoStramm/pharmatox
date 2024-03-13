<?php

add_action('login_form_rp', 'pt_redirect_to_custom_resetpassword');
add_action('login_form_resetpass', 'pt_redirect_to_custom_resetpassword');

function pt_redirect_to_custom_resetpassword()
{
    $login_page_id = pt_get_page_id('login');
    $login_page_url = $login_page_id ? pt_get_page_url('login') : get_home_url();
    unset($_SESSION['pt_resetpassword_error_message']);

    if (!isset($_REQUEST['key']) || !$_REQUEST['key']) {

        $_SESSION['pt_resetpassword_error_message'] = __('Não foi possível validar a requisição.', 'pt');
        wp_safe_redirect($login_page_url);
        exit;
    }

    if (!isset($_REQUEST['login']) || !$_REQUEST['login']) {

        $_SESSION['pt_resetpassword_error_message'] = __('Usuário inválido.', 'pt');
        wp_safe_redirect($login_page_url);
        exit;
    }
    
    $user = check_password_reset_key($_REQUEST['key'], $_REQUEST['login']);
    if (!$user || is_wp_error($user)) {

        if ($user && $user->get_error_code() === 'expired_key') {

            $error_string = $user->get_error_message() ? $user->get_error_message() : __('O link de redefinição de senha expirou.', 'pt');
            $_SESSION['pt_resetpassword_error_message'] = $error_string;
            wp_safe_redirect($login_page_url);
        } else {

            $error_string = $user->get_error_message() ? $user->get_error_message() : __('Url inválida.', 'pt');
            $_SESSION['pt_resetpassword_error_message'] = $error_string;
            wp_safe_redirect($login_page_url);
        }
        exit;
    }

    $redirect_url = pt_get_page_url('resetpassword');
    $redirect_url = add_query_arg('login', esc_attr($_REQUEST['login']), $redirect_url);
    $redirect_url = add_query_arg('key', esc_attr($_REQUEST['key']), $redirect_url);
    wp_safe_redirect($redirect_url);
    exit;
}

add_action('admin_post_pt_resetpassword_form', 'pt_resetpassword_form_handle');
add_action('admin_post_nopriv_pt_resetpassword_form', 'pt_resetpassword_form_handle');

function pt_resetpassword_form_handle()
{
    nocache_headers();

    $login_page_id = pt_get_page_id('login');
    $login_page_url = $login_page_id ? pt_get_page_url('login') : get_home_url();

    $resetpassword_page_id = pt_get_page_id('resetpassword');
    $resetpassword_page_url = $resetpassword_page_id ? pt_get_page_url('resetpassword') : get_home_url();
    unset($_SESSION['pt_resetpassword_error_message']);

    if (!isset($_POST['pt_form_resetpassword_nonce']) || !wp_verify_nonce($_POST['pt_form_resetpassword_nonce'], 'pt_form_resetpassword_nonce')) {

        $_SESSION['pt_resetpassword_error_message'] = __('Não foi possível validar a requisição.', 'pt');
        wp_safe_redirect($resetpassword_page_url);
        exit;
    }

    if (!isset($_POST['key']) || !$_POST['key']) {

        $_SESSION['pt_resetpassword_error_message'] = __('Chave de redefinição de senha inválida.', 'pt');
        wp_safe_redirect($resetpassword_page_url);
        exit;
    }

    if (!isset($_POST['user_pass']) || !$_POST['user_pass']) {

        $_SESSION['pt_resetpassword_error_message'] = __('Senha inválida.', 'pt');
        wp_safe_redirect($resetpassword_page_url);
        exit;
    }

    if (!isset($_POST['user_login']) || !$_POST['user_login']) {

        $_SESSION['pt_resetpassword_error_message'] = __('Usuário inválido.', 'pt');
        wp_safe_redirect($resetpassword_page_url);
        exit;
    }

    if (!isset($_POST['action']) || $_POST['action'] !== 'pt_resetpassword_form') {

        $_SESSION['pt_resetpassword_error_message'] = __('Formulário inválido.', 'pt');
        wp_safe_redirect($resetpassword_page_url);
        exit;
    }

    $user_login = $_POST['user_login'];
    $user_pass = $_POST['user_pass'];
    $rp_key = $_POST['key'];

    $user = check_password_reset_key($rp_key, $user_login);

    if (!$user || is_wp_error($user)) {
        if ($user && $user->get_error_code() === 'expired_key') {
            $error_string = $user->get_error_message() ? $user->get_error_message() : __('A chave de redefinição de senha expirou. Solicite um novo link de redefinição de senha clicando na opção "Esqueceu a senha?" na tela de login.', 'pt');
        } else {
            $error_string = $user->get_error_message() ? $user->get_error_message() : __('A chave de redefinição de senha é inválida. Solicite um novo link de redefinição de senha clicando na opção "Esqueceu a senha?" na tela de login.', 'pt');
        }
        $_SESSION['pt_resetpassword_error_message'] = $error_string;
        wp_safe_redirect($login_page_url);
        exit;
    }

    reset_password($user, $user_pass);

    $_SESSION['pt_resetpassword_success_message'] = __('Senha alterada com sucesso.', 'pt');

    echo '<h3>' . __('Senha alterada com sucesso! Por favor, aguarde enquanto está sendo redicionando...', 'pt') . '</p>';

    wp_safe_redirect($login_page_url);
    exit;
}
