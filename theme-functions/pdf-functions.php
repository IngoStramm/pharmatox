<?php

add_action('admin_post_pt_create_pdf_form', 'pt_create_pdf_form_handle');
add_action('admin_post_nopriv_pt_create_pdf_form', 'pt_create_pdf_form_handle');

function pt_create_pdf_form_handle()
{
    nocache_headers();
    $redirect_url = $_SERVER['HTTP_REFERER'];
    unset($_SESSION['pt_create_pdf_error_message']);

    if (!isset($_POST['pt_form_create_pdf_nonce']) || !wp_verify_nonce($_POST['pt_form_create_pdf_nonce'], 'pt_form_create_pdf_nonce')) {

        $_SESSION['pt_create_pdf_error_message'] = __('Não foi possível validar a requisição.', 'pt');
        wp_safe_redirect($redirect_url);
        exit;
    }

    if (!isset($_POST['action']) || $_POST['action'] !== 'pt_create_pdf_form') {

        $_SESSION['pt_create_pdf_error_message'] = __('Formulário inválido.', 'pt');
        wp_safe_redirect($redirect_url);
        exit;
    }

    if (!isset($_POST['post_id']) || !$_POST['post_id']) {

        $_SESSION['pt_create_pdf_error_message'] = __('ID do relatório ausente.', 'pt');
        wp_safe_redirect($redirect_url);
        exit;
    }

    $post_id = $_POST['post_id'];
    $medico_id = get_post_meta($post_id, 'pt_relatorio_medico', true);

    if (!$medico_id) {

        $_SESSION['pt_create_pdf_error_message'] = __('Médico do relatório ausente.', 'pt');
        wp_safe_redirect($redirect_url);
        exit;
    }

    $pfx_file = get_user_meta($medico_id, 'pt_user_certificado', true);

    if (!$pfx_file) {

        $_SESSION['pt_create_pdf_error_message'] = __('Certificado do médico ausente.', 'pt');
        wp_safe_redirect($redirect_url);
        exit;
    }

    $_SESSION['pt_create_pdf_success_message'] = sprintf(__('PDF gerado com sucesso. Post ID: %s, certificado: "%s".', 'pt'), $post_id, $pfx_file);
    wp_safe_redirect($redirect_url);
    exit;
}

add_action('create_pdf_messages', 'pt_create_pdf_error_message');

/**
 * pt_create_pdf_error_message
 *
 * @return void
 */
function pt_create_pdf_error_message()
{
    // Mensagens de erro de atualização do usuário
    if (isset($_SESSION['pt_create_pdf_error_message']) && $_SESSION['pt_create_pdf_error_message']) {
        echo pt_alert_small('danger', $_SESSION['pt_create_pdf_error_message']);
        unset($_SESSION['pt_create_pdf_error_message']);
    }
}

add_action('create_pdf_messages', 'pt_create_pdf_success_message');

function pt_create_pdf_success_message()
{
    // Mensagens de successo de atualização do usuário
    if (isset($_SESSION['pt_create_pdf_success_message']) && $_SESSION['pt_create_pdf_success_message']) {
        echo pt_alert_small('success', $_SESSION['pt_create_pdf_success_message']);
        unset($_SESSION['pt_create_pdf_success_message']);
    }
}
