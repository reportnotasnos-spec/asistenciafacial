<div class="container mt-4 mb-5">
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 font-weight-bold text-dark mb-1"><?php echo __('pe_title'); ?></h1>
                    <p class="text-muted mb-0"><?php echo __('pe_subtitle'); ?></p>
                </div>
                <a href="<?php echo URL_ROOT; ?>/profile" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                    <i class="fas fa-chevron-left mr-1"></i> <?php echo __('btn_cancel'); ?>
                </a>
            </div>

            <form action="<?php echo URL_ROOT; ?>/profile/edit" method="post">
                <div class="row">
                    <!-- Avatar Sidebar -->
                    <div class="col-md-4 mb-4">
                        <div class="card border-0 shadow-sm text-center py-5">
                            <div class="card-body">
                                <?php 
                                    $avatar = (!empty($data['details']->profile_picture_url)) 
                                        ? URL_ROOT . '/' . $data['details']->profile_picture_url 
                                        : 'https://ui-avatars.com/api/?name=' . urlencode($data['user']['name']) . '&size=150&background=random&bold=true';
                                ?>
                                <div class="position-relative d-inline-block mb-3">
                                    <img src="<?php echo $avatar; ?>" alt="Profile" class="img-fluid rounded-circle shadow-sm" style="width: 150px; height: 150px; border: 5px solid #fff;">
                                    <a href="<?php echo URL_ROOT; ?>/biometrics/register" class="position-absolute badge badge-primary rounded-circle p-2 border border-white" style="bottom: 10px; right: 10px; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;" title="Actualizar foto">
                                        <i class="fas fa-camera text-white"></i>
                                    </a>
                                </div>
                                <h5 class="font-weight-bold mb-0"><?php echo $data['user']['name']; ?></h5>
                                <p class="small text-muted text-uppercase mb-0"><?php echo $data['user']['role']; ?></p>
                                <div class="mt-2"><small class="text-muted"><?php echo __('pe_current_avatar'); ?></small></div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Fields -->
                    <div class="col-md-8">
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-body p-4">
                                
                                <?php if ($data['user']['role'] == 'student'): ?>
                                    <h6 class="font-weight-bold text-primary text-uppercase small mb-4">
                                        <i class="fas fa-graduation-cap mr-2"></i> <?php echo __('pe_academic_info'); ?>
                                    </h6>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label class="font-weight-bold small text-dark"><?php echo __('prof_lbl_student_id'); ?></label>
                                            <input type="text" name="student_id_number" class="form-control" value="<?php echo $data['details']->student_id_number ?? ''; ?>" placeholder="Ej. 20210001">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="font-weight-bold small text-dark"><?php echo __('prof_lbl_grade'); ?></label>
                                            <input type="text" name="grade_level" class="form-control" value="<?php echo $data['details']->grade_level ?? ''; ?>" placeholder="Ej. 7mo Semestre">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label class="font-weight-bold small text-dark"><?php echo __('prof_lbl_dob'); ?></label>
                                            <input type="date" name="date_of_birth" class="form-control" value="<?php echo $data['details']->date_of_birth ?? ''; ?>">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="font-weight-bold small text-dark"><?php echo __('prof_lbl_enrollment'); ?></label>
                                            <input type="date" name="enrollment_date" class="form-control" value="<?php echo $data['details']->enrollment_date ?? ''; ?>">
                                        </div>
                                    </div>

                                    <hr class="my-4">
                                    
                                    <h6 class="font-weight-bold text-primary text-uppercase small mb-4">
                                        <i class="fas fa-phone-alt mr-2"></i> <?php echo __('pe_contact_info'); ?>
                                    </h6>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label class="font-weight-bold small text-dark"><?php echo __('prof_lbl_emergency'); ?></label>
                                            <input type="text" name="emergency_contact_name" class="form-control" value="<?php echo $data['details']->emergency_contact_name ?? ''; ?>">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="font-weight-bold small text-dark"><?php echo __('pe_lbl_emergency_phone'); ?></label>
                                            <input type="text" name="emergency_contact_phone" class="form-control" value="<?php echo $data['details']->emergency_contact_phone ?? ''; ?>">
                                        </div>
                                    </div>

                                <?php elseif ($data['user']['role'] == 'teacher'): ?>
                                    <h6 class="font-weight-bold text-primary text-uppercase small mb-4">
                                        <i class="fas fa-briefcase mr-2"></i> <?php echo __('pe_professional_info'); ?>
                                    </h6>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label class="font-weight-bold small text-dark"><?php echo __('prof_lbl_employee_id'); ?></label>
                                            <input type="text" name="employee_id_number" class="form-control" value="<?php echo $data['details']->employee_id_number ?? ''; ?>">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="font-weight-bold small text-dark"><?php echo __('prof_lbl_department'); ?></label>
                                            <input type="text" name="department" class="form-control" value="<?php echo $data['details']->department ?? ''; ?>">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label class="font-weight-bold small text-dark"><?php echo __('prof_lbl_spec'); ?></label>
                                            <input type="text" name="specialization" class="form-control" value="<?php echo $data['details']->specialization ?? ''; ?>">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="font-weight-bold small text-dark"><?php echo __('pe_lbl_hire_date'); ?></label>
                                            <input type="date" name="hire_date" class="form-control" value="<?php echo $data['details']->hire_date ?? ''; ?>">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label class="font-weight-bold small text-dark"><?php echo __('prof_lbl_office'); ?></label>
                                            <input type="text" name="office_location" class="form-control" value="<?php echo $data['details']->office_location ?? ''; ?>">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="font-weight-bold small text-dark"><?php echo __('prof_lbl_phone'); ?></label>
                                            <input type="text" name="contact_phone" class="form-control" value="<?php echo $data['details']->contact_phone ?? ''; ?>">
                                        </div>
                                    </div>

                                    <hr class="my-4">
                                    
                                    <h6 class="font-weight-bold text-primary text-uppercase small mb-4">
                                        <i class="fas fa-id-card mr-2"></i> <?php echo __('pe_bio_info'); ?>
                                    </h6>
                                    <div class="form-group">
                                        <label class="font-weight-bold small text-dark"><?php echo __('pe_lbl_bio'); ?></label>
                                        <textarea name="bio" class="form-control" rows="4"><?php echo $data['details']->bio ?? ''; ?></textarea>
                                    </div>

                                <?php else: ?>
                                    <div class="text-center py-5">
                                        <i class="fas fa-user-shield fa-4x text-light mb-3"></i>
                                        <p class="text-muted"><?php echo __('pe_msg_admin'); ?></p>
                                    </div>
                                <?php endif; ?>

                                <?php if ($data['user']['role'] != 'admin'): ?>
                                    <div class="mt-5">
                                        <button type="submit" class="btn btn-primary btn-block btn-lg shadow-sm font-weight-bold rounded-pill">
                                            <i class="fas fa-save mr-2"></i> <?php echo __('pe_btn_update'); ?>
                                        </button>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
