	-- Crear base de datos si no existe
	CREATE DATABASE IF NOT EXISTS db_ventas;

	-- Usar la base de datos
	USE db_ventas;

	-- Crear tabla de usuarios
	CREATE TABLE IF NOT EXISTS usuarios (
		id INT AUTO_INCREMENT PRIMARY KEY,
		nombre_completo VARCHAR(100) NOT NULL,
		usuario VARCHAR(50) NOT NULL UNIQUE,
		contraseña VARCHAR(255) NOT NULL,
		genero ENUM('masculino', 'femenino') NOT NULL,
		direcion VARCHAR(100) NOT NULL
	);

	-- Crear tabla de proveedores
	CREATE TABLE IF NOT EXISTS proveedores (
		id INT AUTO_INCREMENT PRIMARY KEY,
		nombre_empresa VARCHAR(100) NOT NULL UNIQUE,
		nombre_proveedor VARCHAR(100),
		celular VARCHAR(20) NOT NULL,
		rut VARCHAR(20) NOT NULL UNIQUE
	);

	-- Crear tabla de productos
	CREATE TABLE IF NOT EXISTS productos (
		id INT AUTO_INCREMENT PRIMARY KEY,
		nombre VARCHAR(255) NOT NULL,
		descripcion TEXT,
		precio DECIMAL(10, 2) NOT NULL,
		cantidad_total INT NOT NULL,
		cantidad_disponible INT NOT NULL,
		proveedor_id INT,
		nombre_empresa VARCHAR(100) NOT NULL,
		FOREIGN KEY (proveedor_id) REFERENCES proveedores(id)
	);

	-- Crear tabla de clientes
	CREATE TABLE IF NOT EXISTS clientes (
		dni VARCHAR(20) PRIMARY KEY,
		nombre_completo VARCHAR(100) NOT NULL,
		correo VARCHAR(255) NOT NULL,
		telefono VARCHAR(20) NOT NULL
	);

	-- Crear tabla de ventas
	CREATE TABLE IF NOT EXISTS ventas (
		id INT AUTO_INCREMENT PRIMARY KEY,
		usuario_id INT NOT NULL,
		dni_cliente VARCHAR(20) NOT NULL,
		producto_id INT NOT NULL,
		rut_empresa VARCHAR(20),
		fecha_venta DATE NOT NULL DEFAULT CURRENT_DATE,
		precio_compra DECIMAL(10, 2) NOT NULL,
        total_venta DECIMAL(10, 2) NOT NULL,
		FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
		FOREIGN KEY (producto_id) REFERENCES productos(id)
	);	
    

CREATE TABLE IF NOT EXISTS historial_ventas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    venta_id INT NOT NULL,
    cantidad INT NOT NULL,
    FOREIGN KEY (venta_id) REFERENCES ventas(id)
);

select * from ventas











    
    
   
    

    
    