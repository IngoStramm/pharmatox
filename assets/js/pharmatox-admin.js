(() => {
    'use strict';
    function inputMasks() {
        const inputTelefone = document.getElementById('pt_fone');
        const maskOptionsTelefone = {
            mask: '(00) 0000-0000[0]'
        };
        if (typeof inputTelefone !== undefined && inputTelefone) {
            const maskTelefone = IMask(inputTelefone, maskOptionsTelefone);
        }

        const inputWhatsAppEmpresa = document.getElementById('pt_whatsapp_empresa');
        const inputWhatsAppFornecedor = document.getElementById('pt_fornecedor_whatsapp');
        const inputWhatsAppPaciente = document.getElementById('pt_paciente_whatsapp');
        const maskOptionsWhatsApp = {
            mask: '(00) 00000-0000'
        };
        if (typeof inputWhatsAppEmpresa !== undefined && inputWhatsAppEmpresa) {
            const maskWhatsAppEmpresa = IMask(inputWhatsAppEmpresa, maskOptionsWhatsApp);
        }
        if (typeof inputWhatsAppFornecedor !== undefined && inputWhatsAppFornecedor) {
            const maskWhatsAppFornecedor = IMask(inputWhatsAppFornecedor, maskOptionsWhatsApp);
        }
        if (typeof inputWhatsAppPaciente !== undefined && inputWhatsAppPaciente) {
            const maskWhatsAppPaciente = IMask(inputWhatsAppPaciente, maskOptionsWhatsApp);
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        inputMasks();
    }, false);
})();