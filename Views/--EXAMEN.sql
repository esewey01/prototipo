--EXAMEN 

CREATE DATABASE PRUEBAS

USE [PRUEBAS]

--PRIMERA PRUEBA
--Para la siguiente necesidad de información, diseñe un diagrama ilustrando las tablas que mejor responden a las preguntas del negocio.
--Skandia, dentro de su portafolio de productos, ofrece varios productos de inversión y los productos de inversión son puestos a disposición del público a través de diversos equipos de venta.
--La compañía requerirá monitorear en el futuro el total de ventas por producto, para identificar entre los mejores y que deben ser proyectados como los productos estrella de la compañía y aquellos con niveles de venta bajos, que son candidatos a desaparecer.
--Además, la compañía también requiere evaluar a sus equipos de venta, para monitorear su productividad.



CREATE TABLE PRODUCTOS (
 ID_PRODUCTO INT PRIMARY KEY IDENTITY(1,1),
    NOMBRE_PRODUCTO VARCHAR(100) NOT NULL,
    CATEGORIA VARCHAR(50) NOT NULL,
    FECHA_LANZAMIENTO DATE NOT NULL,
    ESTADO VARCHAR(20) NOT NULL CHECK (ESTADO IN ('Activo', 'Inactivo')),
    PRECIO DECIMAL(10, 2) NOT NULL,
)


CREATE TABLE EQUIPOS (
    ID_EQUIPO INT PRIMARY KEY IDENTITY(1,1),
    NOMBRE_EQUIPO VARCHAR(100) NOT NULL,
    REGION VARCHAR(50) NOT NULL,
    LIDER_EQUIPO VARCHAR(100) NOT NULL,
    FECHA_CREACION DATE NOT NULL,
)


CREATE TABLE CLIENTES(
    ID_CLIENTE INT PRIMARY KEY IDENTITY(1,1),
    NOMBRE_CLIENTE VARCHAR(100) NOT NULL,
    EMAIL VARCHAR(100) NOT NULL,
    TELEFONO VARCHAR(20) NOT NULL,
    DIRECCION VARCHAR(255) NOT NULL,
    FECHA_REGISTRO DATE NOT NULL
)

CREATE TABLE VENTAS(
    ID_VENTA INT PRIMARY KEY IDENTITY(1,1),
    ID_PRODUCTO INT NOT NULL,
    ID_EQUIPO INT NOT NULL,
    ID_CLIENTE INT NOT NULL,
    FECHA_VENTA DATE NOT NULL,
    CANTIDAD INT NOT NULL CHECK (CANTIDAD > 0),
    COMISION_GENERADA DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (ID_PRODUCTO) REFERENCES PRODUCTOS(ID_PRODUCTO),
    FOREIGN KEY (ID_EQUIPO) REFERENCES EQUIPOS(ID_EQUIPO),
    FOREIGN KEY (ID_CLIENTE) REFERENCES CLIENTES(ID_CLIENTE)
)

CREATE TABLE VENDEDORES(
    ID_VENDEDOR INT PRIMARY KEY IDENTITY(1,1),
    NOMBRE_VENDEDOR VARCHAR(100) NOT NULL,
    ID_EQUIPO INT NOT NULL,
    FECHA_CONTRATACION DATE NOT NULL,
    ACTIVO BIT NOT NULL CHECK (ACTIVO IN (0, 1)),
    FOREIGN KEY (ID_EQUIPO) REFERENCES EQUIPOS(ID_EQUIPO)
)


--Como parte de la estrategia de servicio de Skandia, se necesita identificar los estados con más clientes con el objetivo de abrir nuevas oficinas de atención directa y resolución de dudas a clientes.
--La función de Servicio requiere un listado con dos columnas donde se listen todos los estados y el total de clientes en cada una de ellas. Como la función de marketing se enfocará en los cinco estados con más clientes requiere ver el resultado en orden descendente de acuerdo al número de clientes.
--En la última fila del mismo reporte necesitan el total de clientes.|
--Además de abrir nuevas oficinas de servicio se abrirán oficinas de venta en los estados que aún no tienen clientes, por lo que los estados sin clientes también deben aparecer en el reporte.

CREATE TABLE ESTADOS (
    ID_ESTADO INT PRIMARY KEY IDENTITY(1,1),
    NOMBRE_ESTADO VARCHAR(100) NOT NULL
)

CREATE TABLE CLIENTES (
    ID_CLIENTE INT PRIMARY KEY IDENTITY(1,1),
    NOMBRE_CLIENTE VARCHAR(100) NOT NULL,
    EMAIL VARCHAR(100) NOT NULL,
    TELEFONO VARCHAR(20) NOT NULL,
    DIRECCION VARCHAR(255) NOT NULL,
    FECHA_REGISTRO DATE NOT NULL,
    ID_ESTADO INT NOT NULL,
    FOREIGN KEY (ID_ESTADO) REFERENCES ESTADOS(ID_ESTADO)
)

SELECT *
FROM (
    SELECT
        E.NOMBRE_ESTADO AS ESTADO,
        COUNT(C.ID_CLIENTE) AS TOTAL_CLIENTES
    FROM ESTADOS E
    LEFT JOIN CLIENTES C ON E.ID_ESTADO = C.ID_ESTADO
    GROUP BY E.NOMBRE_ESTADO

    UNION ALL

    SELECT 
        'Total' AS ESTADO,
        COUNT(*) AS TOTAL_CLIENTES
    FROM CLIENTES
) AS ResultadoFinal
ORDER BY 
    CASE 
        WHEN ESTADO = 'Total' THEN 1
        ELSE 0
    END,
    TOTAL_CLIENTES DESC;




UPDATE Cliente
SET segmento_id = (
    SELECT segmento_id
    FROM Segmento_Cliente
    WHERE segmento_cliente_dsc = 'Sur'
)
WHERE segmento_id IN (
    SELECT segmento_id
    FROM Segmento_Cliente
    WHERE segmento_cliente_dsc LIKE 'Sur%' AND segmento_cliente_dsc <> 'Sur'
);

