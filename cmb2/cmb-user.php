<?php

add_action('cmb2_admin_init', 'pt_register_user_profile_metabox');

function pt_register_user_profile_metabox()
{

    /**
     * Metabox for the user profile screen
     */
    $cmb_user = new_cmb2_box(array(
        'id'               => 'pt_user_edit',
        'title'            => esc_html__('Campos customizados do perfil de usuário', 'pt'), // Doesn't output for user boxes
        'object_types'     => array('user'), // Tells CMB2 to use user_meta vs post_meta
        'show_names'       => true,
        'new_user_section' => 'add-new-user', // where form will show on new user page. 'add-existing-user' is only other valid option.
    ));

    $cmb_user->add_field(array(
        'name' => esc_html__('O usuário é um médico?', 'pt'),
        'id'   => 'pt_user_medico',
        'type' => 'checkbox',
    ));

    $cmb_user->add_field(array(
        'name'     => esc_html__('Dados do médico', 'pt'),
        'id'       => 'pt_user_extra_info',
        'type'     => 'title',
        'on_front' => false,
    ));

    $cmb_user->add_field(array(
        'name' => esc_html__('Especialidade do médico', 'pt'),
        'id'   => 'pt_user_especialidade',
        'type' => 'text',
    ));

    $cmb_user->add_field(array(
        'name' => esc_html__('CRM do médico', 'pt'),
        'id'   => 'pt_user_crm',
        'type' => 'text',
    ));

    $cmb_user->add_field(array(
        'name'    => esc_html__('Certificado digital tipo A1', 'cmb2'),
        'desc'    => esc_html__('Formato .pfx', 'cmb2'),
        'id'      => 'pt_user_certificado',
        'type'    => 'file',
    ));

    $cmb_user->add_field(array(
        'name' => esc_html__('Senha do certificado', 'pt'),
        'id'   => 'pt_user_certificado_password',
        'type' => 'text',
        'attributes' => array(
            'type' => 'password',
        ),
    ));
}
