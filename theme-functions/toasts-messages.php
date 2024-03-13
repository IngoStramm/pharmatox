<?php

add_action('wp_footer', 'wtShowToasts');

/**
 * wtShowToasts
 *
 * @return void
 */
function wtShowToasts()
{
    $output = '';
    $output .= '<div class="toast-container bottom-0 end-0 p-3">';

    // Mensagem de successo de login
    $pt_login_success_message = isset($_SESSION['pt_login_success_message']) && $_SESSION['pt_login_success_message'] ? $_SESSION['pt_login_success_message'] : null;

    if ($pt_login_success_message) {
        $output .= wtToastsHtml($pt_login_success_message);
        unset($_SESSION['pt_login_success_message']);
    }

    // Mensagem de successo de registro de novo usuário
    $pt_register_new_user_success_message = isset($_SESSION['pt_register_new_user_success_message']) && $_SESSION['pt_register_new_user_success_message'] ? $_SESSION['pt_register_new_user_success_message'] : null;

    if ($pt_register_new_user_success_message) {
        $output .= wtToastsHtml($pt_register_new_user_success_message);
        unset($_SESSION['pt_register_new_user_success_message']);
    }

    $output .= '</div>';
    echo $output;
}

/**
 * wtToastsHtml
 *
 * @param  string $message
 * @return string
 */
function wtToastsHtml($message = null)
{
    if (!$message) {
        return;
    }
    return '    
    <div class="toast align-items-center" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">' . $message . '</div>
            <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>';
}
