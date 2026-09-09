# Estado del proyecto y relevo

Fecha del relevamiento: 7 de septiembre de 2026.

## Resumen ejecutivo

El núcleo de autenticación y gestión de proyectos está implementado y cubierto por pruebas de Feature. La importación tiene lógica sustancial para resolver catálogos, sectores y financiamiento, pero la interfaz y sus pruebas no están alineadas con la ruta backend vigente. Además, el flujo carece de autorización granular, idempotencia y trazabilidad de estado.

## Qué funciona según código y pruebas

- Registro con rol de usuario estándar.
- Login, logout, perfil y cambio de contraseña.
- Restricción del directorio de usuarios a administradores.
- Listado, búsqueda y paginación de proyectos.
- Detalle con relaciones y catálogos.
- Edición validada y eliminación de proyectos.
- Parsing y matching probado de sectores y fuentes de financiamiento.

Resultado de `php artisan test --compact` durante el relevo:

```text
27 tests: 24 passed, 3 failed, 137 assertions
```

Las tres fallas provienen de `tests/Feature/FileImportTest.php`, que intenta generar `projects.create` y `projects.store`; las rutas actuales se llaman `file.create` y `file.store`.

El build frontend de producción terminó correctamente con Vite 8.0.13 usando el runtime Node disponible en el entorno. No se pudo consultar la base MySQL local porque el servicio rechazó la conexión; esta limitación no afecta el resultado de Pest, que usa SQLite en memoria.

## Prioridades recomendadas

### P0 — restaurar y verificar la importación

1. Cambiar el POST de `resources/js/Pages/Projects/addProjectsFile.vue` de `/projects/add` a la ruta backend vigente o renombrar de forma coordinada las rutas.
2. Actualizar `FileImportTest` para usar `file.create`/`file.store`.
3. Ejecutar la suite completa y el build frontend.
4. Realizar una importación manual con un CSV representativo en una base desechable.

### P1 — seguridad y protección de datos

1. Definir Policies/Gates para ver, editar, borrar e importar proyectos.
2. Sustituir comparaciones de IDs mágicos de roles por una política o enum estable.
3. Eliminar credenciales fijas del seeder de entornos compartidos.
4. Revisar los `cascade` de catálogos: hoy pueden producir borrados masivos.
5. Evaluar minimización y retención de CUIL, correo, teléfono e IP.

### P1 — importación robusta

1. Procesar mediante un Job en cola, por lotes, fuera de la petición web.
2. Actualizar `imports.status`, `processed_rows`, `total_rows` y `error_message`.
3. Conservar o mover el archivo sólo después del resultado y definir retención.
4. Añadir una clave de idempotencia —por ejemplo SID o huella de archivo— y usar upserts.
5. Agregar restricciones únicas en pivotes.
6. Validar encabezados obligatorios y reportar fila/columna en cada error.

### P2 — mantenibilidad

1. Separar la migración monolítica en cambios futuros sin editar migraciones ya desplegadas.
2. Corregir el orden de `down()` mediante una nueva estrategia segura de rollback para entornos nuevos.
3. Tipar métodos heredados de servicios, estrategias y repositorios.
4. Inyectar estrategias en vez de resolverlas con `app()` dentro del servicio.
5. Eliminar IDs de catálogo codificados —como `parent_need_id = 3`—.
6. Normalizar el casing de páginas Inertia con una migración de nombre compatible con Linux.
7. Reemplazar URLs literales del frontend por una solución consistente de rutas.

## Matriz de acceso actual

| Función | Invitado | Usuario autenticado | Admin |
|---|---:|---:|---:|
| Registro / login | Sí | Redirección por `guest` | Redirección por `guest` |
| Inicio / perfil / ajustes | No | Sí | Sí |
| Listar y ver proyectos | No | Sí | Sí |
| Editar y eliminar proyectos | No | Sí | Sí |
| Importar proyectos | No | Sí | Sí |
| Listar usuarios | No | No | Sí |
| Ver perfil de otro usuario | No | No | Sí |

La matriz describe el código actual, no una recomendación de permisos.

## Riesgos conocidos

| Riesgo | Impacto | Evidencia en código |
|---|---|---|
| Ruta de importación desalineada | La carga desde UI devuelve 404 y sus pruebas fallan. | `routes/web.php`, `addProjectsFile.vue`, `FileImportTest.php` |
| Importación no idempotente | Repetir un CSV duplica datos. | `ProjectPersistenceService`, `ProjectRepository` |
| Auditoría incompleta | Los intentos permanecen `pending` aunque terminen o fallen. | `FileImportRepository`, tabla `imports` |
| Archivo eliminado antes de persistir | Un fallo de DB deja poco material para reprocesar. | `ProjectImportParser::parseFile` |
| Proceso síncrono y en memoria | Riesgo de timeout/memoria con archivos grandes. | `ProjectImportService`, `ProjectImportParser` |
| Autorización amplia | Todo autenticado puede modificar, borrar e importar. | `routes/web.php`; no hay Policies |
| Credencial inicial fija | Riesgo si se ejecuta el seeder sin adaptar. | `DatabaseSeeder.php` |
| Cascadas desde catálogos | Un borrado de catálogo puede eliminar proyectos. | migración `2026_03_09_184723_tables.php` |
| Rollback frágil | `down()` intenta eliminar padres antes que dependencias. | misma migración |
| Checkout sin metadatos Git utilizables | No se pudo obtener estado, rama, remoto ni historial. | `git status` informó que no era repositorio |

## Estado del repositorio y entrega

Durante el relevamiento existía una carpeta `.git`, pero Git no la reconoció como repositorio y su contenido no estaba disponible. Antes de transferir responsabilidad:

- verificar que el checkout incluya metadatos Git válidos;
- confirmar remoto, rama principal y estrategia de releases;
- revisar que la rama del workflow (`master`) coincida con la rama real;
- registrar dónde se alojan producción, staging, DNS, certificados y backups;
- transferir accesos fuera del repositorio mediante un gestor seguro;
- indicar quién aprueba despliegues y quién responde incidentes.

Esos datos operativos no pueden inferirse del código y deben completarse por la persona propietaria del sistema.

## Checklist para la nueva persona

- [ ] Obtener acceso al repositorio, ambientes, base, hosting y backups.
- [ ] Confirmar rama principal y política de cambios.
- [ ] Crear entorno local siguiendo el README.
- [ ] Revisar y cambiar la credencial inicial antes de sembrar datos.
- [ ] Ejecutar migraciones y seeders en una base nueva.
- [ ] Reproducir el estado de pruebas documentado.
- [ ] Corregir la desalineación de rutas de importación.
- [ ] Ejecutar `php artisan test --compact` y `npm run build`.
- [ ] Validar importación con un CSV real anonimizado.
- [ ] Acordar la matriz de permisos deseada.
- [ ] Definir backup, restauración, monitoreo y despliegue.
- [ ] Convertir las prioridades P0/P1 en issues con responsables.

## Fuentes autoritativas

Cuando este documento y el código difieran, prevalecen:

- dependencias: `composer.lock` y `package-lock.json`;
- rutas: `routes/web.php`;
- esquema: `database/migrations`;
- catálogos: `database/seeders`;
- contrato CSV: `ProjectImportParser`, matchers y pruebas de importación;
- permisos: middleware, controladores y futuras Policies;
- comportamiento: pruebas automatizadas que pasen en CI.

Actualice esta documentación en la misma entrega que cambie cualquiera de esos contratos.
