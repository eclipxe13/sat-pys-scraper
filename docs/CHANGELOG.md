# phpcfdi/sat-pys-scraper CHANGELOG

## Acerca de SemVer

Usamos [Versionado Semántico 2.0.0](SEMVER.md) por lo que puedes usar esta librería sin temor a romper tu aplicación.

## Cambios no liberados en una versión

Pueden aparecer cambios no liberados que se integran a la rama principal, pero no ameritan una nueva liberación de
versión, aunque sí su incorporación en la rama principal de trabajo. Generalmente, se tratan de cambios en el desarrollo.

## Listado de cambios

### Versión 3.0.2 2024-10-17

A la herramienta `bin/sat-pys-scraper` se le puede definir un número máximo de ejecuciones en la 
variable de entorno `MAX_TRIES`, de forma predeterminada usa el valor `1`. 
Con este cambio se intenta resolver el problema de error `500 Internal Server Error` de la 
aplicación de Productos y Servicios del SAT.

En el flujo de trabajo `system.yml` en el trabajo `system-tests` se configura `MAX_TRIES` a `5`.

### Versión 3.0.1 2024-10-15

La aplicación del SAT devuelve un error 500 frecuentemente (1 de cada 3 veces) desde 2024-07-15.
Este error parece estar relacionado con la distribución de cargas por parte del SAT, así que
reintentar la llamada HTTP sobre la misma conexión no soluciona el problema y hay que crear
un nuevo cliente HTTP. Para intentar solventarlo, se modifica la librería para tirar
excepciones con errores HTTP e intentar solventar el error.

Se cambia la construcción de imagen de docker, ahora depende de `php:8.3-cli-alpine`.

Se actualiza el archivo de licencia a 2024.

Se hacen otros cambios en el entorno de desarrollo:

- Se modifica la prueba funcional para poder hacer hasta 5 reintentos reconstruyendo el cliente http.
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
