<?php

add_action('wp_enqueue_scripts', 'pt_frontend_scripts');

function pt_frontend_scripts()
{

    $min = (in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1', '::1', '10.0.0.3'))) ? '' : '.min';
    $version = pt_version();

    if (empty($min)) :
        wp_enqueue_script('pharmatox-livereload', 'http://localhost:35729/livereload.js?snipver=1', array(), null, true);
    endif;

    // wp_register_script('list-js', PT_URL . '/assets/js/list' . $min . '.js', array('jquery'), $version, true);

    wp_register_script('imask-script', PT_URL . '/assets/js/imask.min.js', array('jquery'), $version, true);

    wp_register_script('bootstrap-script', PT_URL . '/assets/js/bootstrap.bundle.min.js', array('jquery'), $version, true);

    wp_register_script('list-js', PT_URL . '/assets/js/list' . $min . '.js', array('jquery'), $version, true);


    wp_register_script('pharmatox-script', PT_URL . '/assets/js/pharmatox' . $min . '.js', array('jquery', 'bootstrap-script', 'imask-script', 'list-js'), $version, true);

    wp_enqueue_script('pharmatox-script');

    wp_localize_script('pharmatox-script', 'ajax_object', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'plugin_url' => PT_URL,

    ));
    wp_enqueue_style('bootstrap-style', PT_URL . '/assets/css/bootstrap.min.css', array(), $version, 'all');
    wp_enqueue_style('bootstrap-icon-style', PT_URL . '/assets/fonts/bootstrap-icons/bootstrap-icons.min.css', array(), $version, 'all');
    wp_enqueue_style('pharmatox-style', PT_URL . '/assets/css/pharmatox.css', array('bootstrap-style'), $version, 'all');
}

add_action('admin_enqueue_scripts', 'pt_admin_scripts');

function pt_admin_scripts()
{
    if (!is_user_logged_in())
        return;

    $version = pt_version();

    $min = (in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1', '::1', '10.0.0.3'))) ? '' : '.min';

    wp_register_script('imask-script', PT_URL . '/assets/js/imask.min.js', array('jquery'), $version, true);

    wp_register_script('pharmatox-admin-script', PT_URL . '/assets/js/pharmatox-admin' . $min . '.js', array('jquery', 'imask-script'), $version, true);

    wp_enqueue_script('pharmatox-admin-script');

    wp_localize_script('pharmatox-admin-script', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
}
