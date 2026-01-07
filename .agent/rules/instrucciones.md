---
trigger: always_on
---

Objetivo: Desarrollar una aplicación web con HTML5/CSS3/PHP/jQuery/Bootstrap5 y MySQL que siga una estructura de archivos definida, use AJAX (jQuery) para todas las interacciones con la base de datos, implemente control de accesos por roles y registre un log de eventos en la base de datos. Todas las especificaciones y convenciones siguientes deben cumplirse.

1) Stack tecnológico obligatorio

- Frontend: HTML5, CSS3, Bootstrap 5, jQuery (JavaScript), plugins jQuery si es necesario.
- Librería de alertas y prompts: SweetAlert.
- Backend: PHP (servidor).
- Build / tooling / utilidades: Node.js (solo para tareas de desarrollo si corresponde).
- Base de datos: MySQL.

2) Configuración de conexión a la base de datos

Debe existir un único archivo de conexión ubicado en la raíz del proyecto llamado cn.php.

cn.php debe exponer la conexión a la base de datos en la variable $cnn (toda la aplicación usará global $cnn o incluirá cn.php para acceder a la conexión).

Las credenciales y ambientes se almacenan en un archivo .env en la raíz con variables claras. El .env debe contener, como mínimo, estas variables (nombres sugeridos — puedes adaptarlos, pero deben documentarse):

APP_ENV = local ó production (variable para seleccionar el ambiente activo)

#CONEXION LOCAL
DB_LOCAL_HOST
DB_LOCAL_USER
DB_LOCAL_PASS
DB_LOCAL_NAME

#CONEXION PRODUCCION
DB_PROD_HOST
DB_PROD_USER
DB_PROD_PASS
DB_PROD_NAME

Conexiones actuales (debe incluirse en el .env)

- Local: usuario root, contraseña vacía (null), host localhost, db uniexcel.
- Producción: usuario root, contraseña M@j0309!, host 54.23.12.12, db uniexcel.

cn.php leerá el .env, detectará APP_ENV y creará $cnn con la conexión correspondiente (recomendar usar mysqli o PDO con manejo de errores).

Todas las consultas deben usar sentencias preparadas (prepared statements) para evitar SQL injection.

3) Estructura de archivos requerida (obligatoria)

/assets/css/                # Todos los archivos CSS (separados por responsabilidad)
 /assets/js/                # Todos los archivos JS / jQuery (separados por responsabilidad)
 /assets/images/            # Imágenes del proyecto
 /includes/_estructura/     # Archivos de estructura globales e incluidos en index.php
    _header.php
    _footer.php
    _menu.php
 /includes/vistas/          # Todas las vistas PHP (cada vista: vista.php)
 /includes/endpoints/       # Endpoints (CRUD). Todos deben ser llamadas AJAX (POST)
cn.php                      # Archivo único de conexión (en la raíz)
.env                        # Variables de configuración (en la raíz)
index.php                   # Punto de entrada; incluye _estructura/_header, menu y carga vistas 
en el área de contenido

4) Reglas para vistas y endpoints

Todas las llamadas a la base de datos deben hacerse exclusivamente desde archivos en /includes/endpoints/.

Los endpoints deben aceptar sólo peticiones AJAX POST y deben devolver JSON con una estructura estándar, por ejemplo:

{ "status": "success"|"error", "code": 200, "message": "texto", "data": {...} }


Las vistas (archivos en /includes/vistas/) no deben ejecutar consultas directamente; en su lugar llaman a los endpoints por AJAX para obtener/guardar/actualizar datos.

Los archivos de estructura (_header.php, _menu.php, _footer.php) se cargarán mediante include o require desde index.php.

5) Convención de pruebas / archivos _test.php

Cada vez que se cree o modifique una vista X.php en /includes/vistas/, generar un archivo de pruebas paralelo llamado X_test.php en la misma carpeta.

Qué debe incluir X_test.php:

Botones y formularios que permitan probar manualmente todos los endpoints que afecte la vista (INSERT, SELECT, UPDATE, DELETE).

Muestras de los queries SQL usados: (a) la consulta vacía/plantilla y (b) la misma consulta con datos simulados rellenados.

Un diccionario de variables (nombres de campos, tipos esperados, validaciones) usado por los endpoints.

Visualización de la respuesta JSON cruda y parseada para depuración.

Estas páginas _test.php servirán exclusivamente para pruebas de desarrollo y no deben desplegarse en producción sin control.

6) Roles y control de acceso: El sistema debe implementar control de acceso por roles con, al menos, los siguientes roles:

- OWNER (acceso total)
- ADMINISTRADOR
- COLABORADOR
- MONITOR

Las autorizaciones deben comprobarse en cada endpoint y, si procede, también en vistas (para ocultar/mostrar UI).

Debe existir una capa o función central para verificar permisos (por ejemplo checkPermission($userId, $action)), reutilizable en endpoints.

7) Log de eventos (auditoría)

Toda acción relevante en la aplicación debe quedar registrada en la base de datos para trazabilidad.

Crear una tabla (ejemplo event_log) con al menos estos campos:

id (PK, autoincrement)
user_id (quién ejecutó la acción)
role (rol del usuario en el momento)
action (texto breve: 'crear usuario', 'editar contrato', etc.)
details (JSON o TEXT con información adicional/contexto)
ip (opcional)
created_at (timestamp con fecha y hora)

Todos los endpoints que modifican datos (insert/update/delete) deben insertar una fila en event_log describiendo la acción.

8) Estándares de seguridad y buenas prácticas

Todas las entradas provenientes del cliente deben validarse y sanitizarse en el servidor.

Usar prepared statements (mysqli/PDO) siempre.

Guardar contraseñas con hash seguro (password_hash / password_verify).

Proteger endpoints sensibles con control de sesión y comprobación CSRF (token).

Manejar errores y no exponer trazas de la base de datos en producción.

Todas las respuestas AJAX deben devolver códigos HTTP y mensajes claros para la UI.

9) UX / Integración frontend

Todas las alertas y prompts deben usarse con SweetAlert para consistencia visual.

Las llamadas AJAX se harán con jQuery.ajax o atajos ($.post, $.ajax), siguiendo la convención de JSON mencionada.

Los recursos CSS y JS deben estar separados en /assets/css y /assets/js y referenciados desde _header.php o index.php.

Evitar inline styles y scripts; mantener el código modular.

10) Entregables / Expectativas del proyecto

Código fuente con la estructura de carpetas indicada.

Archivo .env.example con las variables documentadas (sin credenciales reales).

cn.php que cargue .env y cree $cnn acorde a APP_ENV.

Vistas en /includes/vistas/ y endpoints en /includes/endpoints/.

Archivos _test.php por cada vista modificada/creada.

Esquema SQL para crear la base de datos básica y la tabla event_log.

Documentación breve (README) explicando:

Cómo configurar .env

Cómo arrancar el entorno (si hay scripts Node)

Cómo ejecutar las pruebas _test.php

Convenciones de endpoints y formato de respuesta

11) Sugerencias adicionales (opcionales, recomendadas)

Usar composer para dependencias PHP si se requieren librerías (por ejemplo vlucas/phpdotenv para leer .env).

Usar un archivo db_schema.sql con los CREATE TABLE principales, incluyendo users, roles, role_user (o la relación que se prefiera) y event_log.

Mantener consistencia en nombres (snake_case para DB, camelCase para JS, etc.) y comentar el código.