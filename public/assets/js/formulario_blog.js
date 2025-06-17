const contactFormSuscripcion = document.getElementById('contactFormSuscripcion');

contactFormSuscripcion.addEventListener('submit', function (event) {
  event.preventDefault();
  const form = event.target;
  let isValid = true;
  let firstError = null;

  // Lista de campos a validar
  const campos = ['email', 'consentimiento'];

  campos.forEach(id => {
    const field = document.getElementById(id);
    const value = field.type === 'checkbox' ? field.checked : field.value.trim();
    const errorElement = document.getElementById(`error-${id}`);

        // Validación específica del email
    if (id === 'email' && value) {
      const patronEmail = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
      if (!patronEmail.test(field.value.trim())) {
        muestraError(field, errorElement, 'Introduce un correo válido');
        isValid = false;
        if (!firstError) firstError = field;
      }
    }

    // Validación general
    if (
      (field.type === 'checkbox' && !value) ||
      (field.type !== 'checkbox' && !value)
    ) {
      muestraError(field, errorElement, 'Este campo es obligatorio');
      isValid = false;
      if (!firstError) firstError = field;
    } else {
      clearError(field, errorElement);
    }


  });

  if (isValid) {
    alert('Solicitud enviada correctamente');
    form.reset();
    form.querySelectorAll('.error-message').forEach(el => el.textContent = '');
  } else {
    alert('Hay errores o campos vacíos en el formulario.');
    firstError?.focus();
  }
});

function muestraError(field, errorElement, message) {
  field.setAttribute('aria-invalid', 'true');
  if (errorElement) errorElement.textContent = message;
  field.style.border = '2px solid red';
}

function clearError(field, errorElement) {
  field.removeAttribute('aria-invalid');
  if (errorElement) errorElement.textContent = '';
  field.style.border = '1px solid #ccc';
}
