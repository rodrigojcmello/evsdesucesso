/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function() {
    $(document).on('keypress', '.numerico', function(e){
        if(e.which!=46 && e.which!=44 && e.which!=13 && e.which!=8 && e.which!=0 && (e.which<48 || e.which>57)) return false;
    });
    
    $(".money").maskMoney({ thousands:'.', decimal:',' });

    $('.mask-cpf').mask('000.000.000-00', {reverse: true});

	$('.mask-phone').mask(function( val ){
        var mask = '(00) 0000-00009';
        
        if( val.replace(/\D/g, '').length === 11 ) {
            mask = '(00) 00000-0000';
        }

        return mask;
    });
    // $('.mask-date').mask('0000-00-00');

    $('.mask-date').mask("0000-00-00", {placeholder: "AAAA-MM-DD"});
});