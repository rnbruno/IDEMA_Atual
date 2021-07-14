(function($){
    $.fn.validationEngineLanguage = function(){};
    $.validationEngineLanguage = {
        newLang: function(){
            $.validationEngineLanguage.allRules = {
                "required": {
                    "regex": "none",
                    "alertText": "* Este campo &eacute; obrigat&oacute;rio",
                    "alertTextCheckboxMultiple": "* Favor selecionar uma op&ccedil;&atilde;o",
                    "alertTextCheckboxe": "* Este checkbox &eacute; obrigat&oacute;rio",
                    "alertTextDateRange": "* Ambas as datas do intervalo s&atilde;o obrigat&oacute;rias"
                },
                "requiredInFunction": { 
                    "func": function(field, rules, i, options){
                        return (field.val() == "test") ? true : false;
                    },
                    "alertText": "* Field must equal test"
                },
                "dateRange": {
                    "regex": "none",
                    "alertText": "* Intervalo de datas inv&aacute;lido"
                },
                "dateTimeRange": {
                    "regex": "none",
                    "alertText": "* Intervalo de data e hora inv&aacute;lido"
                },
                "minSize": {
                    "regex": "none",
                    "alertText": "* Permitido o m&iacute;nimo de ",
                    "alertText2": " caractere(s)"
                },
                "maxSize": {
                    "regex": "none",
                    "alertText": "* Permitido o m&aacute;ximo de ",
                    "alertText2": " caractere(s)"
                },
				"groupRequired": {
                    "regex": "none",
                    "alertText": "* Voc&ecirc; deve preencher um dos seguintes campos"
                },
                "min": {
                    "regex": "none",
                    "alertText": "* Valor m&iacute;nimo &eacute; "
                },
                "max": {
                    "regex": "none",
                    "alertText": "* Valor m&aacute;ximo &eacute; "
                },
                "past": {
                    "regex": "none",
                    "alertText": "* Data anterior a "
                },
                "future": {
                    "regex": "none",
                    "alertText": "* Data posterior a "
                },	
                "maxCheckbox": {
                    "regex": "none",
                    "alertText": "* M&aacute;ximo de ",
                    "alertText2": " op&ccedil;&otilde;es permitidas"
                },
                "minCheckbox": {
                    "regex": "none",
                    "alertText": "* Favor selecionar ",
                    "alertText2": " op&ccedil;&atilde;o(&otilde;es)"
                },
                "equals": {
                    "regex": "none",
                    "alertText": "* Os campos n&atilde;o correspondem"
                },
                "creditCard": {
                    "regex": "none",
                    "alertText": "* N&uacute;mero de cart&atilde;o de cr&eacute;dito inv&aacute;lido"
                },
                "phone": {
                    "regex": /^([\+][0-9]{1,3}[ \.\-])?([\(]{1}[0-9]{2,6}[\)])?([0-9 \.\-\/]{3,20})((x|ext|extension)[ ]?[0-9]{1,4})?$/,
                    "alertText": "* N&uacute;mero de telefone inv&aacute;lido"
                },
                "email": {
                    "regex": /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i,
                    "alertText": "* Endere&ccedil;o de email inv&aacute;lido"
                },
                "integer": {
                    "regex": /^[\-\+]?\d+$/,
                    "alertText": "* N&uacute;mero inteiro inv&aacute;lido"
                },
                "number": {
                    "regex": /^[\-\+]?((([0-9]{1,3})([,][0-9]{3})*)|([0-9]+))?([\.]([0-9]+))?$/,
                    "alertText": "* N&uacute;mero decimal inv&aacute;lido"
                },
                "date": {
                    "regex": /^\d{4}[\/\-](0?[1-9]|1[012])[\/\-](0?[1-9]|[12][0-9]|3[01])$/,
                    "alertText": "* Data inv&aacute;lida, deve ser no formato AAAA-MM-DD"
                },
                "ipv4": {
                    "regex": /^((([01]?[0-9]{1,2})|(2[0-4][0-9])|(25[0-5]))[.]){3}(([0-1]?[0-9]{1,2})|(2[0-4][0-9])|(25[0-5]))$/,
                    "alertText": "* Endere&ccedil;o IP inv&aacute;lido"
                },
                "url": {
                    "regex": /^(https?|ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i,
                    "alertText": "* URL inv&aacute;lida"
                },
                "onlyNumberSp": {
                    "regex": /^[0-9\ ]+$/,
                    "alertText": "* Apenas n&uacute;meros"
                },
                "onlyLetterSp": {
                    "regex": /^[a-zA-Z\ \']+$/,
                    "alertText": "* Apenas letras"
                },
                "onlyLetterNumber": {
                    "regex": /^[0-9a-zA-Z]+$/,
                    "alertText": "* N&atilde;o s&atilde;o permitidos caracteres especiais"
                },
                "real": {
                	// Brazilian (Real - R$) money format
                	"regex": /^([1-9]{1}[\d]{0,2}(\.[\d]{3})*(\,[\d]{0,2})?|[1-9]{1}[\d]{0,}(\,[\d]{0,2})?|0(\,[\d]{0,2})?|(\,[\d]{1,2})?)$/,
                    "alertText": "* N&uacute;mero decimal inv&aacute;lido"
                }
            };
            
        }
    };

    $.validationEngineLanguage.newLang();
    
})(jQuery);
