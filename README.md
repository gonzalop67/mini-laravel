# Mini Laravel

Una versión simplificada de Laravel implementada en PHP puro.

## Instalación

1. Clona o descarga el proyecto en `c:\xampp\htdocs\mini-laravel`.
2. Crea una base de datos MySQL llamada `mvc`.
3. Crea la tabla `usuarios`:

```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL
);
```

4. Asegúrate de que XAMPP esté corriendo Apache y MySQL.
5. Accede a `http://localhost/mini-laravel/public/` en tu navegador.

## Estructura

- `public/index.php`: Punto de entrada.
- `core/`: Núcleo del framework (Route, MiniBlade, ...).
- `app/Controllers/`: Controladores.
- `app/Models/`: Modelos.
- `routes/web.php`: Definición de rutas.
- `resources/views/`: Vistas.

## Funcionalidades

- Routing básico.
- Conexión a DB con mysqli.
- Modelo base con CRUD simple.
- Controladores con vistas.
- Vistas básicas con PHP.
- Middlewares básicos.
- Validación básica de datos.

## Próximos pasos

- Agregar otros middleware.
- Autoloading con Composer.
- Más métodos en modelos.
- Configuración en el archivo .env