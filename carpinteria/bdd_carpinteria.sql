
-- Módulo: Gestión de Productos
CREATE TABLE productos (
    id_producto SERIAL PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    precio_estimado DECIMAL(10,2),
    tiempo_estimado INT,
    estado VARCHAR(20) DEFAULT 'Activo'
);

ALTER TABLE productos
ADD COLUMN fotografia BYTEA;

-- Módulo: Gestión de Materiales
CREATE TABLE materiales (
    id_material SERIAL PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    unidad VARCHAR(50),
    costo_unitario DECIMAL(10,2),
    estado VARCHAR(20) DEFAULT 'Activo'
);

ALTER TABLE materiales
RENAME COLUMN unidad TO cantidad;

ALTER TABLE materiales
ALTER COLUMN cantidad TYPE INTEGER
USING cantidad::INTEGER;

ALTER TABLE materiales
DROP COLUMN cantidad;

ALTER TABLE materiales
ADD COLUMN id_categoria INT,
ADD CONSTRAINT fk_categoria_material FOREIGN KEY (id_categoria) REFERENCES categorias_materiales(id_categoria);




-- Relación Producto - Material (Muchos a Muchos)
CREATE TABLE producto_material (
    id_promat SERIAL PRIMARY KEY,
    id_producto INT REFERENCES productos(id_producto) ON DELETE CASCADE,
    id_material INT REFERENCES materiales(id_material) ON DELETE CASCADE,
    cantidad DECIMAL(10,2) NOT NULL
);

-- Módulo: Gestión de Clientes
CREATE TABLE clientes (
    id_cliente SERIAL PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    telefono VARCHAR(20),
    correo VARCHAR(100),
    direccion TEXT,
    estado VARCHAR(20) DEFAULT 'Activo'
);

-- Módulo: Gestión de Proveedores
CREATE TABLE proveedores (
    id_proveedor SERIAL PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    telefono VARCHAR(20),
    correo VARCHAR(100),
    direccion TEXT,
    estado VARCHAR(20) DEFAULT 'Activo'
);

ALTER TABLE proveedores
ADD COLUMN id_producto INT REFERENCES productos(id_producto) ON DELETE SET NULL,
ADD COLUMN precio_producto DECIMAL(10,2);

-- Relación Material - Proveedor (Muchos a Muchos)
CREATE TABLE material_proveedor (
    id:matpro SERIAL PRIMARY KEY,
    id_proveedor INT REFERENCES proveedores(id_proveedor) ON DELETE CASCADE,
    id_material INT REFERENCES materiales(id_material) ON DELETE CASCADE,
    precio DECIMAL(10,2) NOT NULL
);


-- Módulo: Inventarios
CREATE TABLE inventarios (
    id_inv SERIAL PRIMARY KEY,
    id_material INT REFERENCES materiales(id_material) ON DELETE CASCADE,
    cantidad DECIMAL(10,2) NOT NULL,
    fecha_actualizacion DATE DEFAULT CURRENT_DATE
);

ALTER TABLE inventarios
DROP CONSTRAINT inventarios_id_material_fkey;

ALTER TABLE inventarios
ALTER COLUMN id_material DROP NOT NULL;

ALTER TABLE inventarios
ADD COLUMN tipo VARCHAR(20) NOT NULL DEFAULT 'Material';

ALTER TABLE inventarios
ADD COLUMN descripcion TEXT;

ALTER TABLE inventarios
ALTER COLUMN cantidad TYPE INT USING cantidad::integer;

ALTER TABLE inventarios
ALTER COLUMN fecha_actualizacion TYPE TIMESTAMP USING fecha_actualizacion::timestamp;

ALTER TABLE inventarios
ALTER COLUMN fecha_actualizacion SET DEFAULT CURRENT_TIMESTAMP;

ALTER TABLE inventarios
ADD CONSTRAINT fk_inventario_material FOREIGN KEY (id_material) 
REFERENCES materiales(id_material) ON DELETE CASCADE;

ALTER TABLE inventarios
    ADD COLUMN largo DECIMAL(10,2) DEFAULT NULL,
    ADD COLUMN alto DECIMAL(10,2) DEFAULT NULL,
    ADD COLUMN ancho DECIMAL(10,2) DEFAULT NULL,
    ADD COLUMN tipo_material VARCHAR(50) DEFAULT NULL;

ALTER TABLE inventarios
    ADD COLUMN precio_unitario DECIMAL(10,2) DEFAULT 0.00;


-- Módulo: Usuarios
CREATE TABLE usuarios (
    id_usuario SERIAL PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    clave VARCHAR(100) NOT NULL,
    rol VARCHAR(50),
    estado VARCHAR(20) DEFAULT 'Activo'
);

ALTER TABLE usuarios
DROP COLUMN rol;

ALTER TABLE usuarios
ADD COLUMN id_rol INT REFERENCES roles(id_rol) ON DELETE SET NULL;


CREATE TABLE roles (
    id_rol SERIAL PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE
);



-- Tabla de Cotizaciones
CREATE TABLE cotizaciones (
    id_cotizacion SERIAL PRIMARY KEY,
    id_cliente INT REFERENCES clientes(id_cliente) ON DELETE SET NULL,
    id_usuario INT REFERENCES usuarios(id_usuario) ON DELETE SET NULL,
    fecha DATE DEFAULT CURRENT_DATE,
    total_estimado DECIMAL(10,2),
    estado VARCHAR(20) DEFAULT 'Pendiente'
);

ALTER TABLE cotizaciones
    RENAME COLUMN total_estimado TO costo_total;

ALTER TABLE cotizaciones
    ADD COLUMN comentario TEXT;



-- Detalle de Cotizaciones
CREATE TABLE detalle_cotizacion (
    id_detalle SERIAL PRIMARY KEY,
    id_cotizacion INT REFERENCES cotizaciones(id_cotizacion) ON DELETE CASCADE,
    id_producto INT REFERENCES productos(id_producto) ON DELETE SET NULL,
    cantidad INT NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL
);

ALTER TABLE detalle_cotizacion
ADD COLUMN id_material INT REFERENCES materiales(id_material) ON DELETE SET NULL;

ALTER TABLE detalle_cotizacion
ADD COLUMN ancho DECIMAL(10,2),
ADD COLUMN alto DECIMAL(10,2),
ADD COLUMN profundidad DECIMAL(10,2);

ALTER TABLE detalle_cotizacion
    ADD COLUMN precio_unitario DECIMAL(10,2) NOT NULL DEFAULT 0.00;

ALTER TABLE detalle_cotizacion
    DROP COLUMN subtotal;

ALTER TABLE detalle_cotizacion
    ADD COLUMN subtotal DECIMAL(10,2) GENERATED ALWAYS AS (cantidad * precio_unitario) STORED;

ALTER TABLE detalle_cotizacion
    ALTER COLUMN ancho SET DEFAULT 0.00,
    ALTER COLUMN alto SET DEFAULT 0.00,
    ALTER COLUMN profundidad SET DEFAULT 0.00;

ALTER TABLE detalle_cotizacion ALTER COLUMN subtotal DROP EXPRESSION;


CREATE TABLE categorias_materiales (
    id_categoria SERIAL PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT
);

INSERT INTO categorias_materiales (nombre, descripcion) VALUES
('Madera', 'Materiales provenientes de la madera (pino, cedro, arce)'),
('Metal', 'Materiales metálicos para refuerzos y estructuras'),
('Vidrio', 'Materiales de vidrio utilizados en acabados');

