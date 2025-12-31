<div class="mb-5">
<h2 class="mb-4 font-weight-bold text-dark"><?php echo __('um_title'); ?></h2>

<?php Session::flash('user_msg'); ?>

<!-- Tabs -->
<ul class="nav nav-tabs mb-4 border-bottom-0" id="userTabs" role="tablist">
  <li class="nav-item">
    <a class="nav-link active font-weight-bold px-4 py-3" id="students-tab" data-toggle="tab" href="#students" role="tab"><?php echo __('um_tab_students'); ?></a>
  </li>
  <li class="nav-item">
    <a class="nav-link font-weight-bold px-4 py-3" id="teachers-tab" data-toggle="tab" href="#teachers" role="tab"><?php echo __('um_tab_teachers'); ?></a>
  </li>
</ul>

<div class="tab-content card border-0 shadow-sm" id="userTabsContent">
  
  <!-- STUDENTS TAB -->
  <div class="tab-pane fade show active p-4" id="students" role="tabpanel">
      <div class="d-flex justify-content-between align-items-center mb-4">
          <h4 class="mb-0 font-weight-bold text-primary"><?php echo __('um_list_students'); ?></h4>
          <a href="<?php echo URL_ROOT; ?>/auth/register" class="btn btn-primary px-4 font-weight-bold shadow-sm rounded-pill">
              <i class="fas fa-plus mr-2"></i> <?php echo __('um_add_student'); ?>
          </a>
      </div>
      <div class="table-responsive">
          <table class="table table-hover mb-0 js-no-auto-datatable" id="studentsTable" style="width:100%">
              <thead class="bg-light text-center small text-uppercase font-weight-bold">
                  <tr>
                      <th class="py-3 px-4 text-left"><?php echo __('um_col_name'); ?></th>
                      <th class="py-3 text-left"><?php echo __('um_col_email'); ?></th>
                      <th class="py-3"><?php echo __('um_col_student_id'); ?></th>
                      <th class="py-3"><?php echo __('um_col_grade'); ?></th>
                      <th class="py-3"><?php echo __('um_col_actions'); ?></th>
                  </tr>
              </thead>
              <tbody>
                  <?php foreach($data['students'] as $s): ?>
                  <tr>
                      <td class="align-middle py-4 px-4 font-weight-bold text-dark"><?php echo $s->name; ?></td>
                      <td class="align-middle py-4 small font-weight-bold text-muted"><?php echo $s->email; ?></td>
                      <td class="align-middle py-4 text-center">
                          <span class="badge badge-light border px-3 py-2 font-weight-bold"><?php echo $s->student_id_number ?? __('um_not_set'); ?></span>
                      </td>
                      <td class="align-middle py-4 text-center font-weight-bold text-dark"><?php echo $s->grade_level ?? '-'; ?></td>
                      <td class="align-middle py-4 text-center">
                          <div class="btn-group shadow-sm">
                              <button class="btn btn-sm btn-info edit-user-btn" 
                                      data-id="<?php echo $s->id; ?>" 
                                      data-name="<?php echo $s->name; ?>" 
                                      data-email="<?php echo $s->email; ?>"
                                      data-role="student"
                                      data-toggle="tooltip" title="<?php echo __('um_tooltip_edit'); ?>">
                                  <i class="fas fa-edit"></i>
                              </button>
                              <button class="btn btn-sm btn-warning reset-pass-btn" 
                                      data-id="<?php echo $s->id; ?>" 
                                      data-role="student"
                                      data-toggle="tooltip" title="<?php echo __('um_tooltip_reset_pass'); ?>">
                                  <i class="fas fa-key"></i>
                              </button>
                              <button class="btn btn-sm btn-danger btn-delete-action" 
                                      data-url="<?php echo URL_ROOT; ?>/admin/userManage/delete/<?php echo $s->id; ?>"
                                      data-name="<?php echo $s->name; ?>"
                                      data-toggle="tooltip" title="<?php echo __('um_tooltip_delete'); ?>">
                                  <i class="fas fa-trash"></i>
                              </button>
                          </div>
                      </td>
                  </tr>
                  <?php endforeach; ?>
              </tbody>
          </table>
      </div>
  </div>

  <!-- TEACHERS TAB -->
  <div class="tab-pane fade p-4" id="teachers" role="tabpanel">
      <div class="d-flex justify-content-between align-items-center mb-4">
          <h4 class="mb-0 font-weight-bold text-primary"><?php echo __('um_list_teachers'); ?></h4>
          <a href="<?php echo URL_ROOT; ?>/auth/register" class="btn btn-primary px-4 font-weight-bold shadow-sm rounded-pill">
              <i class="fas fa-plus mr-2"></i> <?php echo __('um_add_teacher'); ?>
          </a>
      </div>
      <div class="table-responsive">
          <table class="table table-hover mb-0 js-no-auto-datatable" id="teachersTable" style="width:100%">
              <thead class="bg-light text-center small text-uppercase font-weight-bold">
                  <tr>
                      <th class="py-3 px-4 text-left"><?php echo __('um_col_name'); ?></th>
                      <th class="py-3 text-left"><?php echo __('um_col_email'); ?></th>
                      <th class="py-3"><?php echo __('um_col_employee_id'); ?></th>
                      <th class="py-3 text-left"><?php echo __('um_col_department'); ?></th>
                      <th class="py-3"><?php echo __('um_col_actions'); ?></th>
                  </tr>
              </thead>
              <tbody>
                  <?php foreach($data['teachers'] as $t): ?>
                  <tr>
                      <td class="align-middle py-4 px-4 font-weight-bold text-dark"><?php echo $t->name; ?></td>
                      <td class="align-middle py-4 small font-weight-bold text-muted"><?php echo $t->email; ?></td>
                      <td class="align-middle py-4 text-center">
                          <span class="badge badge-light border px-3 py-2 font-weight-bold"><?php echo $t->employee_id_number ?? __('um_not_set'); ?></span>
                      </td>
                      <td class="align-middle py-4 text-muted small font-weight-bold text-uppercase">
                          <i class="fas fa-building mr-1"></i> <?php echo $t->department ?? '-'; ?>
                      </td>
                      <td class="align-middle py-4 text-center">
                          <div class="btn-group shadow-sm">
                              <button class="btn btn-sm btn-info edit-user-btn" 
                                      data-id="<?php echo $t->id; ?>" 
                                      data-name="<?php echo $t->name; ?>" 
                                      data-email="<?php echo $t->email; ?>"
                                      data-role="teacher"
                                      data-toggle="tooltip" title="<?php echo __('um_tooltip_edit'); ?>">
                                  <i class="fas fa-edit"></i>
                              </button>
                              <button class="btn btn-sm btn-warning reset-pass-btn" 
                                      data-id="<?php echo $t->id; ?>" 
                                      data-role="teacher"
                                      data-toggle="tooltip" title="<?php echo __('um_tooltip_reset_pass'); ?>">
                                  <i class="fas fa-key"></i>
                              </button>
                              <button class="btn btn-sm btn-danger btn-delete-action" 
                                      data-url="<?php echo URL_ROOT; ?>/admin/userManage/delete/<?php echo $t->id; ?>"
                                      data-name="<?php echo $t->name; ?>"
                                      data-toggle="tooltip" title="<?php echo __('um_tooltip_delete'); ?>">
                                  <i class="fas fa-trash"></i>
                              </button>
                          </div>
                      </td>
                  </tr>
                  <?php endforeach; ?>
              </tbody>
          </table>
      </div>
  </div>
</div>

<!-- ================= MODALS ================= -->

<!-- Edit User Basic Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <form action="<?php echo URL_ROOT; ?>/admin/userManage/editBasic" method="post">
    <input type="hidden" name="id" id="edit_user_id">
    <input type="hidden" name="role" id="edit_user_role">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-info text-white">
        <h5 class="modal-title font-weight-bold"><i class="fas fa-user-edit mr-2"></i><?php echo __('um_modal_edit_title'); ?></h5>
        <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body py-4">
         <div class="form-group mb-3">
             <label class="font-weight-bold text-dark"><?php echo __('um_modal_edit_name'); ?></label>
             <input type="text" name="name" id="edit_user_name" class="form-control py-4" required>
         </div>
         <div class="form-group mb-3">
             <label class="font-weight-bold text-dark"><?php echo __('um_modal_edit_email'); ?></label>
             <input type="email" name="email" id="edit_user_email" class="form-control py-4" required>
         </div>
         <div class="alert alert-light border small text-muted mt-4">
            <i class="fas fa-info-circle mr-1"></i> <?php echo __('um_modal_edit_info'); ?>
         </div>
      </div>
      <div class="modal-footer border-top-0">
        <button type="button" class="btn btn-light px-4 font-weight-bold" data-dismiss="modal"><?php echo __('btn_cancel'); ?></button>
        <button type="submit" class="btn btn-primary px-4 font-weight-bold"><?php echo __('um_modal_edit_update'); ?></button>
      </div>
    </div>
    </form>
  </div>
</div>

<!-- Reset Password Modal -->
<div class="modal fade" id="resetPassModal" tabindex="-1" role="dialog">
  <div class="modal-dialog role="document">
    <form action="<?php echo URL_ROOT; ?>/admin/userManage/resetPassword" method="post">
    <input type="hidden" name="id" id="reset_user_id">
    <input type="hidden" name="role" id="reset_user_role">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-warning border-0">
        <h5 class="modal-title font-weight-bold"><i class="fas fa-key mr-2"></i> <?php echo __('um_modal_reset_title'); ?></h5>
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body py-4">
         <div class="text-center mb-4">
            <div class="bg-warning text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                <i class="fas fa-lock fa-2x"></i>
            </div>
            <p class="h6 font-weight-bold text-dark mb-1"><?php echo __('um_modal_reset_for'); ?></p>
            <p class="text-muted" id="reset_user_name_display"></p>
         </div>
         <div class="form-group">
             <label class="font-weight-bold text-dark small text-uppercase"><?php echo __('um_modal_reset_new'); ?></label>
             <input type="password" name="new_password" class="form-control py-4" placeholder="<?php echo __('um_modal_reset_ph'); ?>" required minlength="6">
         </div>
      </div>
      <div class="modal-footer border-top-0">
        <button type="button" class="btn btn-light px-4 font-weight-bold" data-dismiss="modal"><?php echo __('btn_cancel'); ?></button>
        <button type="submit" class="btn btn-warning px-4 font-weight-bold"><?php echo __('um_modal_reset_btn'); ?></button>
      </div>
    </div>
    </form>
  </div>
</div>

<!-- Delete Confirmation Modal (Reusable) -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-danger text-white border-0 py-3">
        <h5 class="modal-title font-weight-bold"><i class="fas fa-exclamation-triangle mr-2"></i> <?php echo __('um_modal_delete_title'); ?></h5>
        <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body py-5 text-center">
        <p class="mb-1 h5"><?php echo __('um_modal_delete_confirm'); ?> <strong id="deleteItemName" class="text-danger"></strong>?</p>
        <p class="text-muted mb-0 small font-weight-bold"><?php echo __('um_modal_delete_warning'); ?></p>
      </div>
      <div class="modal-footer border-0 justify-content-center pb-4">
        <button type="button" class="btn btn-light px-5 font-weight-bold rounded-pill" data-dismiss="modal"><?php echo __('btn_cancel'); ?></button>
        <a href="#" id="confirmDeleteBtn" class="btn btn-danger px-5 font-weight-bold shadow-sm rounded-pill"><?php echo __('um_modal_delete_btn'); ?></a>
      </div>
    </div>
  </div>
</div>

<script>
    window.addEventListener('load', function() {
        // --- DataTables Initialization ---
        const dtConfig = {
            pageLength: 10,
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
            responsive: true,
            dom: "<'row'<'col-sm-12 col-md-4'l><'col-sm-12 col-md-4'B><'col-sm-12 col-md-4'f>>" +
                 "<'row'<'col-sm-12'tr>>" +
                 "<'row'<'col-sm-12 col-md-5 small'i><'col-sm-12 col-md-7'p>>",
            buttons: [
                { extend: 'excelHtml5', text: '<i class="fas fa-file-excel"></i>', className: 'btn btn-success btn-sm px-3 shadow-sm', titleAttr: 'Export to Excel' },
                { extend: 'csvHtml5', text: '<i class="fas fa-file-csv"></i>', className: 'btn btn-info btn-sm px-3 shadow-sm', titleAttr: 'Export to CSV' },
                { extend: 'pdfHtml5', text: '<i class="fas fa-file-pdf"></i>', className: 'btn btn-danger btn-sm px-3 shadow-sm', titleAttr: 'Export to PDF' },
                { extend: 'print', text: '<i class="fas fa-print"></i>', className: 'btn btn-secondary btn-sm px-3 shadow-sm', titleAttr: 'Print Table' }
            ],
            language: { "url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json" }
        };

        if ($('#studentsTable').length > 0) $('#studentsTable').DataTable(dtConfig);
        if ($('#teachersTable').length > 0) $('#teachersTable').DataTable(dtConfig);

        // --- Tabs Persistence ---
        var hash = window.location.hash;
        if (hash) {
            $('.nav-link[href="' + hash + '"]').tab('show');
        }

        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            var newHash = $(e.target).attr('href');
            if(history.pushState) {
                history.pushState(null, null, newHash);
            } else {
                location.hash = newHash;
            }
            $.fn.dataTable.tables({visible: true, api: true}).columns.adjust();
        });

        // Edit User Populate
        $('.edit-user-btn').on('click', function() {
            $('#edit_user_id').val($(this).data('id'));
            $('#edit_user_name').val($(this).data('name'));
            $('#edit_user_email').val($(this).data('email'));
            $('#edit_user_role').val($(this).data('role'));
            $('#editUserModal').modal('show');
        });

        // Reset Pass Populate
        $('.reset-pass-btn').on('click', function() {
            $('#reset_user_id').val($(this).data('id'));
            $('#reset_user_role').val($(this).data('role'));
            $('#reset_user_name_display').text($(this).closest('tr').find('td:first').text());
            $('#resetPassModal').modal('show');
        });

        // Delete Confirmation
        $('.btn-delete-action').on('click', function() {
            var url = $(this).data('url');
            var name = $(this).data('name');
            $('#deleteItemName').text(name);
            $('#confirmDeleteBtn').attr('href', url);
            $('#deleteModal').modal('show');
        });
    });
</script>
</div>