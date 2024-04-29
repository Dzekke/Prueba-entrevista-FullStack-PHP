## API de Customer

Esta API proporciona endpoints para administrar clientes en un sistema.

## Endpoints Disponibles
Listar clientes
bash

## GET /api/customers
Este endpoint permite obtener una lista de clientes. Se pueden proporcionar parámetros opcionales para filtrar los resultados.

## Parámetros
op: (Opcional) Si se establece como 'all', se devolverán todos los clientes sin filtrar.
Respuestas
200 OK: Se devuelve una lista de clientes en formato JSON.
401 Unauthorized: Token de autenticación expirado.
500 Error de servidor: Ocurre un error al procesar la solicitud.
Registrar nuevo cliente
bash


## POST /api/customers
Este endpoint permite registrar un nuevo cliente en el sistema.

## Parámetros
dni: (Requerido) DNI del cliente.
id_reg: (Requerido) ID de la región del cliente.
id_com: (Requerido) ID de la comuna del cliente.
email: (Requerido) Correo electrónico del cliente.
name: (Requerido) Nombre del cliente.
last_name: (Requerido) Apellido del cliente.
address: (Opcional) Dirección del cliente.
token: (Requerido) Token de autenticación.
Respuestas
200 OK: Se devuelve el cliente registrado en formato JSON.
400 Bad Request: Error en los datos del cliente.
404 Not Found: La comuna o región no están relacionadas o no existen.
500 Error de servidor: Ocurre un error al procesar la solicitud.
Eliminar cliente
bash

## DELETE /api/customers/{dni}
Este endpoint permite eliminar un cliente del sistema.

## Parámetros
dni: (Requerido) DNI del cliente a eliminar.
Respuestas
200 OK: El cliente se elimina correctamente.
400 Bad Request: Fallo al eliminar el cliente.
401 Unauthorized: Token de autenticación expirado.
404 Not Found: No se encuentra el cliente a eliminar.

## Nota Importante
Para acceder a los endpoints de la API GET de Customer, asegúrate de incluir un token de autenticación válido en las solicitudes. Esta API está protegida por un middleware llamado EnsureTokenIsValid, que verifica la validez del token antes de permitir el acceso el token es: ttywrmgkRoTHROmCsq7nO9T40cWKnYYkuoRBmMSoPnA0a .

## Requisitos Previos
PHP >= 7.0
Laravel Framework
Base de datos MySQL
Instalación
Clona este repositorio.
Configura el archivo .env con la información de tu base de datos.
Ejecuta composer install para instalar las dependencias de Laravel.
Ejecuta php artisan migrate para crear las tablas necesarias en la base de datos.
Ejecuta php artisan serve para iniciar el servidor de desarrollo.
Contribución
¡Las contribuciones son bienvenidas! Si deseas contribuir a este proyecto, realiza una bifurcación y envía una solicitud de extracción.


## API de Login
Este controlador maneja la autenticación de usuarios en el sistema.

Endpoints Disponibles
Iniciar sesión
bash

## POST /api/login
Este endpoint permite a los usuarios iniciar sesión en el sistema proporcionando sus credenciales.

## Parámetros
email: (Requerido) Correo electrónico del usuario.
password: (Requerido) Contraseña del usuario.
Respuestas
200 OK: El usuario inicia sesión correctamente y se genera un token de autenticación.
400 Bad Request: Error en la validación de los datos.
401 Unauthorized: Credenciales incorrectas o error en la autenticación.
500 Error de servidor: Ocurre un error al procesar la solicitud.
