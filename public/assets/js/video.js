document.getElementById('abrirVideo').addEventListener('click', function(e) {
  e.preventDefault();
  document.getElementById('modalVideo').style.display = 'block';
});

document.querySelector('.cerrar').addEventListener('click', function() {
  document.getElementById('modalVideo').style.display = 'none';
});

window.addEventListener('click', function(e) {
  const modal = document.getElementById('modalVideo');
  if (e.target === modal) {
    modal.style.display = 'none';
  }
});

