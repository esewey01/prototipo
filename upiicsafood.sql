---------BASE DE DATOS UPIICSA FOOD
--UGARTE VELASQUEZ DAVID
--UPIICSAFOOD

--CREATE DATABASE UPIICSAFOOD
--------------------------TABLA DE ROLES PARA LOS USUARIOS
CREATE TABLE ROLES (
  id_rol INT IDENTITY(0,1) PRIMARY KEY,
  nombre_rol VARCHAR(50) NOT NULL UNIQUE,
  descripcion VARCHAR(255),
);

use UPIICSAFOOD
SELECT*FROM usuarios
SELECT*FROM ROLES_USUARIO
SELECT SCOPE_IDENTITY() AS last_id
SELECT*FROM ROLES
SELECT id_rol, nombre_rol from ROLES

DELETE usuarios 
where id_usuario>9

SELECT*FROM REDES_SOCIALES

UPDATE REDES_SOCIALES SET 
url_perfil = 'https://www.facebook.com/profile.php?id=100004708937399'
 WHERE id_usuario = 3 AND tipo_red = 'facebook'

ALTER TABLE USUARIOS
ALTER COLUMN email VARCHAR(100) NULL;


SELECT u.*, r.nombre_rol, r.id_rol 
            FROM USUARIOS u
            JOIN ROLES_USUARIO ur ON u.id_usuario = ur.id_usuario
            JOIN ROLES r ON ur.id_rol = r.id_rol
            WHERE u.login = '2020601052'

SELECT*FROM ROLES

SELECT u.*, r.nombre_rol 
            FROM USUARIOS u
            JOIN ROLES r ON u.id_rol = r.id_rol
            WHERE u.id_rol = '1'
            ORDER BY u.nombre

--TABLA PARA DEFINIR LOS ROLES 

INSERT INTO ROLES (nombre_rol, descripcion ) VALUES
('Super User','Acceso completo al sistema'),
('Administrador', 'Funciones autorizadas del manejo del sistema'),
('Vendedor', 'Puede gestionar productos y ventas'),
('Cliente', 'Puede realizar compras');

--------------------------TABLA DE USUARIOS 
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
-- Administradores
('2020601052', 'david.ugarte@empresa.com', 'david', 'David', 'Ugarte', '55-6158-4615', 'Av. Principal 123, Ciudad', '1985-05-15', 'M', 'fotoproducto/user.png', 1, 1),
('henry', 'henry@empresa.com', 'henry', 'Henrych', NULL, '55-1234-5678', 'Calle Secundaria 456, Ciudad', '1990-08-20', 'M', 'fotoproducto/user02.jpg', 1, 1),

-- Vendedores
('2017106441', 'david.vendedor@empresa.com', 'david', 'David', 'Ugarte', '55-6158-4615', 'Av. Comercial 789, Ciudad', '1988-03-10', 'M', 'fotoproducto/user.png', 1, 1),
('2056465123', 'sussy@empresa.com', '123', 'Sussy', 'Gómez', '33-4567-8901', 'Boulevard Central 321, Ciudad', '1992-11-25', 'F', 'fotoproducto/user.png', 1, 1),
('2018105841', 'juan.perez@empresa.com', '123', 'Juan', 'Perez', '81-8765-4321', 'Calle Norte 654, Ciudad', '1987-07-30', 'M', 'fotoproducto/user04.jpg', 1, 1),
('2018642322', 'carolina.valdivia@empresa.com', '123', 'Carolina', 'Valdivia', '55-6789-0123', 'Av. Sur 987, Ciudad', '1991-04-18', 'F', 'fotoproducto/user07.jpg', 1, 1),
('2019586523', 'thais.calani@empresa.com', '123', 'Thais', 'Calani', '81-9876-5432', 'Calle Este 147, Ciudad', '1993-09-05', 'F', 'fotoproducto/userM4.jpg', 1, 1),

-- Clientes
('2020605213', 'lety.calani@cliente.com', '2020605213', 'Lety', 'Calani', '33-5678-9012', 'Residencial Las Flores 258, Ciudad', '1995-02-14', 'F', 'fotoproducto/user.png', 1, 1),
('2017656456', 'test.user@cliente.com', '123', 'Test', 'User', '81-7654-3210', 'Privada Jardines 369, Ciudad', NULL, NULL, 'fotoproducto/userM3.jpg', 1, 0);
SELECT*FROM USUARIOS
SELECT*FROM ROLES_USUARIO
--------------------------TABLA DE ROLES DE USUARIO
CREATE TABLE ROLES_USUARIO (
  id_usuario_rol INT IDENTITY(1,1) PRIMARY KEY,
  id_usuario INT NOT NULL,
  id_rol INT NOT NULL,
  fecha_asignacion DATETIME DEFAULT GETDATE(),
  FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE,
  FOREIGN KEY (id_rol) REFERENCES roles(id_rol) ON DELETE CASCADE,
);
INSERT INTO ROLES_USUARIO(id_usuario, id_rol) VALUES
-- Administradores (rol 1)
(1, 1), -- David Ugarte
(2, 1), -- Henrych

-- Vendedores (rol 2)
(3, 2), -- David Ugarte (vendedor)
(4, 2), -- Sussy
(5, 2), -- Juan Perez
(6, 2), -- Carolina Valdivia
(7, 2), -- Thais Calani

-- Clientes (rol 3)
(8, 3), -- LetyCalani
(9, 3); -- Test123334


--------------------------TABLA DE REDES SOCIALES

CREATE TABLE REDES_SOCIALES(
  id_red_social INT IDENTITY(1,1) PRIMARY KEY,
  id_usuario INT NOT NULL,
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







--------------------------TABLA MENU 
CREATE TABLE MENU (
  id_menu INT IDENTITY(1,1) PRIMARY KEY,
  opcion VARCHAR(100) NOT NULL,
  estado VARCHAR(50) NOT NULL DEFAULT 'Activo',
  icono VARCHAR(100) NOT NULL,
  ubicacion VARCHAR(150) NULL,
  color VARCHAR(150) NOT NULL DEFAULT '#ffffff',
  acceso VARCHAR(50) NOT NULL DEFAULT 'A',
  orden INT NOT NULL DEFAULT 0,
  id_rol INT NULL, -- NULL = para todos los roles
  FOREIGN KEY (id_rol) REFERENCES ROLES(id_rol)
);


UPDATE MENU SET
icono='icon_house'
where opcion='principal'


-- Opciones para todos los usuarios (id_rol = NULL)
INSERT INTO MENU (opcion, estado, icono, ubicacion, color, acceso, id_rol, orden) VALUES
('Principal', 'Activo', 'icon_house', 'PrincipalController.php', '#ffffff', 'A', NULL, 1),
('Perfil', 'Activo', 'icon_profile', 'PerfilController.php', '#ffffff', 'A', NULL, 2);

-- Opciones solo para administradores (id_rol = 1)
INSERT INTO MENU (opcion, estado, icono, ubicacion, color, acceso, id_rol, orden) VALUES
('Usuarios', 'Activo', 'icon_users', 'UsuariosController.php', '#ffffff', 'A', 1, 3),
('Configuración', 'Activo', 'icon_tools', 'ConfiguracionController.php', '#ffffff', 'A', 1, 4),
('Reportes', 'Activo', 'icon_chart', 'ReportesController.php', '#ffffff', 'A', 1, 5);

-- Opciones para vendedores (id_rol = 2)
INSERT INTO MENU (opcion, estado, icono, ubicacion, color, acceso, id_rol, orden) VALUES
('Productos', 'Activo', 'icon_box', 'ProductosController.php', '#ffffff', 'A', 2, 3),
('Ventas', 'Activo', 'icon_cart', 'VentasController.php', '#ffffff', 'A', 2, 4),
('Clientes', 'Activo', 'icon_users', 'ClientesController.php', '#ffffff', 'A', 2, 5);

-- Opciones para clientes (id_rol = 3)
INSERT INTO MENU (opcion, estado, icono, ubicacion, color, acceso, id_rol, orden) VALUES
('Compras', 'Activo', 'icon_shopping_bag', 'ComprasController.php', '#ffffff', 'A', 3, 3),
('Historial', 'Activo', 'icon_history', 'HistorialController.php', '#ffffff', 'A', 3, 4);








--------------------------TABLA PARA CATEGORIZAR PRODUCTOS 

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

--------------------------TABLA DE PROVEEDORES
CREATE TABLE PROVEEDORES (
  id_proveedor INT IDENTITY(1,1) PRIMARY KEY,
  nombre_proveedor VARCHAR(100) NOT NULL,
  contacto VARCHAR(100),
  telefono VARCHAR(20),
  direccion VARCHAR(255),
  estado VARCHAR(20) DEFAULT 'ACTIVO',
  fecha_registro DATETIME DEFAULT GETDATE()
);

INSERT INTO PROVEEDORES (nombre_proveedor) VALUES
('POLLOS IMBA SRL'),
('ARIEL SA'),
('Arroz Okinawa');


SELECT*FROM USUARIOS
SELECT*FROM SOLICITUDES_VENDEDOR

UPDATE USUARIOS SET password = 6441 WHERE id_usuario = 3

SELECT*FROM CATEGORIAS
SELECT * FROM CATEGORIAS WHERE estado = 'ACTIVO'

--------------------------SOLICITUDESVENDEDOR
CREATE TABLE SOLICITUDES_VENDEDOR (
  id_solicitud INT IDENTITY(1,1) PRIMARY KEY,
  id_usuario INT NOT NULL,
  descripcion TEXT,
  estado VARCHAR(20) NOT NULL DEFAULT 'PENDIENTE' CHECK (estado IN ('PENDIENTE', 'APROBADA', 'RECHAZADA')),
  fecha_solicitud DATETIME DEFAULT GETDATE(),
  fecha_revision DATETIME NULL,
  id_revisor INT NULL,
  comentarios TEXT NULL,
  FOREIGN KEY (id_usuario) REFERENCES USUARIOS(id_usuario),
  FOREIGN KEY (id_revisor) REFERENCES USUARIOS(id_usuario)
);
--------------------------TABLA DE PRODUCTOS
CREATE TABLE PRODUCTOS (
  id_producto INT IDENTITY(1,1) PRIMARY KEY,
  id_usuario INT NOT NULL, -- NULL para productos del sistema
  id_categoria INT NOT NULL,
  codigo VARCHAR(50),
  nombre_producto VARCHAR(100) NOT NULL,
  descripcion VARCHAR(255),
  cantidad INT NOT NULL DEFAULT 0,
  precio_venta DECIMAL(10,2) NOT NULL,
  precio_compra DECIMAL(10,2) NOT NULL,
  imagen VARCHAR(255) DEFAULT 'fotoproducto/default.jpg',
  fecha_registro DATETIME DEFAULT GETDATE(),
  estado VARCHAR(20) DEFAULT 'ACTIVO' CHECK (estado IN ('ACTIVO', 'INACTIVO', 'AGOTADO')),
  FOREIGN KEY (id_vendedor) REFERENCES usuarios(id_usuario),
  FOREIGN KEY (id_categoria) REFERENCES CATEGORIAS(id_categoria),
  FOREIGN KEY (id_proveedor) REFERENCES usuarios(id_usuario)
);
SELECT*FROM USUARIOS

SELECT*FROM PRODUCTOS
-- Insertar tus datos de productos (adaptado al nuevo esquema)
INSERT INTO PRODUCTOS (
  id_vendedor, id_categoria, id_proveedor, codigo, nombre_producto, descripcion, 
  cantidad, precio_venta, precio_compra, imagen, fecha_registro, estado
) VALUES
-- Productos de Pollo (Proveedor: POLLOS IMBA SRL)
(NULL, 1, 1, NULL, 'Un Cuarto', 'Un Cuarto', 100, 23.00, 6.00, 'fotoproducto/imagen_1349563845.jpg', '2020-06-12', 'ACTIVO'),
(NULL, 1, 1, NULL, 'DUOPOLLO', 'DUOPOLLO', 15, 16.00, 6.00, 'fotoproducto/imagen_1349563882.jpg', '2018-04-28', 'ACTIVO'),
(NULL, 1, 1, NULL, 'LIMITO', 'LIMITO', 12, 13.00, 6.00, 'fotoproducto/imagen_1349563944.jpg', '2018-04-28', 'ACTIVO'),
(NULL, 1, 1, NULL, 'ECONOMICO', 'ECONOMICO', 8, 8.00, 0.00, 'fotoproducto/imagen_1349563944.jpg', '2018-05-06', 'ACTIVO'),
(NULL, 1, 1, NULL, 'SUPER COMBO', 'SUPER COMBO', 100, 10.00, 6.00, 'fotoproducto/imagen_1349564147.jpg', '2018-05-06', 'ACTIVO'),
(NULL, 1, 1, NULL, 'ALMUERZO COMPLETO', 'ALMUERZO COMPLETO', 11, 10.00, 6.00, 'fotoproducto/imagen_1349564147.jpg', '2018-04-28', 'ACTIVO'),
(NULL, 1, 1, NULL, 'SEGUNDO', 'SEGUNDO', 7, 8.00, 6.00, 'fotoproducto/imagen_1349564229.jpg', '2018-04-28', 'ACTIVO'),
(NULL, 1, 1, NULL, 'HAMBURGUESA', 'HAMBURGUESA', 15, 8.00, 6.00, 'fotoproducto/imagen_1349564269.jpg', '2018-04-28', 'ACTIVO'),
(NULL, 1, 1, NULL, 'Pollo Entero', 'Pollo Entero', 12, 60.00, 6.00, 'fotoproducto/imagen_1349563882.jpg', '2018-04-28', 'ACTIVO'),
(NULL, 1, 1, NULL, 'MEDIO POLLO', 'MEDIO POLLO', 25, 30.00, 6.00, 'fotoproducto/imagen_1349563882.jpg', '2018-04-28', 'ACTIVO'),

-- Refrescos (Proveedor: ARIEL SA)
(NULL, 2, 2, NULL, 'SIMBA MANZANA', 'SIMBA MANZANA', 5, 10.00, 6.00, 'fotoproducto/imagen_1349564463.jpg', '2018-04-28', 'ACTIVO'),
(NULL, 2, 2, NULL, 'Popular Sprite', 'Popular Sprite', 100, 5.00, 6.00, 'fotoproducto/imagen_1349564498.jpg', '2016-08-22', 'ACTIVO'),
(NULL, 2, 2, NULL, 'Popular Coca Cola', 'Popular Coca Cola', 11, 5.00, 6.00, 'fotoproducto/imagen_1349564524.jpg', '2016-08-22', 'ACTIVO'),
(NULL, 2, 2, NULL, 'DEL VALLE', 'DEL VALLE', 100, 10.00, 6.00, 'fotoproducto/imagen_1349564587.jpg', '2018-04-28', 'ACTIVO'),
(NULL, 2, 2, NULL, 'COCA COLA 1 ltr', 'COCA COLA 1 ltr', 11, 8.00, 6.00, 'fotoproducto/imagen_1349564926.jpg', '2018-04-28', 'ACTIVO'),

-- Productos de Arroz (Proveedor: Arroz Okinawa)
(NULL, 3, 3, NULL, 'PORCION DE ARROZ', 'PORCION DE ARROZ', 10, 7.00, 6.00, 'fotoproducto/imagen_134951011.jpg', '2018-04-28', 'ACTIVO'),
(NULL, 3, 3, NULL, 'PORCION DE PAPA', 'PORCION DE PAPA', 100, 7.00, 6.00, 'fotoproducto/imagen_134951110.jpg', '2018-04-28', 'ACTIVO'),

-- Productos varios
(NULL, 4, NULL, '101', 'Coca Cola 500 gr', 'Coca Cola 500 gr', 0, 7.00, 6.00, 'fotoproducto/cocacola.jpg', '2020-06-12', 'ACTIVO'),
(NULL, 4, NULL, '001', 'Pepsi de 500 ml', 'Pepsi de 500 ml', 0, 12.00, 11.00, 'fotoproducto/pepsi.jpg', '2020-07-06', 'ACTIVO');




-----------TABLA DE SOLICITUD DE VENDEDORES
CREATE TABLE VENDEDORES (
  id_vendedor INT IDENTITY(1,1) PRIMARY KEY,
  id_usuario INT NOT NULL UNIQUE,
  descripcion TEXT NULL,
  estado VARCHAR(20) NOT NULL DEFAULT 'PENDIENTE' 
    CHECK (estado IN ('PENDIENTE', 'APROBADO', 'RECHAZADO', 'INACTIVO', 'SUSPENDIDO')),
  fecha_solicitud DATETIME DEFAULT GETDATE(),
  fecha_revision DATETIME NULL,
  id_revisor INT NULL,
  comentarios TEXT NULL,
  comision DECIMAL(5,2) DEFAULT 0.00,
  rating DECIMAL(3,2) DEFAULT 5.00,
  FOREIGN KEY (id_usuario) REFERENCES USUARIOS(id_usuario),
  FOREIGN KEY (id_revisor) REFERENCES USUARIOS(id_usuario)
);


-- Solicitud pendiente
INSERT INTO VENDEDORES (id_usuario, descripcion, estado) 
VALUES (5, 'Quiero vender mis productos de repostería', 'PENDIENTE');

-- Vendedor aprobado
INSERT INTO VENDEDORES (id_usuario, descripcion, estado, fecha_revision, id_revisor, comision, comentarios) 
VALUES (6, 'Venta de artículos electrónicos', 'APROBADO', GETDATE(), 1, 10.00, 'Documentación completa');

-- Solicitud rechazada
INSERT INTO VENDEDORES (id_usuario, descripcion, estado, fecha_revision, id_revisor, comentarios) 
VALUES (7, 'Venta de ropa usada', 'RECHAZADO', GETDATE(), 1, 'No cumple políticas de calidad');