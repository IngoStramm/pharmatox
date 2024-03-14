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

    $tcpdf = pt_generate_pdf($post_id, $pem_file);

    $_SESSION['pt_create_pdf_success_message'] = sprintf(__('PDF gerado com sucesso. Post ID: %s, PDF: "%s".', 'pt'), $post_id, $tcpdf);
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
    // create new PDF document
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // set document information
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Nicola Asuni');
    $pdf->SetTitle('TCPDF Example 001');
    $pdf->SetSubject('TCPDF Tutorial');
    $pdf->SetKeywords('TCPDF, PDF, example, test, guide');

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
    $html = <<<EOD
<h1>Welcome to <a href="http://www.tcpdf.org" style="text-decoration:none;background-color:#CC0000;color:black;">&nbsp;<span style="color:black;">TC</span><span style="color:white;">PDF</span>&nbsp;</a>!</h1>
<i>This is the first example of TCPDF library.</i>
<p>This text is printed using the <i>writeHTMLCell()</i> method but you can also use: <i>Multicell(), writeHTML(), Write(), Cell() and Text()</i>.</p>
<p>Please check the source code documentation and other examples for further information.</p>
<p style="color:#CC0000;">TO IMPROVE AND EXPAND TCPDF I NEED YOUR SUPPORT, PLEASE <a href="http://sourceforge.net/donate/index.php?group_id=128076">MAKE A DONATION!</a></p>
EOD;

    // Print text using writeHTMLCell()
    $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

    // ---------------------------------------------------------
    $info = array(
        'Name' => 'TCPDF',
        'Location' => 'Office',
        'Reason' => 'Testing TCPDF',
        'ContactInfo' => 'http://www.tcpdf.org',
    );
    $pdf->setSignature($pem_file, $pem_file, 'tcpdfdemo', '', 2, $info, 'A');

    $pdf_dir = pt_get_pdf_dir();
    // Close and output PDF document
    // This method has several options, check the source code documentation for more information.
    $pdf->Output($pdf_dir . '/relatorio-' . $post_id . '.pdf', 'F');
}
