<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('feature_product', function (Blueprint $table) {
            $table->id();

            $table->foreignId('feature_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('option_product');
    }
};


/*Este código define una migración de base de datos en Laravel para crear la tabla `option_product`, que sirve como tabla pivote para una relación de muchos a muchos entre las tablas `options` y `products`. La tabla pivote almacena los datos adicionales de la relación entre `Option` y `Product`, como las `features` y las marcas de tiempo (`created_at` y `updated_at`).

### Explicación Detallada:
- **Migración en Laravel**:
  - Laravel usa migraciones para gestionar las modificaciones en la base de datos de forma estructurada y versionada.
  - `up()` se ejecuta para aplicar los cambios (crear la tabla), mientras que `down()` se utiliza para revertir los cambios (eliminar la tabla).

- **Método `up()`**:
  - **`Schema::create('option_product', function (Blueprint $table) {...});`**:
    - Crea una tabla llamada `option_product` que servirá como tabla intermedia o pivote entre `options` y `products`.
  - **`$table->id();`**:
    - Crea una columna `id` autoincremental como clave primaria de la tabla.
  - **`$table->foreignId('option_id')->constrained()->onDelete('cascade');`**:
    - Crea una columna `option_id` que actúa como clave foránea, vinculando cada registro de la tabla pivote a un `Option`.
    - `constrained()` establece la relación de clave foránea automáticamente, asumiendo que existe una tabla `options` relacionada.
    - `onDelete('cascade')` significa que si un `Option` es eliminado, automáticamente se eliminarán las filas relacionadas en la tabla `option_product`.
  - **`$table->foreignId('product_id')->constrained()->onDelete('cascade');`**:
    - Similar a `option_id`, esta columna conecta cada registro de la tabla pivote con un `Product`.
    - Al eliminar un `Product`, también se eliminarán las asociaciones relacionadas en la tabla pivote.
  - **`$table->json('features');`**:
    - Crea una columna `features` de tipo `json` para almacenar características adicionales sobre la relación entre un `Option` y un `Product`.
    - El uso de `json` permite almacenar múltiples datos estructurados en esta columna.
  - **`$table->timestamps();`**:
    - Crea las columnas `created_at` y `updated_at` para registrar la fecha y hora de creación y última actualización de cada fila en la tabla.

- **Método `down()`**:
  - **`Schema::dropIfExists('option_product');`**:
    - Si la migración se revierte, este método elimina la tabla `option_product` si existe. Esto facilita la eliminación de la tabla en caso de necesitar deshacer la migración.

### Resumen del Propósito:
La tabla `option_product` permite asociar de manera eficiente cada `Option` con múltiples `Products` y viceversa. Además, al usar la columna `features`,
 se puede almacenar información detallada y específica sobre cada asociación entre una opción y un producto. Las claves foráneas aseguran la integridad referencial
  y las marcas de tiempo facilitan el rastreo de cambios en las relaciones.
 */





/*En Laravel, y en general en bases de datos relacionales como MySQL o PostgreSQL, el tipo de dato `json` permite almacenar datos en formato JSON (JavaScript Object Notation) en una sola columna. Esto significa que se pueden guardar datos estructurados de manera flexible, similar a un objeto o arreglo de JSON, dentro de una celda de la base de datos.

### ¿Qué es JSON?
JSON es un formato de texto ligero y fácil de leer para representar datos estructurados, comúnmente usado en aplicaciones web para enviar y recibir datos entre el cliente y el servidor. Un objeto JSON se compone de pares clave-valor, por ejemplo:

```json
{
  "color": "red",
  "size": "large",
  "tags": ["summer", "sale", "trending"]
}
```

### Uso del Tipo `json` en Bases de Datos
El tipo `json` en una columna permite almacenar este tipo de estructura directamente en la base de datos, lo cual es útil cuando se requiere guardar información que no tiene un esquema fijo. 

#### Ejemplos de Uso en Laravel
Imagina que en la columna `features` de la tabla `option_product`, queremos almacenar detalles adicionales sobre la relación entre una `Option` y un `Product`. Estos detalles podrían ser atributos variados como especificaciones técnicas, descripciones u otros datos.

```json
{
  "color": "blue",
  "material": "cotton",
  "warranty": "2 years"
}
```

### Ventajas de Usar el Tipo `json`
1. **Flexibilidad**: Permite almacenar datos semi-estructurados sin necesidad de definir una estructura fija de tablas y columnas para cada detalle.
2. **Anidación de Datos**: Se pueden almacenar objetos y arreglos anidados, lo cual sería más complicado de hacer con tipos de datos tradicionales como `string` o `int`.
3. **Consultas JSON en SQL**:
   - Bases de datos como MySQL y PostgreSQL permiten realizar consultas sobre los datos JSON. Por ejemplo, se pueden buscar registros donde un valor específico esté presente en la columna JSON.
   - En Laravel, se pueden usar métodos como `->whereJsonContains()` o `->whereJsonLength()` para hacer consultas sobre el contenido de columnas JSON.

### Desventajas y Consideraciones
1. **Consulta Compleja**: Aunque es posible realizar consultas sobre el contenido JSON, a veces puede ser más complejo y menos eficiente que trabajar con tablas normalizadas (esquemas tradicionales con columnas).
2. **Desempeño**: Para grandes cantidades de datos, el almacenamiento y la consulta de datos JSON pueden ser más lentos que en tablas normalizadas.
3. **No Adecuado para Relaciones Complejas**: Si los datos JSON son muy complejos o las relaciones entre los datos son importantes, es preferible usar un modelo relacional con tablas y relaciones adecuadas.

### Ejemplo en Laravel
Cuando definimos la columna `json` en una migración, así:

```php
$table->json('features');
```

Podemos guardar un valor JSON en esta columna cuando creamos o actualizamos un registro:

```php
$optionProduct = OptionProduct::create([
    'option_id' => 1,
    'product_id' => 2,
    'features' => json_encode([
        'color' => 'blue',
        'size' => 'M',
        'available_in_store' => true
    ])
]);
```

Y al recuperar el registro, Laravel automáticamente decodifica el valor JSON para que podamos acceder a él como un array o un objeto:

```php
$features = $optionProduct->features;
echo $features['color']; // Output: blue
```

### ¿Cuándo Usar el Tipo `json`?
- Cuando los datos no son siempre los mismos para cada registro (por ejemplo, diferentes productos pueden tener diferentes características).
- Cuando no se desea crear múltiples columnas para cada posible atributo.
- Cuando se necesita almacenar datos que cambian con frecuencia y cuya estructura no está completamente definida desde el principio.

En resumen, el tipo `json` proporciona flexibilidad para almacenar datos complejos en una sola columna, pero debe ser usado con cautela, especialmente en aplicaciones donde el rendimiento es crítico y las consultas a datos estructurados son frecuentes. */