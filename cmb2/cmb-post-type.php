<?php

add_action('cmb2_admin_init', 'pt_cmb_fornecedor');

function pt_cmb_fornecedor()
{
    $cmb = new_cmb2_box(array(
        'id'            => 'pt_fornecedor_metabox',
        'title'         => esc_html__('Informações', 'pt'),
        'object_types'  => array('fornecedores'), // Post type
    ));

    $cmb->add_field(array(
        'name'       => esc_html__('E-mail', 'pt'),
        'id'         => 'pt_fornecedor_email',
        'type'       => 'text_email',
    ));

    $cmb->add_field(array(
        'name'       => esc_html__('WhatsApp', 'pt'),
        'id'         => 'pt_fornecedor_whatsapp',
        'type'       => 'text',
    ));
}

add_action('cmb2_admin_init', 'pt_cmb_paciente');

function pt_cmb_paciente()
{
    $cmb = new_cmb2_box(array(
        'id'            => 'pt_paciente_metabox',
        'title'         => esc_html__('Informações', 'pt'),
        'object_types'  => array('pacientes'), // Post type
    ));

    $cmb->add_field(array(
        'name'       => esc_html__('E-mail', 'pt'),
        'id'         => 'pt_paciente_email',
        'type'       => 'text_email',
    ));

    $cmb->add_field(array(
        'name'       => esc_html__('WhatsApp', 'pt'),
        'id'         => 'pt_paciente_whatsapp',
        'type'       => 'text',
    ));

    $cmb->add_field(array(
        'name'       => esc_html__('Endereço', 'pt'),
        'id'         => 'pt_paciente_endereco',
        'type'       => 'textarea',
        'attributes' => array(
            'rows'  => 3
        )
    ));
}

add_action('cmb2_admin_init', 'pt_cmb_relatorio');

function pt_cmb_relatorio()
{
    $cmb = new_cmb2_box(array(
        'id'            => 'pt_relatorio_metabox',
        'title'         => esc_html__('Informações', 'pt'),
        'object_types'  => array('relatorios'), // Post type
    ));

    $cmb->add_field(array(
        'name'       => esc_html__('Médico', 'pt'),
        'id'         => 'pt_relatorio_medico',
        'type'       => 'select',
        'attributes' => array(
            'required'      => 'required'
        ),
        'options_cb'       => function () {
            $args = array(
                'meta_key'          => 'pt_user_medico',
                'meta_value'        => 'on',
                'order'             => 'ASC',
                'orderby'           => 'first_name'
            );
            $medicos = get_users($args);
            $options = [];
            foreach ($medicos as $medico) {
                $options[$medico->ID] = $medico->first_name && $medico->last_name ? $medico->first_name . ' ' . $medico->last_name : $medico->display_name;
            }
            if (count($options) <= 0) {
                $options[] = __('Nenhum médico encontrado', 'pt');
            }
            return $options;
        }
    ));

    $cmb->add_field(array(
        'name'       => esc_html__('Paciente', 'pt'),
        'id'         => 'pt_relatorio_paciente',
        'type'       => 'select',
        'attributes' => array(
            'required'      => 'required'
        ),
        'options_cb'       => function () {
            $args = array(
                'post_type'          => 'pacientes',
                'numberposts'        => -1,
                'order'             => 'ASC',
                'orderby'           => 'title'
            );
            $pacientes = get_posts($args);
            $options = [];
            foreach ($pacientes as $paciente) {
                $options[$paciente->ID] = $paciente->post_title;
            }
            if (count($options) <= 0) {
                $options[] = __('Nenhum paciente encontrado', 'pt');
            }
            return $options;
        }
    ));

    $cmb->add_field(array(
        'name'       => esc_html__('Fornecedor', 'pt'),
        'id'         => 'pt_relatorio_fornecedor',
        'type'       => 'select',
        'attributes' => array(
            'required'      => 'required'
        ),
        'options_cb'       => function () {
            $args = array(
                'post_type'          => 'fornecedores',
                'numberposts'        => -1,
                'order'             => 'ASC',
                'orderby'           => 'title'
            );
            $pacientes = get_posts($args);
            $options[] = __('Nenhum fornecedor', 'pt');
            foreach ($pacientes as $paciente) {
                $options[$paciente->ID] = $paciente->post_title;
            }
            return $options;
        }
    ));

    $cmb->add_field(array(
        'name'       => esc_html__('Empresa', 'pt'),
        'id'         => 'pt_relatorio_empresa',
        'type'       => 'select',
        'attributes' => array(
            'required'      => 'required'
        ),
        'options_cb'       => function () {
            $args = array(
                'post_type'          => 'empresas',
                'numberposts'        => -1,
                'order'             => 'ASC',
                'orderby'           => 'title'
            );
            $empresas = get_posts($args);
            $options = [];
            foreach ($empresas as $empresa) {
                $options[$empresa->ID] = $empresa->post_title;
            }
            if (count($options) <= 0) {
                $options[] = __('Nenhuma empresa encontrada', 'pt');
            }
            return $options;
        }
    ));
}

add_action('cmb2_admin_init', 'pt_cmb_empresa');

function pt_cmb_empresa()
{
    $cmb = new_cmb2_box(array(
        'id'            => 'pt_empresa_metabox',
        'title'         => esc_html__('Informações', 'pt'),
        'object_types'  => array('empresas'), // Post type
    ));

    $cmb->add_field(array(
        'name' => esc_html__('Logotipo da empresa', 'cmb2'),
        'id'   => 'pt_empresa_logo',
        'type' => 'file',
        'attributes' => array(
            'accept' => '.jpg,.jpeg,.png'
        )
    ));

    $cmb->add_field(array(
        'name'    => esc_html__('Endereço da empresa', 'pt'),
        'id'      => 'pt_endereco_empresa',
        'type'    => 'textarea',
        'attributes'        => array(
            'rows'      => 3
        )
    ));

    $cmb->add_field(array(
        'name'    => esc_html__('WhatsApp da empresa', 'pt'),
        'id'      => 'pt_whatsapp_empresa',
        'type'    => 'text',
    ));
}
