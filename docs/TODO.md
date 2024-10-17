# phpcfdi/sat-pys-scraper To Do List

## Lista de tareas pendientes

### PHP 8.3

Migrar a PHP 8.3 en cuanto las herramientas (como *PHP_CodeSniffer*) lo permitan.

## Ideas y deseos

### Buscador

Se podría implementar un buscador directo sobre el archivo exportado. De esta forma, se puede evitar la implementación de la carga y la implementación de las consultas *XPath* para los consumidores del recurso.

### Importador

En lugar de hacer el generador para hacer el *scrap*, podría usarse un importador del archivo XML para tomar un archivo existente y de esta forma crear la estructura. Podría ser útil para, por ejemplo, descargar el recurso y luego usarlo dentro de PHP.

Si el importador existe, entonces sería bueno mejorar las estructuras, por ejemplo, separar la entidad de la colección, o nombrar a los hijos en un método como `$segment->families()`.

### Dependencia de Guzzle opcional

La dependencia de Guzzle se podría establecer como una recomendación, y no como un requisito. De esta forma, la librería se podría usar para sus operaciones normales, excepto hacer el *scrap*. Esta idea solo es útil si se le agregan más utilerías a esta herramienta.
