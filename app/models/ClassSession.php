<?php

class ClassSession {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    /**
     * Genera automáticamente las sesiones de clase para un curso durante todo el periodo.
     */
    public function generateSchedule($courseId, $periodId, $schedules) {
        // 1. Obtener fechas del periodo
        $this->db->query('SELECT start_date, end_date FROM academic_periods WHERE id = :id');
        $this->db->bind(':id', $periodId);
        $period = $this->db->single();

        if (!$period) {
            return 0; // Periodo no encontrado
        }

        $startDate = new DateTime($period->start_date);
        $endDate = new DateTime($period->end_date);
        // Asegurar incluir el último día
        $endDate->modify('+1 day'); 

        $interval = new DateInterval('P1D');
        $periodRange = new DatePeriod($startDate, $interval, $endDate);

        $sessionsCreated = 0;

        // 2. Iterar cada día del semestre
        foreach ($periodRange as $date) {
            $currentDayName = $date->format('l'); // Monday, Tuesday...

            // 3. Verificar si este día corresponde a alguna configuración de horario
            foreach ($schedules as $schedule) {
                if (strcasecmp($currentDayName, $schedule['day_of_week']) === 0) {
                    
                    // Insertar sesión
                    $this->db->query('INSERT INTO class_sessions (course_id, room_id, specific_date, start_time, end_time, status) 
                                      VALUES (:course_id, :room_id, :specific_date, :start_time, :end_time, "scheduled")');
                    
                    $this->db->bind(':course_id', $courseId);
                    $this->db->bind(':room_id', $schedule['room_id'] ?? null);
                    $this->db->bind(':specific_date', $date->format('Y-m-d'));
                    $this->db->bind(':start_time', $schedule['start_time']);
                    $this->db->bind(':end_time', $schedule['end_time']);

                    if ($this->db->execute()) {
                        $sessionsCreated++;
                    }
                }
            }
        }

        return $sessionsCreated;
    }

    public function findCurrentSession($courseId = null) {
        $now = new DateTime();
        $date = $now->format('Y-m-d');
        $time = $now->format('H:i:s');

        $sql = "SELECT * FROM class_sessions 
                WHERE specific_date = :date 
                AND :time >= SUBTIME(start_time, '00:15:00') 
                AND :time <= end_time
                AND status = 'scheduled'";

        if ($courseId) {
            $sql .= " AND course_id = :course_id";
        }

        $this->db->query($sql);
        $this->db->bind(':date', $date);
        $this->db->bind(':time', $time);
        
        if ($courseId) {
            $this->db->bind(':course_id', $courseId);
        }

        return $this->db->single();
    }

    public function getTodaysSessionForTeacher($teacherId) {
        $now = new DateTime();
        $date = $now->format('Y-m-d');
        $time = $now->format('H:i:s');

        $this->db->query("SELECT cs.*, s.name as subject_name, c.group_name, r.name as room_name
                          FROM class_sessions cs
                          JOIN courses c ON cs.course_id = c.id
                          JOIN subjects s ON c.subject_id = s.id
                          LEFT JOIN rooms r ON cs.room_id = r.id
                          WHERE c.teacher_id = :tid 
                          AND cs.specific_date = :date
                          AND cs.end_time >= :time
                          ORDER BY cs.start_time ASC
                          LIMIT 1");
        $this->db->bind(':tid', $teacherId);
        $this->db->bind(':date', $date);
        $this->db->bind(':time', $time);

        return $this->db->single();
    }

    public function getSessionsForWeek($teacherId) {
        $startOfWeek = new DateTime();
        $startOfWeek->modify('monday this week');
        $endOfWeek = clone $startOfWeek;
        $endOfWeek->modify('+6 days');

        $this->db->query("SELECT cs.*, s.name as subject_name, s.code as subject_code, c.group_name, r.name as room_name
                          FROM class_sessions cs
                          JOIN courses c ON cs.course_id = c.id
                          JOIN subjects s ON c.subject_id = s.id
                          LEFT JOIN rooms r ON cs.room_id = r.id
                          WHERE c.teacher_id = :tid 
                          AND cs.specific_date BETWEEN :start AND :end
                          ORDER BY cs.specific_date ASC, cs.start_time ASC");
        
        $this->db->bind(':tid', $teacherId);
        $this->db->bind(':start', $startOfWeek->format('Y-m-d'));
        $this->db->bind(':end', $endOfWeek->format('Y-m-d'));

        return $this->db->resultSet();
    }

    public function getStudentSessionsByDate($studentId, $date) {
        $this->db->query("SELECT cs.*, s.name as subject_name, s.code as subject_code, 
                                 c.group_name, r.name as room_name,
                                 al.status as attendance_status, al.scan_time
                          FROM class_sessions cs
                          JOIN courses c ON cs.course_id = c.id
                          JOIN course_enrollments ce ON c.id = ce.course_id
                          JOIN subjects s ON c.subject_id = s.id
                          LEFT JOIN rooms r ON cs.room_id = r.id
                          LEFT JOIN attendance_logs al ON cs.id = al.session_id AND al.student_id = :sid
                          WHERE ce.student_id = :sid 
                          AND cs.specific_date = :date
                          ORDER BY cs.start_time ASC");
        
        $this->db->bind(':sid', $studentId);
        $this->db->bind(':date', $date);

        return $this->db->resultSet();
    }

    public function getStudentAttendanceByCourse($studentId, $courseId) {
        $this->db->query("SELECT cs.*, r.name as room_name, al.status as attendance_status, al.scan_time, al.verification_method
                          FROM class_sessions cs
                          LEFT JOIN rooms r ON cs.room_id = r.id
                          LEFT JOIN attendance_logs al ON cs.id = al.session_id AND al.student_id = :sid
                          WHERE cs.course_id = :cid
                          ORDER BY cs.specific_date DESC, cs.start_time DESC");
        
        $this->db->bind(':sid', $studentId);
        $this->db->bind(':cid', $courseId);

        return $this->db->resultSet();
    }

    public function getSessionById($sessionId) {
        $this->db->query('SELECT cs.*, s.name as subject_name, c.group_name 
                          FROM class_sessions cs
                          JOIN courses c ON cs.course_id = c.id
                          JOIN subjects s ON c.subject_id = s.id
                          WHERE cs.id = :id');
        $this->db->bind(':id', $sessionId);
        return $this->db->single();
    }

    public function getSessionsByCourse($courseId) {
        $this->db->query('SELECT cs.*, r.name as room_name 
                          FROM class_sessions cs 
                          LEFT JOIN rooms r ON cs.room_id = r.id 
                          WHERE cs.course_id = :course_id 
                          ORDER BY cs.specific_date ASC, cs.start_time ASC');
        $this->db->bind(':course_id', $courseId);
        return $this->db->resultSet();
    }

    public function markAttendance($sessionId, $studentId, $status = 'present', $method = 'face_id') {
        $this->db->query('SELECT id FROM attendance_logs WHERE session_id = :session_id AND student_id = :student_id');
        $this->db->bind(':session_id', $sessionId);
        $this->db->bind(':student_id', $studentId);
        
        if ($this->db->single()) {
            return false;
        }

        $this->db->query('INSERT INTO attendance_logs (session_id, student_id, scan_time, status, verification_method) 
                          VALUES (:session_id, :student_id, NOW(), :status, :method)');
        $this->db->bind(':session_id', $sessionId);
        $this->db->bind(':student_id', $studentId);
        $this->db->bind(':status', $status);
        $this->db->bind(':method', $method);

        return $this->db->execute();
    }

    public function getAttendanceLog($sessionId) {
        $this->db->query('SELECT al.*, u.name, sd.profile_picture_url as profile_picture
                          FROM attendance_logs al
                          JOIN users u ON al.student_id = u.id
                          LEFT JOIN student_details sd ON u.id = sd.user_id
                          WHERE al.session_id = :session_id
                          ORDER BY al.scan_time DESC');
        $this->db->bind(':session_id', $sessionId);
        return $this->db->resultSet();
    }

    public function updateSession($data) {
        $this->db->query('UPDATE class_sessions 
                          SET room_id = :room_id, 
                              specific_date = :specific_date, 
                              start_time = :start_time, 
                              end_time = :end_time 
                          WHERE id = :id');
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':room_id', $data['room_id']);
        $this->db->bind(':specific_date', $data['specific_date']);
        $this->db->bind(':start_time', $data['start_time']);
        $this->db->bind(':end_time', $data['end_time']);

        return $this->db->execute();
    }

    public function deleteSession($id) {
        $this->db->query('DELETE FROM class_sessions WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    /**
     * Busca sesiones pasadas que aún están como "scheduled" y las marca como "completed".
     * Se considera pasada si la fecha es anterior a hoy O si es hoy y la hora de fin ya pasó.
     */
    public function closePastSessions() {
        $this->db->query("UPDATE class_sessions 
                          SET status = 'completed' 
                          WHERE status = 'scheduled' 
                          AND (specific_date < CURDATE() 
                               OR (specific_date = CURDATE() AND end_time < CURTIME()))");
        return $this->db->execute();
    }

    public function createSession($data) {
        $this->db->query('INSERT INTO class_sessions (course_id, room_id, specific_date, start_time, end_time, status) 
                          VALUES (:course_id, :room_id, :specific_date, :start_time, :end_time, "scheduled")');
        
        $this->db->bind(':course_id', $data['course_id']);
        $this->db->bind(':room_id', $data['room_id']);
        $this->db->bind(':specific_date', $data['specific_date']);
        $this->db->bind(':start_time', $data['start_time']);
        $this->db->bind(':end_time', $data['end_time']);

        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    public function getSessionsPaginated($courseId, $start, $length, $search, $columnName, $columnSortOrder) {
        // Map column names from DataTables to SQL
        $columnsMap = [
            'specific_date' => 'cs.specific_date',
            'start_time' => 'cs.start_time',
            'room_name' => 'r.name',
            'status' => 'cs.status'
        ];
        
        $orderBy = $columnsMap[$columnName] ?? 'cs.specific_date';
        
        // 1. Total records (without filtering)
        $this->db->query('SELECT COUNT(*) as total FROM class_sessions WHERE course_id = :cid');
        $this->db->bind(':cid', $courseId);
        $totalRecords = $this->db->single()->total;

        // 2. Base Query with Filtering
        $sql = "FROM class_sessions cs 
                LEFT JOIN rooms r ON cs.room_id = r.id 
                WHERE cs.course_id = :cid";
        
        $searchSql = "";
        if (!empty($search)) {
            $searchSql = " AND (cs.specific_date LIKE :search 
                           OR r.name LIKE :search 
                           OR cs.status LIKE :search)";
        }
        
        // 3. Records after filtering
        $this->db->query("SELECT COUNT(*) as total $sql $searchSql");
        $this->db->bind(':cid', $courseId);
        if (!empty($search)) $this->db->bind(':search', "%$search%");
        $totalFiltered = $this->db->single()->total;

        // 4. Final Query with Sorting and Pagination
        $this->db->query("SELECT cs.*, r.name as room_name $sql $searchSql 
                          ORDER BY $orderBy $columnSortOrder 
                          LIMIT :start, :length");
        
        $this->db->bind(':cid', $courseId);
        if (!empty($search)) $this->db->bind(':search', "%$search%");
        $this->db->bind(':start', (int)$start, PDO::PARAM_INT);
        $this->db->bind(':length', (int)$length, PDO::PARAM_INT);
        
        $rows = $this->db->resultSet();

        return [
            'total' => $totalRecords,
            'filtered' => $totalFiltered,
            'rows' => $rows
        ];
    }

    public function getAllCourseAttendanceLogs($courseId) {
        $this->db->query('SELECT al.student_id, al.session_id, al.status
                          FROM attendance_logs al
                          JOIN class_sessions cs ON al.session_id = cs.id
                          WHERE cs.course_id = :course_id');
        $this->db->bind(':course_id', $courseId);
        return $this->db->resultSet();
    }
}