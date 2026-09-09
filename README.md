# Project Manager

Aplicación interna para importar, consultar y mantener proyectos de innovación a partir de archivos CSV. Incluye autenticación, perfiles de usuario, un directorio de usuarios para administradores y gestión de proyectos.

> Estado del relevo: la documentación fue relevada contra el código el 7 de septiembre de 2026. Antes de continuar el desarrollo, consulte [Estado del proyecto y relevo](docs/HANDOVER.md).

## Stack

- PHP `^8.3` (entorno relevado: PHP 8.5.1)
- Laravel 13
- MySQL para desarrollo/producción y SQLite en memoria para pruebas
- Inertia.js 3 + Vue 3
- Tailwind CSS 4, Vite 8 y componentes basados en Reka UI/shadcn-vue
- Pest 4

Las versiones exactas están bloqueadas en `composer.lock` y `package-lock.json`.

## Puesta en marcha local

Requisitos:

- PHP 8.3 o superior con las extensiones requeridas por Laravel y MySQL
- Composer 2
- Node.js y npm compatibles con Vite 8
- MySQL

1. Cree una base MySQL vacía.
2. Copie `.env.example` a `.env` y ajuste, como mínimo, `APP_NAME`, `APP_URL`, `APP_LOCALE`, `APP_FALLBACK_LOCALE`, `APP_FAKER_LOCALE`, `APP_TIMEZONE` y las variables `DB_*`.
3. Ejecute el instalador del proyecto:

   ```bash
   composer run setup
   ```

4. Cargue los catálogos requeridos por la importación:

   ```bash
   php artisan db:seed
   ```

5. Inicie el entorno de desarrollo:

   ```bash
   composer run dev
   ```

`composer run setup` instala dependencias PHP y JavaScript, crea la clave de aplicación, ejecuta migraciones y compila los assets. No ejecuta los seeders. Los seeders actuales no son idempotentes: ejecútelos una sola vez sobre una base nueva.

### Usuario inicial

`DatabaseSeeder` crea un administrador con credenciales fijas definidas en el propio archivo. Revise `database/seeders/DatabaseSeeder.php` antes de ejecutar el seeder y cambie inmediatamente esa contraseña. No ejecute ese seeder sin adaptar en un entorno compartido o de producción.

## Comandos habituales

```bash
# Servidor Laravel, listener de cola y Vite
composer run dev

# Suite completa
php artisan test --compact

# Compilar frontend
npm run build

# Formatear PHP modificado
vendor/bin/pint --dirty --format agent

# Ver rutas
php artisan route:list --except-vendor
```

La cola está configurada con base de datos, pero el flujo principal de importación es actualmente síncrono. El listener incluido en `composer run dev` no procesa la importación.

## Funcionalidades actuales

- Registro, inicio y cierre de sesión.
- Consulta y actualización del perfil y la contraseña.
- Directorio de usuarios restringido al rol administrador.
- Listado paginado de proyectos, con búsqueda por título, solicitante, TRL o provincia.
- Detalle, edición y eliminación de proyectos.
- Importación CSV de proyectos, solicitantes y sus clasificaciones.
- Tema claro/oscuro y layout responsivo.

Todas las pantallas funcionales requieren autenticación salvo registro e inicio de sesión.

## Documentación del proyecto

- [Arquitectura y modelo de datos](docs/ARCHITECTURE.md)
- [Contrato y flujo de importación CSV](docs/IMPORTS.md)
- [Operación, despliegue y resolución de problemas](docs/OPERATIONS.md)
- [Estado actual, riesgos y prioridades de relevo](docs/HANDOVER.md)

## Convenciones para continuar

- El backend se organiza por dominio en `app/Domains`: `Users`, `Projects` y `Files`.
- El flujo habitual es ruta → controlador → Form Request → servicio → repositorio → modelo Eloquent.
- Las páginas Inertia viven en `resources/js/Pages`; los componentes reutilizables, en `resources/js/Components`.
- Mantenga la validación en Form Requests y los mensajes visibles en español.
- Reutilice repositorios y componentes existentes antes de introducir otra abstracción.
- Añada o actualice pruebas Pest junto con cada cambio funcional.
- No cambie dependencias ni la estructura raíz sin acordarlo con la persona responsable.

## Estado de verificación

En el último relevamiento:

- Backend: 24 de 27 pruebas pasan; las 3 fallas están concentradas en rutas antiguas del test de importación.
- Frontend: el build de producción con Vite 8.0.13 terminó correctamente usando el runtime Node disponible en el entorno.
- MySQL local: no estaba accesible; la suite sí se ejecutó con SQLite en memoria.

Los detalles y acciones recomendadas están en [docs/HANDOVER.md](docs/HANDOVER.md).
