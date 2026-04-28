# Taquillera Coomotor

Sistema web de gestión de taquilla para la empresa de transporte Coomotor. Permite administrar la flota de buses, programar viajes, vender tiquetes y generar reportes operativos y financieros.

## Stack tecnológico

- **Backend:** PHP 8.2 + Laravel 12
- **Base de datos:** MySQL (nombre: `taquilla_coomotor`)
- **Frontend:** Blade + CSS personalizado (sin framework CSS externo)
- **Servidor local:** XAMPP

---

## Requisitos

- PHP >= 8.2
- Composer
- MySQL
- XAMPP (o cualquier servidor Apache + MySQL)

---

## Instalación

```bash
# 1. Clonar el repositorio
git clone https://github.com/SergioMaje/Taquillera.git
cd Taquillera

# 2. Instalar dependencias PHP
composer install

# 3. Configurar el entorno
cp .env.example .env
php artisan key:generate
```

Editar `.env` con los datos de la base de datos:

```env
DB_DATABASE=taquilla_coomotor
DB_USERNAME=root
DB_PASSWORD=
```

```bash
# 4. Crear la base de datos y ejecutar migraciones
php artisan migrate

# 5. Crear el primer usuario administrador
php artisan tinker
```

```php
// Dentro de tinker:
App\Models\Usuario::create([
    'nombre'   => 'Administrador',
    'email'    => 'admin@coomotor.com',
    'password' => bcrypt('tu_contraseña'),
    'cedula'   => '000000000',
    'rol'      => 'admin',
]);
```

```bash
# 6. Levantar el servidor
php artisan serve
```

---

## Estructura de la base de datos

El sistema tiene 16 tablas organizadas en 5 grupos:

```
Geografía:   departamentos → municipios
Flota:       tipos_bus → buses → asientos
Viajes:      rutas → viajes → asientos_viaje
Ventas:      usuarios → ordenes → tiquetes → pagos
Auditoría:   auditoria
```

**Convenciones de BD:**
- Todas las PKs son custom: `id_bus`, `id_viaje`, `id_tiquete`, etc.
- Las tablas de negocio no usan `timestamps` de Laravel; solo `usuarios` tiene `fecha_registro`.
- Las FKs usan `RESTRICT` para proteger el historial y `CASCADE` donde el borrado es seguro.

---

## Módulos del sistema

### Flota

**Tipos de bus** (`/tipos-bus`)  
Define las plantillas de bus: nombre, número de columnas izquierda/derecha, si es doble piso y capacidad por defecto.

**Buses** (`/buses`)  
Registra cada unidad física con su placa, tipo, propietario y capacidad. Al crear un bus se generan automáticamente los asientos en grilla según la configuración del tipo de bus.  
Los buses con tiquetes vendidos no se eliminan — se dan de baja (`activo = false`) para preservar el historial. Se pueden reactivar desde la pestaña "Dados de baja".

**Propietarios** (`/propietarios`)  
Personas naturales o jurídicas dueñas de los buses.

**Conductores** (`/conductores`)  
Conductores asignables a viajes.

### Operación

**Rutas** (`/rutas`)  
Define origen y destino (municipio a municipio). Base para programar viajes.

**Viajes** (`/viajes`)  
Programa un viaje asignando ruta, bus, conductor, fecha, hora y precio. Al crear un viaje se generan automáticamente los registros de `asientos_viaje` (uno por cada asiento del bus) con estado `libre`.  
Estados posibles: `programado` → `en_ruta` → `completado` / `cancelado`.  
Los costos adicionales del viaje (peajes, combustible, etc.) se registran en `costos_viaje`.

### Taquilla

**Taquilla** (`/taquilla/{viaje}`)  
Vista principal de venta. Muestra el mapa visual del bus con cada asiento coloreado según su estado (libre/ocupado). Al seleccionar un asiento libre y confirmar la compra, el sistema crea una `Orden`, un `Tiquete` y actualiza el `AsientoViaje` a `ocupado`.

**Tiquetes** (`/tiquetes`)  
Listado de todos los tiquetes vendidos con datos del pasajero, viaje y precio.

### Reportes

Disponibles en `/reportes`:

| Reporte | Descripción |
|---|---|
| Viajes del día | Ocupación de cada viaje filtrable por fecha |
| Manifiesto | Lista de pasajeros de un viaje específico |
| Ventas | Tiquetes vendidos en un rango de fechas |
| Ingresos por ruta | Total recaudado agrupado por ruta |
| Ingresos por período | Evolución de ingresos en el tiempo |
| Viajes cancelados | Historial de viajes no realizados |
| Utilidad por viaje | Ingresos menos costos registrados |

---

## Estructura de archivos relevante

```
app/
├── Http/Controllers/
│   ├── Auth/LoginController.php     # Autenticación sesión
│   ├── BusController.php            # CRUD buses + soft delete
│   ├── ViajeController.php          # CRUD viajes + cancelar/completar
│   ├── TaquillaController.php       # Mapa de asientos + venta
│   ├── TiqueteController.php        # Listado tiquetes
│   ├── ReporteController.php        # 7 reportes operativos/financieros
│   ├── TipoBusController.php        # CRUD tipos de bus
│   ├── RutaController.php           # CRUD rutas
│   ├── ConductorController.php      # CRUD conductores
│   ├── PropietarioController.php    # CRUD propietarios
│   └── CostoViajeController.php     # Costos adicionales por viaje
├── Models/                          # 16 modelos Eloquent
│   ├── Bus.php                      # scope activos()
│   ├── Viaje.php
│   ├── Tiquete.php
│   └── ...
database/
├── migrations/                      # 20 migraciones en orden cronológico
resources/views/
├── layouts/app.blade.php            # Layout principal con nav
├── buses/
├── viajes/
├── taquilla.blade.php               # Mapa visual del bus
├── tiquetes/
└── reportes/
routes/
└── web.php                          # Todas las rutas protegidas con auth
```

---

## Autenticación

El sistema usa sesiones nativas de Laravel. Todas las rutas están protegidas con el middleware `auth`. No hay roles diferenciados en esta versión — cualquier usuario registrado tiene acceso completo.

El modelo de autenticación es `App\Models\Usuario` con tabla `usuarios` (alias de `User`).

---

## Decisiones de diseño notables

- **Sin soft deletes de Laravel:** Se implementó soft delete manual con campo `activo` en `buses` para mantener control explícito y compatibilidad con las FKs existentes.
- **Asientos generados automáticamente:** Al crear un bus o un viaje, los asientos/asientos_viaje se crean por código, no manualmente, garantizando consistencia.
- **FKs con RESTRICT en historial:** Las relaciones hacia tiquetes usan `RESTRICT` para que la BD sea la última línea de defensa contra borrados accidentales de historial.
- **Sin timestamps en tablas de negocio:** Solo `usuarios` tiene `fecha_registro`; el resto omite `created_at`/`updated_at` para mantener el esquema limpio.
