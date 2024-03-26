<?php

/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Pharmatox
 * @since Twenty Twenty-One 1.0
 */

?>

<?php
$post_id = get_the_ID();

$medico_obj = pt_get_medico_from_relatorio($post_id);
$medico_nome = $medico_obj->nome;
$medico_especialidade = $medico_obj->especialidade;
$medico_crm = $medico_obj->crm;

$paciente_obj = pt_get_paciente_from_relatorio($post_id);
$paciente_nome = $paciente_obj->nome;
$paciente_whatsapp = $paciente_obj->whatsapp;
$paciente_endereco = $paciente_obj->endereco;

$fornecedor_obj = pt_get_fornecedor_from_relatorio($post_id);
$fornecedor_nome = $fornecedor_obj->nome;
$fornecedor_whatsapp = $fornecedor_obj->whatsapp;

$cids = wp_get_post_terms($post_id, 'cid');

$empresa = pt_get_empresa($post_id);

// pt_debug($empresa);
?>

<div class="container">
    <div class="row">
        <div class="col">
            <?php do_action('create_pdf_messages'); ?>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col">
            <header class="page-header alignwide">
                <?php echo pt_breadcrumbs('relatorios'); ?>
            </header><!-- .page-header -->
        </div>
    </div>
</div>

<article id="post-<?php echo $post_id; ?>" <?php post_class(); ?>>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1 class="entry-title"><?php _e('Relatório', 'pt'); ?> #<?php echo $post_id ?></h1>
                <div class="d-flex justify-content-start align-items-center gap-1 my-3">
                    <?php echo pt_show_relatorio_actions($post_id); ?>
                </div>
                <dl class="row">

                    <dt class="col-sm-3"><?php _e('Médico', 'pt'); ?></dt>
                    <dd class="col-sm-9"><?php echo $medico_nome ?></dd>

                    <dt class="col-sm-3"><?php _e('Especialidade', 'pt'); ?></dt>
                    <dd class="col-sm-9"><?php echo $medico_especialidade ?></dd>

                    <dt class="col-sm-3"><?php _e('CRM', 'pt'); ?></dt>
                    <dd class="col-sm-9"><?php echo $medico_crm ?></dd>

                    <dt class="col-sm-3"><?php _e('Paciente', 'pt'); ?></dt>
                    <dd class="col-sm-9"><?php echo $paciente_nome ?></dd>

                    <?php if ($paciente_whatsapp) { ?>
                        <dt class="col-sm-3">
                            <?php _e('WhatsApp do paciente', 'pt'); ?> <i class="bi bi-whatsapp text-success"></i>
                        </dt>
                        <dd class="col-sm-9">
                            <a href="https://wa.me/55<?php echo preg_replace('/[^0-9]/', '', $paciente_whatsapp); ?>" target="_blank">
                                <?php echo $paciente_whatsapp; ?>
                            </a>
                        </dd>
                    <?php } ?>

                    <?php if ($paciente_endereco) { ?>
                        <dt class="col-sm-3"><?php _e('Endereço do paciente', 'pt'); ?></dt>
                        <dd class="col-sm-9"><?php echo $paciente_endereco; ?></dd>
                    <?php } ?>

                    <?php if ($fornecedor_nome) { ?>
                        <dt class="col-sm-3"><?php _e('Fornecedor', 'pt'); ?></dt>
                        <dd class="col-sm-9"><?php echo $fornecedor_nome ?></dd>
                    <?php } ?>

                    <?php if ($fornecedor_whatsapp) { ?>
                        <dt class="col-sm-3">
                            <?php _e('WhatsApp do fornecedor', 'pt'); ?> <i class="bi bi-whatsapp text-success"></i>
                        </dt>
                        <dd class="col-sm-9">
                            <a href="https://wa.me/55<?php echo preg_replace('/[^0-9]/', '', $fornecedor_whatsapp); ?>" target="_blank">
                                <?php echo $fornecedor_whatsapp; ?>
                            </a>
                        </dd>
                    <?php } ?>

                    <dt class="col-sm-3"><?php _e('Doenças (CID)', 'pt'); ?></dt>
                    <dd class="col-sm-9">
                        <?php foreach ($cids as $k => $cid) {
                            $cid_name = $cid->name;
                            $cid_codigo = get_term_meta($cid->term_id, 'pt_cid_codigo', true);
                            if ($k !== 0) {
                                echo ', ';
                            }
                            echo $cid_codigo ? $cid_name . ' (' . $cid_codigo . ')' : $cid_name;
                        } ?>
                    </dd>

                    <dt class="col-sm-3"><?php _e('Data de emissão', 'pt'); ?></dt>
                    <dd class="col-sm-9"><?php echo get_the_date(); ?></dd>

                    <dt class="col-sm-3"><img class="img-fluid my-3" src="<?php echo $empresa->logo_url; ?>" alt="<?php echo sprintf(__('Logotipo da empresa %s', 'pt'), $empresa->nome); ?>" /></dt>

                    <dd class="col-sm-9"></dd>

                    <dt class="col-sm-3"><?php _e('Empresa', 'pt'); ?></dt>
                    <dd class="col-sm-9"><?php echo $empresa->nome; ?></dd>

                    <dt class="col-sm-3"><?php _e('WhatsApp', 'pt'); ?> <i class="bi bi-whatsapp text-success"></i></dt>
                    <dd class="col-sm-9"><a href="https://wa.me/55<?php echo preg_replace('/[^0-9]/', '', $empresa->whatsapp); ?>" target="_blank"><?php echo $empresa->whatsapp; ?></a>

                    <dt class=" col-sm-3"><?php _e('Endereço', 'pt'); ?></dt>
                    <dd class="col-sm-9"><?php echo $empresa->endereco; ?></dd>

                </dl>
                <div class="entry-content">
                    <?php the_content(); ?>
                </div><!-- .entry-content -->
            </div>
        </div>
    </div>

</article><!-- #post-<?php the_ID(); ?> -->