# Propuesta de Normalización: Gestión Académica y Asistencia por Clases

Esta propuesta reestructura la base de datos para pasar de un modelo de "Fichaje Diario" (tipo oficina) a un modelo "Académico" (Clases, Horarios, Periodos).

## 1. Diagrama de Entidad-Relación (ER) Propuesto

El sistema se basa en la relación jerárquica:
`Programa -> Materia -> Curso (Periodo) -> Sesión de Clase <- Asistencia`

### Nuevas Tablas

#### A. Estructura Académica

1.  **`study_programs`** (Carreras/Programas)
    *   Ej: "Ingeniería de Sistemas", "Medicina".
    *   `id`, `name`, `code`.

2.  **`academic_periods`** (Periodos/Semestres)
    *   Ej: "2025-I", "Semestre Otoño 2025".
    *   `id`, `name`, `start_date`, `end_date`, `is_active`.

3.  **`subjects`** (Materias/Asignaturas - El concepto abstracto)
    *   Ej: "Matemáticas I", "Programación Web".
    *   `id`, `program_id` (FK), `name`, `code`, `credits`.

4.  **`rooms`** (Aulas/Laboratorios)
    *   Ej: "Aula 101", "Lab Computación".
    *   `id`, `name`, `location`, `capacity`.

#### B. Gestión de Cursos y Horarios

5.  **`courses`** (La instancia real de una materia en un periodo)
    *   Representa: "Matemáticas I del 2025-I, Grupo A".
    *   `id`
    *   `subject_id` (FK -> subjects)
    *   `period_id` (FK -> academic_periods)
    *   `teacher_id` (FK -> users)
    *   `name` (Ej: "Grupo A", "Sección Nocturna")

6.  **`course_enrollments`** (Inscripciones)
    *   Vincula estudiantes con cursos.
    *   `id`, `course_id` (FK), `student_id` (FK -> users), `enrolled_at`.

7.  **`class_sessions`** (Las clases reales en el calendario)
    *   Aquí es donde se define el horario específico (Mon, Tue...) y las fechas exactas.
    *   `id`
    *   `course_id` (FK)
    *   `room_id` (FK)
    *   `specific_date` (DATE - Ej: 2025-10-12)
    *   `start_time` (TIME - Ej: 08:00:00)
    *   `end_time` (TIME - Ej: 10:00:00)
    *   `status` (ENUM: 'scheduled', 'cancelled', 'completed')
    *   *Nota: Se recomienda generar estas filas automáticamente al crear el curso para todo el periodo.*

#### C. Asistencia

8.  **`attendance_logs`** (Redefinida)
    *   Vincula un escaneo facial a una sesión específica.
    *   `id`
    *   `session_id` (FK -> class_sessions)
    *   `student_id` (FK -> users)
    *   `scan_time` (DATETIME)
    *   `status` (ENUM: 'present', 'late', 'absent')
    *   `verification_method` (face_id, manual).

---

## 2. Definición SQL (Schema)

```sql
-- Estructura Base
CREATE TABLE study_programs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    code VARCHAR(20) UNIQUE
);

CREATE TABLE academic_periods (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL, -- Ej: '2025-I'
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    is_active BOOLEAN DEFAULT 0
);

CREATE TABLE rooms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    capacity INT
);

CREATE TABLE subjects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    program_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    code VARCHAR(20),
    FOREIGN KEY (program_id) REFERENCES study_programs(id)
);

-- Instancias (Cursos)
CREATE TABLE courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    subject_id INT NOT NULL,
    period_id INT NOT NULL,
    teacher_id INT NOT NULL, -- User con rol 'teacher'
    group_name VARCHAR(50), -- Ej: 'Grupo A'
    FOREIGN KEY (subject_id) REFERENCES subjects(id),
    FOREIGN KEY (period_id) REFERENCES academic_periods(id),
    FOREIGN KEY (teacher_id) REFERENCES users(id)
);

CREATE TABLE course_enrollments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT NOT NULL,
    student_id INT NOT NULL,
    enrolled_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(course_id, student_id), -- Evitar duplicados
    FOREIGN KEY (course_id) REFERENCES courses(id),
    FOREIGN KEY (student_id) REFERENCES users(id)
);

-- Horarios / Calendario
CREATE TABLE class_sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT NOT NULL,
    room_id INT NULL,
    specific_date DATE NOT NULL, -- La fecha real (Lunes 12 de Oct)
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    status ENUM('scheduled', 'cancelled', 'completed') DEFAULT 'scheduled',
    FOREIGN KEY (course_id) REFERENCES courses(id),
    FOREIGN KEY (room_id) REFERENCES rooms(id),
    INDEX (specific_date, start_time) -- Índice crucial para búsquedas rápidas al escanear
);

-- Asistencias
CREATE TABLE attendance_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    session_id INT NOT NULL,
    student_id INT NOT NULL,
    scan_time DATETIME NOT NULL,
    status ENUM('present', 'late') DEFAULT 'present',
    verification_method VARCHAR(50) DEFAULT 'face_id',
    FOREIGN KEY (session_id) REFERENCES class_sessions(id),
    FOREIGN KEY (student_id) REFERENCES users(id),
    UNIQUE(session_id, student_id) -- Solo una asistencia por sesión
);
```

---

## 3. Lógica de Negocio: "Del Rostro a la Clase"

Dado que un estudiante puede tener múltiples cursos en un día (Ej: Matemáticas a las 8:00 y Física a las 10:00), el sistema debe ser inteligente al recibir el escaneo facial.

### Algoritmo de Registro de Asistencia (`BiometricController`)

1.  **Recibir Datos:** El sistema recibe `student_id` y `current_timestamp` (Ej: Lunes, 08:05 AM).
2.  **Buscar Clases Activas:**
    *   Buscar en `class_sessions` donde:
        *   `specific_date` == HOY
        *   `start_time` <= AHORA + Holgura (Ej: 15 min antes)
        *   `end_time` >= AHORA
3.  **Filtrar Inscripción:**
    *   De las sesiones encontradas, verificar en `course_enrollments` en cuál está inscrito el `student_id`.
4.  **Registrar:**
    *   Si encuentra **una única coincidencia**: Insertar en `attendance_logs`.
    *   **Cálculo de Tardanza:** Si `current_timestamp` > `start_time` + 10 min -> `status = 'late'`.
    *   Si encuentra **múltiples coincidencias** (Error de horario superpuesto): Registrar en la que tenga el inicio más cercano o pedir confirmación manual.
    *   Si **no encuentra coincidencias**: Registrar en un log de "Accesos sin clase asignada" (Opcional, seguridad física).

## 4. Ejemplo de Flujo de Datos

1.  **Administrador:** Crea el Periodo "2025-I".
2.  **Administrador:** Crea el Curso "Matemáticas - Grupo A".
3.  **Sistema (Script):** Genera automáticamente 30 filas en `class_sessions` (Lunes y Miércoles) para todo el semestre.
4.  **Alumno (Juan):** Se inscribe en el curso.
5.  **Día Lunes 08:00 AM:** Juan se para frente a la cámara.
    *   Reconocimiento Facial -> OK (ID: 45).
    *   Backend busca: ¿Qué clases hay hoy Lunes cerca de las 8:00 AM? -> Encuentra "Sesión #104 de Matemáticas".
    *   Backend verifica: ¿Juan está en Matemáticas? -> Sí.
    *   Backend guarda: Asistencia en Sesión #104.
