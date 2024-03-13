<?php
$terms = get_terms(array(
    'taxonomy'   => 'categoria-de-anuncio',
    'hide_empty' => false,
));
// pt_debug($terms);
?>

<form class="" role="search" action="<?php echo site_url('/'); ?>" method="get" id="searchform">
    <div class="input-group mb-3">
        <input type="text" name="s" class="form-control" placeholder="<?php _e('Pesquisar', 'pt'); ?>" aria-label="<?php _e('Pesquisar', 'pt'); ?>" aria-describedby="button-addon2">
        <input type="hidden" name="post_type" value="anuncios" />
        <button class="btn btn-secondary" type="button" id="button-addon2"><i class="bi bi-search"></i></button>
    </div>
</form>

<?php // echo pt_show_anuncio_terms_nav($terms);
