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
// FORMATO UNIVERSAL PARA INPUTS TYPE="NUMBER"
// VERSIÓN CON PADDING DINÁMICO - RESPETA SPANS EXISTENTES
// ==============================================
(function() {
    'use strict';
    
    // Configuración
    const config = {
        decimalPlaces: 2,
        thousandSeparator: ',',
        decimalSeparator: '.',
        currencySymbol: '$',
        debug: true,
        
        defaultMode: 0,
        helpTextClass: 'number-format-helper',
        modeAttribute: 'data-format-mode',
        excludeAttribute: 'data-no-format',
        
        excludePatterns: ['noformat', 'no-format', 'skip-format']
    };
    
    window.helptext = config.defaultMode;
    
    function log(...args) {
        if (config.debug) console.log('[NumberFormatter]', ...args);
    }
    
    function shouldExclude(input) {
        if (input.hasAttribute(config.excludeAttribute)) return true;
        for (const pattern of config.excludePatterns) {
            if (input.hasAttribute(pattern) || input.classList.contains(pattern)) return true;
        }
        return false;
    }
    
    function getMode(input) {
        if (input.hasAttribute(config.modeAttribute)) {
            const mode = parseInt(input.getAttribute(config.modeAttribute));
            if (!isNaN(mode)) return mode;
        }
        return window.helptext;
    }
    
    function formatNumber(value) {
        if (value === '' || value === null || value === undefined) {
            return config.currencySymbol + ' 0.00';
        }
        
        let num = parseFloat(value);
        if (isNaN(num)) return value;
        
        return config.currencySymbol + ' ' + 
               num.toFixed(config.decimalPlaces)
                  .replace(/\B(?=(\d{3})+(?!\d))/g, config.thousandSeparator);
    }
    
    // ==============================================
    // CALCULAR PADDING NECESARIO
    // ==============================================
    
    function calculatePadding(input, inputGroup) {
        if (!inputGroup) return 75; // Padding por defecto
        
        const children = Array.from(inputGroup.children);
        const inputIndex = children.indexOf(input);
        
        // Si no hay elementos antes del input, padding normal
        if (inputIndex <= 0) return 75;
        
        // Calcular ancho total de los elementos ANTES del input
        let totalWidth = 0;
        for (let i = 0; i < inputIndex; i++) {
            const element = children[i];
            // Obtener ancho real incluyendo márgenes
            const styles = window.getComputedStyle(element);
            const width = element.offsetWidth;
            const marginRight = parseInt(styles.marginRight) || 0;
            totalWidth += width + marginRight;
        }
        
        // Añadir un margen de seguridad (10px) y algo extra para el símbolo $
        const paddingNeeded = totalWidth + 25; // 25px extra para el símbolo $
        
        log(`Padding calculado para ${input.id}: ${paddingNeeded}px (elementos antes: ${totalWidth}px)`);
        
        return Math.max(paddingNeeded, 75); // Mínimo 75px
    }
    
    // ==============================================
    // CLASE PRINCIPAL
    // ==============================================
    
    class NumberFormatter {
        constructor(input) {
            this.input = input;
            this.mode = getMode(input);
            this.display = null;
            this.helper = null;
            this.inputGroup = input.closest('.input-group');
            
            if (shouldExclude(input)) {
                log('Excluido:', input.id);
                input.dataset.numberFormatted = 'skipped';
                return;
            }
            
            log('Procesando:', input.id, 'modo:', this.mode);
            this.setup();
            this.attachEvents();
            
            // Marcar como procesado
            this.input.dataset.numberFormatted = 'true';
            this.input.dataset.formatMode = this.mode;
            
            // Actualizar inmediatamente
            setTimeout(() => this.update(), 50);
        }
        
        setup() {
            if (this.mode === 0) {
                this.setupDisplay();
            } else {
                this.setupHelpText();
            }
        }
        
        setupDisplay() {
            // CALCULAR PADDING DINÁMICO
            const paddingLeft = calculatePadding(this.input, this.inputGroup);
            
            // Aplicar padding al input
            this.input.style.paddingLeft = paddingLeft + 'px';
            
            if (this.inputGroup) {
                // Estamos en input-group
                if (window.getComputedStyle(this.inputGroup).position === 'static') {
                    this.inputGroup.style.position = 'relative';
                }
                
                // Eliminar displays existentes
                const existing = this.inputGroup.querySelectorAll('.number-format-display');
                existing.forEach(el => el.remove());
                
                // Calcular posición left para el display
                let leftPosition = 10; // Valor por defecto
                
                if (this.inputGroup) {
                    const children = Array.from(this.inputGroup.children);
                    const inputIndex = children.indexOf(this.input);
                    
                    // Si hay elementos antes, posicionar después del último
                    if (inputIndex > 0) {
                        const lastElementBefore = children[inputIndex - 1];
                        leftPosition = lastElementBefore.offsetLeft + lastElementBefore.offsetWidth + 5;
                    }
                }
                
                // Crear display
                this.display = document.createElement('span');
                this.display.className = 'number-format-display';
                this.display.style.cssText = `
                    position: absolute;
                    left: ${leftPosition}px;
                    top: 50%;
                    transform: translateY(-50%);
                    color: #495057;
                    pointer-events: none;
                    z-index: 10;
                    background: transparent;
                    font-family: inherit;
                    font-size: inherit;
                    white-space: nowrap;
                    opacity: 0.9;
                `;
                
                // Insertar antes del input
                this.inputGroup.insertBefore(this.display, this.input);
            } else {
                // Crear wrapper
                const wrapper = document.createElement('div');
                wrapper.className = 'number-format-wrapper';
                wrapper.style.cssText = `
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
                    color: #495057;
                    pointer-events: none;
                    z-index: 10;
                    background: transparent;
                    font-family: inherit;
                    font-size: inherit;
                    white-space: nowrap;
                    opacity: 0.9;
                `;
                
                this.input.parentNode.insertBefore(wrapper, this.input);
                wrapper.appendChild(this.input);
                wrapper.appendChild(this.display);
                
                this.input.style.width = '100%';
                this.input.style.paddingLeft = paddingLeft + 'px';
                this.input.style.boxSizing = 'border-box';
            }
        }
        
        setupHelpText() {
            this.helper = document.createElement('div');
            this.helper.className = config.helpTextClass;
            this.helper.style.cssText = `
                font-size: 0.85rem;
                color: #6c757d;
                margin-top: 0.25rem;
                padding: 0.25rem 0.5rem;
                border-left: 2px solid #007bff;
                background-color: #f8f9fa;
                border-radius: 0 4px 4px 0;
                clear: both;
            `;
            
            if (this.inputGroup) {
                this.inputGroup.insertAdjacentElement('afterend', this.helper);
            } else {
                this.input.insertAdjacentElement('afterend', this.helper);
            }
        }
        
        attachEvents() {
            // TIEMPO REAL
            this.input.addEventListener('input', () => this.update());
            this.input.addEventListener('keyup', () => this.update());
            this.input.addEventListener('change', () => {
                log('Change event en', this.input.id);
                this.update();
            });
            this.input.addEventListener('blur', () => this.update());
            
            // Recalcular padding si cambia el tamaño de la ventana
            window.addEventListener('resize', () => {
                if (this.mode === 0 && this.inputGroup) {
                    const newPadding = calculatePadding(this.input, this.inputGroup);
                    this.input.style.paddingLeft = newPadding + 'px';
                    
                    // Reposicionar display
                    const children = Array.from(this.inputGroup.children);
                    const inputIndex = children.indexOf(this.input);
                    if (inputIndex > 0 && this.display) {
                        const lastElementBefore = children[inputIndex - 1];
                        this.display.style.left = (lastElementBefore.offsetLeft + lastElementBefore.offsetWidth + 5) + 'px';
                    }
                }
            });
        }
        
        update() {
            const formatted = formatNumber(this.input.value);
            
            if (this.mode === 0 && this.display) {
                this.display.textContent = formatted;
                log('Display actualizado:', this.input.id, '->', formatted);
            } else if (this.mode === 1 && this.helper) {
                this.helper.textContent = 'Valor formateado: ' + formatted;
            }
        }
    }
    
    // ==============================================
    // FUNCIONES GLOBALES
    // ==============================================
    
    function init() {
        const inputs = document.querySelectorAll('input[type="number"]:not([data-number-formatted])');
        inputs.forEach(input => new NumberFormatter(input));
    }
    
    window.refreshNumberFormats = function() {
        log('Refrescando todos los formatos');
        document.querySelectorAll('input[type="number"][data-number-formatted="true"]').forEach(input => {
            input.dispatchEvent(new Event('input', { bubbles: true }));
        });
    };
    
    window.NumberFormatter = {
        format: formatNumber,
        refresh: window.refreshNumberFormats,
        setMode: function(mode) {
            window.helptext = mode === 0 ? 0 : 1;
            location.reload();
        }
    };
    
    // ==============================================
    // INICIALIZACIÓN
    // ==============================================
    
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
    
    // Detectar inputs nuevos
    document.addEventListener('focusin', function(e) {
        if (e.target.matches && e.target.matches('input[type="number"]:not([data-number-formatted])')) {
            new NumberFormatter(e.target);
        }
    });
    
    log('NumberFormatter inicializado - Con padding dinámico');
    
})();


