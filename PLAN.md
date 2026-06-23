# 📋 Plan de Adaptación y Estructura Arquitectónica: ruedas.store

Este documento sirve como guía metodológica y de requerimientos para los pasantes. Su objetivo es adaptar el proyecto actual para soportar la venta de **Autos, Motos y Maquinaria Pesada** en Bolivia, estableciendo roles de usuario, límites de publicación y el sistema de contacto con el vendedor.

---

## 🏛️ 1. Estructura de Diseño y Reglas del Sitio

*   **Dominio Oficial**: **ruedas.store** (Configurar en la variable `APP_NAME` y `APP_URL` de su entorno `.env`).
*   **Modelo de Datos Principal**: Se utilizará la tabla de datos existente `cars` (conceptualizada a nivel visual como **"Publicación"** o **"Anuncio"**) para aprovechar las relaciones de imágenes, marcas, modelos y localizaciones ya desarrolladas.
*   **Geografía**: Adaptado exclusivamente a Bolivia (9 Departamentos y sus Municipios/Ciudades principales).

---

## 🛠️ 2. Módulos de Trabajo (Requerimientos Lógicos)

### 📍 Módulo 1: Geografía Boliviana (Localización)
**Objetivo**: Reemplazar los datos de ubicación predeterminados por los departamentos y municipios de Bolivia.

*   **Requerimientos de Base de Datos**:
    *   La tabla `states` debe almacenar los **9 Departamentos**: La Paz, Santa Cruz, Cochabamba, Oruro, Potosí, Tarija, Chuquisaca, Beni y Pando.
    *   La tabla `cities` debe almacenar los municipios más importantes correspondientes a cada departamento (ej. Santa Cruz de la Sierra, Montero, El Alto, Sacaba, Cercado, etc.).
*   **Modificaciones en Vistas**:
    *   Renombrar en las etiquetas de los formularios y filtros de búsqueda las palabras inglesas "State" por **"Departamento"** y "City" por **"Ciudad o Municipio"**.
*   **Archivos de Referencia en el Proyecto**:
    *   Ver el seeder existente en `database/seeders/DatabaseSeeder.php` para comprender cómo se siembran las tablas iniciales.

---

### 🏍️ Módulo 2: Clasificación y Campos Dinámicos por Categoría
**Objetivo**: Permitir al usuario categorizar su anuncio y adaptar el formulario mostrando campos específicos para cada tipo de vehículo.

*   **Categorías Base**:
    *   En la tabla `car_types`, definir tres tipos fijos: `Auto`, `Motocicleta` y `Maquinaria Pesada`.
*   **Campos Dinámicos según Categoría**:
    *   **🚗 Si elige "Auto"**:
        *   **Desgaste**: Campo con etiqueta **"Kilometraje (Km)"**.
        *   **Tipo de carrocería**: Selector de tipo (*Sedán, Hatchback, Camioneta Pick-up, Vagoneta SUV, Minibús, etc.*).
        *   **Tracción**: Selector de tracción (*4x4, 4x2, Delantera, Trasera*).
        *   **Filtros de Confort**: Mostrar todas las casillas (*Aire Acondicionado, ABS, Vidrios Eléctricos, Calefacción de asientos*).
    *   **🏍️ Si elige "Motocicleta"**:
        *   **Desgaste**: Campo con etiqueta **"Kilometraje (Km)"**.
        *   **Cilindrada**: Campo numérico para los centímetros cúbicos (ej. *150 cc, 250 cc, 600 cc*).
        *   **Tipo de Moto**: Selector de estilo (*Scooter, Deportiva, Enduro/Cross, Custom/Chopper, Cuadratrack*).
        *   **Tipo de arranque**: Selector de arranque (*Eléctrico, Pedal o Ambos*).
        *   **Filtros de Confort**: **Ocultar** casillas irrelevantes como *Aire acondicionado y Vidrios eléctricos*.
    *   **🚜 Si elige "Maquinaria Pesada"**:
        *   **Desgaste**: La etiqueta del campo cambia automáticamente a **"Horas de uso"**.
        *   **Tipo de Rodado**: Selector para indicar si se desplaza mediante *Llantas* o *Orugas*.
        *   **Tipo de Maquinaria**: Selector de rubro (*Excavadora, Retroexcavadora, Tractor Agrícola, Cosechadora, Montacargas, etc.*).
        *   **Filtros de Confort**: Ocultar la mayoría de casillas, dejando únicamente activa la de *Aire Accondicionado* (para cabinas de maquinaria moderna).
*   **Archivos de Referencia en el Proyecto**:
    *   Ver la vista del formulario en `resources/views/car/create.blade.php` para integrar lógica dinámica de JavaScript/Alpine.js que controle la visualización de los bloques de inputs según la categoría elegida.

---

### 👥 Módulo 3: Roles, Moderación y Límites de Publicación
**Objetivo**: Diferenciar los permisos del Administrador (dueño del sitio) frente a los Clientes comunes.

*   **Estructura de Base de Datos**:
    *   **Usuarios (`users`)**: Agregar un campo lógico para el rol (`role`), con valores válidos como `admin` y `client` (siendo `client` por defecto).
    *   **Anuncios (`cars`)**: Agregar un campo booleano de moderación (`is_approved`) para indicar si el anuncio está aprobado y es visible públicamente.
*   **Reglas de Negocio en la Creación de Anuncios**:
    *   Si el usuario autenticado tiene el rol `client`:
        *   Validar antes de guardar que no exceda el límite de **2 publicaciones aprobadas**. Si ya las tiene, denegar la acción.
        *   Guardar el anuncio en estado pendiente (`is_approved = false`).
    *   Si el usuario es `admin`:
        *   Permitir publicaciones ilimitadas.
        *   Aprobar automáticamente el anuncio (`is_approved = true`).
*   **Filtrado Público**:
    *   Los listados de la página principal (Home) y las búsquedas generales deben filtrar únicamente aquellos anuncios que tengan `is_approved = true` y cuya fecha de publicación sea pasada.
*   **Archivos de Referencia en el Proyecto**:
    *   Ver `app/Actions/StoreCarAction.php` como plantilla para inyectar la lógica de verificación de límites y definición de estado antes de guardar en la base de datos.
    *   Ver `app/Http/Controllers/HomeController.php` para ver cómo se estructuran las consultas de listados principales.

---

### 🌟 Módulo 4: Anuncios Destacados y Banners de Publicidad
**Objetivo**: Habilitar espacios de publicidad recomendada en la portada y posicionar con prioridad los anuncios de pago.

*   **Estructura de Base de Datos**:
    *   **Anuncios (`cars`)**: Agregar un campo booleano `is_featured` para marcar publicaciones destacadas.
*   **Reglas en la Interfaz de Búsqueda**:
    *   Los resultados de búsqueda en la web siempre deben ordenar los anuncios de manera que los destacados (`is_featured = true`) se muestren en primer lugar, independientemente de los filtros comunes aplicados por el usuario.
*   **Diseño en el Home**:
    *   Crear una sección visual superior llamada **"Autoventas Recomendadas"** o **"Destacados"** que muestre un carrusel o galería con los registros marcados como destacados.

---

### 📞 Módulo 5: Sistema de Contacto (WhatsApp y Teléfono)
**Objetivo**: Facilitar la comunicación directa del comprador boliviano con el vendedor del vehículo de manera rápida y sin fricciones.

*   **1. Enlace Directo a WhatsApp (Principal en Bolivia)**:
    *   En la página de detalle del vehículo (`car/show`), mostrar un botón destacado con el color corporativo de WhatsApp: **"Preguntar por WhatsApp"**.
    *   Este botón debe abrir una pestaña nueva redirigiendo a la API de WhatsApp con el número del vendedor (anteponiendo el código de país de Bolivia `+591`).
    *   El enlace debe contener un mensaje predefinido automático. Ejemplo de URL:
        `https://wa.me/591XXXXXXXX?text=Hola,%20estoy%20interesado%20en%20el%20vehículo%20[Título]%20que%20vi%20en%20ruedas.store.`
*   **2. Revelar Teléfono por AJAX**:
    *   El número de teléfono del vendedor no debe cargarse visible directamente en el código fuente de la página para evitar que bots automáticos raspen la información de los usuarios.
    *   En su lugar, el número de teléfono estará tapado (ej. `78XXXXXX`) y tendrá un botón **"Ver número de teléfono"**.
    *   Al hacer clic, el botón hará una llamada AJAX al backend para recuperar el teléfono del vendedor y mostrarlo.
*   **Archivos de Referencia en el Proyecto**:
    *   Ver el controlador `CarController@getPhone` que ya devuelve el teléfono del coche vía JSON.
    *   Ver la vista de detalle `resources/views/car/show.blade.php` para integrar la llamada AJAX en JS.

---

## 📅 PLANIFICACIÓN DE TAREAS PARA LA FASE DE LANZAMIENTO (FASE 2)

### 🌟 Módulo A: Sistema de Destacados, Banners Publicitarios y Estadísticas — **Asignado a: Javier**
*   **Descripción del Módulo**: Javier debe programar la monetización y la visualización de anuncios premium.
*   **Objetivos Específicos**:
    1.  **Esquema de Base de Datos**: Crear una migración que agregue `is_featured` (boolean, default false), `is_featured_until` (datetime, null) y `views_count` (integer, default 0) a la tabla `cars`.
    2.  **Prioridad en Consultas**: Modificar la consulta de búsqueda (`scopeFilter` en `Car.php`) y la consulta del Home (`HomeController`) para que los vehículos marcados como destacados y cuya fecha no haya expirado se muestren al principio de cualquier resultado, con prioridad sobre los normales.
    3.  **Carrusel Superior en Portada**: Agregar una sección premium tipo slider o carrusel en la parte superior del Home llamada **"Autoventas Recomendadas"** que muestre únicamente los anuncios destacados.
    4.  **Métricas del Vendedor**: En el detalle de cada vehículo (`show.blade.php`), añadir un contador visual del número de visitas con un icono de ojo (👁️). Crear un Middleware para incrementar `views_count` de manera única por sesión de usuario para evitar manipulaciones.
    5.  **Botones de Compartir**: Integrar un widget flotante o botones en la barra de contacto del vehículo para compartir en redes sociales (Facebook, Twitter y WhatsApp).

### 🛡️ Módulo B: Panel de Administración, Moderación y Gestión de Roles — **Asignado a: Alejandro**
*   **Descripción del Módulo**: Alejandro debe programar todo el backend y frontend administrativo para la moderación del sitio.
*   **Objetivos Específicos**:
    1.  **Middleware de Seguridad**: Implementar un middleware `AdminMiddleware` que verifique que el usuario autenticado tiene el rol `admin`.
    2.  **Dashboard de Moderación**: Crear un panel de control bajo la ruta `/admin/moderation` accesible únicamente para administradores.
    3.  **Listado de Aprobaciones**: Diseñar una tabla dinámica en el panel administrativo que liste los anuncios pendientes de aprobación (`is_approved = false`). El administrador debe poder aprobarlos con un botón (usando AJAX/Fetch para una experiencia interactiva sin recarga de página completa).
    4.  **Gestión de Usuarios**: En el mismo panel, agregar una pestaña para visualizar a todos los usuarios registrados, pudiendo cambiar el rol de un usuario (`client` $\leftrightarrow$ `admin`) o desactivar cuentas que incumplan las reglas del portal.
    5.  **Reporte Estadístico**: Mostrar en el panel un gráfico o contadores rápidos con: Total de anuncios del sitio, total por categoría (Autos, Motocicletas, Maquinaria) y total de usuarios registrados.

### 🗺️ Módulo C: Geolocalización GPS, Mapas Interactivos y Gestión de Fotos — **Asignado a: Roger**
*   **Descripción del Módulo**: Roger debe programar la experiencia geográfica del usuario y la edición de multimedia.
*   **Objetivos Específicos**:
    1.  **Mapa Interactivo en Registro**: Integrar la librería gratuita **Leaflet.js** en los formularios de creación (`create.blade.php`) y edición (`edit.blade.php`). Al hacer clic sobre el mapa, se debe colocar un pin y utilizar un servicio de geocodificación inversa gratuito (Nominatim) para escribir automáticamente la dirección sugerida en el input `address`.
    2.  **Mapa en el Detalle**: Renderizar en la página de detalle del vehículo (`show.blade.php`) un mapa interactivo simple centrado en las coordenadas guardadas del vendedor.
    3.  **Edición y Eliminación Individual de Imágenes**: Desarrollar la lógica para que, en la vista de edición (`edit.blade.php`), el usuario pueda ver las imágenes previamente subidas y borrarlas de manera individual con un botón de "Eliminar Foto". Roger debe encargarse de eliminar el archivo físico del disco (`Storage::disk('public')->delete(...)`) y su registro en la base de datos de manera sincronizada.

---

## 💡 Plantillas y Patrones de Código del Proyecto (Blueprints)

Para mantener la consistencia del código del repositorio, los pasantes deben guiarse estrictamente por los siguientes archivos existentes en el proyecto como plantilla de trabajo:

1.  **Para crear migraciones y esquemas de base de datos**: Ver ejemplos en `database/migrations/`.
2.  **Para la lógica de creación e inserción segura de datos**: Guiarse por `app/Actions/StoreCarAction.php` y `app/Actions/UpdateCarAction.php` (utilizan DTOs y transacciones).
3.  **Para los formularios de petición y validación de inputs**: Usar `app/Http/Requests/CarRequest.php` como base para añadir las nuevas reglas.
4.  **Para las políticas de seguridad y restricciones de acceso**: Seguir el patrón de Laravel Policies en `app/Policies/CarPolicy.php`.
5.  **Para los componentes visuales Blade reutilizables**: Analizar `resources/views/components/` (por ejemplo, `search-form.blade.php` e `image-upload.blade.php`).

