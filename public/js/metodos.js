function Url(){
    if(window.location.origin.includes('localhost') || window.location.origin.includes('192.168')){
        return window.location.origin+'/constructora/public/';
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



// ==============================================
// FORMATO DE NÚMEROS PARA INPUTS TYPE="NUMBER"
// Agregar al final de metodos.js
// ==============================================

(function() {
    'use strict';
    
    // Configuración global
    const config = {
        decimalPlaces: 2,
        thousandSeparator: ',',
        decimalSeparator: '.',
        currencySymbol: '$',
        enabled: true,
        debug: false, // Cambiar a true para ver logs
        excludeAttribute: 'noformat', // Atributo para excluir inputs
        excludeClass: 'no-format', // Clase para excluir inputs
        defaultMode: 0, // 0 = formato en display, 1 = formato en help text
        helpTextClass: 'help-text', // Clase para el help text
        helpTextPosition: 'after' // 'after' o 'before' para posición del help text
    };
    
    // Variable de control global (la que mencionaste)
    var helptext = 0; // 0 = formato en display, 1 = formato en help text
    
    // Función para debug
    function log(...args) {
        if (config.debug) console.log('[NumberFormatter]', ...args);
    }
    
    // Función para verificar si un input debe ser excluido
    function shouldExcludeInput(input) {
        // Verificar por atributo data-noformat
        if (input.hasAttribute('data-noformat') || 
            input.hasAttribute('data-no-format') || 
            input.hasAttribute(config.excludeAttribute)) {
            log('Input excluido por atributo:', input.id || input.name || 'sin id');
            return true;
        }
        
        // Verificar por clase
        if (input.classList.contains(config.excludeClass)) {
            log('Input excluido por clase:', input.id || input.name || 'sin id');
            return true;
        }
        
        // Verificar por atributo personalizado en el HTML
        if (input.getAttribute('noformat') !== null || 
            input.getAttribute('no-format') !== null) {
            log('Input excluido por atributo HTML:', input.id || input.name || 'sin id');
            return true;
        }
        
        return false;
    }
    
    // Función para obtener el modo de formato de un input específico
    function getInputMode(input) {
        // Verificar si tiene atributo data-helptext-mode
        if (input.hasAttribute('data-helptext-mode')) {
            const mode = parseInt(input.getAttribute('data-helptext-mode'));
            if (!isNaN(mode)) return mode;
        }
        
        // Verificar si tiene atributo data-format-mode
        if (input.hasAttribute('data-format-mode')) {
            const mode = parseInt(input.getAttribute('data-format-mode'));
            if (!isNaN(mode)) return mode;
        }
        
        // Usar el modo global
        return helptext;
    }
    
    // Función para formatear número
    function formatNumber(value, options = {}) {
        if (value === '' || value === null || value === undefined) return '';
        let num = parseFloat(value);
        if (isNaN(num)) return value;
        
        const decimalPlaces = options.decimalPlaces || config.decimalPlaces;
        const currencySymbol = options.currencySymbol || config.currencySymbol;
        const thousandSeparator = options.thousandSeparator || config.thousandSeparator;
        
        return currencySymbol + ' ' + 
               num.toFixed(decimalPlaces)
                  .replace(/\B(?=(\d{3})+(?!\d))/g, thousandSeparator);
    }
    
    // Función para extraer número del formato
    function parseFormattedValue(formattedValue) {
        if (!formattedValue) return '';
        // Remover símbolo de moneda y comas
        let cleanValue = formattedValue.replace(config.currencySymbol, '').replace(/,/g, '').trim();
        return cleanValue;
    }
    
    // Función para crear o actualizar help text
    function updateHelpText(input, formattedValue) {
        // Buscar help text existente
        let helpText = input.nextElementSibling;
        if (!helpText || !helpText.classList.contains(config.helpTextClass)) {
            // Buscar en el contenedor padre
            const parent = input.parentElement;
            if (parent) {
                helpText = parent.querySelector('.' + config.helpTextClass);
            }
        }
        
        // Si no existe help text, crearlo
        if (!helpText) {
            helpText = document.createElement('div');
            helpText.className = config.helpTextClass;
            
            if (config.helpTextPosition === 'after') {
                input.insertAdjacentElement('afterend', helpText);
            } else {
                input.insertAdjacentElement('beforebegin', helpText);
            }
        }
        
        // Actualizar contenido
        helpText.textContent = ' ' + formattedValue;
        helpText.style.color = '#000000';
        helpText.style.fontSize = '0.85rem';
        helpText.style.marginTop = '0.25rem';
    }
    
    // Clase para manejar inputs formateados
    class FormattedNumberInput {
        constructor(input) {
            this.input = input;
            this.originalValue = input.value;
            this.mode = getInputMode(input);
            this.display = null;
            this.wrapper = null;
            
            log(`Input ${input.id || input.name} modo: ${this.mode === 0 ? 'Display' : 'Help Text'}`);
            
            this.setup();
        }
        
        setup() {
            // Verificar si debe ser excluido
            if (shouldExcludeInput(this.input)) {
                this.input.dataset.numberFormatted = 'skipped';
                log('Input omitido por exclusión');
                return;
            }
            
            // Marcar como procesado
            this.input.dataset.numberFormatted = 'true';
            this.input.dataset.formatMode = this.mode;
            
            // Guardar referencia al objeto
            const self = this;
            
            // Configurar según el modo
            if (this.mode === 0) {
                // Modo 0: Formato en display (wrapper)
                this.setupDisplayMode();
            } else {
                // Modo 1: Formato en help text
                this.setupHelpTextMode();
            }
            
            // Interceptar cambios programáticos
            this.interceptValueChanges();
        }
        
        setupDisplayMode() {
            // Crear wrapper y display
            this.wrapper = document.createElement('div');
            this.wrapper.className = 'number-format-wrapper';
            this.wrapper.style.cssText = `
                position: relative;
                display: inline-block;
                width: ${this.input.offsetWidth || 200}px;
            `;
            
            this.display = document.createElement('span');
            this.display.className = 'number-format-display';
            this.display.style.cssText = `
                position: absolute;
                left: 10px;
                top: 50%;
                transform: translateY(-50%);
                color: #333;
                pointer-events: none;
                z-index: 1;
                background: transparent;
                font-family: inherit;
                font-size: inherit;
            `;
            
            // Insertar en el DOM
            this.input.parentNode.insertBefore(this.wrapper, this.input);
            this.wrapper.appendChild(this.input);
            this.wrapper.appendChild(this.display);
            
            // Ajustar estilos del input
            this.input.style.width = '100%';
            this.input.style.paddingLeft = '70px';
            this.input.style.boxSizing = 'border-box';
            
            // Actualizar display inicial
            this.updateDisplay();
            
            // Event listeners
            this.input.addEventListener('input', () => this.updateDisplay());
            this.input.addEventListener('change', () => this.updateDisplay());
        }
        
        setupHelpTextMode() {
            // Asegurar que el input tenga márgenes para el help text
            this.input.style.marginBottom = '5px';
            
            // Actualizar help text inicial
            this.updateHelpText();
            
            // Event listeners
            this.input.addEventListener('input', () => this.updateHelpText());
            this.input.addEventListener('change', () => this.updateHelpText());
        }
        
        updateDisplay() {
            if (this.display) {
                this.display.textContent = formatNumber(this.input.value);
            }
        }
        
        updateHelpText() {
            const formattedValue = formatNumber(this.input.value);
            updateHelpText(this.input, formattedValue);
        }
        
        interceptValueChanges() {
            const self = this;
            const input = this.input;
            const originalDescriptor = Object.getOwnPropertyDescriptor(HTMLInputElement.prototype, 'value');
            
            Object.defineProperty(input, 'value', {
                set: function(v) {
                    // Llamar al setter original
                    originalDescriptor.set.call(this, v);
                    // Actualizar según modo
                    if (self.mode === 0) {
                        self.updateDisplay.call(self);
                    } else {
                        self.updateHelpText.call(self);
                    }
                    // Disparar evento para notificar cambio
                    this.dispatchEvent(new Event('input', { bubbles: true }));
                },
                get: function() {
                    return originalDescriptor.get.call(this);
                },
                configurable: true
            });
        }
    }
    
    // Función principal para procesar todos los inputs
    function processNumberInputs() {
        if (!config.enabled) return;
        
        const numberInputs = document.querySelectorAll('input[type="number"]:not([data-number-formatted="true"]):not([data-number-formatted="skipped"])');
        log(`Procesando ${numberInputs.length} inputs type="number"`);
        
        numberInputs.forEach(input => {
            new FormattedNumberInput(input);
        });
    }
    
    // Función para cambiar el modo global
    window.setHelptextMode = function(mode) {
        helptext = mode === 0 ? 0 : 1;
        log(`Modo global cambiado a: ${helptext === 0 ? 'Display' : 'Help Text'}`);
        
        // Reprocesar todos los inputs para aplicar el nuevo modo
        document.querySelectorAll('input[type="number"]').forEach(input => {
            // Limpiar datos anteriores
            input.dataset.numberFormatted = '';
            
            // Eliminar wrapper si existe
            const wrapper = input.closest('.number-format-wrapper');
            if (wrapper) {
                const parent = wrapper.parentNode;
                parent.insertBefore(input, wrapper);
                parent.removeChild(wrapper);
            }
            
            // Eliminar help text personalizado
            const helpText = input.nextElementSibling;
            if (helpText && helpText.classList.contains(config.helpTextClass)) {
                helpText.remove();
            }
            
            // Resetear estilos
            input.style.paddingLeft = '';
            input.style.width = '';
            input.style.marginBottom = '';
        });
        
        // Reprocesar
        processNumberInputs();
    };
    
    // Inicializar cuando el DOM esté listo
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', processNumberInputs);
    } else {
        processNumberInputs();
    }
    
    // Observar cambios en el DOM (para inputs agregados dinámicamente)
    const observer = new MutationObserver(function(mutations) {
        let shouldProcess = false;
        
        mutations.forEach(mutation => {
            if (mutation.addedNodes.length) {
                mutation.addedNodes.forEach(node => {
                    if (node.nodeType === 1) { // Element node
                        if (node.matches && node.matches('input[type="number"]')) {
                            shouldProcess = true;
                        }
                        if (node.querySelectorAll && node.querySelectorAll('input[type="number"]').length) {
                            shouldProcess = true;
                        }
                    }
                });
            }
        });
        
        if (shouldProcess) {
            setTimeout(processNumberInputs, 100);
        }
    });
    
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });
    
    // Exponer API global
    window.NumberFormatter = {
        format: formatNumber,
        parse: parseFormattedValue,
        refresh: processNumberInputs,
        enable: function() { config.enabled = true; processNumberInputs(); },
        disable: function() { config.enabled = false; },
        setConfig: function(newConfig) { Object.assign(config, newConfig); },
        setMode: function(mode) { helptext = mode; this.refresh(); },
        getMode: function() { return helptext; }
    };
    
    log('NumberFormatter inicializado con helptext =', helptext);
    
    // ==============================================
    // PARCHE PARA LA FUNCIÓN calcularMontos
    // ==============================================
    
    // Guardar referencia a la función original si existe
    const originalCalcularMontos = window.calcularMontos;
    
    // Crear nueva función que maneje el formato
    window.calcularMontos = function() {
        log('Ejecutando calcularMontos con soporte de formato');
        
        // Obtener valores de los inputs
        const subtotalInput = document.getElementById('subtotal');
        const porcentajeIvaInput = document.getElementById('porcentaje_iva');
        
        if (subtotalInput && porcentajeIvaInput) {
            // Extraer valores numéricos
            let subtotal = subtotalInput.value;
            let porcentajeIva = porcentajeIvaInput.value;
            
            // Si los inputs tienen formato, extraer solo números
            if (subtotalInput.dataset.numberFormatted === 'true') {
                subtotal = subtotal.replace(/[$,]/g, '');
            }
            if (porcentajeIvaInput.dataset.numberFormatted === 'true') {
                porcentajeIva = porcentajeIvaInput.value.replace(/[$,]/g, '');
            }
            
            // Convertir a números
            subtotal = parseFloat(subtotal) || 0;
            porcentajeIva = parseFloat(porcentajeIva) || 0;
            
            // Calcular
            const ivaCalculado = (subtotal * porcentajeIva) / 100;
            const totalCalculado = subtotal + ivaCalculado;
            
            // Actualizar campos
            const ivaInput = document.getElementById('iva');
            const totalInput = document.getElementById('total');
            
            if (ivaInput) {
                ivaInput.value = ivaCalculado.toFixed(2);
                // Actualizar display según modo
                if (ivaInput.dataset.numberFormatted === 'true') {
                    ivaInput.dispatchEvent(new Event('input'));
                }
            }
            
            if (totalInput) {
                totalInput.value = totalCalculado.toFixed(2);
                if (totalInput.dataset.numberFormatted === 'true') {
                    totalInput.dispatchEvent(new Event('input'));
                }
            }
            
            log('Cálculo completado:', { subtotal, porcentajeIva, ivaCalculado, totalCalculado });
        }
        
        // Llamar a la función original si existe
        if (typeof originalCalcularMontos === 'function') {
            originalCalcularMontos();
        }
    };
    
})();


