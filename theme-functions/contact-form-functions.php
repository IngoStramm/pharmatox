<?php

function pt_get_field_value($name)
{
    $value = isset($_POST[$name]) && !is_null($_POST[$name]) ? $_POST[$name] : null;
    if (!$value) {
        // retorna uma mensagem de erro com o campo 'success' falso
        wp_send_json_error(array('msg' => __("Campo \"$name\" não foi passado ou está vazio.", 'cl')), 200);
    }
    return $value;
}

add_action('wp_ajax_nopriv_pt_contact_form', 'pt_contact_form');
add_action('wp_ajax_pt_contact_form', 'pt_contact_form');

function pt_contact_form()
{

    if (!isset($_POST['pt_contact_form_nonce']) || !wp_verify_nonce($_POST['pt_contact_form_nonce'], 'pt_contact_form_nonce')) {
        wp_send_json_error(array('msg' => __('Não foi possível validar a requisição.', 'pt')), 200);
    }

    $fields = array('nome', 'email', 'mensagem');
    $data = [];
    foreach ($fields as $name) {
        $data[$name] = pt_get_field_value($name);
    }

    $send_to_emails = pt_get_option('pt_contact_form_emails');
    $to = $send_to_emails;
    $subject = sprintf(__('Nova mensagem de contato | %s', 'pt'), get_bloginfo('name'));
    $body = '';
    $body .= '<p>' . sprintf(__('Nome: "%s"', 'pt'), $data['nome']) . '</p>';
    $body .= '<p>' . sprintf(__('Email: "%s"', 'pt'), $data['email']) . '</p>';
    $body .= '<p>' . sprintf(__('Mensagem: "%s"', 'pt'), $data['mensagem']) . '</p>';
    $send_email_notification = pt_mail($to, $subject, $body);

    if (!$send_email_notification) {
        wp_send_json_error(array('msg' => __('Ocorreu um erro ao tentar enviar a sua mensagem.', 'pt')), 200);
    }

    $response = array(
        'msg'                   => __('Mensagem enviada com sucesso!', 'pt'),
    );

    wp_send_json_success($response);
}
