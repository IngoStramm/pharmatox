<?php

add_action('admin_post_pt_register_new_user_form', 'pt_register_new_user_form_handle');
add_action('admin_post_nopriv_pt_register_new_user_form', 'pt_register_new_user_form_handle');

/**
 * pt_register_new_user_handle
 *
 * @return void
 */
function pt_register_new_user_form_handle()
{
    nocache_headers();
    $users_can_register = get_option('users_can_register');
    $login_page_id = pt_get_option('pt_login_page');
    $login_page_url = $login_page_id ? get_page_link($login_page_id) : get_home_url();
    $register_new_user_page_id = pt_get_option('pt_new_user_page');
    $register_new_user_page_url = $register_new_user_page_id ? get_page_link($register_new_user_page_id) : get_home_url();
    unset($_SESSION['pt_register_new_user_error_message']);

    if(!$users_can_register) {
        $_SESSION['pt_login_error_message'] = __('Este site não permite o cadastro de novos usuários.', 'pt');
        wp_safe_redirect($login_page_id);
        exit;
    }

    if (!isset($_POST['pt_form_register_new_user_nonce']) || !wp_verify_nonce($_POST['pt_form_register_new_user_nonce'], 'pt_form_register_new_user_nonce')) {

        $_SESSION['pt_register_new_user_error_message'] = __('Não foi possível validar a requisição.', 'pt');
        wp_safe_redirect($register_new_user_page_url);
        exit;
    }

    if (!isset($_POST['action']) || $_POST['action'] !== 'pt_register_new_user_form') {

        $_SESSION['pt_register_new_user_error_message'] = __('Formulário inválido.', 'pt');
        wp_safe_redirect($register_new_user_page_url);
        exit;
    }

    if (!isset($_POST['user_name']) || !$_POST['user_name']) {

        $_SESSION['pt_register_new_user_error_message'] = __('Nome inválido.', 'pt');
        wp_safe_redirect($register_new_user_page_url);
        exit;
    }
    $user_name = sanitize_text_field($_POST['user_name']);
    $user_login = pt_generate_unique_username($user_name);

    if (!isset($_POST['user_surname']) || !$_POST['user_surname']) {

        $_SESSION['pt_register_new_user_error_message'] = __('Sobrenome inválido.', 'pt');
        wp_safe_redirect($register_new_user_page_url);
        exit;
    }
    $user_surname = sanitize_text_field($_POST['user_surname']);

    if (!isset($_POST['user_email']) || !$_POST['user_email']) {

        $_SESSION['pt_register_new_user_error_message'] = __('E-mail inválido.', 'pt');
        wp_safe_redirect($register_new_user_page_url);
        exit;
    }
    $user_email = sanitize_email($_POST['user_email']);

    if (!isset($_POST['user_whatsapp']) || !$_POST['user_whatsapp']) {

        $_SESSION['pt_register_new_user_error_message'] = __('WhatsApp inválido.', 'pt');
        wp_safe_redirect($register_new_user_page_url);
        exit;
    }
    $user_whatsapp = preg_replace('/[^0-9]/', '', $_POST['user_whatsapp']);

    if (!isset($_POST['user_phone']) || !$_POST['user_phone']) {

        $_SESSION['pt_register_new_user_error_message'] = __('Telefone inválido.', 'pt');
        wp_safe_redirect($register_new_user_page_url);
        exit;
    }
    $user_phone = preg_replace('/[^0-9]/', '', $_POST['user_phone']);

    if (!isset($_POST['user_type']) || !$_POST['user_type']) {

        $_SESSION['pt_register_new_user_error_message'] = __('Tipo de usuário inválido.', 'pt');
        wp_safe_redirect($register_new_user_page_url);
        exit;
    }
    $user_type = sanitize_text_field($_POST['user_type']);

    if (!isset($_POST['user_pass']) || !$_POST['user_pass']) {

        $_SESSION['pt_register_new_user_error_message'] = __('Senha inválida.', 'pt');
        wp_safe_redirect($register_new_user_page_url);
        exit;
    }
    $user_password = $_POST['user_pass'];

    // if (!isset($_POST['user_avatar']) || !$_POST['user_avatar']) {

    //     $_SESSION['pt_register_new_user_error_message'] = __('Avatar inválido.', 'pt');
    //     wp_safe_redirect($register_new_user_page_url);
    //     exit;
    // }
    // $user_avatar = $_POST['user_avatar'];

    $userdata = array(
        'user_pass'                => $user_password,     //(string) The plain-text user password.
        'user_login'             => $user_login,     //(string) The user's login username.
        'user_nicename'         => $user_name,     //(string) The URL-friendly user name.
        'user_url'                 => '',     //(string) The user URL.
        'user_email'             => $user_email,     //(string) The user email address.
        'display_name'             => $user_name,     //(string) The user's display name. Default is the user's username.
        'nickname'                 => $user_name,     //(string) The user's nickname. Default is the user's username.
        'first_name'             => $user_name,     //(string) The user's first name. For new users, will be used to build the first part of the user's display name if $display_name is not specified.
        'last_name'             => $user_surname,     //(string) The user's last name. For new users, will be used to build the second part of the user's display name if $display_name is not specified.
        // 'description'             => '',     //(string) The user's biographical description.
        // 'rich_editing'             => '',     //(string|bool) Whether to enable the rich-editor for the user. False if not empty.
        // 'syntax_highlighting'     => '',     //(string|bool) Whether to enable the rich code editor for the user. False if not empty.
        // 'comment_shortcuts'     => '',     //(string|bool) Whether to enable comment moderation keyboard shortcuts for the user. Default false.
        // 'admin_color'             => '',     //(string) Admin color scheme for the user. Default 'fresh'.
        'use_ssl'                 => 'true',     //(bool) Whether the user should always access the admin over https. Default false.
        // 'user_registered'         => '',     //(string) Date the user registered. Format is 'Y-m-d H:i:s'.
        'show_admin_bar_front'     => 'false',     //(string|bool) Whether to display the Admin Bar for the user on the site's front end. Default true.
        'role'                     => 'subscriber',     //(string) User's role.
        // 'locale'                 => '',     //(string) User's locale. Default empty.
        'meta_input'            => array(
            'pt_user_type'      => $user_type,
            'pt_user_phone'     => $user_phone,
            'pt_user_whatsapp'     => $user_whatsapp,
        )

    );
    $register_new_user_result = wp_insert_user($userdata);

    if (is_wp_error($register_new_user_result)) {
        $error_string = $register_new_user_result->get_error_message() ? $register_new_user_result->get_error_message() : __('Ocorreu um erro ao tentar cadastrar o usuário. Revise os dados inseridos e tente novamente.', 'pt');
        $_SESSION['pt_register_new_user_error_message'] = $error_string;
        wp_safe_redirect($register_new_user_page_url);
        exit;
    }

    $user = get_user_by('id', $register_new_user_result);

    $_SESSION['pt_register_new_user_success_message'] = wp_sprintf(__('Seja bem vindo(a), %s!', 'pt'), $user->display_name);

    echo '<h3>' . __('Novo usuário cadastrado com sucesso! Por favor, aguarde enquanto está sendo redicionando...', 'pt') . '</p>';

    $login_result = wp_signon(array(
        'user_login'        => $user_login,
        'user_password'     => $user_password,
    ));

    if (is_wp_error($login_result)) {
        $error_string = $login_result->get_error_message() ? $login_result->get_error_message() : __('Login falhou. Verifique se os dados de login estão corretos e tente novamente.', 'pt');
        $_SESSION['pt_login_error_message'] = $error_string;
        wp_safe_redirect($login_page_url);
        exit;
    }

    wp_safe_redirect(get_home_url());
    exit;
}

add_action('login_user_messages', 'pt_register_new_user_error_message');

/**
 * pt_register_new_user_error_message
 *
 * @return void
 */
function pt_register_new_user_error_message()
{
    // Mensagens de erro de atualização do usuário
    if (isset($_SESSION['pt_register_new_user_error_message']) && $_SESSION['pt_register_new_user_error_message']) {
        echo pt_alert_small('danger', $_SESSION['pt_register_new_user_error_message']);
        unset($_SESSION['pt_register_new_user_error_message']);
    }
}

add_action('login_user_messages', 'pt_register_new_user_success_message');

function pt_register_new_user_success_message()
{
    // Mensagens de successo de atualização do usuário
    if (isset($_SESSION['pt_register_new_user_success_message']) && $_SESSION['pt_register_new_user_success_message']) {
        echo pt_alert_small('success', $_SESSION['pt_register_new_user_success_message']);
        unset($_SESSION['pt_register_new_user_success_message']);
    }
}
