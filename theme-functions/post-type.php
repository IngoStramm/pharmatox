<?php

add_action('init', 'pt_relatorio_post_type', 1);

function pt_relatorio_post_type()
{
    $relatorios = new WT_Post_Type(
        'Relatório', // Nome (Singular) do Post Type.
        'relatorios' // Slug do Post Type.;
    );

    $relatorios->set_labels(
        array(
            'name'               => __('Relatório', 'pt'),
            'singular_name'      => __('Relatório', 'pt'),
            'menu_name'          => __('Relatórios', 'pt'),
            'name_admin_bar'     => __('Relatório', 'pt'),
            'add_new'            => __('Adicionar Relatório', 'pt'),
            'add_new_item'       => __('Adicionar Novo Relatório', 'pt'),
            'new_item'           => __('Novo Relatório', 'pt'),
            'edit_item'          => __('Editar Relatório', 'pt'),
            'view_item'          => __('Visualizar Relatório', 'pt'),
            'all_items'          => __('Todos os Relatórios', 'pt'),
            'search_items'       => __('Pesquisar Relatórios', 'pt'),
            'parent_item_colon'  => __('Relatórios Pai', 'pt'),
            'not_found'          => __('Nenhum Relatório encontrado', 'pt'),
            'not_found_in_trash' => __('Nenhum Relatório encontrado na lixeira.', 'pt'),
        )
    );

    $relatorios->set_arguments(
        array(
            'supports'             => array('editor' , 'revisions'),
            'menu_icon'         => 'dashicons-media-document',
            'show_in_nav_menus' => true
        )
    );
}

add_action('init', 'pt_pacientes_post_type', 1);

function pt_pacientes_post_type()
{
    $pacientes = new WT_Post_Type(
        'Paciente', // Nome (Singular) do Post Type.
        'pacientes' // Slug do Post Type.;
    );

    $pacientes->set_labels(
        array(
            'name'               => __('Paciente', 'pt'),
            'singular_name'      => __('Paciente', 'pt'),
            'menu_name'          => __('Pacientes', 'pt'),
            'name_admin_bar'     => __('Paciente', 'pt'),
            'add_new'            => __('Adicionar Paciente', 'pt'),
            'add_new_item'       => __('Adicionar Novo Paciente', 'pt'),
            'new_item'           => __('Novo Paciente', 'pt'),
            'edit_item'          => __('Editar Paciente', 'pt'),
            'view_item'          => __('Visualizar Paciente', 'pt'),
            'all_items'          => __('Todos os Pacientes', 'pt'),
            'search_items'       => __('Pesquisar Pacientes', 'pt'),
            'parent_item_colon'  => __('Pacientes Pai', 'pt'),
            'not_found'          => __('Nenhum Paciente encontrado', 'pt'),
            'not_found_in_trash' => __('Nenhum Paciente encontrado na lixeira.', 'pt'),
        )
    );

    $pacientes->set_arguments(
        array(
            'supports'             => array('title', 'revisions'),
            'menu_icon'         => 'dashicons-id',
            'show_in_nav_menus' => true
        )
    );
}

add_action('init', 'pt_fornecedores_post_type', 1);

function pt_fornecedores_post_type()
{
    $fornecedores = new WT_Post_Type(
        'Fornecedor', // Nome (Singular) do Post Type.
        'fornecedores' // Slug do Post Type.;
    );

    $fornecedores->set_labels(
        array(
            'name'               => __('Fornecedor', 'pt'),
            'singular_name'      => __('Fornecedor', 'pt'),
            'menu_name'          => __('Fornecedores', 'pt'),
            'name_admin_bar'     => __('Fornecedor', 'pt'),
            'add_new'            => __('Adicionar Fornecedor', 'pt'),
            'add_new_item'       => __('Adicionar Novo Fornecedor', 'pt'),
            'new_item'           => __('Novo Fornecedor', 'pt'),
            'edit_item'          => __('Editar Fornecedor', 'pt'),
            'view_item'          => __('Visualizar Fornecedor', 'pt'),
            'all_items'          => __('Todos os Fornecedores', 'pt'),
            'search_items'       => __('Pesquisar Fornecedores', 'pt'),
            'parent_item_colon'  => __('Fornecedores Pai', 'pt'),
            'not_found'          => __('Nenhum Fornecedor encontrado', 'pt'),
            'not_found_in_trash' => __('Nenhum Fornecedor encontrado na lixeira.', 'pt'),
        )
    );

    $fornecedores->set_arguments(
        array(
            'supports'             => array('title', 'revisions'),
            'menu_icon'         => 'dashicons-businessman',
            'show_in_nav_menus' => true
        )
    );
}
