<div class="row">
    <div class="col-lg-5 col-md-7 mx-auto">
        <!-- Success Message Placeholder -->
        <?php Session::flash('password_change_success'); ?>

        <div class="card shadow-lg border-0 rounded-lg mt-5">
            <div class="card-header bg-dark text-white text-center py-4">
                <div class="mb-3">
                    <span class="fa-stack fa-2x">
                        <i class="fas fa-circle fa-stack-2x text-secondary"></i>
                        <i class="fas fa-key fa-stack-1x fa-inverse"></i>
                    </span>
                </div>
                <h3 class="font-weight-bold mb-0"><?php echo __('cp_title'); ?></h3>
                <p class="mb-0 text-white-50 small"><?php echo __('cp_subtitle'); ?></p>
            </div>
            
            <div class="card-body p-5">
                <form action="<?php echo URL_ROOT; ?>/users/change-password" method="post">
                    <!-- CSRF Token (Best Practice) -->
                    <?php if(function_exists('csrf_field')) echo csrf_field(); ?>

                    <!-- Current Password -->
                    <div class="form-group mb-4">
                        <label for="current_password" class="font-weight-bold text-muted small text-uppercase"><?php echo __('cp_current'); ?></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-light border-right-0"><i class="fas fa-unlock text-muted"></i></span>
                            </div>
                            <input type="password" 
                                   name="current_password" 
                                   id="current_password"
                                   autocomplete="current-password"
                                   class="form-control border-left-0 py-2 <?php echo (!empty($data['current_password_err'])) ? 'is-invalid' : ''; ?>" 
                                   value="<?php echo $data['current_password']; ?>"
                                   autofocus>
                            <div class="invalid-feedback ml-3"><?php echo $data['current_password_err']; ?></div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- New Password -->
                    <div class="form-group">
                        <label for="new_password" class="font-weight-bold text-muted small text-uppercase"><?php echo __('cp_new'); ?></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-light border-right-0"><i class="fas fa-lock text-success"></i></span>
                            </div>
                            <input type="password" 
                                   name="new_password" 
                                   id="new_password"
                                   autocomplete="new-password"
                                   class="form-control border-left-0 py-2 <?php echo (!empty($data['new_password_err'])) ? 'is-invalid' : ''; ?>" 
                                   value="<?php echo $data['new_password']; ?>"
                                   placeholder="Min. 6 characters">
                            <div class="invalid-feedback ml-3"><?php echo $data['new_password_err']; ?></div>
                        </div>
                    </div>

                    <!-- Confirm New Password -->
                    <div class="form-group mb-4">
                        <label for="confirm_new_password" class="font-weight-bold text-muted small text-uppercase"><?php echo __('cp_confirm'); ?></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-light border-right-0"><i class="fas fa-check-circle text-success"></i></span>
                            </div>
                            <input type="password" 
                                   name="confirm_new_password" 
                                   id="confirm_new_password"
                                   autocomplete="new-password"
                                   class="form-control border-left-0 py-2 <?php echo (!empty($data['confirm_new_password_err'])) ? 'is-invalid' : ''; ?>" 
                                   value="<?php echo $data['confirm_new_password']; ?>"
                                   placeholder="Retype new password">
                            <div class="invalid-feedback ml-3"><?php echo $data['confirm_new_password_err']; ?></div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <a href="<?php echo URL_ROOT; ?>/profile" class="btn btn-link text-muted px-0 text-decoration-none font-weight-bold">
                            <i class="fas fa-arrow-left mr-1"></i> <?php echo __('cp_back_profile'); ?>
                        </a>
                        <button type="submit" class="btn btn-dark px-4 shadow-sm font-weight-bold rounded-pill">
                            <?php echo __('cp_btn_update'); ?> <i class="fas fa-shield-alt ml-2"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="text-center mt-3">
            <p class="small text-muted">
                <i class="fas fa-info-circle mr-1"></i> <?php echo __('cp_tip'); ?>
            </p>
        </div>
    </div>
</div>