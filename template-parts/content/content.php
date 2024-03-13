<?php

/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Pharmatox
 */

?>

<?php
$post_id = get_the_ID();
$medico_obj = pt_get_medico_from_relatorio($post_id);
$medico_nome = $medico_obj->nome;

$paciente_obj = pt_get_paciente_from_relatorio($post_id);
$paciente_nome = $paciente_obj->nome;

$fornecedor_obj = pt_get_fornecedor_from_relatorio($post_id);
$fornecedor_nome = $fornecedor_obj->nome;
?>

<tr>
    <th scope="row"><a href="<?php echo get_permalink(); ?>">#<?php echo get_the_ID(); ?></a></th>
    <td><?php echo $medico_nome; ?></td>
    <td><?php echo $paciente_nome; ?></td>
    <td><?php echo $fornecedor_nome; ?></td>
    <td><?php echo get_the_date(); ?></td>
    <td>
        <div class="d-flex justify-content-center align-items-center gap-1">
            <?php echo pt_show_relatorio_actions($post_id); ?>
        </div>
    </td>
</tr>