<?php
$user = wp_get_current_user();
$user_id = $user->get('id');
$account_page_id = pt_get_option('pt_account_page');
$redirect_to = $account_page_id ? get_page_link($account_page_id) : get_home_url();
$pt_add_form_update_user_nonce = wp_create_nonce('pt_form_update_user_nonce');
$certificado = get_user_meta($user_id, 'pt_user_certificado', true);
$certificado_password = get_user_meta($user_id, 'pt_user_certificado_password', true);
?>
<form name="update-user-form" id="update-user-form" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" class="needs-validation" enctype="multipart/form-data" novalidate>
    <div class="row">
        <div class="mb-3">
            <label for="user_name" class="form-label"><?php _e('Nome', 'pt'); ?></label>
            <input type="text" class="form-control" id="user_name" name="user_name" tabindex="1" value="<?php echo $user->get('first_name'); ?>" required>
            <div class="invalid-feedback"><?php _e('Campo obrigatório', 'pt'); ?></div>
        </div>

        <div class="mb-3">
            <label for="user_surname" class="form-label"><?php _e('Sobrenome', 'pt'); ?></label>
            <input type="text" class="form-control" id="user_surname" name="user_surname" tabindex="2" value="<?php echo $user->get('last_name'); ?>" required>
            <div class="invalid-feedback"><?php _e('Campo obrigatório', 'pt'); ?></div>
        </div>

        <div class="mb-3">
            <label for="user_email" class="form-label"><?php _e('E-mail', 'pt') ?></label>
            <input type="email" class="form-control" id="user_email" name="user_email" tabindex="3" value="<?php echo $user->get('user_email'); ?>" required>
            <div class="invalid-feedback"><?php _e('Campo obrigatório', 'pt'); ?></div>
        </div>

        <div class="mb-3">
            <label for="user_especialidade" class="form-label"><?php _e('Especialidade do médico', 'pt') ?></label>
            <input type="text" class="form-control phone-input" id="user_especialidade" name="user_especialidade" tabindex="4" value="<?php echo get_user_meta($user_id, 'pt_user_especialidade', true); ?>">
            <div class="invalid-feedback"><?php _e('Campo obrigatório', 'pt'); ?></div>
        </div>

        <div class="mb-3">
            <label for="user_crm" class="form-label"><?php _e('CRM do médico', 'pt') ?></label>
            <input type="text" class="form-control phone-input" id="user_crm" name="user_crm" tabindex="5" value="<?php echo get_user_meta($user_id, 'pt_user_crm', true); ?>">
            <div class="invalid-feedback"><?php _e('Campo obrigatório', 'pt'); ?></div>
        </div>

        <div class="mb-3 pt-file-pfx-preview">
            <label for="user_certificado" class="form-label"><?php _e('Certificado digital tipo A1', 'pt') ?></label>
            <input type="file" class="form-control" id="user_certificado" name="user_certificado" accept=".pfx" value="<?php echo get_user_meta($user_id, 'user_certificado', true); ?>" tabindex="6" <?php echo !$certificado ? '' : 'style="display: none;"'; ?>>
            <div class="form-text"><?php _e('Arquivo aceito: ".pfx".'); ?></div>
            <div class="invalid-feedback"><?php _e('Campo obrigatório', 'pt'); ?></div>
            <p class="pfx-preview" <?php echo $certificado ? '' : 'style="display: none;"'; ?>>
                <span><?php printf(__('Arquivo: <strong>%s</strong>', 'pt'), basename($certificado)); ?></span>
                (<a href="<?php echo $certificado ?>" download><?php _e('Download', 'pt'); ?></a> / <a class="btn-clear-pfx" href="#"><?php _e('Remover', 'pt'); ?></a>)
                <input type="hidden" name="changed-pfx" value="false">
            </p>
        </div>

        <div class="mb-3">
            <label for="certificado_pass" class="form-label"><?php _e('Senha do certificado', 'pt'); ?></label>
            <input type="password" class="form-control" name="certificado_pass" id="certificado_pass" value="<?php echo $certificado_password; ?>" autocomplete="off" aria-autocomplete="list" aria-label="CertificadoPassword" aria-describedby="certificadoPasswordHelp" tabindex="7">
            <div class="invalid-feedback"><?php _e('Campo obrigatório', 'pt'); ?></div>
        </div>

        <div class="mb-3">
            <label for="user_pass" class="form-label"><?php _e('Senha do usuário', 'pt'); ?></label>
            <input type="password" class="form-control" name="user_pass" id="user_pass" autocomplete="off" aria-autocomplete="list" aria-label="Password" aria-describedby="passwordHelp" tabindex="8">
            <div class="invalid-feedback"><?php _e('Campo obrigatório', 'pt'); ?></div>
            <div class="password-meter">
                <div class="meter-section rounded me-2"></div>
                <div class="meter-section rounded me-2"></div>
                <div class="meter-section rounded me-2"></div>
                <div class="meter-section rounded"></div>
            </div>
            <div id="passwordHelp" class="form-text text-muted"><?php _e('Use 8 ou mais caracteres com uma mistura de letras, números e símbolos.', 'pt'); ?></div>
        </div>

        <div class="mb-3">
            <button type="submit" class="btn btn-primary" tabindex="9"><?php _e('Salvar', 'pt'); ?></button>

        </div>
    </div>

    <input type="hidden" name="pt_form_update_user_nonce" value="<?php echo $pt_add_form_update_user_nonce ?>" />
    <input type="hidden" value="pt_update_user_form" name="action">
    <input type="hidden" value="<?php echo $user_id; ?>" name="user_id">
    <input type="hidden" value="<?php echo esc_attr($redirect_to); ?>" name="redirect_to">
</form>