<script>
$(document).ready(function() {
    // Inicializar Select2 con tema Bootstrap
    $('#id_contrato').select2({
        theme: 'bootstrap-5',
        width: '100%',
        placeholder: 'Buscar contrato...',
        allowClear: true,
        language: {
            noResults: function() { return "No se encontraron contratos"; },
            searching: function() { return "Buscando..."; }
        }
    });

    // Función para actualizar TODOS los campos de información del contrato
    function actualizarInfoContrato() {
        var selectedOption = $('#id_contrato').find('option:selected');
        
        if (selectedOption.val()) {
            // Actualizar todos los spans
            $('#consecutivo_display').text(selectedOption.data('consecutivo') || '---');
            $('#refinterna_display').text(selectedOption.data('refinterna') || '---');
            $('#contrato_no_display').text(selectedOption.data('contrato-no') || '---');
            $('#cliente_display').text(selectedOption.data('cliente') || '---');
            $('#obra_display').text(selectedOption.data('obra') || '---');
            
            if ($('#frente_display').length) {
                $('#frente_display').text(selectedOption.data('frente') || '---');
            }
        } else {
            $('#consecutivo_display').text('---');
            $('#refinterna_display').text('---');
            $('#contrato_no_display').text('---');
            $('#cliente_display').text('---');
            $('#obra_display').text('---');
            
            if ($('#frente_display').length) {
                $('#frente_display').text('---');
            }
        }
    }

    // Función para calcular importe del IVA y total de estimación
    function calcularImporteIVAYTotal() {
        const importeEstimacion = parseFloat($('#importe_estimacion').val()) || 0;
        const iva = parseFloat($('#iva').val()) || 0;
        
        const importeIVACalculado = importeEstimacion * (iva / 100);
        const totalCalculado = importeEstimacion + importeIVACalculado;
        
        $('#importe_iva').val(importeIVACalculado.toFixed(2));
        $('#total_estimacion_con_iva').val(totalCalculado.toFixed(2));
        
        dispararEventos('#importe_iva');
        dispararEventos('#total_estimacion_con_iva');
        
        // Recalcular porcentajes si los checkboxes están activos
        if ($('#aplicar_sicv').is(':checked')) {
            calcularSICV();
        }
        if ($('#aplicar_srcop').is(':checked')) {
            calcularSRCOP();
        }
        if ($('#aplicar_derechos_supervision').is(':checked')) {
            calcularDerechosSupervision();
        }
        if ($('#aplicar_aportacion_cmic').is(':checked')) {
            calcularAportacionCMIC();
        }
        if ($('#aplicar_delegacion_icic').is(':checked')) {
            calcularDelegacionICIC();
        }
        calcularRetencionesSanciones();
    }

    // Función para calcular 2% SICV
    function calcularSICV() {
        const totalEstimacion = parseFloat($('#importe_estimacion').val()) || 0;
        const resultado = totalEstimacion * 0.02;
        $('#sicv_cop').val(resultado.toFixed(2));
        calcularRetencionesSanciones();
    }

    // Función para calcular 1.5% SRCOP
    function calcularSRCOP() {
        const totalEstimacion = parseFloat($('#importe_estimacion').val()) || 0;
        const resultado = totalEstimacion * 0.015;
        $('#srcop_cdmx').val(resultado.toFixed(2));
        calcularRetencionesSanciones();
    }

    // Función para calcular 2% Derechos de Supervisión
    function calcularDerechosSupervision() {
        const totalEstimacionConIva = parseFloat($('#total_estimacion_con_iva').val()) || 0;
        const resultado = totalEstimacionConIva * 0.02;
        $('#derechos_supervision').val(resultado.toFixed(2));
        calcularRetencionesSanciones();
    }

    // Función para calcular 0.50% Aportación CMIC
    function calcularAportacionCMIC() {
        const totalEstimacion = parseFloat($('#importe_estimacion').val()) || 0;
        const resultado = totalEstimacion * 0.005;
        $('#aportacion_cmic').val(resultado.toFixed(2));
        calcularRetencionesSanciones();
    }

    // Función para calcular 0.20% Delegación ICIC
    function calcularDelegacionICIC() {
        const totalEstimacion = parseFloat($('#importe_estimacion').val()) || 0;
        const resultado = totalEstimacion * 0.002;
        $('#delegacion_icic').val(resultado.toFixed(2));
        calcularRetencionesSanciones();
    }

    // Función para calcular Retenciones o Sanciones (INCLUYE NUEVOS CAMPOS)
    function calcularRetencionesSanciones() {
        var camposRetenciones = [
            'sicv_cop',
            'srcop_cdmx',
            'derechos_supervision',
            'aportacion_cmic',
            'delegacion_icic',
            'retencion_5_al_millar',
            'sancion_atrazo_presentacion_estimacion',
            'sancion_atraso_de_obra',
            'sancion_por_obra_mal_ejecutada',
            'retencion_por_atraso_en_programa_obra'
        ];
        
        var total = 0;
        
        $.each(camposRetenciones, function(index, id) {
            var valor = parseFloat($('#' + id).val()) || 0;
            total += valor;
        });
        
        $('#retenciones_o_sanciones').val(total.toFixed(2));
        dispararEventos('#retenciones_o_sanciones');
        calcularEstimadoMenosDeducciones();
    }

    // Función para calcular Total Amortización
    function calcularTotalAmortizacion() {
        var amortizacionAnticipo = parseFloat($('#amortizacion_anticipo').val()) || 0;
        var amortizacionIva = parseFloat($('#amortizacion_iva').val()) || 0;
        
        var ivaCalculado = amortizacionAnticipo * (amortizacionIva / 100);
        var total = amortizacionAnticipo + ivaCalculado;
        
        $('#amor_iva').val(ivaCalculado.toFixed(2));
        $('#total_amortizacion').val(total.toFixed(2));
        
        dispararEventos('#amor_iva');
        dispararEventos('#total_amortizacion');
        calcularEstimadoMenosDeducciones();
    }

    // Función para calcular Estimado menos Deducciones
    function calcularEstimadoMenosDeducciones() {
        var totalEstimacionConIva = parseFloat($('#total_estimacion_con_iva').val()) || 0;
        var retencionesSanciones = parseFloat($('#retenciones_o_sanciones').val()) || 0;
        var totalAmortizacion = parseFloat($('#total_amortizacion').val()) || 0;
        
        var resultado = totalEstimacionConIva - retencionesSanciones - totalAmortizacion;
        $('#estimado_menos_deducciones').val(resultado.toFixed(2));
        dispararEventos('#estimado_menos_deducciones');
    }

    // Función para disparar eventos
    function dispararEventos(selector) {
        var input = document.querySelector(selector);
        if (input) {
            input.dispatchEvent(new Event('input', { bubbles: true }));
            input.dispatchEvent(new Event('keyup', { bubbles: true }));
            input.dispatchEvent(new Event('change', { bubbles: true }));
        }
    }

    // Función para cargar datos del ingreso (con nuevos campos)
    function cargarDatosIngreso(data) {
        $('#periodo_del').val(data.periodo_del || '');
        $('#periodo_al').val(data.periodo_al || '');
        
        $('#importe_estimacion').val(data.importe_estimacion || 0);
        dispararEventos('#importe_estimacion');
        
        $('#iva').val(data.iva || 0);
        dispararEventos('#iva');
        
        $('#importe_iva').val(data.importe_iva || 0);
        $('#total_estimacion_con_iva').val(data.total_estimacion_con_iva || 0);
        
        // SICV
        $('#sicv_cop').val(data.sicv_cop || 0);
        if (parseFloat(data.sicv_cop) > 0) {
            $('#aplicar_sicv').prop('checked', true);
        } else {
            $('#aplicar_sicv').prop('checked', false);
        }
        
        // SRCOP
        $('#srcop_cdmx').val(data.srcop_cdmx || 0);
        if (parseFloat(data.srcop_cdmx) > 0) {
            $('#aplicar_srcop').prop('checked', true);
        } else {
            $('#aplicar_srcop').prop('checked', false);
        }
        
        // Derechos Supervisión (NUEVO)
        $('#derechos_supervision').val(data.derechos_supervision || 0);
        if (parseFloat(data.derechos_supervision) > 0) {
            $('#aplicar_derechos_supervision').prop('checked', true);
        } else {
            $('#aplicar_derechos_supervision').prop('checked', false);
        }
        
        // Aportación CMIC (NUEVO)
        $('#aportacion_cmic').val(data.aportacion_cmic || 0);
        if (parseFloat(data.aportacion_cmic) > 0) {
            $('#aplicar_aportacion_cmic').prop('checked', true);
        } else {
            $('#aplicar_aportacion_cmic').prop('checked', false);
        }
        
        // Delegación ICIC (NUEVO)
        $('#delegacion_icic').val(data.delegacion_icic || 0);
        if (parseFloat(data.delegacion_icic) > 0) {
            $('#aplicar_delegacion_icic').prop('checked', true);
        } else {
            $('#aplicar_delegacion_icic').prop('checked', false);
        }
        
        // Retenciones y sanciones
        $('#retencion_5_al_millar').val(data.retencion_5_al_millar || 0);
        dispararEventos('#retencion_5_al_millar');
        
        $('#sancion_atrazo_presentacion_estimacion').val(data.sancion_atrazo_presentacion_estimacion || 0);
        dispararEventos('#sancion_atrazo_presentacion_estimacion');
        
        $('#sancion_atraso_de_obra').val(data.sancion_atraso_de_obra || 0);
        dispararEventos('#sancion_atraso_de_obra');
        
        $('#sancion_por_obra_mal_ejecutada').val(data.sancion_por_obra_mal_ejecutada || 0);
        dispararEventos('#sancion_por_obra_mal_ejecutada');
        
        $('#retencion_por_atraso_en_programa_obra').val(data.retencion_por_atraso_en_programa_obra || 0);
        dispararEventos('#retencion_por_atraso_en_programa_obra');
        
        $('#retenciones_o_sanciones').val(data.retenciones_o_sanciones || 0);
        
        // Amortizaciones
        $('#amortizacion_anticipo').val(data.amortizacion_anticipo || 0);
        dispararEventos('#amortizacion_anticipo');
        
        $('#amortizacion_iva').val(data.amortizacion_iva || 0);
        dispararEventos('#amortizacion_iva');
        
        $('#total_amortizacion').val(data.total_amortizacion || 0);
        $('#estimado_menos_deducciones').val(data.estimado_menos_deducciones || 0);
        
        // Forzar recálculo
        setTimeout(function() {
            calcularRetencionesSanciones();
            calcularEstimadoMenosDeducciones();
        }, 100);
    }

    // Función para limpiar el formulario (con nuevos campos)
    function limpiarFormulario() {
        $('#periodo_del').val('');
        $('#periodo_al').val('');
        
        $('#importe_estimacion').val(0);
        dispararEventos('#importe_estimacion');
        
        $('#iva').val(0);
        dispararEventos('#iva');
        
        $('#importe_iva').val(0);
        $('#total_estimacion_con_iva').val(0);
        
        $('#sicv_cop').val(0);
        $('#aplicar_sicv').prop('checked', false);
        
        $('#srcop_cdmx').val(0);
        $('#aplicar_srcop').prop('checked', false);
        
        // Nuevos campos
        $('#derechos_supervision').val(0);
        $('#aplicar_derechos_supervision').prop('checked', false);
        
        $('#aportacion_cmic').val(0);
        $('#aplicar_aportacion_cmic').prop('checked', false);
        
        $('#delegacion_icic').val(0);
        $('#aplicar_delegacion_icic').prop('checked', false);
        
        $('#retencion_5_al_millar').val(0);
        dispararEventos('#retencion_5_al_millar');
        
        $('#sancion_atrazo_presentacion_estimacion').val(0);
        dispararEventos('#sancion_atrazo_presentacion_estimacion');
        
        $('#sancion_atraso_de_obra').val(0);
        dispararEventos('#sancion_atraso_de_obra');
        
        $('#sancion_por_obra_mal_ejecutada').val(0);
        dispararEventos('#sancion_por_obra_mal_ejecutada');
        
        $('#retencion_por_atraso_en_programa_obra').val(0);
        dispararEventos('#retencion_por_atraso_en_programa_obra');
        
        $('#retenciones_o_sanciones').val(0);
        
        $('#amortizacion_anticipo').val(0);
        dispararEventos('#amortizacion_anticipo');
        
        $('#amortizacion_iva').val(0);
        dispararEventos('#amortizacion_iva');
        
        $('#total_amortizacion').val(0);
        $('#estimado_menos_deducciones').val(0);
    }

    // ========== EVENTOS ==========
    
    // Evento change del select de contrato
    $('#id_contrato').on('change', function() {
        actualizarInfoContrato();
        var contratoId = $(this).val();
        
        if (contratoId) {
            Swal.fire({
                title: 'Cargando...',
                text: 'Buscando último ingreso del contrato',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });
            
            $.ajax({
                url: '{{ route("ingresos.ultimo", "") }}/' + contratoId,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    Swal.close();
                    if (response.success && response.data) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Datos cargados',
                            text: 'Se cargó la información del último ingreso',
                            timer: 2000,
                            showConfirmButton: false
                        });
                        cargarDatosIngreso(response.data);
                    } else {
                        limpiarFormulario();
                        Swal.fire({
                            icon: 'info',
                            title: 'Sin datos previos',
                            text: 'No hay ingresos anteriores para este contrato',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                },
                error: function(xhr, status, error) {
                    Swal.close();
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se pudo cargar la información del contrato',
                        timer: 2000,
                        showConfirmButton: false
                    });
                }
            });
        } else {
            limpiarFormulario();
        }
    });
    
    // Eventos para importe estimación e IVA
    $('#importe_estimacion, #iva').on('input', function() {
        calcularImporteIVAYTotal();
    });
    
    // Evento para checkbox SICV
    $('#aplicar_sicv').on('change', function() {
        if ($(this).is(':checked')) {
            calcularSICV();
        } else {
            $('#sicv_cop').val('0.00');
            calcularRetencionesSanciones();
        }
    });
    
    // Evento para checkbox SRCOP
    $('#aplicar_srcop').on('change', function() {
        if ($(this).is(':checked')) {
            calcularSRCOP();
        } else {
            $('#srcop_cdmx').val('0.00');
            calcularRetencionesSanciones();
        }
    });
    
    // Evento para checkbox Derechos Supervisión (NUEVO)
    $('#aplicar_derechos_supervision').on('change', function() {
        if ($(this).is(':checked')) {
            calcularDerechosSupervision();
        } else {
            $('#derechos_supervision').val('0.00');
            calcularRetencionesSanciones();
        }
    });
    
    // Evento para checkbox Aportación CMIC (NUEVO)
    $('#aplicar_aportacion_cmic').on('change', function() {
        if ($(this).is(':checked')) {
            calcularAportacionCMIC();
        } else {
            $('#aportacion_cmic').val('0.00');
            calcularRetencionesSanciones();
        }
    });
    
    // Evento para checkbox Delegación ICIC (NUEVO)
    $('#aplicar_delegacion_icic').on('change', function() {
        if ($(this).is(':checked')) {
            calcularDelegacionICIC();
        } else {
            $('#delegacion_icic').val('0.00');
            calcularRetencionesSanciones();
        }
    });
    
    // Campos de retenciones y sanciones
    var camposRetenciones = [
        'retencion_5_al_millar',
        'sancion_atrazo_presentacion_estimacion',
        'sancion_atraso_de_obra',
        'sancion_por_obra_mal_ejecutada',
        'retencion_por_atraso_en_programa_obra'
    ];
    
    $.each(camposRetenciones, function(index, id) {
        $('#' + id).on('input', function() {
            calcularRetencionesSanciones();
        });
    });
    
    // Campos para amortización
    $('#amortizacion_anticipo, #amortizacion_iva').on('input', function() {
        calcularTotalAmortizacion();
    });
    
    // Validar IVA
    $('#iva').on('change', function() {
        let valor = parseFloat($(this).val()) || 0;
        if (valor < 0) $(this).val(0);
        if (valor > 100) $(this).val(100);
    });
    
    // Si hay un contrato preseleccionado
    @if(old('id_contrato', $ultimoIngreso->id_contrato ?? ''))
        setTimeout(function() {
            $('#id_contrato').val('{{ old('id_contrato', $ultimoIngreso->id_contrato ?? '') }}').trigger('change');
        }, 200);
    @endif
    
    @if(request()->has('contrato_id'))
        setTimeout(function() {
            $('#id_contrato').val('{{ request('contrato_id') }}').trigger('change');
        }, 500);
    @endif
    
    // Calcular valores iniciales
    setTimeout(function() {
        calcularImporteIVAYTotal();
        calcularRetencionesSanciones();
        calcularTotalAmortizacion();
        calcularEstimadoMenosDeducciones();
    }, 200);
});
</script>