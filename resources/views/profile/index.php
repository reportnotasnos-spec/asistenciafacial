<?php
// Profile View (Shared but optimized for Admin)
?>
<div class="container mt-4 mb-5">
    
    <!-- ADMIN DASHBOARD SECTION -->
    <?php if ($data['user']['role'] == 'admin'): ?>
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-lg overflow-hidden admin-header-card">
                <div class="card-body p-5 position-relative">
                    <div class="position-absolute" style="top: -20px; right: 20px; opacity: 0.15;">
                        <i class="fas fa-user-shield fa-10x"></i>
                    </div>
                    <div class="position-relative" style="z-index: 2;">
                        <h1 class="font-weight-bold display-4 mb-1"><?php echo __('prof_admin_portal'); ?></h1>
                        <p class="lead mb-0 font-weight-bold opacity-75"><?php echo __('prof_system_overview'); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Admin Quick Actions -->
    <div class="row mb-5">
        <div class="col-md-3">
            <a href="<?php echo URL_ROOT; ?>/admin" class="card border-0 shadow-sm hover-lift text-decoration-none h-100">
                <div class="card-body text-center py-4">
                    <div class="icon-circle bg-primary-light text-primary mb-3 mx-auto">
                        <i class="fas fa-tachometer-alt fa-2x"></i>
                    </div>
                    <h6 class="font-weight-bold text-dark"><?php echo __('prof_quick_dashboard'); ?></h6>
                    <p class="small text-muted mb-0"><?php echo __('prof_desc_dashboard'); ?></p>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="<?php echo URL_ROOT; ?>/auth/register" class="card border-0 shadow-sm hover-lift text-decoration-none h-100">
                <div class="card-body text-center py-4">
                    <div class="icon-circle bg-success-light text-success mb-3 mx-auto">
                        <i class="fas fa-user-plus fa-2x"></i>
                    </div>
                    <h6 class="font-weight-bold text-dark"><?php echo __('prof_quick_add_user'); ?></h6>
                    <p class="small text-muted mb-0"><?php echo __('prof_desc_add_user'); ?></p>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="<?php echo URL_ROOT; ?>/admin/settings" class="card border-0 shadow-sm hover-lift text-decoration-none h-100">
                <div class="card-body text-center py-4">
                    <div class="icon-circle bg-info-light text-info mb-3 mx-auto">
                        <i class="fas fa-cogs fa-2x"></i>
                    </div>
                    <h6 class="font-weight-bold text-dark"><?php echo __('prof_quick_settings'); ?></h6>
                    <p class="small text-muted mb-0"><?php echo __('prof_desc_settings'); ?></p>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="#" class="card border-0 shadow-sm hover-lift text-decoration-none h-100">
                <div class="card-body text-center py-4">
                    <div class="icon-circle bg-warning-light text-warning mb-3 mx-auto">
                        <i class="fas fa-file-alt fa-2x"></i>
                    </div>
                    <h6 class="font-weight-bold text-dark"><?php echo __('prof_quick_logs'); ?></h6>
                    <p class="small text-muted mb-0"><?php echo __('prof_desc_logs'); ?></p>
                </div>
            </a>
        </div>
    </div>
    <?php endif; ?>

    <!-- STUDENT DASHBOARD SECTION (Collapsible logic or kept distinct) -->
    <?php if ($data['user']['role'] == 'student'): ?>
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="d-flex align-items-center mb-3">
                    <h1 class="h3 font-weight-bold text-dark mb-0"><?php echo __('stud_dashboard'); ?></h1>
                    <span class="badge badge-primary ml-3 px-3 py-2 rounded-pill"><?php echo __('stud_academic_overview'); ?></span>
                </div>
            </div>
        </div>

        <!-- Biometric Alert -->
        <?php if (!$data['has_biometric']): ?>
            <div class="alert alert-danger shadow-sm border-0 mb-4 animate__animated animate__pulse animate__infinite" style="border-left: 5px solid #dc3545 !important;">
                <div class="row align-items-center py-2">
                    <div class="col-md-9">
                        <h5 class="alert-heading font-weight-bold mb-1"><i class="fas fa-exclamation-triangle mr-2"></i> <?php echo __('stud_bio_not_reg'); ?></h5>
                        <p class="mb-0 small font-weight-bold text-dark"><?php echo __('stud_bio_not_reg_desc'); ?></p>
                    </div>
                    <div class="col-md-3 text-md-right mt-3 mt-md-0">
                        <a href="<?php echo URL_ROOT; ?>/biometrics/register" class="btn btn-danger btn-sm px-4 font-weight-bold shadow-sm rounded-pill"><?php echo __('prof_biometric_register'); ?> <i class="fas fa-arrow-right ml-1"></i></a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- Quick Stats Row -->
        <div class="row mb-4">
             <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-primary text-white h-100">
                    <div class="card-body">
                         <h6 class="text-uppercase mb-2 small font-weight-bold opacity-75"><?php echo __('stud_enrolled_courses'); ?></h6>
                         <h2 class="mb-0 font-weight-bold"><?php echo $data['student_stats']['total_courses']; ?></h2>
                    </div>
                </div>
             </div>
             <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-info text-white h-100">
                     <div class="card-body">
                         <h6 class="text-uppercase mb-2 small font-weight-bold opacity-75"><?php echo __('stud_avg_attendance'); ?></h6>
                         <h2 class="mb-0 font-weight-bold"><?php echo $data['student_stats']['avg_attendance']; ?>%</h2>
                    </div>
                </div>
             </div>
        </div>
    <?php endif; ?>


    <!-- MAIN PROFILE CONTENT (Shared Layout) -->
    <div class="row">
        <!-- Sidebar: Identity Card -->
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-lg h-100 overflow-hidden">
                <div class="card-body text-center p-0">
                    <div class="bg-light py-5">
                         <?php 
                            $avatar = (!empty($data['details']->profile_picture_url)) 
                                ? URL_ROOT . '/' . $data['details']->profile_picture_url 
                                : 'https://ui-avatars.com/api/?name=' . urlencode($data['user']['name']) . '&size=200&background=random&bold=true';
                         ?>
                         <div class="position-relative d-inline-block">
                             <img src="<?php echo $avatar; ?>" alt="Profile" class="img-fluid rounded-circle shadow-sm" style="width: 150px; height: 150px; border: 5px solid #fff;">
                             <?php if($data['user']['role'] == 'admin'): ?>
                                <span class="position-absolute badge badge-danger rounded-circle p-2 border border-white" style="bottom: 10px; right: 10px; width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-crown text-white"></i>
                                </span>
                             <?php endif; ?>
                         </div>
                    </div>
                    <div class="p-4">
                        <h4 class="font-weight-bold text-dark mb-1"><?php echo $data['user']['name']; ?></h4>
                        <p class="text-muted mb-3 font-weight-bold small text-uppercase"><?php echo $data['user']['role']; ?></p>
                        
                        <div class="d-flex justify-content-center mb-4">
                            <a href="#" class="btn btn-sm btn-light rounded-circle mx-1"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="btn btn-sm btn-light rounded-circle mx-1"><i class="fab fa-linkedin-in"></i></a>
                            <a href="mailto:<?php echo $data['user']['email']; ?>" class="btn btn-sm btn-light rounded-circle mx-1"><i class="fas fa-envelope"></i></a>
                        </div>
                        
                        <div class="list-group list-group-flush text-left custom-list-group">
                            <a href="<?php echo URL_ROOT; ?>/profile/edit" class="list-group-item list-group-item-action border-0 rounded mb-2">
                                <i class="fas fa-user-edit mr-3 text-primary"></i> <?php echo __('prof_edit_profile'); ?>
                            </a>
                            <a href="<?php echo URL_ROOT; ?>/users/change-password" class="list-group-item list-group-item-action border-0 rounded mb-2">
                                <i class="fas fa-key mr-3 text-warning"></i> <?php echo __('nav_change_pass'); ?>
                            </a>
                            <?php if($data['user']['role'] != 'admin'): ?>
                            <a href="<?php echo URL_ROOT; ?>/biometrics/register" class="list-group-item list-group-item-action border-0 rounded mb-2">
                                <i class="fas fa-fingerprint mr-3 text-success"></i> <?php echo __('prof_biometric_register'); ?>
                            </a>
                            <?php endif; ?>
                            <a href="<?php echo URL_ROOT; ?>/auth/logout" class="list-group-item list-group-item-action border-0 rounded text-danger">
                                <i class="fas fa-sign-out-alt mr-3"></i> <?php echo __('nav_logout'); ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content: Details -->
        <div class="col-md-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <h5 class="font-weight-bold text-dark mb-0"><?php echo __('prof_personal_info'); ?></h5>
                </div>
                <div class="card-body pt-0">
                    <div class="form-row">
                        <div class="col-md-6 mb-4">
                            <label class="small text-muted font-weight-bold text-uppercase"><?php echo __('reg_name'); ?></label>
                            <div class="font-weight-bold text-dark border-bottom pb-2"><?php echo $data['user']['name']; ?></div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="small text-muted font-weight-bold text-uppercase"><?php echo __('prof_lbl_email'); ?></label>
                            <div class="font-weight-bold text-dark border-bottom pb-2"><?php echo $data['user']['email']; ?></div>
                        </div>
                    </div>

                    <!-- Role Specific Details -->
                    <?php if ($data['user']['role'] == 'student' && $data['details']): ?>
                        <h6 class="font-weight-bold text-primary mb-3 mt-2"><?php echo __('prof_academic_details'); ?></h6>
                        <div class="form-row">
                            <div class="col-md-6 mb-4">
                                <label class="small text-muted font-weight-bold text-uppercase"><?php echo __('prof_lbl_student_id'); ?></label>
                                <div class="font-weight-bold text-dark"><?php echo $data['details']->student_id_number ?? '-'; ?></div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="small text-muted font-weight-bold text-uppercase"><?php echo __('prof_lbl_grade'); ?></label>
                                <div class="font-weight-bold text-dark"><?php echo $data['details']->grade_level ?? '-'; ?></div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="small text-muted font-weight-bold text-uppercase"><?php echo __('prof_lbl_dob'); ?></label>
                                <div class="font-weight-bold text-dark"><?php echo $data['details']->date_of_birth ?? '-'; ?></div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="small text-muted font-weight-bold text-uppercase"><?php echo __('prof_lbl_emergency'); ?></label>
                                <div class="font-weight-bold text-dark"><?php echo $data['details']->emergency_contact_name ?? '-'; ?></div>
                            </div>
                        </div>
                    <?php elseif ($data['user']['role'] == 'teacher' && $data['details']): ?>
                        <h6 class="font-weight-bold text-primary mb-3 mt-2"><?php echo __('prof_professional_details'); ?></h6>
                        <div class="form-row">
                             <div class="col-md-6 mb-4">
                                <label class="small text-muted font-weight-bold text-uppercase"><?php echo __('prof_lbl_employee_id'); ?></label>
                                <div class="font-weight-bold text-dark"><?php echo $data['details']->employee_id_number ?? '-'; ?></div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="small text-muted font-weight-bold text-uppercase"><?php echo __('prof_lbl_department'); ?></label>
                                <div class="font-weight-bold text-dark"><?php echo $data['details']->department ?? '-'; ?></div>
                            </div>
                            <div class="col-md-12 mb-4">
                                <label class="small text-muted font-weight-bold text-uppercase"><?php echo __('prof_lbl_spec'); ?></label>
                                <div class="font-weight-bold text-dark bg-light p-3 rounded"><?php echo $data['details']->specialization ?? 'No bio provided.'; ?></div>
                            </div>
                        </div>
                    <?php elseif ($data['user']['role'] == 'admin'): ?>
                        <div class="alert alert-light border border-info text-info rounded p-3 mt-2">
                            <i class="fas fa-info-circle mr-2"></i> <?php echo __('prof_admin_info'); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Activity / Notifications for Non-Students (Students have it above) -->
            <?php if ($data['user']['role'] != 'student'): ?>
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 font-weight-bold text-dark"><?php echo __('prof_recent_activity'); ?></h5>
                </div>
                <div class="card-body p-0">
                    <!-- Mockup Activity for Admin/Teacher -->
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item py-3">
                            <div class="media">
                                <div class="mr-3 text-center" style="width: 40px;">
                                    <i class="fas fa-sign-in-alt text-success"></i>
                                </div>
                                <div class="media-body">
                                    <h6 class="mb-1 font-weight-bold text-dark"><?php echo __('prof_act_login'); ?></h6>
                                    <small class="text-muted"><?php echo __('prof_act_login_desc'); ?></small>
                                </div>
                                <small class="text-muted"><?php echo __('prof_act_just_now'); ?></small>
                            </div>
                        </li>
                        <li class="list-group-item py-3">
                            <div class="media">
                                <div class="mr-3 text-center" style="width: 40px;">
                                    <i class="fas fa-user-edit text-primary"></i>
                                </div>
                                <div class="media-body">
                                    <h6 class="mb-1 font-weight-bold text-dark"><?php echo __('prof_act_update'); ?></h6>
                                    <small class="text-muted"><?php echo __('prof_act_update_desc'); ?></small>
                                </div>
                                <small class="text-muted"><?php echo __('prof_act_2_days'); ?></small>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.hover-lift {
    transition: transform 0.2s, box-shadow 0.2s;
}
.hover-lift:hover {
    transform: translateY(-5px);
    box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
}
.icon-circle {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}
.custom-list-group .list-group-item:hover {
    background-color: #f8f9fa;
    color: #007bff;
}
</style>
