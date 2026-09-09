# Operación, despliegue y resolución de problemas

## Configuración por entorno

Nunca copie `.env` entre personas ni lo incluya en control de versiones. Parta de `.env.example` y entregue secretos por un canal seguro.

Variables que deben revisarse en cada entorno:

| Grupo | Variables | Observación |
|---|---|---|
| Aplicación | `APP_NAME`, `APP_ENV`, `APP_KEY`, `APP_DEBUG`, `APP_URL` | En producción: `APP_ENV=production`, `APP_DEBUG=false` y URL pública correcta. |
| Idioma | `APP_LOCALE`, `APP_FALLBACK_LOCALE`, `APP_FAKER_LOCALE`, `APP_TIMEZONE` | La interfaz y los datos están orientados a español/Argentina, pero el ejemplo conserva valores genéricos. |
| Base | `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` | MySQL es el driver previsto fuera de tests. |
| Estado | `SESSION_DRIVER`, `CACHE_STORE`, `QUEUE_CONNECTION` | El ejemplo usa tablas de base de datos. |
| Correo | `MAIL_*` | El driver actual de ejemplo escribe al log; no envía correo real. |

Después de modificar `.env` en un entorno con caché de configuración:

```bash
php artisan optimize:clear
php artisan config:cache
```

## Servicios y almacenamiento

- Web: PHP/Laravel detrás del servidor HTTP elegido.
- Base de datos: MySQL.
- Assets: compilados por Vite en `public/build`.
- Archivos importados: disco `local`, bajo `storage/app/private/imports`; se eliminan durante el flujo normal del parser.
- Logs: `storage/logs/laravel.log` con configuración `stack/single` por defecto.
- Sesiones, caché y cola: tablas de MySQL según `.env.example`.
- Health check: `GET /up`.

No hay SSR de Inertia configurado. No debe levantarse un servidor SSR ni compilarse un bundle SSR.

## Desarrollo local

```bash
composer run dev
```

Este script inicia en paralelo:

- `php artisan serve`;
- `php artisan queue:listen --tries=1`;
- `npm run dev`.

Aunque el listener se inicia, la importación actual no despacha jobs. Si el equipo no agrega trabajo en cola, el listener no es necesario para probar las funciones existentes.

## Despliegue sugerido

Adaptar los pasos al proveedor, pero conservar este orden general:

```bash
composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction
npm ci --ignore-scripts
npm run build
php artisan migrate --force
php artisan optimize
php artisan queue:restart
```

Notas:

- Haga backup de la base antes de migrar.
- No ejecute `db:seed` automáticamente en producción mientras los seeders no sean idempotentes y creen credenciales fijas.
- `queue:restart` sólo es necesario si existe un worker administrado por Supervisor, systemd o el proveedor.
- Configure el document root en `public/`, permisos de escritura para `storage/` y `bootstrap/cache/`, HTTPS y rotación de logs.
- No exponga `storage/app/private` desde el servidor web.
- Verifique `/up`, login, listado de proyectos y logs después del despliegue.

## Backup y restauración

El activo principal es MySQL. El CSV temporal no constituye un backup porque el parser lo elimina.

Plan mínimo:

- backup automatizado de MySQL con retención definida;
- prueba periódica de restauración en un entorno aislado;
- copia separada y controlada de los CSV originales antes de importarlos;
- resguardo seguro de variables y credenciales de infraestructura;
- registro de versión/despliegue asociado a cada backup.

Antes de borrar catálogos, revise las claves foráneas con `cascade`: eliminar una institución puede eliminar solicitantes y proyectos; eliminar TRL, área o provincia también puede eliminar proyectos relacionados.

## Pruebas y CI

La suite usa Pest y SQLite en memoria:

```bash
php artisan test --compact
```

GitHub Actions ejecuta la suite en PHP 8.3, 8.4 y 8.5. El workflow actual escucha pushes a `master`, ramas `*.x`, pull requests y una ejecución diaria.

Antes de integrar un cambio:

```bash
vendor/bin/pint --dirty --format agent
php artisan test --compact
npm run build
```

## Diagnóstico rápido

### La aplicación no conecta con MySQL

1. Confirme que MySQL esté iniciado.
2. Revise `DB_HOST`, `DB_PORT`, `DB_DATABASE` y credenciales.
3. Ejecute `php artisan optimize:clear`.
4. Pruebe `php artisan migrate:status`.

### Aparece "table not found"

```bash
php artisan migrate
```

Si faltan catálogos en una base nueva, ejecute `php artisan db:seed` una sola vez y sólo después de revisar el usuario inicial.

### La UI no refleja cambios

Ejecute `npm run dev` durante desarrollo o `npm run build` para regenerar assets. Revise también que `public/hot` no haya quedado de una sesión anterior.

### La importación muestra un error genérico

- Revise las entradas recientes en `storage/logs/laravel.log`.
- Confirme formato, tamaño, dos filas descriptivas y encabezados en la tercera fila.
- Confirme que todos los catálogos hayan sido sembrados.
- Pruebe con una copia del CSV; el archivo temporal puede eliminarse aun cuando la persistencia falle.

### La importación no llega al backend

La ruta backend vigente es `/file/add`, pero la página relevada publica a `/projects/add`. Alinee la URL frontend y los nombres de ruta de la prueba antes de investigar el parser.

### Fallan rollbacks

La migración principal elimina algunas tablas padre antes que sus pivotes/hijas. En una base desechable, prefiera reconstruirla con `php artisan migrate:fresh --seed`; no ejecute ese comando sobre datos que necesite conservar.

## Observabilidad actual

La aplicación no integra métricas, trazas ni alertas. La auditoría de importaciones tampoco refleja el resultado real. Hasta implementar observabilidad, el operador debe combinar logs, estado de la base y comprobaciones funcionales.
