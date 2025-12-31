# Asistencia Facial - Sistema de Gestión Académica

Sistema moderno de gestión de asistencia basado en reconocimiento facial con IA, diseñado para instituciones educativas que buscan automatizar y asegurar el registro de presencia en el aula.

## 🚀 Características Principales

- **Reconocimiento Facial en Tiempo Real:** Utiliza `face-api.js` para identificar estudiantes mediante la cámara web de forma instantánea.
- **Tableros Multi-Rol:** Vistas personalizadas para Administradores, Profesores y Estudiantes.
- **Gestión Académica Completa:** Control de programas, periodos, materias, cursos y horarios.
- **Calendario Automatizado:** Generación inteligente de sesiones de clase basadas en horarios semanales.
- **Reportes de Asistencia:** Visualización de métricas, alumnos en riesgo y exportación de datos.
- **Interfaz Moderna:** Diseño responsive con soporte para modo oscuro y notificaciones dinámicas.

## 🛠️ Tecnologías Utilizadas

- **Backend:** PHP 7.4+ (Arquitectura MVC propia)
- **Frontend:** Bootstrap 4, jQuery, Toastr, DataTables
- **IA/Biometría:** Face-api.js (TensorFlow.js)
- **Base de Datos:** MySQL con Triggers para optimización de cálculos
- **DevOps:** Git / GitHub

## 📋 Requisitos del Sistema

- Servidor Web (Apache/Nginx)
- PHP 7.4 o superior
- MySQL 5.7+ o MariaDB
- Conexión a Internet (para carga inicial de modelos de IA)

## 🔧 Instalación Rápida

1. **Clonar el repositorio:**
   ```bash
   git clone https://github.com/reportnotasnos-spec/asistenciafacial.git
   ```

2. **Configurar Base de Datos:**
   - Crear una base de datos en MySQL.
   - Importar las migraciones o ejecutar el archivo `migrate.php`.

3. **Variables de Entorno:**
   - Renombrar `.env.example` (si existe) a `.env` y configurar las credenciales de DB.
   - Ajustar `URL_ROOT` en `config/app.php`.

4. **Acceso:**
   - Admin por defecto: `admin@nos.edu.pe` / `123456`

## 🤝 Contribución

Este es un proyecto privado para la institución. Para sugerencias de mejora, por favor contactar al administrador del sistema.

---
© 2025 Sistema de Asistencia Facial - Inteligencia y Control Académico.
