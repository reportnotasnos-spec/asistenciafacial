<?php

// Define your routes here

Route::get('', 'HomeController@index');
Route::get('/', 'HomeController@index');

// Auth Routes
Route::get('auth/register', 'AuthController@register');
Route::post('auth/register', 'AuthController@register');
Route::post('auth/import', 'AuthController@import');
Route::get('auth/template', 'AuthController@template');
Route::get('auth/login', 'AuthController@login');
Route::post('auth/login', 'AuthController@login');
Route::get('auth/logout', 'AuthController@logout');

// Language Route
Route::get('language/set/{lang}', 'LanguageController@set');

// User Profile Routes
Route::get('users/change-password', 'UserController@changePassword');
Route::post('users/change-password', 'UserController@changePassword');

Route::get('profile', 'ProfileController@index');
Route::get('profile/attendance/{id}', 'ProfileController@attendance');
Route::get('profile/edit', 'ProfileController@edit');
Route::post('profile/edit', 'ProfileController@edit');

Route::get('biometrics/register', 'BiometricController@register');
Route::post('biometrics/register', 'BiometricController@register');

// Attendance Routes
Route::get('attendance', 'AttendanceController@index');
Route::get('attendance/course/{id}', 'AttendanceController@course');
Route::get('attendance/session/{id}', 'AttendanceController@session');
Route::post('attendance/mark', 'AttendanceController@mark');

// Notification Routes
Route::get('notifications/fetch', 'NotificationController@fetch');
Route::post('notifications/markRead/{id}', 'NotificationController@markRead');
Route::post('notifications/markAllRead', 'NotificationController@markAllRead');

// Admin Routes
Route::get('admin', 'AdminController@index');
Route::get('admin/settings', 'AdminController@settings');
Route::post('admin/settings', 'AdminController@settings');

// Academic Management
Route::get('admin/academic', 'AcademicController@index');
// Programs
Route::post('admin/academic/addProgram', 'AcademicController@addProgram');
Route::post('admin/academic/editProgram', 'AcademicController@editProgram');
Route::get('admin/academic/deleteProgram/{id}', 'AcademicController@deleteProgram');
// Periods
Route::post('admin/academic/addPeriod', 'AcademicController@addPeriod');
Route::post('admin/academic/editPeriod', 'AcademicController@editPeriod');
Route::get('admin/academic/deletePeriod/{id}', 'AcademicController@deletePeriod');
// Rooms
Route::post('admin/academic/addRoom', 'AcademicController@addRoom');
Route::post('admin/academic/editRoom', 'AcademicController@editRoom');
Route::get('admin/academic/deleteRoom/{id}', 'AcademicController@deleteRoom');

// Subjects & Courses Management
Route::get('admin/subjectCourse', 'SubjectCourseController@index');
Route::post('admin/subjectCourse/addSubject', 'SubjectCourseController@addSubject');
Route::post('admin/subjectCourse/editSubject', 'SubjectCourseController@editSubject');
Route::get('admin/subjectCourse/deleteSubject/{id}', 'SubjectCourseController@deleteSubject');
Route::post('admin/subjectCourse/addCourse', 'SubjectCourseController@addCourse');
Route::post('admin/subjectCourse/editCourse', 'SubjectCourseController@editCourse');
Route::get('admin/subjectCourse/deleteCourse/{id}', 'SubjectCourseController@deleteCourse');

// Enrollments
Route::get('admin/enrollment/course/{id}', 'EnrollmentController@course');
Route::get('admin/enrollment/add/{course_id}/{student_id}', 'EnrollmentController@add');
Route::get('admin/enrollment/remove/{course_id}/{student_id}', 'EnrollmentController@remove');

// Scheduling
Route::get('admin/subjectCourse/schedule/{id}', 'SubjectCourseController@schedule');
Route::post('admin/subjectCourse/generateSchedule/{id}', 'SubjectCourseController@generateSchedule');
Route::post('admin/subjectCourse/addSession', 'SubjectCourseController@addSession');
Route::post('admin/subjectCourse/updateSession', 'SubjectCourseController@updateSession');
Route::get('admin/subjectCourse/deleteSession/{id}', 'SubjectCourseController@deleteSession');
Route::get('admin/subjectCourse/getSessionsDataTable/{id}', 'SubjectCourseController@getSessionsDataTable');

// User Management
Route::get('admin/userManage', 'UserManageController@index');
Route::post('admin/userManage/editBasic', 'UserManageController@editBasic');
Route::post('admin/userManage/resetPassword', 'UserManageController@resetPassword');
Route::get('admin/userManage/delete/{id}', 'UserManageController@delete');

// API REST Routes
Route::get('api/stats/student/{id}', 'ApiController@studentStats');
Route::get('api/schedule/today', 'ApiController@todaySchedule');

// Example with parameters:
// Route::get('/users/{id}', 'UsersController@show');

// Example with a closure:
// Route::get('/about', function() {
//     echo 'About Us page';
// });