var marker_actual;
var circulo;
console.log("Cargado ver_mapa.js");
var Icon = L.icon({
    iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
    shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
    iconSize: [25, 41], 
    iconAnchor: [12, 41],
    popupAnchor: [1, -34],
    shadowSize: [41, 41]
});

//Crear el mapa
var map = L.map('map', {
    zoomDelta: 0.25,
    zoomSnap: 0
});

//Añadir capa de OpenStreetMap
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://openstreetmap.org">OpenStreetMap</a> contributors',
    maxZoom: 18
}).addTo(map);

//Obtener dirección a partir de coordenadas
function obtenerDireccion(lat, lon) {
    fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lon}&format=json`)
        .then(response => response.json())
        .then(data => {
            let direccion = data.display_name || "Ubicación desconocida";
            crearMarcador(lat, lon, direccion);
        })
        .catch(error => {
            console.error('Error al obtener la dirección:', error);
        });
}

// Crear marcador en el mapa
function crearMarcador(lat, lon, direccion) {
    if (marker_actual) {
        map.removeLayer(marker_actual);
        map.removeLayer(circulo);
    }

    marker_actual = L.marker([lat, lon], { icon: Icon }).addTo(map);
    marker_actual.bindPopup(`Latitud: ${lat}<br>Longitud: ${lon}<br>${direccion}`).openPopup();

    circulo = L.circle([lat, lon], { radius: 5 }).addTo(map);

    map.setView([lat, lon], 18);
}

//Obtener coordenadas desde la API
function obtenerCoordenadas(direccion) {
    const key = "hdKDIQjbdYjuRZa73PzdkomXAvylrHmFR0REdd2nr0KDzdUR1xhMBQnV4IUIqAsh";

    console.log(`Buscando ubicación para: ${direccion}`);
    fetch(`https://api.distancematrix.ai/maps/api/geocode/json?address=${encodeURIComponent(direccion)}&key=${key}`)
        .then(response => response.json())
        .then(data => {
            console.log("📡 Respuesta de la API:", data);
            if (data.status === "OK" && data.result.length > 0) {
                let lat = data.result[0].geometry.location.lat;
                let lon = data.result[0].geometry.location.lng;
                let dir = direccion;
                crearMarcador(lat, lon, dir);
            } else {
                alert("No se encontraron coordenadas.");
            }
        })
        .catch(error => console.error('Error al obtener coordenadas:', error));
}

//Mostrar mapa
function verMapa(direccion) {
    obtenerCoordenadas(direccion);

    const mapContainer = document.getElementById('mapContainer');
    mapContainer.style.display = 'block';
}

//Cerrar mapa
function cerrarMapa() {
    const mapContainer = document.getElementById('mapContainer');
    mapContainer.style.display = 'none';
}


    document.querySelectorAll('.enlace_mapa').forEach((enlace) => {
        enlace.addEventListener('click', () => {
            let direccion = enlace.closest('tr').querySelector('#localizacion').innerText + ' España';
            verMapa(direccion);
        });
    });

