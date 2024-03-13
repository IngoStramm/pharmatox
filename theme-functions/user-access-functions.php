<?php

add_action('wp', 'pt_forcelogin');

/**
 * pt_forcelogin
 *
 * @return void
 */
function pt_forcelogin()
{
    if (is_admin()) {
        return;
    }

    global $wp_query;
    $current_page_id = isset($wp_query->post->ID) ? $wp_query->post->ID : null;
    $login_page_id = pt_get_page_id('login');
    $new_user_page_id = pt_get_page_id('newuser');
    $users_can_register = get_option('users_can_register');
    $lostpassword_page_id = pt_get_page_id('lostpassword');
    $resetpassword_page_id = pt_get_page_id('resetpassword');
    $account_page_id = pt_get_page_id('account');

    if ($account_page_id) {

        if (($current_page_id === (int)$login_page_id) ||
            ($current_page_id === (int)$lostpassword_page_id) ||
            ($current_page_id === (int)$resetpassword_page_id)
        ) {
            return;
        }

        if ($users_can_register && ($current_page_id === (int)$new_user_page_id)) {
            return;
        }

        if (!is_user_logged_in()) {
            $url = pt_get_url();
            $redirect_url = apply_filters('pt_forcelogin_redirect', $url);
            wp_safe_redirect(pt_get_page_url('login'), '302', $redirect_url);
            exit();
        }
    }
}


function pt_alert($text)
{
    $output = '';
    $output .= '<div class="alert alert-warning d-flex align-items-top align-content-center" role="alert">';
    $output .= '<i class="bi bi-exclamation-triangle-fill me-2"></i>';
    $output .= '<div>' . $text . '</div>';
    $output .= '</div>';
    return $output;
}

function pt_alert_not_logged_in($text)
{
    $login_page_id = pt_get_page_id('login');
    if (!$login_page_id) {
        return;
    }
    $login_page_url = pt_get_page_url('login');
    $output = '';
    $output .= '<div class="alert alert-warning">';
    $output .= $text;
    $output .= '<br><a class="" href="';
    $output .= $login_page_url . '">' . __('Entrar', 'pt') . '</a></div>';
    return $output;
}
