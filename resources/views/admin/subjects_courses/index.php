<div class="mb-5">
<h2 class="mb-4 font-weight-bold text-dark"><?php echo __('sc_title'); ?></h2>

<!-- Tabs -->
<ul class="nav nav-tabs mb-4 border-bottom-0" id="scTabs" role="tablist">
  <li class="nav-item">
    <a class="nav-link active font-weight-bold px-4 py-3" id="subjects-tab" data-toggle="tab" href="#subjects" role="tab"><?php echo __('sc_tab_subjects'); ?></a>
  </li>
  <li class="nav-item">
    <a class="nav-link font-weight-bold px-4 py-3" id="courses-tab" data-toggle="tab" href="#courses" role="tab"><?php echo __('sc_tab_courses'); ?></a>
  </li>
</ul>

<div class="tab-content card border-0 shadow-sm" id="scTabsContent">
  
  <!-- SUBJECTS TAB -->
  <div class="tab-pane fade show active p-4" id="subjects" role="tabpanel">
      <?php Session::flash('subject_msg'); ?>
      <div class="d-flex justify-content-between align-items-center mb-4">
          <h4 class="mb-0 font-weight-bold text-primary"><?php echo __('sc_tab_subjects'); ?></h4>
          <button class="btn btn-primary px-4 font-weight-bold shadow-sm rounded-pill" data-toggle="modal" data-target="#addSubjectModal">
              <i class="fas fa-plus mr-2"></i> <?php echo __('sc_btn_add_subject'); ?>
          </button>
      </div>
      <div class="table-responsive">
          <table class="table table-hover mb-0 js-no-auto-datatable" id="subjectsTable" style="width:100%">
              <thead class="bg-light text-center small text-uppercase font-weight-bold">
                  <tr>
                      <th class="py-3 px-4 text-left"><?php echo __('sc_col_program'); ?></th>
                      <th class="py-3 text-left"><?php echo __('um_col_name'); ?></th>
                      <th class="py-3"><?php echo __('am_col_code'); ?></th>
                      <th class="py-3"><?php echo __('sc_col_credits'); ?></th>
                      <th class="py-3"><?php echo __('um_col_actions'); ?></th>
                  </tr>
              </thead>
              <tbody>
                  <?php foreach($data['subjects'] as $s): ?>
                  <tr>
                      <td class="align-middle py-4 px-4"><small class="text-muted font-weight-bold"><?php echo $s->program_name; ?></small></td>
                      <td class="align-middle py-4 font-weight-bold text-dark"><?php echo $s->name; ?></td>
                      <td class="align-middle py-4 text-center">
                          <span class="badge badge-pill badge-info px-3 py-2 font-weight-bold"><?php echo $s->code; ?></span>
                      </td>
                      <td class="align-middle py-4 text-center">
                          <span class="badge badge-light border px-2 py-1 font-weight-bold"><?php echo $s->credits; ?> cr.</span>
                      </td>
                      <td class="align-middle py-4 text-center">
                          <div class="btn-group shadow-sm">
                              <button class="btn btn-sm btn-info edit-subject-btn" 
                                      data-id="<?php echo $s->id; ?>" 
                                      data-program="<?php echo $s->program_id; ?>" 
                                      data-name="<?php echo $s->name; ?>" 
                                      data-code="<?php echo $s->code; ?>" 
                                      data-credits="<?php echo $s->credits; ?>"
                                      data-toggle="tooltip" title="<?php echo __('sc_tooltip_edit_subject'); ?>">
                                  <i class="fas fa-edit"></i>
                              </button>
                              <button class="btn btn-sm btn-danger btn-delete-action" 
                                      data-url="<?php echo URL_ROOT; ?>/admin/subjectCourse/deleteSubject/<?php echo $s->id; ?>"
                                      data-name="<?php echo $s->name; ?>"
                                      data-toggle="tooltip" title="<?php echo __('sc_tooltip_delete_subject'); ?>">
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

  <!-- COURSES TAB -->
  <div class="tab-pane fade p-4" id="courses" role="tabpanel">
      <?php Session::flash('course_msg'); ?>
      <div class="d-flex justify-content-between align-items-center mb-4">
          <h4 class="mb-0 font-weight-bold text-primary"><?php echo __('sc_tab_courses'); ?></h4>
          <button class="btn btn-primary px-4 font-weight-bold shadow-sm rounded-pill" data-toggle="modal" data-target="#addCourseModal">
              <i class="fas fa-plus mr-2"></i> <?php echo __('sc_btn_open_course'); ?>
          </button>
      </div>
      <div class="table-responsive">
          <table class="table table-hover mb-0 js-no-auto-datatable" id="coursesTable" style="width:100%">
              <thead class="bg-light text-center small text-uppercase font-weight-bold">
                  <tr>
                      <th class="py-3 px-4 text-left"><?php echo __('am_tab_periods'); ?></th>
                      <th class="py-3 text-left"><?php echo __('sc_col_sub_group'); ?></th>
                      <th class="py-3 text-left"><?php echo __('sc_col_teacher'); ?></th>
                      <th class="py-3"><?php echo __('um_col_actions'); ?></th>
                  </tr>
              </thead>
              <tbody>
                  <?php foreach($data['courses'] as $c): ?>
                  <tr>
                      <td class="align-middle py-4 px-4 small font-weight-bold text-muted"><?php echo $c->period_name; ?></td>
                      <td class="align-middle py-4">
                          <div class="font-weight-bold text-dark mb-1"><?php echo $c->subject_name; ?></div>
                          <span class="badge badge-pill badge-secondary px-3 py-1 text-uppercase x-small font-weight-bold"><?php echo __('sc_lbl_group_name'); ?> <?php echo $c->group_name; ?></span>
                      </td>
                      <td class="align-middle py-4 small font-weight-bold text-muted">
                          <i class="fas fa-chalkboard-teacher mr-1 text-info"></i> <?php echo $c->teacher_name; ?>
                      </td>
                      <td class="align-middle py-4 text-center">
                          <div class="btn-group shadow-sm">
                              <a href="<?php echo URL_ROOT; ?>/admin/enrollment/course/<?php echo $c->id; ?>" class="btn btn-sm btn-outline-primary" data-toggle="tooltip" title="<?php echo __('sc_tooltip_enroll'); ?>">
                                  <i class="fas fa-user-plus"></i>
                              </a>
                              <a href="<?php echo URL_ROOT; ?>/admin/subjectCourse/schedule/<?php echo $c->id; ?>" class="btn btn-sm btn-outline-success" data-toggle="tooltip" title="<?php echo __('sc_tooltip_schedule'); ?>">
                                  <i class="fas fa-calendar-alt"></i>
                              </a>
                              <button class="btn btn-sm btn-info edit-course-btn" 
                                      data-id="<?php echo $c->id; ?>" 
                                      data-subject="<?php echo $c->subject_id; ?>" 
                                      data-period="<?php echo $c->period_id; ?>" 
                                      data-teacher="<?php echo $c->teacher_id; ?>"
                                      data-group="<?php echo $c->group_name; ?>"
                                      data-toggle="tooltip" title="<?php echo __('sc_tooltip_edit_course'); ?>">
                                  <i class="fas fa-edit"></i>
                              </button>
                              <button class="btn btn-sm btn-danger btn-delete-action" 
                                      data-url="<?php echo URL_ROOT; ?>/admin/subjectCourse/deleteCourse/<?php echo $c->id; ?>"
                                      data-name="<?php echo $c->subject_name . ' (' . $c->group_name . ')'; ?>"
                                      data-toggle="tooltip" title="<?php echo __('sc_tooltip_delete_course'); ?>">
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

<!-- Add Subject Modal -->
<div class="modal fade" id="addSubjectModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <form action="<?php echo URL_ROOT; ?>/admin/subjectCourse/addSubject" method="post">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title font-weight-bold"><i class="fas fa-plus mr-2"></i><?php echo __('sc_modal_add_subject'); ?></h5>
        <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body py-4">
         <div class="form-group mb-3">
             <label class="font-weight-bold"><?php echo __('am_tab_programs'); ?></label>
             <select name="program_id" class="form-control" required>
                 <?php foreach($data['programs'] as $p): ?>
                    <option value="<?php echo $p->id; ?>"><?php echo $p->name; ?></option>
                 <?php endforeach; ?>
             </select>
         </div>
         <div class="form-group mb-3">
             <label class="font-weight-bold"><?php echo __('sc_lbl_sub_name'); ?></label>
             <input type="text" name="name" class="form-control py-4" placeholder="e.g. Artificial Intelligence" required>
         </div>
         <div class="row">
             <div class="col">
                <div class="form-group">
                    <label class="font-weight-bold"><?php echo __('am_col_code'); ?></label>
                    <input type="text" name="code" class="form-control py-4" placeholder="AI-401" required>
                </div>
             </div>
             <div class="col">
                <div class="form-group">
                    <label class="font-weight-bold"><?php echo __('sc_lbl_credits'); ?></label>
                    <input type="number" name="credits" class="form-control py-4" value="3" required>
                </div>
             </div>
         </div>
      </div>
      <div class="modal-footer border-top-0">
        <button type="button" class="btn btn-light px-4 font-weight-bold" data-dismiss="modal"><?php echo __('btn_cancel'); ?></button>
        <button type="submit" class="btn btn-primary px-4 font-weight-bold"><?php echo __('sc_btn_save_subject'); ?></button>
      </div>
    </div>
    </form>
  </div>
</div>

<!-- Edit Subject Modal -->
<div class="modal fade" id="editSubjectModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <form action="<?php echo URL_ROOT; ?>/admin/subjectCourse/editSubject" method="post">
    <input type="hidden" name="id" id="edit_subject_id">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-info text-white">
        <h5 class="modal-title font-weight-bold"><i class="fas fa-edit mr-2"></i><?php echo __('sc_modal_edit_subject'); ?></h5>
        <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body py-4">
         <div class="form-group mb-3">
             <label class="font-weight-bold"><?php echo __('am_tab_programs'); ?></label>
             <select name="program_id" id="edit_subject_program" class="form-control" required>
                 <?php foreach($data['programs'] as $p): ?>
                    <option value="<?php echo $p->id; ?>"><?php echo $p->name; ?></option>
                 <?php endforeach; ?>
             </select>
         </div>
         <div class="form-group mb-3">
             <label class="font-weight-bold"><?php echo __('sc_lbl_sub_name'); ?></label>
             <input type="text" name="name" id="edit_subject_name" class="form-control py-4" required>
         </div>
         <div class="row">
             <div class="col">
                <div class="form-group">
                    <label class="font-weight-bold"><?php echo __('am_col_code'); ?></label>
                    <input type="text" name="code" id="edit_subject_code" class="form-control py-4" required>
                </div>
             </div>
             <div class="col">
                <div class="form-group">
                    <label class="font-weight-bold"><?php echo __('sc_lbl_credits'); ?></label>
                    <input type="number" name="credits" id="edit_subject_credits" class="form-control py-4" required>
                </div>
             </div>
         </div>
      </div>
      <div class="modal-footer border-top-0">
        <button type="button" class="btn btn-light px-4 font-weight-bold" data-dismiss="modal"><?php echo __('btn_cancel'); ?></button>
        <button type="submit" class="btn btn-primary px-4 font-weight-bold"><?php echo __('sc_btn_update_subject'); ?></button>
      </div>
    </div>
    </form>
  </div>
</div>

<!-- Add Course Modal -->
<div class="modal fade" id="addCourseModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <form action="<?php echo URL_ROOT; ?>/admin/subjectCourse/addCourse" method="post">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title font-weight-bold"><i class="fas fa-graduation-cap mr-2"></i><?php echo __('sc_modal_add_course'); ?></h5>
        <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body py-4">
         <div class="form-group mb-3">
             <label class="font-weight-bold"><?php echo __('ad_table_subject'); ?></label>
             <select name="subject_id" class="form-control" required>
                 <?php foreach($data['subjects'] as $s): ?>
                    <option value="<?php echo $s->id; ?>"><?php echo $s->name; ?> (<?php echo $s->program_name; ?>)</option>
                 <?php endforeach; ?>
             </select>
         </div>
         <div class="form-group mb-3">
             <label class="font-weight-bold"><?php echo __('am_tab_periods'); ?></label>
             <select name="period_id" class="form-control" required>
                 <?php foreach($data['periods'] as $per): ?>
                    <option value="<?php echo $per->id; ?>" <?php echo $per->is_active ? 'selected' : ''; ?>>
                        <?php echo $per->name; ?>
                    </option>
                 <?php endforeach; ?>
             </select>
         </div>
         <div class="form-group mb-3">
             <label class="font-weight-bold"><?php echo __('sc_lbl_assign_teacher'); ?></label>
             <select name="teacher_id" class="form-control" required>
                 <?php foreach($data['teachers'] as $t): ?>
                    <option value="<?php echo $t->id; ?>"><?php echo $t->name; ?></option>
                 <?php endforeach; ?>
             </select>
         </div>
         <div class="form-group">
             <label class="font-weight-bold"><?php echo __('sc_lbl_group_name'); ?></label>
             <input type="text" name="group_name" class="form-control py-4" placeholder="e.g. Group A" required>
         </div>
      </div>
      <div class="modal-footer border-top-0">
        <button type="button" class="btn btn-light px-4 font-weight-bold" data-dismiss="modal"><?php echo __('btn_cancel'); ?></button>
        <button type="submit" class="btn btn-primary px-4 font-weight-bold"><?php echo __('sc_btn_create_instance'); ?></button>
      </div>
    </div>
    </form>
  </div>
</div>

<!-- Edit Course Modal -->
<div class="modal fade" id="editCourseModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <form action="<?php echo URL_ROOT; ?>/admin/subjectCourse/editCourse" method="post">
    <input type="hidden" name="id" id="edit_course_id">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-info text-white">
        <h5 class="modal-title font-weight-bold"><i class="fas fa-edit mr-2"></i><?php echo __('sc_modal_edit_course'); ?></h5>
        <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body py-4">
         <div class="form-group mb-3">
             <label class="font-weight-bold"><?php echo __('ad_table_subject'); ?></label>
             <select name="subject_id" id="edit_course_subject" class="form-control" required>
                 <?php foreach($data['subjects'] as $s): ?>
                    <option value="<?php echo $s->id; ?>"><?php echo $s->name; ?></option>
                 <?php endforeach; ?>
             </select>
         </div>
         <div class="form-group mb-3">
             <label class="font-weight-bold"><?php echo __('am_tab_periods'); ?></label>
             <select name="period_id" id="edit_course_period" class="form-control" required>
                 <?php foreach($data['periods'] as $per): ?>
                    <option value="<?php echo $per->id; ?>"><?php echo $per->name; ?></option>
                 <?php endforeach; ?>
             </select>
         </div>
         <div class="form-group mb-3">
             <label class="font-weight-bold"><?php echo __('sc_lbl_assign_teacher'); ?></label>
             <select name="teacher_id" id="edit_course_teacher" class="form-control" required>
                 <?php foreach($data['teachers'] as $t): ?>
                    <option value="<?php echo $t->id; ?>"><?php echo $t->name; ?></option>
                 <?php endforeach; ?>
             </select>
         </div>
         <div class="form-group">
             <label class="font-weight-bold"><?php echo __('sc_lbl_group_name'); ?></label>
             <input type="text" name="group_name" id="edit_course_group" class="form-control py-4" required>
         </div>
      </div>
      <div class="modal-footer border-top-0">
        <button type="button" class="btn btn-light px-4 font-weight-bold" data-dismiss="modal"><?php echo __('btn_cancel'); ?></button>
        <button type="submit" class="btn btn-primary px-4 font-weight-bold"><?php echo __('sc_btn_update_instance'); ?></button>
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
        <p class="text-muted mb-0 small font-weight-bold"><?php echo __('sc_modal_delete_warning'); ?></p>
      </div>
      <div class="modal-footer border-0 justify-content-center pb-4">
        <button type="button" class="btn btn-light px-5 font-weight-bold rounded-pill" data-dismiss="modal"><?php echo __('btn_cancel'); ?></button>
        <a href="#" id="confirmDeleteBtn" class="btn btn-danger px-5 font-weight-bold shadow-sm rounded-pill"><?php echo __('am_btn_delete_forever'); ?></a>
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

        if ($('#subjectsTable').length > 0) $('#subjectsTable').DataTable(dtConfig);
        if ($('#coursesTable').length > 0) $('#coursesTable').DataTable(dtConfig);

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

        // Edit Subject Populate
        $('.edit-subject-btn').on('click', function() {
            $('#edit_subject_id').val($(this).data('id'));
            $('#edit_subject_program').val($(this).data('program'));
            $('#edit_subject_name').val($(this).data('name'));
            $('#edit_subject_code').val($(this).data('code'));
            $('#edit_subject_credits').val($(this).data('credits'));
            $('#editSubjectModal').modal('show');
        });

        // Edit Course Populate
        $('.edit-course-btn').on('click', function() {
            $('#edit_course_id').val($(this).data('id'));
            $('#edit_course_subject').val($(this).data('subject'));
            $('#edit_course_period').val($(this).data('period'));
            $('#edit_course_teacher').val($(this).data('teacher'));
            $('#edit_course_group').val($(this).data('group'));
            $('#editCourseModal').modal('show');
        });

        // Delete Confirmation
        $('.btn-delete-action').on('click', function() {
            var url = $(this).data('url');
            var name = $(this).data('name');
            $('#deleteItemName').text(name);
            $('#confirmDeleteBtn').attr('href', url);
            $('#deleteModal').modal('show');
        });

        // Generic AJAX Form Submission for "Add" Modals
        function handleAjaxForm(modalId, formSelector, successMessage) {
            $(formSelector).on('submit', function(e) {
                e.preventDefault();
                var $form = $(this);
                var $btn = $form.find('button[type="submit"]');
                var originalBtnText = $btn.text();

                $btn.prop('disabled', true).text('<?php echo __('am_msg_saving'); ?>');

                $.ajax({
                    url: $form.attr('action'),
                    type: 'POST',
                    data: $form.serialize(),
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            var $msg = $('<div class="alert alert-success mt-3 py-2 font-weight-bold small">' + (response.message || successMessage) + '</div>');
                            $form.find('.alert').remove();
                            $form.find('.modal-body').prepend($msg);
                            setTimeout(function(){ $msg.fadeOut(function(){$(this).remove()}); }, 2000);

                            $form[0].reset();
                            $form.find('input:visible:first').focus();
                            $(modalId).data('reload-on-close', true);
                        } else {
                            alert(response.message || 'Error occurred');
                        }
                    },
                    error: function() { alert('Server error occurred'); },
                    complete: function() { $btn.prop('disabled', false).text(originalBtnText); }
                });
            });

            $(modalId).on('hidden.bs.modal', function () {
                if ($(this).data('reload-on-close')) {
                    location.reload();
                }
            });
        }

        handleAjaxForm('#addCourseModal', '#addCourseModal form', '<?php echo __('sc_msg_course_created'); ?>');
    });
</script>
</div>