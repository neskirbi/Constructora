function Url(){
    if(window.location.origin.includes('localhost') || window.location.origin.includes('192.168')){
        return window.location.origin+'/Constructora/public/';
    }else{
       return window.location.origin+'/';
    }
}

function AppKey(){
    return 'cefa31fdcb2e11ec81768030496e73b5';
}

function GenerarPass(element){
    
    const userId = $(element).data('user-id');
    const userType = $(element).data('user-type');
    
    if (!userId) {
        alert('Error: ID de usuario no especificado');
        return;
    }
    
    if (!confirm('¿Generar una contraseña temporal? La contraseña actual será reemplazada.')) {
        return;
    }
    
    // Mostrar loading
    const $icon = $(element).find('i');
    const originalClass = $icon.attr('class');
    $icon.attr('class', 'fas fa-spinner fa-spin');
    $(element).prop('disabled', true);
    
    $.ajax({
        headers: { "APP-KEY": AppKey() },
        async: true,
        method: 'post',
        url: Url() + "api/GenerarPass",
        data: { 
            id: userId,
            tipo: userType
        }
    }).done(function(data) {
        console.log(data);
        if (data.status == 1) {
            // Actualizar el campo visualmente
            $(element).closest('.input-group').find('.pass-temp-field').val(data[0].passtemp);
            //alert('Contraseña temporal generada: ' + data[0].passtemp);
        } else {
            alert('Error al generar la contraseña.');
        }
    }).fail(function(xhr) {
        console.error(xhr);
        alert('Error de conexión al generar contraseña');
    }).always(function() {
        // Restaurar icono original y habilitar botón
        $icon.attr('class', originalClass);
        $(element).prop('disabled', false);
    });
}