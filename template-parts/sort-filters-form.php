<?php
$wt_add_form_sort_anuncio_nonce = wp_create_nonce('wt_form_sort_anuncio_nonce');
$selected = isset($_GET['orderby']) && $_GET['orderby'] ? $_GET['orderby'] : 'date_desc';
$order_options = array(
    'date_desc'             => __('Mais recentes primeiro', 'wt'),
    'date_asc'              => __('Mais antigos primeiro', 'wt'),
    'id_asc'             => __('Ordem dos IDs crescente', 'wt'),
    'id_desc'            => __('Ordem dos IDs decrescente', 'wt'),
);
$start_date = isset($_GET['start-date']) && $_GET['start-date'] ? $_GET['start-date'] : null;
$end_date = isset($_GET['end-date']) && $_GET['end-date'] ? $_GET['end-date'] : null;
?>
<form class="filters-form d-md-flex align-items-center justify-content-between gap-3 mb-3 w-100" name="filters-form" method="get">

    <div class="d-md-flex justify-content-start align-items-center gap-3 mb-4 mb-md-0">

        <?php
        // Parei aqui
        // Testar filtros nas categorias e no autor
        ?>
        <label class="form-label flex-shrink-0 mb-0" for="start-date">
            <?php _e('Data inicial', 'wt'); ?>
        </label>
        <span class="d-flex align-items-start">
            <input type="date" class="form-control mb-3 mb-md-0" name="start-date" id="start-date" value="<?php echo $start_date ? $start_date : ''; ?>" />
            <a href="#" class="clear-input-value link-danger ms-1" data-input="start-date"><i class="bi bi-x"></i></a>
        </span>

        <label class="form-label flex-shrink-0 mb-0" for="end-date">
            <?php _e('Data final', 'wt'); ?>
        </label>
        <span class="d-flex align-items-start">
            <input type="date" class="form-control" name="end-date" id="end-date" value="<?php echo $end_date ? $end_date : ''; ?>" />
            <a href="#" class="clear-input-value link-danger ms-1" data-input="end-date"><i class="bi bi-x"></i></a>
        </span>

    </div>

    <div class="d-flex justify-content-end  align-items-center gap-3">
        <select class="form-select" name="orderby" aria-label="<?php _e('Ordenar anúncios', 'wt'); ?>">
            <?php foreach ($order_options as $value => $text) { ?>
                <option value="<?php echo $value; ?>" <?php echo $selected === $value ? 'selected=""' : '' ?>><?php echo $text; ?></option>
            <?php } ?>
        </select>
        <i class="bi bi-arrow-down-up"></i>
        <button class="btn btn-primary"><?php _e('Enviar', 'wt'); ?></button>
    </div>
    <input type="hidden" name="action" value="pt_sort_anuncio_form">
    <?php /* ?><input type="hidden" name="wt_form_sort_anuncio_nonce" value="<?php echo $wt_add_form_sort_anuncio_nonce; ?>"><?php */ ?>
</form>