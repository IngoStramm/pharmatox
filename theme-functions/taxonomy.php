<?php

add_action('init', 'pt_relatorio_tax', 1);

function pt_relatorio_tax()
{
    $cid = new WT_Taxonomy(
        __('CID', 'pt'), // Nome (Singular) da nova Taxonomia.
        'cid', // Slug do Taxonomia.
        'relatorios' // Nome do tipo de conteúdo que a taxonomia irá fazer parte.
    );

    $cid->set_labels(
        array(
            'menu_name' => __('CIDs', 'pt')
        )
    );

    $cid->set_arguments(
        array(
            'hierarchical' => false,
            // 'default_term' => array(
            //     'name' => __('Geral', 'pt'),
            //     'slug' => 'geral',
            // )
        )
    );
}
