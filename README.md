# Documentation API

# AuthController

    1.	register: Registra un nuevo usuario, validando que el correo sea de Gmail o Hotmail y que la contraseña cumpla con ciertos criterios de seguridad.
    2.	login: Autentica al usuario con su correo y contraseña, y genera un token JWT para las sesiones.

# PqrController

    1.	store: Crea una nueva PQR, asociándola con el usuario autenticado.
    2.	index: Lista todas las PQR con opción de filtrar por fecha o tipo, accesible solo por administradores.
    3.	show: Muestra los detalles de una PQR específica, accesible solo para administradores o el creador.
    4.	update: Actualiza una PQR existente, verificando si el usuario tiene permisos para modificarla.
    5.	destroy: Elimina una PQR específica, verificando los permisos del usuario.

# UserController

    1.	index: Lista todos los usuarios, solo accesible para administradores.
    2.	update: Actualiza los datos de un usuario existente.
    3.	destroy: Elimina un usuario del sistema.
    4.	changePassword: Permite a los usuarios cambiar su contraseña, verificando la contraseña actual antes de hacer el cambio.



# CREAR LA BASE DE DATOS

En el archivo .env se encuentra el nombre de la base de datos

Ejecutar el comando php artisan migrate para ejecutar las migraciones ya creadas a la base de datos, esto se hace para no crear una tabla en la base de datos manualmente 


CREATE TABLE `pqr` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT(20) UNSIGNED NOT NULL,
  `fecha_incidencia` DATE NOT NULL,
  `identificacion` VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `primer_nombre` VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `primer_apellido` VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `correo` VARCHAR(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `medio_notificacion` VARCHAR(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipo` ENUM('Petición', 'Queja', 'Reclamo') COLLATE utf8mb4_unicode_ci NOT NULL,
  `causas` TEXT COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `observacion` TEXT COLLATE utf8mb4_unicode_ci NOT NULL,
  `evidencias` TEXT COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;




# Ejemplos de como se usa en postman

Se debe usar el token para poder acceder a las otras rutas de la Api, en postman se debe ir a Autentication y luego seleccionar Bearer token

# http://127.0.0.1:8000/api/register
Body

{
"name": "mateo",
"email": "testing@gmail.com",
"password": "Prueba1!",
"password_confirmation": "Prueba1!",
"role": "user" // Aquí asignamos el rol de administrador
}

Respuesta

{
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYXBpL3JlZ2lzdGVyIiwiaWF0IjoxNzI4MDA3NDEyLCJleHAiOjE3MjgwMTEwMTIsIm5iZiI6MTcyODAwNzQxMiwianRpIjoiZzdsc3NDaVRRaWVBd0VVWiIsInN1YiI6IjE2IiwicHJ2IjoiMjNiZDVjODk0OWY2MDBhZGIzOWU3MDFjNDAwODcyZGI3YTU5NzZmNyJ9.0q_sjE0WZv3W6QUg-ZoE8Iml0k4DfG5Qfo82-ci3L4c"
}


# http://localhost:8000/api/login
body 

{
    "email": "testing@gmail.com",
    "password": "Prueba1!"
}

Respuesta

{
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjgwMDAvYXBpL2xvZ2luIiwiaWF0IjoxNzI4MDA3NTQyLCJleHAiOjE3MjgwMTExNDIsIm5iZiI6MTcyODAwNzU0MiwianRpIjoiVDJJd1VwRldsYWdjakxLciIsInN1YiI6IjE2IiwicHJ2IjoiMjNiZDVjODk0OWY2MDBhZGIzOWU3MDFjNDAwODcyZGI3YTU5NzZmNyJ9.p-vMuaHW4rLBDN_qvGs4Dp2zzrziY2Ct8nMVV5HvH_E",
    "user": {
        "name": "mateo",
        "email": "testing@gmail.com",
        "role": "user"
    }
}

# http://localhost:8000/api/pqr
Body

{
    "fecha_incidencia": "2024-10-04",
    "identificacion": "987654321",
    "primer_nombre": "Ana",
    "primer_apellido": "Gómez",
    "correo": "ana.gomez@example.com",
    "medio_notificacion": "Teléfono",
    "tipo": "Queja",
    "causas": "Producto defectuoso",
    "observacion": "El producto llegó dañado y no funciona.",
    "evidencias": "Imagen adjunta del producto"
}

Respuesta

{
    "message": "PQR registrada con éxito.",
    "codigo": 5,
    "pqr": {
        "user_id": 12,
        "fecha_incidencia": "2024-10-04",
        "identificacion": "987654321",
        "primer_nombre": "Ana",
        "primer_apellido": "Gómez",
        "correo": "ana.gomez@example.com",
        "medio_notificacion": "Teléfono",
        "tipo": "Queja",
        "causas": "Producto defectuoso",
        "observacion": "El producto llegó dañado y no funciona.",
        "evidencias": "Imagen adjunta del producto",
        "updated_at": "2024-10-03T06:26:11.000000Z",
        "created_at": "2024-10-03T06:26:11.000000Z",
        "id": 5
    }
}

# http://127.0.0.1:8000/api/users

Agregar el token ser un usuario admin para ver el listado


# http://localhost:8000/api/pqrs

Agregar token

# http://127.0.0.1:8000/api/users/9

Agregar token de usuario admin

# http://localhost:8000/api/pqr/5

Agregar token

# http://localhost:8000/api/change-password

Usar token de un usuario ya creado 

Body

{
    "current_password": "mateo1234",
    "new_password": "Mateo12345!",
    "new_password_confirmation": "Mateo12345!" // Debe coincidir con 'new_password'
}

Respuesta

{
    "message": "Contraseña actualizada con éxito."
}

#  http://localhost:8000/api/pqrs?fecha_incidencia=2024-10-02

Agregar token y modificar los filtros con las pqr ya creadas