# Sistema de Monitoreo de Pulsaciones - Tecno 3

Este proyecto es una plataforma interactiva de monitoreo biométrico familiar en tiempo real. Permite capturar datos de ritmo cardíaco (BPM) y oxígeno en sangre (%SpO2) mediante un dispositivo de hardware (ESP32) o simuladores], almacenarlos en una base de datos relacional, y generar una interfaz visual y reactiva utilizando la biblioteca **p5.js**

## 🚀 Características Principales

- **Autenticación y Roles:** Sistema de inicio de sesión y registro con diferenciación entre usuarios comunes y administradores.
- **Gestión Familiar:** Los usuarios pueden crear un grupo familiar único mediante un código de 4 dígitos o unirse a uno existente al registrarse
- **Panel de Control Avanzado:** Los administradores pueden gestionar usuarios, inyectar lecturas manuales o simular el comportamiento de un oxímetro
- **Visualización (p5.js):** Renderizado en tiempo real de partículas en forma de corazones que laten, escalan y cambian de color según los datos biométricos, la edad y el grupo familiar

## 🛠️ Tecnologías Utilizadas

- **Frontend:** HTML5, CSS3, JavaScript
- **Programación Creativa:** p5.js
- **Backend:** PHP
- **Base de Datos:** MySQL
- **Hardware:** C++ / ESP32 /

## 📂 Estructura del Proyecto

```text
├── css/
│   ├── estilos.css           # Estilos de los formularios de login y registro
│   └── panel.css             # Estilos del panel de administración y estructuras web
├── p5/
│   └── empty-example/
│       ├── index.html        # Contenedor del lienzo de p5.js
│       └── sketch.js         # Lógica visual de los corazones reactivos
├── admin.php                 # Vista general del administrador
├── conexion.php              # Archivo de conexión a la base de datos MySQL
├── consultafamilia.php       # API que retorna los datos del grupo familiar en JSON
├── gestionar_usuarios.php    # Panel destructivo de roles, eliminación e inyección manual
├── index.php                 # Panel principal para usuarios e invitados
├── login.html                # Formulario de inicio de sesión
├── logout.php                # Cierre de sesión y destrucción de variables globales
├── procesar_login.php        # Validación de credenciales y redirección por rol
├── procesar_registro.php     # Alta de usuarios y asignación/creación de códigos familiares
├── recibirdatos_esp.php      # Endpoint HTTP que procesa e inserta las lecturas en la BD
├── registro.html             # Formulario de registro público
├── simulador_esp.php         # Disparador para simular muestras del hardware aleatorias
├── verificar_vinculo.php     # Endpoint para comprobar la vinculación por dirección MAC
└── pulsaciones.sql           # Estructura y volcado inicial de las tablas de la base de datos
```

## ⚙️ Instalación y Configuración Local

- **Clonar el repositorio:** `https://github.com/jworkss/fintaltecno3` Mové la carpeta del proyecto dentro de la carpeta raíz de tu servidor local (ej. `htdocs` XAMPP).
- **Importar la Base de Datos:** Abrí phpMyAdmin, creá una base de datos llamada `pulsaciones` e importá el archivo `pulsaciones.sql`
- **Verificar Conexión:** Revisá que las credenciales de tu entorno coincidan con las configuradas en el archivo `conexion.php`
- **Ejecutar:** Abrí tu navegador web e ingresá a `http://localhost/fintaltecno3/index.php`

---

## 👥 Autores y Cursada

- **Institución:** Universidad Nacional de Moreno (UNM).
- **Materia:** Tecnología de Diseño Multimedial III.
- **Integrantes:** Marcos Duarte, Aimee Meza, Uthurri Gonzalo
- **Docentes:** Mauricio Gutiérrez, Sebastián Zavatarelli
