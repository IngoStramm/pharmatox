<?php

/**
 * pt_mail
 *
 * @param  string $to
 * @param  string $subject
 * @param  string $body
 * @return boolean
 */
function pt_mail($to, $subject, $body)
{
    $headers = array('Content-Type: text/html; charset=UTF-8');
    $send_email_notification = wp_mail($to, $subject, $body, $headers);
    return $send_email_notification;
}

add_action('wp_mail_failed', 'pt_log_mailer_errors', 10, 1);

/**
 * pt_log_mailer_errors
 *
 * @param  WP_Error $wp_error
 * @return void
 */
function pt_log_mailer_errors($wp_error)
{
    $fn = ABSPATH . '/wp-content/mail.log'; // say you've got a mail.log file in your server root
    $fp = fopen($fn, 'a');
    fputs($fp, "Mailer Error: " . $wp_error->get_error_message() . "\n");
    fclose($fp);
}

add_filter('wp_mail_from', 'pt_custom_wp_mail_from');

/**
 * pt_custom_wp_mail_from
 *
 * @param  string $original_email_address
 * @return string
 */
function pt_custom_wp_mail_from($original_email_address)
{
    $domain = get_option('siteurl'); //or home
    $domain = str_replace('http://', '', $domain);
    $domain = str_replace('https://', '', $domain);
    $domain = str_replace('www', '', $domain); //add the . after the www if you don't want it
    return 'noreply@' . $domain;
}

add_filter('wp_mail_from_name', 'pt_custom_wp_mail_name_from');

/**
 * pt_custom_wp_mail_name_from
 *
 * @param  string $original_name
 * @return string
 */
function pt_custom_wp_mail_name_from($original_name)
{
    return get_bloginfo('name');
}
