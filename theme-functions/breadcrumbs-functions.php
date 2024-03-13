<?php

/**
 * Display the breadcrumb automaticaly
 * @param  Array $custom_post_types [OPTIONAL] Prevent custom post types with hierarchical structure
 */

function pt_breadcrumbs($custom_post_types = false)
{
    wp_reset_query();
    global $post;
    $output = '';
    $is_custom_post = $custom_post_types ? is_singular('relatorios') : false;

    $output .= '<ol class="breadcrumb breadcrumb-chevron p-3 bg-body-tertiary rounded-3">';
    $output .= '<li class="breadcrumb-item"><a class="link-body-emphasis" href="';
    $output .= get_option('home');
    $output .= '">';
    $output .= '<i class="bi bi-house-door-fill"></i>';
    $output .= '<span class="visually-hidden">' . get_bloginfo('name') . '</span>';
    $output .= "</a></li>";
    if (has_category()) {
        $output .= '<li class="breadcrumb-item active" aria-current="page"><a class="link-body-emphasis fw-semibold text-decoration-none" href="' . esc_url(get_permalink(get_page(get_the_category($post->ID)))) . '">';
        $category = get_the_category();
        if (!is_array($category)) {
            $output .= get_the_category(', ');
        } else {
            $output .= $category[0]->name;
        }
        $output .= '</a></li>';
    }
    if (is_search()) {
        $output .= '<li class="breadcrumb-item active">' . __('Resultados para:', 'pt') . ' "' . get_search_query() . '"</li>';
    } elseif (is_archive() || is_category() || is_single() || $is_custom_post) {
        if (is_category()) {
            $output .= '<li class="breadcrumb-item active"><a class="link-body-emphasis fw-semibold text-decoration-none" href="' . esc_url(get_permalink(get_page(get_the_category($post->ID)))) . '">' . get_the_category($post->ID)[0]->name . '</a></li>';
        }
        if (is_archive() && !is_author() && !is_search()) {
            $current_term = get_queried_object();
            if (isset($current_term->parent) && $current_term->parent) {
                $parent = get_term_by('term_id', $current_term->parent, 'categoria-de-anuncio');
                $output .= '<li class="breadcrumb-item active"><a class="link-body-emphasis fw-semibold text-decoration-none" href="' . esc_url(get_term_link($parent, 'categoria-de-anuncio')) . '">' . $parent->name . '</a></li>';
            }
            // checa se é mesmo um termo
            if (isset($current_term->term_id)) {
                $output .= '<li class="breadcrumb-item active"><a class="link-body-emphasis fw-semibold text-decoration-none" href="' . esc_url(get_term_link($current_term, 'categoria-de-anuncio')) . '">' . $current_term->name . '</a></li>';
            } elseif (isset($current_term->labels) && isset($current_term->labels->name)) {
                $output .= '<li class="breadcrumb-item active">' . $current_term->labels->name . '</li>';
            } else {
                $output .= '<li class="breadcrumb-item active">' . $current_term->name . '</li>';
            }
        }
        if (is_author()) {
            $author_data = get_queried_object();
            $display_name = $author_data->get('first_name') && $author_data->get('last_name') ? $author_data->get('first_name') . ' ' . $author_data->get('last_name') : $author_data->get('display_name');
            $output .= '<li class="breadcrumb-item active">' . $display_name . '</li>';
        }
        if ($is_custom_post) {
            $slug = get_post_type_object(get_post_type($post))->name;
            if ($slug !== 'relatorios') {
                $output .= '<li class="breadcrumb-item active"><a class="link-body-emphasis fw-semibold text-decoration-none" href="' . get_option('home') . '/' . $slug . '">' . get_post_type_object(get_post_type($post))->label . '</a></li>';
            }
            if (has_term('', 'categoria-de-anuncio', $post)) {
                $terms = get_the_terms($post, 'categoria-de-anuncio');
                $children = '';
                $parent = '';
                foreach ($terms as $term) {
                    if ($term->parent) {
                        $children = $term;
                    } else {
                        $parent = $term;
                    }
                }
                if ($children) {
                    $output .= '<li class="breadcrumb-item active"><a class="link-body-emphasis fw-semibold text-decoration-none" href="' . esc_url(get_term_link($parent->slug, 'categoria-de-anuncio')) . '">' . $parent->name . '</a></li>';
                    $output .= '<li class="breadcrumb-item active"><a class="link-body-emphasis fw-semibold text-decoration-none" href="' . esc_url(get_term_link($children->slug, 'categoria-de-anuncio')) . '">' . $children->name . '</a></li>';
                } else {
                    $output .= '<li class="breadcrumb-item active"><a class="link-body-emphasis fw-semibold text-decoration-none" href="' . esc_url(get_term_link($parent->slug, 'categoria-de-anuncio')) . '">' . $parent->name . '</a></li>';
                }
            }
            if ($post->post_parent) {
                $home = get_page(get_option('page_on_front'));
                for ($i = count($post->ancestors) - 1; $i >= 0; $i--) {
                    if (($home->ID) != ($post->ancestors[$i])) {
                        $output .= '<li class="breadcrumb-item"><a class="link-body-emphasis fw-semibold text-decoration-none" href="';
                        $output .= get_permalink($post->ancestors[$i]);
                        $output .= '">';
                        $output .= get_the_title($post->ancestors[$i]);
                        $output .= "</a></li>";
                    }
                }
            }
        }
        if (is_singular('relatorios')) {
            $output .= '<li class="breadcrumb-item active">' . sprintf(__('Relatório #%s', 'pt'), $post->ID) . '</li>';
        } elseif (is_single()) {
            $output .= '<li class="breadcrumb-item active">' . get_the_title($post->ID) . '</li>';
        }
    } elseif (is_page() && $post->post_parent) {
        $home = get_page(get_option('page_on_front'));
        for ($i = count($post->ancestors) - 1; $i >= 0; $i--) {
            if (($home->ID) != ($post->ancestors[$i])) {
                $output .= '<li class="breadcrumb-item"><a class="link-body-emphasis fw-semibold text-decoration-none" href="';
                $output .= get_permalink($post->ancestors[$i]);
                $output .= '">';
                $output .= get_the_title($post->ancestors[$i]);
                $output .= "</a></li>";
            }
        }
        $output .= '<li class="breadcrumb-item active">' . get_the_title($post->ID) . '</li>';
    } elseif (is_page()) {
        $output .= '<li class="breadcrumb-item active">' . get_the_title($post->ID) . '</li>';
    } elseif (is_404()) {
        $output .= '<li class="breadcrumb-item active">404</li>';
    }

    if (!is_home() && !is_front_page()) {
        $output .= '<li class="ms-auto"><a class="go-back-btn link-body-emphasis fw-semibold text-decoration-none" href="#">' . __('Voltar', 'pt') . '</a></li>';
    }
    $output .= '</ol>';
    return $output;
}


/**
 * Breadcrumbs.
 *
 * @since  2.2.0
 *
 * @param  string $homepage  Homepage name.
 *
 * @return string            HTML of breadcrumbs.
 */
/*
function pt_breadcrumbs($homepage = '')
{
    global $wp_query, $post, $author;

    !empty($homepage) || $homepage = __('Home', 'odin');

    // Default html.
    $current_before = '<li class="active">';
    $current_after  = '</li>';

    if (!is_home() && !is_front_page() || is_paged()) {

        // First level.
        echo '<ol class="breadcrumb breadcrumb-chevron p-3 bg-body-tertiary rounded-3">';
        echo '<li class="breadcrumb-item"><a class="link-body-emphasis" href="' . home_url() . '" rel="nofollow"><i class="bi bi-house-door-fill"></i></a></li>';

        // Single post.
        if (is_single() && !is_attachment()) {

            // Checks if is a custom post type.

            $category = get_the_category();
            $category = $category[0];
            // Gets parent post terms.
            $parent_cat = get_term($category->parent, 'category');
            // Gets top term
            $cat_tree = get_category_parents($category, FALSE, ':');
            $top_cat = explode(':', $cat_tree);
            $top_cat = $top_cat[0];

            if ($category->parent) {
                if ($parent_cat->parent) {
                    echo '<li class="breadcrumb-item"><a class="link-body-emphasis fw-semibold text-decoration-none" href="' . get_term_link($top_cat, 'category') . '">' . $top_cat . '</a></li>';
                }
                echo '<li class="breadcrumb-item"><a class="link-body-emphasis fw-semibold text-decoration-none" href="' . get_term_link($parent_cat) . '">' . $parent_cat->name . '</a></li>';
            }

            echo '<li class="breadcrumb-item"><a class="link-body-emphasis fw-semibold text-decoration-none" href="' . get_category_link($category->term_id) . '">' . $category->name . '</a></li>';

            echo $current_before . get_the_title() . $current_after;

            // Single attachment.
        } elseif (is_attachment()) {
            $parent   = get_post($post->post_parent);
            $category = get_the_category($parent->ID);
            $category = $category[0];

            echo '<li class="breadcrumb-item"><a class="link-body-emphasis fw-semibold text-decoration-none" href="' . get_category_link($category->term_id) . '">' . $category->name . '</a></li>';

            echo '<li class="breadcrumb-item"><a class="link-body-emphasis fw-semibold text-decoration-none" href="' . esc_url(get_permalink($parent)) . '">' . $parent->post_title . '</a></li>';

            echo $current_before . get_the_title() . $current_after;

            // Page without parents.
        } elseif (is_page() && !$post->post_parent) {
            echo $current_before . get_the_title() . $current_after;

            // Page with parents.
        } elseif (is_page() && $post->post_parent) {
            $parent_id   = $post->post_parent;
            $breadcrumbs = array();

            while ($parent_id) {
                $page = get_page($parent_id);

                $breadcrumbs[] = '<li class="breadcrumb-item"><a class="link-body-emphasis fw-semibold text-decoration-none" href="' . esc_url(get_permalink($page->ID)) . '">' . get_the_title($page->ID) . '</a></li>';
                $parent_id  = $page->post_parent;
            }

            $breadcrumbs = array_reverse($breadcrumbs);

            foreach ($breadcrumbs as $crumb) {
                echo $crumb . ' ';
            }

            echo $current_before . get_the_title() . $current_after;

            // Category archive.
        } elseif (is_category()) {
            $category_object  = $wp_query->get_queried_object();
            $category_id      = $category_object->term_id;
            $current_category = get_category($category_id);
            $parent_category  = get_category($current_category->parent);

            // Displays parent category.
            if (0 != $current_category->parent) {
                $parents = get_category_parents($parent_category, TRUE, false);
                $parents = str_replace('<a', '<li class="breadcrumb-item"><a class="link-body-emphasis fw-semibold text-decoration-none"', $parents);
                $parents = str_replace('</a>', '</a></li>', $parents);
                echo $parents;
            }

            printf(__('%sCategory: %s%s', 'odin'), $current_before, single_cat_title('', false), $current_after);

            // Tags archive.
        } elseif (is_tag()) {
            printf(__('%sTag: %s%s', 'odin'), $current_before, single_tag_title('', false), $current_after);

            // Custom post type archive.
        } elseif (is_post_type_archive()) {
            echo $current_before . post_type_archive_title('', false) . $current_after;

            // Search page.
        } elseif (is_search()) {
            printf(__('%sSearch result for: &quot;%s&quot;%s', 'odin'), $current_before, get_search_query(), $current_after);

            // Author archive.
        } elseif (is_author()) {
            $userdata = get_userdata($author);

            echo $current_before . __('Posted by', 'odin') . ' ' . $userdata->display_name . $current_after;

            // Archives per days.
        } elseif (is_day()) {
            echo '<li class="breadcrumb-item"><a class="link-body-emphasis fw-semibold text-decoration-none" href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a></li>';

            echo '<li class="breadcrumb-item"><a class="link-body-emphasis fw-semibold text-decoration-none" href="' . get_month_link(get_the_time('Y'), get_the_time('m')) . '">' . get_the_time('F') . '</a></li>';

            echo $current_before . get_the_time('d') . $current_after;

            // Archives per month.
        } elseif (is_month()) {
            echo '<li class="breadcrumb-item"><a class="link-body-emphasis fw-semibold text-decoration-none" href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a></li>';

            echo $current_before . get_the_time('F') . $current_after;

            // Archives per year.
        } elseif (is_year()) {
            echo $current_before . get_the_time('Y') . $current_after;

            // Archive fallback for custom taxonomies.
        } elseif (is_archive()) {
            $current_object = $wp_query->get_queried_object();
            $taxonomy       = get_taxonomy($current_object->taxonomy);
            $term_name      = $current_object->name;

            // Displays the post type that the taxonomy belongs.
            if (!empty($taxonomy->object_type)) {
                $_post_type = array_shift($taxonomy->object_type);
                $post_type = get_post_type_object($_post_type);
                echo '<li class="breadcrumb-item"><a class="link-body-emphasis fw-semibold text-decoration-none" href="' . get_post_type_archive_link($post_type->name) . '">' . $post_type->label . '</a></li> ';
            }

            // Displays parent term.
            if (0 != $current_object->parent) {
                $parent_term = get_term($current_object->parent, $current_object->taxonomy);

                echo '<li class="breadcrumb-item"><a class="link-body-emphasis fw-semibold text-decoration-none" href="' . get_term_link($parent_term) . '">' . $parent_term->name . '</a></li>';
            }

            echo $current_before . $taxonomy->label . ': ' . $term_name . $current_after;

            // 404 page.
        } elseif (is_404()) {
            echo $current_before . __('404 Error', 'odin') . $current_after;
        }

        // Gets pagination.
        if (get_query_var('paged')) {
            echo ' (' . sprintf(__('Page %s', 'abelman'), get_query_var('paged')) . ')';
        }

        echo '</ol>';
    }
}
*/