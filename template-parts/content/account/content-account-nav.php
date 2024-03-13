<?php
$curr_page = $args['curr-page'];
$user_type = $args['user-type'];
$links = [];
$links[] = $args['account'];
if ($user_type === 'comprador') {
    $links[] = $args['edit-anuncio'];
    $links[] = $args['new-leads'];
    $links[] = $args['my-anuncios'];
} else {
    $links[] = $args['cat-config'];
    $links[] = $args['contacted-anuncios'];
    $links[] = $args['following-terms-anuncios'];
}
?>
<nav class="nav nav-pills nav-fill mb-4">
    <?php
    foreach ($links as $link) {
        echo '<a class="nav-link';
        if ($curr_page === $link) {
            echo ' active" aria-current="page"';
        } else {
            echo '"';
        }
        echo ' href="' . get_page_link($link) . '">' . get_the_title($link) . '</a>';
    }
    ?>
</nav>