# AppInteracto

Sistema de gestión con control de roles y log de auditoría.

## Configuración

1.  Copia `.env.example` a `.env` y configura tus credenciales de MySQL.
2.  Importa el archivo `db_schema.sql` en tu base de datos.
3.  Asegúrate de que Apache/PHP tenga permisos para leer el directorio.

## Credenciales por defecto
- **Usuario:** `admin@interacto.com`
- **Password:** `root1234`
- **Rol:** `OWNER`

## Estructura de Endpoints
Todas las peticiones deben ser AJAX POST y devuelven:
```json
{
  "status": "success|error",
  "message": "...",
  "data": []
}
```

## Pruebas
Puedes acceder a los archivos `_test.php` dentro de `includes/vistas/` para probar los endpoints de cada sección de forma aislada.
