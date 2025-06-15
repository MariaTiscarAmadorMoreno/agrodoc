const formacionForm = document.getElementById('formacionForm');

formacionForm.addEventListener('submit', function (event) {
  event.preventDefault();
  const form = event.target;
  let isValid = true;
  let firstError = null;

  form.querySelectorAll('.required').forEach(field => {
    const value = field.value.trim();
    const errorElement = document.getElementById(`error-${field.id}`);

    if (!value) {
      muestraError(field, errorElement, 'Este campo es obligatorio');
      isValid = false;
      if (!firstError) firstError = field;
    } else {
      clearError(field, errorElement);
    }
    const patronEmail = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    // Validación específica del correo
    if (field.id === 'email-formacion' && !patronEmail.test(value)) {
      muestraError(field, errorElement, 'Introduce un correo válido');
      isValid = false;
      if (!firstError) firstError = field;
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

