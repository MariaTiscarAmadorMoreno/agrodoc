const contactForm = document.getElementById('contactForm');

contactForm.addEventListener('submit', function (event) {
  event.preventDefault();
  const form = event.target;
  let isValid = true;
  let firstError = null;

  // Patrones
  const patronEmail = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
  const patronCIF = /^[A-HJNPQRSUVW]\d{7}$/i;
  const patronTel = /^\d{9}$/;

  // Limpiar errores previos
  form.querySelectorAll('.error-dinamico').forEach(el => el.remove());

  // Validación general
  form.querySelectorAll('.required').forEach(field => {
    const value = field.type === 'checkbox' ? field.checked : field.value.trim();

    if (!value) {
      crearError(field, 'Este campo es obligatorio');
      isValid = false;
      if (!firstError) firstError = field;
    } else {
      // Validar el email
      if (field.id === 'email' && !patronEmail.test(value)) {
        crearError(field, 'Introduce un correo válido');
        isValid = false;
        if (!firstError) firstError = field;
      }

      // Validar el CIF
      if (field.id === 'cif' && !patronCIF.test(value)) {
        crearError(field, 'Introduce un CIF válido, debe tener una letra y 7 dígitos');
        isValid = false;
        if (!firstError) firstError = field;
      }
    }
  });

  // Validar el teléfono
  const telefono = document.getElementById('telefono');
  const telVal = telefono.value.trim();
  if (telVal && !patronTel.test(telVal)) {
    crearError(telefono, 'Teléfono no válido, debe tener 9 dígitos.');
    isValid = false;
    if (!firstError) firstError = telefono;
  }

  if (isValid) {
    alert('Formulario enviado correctamente');
    form.reset();
    form.querySelectorAll('.error-dinamico').forEach(el => el.remove());
  } else {
    alert('No se puede enviar el formulario. Hay errores o campos vacíos. Revisa los campos marcados.');
    firstError?.focus();
  }
});

// Función para crear los mensajes de error
function crearError(field, mensaje) {
  field.setAttribute('aria-invalid', 'true');
  field.style.border = '2px solid red';

  const errorDiv = document.createElement('div');
  errorDiv.className = 'error-dinamico';
  errorDiv.textContent = mensaje;
  errorDiv.style.color = 'red';
  errorDiv.style.fontSize = '0.9em';
  errorDiv.style.marginTop = '5px';

  field.parentNode.insertBefore(errorDiv, field.nextSibling);
}
