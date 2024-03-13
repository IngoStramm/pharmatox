<?php

add_shortcode('pt_editor', 'pt_editor');

function pt_editor($atts)
{
    $a = shortcode_atts(array(
        'name' => 'pt_editor',
        'tabindex' => -1,
        'post_id' => ''
    ), $atts);
    $editor_id = 'pt_editor';
    $content = $a['post_id'] ? get_the_content(null, null, $a['post_id']) : null;
    $args = array(
        'media_buttons'     => false, // This setting removes the media button.
        'textarea_name'     => $a['name'], // Set custom name.
        'textarea_rows'     => get_option('default_post_edit_rows', 10), //Determine the number of rows.
        'quicktags'         => false, // Remove view as HTML button.
        'tabindex'          => $a['tabindex'],
        'required'          => true,
        'teeny'             => false,
        'tinymce'           => array(
            'toolbar1'      => 'bold,italic,underline,separator,alignleft,aligncenter,alignright,separator,undo,redo',
            'toolbar2'      => '',
            'toolbar3'      => '',
        ),
    );
    return wp_editor($content, $editor_id, $args);
}

add_shortcode('pt_contact_form', 'pt_contact_form_shortcode');

function pt_contact_form_shortcode($atts)
{
    $a = shortcode_atts(array(
        'name' => 'pt_editor',
        'tabindex' => -1,
        'post_id' => ''
    ), $atts);
    $nome = '';
    $email = '';
    if (is_user_logged_in()) {
        $user = wp_get_current_user();
        $nome = $user->first_name && $user->last_name ?
            $user->first_name . ' ' . $user->last_name :
            $user->display_name;
        $email = $user->user_email;
    }
    $pt_add_contact_form_nonce = wp_create_nonce('pt_contact_form_nonce');
    $form = '';
    $form .=
    '<form class="pt-contact-form needs-validation" role="search" action="' . esc_url(admin_url('admin-post.php')) . '" method="post" id="pt-contact-form" novalidate>

            <div class="row">

                <div class="mb-3">
                    <label for="nome" class="form-label">' . __('Nome', 'pt') . '</label>
                    <input type="text" class="form-control" name="nome" id="nome" value ="' . $nome . '" autocomplete="off" aria-autocomplete="list" aria-label="' . __('Nome', 'pt') . '" tabindex="1" required>
                    <div class="invalid-feedback">' . __('Campo obrigatório', 'pt') . '</div>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">' . __('E-mail', 'pt') . '</label>
                    <input type="text" class="form-control" name="email" id="email" value="' . $email . '" autocomplete="off" aria-autocomplete="list" aria-label="' . __('E-mail', 'pt') . '" tabindex="2" required>
                    <div class="invalid-feedback">' . __('Campo obrigatório', 'pt') . '</div>
                </div>

                <div class="mb-3">
                    <label for="mensagem" class="form-label">' . __('Mensagem', 'pt') . '</label>
                    <textarea class="form-control" name="mensagem" id="mensagem" rows="5" aria-autocomplete="list" aria-label="' . __('Mensagem', 'pt') . '" tabindex="3" required></textarea>
                    <div class="invalid-feedback">' . __('Campo obrigatório', 'pt') . '</div>
                </div>

                <div class="mb-3">
                    <button type="submit" class="btn btn-primary" tabindex="4">' . __('Salvar senha', 'pt') . '</button>
                </div>

            </div>

            <input type="hidden" name="pt_contact_form_nonce" value="' . $pt_add_contact_form_nonce . '" />
            <input type="hidden" value="pt_contact_form" name="action">

        </form>
        <div id="contact-form-alert-placeholder"></div>';

    return $form;
}
