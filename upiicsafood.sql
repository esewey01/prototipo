---------BASE DE DATOS UPIICSA FOOD
--UGARTE VELASQUEZ DAVID
--UPIICSAFOOD

--CREATE DATABASE UPIICSAFOOD
--TABLA DE ROLES PARA LOS USUARIOS
CREATE TABLE ROLES (
  id_rol INT IDENTITY(0,1) PRIMARY KEY,
  nombre_rol VARCHAR(50) NOT NULL UNIQUE,
  descripcion VARCHAR(255),
);
--TABLA PARA DEFINIR LOS ROLES 
INSERT INTO ROLES (nombre_rol, descripcion ) VALUES
('Super User','Acceso completo al sistema'),
('Administrador', 'Funciones autorizadas del manejo del sistema'),
('Vendedor', 'Puede gestionar productos y ventas'),
('Cliente', 'Puede realizar compras');



--TABLA DE USUARIOS 
CREATE TABLE USUARIOS (
  id_usuario INT IDENTITY(1,1) PRIMARY KEY,
  login VARCHAR(50) UNIQUE NOT NULL,
  email VARCHAR(100) ,
  password VARCHAR(255) NOT NULL,
  nombre VARCHAR(100) NOT NULL,
  apellido VARCHAR(100),
  telefono VARCHAR(20),
  direccion VARCHAR(255),
  fecha_nacimiento DATE,
  genero CHAR(1) CHECK (genero IN ('M', 'F', 'O', NULL)),
  foto_perfil VARCHAR(255) DEFAULT 'fotoproducto/user.png',
  fecha_registro DATETIME DEFAULT GETDATE(),
  ultimo_acceso DATETIME,
  activo BIT DEFAULT 1,
  verificado BIT DEFAULT 0,
  intentos_fallidos INT DEFAULT 0,
  fecha_bloqueo DATETIME NULL,
  token_verificacion VARCHAR(100),
  fecha_expiracion_token DATETIME
);



INSERT INTO USUARIOS (login, email, password, nombre, apellido, telefono, direccion, 
fecha_nacimiento, genero, foto_perfil, activo, verificado) VALUES

--SUPER USUARIO
('admin', 'david.ugarte@empresa.com', 'admin', 'David', 'Ugarte', '55-6158-4615', 'Av. Principal 123, Ciudad', '1985-05-15', 'M', 'fotoproducto/user.png', 1, 1);
-- Administradores
('2020601052', 'david.ugarte@empresa.com', 'david', 'David', 'Ugarte', '55-6158-4615', 'Av. Principal 123, Ciudad', '1985-05-15', 'M', 'fotoproducto/user.png', 1, 1),
('henry', 'henry@empresa.com', 'henry', 'Henrych',NULL, '55-1234-5678', 'Calle Secundaria 456, Ciudad', '1990-08-20', 'M', 'fotoproducto/user02.jpg', 1, 1),

-- Vendedores
('2017106441', 'david.vendedor@empresa.com', 'david', 'David', 'Ugarte', '55-6158-4615', 'Av. Comercial 789, Ciudad', '1988-03-10', 'M', 'fotoproducto/user.png', 1, 1),
('2056465123', 'sussy@empresa.com', '123', 'Sussy', 'Gómez', '33-4567-8901', 'Boulevard Central 321, Ciudad', '1992-11-25', 'F', 'fotoproducto/user.png', 1, 1),

-- Clientes
('2018105841', 'juan.perez@empresa.com', '123', 'Juan', 'Perez', '81-8765-4321', 'Calle Norte 654, Ciudad', '1987-07-30', 'M', 'fotoproducto/user04.jpg', 1, 1),
('2018642322', 'carolina.valdivia@empresa.com', '123', 'Carolina', 'Valdivia', '55-6789-0123', 'Av. Sur 987, Ciudad', '1991-04-18', 'F', 'fotoproducto/user07.jpg', 1, 1),
('2019586523', 'thais.calani@empresa.com', '123', 'Thais', 'Calani', '81-9876-5432', 'Calle Este 147, Ciudad', '1993-09-05', 'F', 'fotoproducto/userM4.jpg', 1, 1),
('2020605213', 'lety.calani@cliente.com', '2020605213', 'Lety', 'Calani', '33-5678-9012', 'Residencial Las Flores 258, Ciudad', '1995-02-14', 'F', 'fotoproducto/user.png', 1, 1),
('2017656456', 'test.user@cliente.com', '123', 'Test', 'User', '81-7654-3210', 'Privada Jardines 369, Ciudad', NULL, NULL, 'fotoproducto/userM3.jpg', 1, 0);

--TABLA DE ROLES DE USUARIO
CREATE TABLE ROLES_USUARIO (
  id_usuario_rol INT IDENTITY(1,1) PRIMARY KEY,
  id_usuario INT NOT NULL,
  id_rol INT NOT NULL,
  fecha_asignacion DATETIME DEFAULT GETDATE(),
  FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE,
  FOREIGN KEY (id_rol) REFERENCES roles(id_rol) ON DELETE CASCADE,
);

SELECT*FROM USUARIOS
SELECT*FROM ROLES

UPDATE ROLES SET nombre_rol='SuperUser' where id_rol=0
SELECT*FROM ROLES_USUARIO
INSERT INTO ROLES_USUARIO(id_usuario, id_rol) VALUES
(49,0);
-- Administradores (rol 1)
(1, 1), -- David Ugarte
(2, 1), -- Henrych

-- Vendedores (rol 2)
(3, 2), -- David Ugarte (vendedor)
(4, 2), -- Sussy


-- Clientes (rol 3)
(5, 3), -- Juan Perez
(6, 3), -- Carolina Valdivia
(7, 3), -- Thais Calani
(8, 3), -- LetyCalani
(9, 3); -- Test123334

/*ACTUALIZAR A LOS VENDEDORES*/

UPDATE ROLES_USUARIO SET id_rol = 3 WHERE id_usuario > 4; -- Cambiar rol de David Ugarte a cliente

--TABLA DE REDES SOCIALES

CREATE TABLE REDES_SOCIALES(
  id_red_social INT IDENTITY(1,1) PRIMARY KEY,
  id_usuario INT NOT NULL ,
  tipo_red VARCHAR(50) ,
  url_perfil VARCHAR(255) ,
  nombre_usuario VARCHAR(100),
  fecha_vinculacion DATETIME DEFAULT GETDATE(),
  FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE
);
-- Redes sociales para algunos usuarios
INSERT INTO REDES_SOCIALES (id_usuario, tipo_red, url_perfil, nombre_usuario ) VALUES
(1, 'Facebook', 'https://facebook.com/david.ugarte', 'david.ugarte'),
(1, 'LinkedIn', 'https://linkedin.com/in/david-ugarte', 'david-ugarte'),
(2, 'Twitter', 'https://twitter.com/henrych_admin', 'henrych_admin'),
(4, 'Instagram', 'https://instagram.com/sussy_vendedora', 'sussy_vendedora'),
(7, 'Facebook', 'https://facebook.com/thais.calani', 'thais.calani'),
(8, 'Instagram', 'https://instagram.com/lety_calani', 'lety_calani');

use upiicsafood
DROP TABLE MENU
--TABLA MENU 
CREATE TABLE MENU (
  id_menu INT IDENTITY(1,1) PRIMARY KEY,
  opcion VARCHAR(100) NOT NULL,
  estado VARCHAR(50) NOT NULL DEFAULT 'Activo',
  icono VARCHAR(100) NOT NULL,
  ubicacion VARCHAR(150) ,
  color VARCHAR(150) NOT NULL DEFAULT '#ffffff',
  acceso VARCHAR(50) NOT NULL DEFAULT 'A',
  orden INT NOT NULL DEFAULT 0,
  id_rol INT, -- NULL = para todos los roles
  FOREIGN KEY (id_rol) REFERENCES ROLES(id_rol)
);


-- Opciones para todos los usuarios (id_rol = NULL)
INSERT INTO MENU (opcion, estado, icono, ubicacion, color, acceso, id_rol, orden) VALUES
('Principal', 'Activo', 'icon_house', 'PrincipalController.php', '#ffffff', 'A',  NULL, 0),
('Perfil', 'Activo', 'icon_profile', 'PerfilController.php', '#ffffff', 'A',  NULL,1),
('Comprar', 'Activo', 'icon_creditcard', 'ComprarController.php', '#ffffff', 'A',NULL, 2),
('Carrito', 'Activo', 'icon_cart', 'CarritoController.php', '#ffffff', 'A', NULL,3),
('Historial', 'Activo', 'icon_document', 'HistorialController.php', '#ffffff', 'A',NULL, 4);

-- Opciones solo para administradores (id_rol = 1)
INSERT INTO MENU (opcion, estado, icono, ubicacion, color, acceso, id_rol, orden) VALUES
('Usuarios', 'Activo', 'icon_organigrama', 'UsuariosController.php', '#ffffff', 'A', 1, 5),
('Solicitudes', 'Activo', 'icon_documents', 'SolicitudesController.php', '#ffffff', 'A', 1, 6),
('Productos', 'Activo', 'icon_datareport_alt', 'ProductoController.php', '#ffffff', 'A', 1, 7),--TAMBIEN PARA ADMIN
('Reportes', 'Activo', 'icon_clipboard', 'ReportesController.php', '#ffffff', 'A', 1, 8),
('Configuración', 'Activo', 'icon_tools', 'ConfiguracionController.php', '#ffffff', 'A' ,1, 9);

-- Opciones para vendedores (id_rol = 2)
INSERT INTO MENU (opcion, estado, icono, ubicacion, color, acceso, id_rol, orden) VALUES
('Productos', 'Activo', 'icon_datareport_alt', 'ProductoController.php', '#ffffff', 'A', 2, 5),--TAMBIEN PARA ADMIN
('Ventas', 'Activo', 'icon_cart', 'VentasController.php', '#ffffff', 'A', 2, 6),
('Clientes', 'Activo', 'icon_organigrama', 'ClientesController.php', '#ffffff', 'A', 2, 7);



--TABLA PARA CATEGORIZAR PRODUCTOS 

CREATE TABLE CATEGORIAS (
  id_categoria INT IDENTITY(1,1) PRIMARY KEY,
  nombre_categoria VARCHAR(100) NOT NULL,
  descripcion VARCHAR(255),
  estado VARCHAR(20) DEFAULT 'ACTIVO',
  fecha_creacion DATETIME DEFAULT GETDATE()
);

INSERT INTO CATEGORIAS (nombre_categoria, descripcion) VALUES
('Comida', 'Producto preparado'),
('Refrescos', 'Bebidas gaseosas y jugos'),
('Dulces', 'Golosinas de tamaños pequeños'),
('Otros', 'Otros productos diversos');

--------------------------SOLICITUDESVENDEDOR
CREATE TABLE SOLICITUDES_VENDEDOR (
  id_solicitud INT IDENTITY(1,1) PRIMARY KEY,
  id_usuario INT NOT NULL,
  descripcion TEXT,
  estado VARCHAR(20) NOT NULL DEFAULT 'PENDIENTE' CHECK (estado IN ('PENDIENTE', 'APROBADA', 'RECHAZADA')),
  fecha_solicitud DATETIME DEFAULT GETDATE(),
  fecha_revision DATETIME ,
  id_revisor INT ,
  comentarios TEXT NULL,
  FOREIGN KEY (id_usuario) REFERENCES USUARIOS(id_usuario),
  FOREIGN KEY (id_revisor) REFERENCES USUARIOS(id_usuario)
);

-- Solicitud pendiente
INSERT INTO SOLICITUDES_VENDEDOR (id_usuario, descripcion, estado) 
VALUES (5, 'Quiero vender mis productos de repostería', 'PENDIENTE');

-- Vendedor aprobado
INSERT INTO SOLICITUDES_VENDEDOR (id_usuario, descripcion, estado, fecha_revision, id_revisor, comentarios) 
VALUES (6, 'Venta de artículos electrónicos', 'APROBADA', GETDATE(), 1, 'Documentación completa');

-- Solicitud rechazada
INSERT INTO SOLICITUDES_VENDEDOR (id_usuario, descripcion, estado, fecha_revision, id_revisor, comentarios) 
VALUES (7, 'Venta de ropa usada', 'RECHAZADA', GETDATE(), 1, 'No cumple políticas de calidad');


---TABLA DE PRODUCTOS
CREATE TABLE PRODUCTOS (
  id_producto INT IDENTITY(1,1) PRIMARY KEY,
  id_usuario INT NOT NULL,  -- NULL para productos del sistema
  id_categoria INT NOT NULL,
  codigo VARCHAR(50) NULL,
  nombre_producto VARCHAR(100) NOT NULL,
  descripcion VARCHAR(255),
  cantidad INT NOT NULL DEFAULT 0,
  precio_venta DECIMAL(10,2) NOT NULL,
  precio_compra DECIMAL(10,2) NOT NULL,
  imagen VARCHAR(255) DEFAULT 'fotoproducto/default.jpg',
  fecha_registro DATETIME DEFAULT GETDATE(),
  estado VARCHAR(20) DEFAULT 'ACTIVO' CHECK (estado IN ('ACTIVO', 'INACTIVO', 'AGOTADO')),
  FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario),
  FOREIGN KEY (id_categoria) REFERENCES CATEGORIAS(id_categoria),
);


SELECT*FROM PRODUCTOS
UPDATE PRODUCTOS 
SET id_usuario=4
WHERE id_producto>10



-- Insertar tus datos de productos (adaptado al nuevo esquema)
INSERT INTO PRODUCTOS (
  id_usuario, id_categoria, nombre_producto, descripcion, cantidad, precio_venta, precio_compra, imagen, fecha_registro, estado
) VALUES
-- Productos de Pollo (Proveedor: POLLOS IMBA SRL)
( 3, 1,  'Un Cuarto', 'Un Cuarto', 100, 23.00, 6.00, 'fotoproducto/imagen_1349563845.jpg', '2020-06-12', 'ACTIVO'),
( 3, 1,  'DUOPOLLO', 'DUOPOLLO', 15, 16.00, 6.00, 'fotoproducto/imagen_1349563882.jpg', '2018-04-28', 'ACTIVO'),
( 3, 1,  'LIMITO', 'LIMITO', 12, 13.00, 6.00, 'fotoproducto/imagen_1349563944.jpg', '2018-04-28', 'ACTIVO'),
( 3, 1,  'ECONOMICO', 'ECONOMICO', 8, 8.00, 0.00, 'fotoproducto/imagen_1349563944.jpg', '2018-05-06', 'ACTIVO'),
( 3, 1,  'SUPER COMBO', 'SUPER COMBO', 100, 10.00, 6.00, 'fotoproducto/imagen_1349564147.jpg', '2018-05-06', 'ACTIVO'),
( 3, 1,  'ALMUERZO COMPLETO', 'ALMUERZO COMPLETO', 11, 10.00, 6.00, 'fotoproducto/imagen_1349564147.jpg', '2018-04-28', 'ACTIVO'),
( 3, 1,  'SEGUNDO', 'SEGUNDO', 7, 8.00, 6.00, 'fotoproducto/imagen_1349564229.jpg', '2018-04-28', 'ACTIVO'),
( 3, 1,  'HAMBURGUESA', 'HAMBURGUESA', 15, 8.00, 6.00, 'fotoproducto/imagen_1349564269.jpg', '2018-04-28', 'ACTIVO'),
( 3, 1,  'Pollo Entero', 'Pollo Entero', 12, 60.00, 6.00, 'fotoproducto/imagen_1349563882.jpg', '2018-04-28', 'ACTIVO'),
( 3, 1,  'MEDIO POLLO', 'MEDIO POLLO', 25, 30.00, 6.00, 'fotoproducto/imagen_1349563882.jpg', '2018-04-28', 'ACTIVO'),

-- Refrescos (Proveedor: ARIEL SA)
( 4, 2,  'SIMBA MANZANA', 'SIMBA MANZANA', 5, 10.00, 6.00, 'fotoproducto/imagen_1349564463.jpg', '2018-04-28', 'ACTIVO'),
( 4, 2,  'Popular Sprite', 'Popular Sprite', 100, 5.00, 6.00, 'fotoproducto/imagen_1349564498.jpg', '2016-08-22', 'ACTIVO'),
( 4, 2,  'Popular Coca Cola', 'Popular Coca Cola', 11, 5.00, 6.00, 'fotoproducto/imagen_1349564524.jpg', '2016-08-22', 'ACTIVO'),
( 4, 2,  'DEL VALLE', 'DEL VALLE', 100, 10.00, 6.00, 'fotoproducto/imagen_1349564587.jpg', '2018-04-28', 'ACTIVO'),
( 4, 2,  'COCA COLA 1 ltr', 'COCA COLA 1 ltr', 11, 8.00, 6.00, 'fotoproducto/imagen_1349564926.jpg', '2018-04-28', 'ACTIVO'),

-- Productos de Arroz (Proveedor: Arroz Okinawa)
( 4, 3,  'PORCION DE ARROZ', 'PORCION DE ARROZ', 10, 7.00, 6.00, 'fotoproducto/imagen_134951011.jpg', '2018-04-28', 'ACTIVO'),
( 3, 3,  'PORCION DE PAPA', 'PORCION DE PAPA', 100, 7.00, 6.00, 'fotoproducto/imagen_134951110.jpg', '2018-04-28', 'ACTIVO'),

-- Productos varios
( 4,4,    'Coca Cola 500 gr', 'Coca Cola 500 gr', 0, 7.00, 6.00, 'fotoproducto/cocacola.jpg', '2020-06-12', 'ACTIVO'),
( 4,4,   'Pepsi de 500 ml', 'Pepsi de 500 ml', 0, 12.00, 11.00, 'fotoproducto/pepsi.jpg', '2020-07-06', 'ACTIVO');

UPDATE PRODUCTO SET 
            id_categoria = ?,
            codigo = ?,
            nombre_producto = ?,
            descripcion = ?,
            cantidad = ?,
            precio_venta = ?,
            precio_compra = ?,
            fecha_registro = GETDATE()
            where id_producto=


--TABLA PARA REPORTES DE PRODUCTOS DEL USUARIO
CREATE TABLE REPORTES (
    id_reporte INT IDENTITY(1,1) PRIMARY KEY,
    tipo_reporte VARCHAR(20) CHECK(tipo_reporte IN ('PRODUCTO', 'USUARIO')),
    id_producto INT NULL,
    id_usuario_reportado INT NOT NULL,
    id_administrador INT NOT NULL,
    motivo VARCHAR(255) NOT NULL,
    accion_tomada VARCHAR(100) NOT NULL,
    fecha_reporte DATETIME DEFAULT GETDATE(),
    estado VARCHAR(20) DEFAULT 'PENDIENTE',
    comentarios text, 
    FOREIGN KEY (id_producto) REFERENCES PRODUCTOS(id_producto),
    FOREIGN KEY (id_usuario_reportado) REFERENCES USUARIOS(id_usuario),
    FOREIGN KEY (id_administrador) REFERENCES USUARIOS(id_usuario)
);



--TABLA DE CARRITOS
-- TABLA PARA EL CARRITO DE COMPRAS
CREATE TABLE CARRITO (
    id_carrito INT IDENTITY(1,1) PRIMARY KEY,
    id_usuario INT NOT NULL,
    fecha_creacion DATETIME DEFAULT GETDATE(),
    FOREIGN KEY (id_usuario) REFERENCES USUARIOS(id_usuario)
);

-- TABLA PARA LOS ITEMS DEL CARRITO
CREATE TABLE CARRITO_ITEMS (
    id_item INT IDENTITY(1,1) PRIMARY KEY,
    id_carrito INT NOT NULL,
    id_producto INT NOT NULL,
    cantidad INT NOT NULL DEFAULT 1,
    precio_unitario DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (id_carrito) REFERENCES CARRITO(id_carrito) ON DELETE CASCADE,
    FOREIGN KEY (id_producto) REFERENCES PRODUCTOS(id_producto)
);

-- TABLA PARA ÓRDENES DE COMPRA
CREATE TABLE ORDENES (
    id_orden INT IDENTITY(1,1) PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_vendedor INT NOT NULL,
    fecha_orden DATETIME DEFAULT GETDATE(),
    estado VARCHAR(20) DEFAULT 'PENDIENTE' CHECK (estado IN ('PENDIENTE', 'PROCESANDO', 'ENVIADO', 'ENTREGADO', 'CANCELADO')),
    total DECIMAL(10,2) NOT NULL,
    direccion_envio VARCHAR(255),
    notas TEXT,
    FOREIGN KEY (id_usuario) REFERENCES USUARIOS(id_usuario),
    FOREIGN KEY (id_vendedor) REFERENCES USUARIOS(id_usuario)
);

-- TABLA PARA DETALLES DE ÓRDENES
CREATE TABLE ORDEN_DETALLES (
    id_detalle INT IDENTITY(1,1) PRIMARY KEY,
    id_orden INT NOT NULL,
    id_producto INT NOT NULL,
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (id_orden) REFERENCES ORDENES(id_orden) ON DELETE CASCADE,
    FOREIGN KEY (id_producto) REFERENCES PRODUCTOS(id_producto)
);

-- TABLA PARA MENSAJES
CREATE TABLE MENSAJES (
    id_mensaje INT IDENTITY(1,1) PRIMARY KEY,
    id_remitente INT NOT NULL,
    id_destinatario INT NOT NULL,
    id_orden INT NULL,
    asunto VARCHAR(100),
    mensaje TEXT NOT NULL,
    fecha_envio DATETIME DEFAULT GETDATE(),
    leido BIT DEFAULT 0,
    FOREIGN KEY (id_remitente) REFERENCES USUARIOS(id_usuario),
    FOREIGN KEY (id_destinatario) REFERENCES USUARIOS(id_usuario),
    FOREIGN KEY (id_orden) REFERENCES ORDENES(id_orden)
);

-- TABLA PARA VALORACIONES
CREATE TABLE VALORACIONES (
    id_valoracion INT IDENTITY(1,1) PRIMARY KEY,
    id_orden INT NOT NULL,
    id_producto INT NOT NULL,
    id_usuario INT NOT NULL,
    id_vendedor INT NOT NULL,
    calificacion INT CHECK (calificacion BETWEEN 1 AND 5),
    comentario TEXT,
    fecha_valoracion DATETIME DEFAULT GETDATE(),
    respuesta_vendedor TEXT,
    fecha_respuesta DATETIME,
    FOREIGN KEY (id_orden) REFERENCES ORDENES(id_orden),
    FOREIGN KEY (id_producto) REFERENCES PRODUCTOS(id_producto),
    FOREIGN KEY (id_usuario) REFERENCES USUARIOS(id_usuario),
    FOREIGN KEY (id_vendedor) REFERENCES USUARIOS(id_usuario)
);




delete SOLICITUDES_VENDEDOR
USE UPIICSAFOOD
SELECT*FROM USUARIOS