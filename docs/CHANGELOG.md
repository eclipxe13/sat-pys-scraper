# phpcfdi/sat-pys-scraper CHANGELOG

## Acerca de SemVer

Usamos [Versionado Semántico 2.0.0](SEMVER.md) por lo que puedes usar esta librería sin temor a romper tu aplicación.

## Cambios no liberados en una versión

Pueden aparecer cambios no liberados que se integran a la rama principal, pero no ameritan una nueva liberación de
versión, aunque sí su incorporación en la rama principal de trabajo. Generalmente, se tratan de cambios en el desarrollo.

## Listado de cambios

### Versión 3.0.1 2024-09-17

Se modifica el script de ejecución y la prueba funcional para poder reintentar en caso de que el 
servidor del SAT devuelva un estado HTTP 500. Esto sucede frecuentemente desde hace un par de meses.

Se cambia la construcción de imagen de docker, ahora depende de `php:8.3-cli-alpine`.

Se actualiza el archivo de licencia a 2024.

Se hacen otros cambios en el entorno de desarrollo:

- Se prueba el correcto orden para llamar a los métodos para obtener datos.
- Se utiliza la variable `php-version` en singular para las matrices de pruebas.
- Se actualizan las herramientas de desarrollo.

### Versión 3.0.0 2024-03-07

- Se cambia el método `SatPysScraper::run()` para una mejor inyección de dependencias y capacidad de pruebas.
- Se introduce una excepción dedicada para los errores de procesamiento de argumentos.
- Se cambia la forma de procesar los argumentos para usar `array_shift`.

### Versión 2.0.0 2024-03-07

- Se corrige el nodo principal, el nombre correcto es `<pys>`.
- Se cambia el comando de ejecución `bin/sat-pys-scraper` para exportar a JSON y XML al mismo tiempo.

Otros cambios:

- Se utilizan las acciones de GitHub versión 4. 
- Se actualizan las herramientas de desarrollo.

### Versión 1.0.0 2023-12-13

- Versión inicial.
