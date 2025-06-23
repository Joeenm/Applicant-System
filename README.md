# 🗂️ Sistema de Gestión de Postulantes - Proyecto Desarrollo de Software XI

## 📌 Descripción del Proyecto
Este sistema web forma parte del Proyecto de **Desarrollo de Software XI** y tiene como objetivo gestionar eficientemente a los **postulantes a empleos**. Permite registrar, visualizar y administrar la información y documentación de candidatos de manera segura y estructurada.

---

## 🌟 Características Principales

### 🧑‍💼 Para Postulantes
- Formulario interactivo de postulación
- Registro de información personal y académica
- Carga de documentos académicos (PDF)
- Visualización previa de documentos subidos
- Confirmación de postulación con número de seguimiento

### 👨‍💼 Para Administradores
- Registro e Inicio de Sesión
- Panel administrativo con estadísticas clave
- Visualización detallada de cada postulante y sus documentos
- Eliminación segura de registros con confirmación previa

---

## 🛠 Tecnologías Utilizadas

### 🔧 Backend
- **PHP 8.0+**
- **MySQL** (con bases de datos separadas para administradores, usuarios y documentos)
- **XAMPP** (Apache + MySQL + PHP localmente)

### 🎨 Frontend
- **Tailwind CSS 3.0+**
- **Alpine.js** para interacciones dinámicas
- **PDF.js** para la visualización de documentos
- **Chart.js** para visualización gráfica de estadísticas  
- **Font Awesome** para íconos
- **SweetAlert2** para notificaciones elegantes

---

## 🚀 Cómo Empezar
1. Clona este repositorio en el directorio `htdocs` de XAMPP.
2. Inicia Apache y MySQL desde el Panel de Control de XAMPP.
3. Crea las bases de datos necesarias desde **phpMyAdmin**, usando los archivos ubicados en la carpeta `sql/`.
4. Configura los parámetros de conexión en el archivo:  
   `php/conexion.php`
5. Accede al proyecto desde tu navegador:  
   👉 `http://localhost/Applicant-System/`

---

## 📦 Backups Incluidos

En la carpeta `backups/` se incluyen archivos `.sql` con respaldos completos de las siguientes bases de datos:

- `admin_db.sql`  
- `usuarios_db.sql`  
- `academico_db.sql`

🧾 **Credenciales predeterminadas para el administrador** (en `admin_db`):
- Usuario: `admin@utp.ac.pa`  
- Contraseña: `admin2025`

Puedes importar estos archivos directamente desde **phpMyAdmin** para tener el sistema funcional con datos iniciales.

---

## 🔗 Requisitos Adicionales
- 💡 **Conexión a internet requerida** para visualizar correctamente los elementos del frontend (ej. íconos de Font Awesome, gráficos con Chart.js, CDN de Tailwind, etc.).

---

## 📄 Licencia
Este proyecto es parte del módulo de Desarrollo de Software XI y su uso está destinado exclusivamente con fines educativos o institucionales.
