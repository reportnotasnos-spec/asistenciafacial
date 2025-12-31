<div class="row">
    <div class="col-md-5 mx-auto">
        
        <!-- Flash Messages (Register Success, etc) -->
        <?php Session::flash('register_success'); ?>
        <?php Session::flash('logout_success'); ?>
        <?php Session::flash('auth_error'); ?>

        <div class="card shadow-lg border-0 rounded-lg mt-5">
            <div class="card-header bg-primary text-white text-center py-4" style="background: linear-gradient(45deg, #007bff, #0056b3);">
                <div class="mb-3">
                    <span class="fa-stack fa-2x">
                        <i class="fas fa-circle fa-stack-2x text-white-50"></i>
                        <i class="fas fa-sign-in-alt fa-stack-1x text-primary"></i>
                    </span>
                </div>
                <h2 class="font-weight-bold mb-1"><?php echo __('auth_login_title'); ?></h2>
                <p class="mb-0 text-white-50 small"><?php echo __('auth_login_subtitle'); ?></p>
            </div>
            
            <div class="card-body p-5">
                <form action="<?php echo URL_ROOT; ?>/auth/login" method="post">
                    <?php echo csrf_field(); ?>

                    <!-- Email -->
                    <div class="form-group mb-4">
                        <label for="email" class="font-weight-bold text-muted small text-uppercase"><?php echo __('auth_email'); ?></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-light border-right-0"><i class="fas fa-envelope text-muted"></i></span>
                            </div>
                            <input type="email" 
                                   name="email" 
                                   autocomplete="email"
                                   autofocus
                                   class="form-control border-left-0 py-2 <?php echo (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>" 
                                   value="<?php echo $data['email']; ?>" 
                                   placeholder="<?php echo __('auth_email'); ?>">
                            <div class="invalid-feedback ml-3"><?php echo $data['email_err']; ?></div>
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="form-group mb-4">
                        <label for="password" class="font-weight-bold text-muted small text-uppercase"><?php echo __('auth_password'); ?></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-light border-right-0"><i class="fas fa-lock text-muted"></i></span>
                            </div>
                            <input type="password" 
                                   name="password" 
                                   autocomplete="current-password"
                                   class="form-control border-left-0 py-2 <?php echo (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>" 
                                   value="<?php echo $data['password']; ?>" 
                                   placeholder="<?php echo __('auth_password'); ?>">
                            <div class="invalid-feedback ml-3"><?php echo $data['password_err']; ?></div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="mt-5">
                        <button type="submit" class="btn btn-primary btn-lg btn-block shadow-sm font-weight-bold rounded-pill">
                            <?php echo __('auth_btn_login'); ?> <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </div>

                    <div class="text-center mt-4">
                        <a href="<?php echo URL_ROOT; ?>" class="text-decoration-none text-muted small">
                            <i class="fas fa-home mr-1"></i> <?php echo __('auth_back_home'); ?>
                        </a>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="text-center mt-4">
            <p class="text-muted small">
                <?php echo __('auth_no_account'); ?> 
                <a href="#" class="font-weight-bold text-primary ml-1 disabled" title="Contact Admin"><?php echo __('auth_contact_admin'); ?></a>
            </p>
        </div>
    </div>
</div>