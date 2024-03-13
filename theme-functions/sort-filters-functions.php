<?php

add_filter('posts_search', 'pt_db_filter_authors_search');

/**
 * pt_db_filter_authors_search
 *
 * Include posts from authors in the search results where
 * either their display name or user login matches the query string
 * 
 * @param  string $posts_search
 * @return string
 */
function pt_db_filter_authors_search($posts_search)
{

    // Don't modify the query at all if we're not on the search template or if the LIKE is empty
    if (!is_search() || empty($posts_search)) return $posts_search;

    global $wpdb;

    // Get all of the users of the blog and see if the search query matches either the display name or the user login
    add_filter('pre_user_query', 'pt_db_filter_user_query');

    $search = sanitize_text_field(get_query_var('s'));

    $args = array(
        'count_total' => false,
        'search' => sprintf('*%s*', $search),
        'search_fields' => array(
            'display_name',
            'user_login',
        ),
        'fields' => 'ID',
    );

    $matching_users = get_users($args);

    remove_filter('pre_user_query', 'pt_db_filter_user_query');

    // Don't modify the query if there aren't any matching users
    if (empty($matching_users)) return $posts_search;

    // Take a slightly different approach than core where we want all of the posts from these authors
    $posts_search = str_replace(')))', ")) OR ( {$wpdb->posts}.post_author IN (" . implode(',', array_map('absint', $matching_users)) . ")))", $posts_search);
    return $posts_search;
}

/**
 * pt_db_filter_user_query
 * 
 * Modify get_users() to search display_name instead of user_nicename. Helper function used inside of db_filter_authors_search()
 *
 * @param  WP_Query $user_query
 * @return WP_Query
 */
function pt_db_filter_user_query(&$user_query)
{

    if (is_object($user_query))
        $user_query->query_where = str_replace("user_nicename LIKE", "display_name LIKE", $user_query->query_where);
    return $user_query;
}

add_action('pre_get_posts', 'pt_sort_ordering_query');

/**
 * pt_sort_ordering_query
 *
 * @param  mixed $query
 * @return void
 */
function pt_sort_ordering_query($wp_query)
{
    // if (!isset($_GET['pt_form_sort_anuncio_nonce']) || !wp_verify_nonce($_GET['pt_form_sort_anuncio_nonce'], 'pt_form_sort_anuncio_nonce')) {
    //     return;
    // }

    if (!isset($_GET['action']) || $_GET['action'] !== 'pt_sort_anuncio_form') {
        return;
    }

    if (!isset($_GET['orderby']) || !$_GET['orderby']) {
        return;
    }

    $orderby = $_GET['orderby'];
    $order_array = array(
        'date_asc' => array(
            'orderby'   => 'date',
            'order'     => 'ASC'
        ),
        'date_desc' => array(
            'orderby'   => 'date',
            'order'     => 'DESC'
        ),
        'id_asc' => array(
            'orderby'   => 'id',
            'order'     => 'ASC'
        ),
        'id_desc' => array(
            'orderby'   => 'id',
            'order'     => 'DESC'
        ),
    );

    $start_date = isset($_GET['start-date']) && $_GET['start-date'] ? $_GET['start-date'] : null;
    $end_date = isset($_GET['end-date']) && $_GET['end-date'] ? $_GET['end-date'] : null;

    if ((is_home() || is_author() || is_search() || is_archive()) && is_main_query() && !is_admin() && $wp_query->get('post_type') !== 'nav_menu_item') {
        $wp_query->set('orderby', $order_array[$orderby]['orderby']);
        $wp_query->set('order', $order_array[$orderby]['order']);

        $date_query_array = array();

        if ($start_date) {
            $start_date = strtotime($start_date . '-1 day');
            $date_query_array['after'] = array(
                'day' => date('d', $start_date),
                'month' => date('m', $start_date),
                'year' => date('Y', $start_date),
            );
        }

        if ($end_date) {
            $end_date = strtotime($end_date . '+1 day');
            $date_query_array['before'] = array(
                'day' => date('d', $end_date),
                'month' => date('m', $end_date),
                'year' => date('Y', $end_date),
            );
        }

        // pt_debug($date_query_array);

        if (count($date_query_array) > 0) {
            $wp_query->set('date_query', $date_query_array);
        }
    }
}
