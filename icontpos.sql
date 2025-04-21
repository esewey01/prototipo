-- Create USUARIOS table
CREATE TABLE USUARIOS (
    id_usuario INT PRIMARY KEY IDENTITY(1,1),
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100),
    login VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    telefono VARCHAR(20),
    direccion VARCHAR(255),
    genero VARCHAR(20),
    fecha_nacimiento DATE,
    foto_perfil VARCHAR(255) DEFAULT 'user.png',
    fecha_registro DATETIME DEFAULT GETDATE(),
    ultimo_acceso DATETIME,
    estado VARCHAR(20) DEFAULT 'ACTIVO'
);

-- Create ROLES table
CREATE TABLE ROLES (
    id_rol INT PRIMARY KEY IDENTITY(1,1),
    nombre_rol VARCHAR(50) NOT NULL UNIQUE,
    descripcion VARCHAR(255),
    estado VARCHAR(20) DEFAULT 'ACTIVO'
);

-- Create ROLES_USUARIO junction table
CREATE TABLE ROLES_USUARIO (
    id_relacion INT PRIMARY KEY IDENTITY(1,1),
    id_usuario INT NOT NULL,
    id_rol INT NOT NULL,
    fecha_asignacion DATETIME DEFAULT GETDATE(),
    FOREIGN KEY (id_usuario) REFERENCES USUARIOS(id_usuario) ON DELETE CASCADE,
    FOREIGN KEY (id_rol) REFERENCES ROLES(id_rol),
    CONSTRAINT UC_UsuarioRol UNIQUE (id_usuario, id_rol)
);

-- Create REDES_SOCIALES table (optional)
CREATE TABLE REDES_SOCIALES (
    id_red_social INT PRIMARY KEY IDENTITY(1,1),
    id_usuario INT NOT NULL,
    tipo_red VARCHAR(50) NOT NULL,
    url_perfil VARCHAR(255),
    FOREIGN KEY (id_usuario) REFERENCES USUARIOS(id_usuario) ON DELETE CASCADE
);

-- Insert default roles
INSERT INTO ROLES (nombre_rol, descripcion) VALUES 
('SUPER_USER', 'Usuario con todos los permisos del sistema'),
('ADMINISTRADOR', 'Administrador del sistema con permisos elevados'),
('VENDEDOR', 'Usuario con permisos de ventas'),
('CLIENTE', 'Usuario cliente con permisos limitados');

-- Create MENU table (for navigation)
CREATE TABLE MENU (
    id_menu INT PRIMARY KEY IDENTITY(1,1),
    opcion VARCHAR(100) NOT NULL,
    estado VARCHAR(20) DEFAULT 'ACTIVO',
    icono VARCHAR(100),
    url VARCHAR(255),
    orden INT,
    id_rol INT,
    FOREIGN KEY (id_rol) REFERENCES ROLES(id_rol)
);

-- Insert sample menu items
INSERT INTO MENU (opcion, estado, icono, url, orden, id_rol) VALUES
('Principal', 'ACTIVO', 'icon_house_alt', 'AccessUsers.php', 1, NULL),
('Configuracion', 'ACTIVO', 'icon_tools', 'Usuario.php', 2, 1),
('Proveedores', 'ACTIVO', 'icon_briefcase', 'Proveedor.php', 3, 1),
('Clientes', 'ACTIVO', 'icon_group', 'Cliente.php', 4, 1),
('Productos', 'ACTIVO', 'icon_bag_alt', 'Producto.php', 5, 1),
('Ventas', 'ACTIVO', 'icon_cart', 'Ventas.php', 6, 1),
('Reportes', 'ACTIVO', 'icon_piechart', 'ReportesVentas.php', 7, 1);

-- Create SOLICITUDES_VENDEDOR table (optional)
CREATE TABLE SOLICITUDES_VENDEDOR (
    id_solicitud INT PRIMARY KEY IDENTITY(1,1),
    id_usuario INT NOT NULL,
    id_categoria INT,
    descripcion TEXT,
    fecha_solicitud DATETIME DEFAULT GETDATE(),
    estado VARCHAR(20) DEFAULT 'PENDIENTE',
    FOREIGN KEY (id_usuario) REFERENCES USUARIOS(id_usuario)
);

-- Create CATEGORIAS table (optional)
CREATE TABLE CATEGORIAS (
    id_categoria INT PRIMARY KEY IDENTITY(1,1),
    nombre VARCHAR(100) NOT NULL,
    descripcion VARCHAR(255),
    estado VARCHAR(20) DEFAULT 'ACTIVO'
);

-- Create a default super user (password: admin123)
INSERT INTO USUARIOS (nombre, login, password, email, estado)
VALUES ('Super Usuario', 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@system.com', 'ACTIVO');

-- Assign super user role
INSERT INTO ROLES_USUARIO (id_usuario, id_rol)
VALUES (1, 1); -- Assuming SUPER_USER has id_rol = 1