# 🏪 Punto de Venta - Minimarket

Sistema de gestión comercial completo (POS) diseñado para minimarkets, bodegas y tiendas de conveniencia. Permite administrar ventas, inventario, clientes, empleados y el flujo de caja diario con generación automática de boletas electrónicas en formato térmico.

## Características Principales

- **Caja Registradora Inteligente:** Carrito de compras dinámico con búsqueda de productos en tiempo real y cálculo automático de totales.
- **Control de Cajas (Turnos):** Asignación de sencillo inicial, apertura/cierre de turno y cuadre de caja automático.
- **Ticket Digital y Físico:** Generación de boletas en formato de impresora térmica (80mm) con código QR para visualización en el celular.
- **Gestión de Inventario:** Control de stock, alertas de bajo inventario y filtros dinámicos por categoría.
- **Gestión de Clientes (B2B y B2C):** Soporte para RUC, razón social y registro rápido de clientes desde la caja.
- **Gestión de Empleados:** Creación de cajeros con contraseñas hasheadas (Bcrypt).
- **Dashboard Administrativo:** Visualización de métricas clave (ventas del día, bajo stock, empleados activos) y gráficas interactivas.
- **Exportación a Excel:** Descarga de reportes de ventas filtrados por mes.
- **Seguridad de Nivel Empresarial:** Contraseñas protegidas con hashing y consultas a base de datos con PDO (Prevención de Inyección SQL).

## 🛠️ Tecnologías Utilizadas

El sistema fue desarrollado sin frameworks pesados, utilizando una arquitectura limpia y moderna:

### Backend
- **PHP 8.x:** Lenguaje principal del servidor.
- **MySQL / MariaDB:** Sistema Gestor de Base de Datos relacional.
- **PDO (PHP Data Objects):** Capa de abstracción para la base de datos (seguridad contra SQL Injection).
- **Composer:** Gestor de dependencias para PHP.

### Librerías Integradas (vía Composer)
- **[Dompdf](https://github.com/dompdf/dompdf):** Conversión de HTML a PDF (para las boletas térmicas).
- **[PhpSpreadsheet](https://github.com/PHPOffice/PhpSpreadsheet):** Generación de reportes en formato Excel.
- **[Endroid QR Code](https://github.com/endroid/qr-code):** Generación dinámica de códigos QR para los tickets.

### Frontend
- **HTML5 & CSS3:** Estructura y estilos.
- **Bootstrap 5:** Framework CSS para un diseño 100% responsivo (adaptable a celulares y PCs).
- **JavaScript (Vanilla):** Lógica del carrito interactivo y validaciones en tiempo real (sin recargar la página).
- **Chart.js:** Renderizado de gráficas interactivas (líneas y donas).

### Arquitectura
- **Patrón MVC (Modelo-Vista-Controlador):** Separación estricta de responsabilidades.
- **Patrón DAO (Data Access Object):** Aislamiento de las consultas SQL de la lógica de negocio.
- **Patrón Singleton:** Gestión de una única instancia de conexión a la base de datos.
- **Front Controller:** Punto de entrada único (`index.php`) con enrutamiento amigable.
- **Autoloader PSR-4:** Carga automática de clases en el sistema.

## 📂 Estructura del Proyecto

```text
punto_venta/
├── app/
│   ├── config/          # Configuración de conexión a BD (PDO)
│   ├── controllers/     # Lógica de control (Enrutadores)
│   ├── dao/             # Acceso a datos (Consultas SQL puras)
│   ├── models/          # Modelos de datos (POO)
│   ├── services/        # Servicios externos (PDF, Excel, QR)
│   └── views/           # Archivos de interfaz de usuario (HTML/PHP)
├── assets/              # Imágenes, iconos y códigos QR generados
├── css/                 # Hojas de estilo personalizadas
├── vendor/              # Dependencias de Composer (Ignorado en Git)
├── index.php            # Front Controller (Único punto de entrada)
├── composer.json        # Manifiesto de dependencias
└── README.md            # Documentación del proyecto