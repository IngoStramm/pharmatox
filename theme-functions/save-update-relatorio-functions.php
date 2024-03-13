<?php

add_filter('wp_insert_post_data', 'wt_modify_post_title', '99', 2); // Grabs the inserted post data so you can modify it.

function wt_modify_post_title($data, $postarr)
{
    if (
        $data['post_type'] === 'relatorios' &&
        isset($_POST['pt_relatorio_medico']) &&
        isset($_POST['pt_relatorio_paciente'])
    ) {
        $medico_data = get_userdata($_POST['pt_relatorio_medico']);
        $medico_nome = $medico_nome = $medico_data->first_name && $medico_data->last_name ? $medico_data->first_name && $medico_data->last_name : $medico_data->display_name;
        $paciente_nome = get_the_title($_POST['pt_relatorio_paciente']);
        // $date = $data['post_date']; // j \d\e F \d\e Y
        // $date = date('j \d\e F \d\e Y', strtotime($data['post_date']));
        $date = wp_date('j \d\e F \d\e Y', strtotime($data['post_date']));
        $title = sprintf(__('Relatório #%s, do médico: "%s", para o paciente "%s", criado em %s'), $postarr['ID'], $medico_nome, $paciente_nome, $date);
        $data['post_title'] =  $title; //Updates the post title to your new title.
    }
    return $data; // Returns the modified data.
}
