
const contactForm = document.getElementById('contactForm');
contactForm.addEventListener('submit', function(event) {
    event.preventDefault();

    let isValid = true;
    const form = event.target;

    // Validación para que no se quede ningún campo vacío
    form.querySelectorAll('[required]').forEach(field => {
        if (!field.value.trim()) {
            field.style.border = '2px solid red';
            isValid = false;
        } else {
            field.style.border = '1px solid #ccc';
        }
    });

    const email = document.getElementById('email');
    var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    if (!emailPattern.test(email.value)) {
        email.style.border = '2px solid red';
        isValid = false;
    } else {
        email.style.border = '1px solid #ccc';
    }

    // Validación del teléfono 
    const telefono = document.getElementById('telefono');
    if (!/^\d{9}$/.test(telefono.value)) {
        telefono.style.border = '2px solid red';
        isValid = false;
    }

    // Mostrar mensaje si todo es válido
    if (isValid) {
        alert('Formulario enviado correctamente');
        form.reset(); // Limpiar el formulario
    } else {
        alert('Hay errores en el formulario. Revisa los campos resaltados.');
    }
});

