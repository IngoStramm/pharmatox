<?php

class PTPDF extends TCPDF
{

    protected $footer_info = '';
    protected $relatorio_id = '';

    public function setFooterInfo($text)
    {
        $this->footer_info = $text;
    }

    public function setRelatorioId($relatorio_id)
    {
        $this->relatorio_id = $relatorio_id;
    }
    //Page header
    public function Header()
    {
        $empresa_obj = pt_get_empresa($this->relatorio_id);
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
        $footer_text = '<hr /><br />';
        $empresa_obj = pt_get_empresa($this->relatorio_id);
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // $this->writeHTML('<hr />');
        if ($this->footer_info) {
            $footer_text .= $this->footer_info . '<br />';
        }
        $footer_text .= $empresa_obj->nome . ' - ' . $empresa_obj->endereco;
        $this->writeHTML($footer_text);
        // Page number
        $this->Cell(0, 10, 'Página ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'B', 'C');
    }
}
