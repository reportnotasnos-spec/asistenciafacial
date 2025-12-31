<div class="row">
    <div class="col-md-8 mx-auto">
        <!-- Main Register Card -->
        <?php Session::flash('register_success'); ?>
        <div class="card shadow-lg border-0 rounded-lg mt-4">
            <div class="card-header bg-primary text-white text-center py-4" style="background: linear-gradient(45deg, #007bff, #0056b3);">
                <i class="fas fa-user-plus fa-3x mb-2"></i>
                <h2 class="font-weight-bold"><?php echo __('reg_title'); ?></h2>
                <p class="mb-0 text-white-50"><?php echo __('reg_subtitle'); ?></p>
            </div>
            
            <div class="card-body p-5">
                <form action="<?php echo URL_ROOT; ?>/auth/register" method="post">
                    <?php echo csrf_field(); ?>
                    
                    <div class="form-row">
                        <!-- Name -->
                        <div class="col-md-6 form-group">
                            <label for="name" class="font-weight-bold text-muted small text-uppercase"><?php echo __('reg_name'); ?> <sup class="text-danger">*</sup></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light border-right-0"><i class="fas fa-user text-muted"></i></span>
                                </div>
                                <input type="text" name="name" autocomplete="name" autofocus class="form-control border-left-0 <?php echo (!empty($data['name_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['name']; ?>" placeholder="e.g. John Doe">
                                <div class="invalid-feedback ml-3"><?php echo $data['name_err']; ?></div>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="col-md-6 form-group">
                            <label for="email" class="font-weight-bold text-muted small text-uppercase"><?php echo __('auth_email'); ?> <sup class="text-danger">*</sup></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light border-right-0"><i class="fas fa-envelope text-muted"></i></span>
                                </div>
                                <input type="email" name="email" autocomplete="email" class="form-control border-left-0 <?php echo (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['email']; ?>" placeholder="e.g. john@example.com">
                                <div class="invalid-feedback ml-3"><?php echo $data['email_err']; ?></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <!-- Password -->
                        <div class="col-md-6 form-group">
                            <label for="password" class="font-weight-bold text-muted small text-uppercase"><?php echo __('auth_password'); ?> <sup class="text-danger">*</sup></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light border-right-0"><i class="fas fa-lock text-muted"></i></span>
                                </div>
                                <input type="password" name="password" autocomplete="new-password" class="form-control border-left-0 <?php echo (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['password']; ?>" placeholder="Min. 6 characters">
                                <div class="invalid-feedback ml-3"><?php echo $data['password_err']; ?></div>
                            </div>
                        </div>

                        <!-- Confirm Password -->
                        <div class="col-md-6 form-group">
                            <label for="confirm_password" class="font-weight-bold text-muted small text-uppercase"><?php echo __('reg_confirm_pass'); ?> <sup class="text-danger">*</sup></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light border-right-0"><i class="fas fa-check-circle text-muted"></i></span>
                                </div>
                                <input type="password" name="confirm_password" autocomplete="new-password" class="form-control border-left-0 <?php echo (!empty($data['confirm_password_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['confirm_password']; ?>" placeholder="Retype password">
                                <div class="invalid-feedback ml-3"><?php echo $data['confirm_password_err']; ?></div>
                            </div>
                        </div>
                    </div>

                    <!-- Role -->
                    <div class="form-group">
                        <label for="role" class="font-weight-bold text-muted small text-uppercase"><?php echo __('reg_role'); ?> <sup class="text-danger">*</sup></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-light border-right-0"><i class="fas fa-id-badge text-muted"></i></span>
                            </div>
                            <select name="role" class="form-control border-left-0 custom-select <?php echo (!empty($data['role_err'])) ? 'is-invalid' : ''; ?>">
                                <option value="student" <?php echo ($data['role'] == 'student') ? 'selected' : ''; ?>><?php echo __('reg_role_student'); ?></option>
                                <option value="teacher" <?php echo ($data['role'] == 'teacher') ? 'selected' : ''; ?>><?php echo __('reg_role_teacher'); ?></option>
                                <option value="admin" <?php echo ($data['role'] == 'admin') ? 'selected' : ''; ?>><?php echo __('reg_role_admin'); ?></option>
                            </select>
                            <div class="invalid-feedback ml-3"><?php echo $data['role_err']; ?></div>
                        </div>
                    </div>

                    <div class="mt-4 d-flex justify-content-between align-items-center">
                        <a href="<?php echo URL_ROOT; ?>" class="btn btn-secondary px-4 font-weight-bold shadow-sm">
                            <i class="fas fa-times mr-2"></i> <?php echo __('btn_cancel'); ?>
                        </a>
                        <button type="submit" class="btn btn-success px-5 shadow-sm font-weight-bold">
                            <i class="fas fa-save mr-2"></i> <?php echo __('reg_btn_create'); ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Bulk Import Section -->
        <div class="card shadow-sm border-0 rounded-lg mt-4 mb-5">
            <div class="card-body p-4 d-flex justify-content-between align-items-center bg-light rounded-lg">
                <div>
                    <h5 class="font-weight-bold text-dark mb-1"><i class="fas fa-file-csv text-info mr-2"></i><?php echo __('reg_bulk_title'); ?></h5>
                    <p class="mb-0 text-muted small"><?php echo __('reg_bulk_subtitle'); ?></p>
                </div>
                <button type="button" class="btn btn-outline-info font-weight-bold px-4" data-toggle="modal" data-target="#bulkImportModal">
                    <?php echo __('reg_btn_import'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Import Modal -->
<div class="modal fade" id="bulkImportModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header bg-info text-white border-0">
        <h5 class="modal-title font-weight-bold"><i class="fas fa-file-upload mr-2"></i><?php echo __('reg_modal_title'); ?></h5>
        <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-4 p-3 bg-light rounded">
                <span class="text-muted font-weight-bold small"><?php echo __('reg_no_format'); ?></span>
                <a href="<?php echo URL_ROOT; ?>/auth/template" class="btn btn-sm btn-secondary shadow-sm">
                    <i class="fas fa-download mr-1"></i> <?php echo __('reg_download_template'); ?>
                </a>
            </div>
            
            <p class="text-muted small mb-3"><?php echo __('reg_modal_desc'); ?></p>
            
            <form id="importForm" action="<?php echo URL_ROOT; ?>/auth/import" method="post" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="form-group mb-4">
                    <div class="custom-file">
                        <input type="file" name="file" class="custom-file-input" id="csvFile" accept=".csv" required>
                        <label class="custom-file-label text-truncate" for="csvFile"><?php echo __('reg_choose_file'); ?></label>
                    </div>
                </div>
                <div class="alert alert-warning small border-0 bg-warning-light text-dark">
                    <i class="fas fa-exclamation-circle mr-1"></i> <strong><?php echo __('reg_note'); ?></strong>
                </div>
      </div>
      <div class="modal-footer border-top-0 pt-0 pb-4 pr-4">
        <button type="button" class="btn btn-light font-weight-bold" data-dismiss="modal"><?php echo __('btn_cancel'); ?></button>
        <button type="submit" class="btn btn-info font-weight-bold px-4 shadow-sm"><?php echo __('reg_btn_start_import'); ?></button>
      </div>
      </form>
    </div>
  </div>
</div>

<!-- Import Results Modal -->
<?php if(isset($_SESSION['import_results'])): ?>
<div class="modal fade" id="importResultModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header border-0 <?php echo ($_SESSION['import_results']['failed'] > 0) ? 'bg-warning' : 'bg-success text-white'; ?>">
        <h5 class="modal-title font-weight-bold">
            <?php if($_SESSION['import_results']['failed'] > 0): ?>
                <i class="fas fa-exclamation-triangle mr-2"></i>Import Completed with Issues
            <?php else: ?>
                <i class="fas fa-check-circle mr-2"></i>Import Successful
            <?php endif; ?>
        </h5>
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body p-4">
        <div class="row text-center mb-4">
            <div class="col border-right">
                <h2 class="text-success display-4 font-weight-bold"><?php echo $_SESSION['import_results']['success']; ?></h2>
                <small class="text-uppercase text-muted font-weight-bold tracking-wide">Success</small>
            </div>
            <div class="col">
                <h2 class="text-danger display-4 font-weight-bold"><?php echo $_SESSION['import_results']['failed']; ?></h2>
                <small class="text-uppercase text-muted font-weight-bold tracking-wide">Failed</small>
            </div>
        </div>
        
        <?php if(!empty($_SESSION['import_results']['errors'])): ?>
            <div class="card bg-light border-0">
                <div class="card-header bg-transparent border-0 pb-0">
                    <h6 class="font-weight-bold text-danger mb-0">Error Log</h6>
                </div>
                <div class="card-body pt-2" style="max-height: 200px; overflow-y: auto;">
                    <ul class="list-unstyled mb-0 small">
                        <?php foreach($_SESSION['import_results']['errors'] as $err): ?>
                            <li class="text-danger mb-1"><i class="fas fa-times mr-2"></i><?php echo $err; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php endif; ?>
      </div>
      <div class="modal-footer border-0">
        <button type="button" class="btn btn-secondary font-weight-bold px-4 rounded-pill" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<?php 
    // Clear session after displaying
    unset($_SESSION['import_results']); 
?>
<?php endif; ?>

<script>
// Custom file input label change
document.querySelector('.custom-file-input').addEventListener('change', function(e) {
    var fileName = document.getElementById("csvFile").files[0].name;
    var nextSibling = e.target.nextElementSibling;
    nextSibling.innerText = fileName;
});

// Wait for the entire page (including jQuery) to load
window.addEventListener('load', function() {
    // Auto-show result modal if exists
    if ($('#importResultModal').length > 0) {
        $('#importResultModal').modal('show');
    }
});
</script>