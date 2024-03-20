<?php

class PTPDF extends TCPDF
{

    protected $footer_info = '';

    public function setFooterInfo($text)
    {
        $this->footer_info = $text;
    }
    //Page header
    public function Header()
    {
        $empresa_obj = pt_get_empresa();
        $empresa_logo_file = get_attached_file($empresa_obj->logo_id);
        $empresa_nome = $empresa_obj->nome;
        // Logo
        $image_file = $empresa_logo_file;
        $this->Image($image_file, 10, 10, 60, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        // Set font
        $this->SetFont('helvetica', 'B', 12);
        // Title
        $this->Cell(0, 15, $empresa_nome, 0, false, 'R', 0, '', 0, false, 'M', 'B');
    }

    // Page footer
    public function Footer()
    {
        $empresa_obj = pt_get_empresa();
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        $this->writeHTML('<hr />');
        if ($this->footer_info) {
            $this->writeHTML($this->footer_info);
        }
        $this->writeHTML($empresa_obj->nome . ' - ' . $empresa_obj->endereco);
        // Page number
        $this->Cell(0, 10, 'Página ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'B', 'C');
    }
}
