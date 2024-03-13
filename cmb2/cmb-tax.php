<?php

add_action('cmb2_admin_init', 'pt_cmb_cid');

function pt_cmb_cid()
{
    $cmb = new_cmb2_box(array(
        'id'            => 'pt_cid_metabox',
        'title'         => esc_html__('Informações', 'pt'),
        'object_types'  => array('term'), // Post type
        'taxonomies'       => array('cid'),
    ));

    $cmb->add_field(array(
        'name'       => esc_html__('Código', 'pt'),
        'id'         => 'pt_cid_codigo',
        'type'       => 'text',
    ));
}
