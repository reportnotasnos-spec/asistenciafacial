<?php

class EnrollmentController extends Controller {
    private $enrollmentModel;
    private $courseModel;
    private $notificationModel;

    public function __construct() {
        if (!Session::isLoggedIn() || $_SESSION['user_role'] != 'admin') {
            header('location: ' . URL_ROOT . '/auth/login');
            exit;
        }
        $this->enrollmentModel = $this->model('Enrollment');
        $this->courseModel = $this->model('SubjectCourse');
        $this->notificationModel = $this->model('Notification');
    }

    public function course($courseId) {
        $course = $this->courseModel->getCourses(); // Filter manually for simplicity or add getCourseById to SubjectCourse
        $selectedCourse = null;
        foreach($course as $c) {
            if($c->id == $courseId) {
                $selectedCourse = $c;
                break;
            }
        }

        if (!$selectedCourse) {
            header('location: ' . URL_ROOT . '/admin/subjectCourse');
            exit;
        }

        $data = [
            'course' => $selectedCourse,
            'enrolled' => $this->enrollmentModel->getEnrolledStudents($courseId),
            'available' => $this->enrollmentModel->getAvailableStudents($courseId)
        ];

        $this->view('admin/subjects_courses/enroll', $data);
    }

    public function add($courseId, $studentId) {
        if ($this->enrollmentModel->enroll($courseId, $studentId)) {
            // Get Course Name for Notification
            $courses = $this->courseModel->getCourses();
            $courseName = "Course";
            foreach($courses as $c) {
                if ($c->id == $courseId) {
                    $courseName = $c->subject_name . ' (' . $c->group_name . ')';
                    break;
                }
            }

            $this->notificationModel->add(
                $studentId, 
                'Course Enrollment', 
                "You have been enrolled in $courseName.", 
                'success'
            );
            
            Session::flash('enroll_msg', 'Student enrolled');
        }
        header('location: ' . URL_ROOT . '/admin/enrollment/course/' . $courseId);
    }

    public function remove($courseId, $studentId) {
        if ($this->enrollmentModel->unenroll($courseId, $studentId)) {
             // Get Course Name for Notification
             $courses = $this->courseModel->getCourses();
             $courseName = "Course";
             foreach($courses as $c) {
                 if ($c->id == $courseId) {
                     $courseName = $c->subject_name . ' (' . $c->group_name . ')';
                     break;
                 }
             }

            $this->notificationModel->add(
                $studentId, 
                'Course Unenrollment', 
                "You have been removed from $courseName.", 
                'warning'
            );

            Session::flash('enroll_msg', 'Student removed from course');
        }
        header('location: ' . URL_ROOT . '/admin/enrollment/course/' . $courseId);
    }
}
