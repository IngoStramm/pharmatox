<?php

/**
 * pt_debug
 *
 * @param  mixed $a
 * @return string
 */
function pt_debug($a)
{
    echo '<pre>';
    var_dump($a);
    echo '</pre>';
}

/**
 * pt_version
 *
 * @return string
 */
function pt_version()
{
    $version = '1.0.0';
    return $version;
}

/**
 * pt_logo
 *
 * @return string
 */
function pt_logo()
{
    $html = '';
    if (has_custom_logo()) {
        $custom_logo_id = get_theme_mod('custom_logo');
        $image = wp_get_attachment_image_src($custom_logo_id, 'full');
        $html .= '<img class="site-logo img-fluid" src="' . $image[0] . '" />';
    }
    return $html;
}

/**
 * pt_check_if_plugin_is_active
 *
 * @param  string $plugin
 * @return boolean
 */
function pt_check_if_plugin_is_active($plugin)
{
    $active_plugins = get_option('active_plugins');
    return in_array($plugin, $active_plugins);
}

/**
 * pt_get_pages
 *
 * @return array
 */
function pt_get_pages()
{
    $pages = get_pages();
    $return_array = [];
    foreach ($pages as $page) {
        $return_array[$page->ID] = $page->post_title;
    }
    return $return_array;
}

/**
 * pt_get_option
 *
 * @param  string $key
 * @param  boolean $default
 * @return mixed
 */
function pt_get_option($key = '', $default = false)
{
    if (function_exists('cmb2_get_option')) {
        // Use cmb2_get_option as it passes through some key filters.
        return cmb2_get_option('pt_main_options', $key, $default);
    }
    // Fallback to get_option if CMB2 is not loaded yet.
    $opts = get_option('pt_main_options', $default);
    $val = $default;
    if ('all' == $key) {
        $val = $opts;
    } elseif (is_array($opts) && array_key_exists($key, $opts) && false !== $opts[$key]) {
        $val = $opts[$key];
    }
    return $val;
}

/**
 * pt_get_url
 *
 * @return string
 */
function pt_get_url()
{
    $url = $_SERVER['HTTP_HOST'];
    $url .= $_SERVER['REQUEST_URI'];
    return $url;
}

/**
 * pt_get_page_id
 *
 * @param  string $slug ('login', 'newuser', 'lostpassword', 'resetpassword', 'account', 'editanuncio', 'catanuncioconfig', 'myleads', 'myanuncios', 'contactedanuncios', 'followingtermsanuncios')
 * @return string
 */
function pt_get_page_id($slug)
{
    $return_id = '';
    switch ($slug) {
        case 'login':
            $login_page_id = pt_get_option('pt_login_page');
            if ($login_page_id) {
                $return_id = $login_page_id;
            }
            break;

        case 'newuser':
            $new_user_page_id = pt_get_option('pt_new_user_page');
            if ($new_user_page_id) {
                $return_id = $new_user_page_id;
            }
            break;

        case 'lostpassword':
            $lostpassword_page_id = pt_get_option('pt_lostpassword_page');
            if ($lostpassword_page_id) {
                $return_id = $lostpassword_page_id;
            }
            break;

        case 'resetpassword':
            $resetpassword_page_id = pt_get_option('pt_resetpassword_page');
            if ($resetpassword_page_id) {
                $return_id = $resetpassword_page_id;
            }
            break;

        case 'account':
            $account_page_id = pt_get_option('pt_account_page');
            if ($account_page_id) {
                $return_id = $account_page_id;
            }
            break;

        case 'editanuncio':
            $account_edit_anuncio_page_id = pt_get_option('pt_edit_anuncio_page');
            if ($account_edit_anuncio_page_id) {
                $return_id = $account_edit_anuncio_page_id;
            }
            break;

        case 'catanuncioconfig':
            $account_cat_config_anuncio_page_id = pt_get_option('pt_categorias_settings_page');
            if ($account_cat_config_anuncio_page_id) {
                $return_id = $account_cat_config_anuncio_page_id;
            }
            break;

        case 'myleads':
            $account_new_leads_page_id = pt_get_option('pt_my_leads_page');
            if ($account_new_leads_page_id) {
                $return_id = $account_new_leads_page_id;
            }
            break;

        case 'myanuncios':
            $account_my_leads_page_id = pt_get_option('pt_my_anuncios_page');
            if ($account_my_leads_page_id) {
                $return_id = $account_my_leads_page_id;
            }
            break;

        case 'contactedanuncios':
            $account_contacted_anuncios_page_id = pt_get_option('pt_contacted_anuncios_page');
            if ($account_contacted_anuncios_page_id) {
                $return_id = $account_contacted_anuncios_page_id;
            }
            break;

        case 'followingtermsanuncios':
            $account_following_terms_anuncios_page_id = pt_get_option('pt_following_terms_anuncios_page');
            if ($account_following_terms_anuncios_page_id) {
                $return_id = $account_following_terms_anuncios_page_id;
            }
            break;

        default:
            $return_id = get_option('page_for_posts');
            break;
    }
    return $return_id;
}

/**
 * pt_get_page_url
 *
 * @param  string $slug ('login', 'newuser', 'lostpassword', 'resetpassword', 'account', 'editanuncio', 'catanuncioconfig', 'myleads', 'myanuncios', 'contactedanuncios', 'followingtermsanuncios')
 * @return string
 */
function pt_get_page_url($slug)
{
    $return_url = '';
    switch ($slug) {
        case 'login':
            $login_page_id = pt_get_page_id('login');
            if ($login_page_id) {
                $return_url = get_page_link($login_page_id);
            }
            break;

        case 'newuser':
            $new_user_page_id = pt_get_page_id('newuser');
            if ($new_user_page_id) {
                $return_url = get_page_link($new_user_page_id);
            }
            break;

        case 'lostpassword':
            $lostpassword_page_id = pt_get_page_id('lostpassword');
            if ($lostpassword_page_id) {
                $return_url = get_page_link($lostpassword_page_id);
            }
            break;

        case 'resetpassword':
            $resetpassword_page_id = pt_get_page_id('resetpassword');
            if ($resetpassword_page_id) {
                $return_url = get_page_link($resetpassword_page_id);
            }
            break;

        case 'account':
            $account_page_id = pt_get_page_id('account');
            if ($account_page_id) {
                $return_url = get_page_link($account_page_id);
            }
            break;

        case 'editanuncio':
            $account_edit_anuncio_page_id = pt_get_page_id('editanuncio');
            if ($account_edit_anuncio_page_id) {
                $return_url = get_page_link($account_edit_anuncio_page_id);
            }
            break;

        case 'catanuncioconfig':
            $account_cat_config_anuncio_page_id = pt_get_page_id('catanuncioconfig');
            if ($account_cat_config_anuncio_page_id) {
                $return_url = get_page_link($account_cat_config_anuncio_page_id);
            }
            break;

        case 'myleads':
            $account_my_leads_page_id = pt_get_page_id('myleads');
            if ($account_my_leads_page_id) {
                $return_url = get_page_link($account_my_leads_page_id);
            }
            break;

        case 'myanuncios':
            $account_my_leads_page_id = pt_get_page_id('myanuncios');
            if ($account_my_leads_page_id) {
                $return_url = get_page_link($account_my_leads_page_id);
            }
            break;

        case 'contactedanuncios':
            $account_contacted_anuncios_page_id = pt_get_page_id('contactedanuncios');
            if ($account_contacted_anuncios_page_id) {
                $return_url = get_page_link($account_contacted_anuncios_page_id);
            }
            break;

        case 'followingtermsanuncios':
            $account_following_terms_anuncios_page_id = pt_get_page_id('followingtermsanuncios');
            if ($account_following_terms_anuncios_page_id) {
                $return_url = get_page_link($account_following_terms_anuncios_page_id);
            }
            break;

        default:
            $return_url = get_home_url();
            break;
    }
    return $return_url;
}

function pt_especialidade_terms()
{
    $terms = get_terms(array(
        'taxonomy'   => 'especialidade',
        'hide_empty' => false,
    ));
    $array_terms = array();
    foreach ($terms as $term) {
        if (!$term->parent) {
            $array_terms[$term->term_id] = $term->name;
            foreach ($terms as $term2) {
                if ($term2->parent === $term->term_id) {
                    $array_terms[$term2->term_id] = $term2->name;
                }
            }
        }
    }
    return $array_terms;
}

/**
 * pt_alert_small
 *
 * @param  string $type
 * @param  string $message
 * @return string
 */
function pt_alert_small($type = 'success', $message)
{
    if (!$message) {
        return;
    }
    $output = '';
    $output .= '
    <div class="alert alert-' . $type . ' alert-dismissible d-flex align-items-center gap-2 fade show" role="alert">
        <i class="bi bi-exclamation-triangle-fill"></i>
        <div>' . $message . '</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    ';
    return $output;
}

/**
 * Pagination.
 *
 * @since  2.2.0
 *
 * @global array $wp_query   Current WP Query.
 * @global array $wp_rewrite URL rewrite rules.
 *
 * @param  int   $mid   Total of items that will show along with the current page.
 * @param  int   $end   Total of items displayed for the last few pages.
 * @param  bool  $show  Show all items.
 * @param  mixed $query Custom query.
 *
 * @return string       Return the pagination.
 */
function pt_pagination($mid = 2, $end = 1, $show = false, $query = null)
{
    // Prevent show pagination number if Infinite Scroll of JetPack is active.
    if (!isset($_GET['infinity'])) {

        global $wp_query, $wp_rewrite;

        $total_pages = $wp_query->max_num_pages;

        if (is_object($query) && null != $query) {
            $total_pages = $query->max_num_pages;
        }

        if ($total_pages > 1) {
            $url_base = $wp_rewrite->pagination_base;
            $big = 999999999;

            // Sets the paginate_links arguments.
            $arguments = apply_filters(
                'odin_pagination_args',
                array(
                    'base'      => esc_url_raw(str_replace($big, '%#%', get_pagenum_link($big, false))),
                    'format'    => '',
                    'current'   => max(1, get_query_var('paged')),
                    'total'     => $total_pages,
                    'show_all'  => $show,
                    'end_size'  => $end,
                    'mid_size'  => $mid,
                    'type'      => 'list',
                    'prev_text' => '<span aria-hidden="true">&laquo;</span>',
                    'next_text' => '<span aria-hidden="true">&raquo;</span>',
                )
            );

            // Aplica o HTML/classes CSS do bootstrap
            $pt_paginate_links = paginate_links($arguments);
            // $pt_paginate_links = str_replace('page-numbers', 'pagination', paginate_links($arguments));
            $pt_paginate_links = str_replace('<li>', '<li class="page-item">', $pt_paginate_links);
            $pt_paginate_links = str_replace('<li class="page-item"><span aria-current="page" class="page-numbers current">', '<li class="page-item active"><a class="page-link" href="">', $pt_paginate_links);
            $pt_paginate_links = str_replace('</span></li>', '</a></li>', $pt_paginate_links);
            $pt_paginate_links = str_replace('<a class="page-numbers"', '<a class="page-link"', $pt_paginate_links);
            $pt_paginate_links = str_replace('page-numbers dots', 'page-link dots', $pt_paginate_links);
            $pt_paginate_links = str_replace('<a class="next page-numbers"', '<a class="page-link"', $pt_paginate_links);
            $pt_paginate_links = str_replace('<a class="prev page-numbers"', '<a class="page-link"', $pt_paginate_links);
            $pt_paginate_links = str_replace('<span class="page-link dots">', '<a class="page-link dots" href="">', $pt_paginate_links);
            $pt_paginate_links = str_replace('</span>', '</a>', $pt_paginate_links);
            $pt_paginate_links = str_replace('<ul class=\'page-numbers\'>', '<ul class="pagination justify-content-center">', $pt_paginate_links);
            $pt_paginate_links = str_replace('<li class="page-item"><a class="page-link dots" href="">', '<li class="page-item disabled"><a class="page-link dots" href="">', $pt_paginate_links);

            $pagination = '<div class="my-4"><nav aria-label="Page navigation">' . $pt_paginate_links . '</nav></div>';

            // Prevents duplicate bars in the middle of the url.
            if ($url_base) {
                $pagination = str_replace('//' . $url_base . '/', '/' . $url_base . '/', $pagination);
            }

            return $pagination;
        }
    }
}

if (!function_exists('pt_paging_nav')) {

    /**
     * Print HTML with meta information for the current post-date/time and author.
     *
     * @since 2.2.0
     */
    function pt_paging_nav()
    {
        $mid  = 2;     // Total of items that will show along with the current page.
        $end  = 1;     // Total of items displayed for the last few pages.
        $show = false; // Show all items.

        echo pt_pagination($mid, $end, false);
    }
}


/**
 * pt_format_phone_number
 *
 * @param  string $phone
 * @return string
 */
function pt_format_phone_number($phone)
{
    $formated_phone = preg_replace('/[^0-9]/', '', $phone);
    $matches = [];
    preg_match('/^([0-9]{2})([0-9]{4,5})([0-9]{4})$/', $formated_phone, $matches);
    if ($matches) {
        return '(' . $matches[1] . ') ' . $matches[2] . '-' . $matches[3];
    }
    return $phone;
}

/**
 * pt_get_medico_from_relatorio
 *
 * @param  int $post_id
 * @return object
 */
function pt_get_medico_from_relatorio($post_id)
{
    $medico_obj = new stdClass();
    $medico_id = get_post_meta($post_id, 'pt_relatorio_medico', true);
    $medico_data = get_userdata($medico_id);

    $medico_obj->id = $medico_id;
    $medico_obj->nome = $medico_data->first_name && $medico_data->last_name ? $medico_data->first_name . ' ' . $medico_data->last_name : $medico_data->display_name;
    $medico_obj->email = $medico_data->user_email;
    $medico_obj->is_medico = get_user_meta($medico_id, 'pt_user_medico', true);
    $medico_obj->especialidade = get_user_meta($medico_id, 'pt_user_especialidade', true);
    $medico_obj->crm = get_user_meta($medico_id, 'pt_user_crm', true);
    $medico_obj->certificado = get_user_meta($medico_id, 'pt_user_certificado', true);

    return $medico_obj;
}

/**
 * pt_get_paciente_from_relatorio
 *
 * @param  int $post_id
 * @return object
 */
function pt_get_paciente_from_relatorio($post_id)
{
    $paciente_obj = new stdClass();
    $paciente_id = get_post_meta($post_id, 'pt_relatorio_paciente', true);
    $paciente_obj->nome = get_the_title($paciente_id);
    $paciente_obj->email = get_post_meta($paciente_id, 'pt_paciente_email', true);
    $paciente_obj->whatsapp = get_post_meta($paciente_id, 'pt_paciente_whatsapp', true);
    $paciente_obj->endereco = get_post_meta($paciente_id, 'pt_paciente_endereco', true);
    return $paciente_obj;
}

/**
 * pt_get_fornecedor_from_relatorio
 *
 * @param  int $post_id
 * @return object
 */
function pt_get_fornecedor_from_relatorio($post_id)
{
    $fornecedor_obj = new stdClass();
    $fornecedor_id = get_post_meta($post_id, 'pt_relatorio_fornecedor', true);
    $fornecedor_obj->nome = get_the_title($fornecedor_id);
    $fornecedor_obj->email = get_post_meta($fornecedor_id, 'pt_fornecedor_email', true);
    $fornecedor_obj->whatsapp = get_post_meta($fornecedor_id, 'pt_fornecedor_whatsapp', true);
    return $fornecedor_obj;
}

/**
 * pt_get_empresa
 *
 * @return object
 */
function pt_get_empresa()
{
    $empresa_data = get_option('pt_secondary_options');
    $empresa_obj = new StdClass();
    $empresa_obj->logo_url = $empresa_data['pt_empresa_logo'];
    $empresa_obj->logo_id = $empresa_data['pt_empresa_logo_id'];
    $empresa_obj->nome = $empresa_data['pt_nome_empresa'];
    $empresa_obj->endereco = $empresa_data['pt_endereco_empresa'];
    $empresa_obj->whatsapp = $empresa_data['pt_whatsapp_empresa'];
    return $empresa_obj;
}

function pt_get_certificados_dir() {
    $wp_upload_dir = wp_upload_dir();
    $certificados_dir = $wp_upload_dir['basedir'] . '/certificados';
    if (!is_dir($certificados_dir)) {
        if (!mkdir($certificados_dir, 0755, true)) {
            return __('Não foi criar o diretório para armazenar o certificado.', 'pt');
        }
    }
    return $certificados_dir;
}

function pt_get_pdf_dir()
{
    $wp_upload_dir = wp_upload_dir();
    $pdf_dir = $wp_upload_dir['basedir'] . '/pdf';
    if (!is_dir($pdf_dir)) {
        if (!mkdir(
            $pdf_dir,
            0755,
            true
        )) {
            return __('Não foi criar o diretório para armazenar o certificado.', 'pt');
        }
    }
    return $pdf_dir;
}

function pt_get_pdf_url() {
    $wp_upload_dir = wp_upload_dir();
    $pdf_url = $wp_upload_dir['baseurl'] . '/pdf';
    return $pdf_url;
}
