document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("form");
    const usuarioInput = document.getElementById("usu");
    const claveInput = document.getElementById("pas");

    form.addEventListener("submit", function (event) {
        const usuario = usuarioInput.value.trim();
        const clave = claveInput.value.trim();
       
        usuarioInput.style.borderColor = "";
        claveInput.style.borderColor = "";

        if (usuario === "" || clave === "") {
            event.preventDefault();

            let mensaje = "Por favor completa los siguientes campos:\n";
            if (usuario === "") {
                mensaje += "- Usuario\n";
                usuarioInput.style.borderColor = "red";
            }
            if (clave === "") {
                mensaje += "- Contraseña\n";
                claveInput.style.borderColor = "red";
            }

            alert(mensaje);
        }
    });
});
