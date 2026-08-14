# PlastiControl

Sistema web para la administración de una tienda de productos plásticos, desarrollado con Laravel.

PlastiControl permite gestionar productos, categorías, inventario, ventas, usuarios, reportes y configuración general del sistema desde una interfaz centralizada.

---

## Funcionalidades principales

### Autenticación y usuarios

- Inicio y cierre de sesión.
- Control de acceso mediante roles.
- Administración de usuarios.
- Activación e inactivación de usuarios.
- Cambio de contraseña.
- Registro del último acceso.

### Categorías

- Registro y edición de categorías.
- Activación e inactivación.
- Eliminación lógica y restauración.
- Asociación con productos.

### Productos

- Registro y edición de productos.
- Generación automática de códigos.
- Manejo de imágenes.
- Asociación con categorías.
- Control de estado.
- Eliminación lógica y restauración.
- Creación automática del inventario inicial.

### Inventario

- Consulta de existencias.
- Entradas y salidas manuales.
- Historial de movimientos.
- Control de stock mínimo.
- Estados de inventario:
  - Disponible.
  - Stock bajo.
  - Agotado.
- Validación para impedir salidas superiores al stock disponible.

### Punto de venta

- Selección de productos disponibles.
- Registro de múltiples productos por venta.
- Cálculo automático de subtotal.
- Aplicación de descuentos.
- Cálculo de total y cambio.
- Validación de pago recibido.
- Descuento automático del inventario.
- Registro de movimientos de inventario asociados a la venta.
- Generación de folio único.
- Generación de ticket de venta.

### Dashboard

Incluye información resumida del funcionamiento del negocio, como:

- Ventas del día.
- Ganancia estimada.
- Productos con stock bajo.
- Producto más vendido.
- Ventas recientes.
- Ventas por mes.
- Ventas de la semana.

### Reportes

Permite consultar información dentro de un rango de fechas:

- Número de ventas.
- Ingresos.
- Descuentos.
- Ganancia estimada.
- Ticket promedio.
- Unidades vendidas.
- Productos más vendidos.
- Ventas por usuario.
- Ventas por día.
- Resumen de inventario.
- Historial de ventas.

Los reportes pueden generarse en formato PDF.

### Configuración

Permite administrar información general del sistema y realizar respaldos de la base de datos.

El sistema cuenta con:

- Configuración general.
- Logo del negocio.
- Información de versión.
- Creación manual de respaldos SQL.
- Verificación de restauración de respaldos.

---

## Tecnologías utilizadas

- PHP
- Laravel 12
- MySQL
- Blade
- Bootstrap
- JavaScript
- Composer
- DOMPDF
- Git

---

## Base de datos

PlastiControl utiliza MySQL.

Entre las tablas principales se encuentran:

- `roles`
- `usuarios`
- `categorias`
- `productos`
- `inventarios`
- `movimientos_inventario`
- `ventas`
- `detalles_venta`
- `configuraciones`

Las relaciones, restricciones e índices se administran mediante migraciones de Laravel.

---

## Instalación

### 1. Clonar el repositorio

```bash
git clone <URL_DEL_REPOSITORIO>
cd plasticontrol
```

### 2. Instalar dependencias

```bash
composer install
```

### 3. Crear el archivo de entorno

En Windows:

```powershell
Copy-Item .env.example .env
```

### 4. Generar la clave de Laravel

```bash
php artisan key:generate
```

### 5. Configurar la base de datos

Modificar las siguientes variables en `.env` según el entorno:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=plasticontrol
DB_USERNAME=root
DB_PASSWORD=
```

### 6. Ejecutar migraciones

```bash
php artisan migrate
```

### 7. Crear el enlace de almacenamiento

```bash
php artisan storage:link
```

### 8. Iniciar el servidor

```bash
php artisan serve
```

---

## Respaldos

Los respaldos de la base de datos se generan utilizando `mysqldump`.

En el entorno de desarrollo utilizado para el proyecto, el ejecutable corresponde a MySQL incluido con WampServer.

Los archivos generados se almacenan en:

```text
storage/app/backups
```

La restauración de los respaldos fue verificada utilizando una base de datos independiente de prueba antes de considerar esta funcionalidad terminada.

---

## Generación de PDF

PlastiControl utiliza:

```text
barryvdh/laravel-dompdf
```

para generar documentos PDF desde los reportes del sistema.

---

## Pruebas

El proyecto cuenta con pruebas automatizadas para funcionalidades críticas, incluyendo:

- Autenticación.
- Restricción de usuarios inactivos.
- Productos.
- Generación automática de códigos.
- Inventario.
- Entradas y salidas de stock.
- Validación de stock insuficiente.
- Ventas.
- Validación de pagos.
- Rollback de transacciones.

Para ejecutar las pruebas:

```bash
php artisan test
```

Al cierre de la Etapa 3, la suite cuenta con:

```text
19 tests
64 assertions
0 failures
```

---

## Seguridad e integridad

PlastiControl implementa diferentes mecanismos para proteger la integridad de la información:

- Autenticación mediante Laravel.
- Autorización según roles.
- Validación mediante Form Requests.
- Contraseñas cifradas.
- Transacciones de base de datos.
- Bloqueo de registros de inventario durante operaciones críticas.
- Validación de existencias.
- Soft Deletes en módulos correspondientes.
- Restricciones mediante claves foráneas.
- Rollback automático ante errores en operaciones críticas.

---

## Estado del proyecto

### Etapa 1
Estructura inicial y preparación del proyecto.

### Etapa 2
Implementación de los módulos principales del sistema.

### Etapa 3
Mejoras operativas, reportes PDF, tickets, respaldos, validaciones, optimización y pruebas automatizadas.

**Estado actual: Etapa 3 finalizada.**

---

## Proyecto

**PlastiControl**

Sistema de gestión, inventario y punto de venta para tienda de productos plásticos.