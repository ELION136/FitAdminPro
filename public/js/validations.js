// Función para cargar mensajes de validación desde el archivo JSON
async function cargarMensajesValidacion() {
    const response = await fetch('/js/validacion.json');  // Reemplaza con la ruta real del JSON
    return await response.json();
}

// Función para mostrar errores
function mostrarError(input, mensaje) {
    let errorContainer = input.nextElementSibling; // Asume que el contenedor de error está justo después del input
    errorContainer.innerHTML = mensaje;
    errorContainer.style.color = 'red';
}

// Función para eliminar errores
function removerError(input) {
    let errorContainer = input.nextElementSibling;
    errorContainer.innerHTML = '';
}

// Validaciones
function validarSoloLetras(input, mensaje) {
    const regex = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/;
    if (!regex.test(input.value.trim())) {
        mostrarError(input, mensaje);
    } else {
        removerError(input);
    }
}

function validarSoloNumeros(input, mensaje) {
    const regex = /^[0-9]+$/;
    if (!regex.test(input.value.trim())) {
        mostrarError(input, mensaje);
    } else {
        removerError(input);
    }
}

function validarEmail(input, mensaje) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!regex.test(input.value.trim())) {
        mostrarError(input, mensaje);
    } else {
        removerError(input);
    }
}

function validarSinEspacios(input, mensaje) {
    if (/\s/.test(input.value)) {
        mostrarError(input, mensaje);
    } else {
        removerError(input);
    }
}

// Aplicar validaciones en tiempo real
async function aplicarValidaciones() {
    const validationMessages = await cargarMensajesValidacion();
    
    // Nombre: letras solamente y sin espacios
    document.getElementById('nombre').addEventListener('input', function() {
        validarSoloLetras(this, validationMessages.nombre.lettersOnly);
        validarSinEspacios(this, validationMessages.nombre.noSpaces);
    });
    
    // Email: formato correcto y sin espacios
    document.getElementById('email').addEventListener('input', function() {
        validarEmail(this, validationMessages.email.invalid);
        validarSinEspacios(this, validationMessages.email.noSpaces);
    });
    
    // Teléfono: solo números
    document.getElementById('telefono').addEventListener('input', function() {
        validarSoloNumeros(this, validationMessages.telefono.numbersOnly);
    });
}

// Ejecutar validaciones al cargar la página
document.addEventListener('DOMContentLoaded', aplicarValidaciones);




