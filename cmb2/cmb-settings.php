<?php

add_action('cmb2_admin_init', 'pt_register_settings_options_metabox');
/**
 * Hook in and register a metabox to handle a settings options page and adds a menu item.
 */
function pt_register_settings_options_metabox()
{
    $args = array(
        'id'           => 'pt_main_options_page',
        'menu_title'        => esc_html__('Configurações Pharmatox', 'pt'),
        'object_types' => array('options-page'),
        'option_key'   => 'pt_main_options',
        'tab_group'    => 'pt_main_options',
        'tab_title'    => esc_html__('Configurações', 'pt'),
    );

    // 'tab_group' property is supported in > 2.4.0.
    if (version_compare(CMB2_VERSION, '2.4.0')) {
        $args['display_cb'] = 'pt_options_display_with_tabs';
    }

    $main_options = new_cmb2_box($args);

    $main_options->add_field(array(
        'name'    => esc_html__('E-mails que receberão as mensagens do formulário de contato.', 'pt'),
        'id'      => 'pt_contact_form_emails',
        'type'    => 'text_email',
        'repeatable'    => true,
        'required'      => true
    ));

    $main_options->add_field(array(
        'name'    => esc_html__('Página de login', 'pt'),
        'id'      => 'pt_login_page',
        'type'    => 'select',
        'options' => function () {
            $pages = pt_get_pages();
            $array = [];
            $array[''] = __('Selecione uma página', 'pt');
            foreach ($pages as $id => $title) {
                $array[$id] = $title;
            }
            return $array;
        },
        'required'      => true
    ));

    $main_options->add_field(array(
        'name'    => esc_html__('Página de cadastro de novo usuário', 'pt'),
        'id'      => 'pt_new_user_page',
        'type'    => 'select',
        'options' => function () {
            $pages = pt_get_pages();
            $array = [];
            $array[''] = __('Selecione uma página', 'pt');
            foreach ($pages as $id => $title) {
                $array[$id] = $title;
            }
            return $array;
        },
        'required'      => true
    ));

    $main_options->add_field(array(
        'name'    => esc_html__('Página de senha perdida', 'pt'),
        'id'      => 'pt_lostpassword_page',
        'type'    => 'select',
        'options' => function () {
            $pages = pt_get_pages();
            $array = [];
            $array[''] = __('Selecione uma página', 'pt');
            foreach ($pages as $id => $title) {
                $array[$id] = $title;
            }
            return $array;
        },
        'required'      => true
    ));

    $main_options->add_field(array(
        'name'    => esc_html__('Página de redefinição de senha', 'pt'),
        'id'      => 'pt_resetpassword_page',
        'type'    => 'select',
        'options' => function () {
            $pages = pt_get_pages();
            $array = [];
            $array[''] = __('Selecione uma página', 'pt');
            foreach ($pages as $id => $title) {
                $array[$id] = $title;
            }
            return $array;
        },
        'required'      => true
    ));

    $main_options->add_field(array(
        'name'    => esc_html__('Página da conta do usuário', 'pt'),
        'id'      => 'pt_account_page',
        'type'    => 'select',
        'options' => function () {
            $pages = pt_get_pages();
            $array = [];
            $array[''] = __('Selecione uma página', 'pt');
            foreach ($pages as $id => $title) {
                $array[$id] = $title;
            }
            return $array;
        },
        'required'      => true
    ));

    $args = array(
        'id'           => 'pt_secondary_options_page',
        'menu_title'   => esc_html__('Dados da empresa', 'pt'), // Use menu title, & not title to hide main h2.
        'object_types' => array('options-page'),
        'option_key'   => 'pt_secondary_options',
        'parent_slug'  => 'pt_main_options',
        'tab_group'    => 'pt_main_options',
        'tab_title'    => esc_html__('Dados da empresa', 'pt'),
    );

    // 'tab_group' property is supported in > 2.4.0.
    if (version_compare(CMB2_VERSION, '2.4.0')) {
        $args['display_cb'] = 'pt_options_display_with_tabs';
    }

    $secondary_options = new_cmb2_box($args);

    $secondary_options->add_field(array(
        'name' => esc_html__('Logotipo da empresa', 'cmb2'),
        'id'   => 'pt_empresa_logo',
        'type' => 'file',
        'attributes' => array(
            'accept' => '.jpg,.jpeg,.png'
        )
    ));

    $secondary_options->add_field(array(
        'name'    => esc_html__('Nome da empresa', 'pt'),
        'id'      => 'pt_nome_empresa',
        'type'    => 'text',
    ));

    $secondary_options->add_field(array(
        'name'    => esc_html__('Endereço da empresa', 'pt'),
        'id'      => 'pt_endereco_empresa',
        'type'    => 'textarea',
        'attributes'        => array(
            'rows'      => 3
        )
    ));

    $secondary_options->add_field(array(
        'name'    => esc_html__('WhatsApp da empresa', 'pt'),
        'id'      => 'pt_whatsapp_empresa',
        'type'    => 'text',
    ));

}

function pt_options_display_with_tabs($cmb_options)
{
    $tabs = pt_options_page_tabs($cmb_options);
?>
    <div class="wrap cmb2-options-page option-<?php echo $cmb_options->option_key; ?>">
        <?php if (get_admin_page_title()) : ?>
            <h2><?php echo wp_kses_post(get_admin_page_title()); ?></h2>
        <?php endif; ?>
        <h2 class="nav-tab-wrapper">
            <?php foreach ($tabs as $option_key => $tab_title) : ?>
                <a class="nav-tab<?php if (isset($_GET['page']) && $option_key === $_GET['page']) : ?> nav-tab-active<?php endif; ?>" href="<?php menu_page_url($option_key); ?>"><?php echo wp_kses_post($tab_title); ?></a>
            <?php endforeach; ?>
        </h2>
        <form class="cmb-form" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="POST" id="<?php echo $cmb_options->cmb->cmb_id; ?>" enctype="multipart/form-data" encoding="multipart/form-data">
            <input type="hidden" name="action" value="<?php echo esc_attr($cmb_options->option_key); ?>">
            <?php $cmb_options->options_page_metabox(); ?>
            <?php submit_button(esc_attr($cmb_options->cmb->prop('save_button')), 'primary', 'submit-cmb'); ?>
        </form>
    </div>
<?php
}

function pt_options_page_tabs($cmb_options)
{
    $tab_group = $cmb_options->cmb->prop('tab_group');
    $tabs      = array();

    foreach (CMB2_Boxes::get_all() as $cmb_id => $cmb) {
        if ($tab_group === $cmb->prop('tab_group')) {
            $tabs[$cmb->options_page_keys()[0]] = $cmb->prop('tab_title')
                ? $cmb->prop('tab_title')
                : $cmb->prop('title');
        }
    }

    return $tabs;
}
