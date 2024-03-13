<?php

add_action('admin_post_pt_update_user_form', 'pt_update_user_form_handle');
add_action('admin_post_nopriv_pt_update_user_form', 'pt_update_user_form_handle');

/**
 * pt_update_user_handle
 *
 * @return void
 */
function pt_update_user_form_handle()
{
    nocache_headers();
    $account_page_id = pt_get_option('pt_account_page');
    $account_page_url = $account_page_id ? get_page_link($account_page_id) : get_home_url();
    $changed_pfx = isset($_POST['changed-pfx']) && $_POST['changed-pfx'] !== 'false';
    unset($_SESSION['pt_update_user_error_message']);

    if (!isset($_POST['pt_form_update_user_nonce']) || !wp_verify_nonce($_POST['pt_form_update_user_nonce'], 'pt_form_update_user_nonce')) {

        $_SESSION['pt_update_user_error_message'] = __('Não foi possível validar a requisição.', 'pt');
        wp_safe_redirect($account_page_url);
        exit;
    }

    if (!isset($_POST['action']) || $_POST['action'] !== 'pt_update_user_form') {

        $_SESSION['pt_update_user_error_message'] = __('Formulário inválido.', 'pt');
        wp_safe_redirect($account_page_url);
        exit;
    }

    if (!isset($_POST['user_id']) || !$_POST['user_id']) {

        $_SESSION['pt_update_user_error_message'] = __('ID do usuário inválido.', 'pt');
        wp_safe_redirect($account_page_url);
        exit;
    }
    $user_id = $_POST['user_id'];

    $check_user_exists = get_user_by('id', $user_id);
    if (!$check_user_exists) {

        $_SESSION['pt_update_user_error_message'] = __('Usuário inválido.', 'pt');
        wp_safe_redirect($account_page_url);
        exit;
    }

    $user_name = (isset($_POST['user_name']) && $_POST['user_name']) ? sanitize_text_field($_POST['user_name']) : null;

    $user_surname = (isset($_POST['user_surname']) && $_POST['user_surname']) ? sanitize_text_field($_POST['user_surname']) : null;

    $user_email = (isset($_POST['user_email']) && $_POST['user_email']) ? sanitize_email($_POST['user_email']) : null;

    $user_especialidade = (isset($_POST['user_especialidade']) && $_POST['user_especialidade']) ? sanitize_text_field($_POST['user_especialidade']) : null;

    $user_crm = (isset($_POST['user_crm']) && $_POST['user_crm']) ? sanitize_text_field($_POST['user_crm']) : null;

    $user_certificado_password = (isset($_POST['certificado_pass']) && $_POST['certificado_pass']) ? $_POST['certificado_pass'] : null;
    
    $user_password = (isset($_POST['user_pass']) && $_POST['user_pass']) ? $_POST['user_pass'] : null;

    $file = isset($_FILES['user_certificado']['tmp_name']) || $_FILES['user_certificado']['tmp_name'] ? $_FILES['user_certificado']['tmp_name'] : null;

    $userdata = array();
    $userdata['ID'] = $user_id;

    if ($user_name) {
        $userdata['user_nicename'] = $user_name;
        $userdata['display_name'] = $user_name;
        $userdata['nickname'] = $user_name;
        $userdata['first_name'] = $user_name;
    }

    if ($user_surname) {
        $userdata['last_name'] = $user_surname;
    }

    if ($user_email) {
        $userdata['user_email'] = $user_email;
    }

    if ($user_especialidade) {
        $userdata['meta_input']['pt_user_especialidade'] = $user_especialidade;
    }

    if ($user_crm) {
        $userdata['meta_input']['pt_user_crm'] = $user_crm;
    }

    if ($user_certificado_password) {
        $userdata['meta_input']['pt_user_certificado_password'] = $user_certificado_password;
    }

    if ($user_password) {
        $userdata['user_pass'] = $user_password;
    }

    if ($changed_pfx) {
        if (!$file) {
            $userdata['meta_input']['pt_user_certificado_id'] = null;
            $userdata['meta_input']['pt_user_certificado'] = null;
        } else {
            $filename = $_FILES['user_certificado']['name'];
            $file_size = $_FILES['user_certificado']['size'];

            if ($file_size > 2097152) {
                $_SESSION['pt_update_user_error_message'] = __('O arquivo é muito pesado, o tamanho máximo permitido é de 2MB.', 'pt');
                wp_safe_redirect($account_page_url);
                exit;
            }

            $upload_file = wp_upload_bits($filename, null, @file_get_contents($file));
            if (!$upload_file['error']) {
                // Check the type of file. We'll use this as the 'post_mime_type'.
                $filetype = wp_check_filetype($filename, null);

                // Get the path to the upload directory.
                $wp_upload_dir = wp_upload_dir();

                // Prepare an array of post data for the attachment.
                $attachment = array(
                    'post_mime_type' => $filetype['type'],
                    'post_title'     => preg_replace('/\.[^.]+$/', '', $filename),
                    // 'post_content'   => '',
                    // 'post_status'    => 'inherit',
                );

                // Insert the attachment.
                $attach_id = wp_insert_attachment($attachment, $upload_file['file']);

                if (!is_wp_error($attach_id)) {
                    // Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
                    require_once(ABSPATH . 'wp-admin/includes/image.php');

                    // Generate the metadata for the attachment, and update the database record.
                    $attach_data = wp_generate_attachment_metadata($attach_id, $upload_file['file']);
                    wp_update_attachment_metadata($attach_id, $attach_data);

                    $userdata['meta_input']['pt_user_certificado_id'] = $attach_id;
                    $userdata['meta_input']['pt_user_certificado'] = $upload_file['url'];

                } else {
                    $_SESSION['pt_update_user_error_message'] = $attach_id->get_error_message();
                    wp_safe_redirect($account_page_url);
                    exit;
                }
            } else {
                $_SESSION['pt_update_user_error_message'] = __('Ocorreu um erro ao tentar fazer o upload do arquivo.', 'pt');
                wp_safe_redirect($account_page_url);
                exit;
            }
        }
    }


    $update_user_result = wp_update_user($userdata);
    // pt_debug($user_name);
    // pt_debug($userdata);
    // pt_debug($update_user_result);
    // exit;

    if (is_wp_error($update_user_result)) {
        $error_string = $update_user_result->get_error_message() ? $update_user_result->get_error_message() : __('Ocorreu um erro ao tentar atualizar os dados do usuário. Revise os dados inseridos e tente novamente.', 'pt');
        $_SESSION['pt_update_user_error_message'] = $error_string;
        wp_safe_redirect($account_page_url);
        exit;
    }

    $update_user_especialidade = update_user_meta($user_id, 'pt_user_especialidade', $user_especialidade);
    $update_user_crm = update_user_meta($user_id, 'pt_user_crm', $user_crm);

    $user = get_user_by('id', $update_user_result);

    $_SESSION['pt_update_user_success_message'] = wp_sprintf(__('Dados do usuário %s atualizados com sucesso!', 'pt'), $user->display_name);

    echo '<h3>' . __('Dados do usuário atualizados com sucesso! Por favor, aguarde enquanto está sendo redicionando...', 'pt') . '</p>';

    wp_safe_redirect($account_page_url);
    exit;
}

add_action('admin_post_pt_update_vendedor_terms_form', 'pt_update_vendedor_terms_form_handle');
add_action('admin_post_nopriv_pt_update_vendedor_terms_form', 'pt_update_vendedor_terms_form_handle');

/**
 * pt_update_user_handle
 *
 * @return void
 */
function pt_update_vendedor_terms_form_handle()
{
    nocache_headers();
    $account_cat_anuncio_config_page_url = pt_get_page_url('catanuncioconfig');
    unset($_SESSION['pt_update_vendedor_terms_error_message']);

    if (!isset($_POST['pt_form_following_terms_user_nonce']) || !wp_verify_nonce($_POST['pt_form_following_terms_user_nonce'], 'pt_form_following_terms_user_nonce')) {

        $_SESSION['pt_update_vendedor_terms_error_message'] = __('Não foi possível validar a requisição.', 'pt');
        wp_safe_redirect($account_cat_anuncio_config_page_url);
        exit;
    }

    if (!isset($_POST['action']) || $_POST['action'] !== 'pt_update_vendedor_terms_form') {

        $_SESSION['pt_update_vendedor_terms_error_message'] = __('Formulário inválido.', 'pt');
        wp_safe_redirect($account_cat_anuncio_config_page_url);
        exit;
    }

    if (!isset($_POST['user_id']) || !$_POST['user_id']) {

        $_SESSION['pt_update_vendedor_terms_error_message'] = __('ID do usuário inválido.', 'pt');
        wp_safe_redirect($account_cat_anuncio_config_page_url);
        exit;
    }
    $user_id = $_POST['user_id'];

    $check_user_exists = get_user_by('id', $user_id);
    if (!$check_user_exists) {

        $_SESSION['pt_update_vendedor_terms_error_message'] = __('Usuário inválido.', 'pt');
        wp_safe_redirect($account_cat_anuncio_config_page_url);
        exit;
    }

    $terms = (isset($_POST['terms']) && $_POST['terms']) ? $_POST['terms'] : null;

    $pt_user_following_terms = update_user_meta($user_id, 'pt_user_following_terms', $terms);

    $user = $check_user_exists;

    $_SESSION['pt_update_vendedor_terms_success_message'] = __('Configuração de categorias de anúncio atualizadas!', 'pt');

    echo '<h3>' . __('Configuração de categorias de anúncio atualizadas com sucesso! Por favor, aguarde enquanto está sendo redicionando...', 'pt') . '</p>';

    wp_safe_redirect($account_cat_anuncio_config_page_url);
    exit;
}

add_action('update_user_messages', 'pt_update_user_error_message');

/**
 * pt_update_user_error_message
 *
 * @return void
 */
function pt_update_user_error_message()
{
    // Mensagens de erro de atualização do usuário
    if (isset($_SESSION['pt_update_user_error_message']) && $_SESSION['pt_update_user_error_message']) {
        echo pt_alert_small('danger', $_SESSION['pt_update_user_error_message']);
        unset($_SESSION['pt_update_user_error_message']);
    }
}

add_action('update_user_messages', 'pt_update_user_success_message');

function pt_update_user_success_message()
{
    // Mensagens de successo de atualização do usuário
    if (isset($_SESSION['pt_update_user_success_message']) && $_SESSION['pt_update_user_success_message']) {
        echo pt_alert_small('success', $_SESSION['pt_update_user_success_message']);
        unset($_SESSION['pt_update_user_success_message']);
    }
}

add_action('update_vendedor_terms_messages', 'pt_update_vendedor_terms_error_message');

/**
 * pt_update_vendedor_terms_error_message
 *
 * @return void
 */
function pt_update_vendedor_terms_error_message()
{
    // Mensagens de erro de atualização das configurações de categoria
    if (isset($_SESSION['pt_update_vendedor_terms_error_message']) && $_SESSION['pt_update_vendedor_terms_error_message']) {
        echo pt_alert_small('danger', $_SESSION['pt_update_vendedor_terms_error_message']);
        unset($_SESSION['pt_update_vendedor_terms_error_message']);
    }
}

add_action('update_vendedor_terms_messages', 'pt_update_vendedor_terms_success_message');

/**
 * pt_update_vendedor_terms_success_message
 *
 * @return void
 */
function pt_update_vendedor_terms_success_message()
{
    // Mensagens de sucesso de atualização das configurações de categoria
    if (isset($_SESSION['pt_update_vendedor_terms_success_message']) && $_SESSION['pt_update_vendedor_terms_success_message']) {
        echo pt_alert_small('success', $_SESSION['pt_update_vendedor_terms_success_message']);
        unset($_SESSION['pt_update_vendedor_terms_success_message']);
    }
}
