CREATE DATABASE IF NOT EXISTS bdagrodoc;
USE bdagrodoc;

-- Tabla de Contratistas
CREATE TABLE contratistas (
    id_cont INTEGER AUTO_INCREMENT NOT NULL,
    nombre VARCHAR(60),   
    cif VARCHAR(20),
    email VARCHAR(50),
    telefono VARCHAR(20),
    direccion VARCHAR(100),
    PRIMARY KEY (id_cont)
);

-- Tabla de Fincas
CREATE TABLE fincas (
    id_finca INTEGER AUTO_INCREMENT NOT NULL,
    localizacion VARCHAR(100), 
    cultivo VARCHAR(50),
    hectarea INTEGER,
    id_cont INTEGER NOT NULL,
    PRIMARY KEY (id_finca),
    FOREIGN KEY (id_cont) REFERENCES contratistas(id_cont) ON UPDATE CASCADE ON DELETE CASCADE
);

-- Tabla de Proveedores
CREATE TABLE proveedores (
    id_prov INTEGER AUTO_INCREMENT NOT NULL,
    nombre VARCHAR(20),
    apellidos VARCHAR(60),
    cif VARCHAR(20),
    email VARCHAR(50),
    telefono VARCHAR(20),
    direccion VARCHAR(100),
    PRIMARY KEY (id_prov)
);

-- Tabla de Usuarios (con contraseñas encriptadas)
CREATE TABLE usuarios (
    id_usu INTEGER AUTO_INCREMENT NOT NULL,
    usuario VARCHAR(30) UNIQUE NOT NULL,
    clave VARCHAR(64) NOT NULL,  
    nombre VARCHAR(30),
    tipo ENUM('admin', 'contratista', 'proveedor') NOT NULL,
    id_cont INTEGER DEFAULT NULL,
    id_prov INTEGER DEFAULT NULL,
    FOREIGN KEY (id_cont) REFERENCES contratistas(id_cont) ON UPDATE CASCADE ON DELETE SET NULL,
    FOREIGN KEY (id_prov) REFERENCES proveedores(id_prov) ON UPDATE CASCADE ON DELETE SET NULL,
    PRIMARY KEY (id_usu)
);

-- Tabla de Trabajadores
CREATE TABLE trabajadores (
    id_trab INTEGER AUTO_INCREMENT NOT NULL,
    nombre VARCHAR(20),
    apellidos VARCHAR(60),
    dni VARCHAR(20),
    email VARCHAR(50),
    telefono VARCHAR(20),
    direccion VARCHAR(100),
    documentos BOOLEAN,
    id_prov INTEGER NOT NULL,
    PRIMARY KEY (id_trab),
    FOREIGN KEY (id_prov) REFERENCES proveedores(id_prov) ON UPDATE CASCADE ON DELETE CASCADE
);

--Tabla de Docuemntos
CREATE TABLE documentos (
    id_doc INTEGER AUTO_INCREMENT NOT NULL,
    tipo_documento ENUM('dni', 'alta_ss', 'prl', 'reconocimiento_medico', 'aut_maquinaria') NOT NULL,
    ruta_archivo VARCHAR(255) NOT NULL,
    fecha_caducidad DATE, 
    id_trab INTEGER NOT NULL,
    PRIMARY KEY (id_doc),
    FOREIGN KEY (id_trab) REFERENCES trabajadores(id_trab) ON UPDATE CASCADE ON DELETE CASCADE
);


-- Tabla de Proyectos
CREATE TABLE proyectos (
    id_proyec INTEGER AUTO_INCREMENT NOT NULL,
    trabajo VARCHAR (100),
    fecha_inicio DATE,
    fecha_fin DATE,
    id_cont INTEGER NOT NULL,
    id_prov INTEGER NOT NULL,
    id_finca INTEGER NOT NULL,
    PRIMARY KEY (id_proyec),
    FOREIGN KEY (id_cont) REFERENCES contratistas(id_cont) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (id_prov) REFERENCES proveedores(id_prov) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (id_finca) REFERENCES fincas(id_finca) ON UPDATE CASCADE ON DELETE CASCADE
);

-- Relación entre proyectos y trabajadores
CREATE TABLE proyectos_trabajadores (
    id_proyecto_trabajador INTEGER AUTO_INCREMENT NOT NULL,
    id_trab INTEGER NOT NULL,
    id_proyec INTEGER NOT NULL,
    PRIMARY KEY (id_proyecto_trabajador),
    FOREIGN KEY (id_trab) REFERENCES trabajadores(id_trab) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (id_proyec) REFERENCES proyectos(id_proyec) ON UPDATE CASCADE ON DELETE CASCADE
);

-- INSERTAR DATOS DE PRUEBA

INSERT INTO contratistas (nombre, cif, email, telefono, direccion) VALUES
('Vicasol', 'C12345678', 'vicasol@example.com', '600123456', 'Calle Falsa 123, Vícar(Almería)'),
('Casi', 'C98765432', 'casi@example.com', '600654321', 'Avenida Real 45, La Cañada(Almería)');

INSERT INTO fincas (localizacion, cultivo, hectarea, id_cont) VALUES
('Vícar Norte', 'Tomate', 100, 1),
('Cañada Este', 'Pimiento', 50, 2);

INSERT INTO proveedores (nombre, apellidos, cif, email, telefono, direccion) VALUES
('Luis', 'Martínez Fernández', 'P12345678', 'luis.martinez@example.com', '600987654', 'Calle Verde 89, Aguadulce(Almería)'),
('Servicios Agrícolas', 'S.L.', 'P87654321', 'servicios.agricolas@example.com', '600876543', 'Paseo Central 12, Roquetas de Mar(Almería)');

INSERT INTO usuarios (usuario, clave, nombre, tipo, id_cont, id_prov) VALUES
('admin1', 'admin123', 'Maria Amador', 'admin', NULL, NULL),
('contratista1', 'pass123', 'Juan Perez', 'contratista', 1, NULL),
('proveedor1', 'prov123', 'Rafa Martínez', 'proveedor', NULL, 1);

INSERT INTO trabajadores (nombre, apellidos, dni, email, telefono, direccion, documentos, id_prov) VALUES
('Juan', 'Pérez Rodríguez', '12345678A', 'juan.perez@example.com', '600112233', 'Calle Azul 21, Almería', TRUE, 1),
('Carmen', 'Gómez Ruiz', '87654321B', 'carmen.gomez@example.com', '600445566', 'Calle Amarilla 67, Almería', TRUE, 2);

INSERT INTO documentos (tipo_documento,ruta_archivo,fecha_caducidad, id_trab) VALUES
('dni', '/documentos_trab', '2025-03-31', '1'),
('alta_ss', '/documentos_trab', '2025-05-31', '1'),
('prl', '/documentos_trab', '2025-08-31', '1'),
('reconocimiento_medico', '/documentos_trab', '2025-10-31', '1'),
('aut_maquinaria', '/documentos_trab', '2025-08-31', '1');

INSERT INTO proyectos (trabajo,fecha_inicio, fecha_fin, id_cont, id_prov, id_finca) VALUES
('recogida de cosecha', '2024-01-01', '2024-03-31', 1, 1, 1),
('plantar', '2024-04-01', '2024-06-30', 2, 2, 2);

INSERT INTO proyectos_trabajadores (id_trab, id_proyec) VALUES
(1, 1),
(2, 2);
