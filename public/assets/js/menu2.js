const menu = document.getElementById('menuHamburguesa');

// Esperar a que se cargue #barra
function activarMenuHamburguesa() {
  const nav = document.getElementById('nav');
  if (nav) {
    menu.addEventListener('click', () => {
      nav.classList.toggle('open');
    });
  }
}

