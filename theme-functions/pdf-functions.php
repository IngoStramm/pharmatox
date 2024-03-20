<?php

add_action('admin_post_pt_create_pdf_form', 'pt_create_pdf_form_handle');
add_action('admin_post_nopriv_pt_create_pdf_form', 'pt_create_pdf_form_handle');

function pt_create_pdf_form_handle()
{
    nocache_headers();
    $redirect_url = $_SERVER['HTTP_REFERER'];
    unset($_SESSION['pt_create_pdf_error_message']);

    if (!isset($_POST['pt_form_create_pdf_nonce']) || !wp_verify_nonce($_POST['pt_form_create_pdf_nonce'], 'pt_form_create_pdf_nonce')) {

        $_SESSION['pt_create_pdf_error_message'] = __('Não foi possível validar a requisição.', 'pt');
        wp_safe_redirect($redirect_url);
        exit;
    }

    if (!isset($_POST['action']) || $_POST['action'] !== 'pt_create_pdf_form') {

        $_SESSION['pt_create_pdf_error_message'] = __('Formulário inválido.', 'pt');
        wp_safe_redirect($redirect_url);
        exit;
    }

    if (!isset($_POST['post_id']) || !$_POST['post_id']) {

        $_SESSION['pt_create_pdf_error_message'] = __('ID do relatório ausente.', 'pt');
        wp_safe_redirect($redirect_url);
        exit;
    }

    $post_id = $_POST['post_id'];
    $medico_id = get_post_meta($post_id, 'pt_relatorio_medico', true);

    if (!$medico_id) {

        $_SESSION['pt_create_pdf_error_message'] = __('Médico do relatório ausente.', 'pt');
        wp_safe_redirect($redirect_url);
        exit;
    }

    $pfx_file = get_user_meta($medico_id, 'pt_user_certificado', true);
    $certificado_password = get_user_meta($medico_id, 'pt_user_certificado_password', true);

    if (!$pfx_file) {

        $_SESSION['pt_create_pdf_error_message'] = __('Certificado do médico ausente.', 'pt');
        wp_safe_redirect($redirect_url);
        exit;
    }

    $pem_file = pt_convert_pfx_to_pem($pfx_file, $certificado_password, $medico_id);

    if (!$pem_file) {
        $_SESSION['pt_create_pdf_error_message'] = __('Não foi possível criar o novo arquivo.', 'pt');
        wp_safe_redirect($redirect_url);
        exit;
    }

    $pdf_dir = pt_get_pdf_dir();
    $pdf_file_dir = $pdf_dir . '/relatorio-' . $post_id . '.pdf';

    if (file_exists($pdf_file_dir)) {
        unlink($pdf_file_dir);
    }
    $tcpdf = pt_generate_pdf($post_id, $pem_file);

    $pdf_url = pt_get_pdf_url();
    $pdf_file_url = $pdf_url . '/relatorio-' . $post_id . '.pdf';

    $send_pdf_to_paciente = isset($_POST['pt_send_pdf_to_paciente']) ? true : false;
    $send_pdf_to_fornecedor = isset($_POST['pt_send_pdf_to_fornecedor']) ? true : false;
    $paciente_obj = pt_get_paciente_from_relatorio($post_id);
    $medico_obj = pt_get_medico_from_relatorio($post_id);
    $fornecedor_obj = pt_get_fornecedor_from_relatorio($post_id);

    if ($send_pdf_to_paciente) {
        $to = $paciente_obj->email;
        $subject = sprintf(__('%s | Relatório Médico #%s', 'pt'), get_bloginfo('name'), $post_id);
        $body = sprintf(
            __('<h3>Olá, %s!</h3><p>Este é o link para visualizar o seu relatório médico: <a href="%s" target="_blank">ver relatório</a>.</p><p>Em caso de dúvida, entre em contato como seu médico.</p>'),
            $paciente_obj->nome,
            $pdf_file_url
        );
        pt_mail($to, $subject, $body);
    }

    if ($send_pdf_to_fornecedor) {
        $to = $fornecedor_obj->email;
        $subject = sprintf(__('%s | Relatório Médico #%s', 'pt'), get_bloginfo('name'), $post_id);
        $body = sprintf(
            __('<h3>Olá, %s!</h3><p>O médico %s compartilhou com você este relatório médico: <a href="%s" target="_blank">ver relatório</a>.</p>'),
            $fornecedor_obj->nome,
            $medico_obj->nome,
            $pdf_file_url
        );
        pt_mail($to, $subject, $body);
    }

    $_SESSION['pt_create_pdf_success_message'] = sprintf(__('PDF gerado com sucesso, <a target="_blank" href="%s">abrir PDF</a>.', 'pt'), $pdf_file_url);
    wp_safe_redirect($redirect_url);
    exit;
}

add_action('create_pdf_messages', 'pt_create_pdf_error_message');

/**
 * pt_create_pdf_error_message
 *
 * @return void
 */
function pt_create_pdf_error_message()
{
    // Mensagens de erro de atualização do usuário
    if (isset($_SESSION['pt_create_pdf_error_message']) && $_SESSION['pt_create_pdf_error_message']) {
        echo pt_alert_small('danger', $_SESSION['pt_create_pdf_error_message']);
        unset($_SESSION['pt_create_pdf_error_message']);
    }
}

add_action('create_pdf_messages', 'pt_create_pdf_success_message');

/**
 * pt_create_pdf_success_message
 *
 * @return void
 */
function pt_create_pdf_success_message()
{
    // Mensagens de successo de atualização do usuário
    if (isset($_SESSION['pt_create_pdf_success_message']) && $_SESSION['pt_create_pdf_success_message']) {
        echo pt_alert_small('success', $_SESSION['pt_create_pdf_success_message']);
        unset($_SESSION['pt_create_pdf_success_message']);
    }
}

/**
 * pt_convert_pfx_to_pem
 *
 * @param  mixed $pfx_file
 * @param  mixed $certificado_password
 * @param  mixed $medico_id
 * @return void
 */
function pt_convert_pfx_to_pem($pfx_file, $certificado_password, $medico_id)
{
    $certificateStoreData = [];
    $arrContextOptions = array(
        "ssl" => array(
            "verify_peer" => false,
            "verify_peer_name" => false,
        ),
    );

    $readedSuccessful = openssl_pkcs12_read(
        file_get_contents($pfx_file, false, stream_context_create($arrContextOptions)),
        $certificateStoreData,
        $certificado_password
    );

    if ($readedSuccessful === false) {
        throw new \Exception("Error: " . openssl_error_string());
    }

    // We read the data provided by `openssl_pkcs12_read` and assemble the content of the PEM certificate.
    $contentPemCertificate = $certificateStoreData['pkey'];
    $contentPemCertificate .= $certificateStoreData['cert'];
    $contentPemCertificate .= ($certificateStoreData['extracerts'][1] ?? '');
    $contentPemCertificate .= ($certificateStoreData['extracerts'][0] ?? '');

    $certificados_dir = pt_get_certificados_dir();

    $pem_file = file_put_contents($certificados_dir . '/medico-' . $medico_id . '.pem', $contentPemCertificate);

    return $pem_file;
}

/**
 * pt_generate_pdf
 *
 * @param  string/int $post_id
 * @return void
 */
function pt_generate_pdf($post_id, $pem_file)
{
    $medico_obj = pt_get_medico_from_relatorio($post_id);
    $paciente_obj = pt_get_paciente_from_relatorio($post_id);
    $cids = wp_get_post_terms($post_id, 'cid');
    $empresa = pt_get_empresa();

    $footer_info = sprintf(__('Assinado digitalmente por %s, CRM: %s em %s.', 'pt'), $medico_obj->nome, $medico_obj->crm, get_the_date('', $post_id));

    // create new PDF document
    $pdf = new PTPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // set document information
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor($medico_obj->nome);
    $pdf->SetTitle(printf(__('Relatório #%s', 'pt'), $post_id));
    $pdf->SetSubject(get_the_title($post_id));
    $pdf->SetKeywords('Relatório Médico');

    // set default header data
    $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE . ' 001', PDF_HEADER_STRING, array(0, 64, 255), array(0, 64, 128));
    $pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));

    // set header and footer fonts
    $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

    // set default monospaced font
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // set margins
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

    // set auto page breaks
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

    // set image scale factor
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    // set some language-dependent strings (optional)
    if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
        require_once(dirname(__FILE__) . '/lang/eng.php');
        $pdf->setLanguageArray($l);
    }

    // ---------------------------------------------------------

    // set default font subsetting mode
    $pdf->setFontSubsetting(true);

    // Set font
    // dejavusans is a UTF-8 Unicode font, if you only need to
    // print standard ASCII chars, you can use core fonts like
    // helvetica or times to reduce file size.
    $pdf->SetFont('dejavusans', '', 14, '', true);

    // Add a page
    // This method has several options, check the source code documentation for more information.
    $pdf->AddPage();

    // set text shadow effect
    $pdf->setTextShadow(array('enabled' => true, 'depth_w' => 0.2, 'depth_h' => 0.2, 'color' => array(196, 196, 196), 'opacity' => 1, 'blend_mode' => 'Normal'));

    // Set some content to print
    $html = '<p>&nbsp;</p>';
    $html .= '<p>&nbsp;</p>';

    $html .= apply_filters('the_content', get_the_content(null, null, $post_id));

    $html .= '<h4>' . __('CIDs', 'pt') . '</h4>';
    $html .= '<ul>';
    foreach ($cids as $cid) {
        $cid_name = $cid->name;
        $cid_codigo = get_term_meta($cid->term_id, 'pt_cid_codigo', true);
        $html .= '<li>' . sprintf(__('%s'), $cid_codigo) . '</li>';
    }
    $html .= '</ul>';

    // $html .= '<p>&nbsp;</p>';

    $html .= '<h4>' . __('Médico', 'pt') . '</h4>';
    $html .= '<ul>';
    $html .= '<li>' . sprintf(__('Nome: %s', 'pt'), $medico_obj->nome) .  '</li>';
    $html .= '<li>' . sprintf(__('Especialidade: %s', 'pt'), $medico_obj->especialidade) .  '</li>';
    $html .= '<li>' . sprintf(__('CRM: %s', 'pt'), $medico_obj->crm) .  '</li>';
    $html .= '</ul>';

    $html .= '<h4>' . __('Paciente', 'pt') . '</h4>';
    $html .= '<ul>';
    $html .= '<li>' . sprintf(__('Nome: %s', 'pt'), $paciente_obj->nome) .  '</li>';
    $html .= '</ul>';

    $html .= '<p><small>' . sprintf(__('Relatório médico emitido em %s.', 'pt'), get_the_date('', $post_id)) . '</small></p>';

    //     $html = <<<EOD
    // <h1>Welcome to <a href="http://www.tcpdf.org" style="text-decoration:none;background-color:#CC0000;color:black;">&nbsp;<span style="color:black;">TC</span><span style="color:white;">PDF</span>&nbsp;</a>!</h1>
    // <i>This is the first example of TCPDF library.</i>
    // <p>This text is printed using the <i>writeHTMLCell()</i> method but you can also use: <i>Multicell(), writeHTML(), Write(), Cell() and Text()</i>.</p>
    // <p>Please check the source code documentation and other examples for further information.</p>
    // <p style="color:#CC0000;">TO IMPROVE AND EXPAND TCPDF I NEED YOUR SUPPORT, PLEASE <a href="http://sourceforge.net/donate/index.php?group_id=128076">MAKE A DONATION!</a></p>
    // EOD;

    // Print text using writeHTMLCell()
    $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

    // ---------------------------------------------------------
    $info = array(
        'Name' => $empresa->nome,
        'Location' => $empresa->endereco,
        'Reason' => 'Assinatura digital, Não recusa, Chaves de criptografia, Proteção de e-mail, Autenticação do cliente',
        'ContactInfo' => get_site_url(),
    );
    $pdf->setSignature($pem_file, $pem_file, 'tcpdfdemo', '', 2, $info, 'A');

    $pdf->setFooterInfo($footer_info);

    $pdf_dir = pt_get_pdf_dir();
    // Close and output PDF document
    // This method has several options, check the source code documentation for more information.
    $pdf->Output($pdf_dir . '/relatorio-' . $post_id . '.pdf', 'F');
}
