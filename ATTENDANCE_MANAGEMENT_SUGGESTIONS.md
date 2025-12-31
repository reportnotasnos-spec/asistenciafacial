# Propuesta para la Gestión de Asistencia (Attendance)

Este documento describe la arquitectura y los pasos sugeridos para implementar el módulo de control de asistencia en el proyecto `asistenciafacial`.

## 1. Esquema de Base de Datos

Se requiere una nueva tabla para registrar los eventos de entrada y salida.

### Tabla: `attendance_logs`

| Columna | Tipo | Descripción |
| :--- | :--- | :--- |
| `id` | INT (PK) | Identificador único del registro. |
| `user_id` | INT (FK) | Relación con la tabla `users`. |
| `date` | DATE | La fecha del registro (para búsquedas rápidas). |
| `check_in_time` | DATETIME | Hora exacta de entrada. |
| `check_out_time` | DATETIME | Hora exacta de salida (puede ser NULL inicialmente). |
| `status` | ENUM | Estado calculado: 'present', 'late', 'absent', 'left_early'. |
| `verification_method`| VARCHAR | Método usado (ej. 'face_id', 'manual_override'). |
| `confidence_score` | FLOAT | (Opcional) Puntaje de confianza del reconocimiento facial. |

### SQL Sugerido (Migración)

```sql
CREATE TABLE attendance_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    date DATE NOT NULL,
    check_in_time DATETIME NOT NULL,
    check_out_time DATETIME NULL,
    status ENUM('present', 'late', 'left_early') DEFAULT 'present',
    verification_method VARCHAR(50) DEFAULT 'face_id',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
-- Índice para búsquedas rápidas por fecha y usuario
CREATE INDEX idx_attendance_date_user ON attendance_logs(date, user_id);
```

## 2. Flujo de Registro Biométrico

El sistema debe diferenciar automáticamente entre una "Entrada" y una "Salida" basándose en el historial del día.

### Lógica del Controlador (`AttendanceController::scan`)

1.  **Recepción**: El frontend envía el descriptor facial (o la ID del usuario reconocida).
2.  **Verificación**: El backend verifica la identidad (ya implementado en `BiometricController`).
3.  **Decisión de Acción**:
    *   Buscar un registro en `attendance_logs` donde `user_id = X` y `date = HOY`.
    *   **Caso A (Sin registro previo hoy):** Crear nuevo registro. Asignar `check_in_time`.
    *   **Caso B (Registro existe, `check_out_time` es NULL):** Actualizar registro. Asignar `check_out_time`.
    *   **Caso C (Registro existe y cerrado):** ¿Permitir múltiples turnos? (Configurable). Por defecto, rechazar o crear nuevo turno.

### Reglas de Negocio (Business Rules)
*   **Debounce (Rebote):** Evitar que el usuario registre entrada y salida en menos de 5 minutos. Si intenta registrarse de nuevo inmediatamente, mostrar mensaje: "Entrada ya registrada hace un momento".
*   **Tardanzas**: Si `check_in_time` > Hora de inicio configurada (ej. 08:00 AM), marcar `status = 'late'`.

## 3. Componentes Necesarios

### A. Modelo (`app/models/Attendance.php`)
Métodos clave:
*   `logCheckIn($userId)`
*   `logCheckOut($userId)`
*   `getDailyLogs($date)`: Para reporte diario.
*   `getUserHistory($userId, $month)`: Para vista de perfil.

### B. Controlador (`app/controllers/AttendanceController.php`)
*   `scan()`: Renderiza la vista de la cámara (similar a `biometrics/register` pero para matching).
*   `verify()`: Endpoint API (POST) que recibe el descriptor, busca al usuario y registra la asistencia.
*   `history()`: Muestra la tabla de asistencias del usuario logueado.

### C. Vistas (`resources/views/attendance/`)
*   `scan.php`:
    *   Interfaz limpia, pantalla completa.
    *   Feedback visual claro (Verde = Éxito, Rojo = Fallo).
    *   Muestra nombre y hora registrada tras el éxito.
*   `index.php` (Historial):
    *   Calendario o lista de asistencias del usuario.

## 4. Interfaz de Usuario (UI/UX)

### Modo "Kiosco"
Se recomienda crear una vista específica donde el dispositivo (tablet/webcam en entrada) esté siempre activo esperando rostros.
*   **Detección Pasiva**: El sistema detecta rostros continuamente.
*   **Auto-envío**: Si la confianza del reconocimiento es > 0.6, envía automáticamente la petición sin pulsar botones.

### Reportes (Admin/Teachers)
*   Vista para filtrar por: Grado (para estudiantes), Departamento (para profesores) o Fecha.
*   Botón para exportar a CSV/Excel.

## 5. Próximos Pasos (Plan de Implementación)

1.  Crear la migración para la tabla `attendance_logs`.
2.  Crear el Modelo `Attendance`.
3.  Clonar la vista de `biometrics/register.php` a `attendance/scan.php` y adaptarla para *comparar* en lugar de *registrar*.
    *   *Nota:* Para comparar, necesitarás cargar todos los descriptores de la BD al frontend (si son pocos usuarios) O enviar el descriptor al backend para compararlo allí (más seguro y escalable).
4.  Implementar la lógica de Check-in/Check-out en el controlador.
