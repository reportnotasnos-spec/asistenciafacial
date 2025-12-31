<div class="mb-5">
<h2 class="mb-4 font-weight-bold text-dark"><?php echo __('am_title'); ?></h2>

<?php Session::flash('academic_msg'); ?>

<!-- Tabs -->
<ul class="nav nav-tabs mb-4 border-bottom-0" id="academicTabs" role="tablist">
  <li class="nav-item">
    <a class="nav-link active font-weight-bold px-4 py-3" id="programs-tab" data-toggle="tab" href="#programs" role="tab"><?php echo __('am_tab_programs'); ?></a>
  </li>
  <li class="nav-item">
    <a class="nav-link font-weight-bold px-4 py-3" id="periods-tab" data-toggle="tab" href="#periods" role="tab"><?php echo __('am_tab_periods'); ?></a>
  </li>
  <li class="nav-item">
    <a class="nav-link font-weight-bold px-4 py-3" id="rooms-tab" data-toggle="tab" href="#rooms" role="tab"><?php echo __('am_tab_rooms'); ?></a>
  </li>
</ul>

<div class="tab-content card border-0 shadow-sm" id="academicTabsContent">
  
  <!-- PROGRAMS TAB -->
  <div class="tab-pane fade show active p-4" id="programs" role="tabpanel">
      <div class="d-flex justify-content-between align-items-center mb-4">
          <h4 class="mb-0 font-weight-bold text-primary"><?php echo __('am_tab_programs'); ?></h4>
          <button class="btn btn-primary px-4 font-weight-bold shadow-sm rounded-pill" data-toggle="modal" data-target="#addProgramModal">
              <i class="fas fa-plus mr-2"></i> <?php echo __('am_btn_add_program'); ?>
          </button>
      </div>
      <div class="table-responsive">
          <table class="table table-hover mb-0 js-no-auto-datatable" id="programsTable" style="width:100%">
              <thead class="bg-light text-center small text-uppercase font-weight-bold">
                  <tr>
                      <th class="py-3 px-4 text-left"><?php echo __('um_col_name'); ?></th>
                      <th class="py-3"><?php echo __('am_col_code'); ?></th>
                      <th class="py-3"><?php echo __('um_col_actions'); ?></th>
                  </tr>
              </thead>
              <tbody>
                  <?php foreach($data['programs'] as $p): ?>
                  <tr>
                      <td class="align-middle py-4 px-4 font-weight-bold text-dark"><?php echo $p->name; ?></td>
                      <td class="align-middle py-4 text-center">
                          <span class="badge badge-pill badge-info px-3 py-2"><?php echo $p->code; ?></span>
                      </td>
                      <td class="align-middle py-4 text-center">
                          <div class="btn-group shadow-sm">
                              <button class="btn btn-sm btn-info edit-program-btn" 
                                      data-id="<?php echo $p->id; ?>" 
                                      data-name="<?php echo $p->name; ?>" 
                                      data-code="<?php echo $p->code; ?>"
                                      data-toggle="tooltip" title="<?php echo __('am_tooltip_edit_program'); ?>">
                                  <i class="fas fa-edit"></i>
                              </button>
                              <button class="btn btn-sm btn-danger btn-delete-action" 
                                      data-url="<?php echo URL_ROOT; ?>/admin/academic/deleteProgram/<?php echo $p->id; ?>"
                                      data-name="<?php echo $p->name; ?>"
                                      data-toggle="tooltip" title="<?php echo __('am_tooltip_delete_program'); ?>">
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

  <!-- PERIODS TAB -->
  <div class="tab-pane fade p-4" id="periods" role="tabpanel">
      <div class="d-flex justify-content-between align-items-center mb-4">
          <h4 class="mb-0 font-weight-bold text-primary"><?php echo __('am_tab_periods'); ?></h4>
          <button class="btn btn-primary px-4 font-weight-bold shadow-sm rounded-pill" data-toggle="modal" data-target="#addPeriodModal">
              <i class="fas fa-plus mr-2"></i> <?php echo __('am_btn_add_period'); ?>
          </button>
      </div>
      <div class="table-responsive">
          <table class="table table-hover mb-0 js-no-auto-datatable" id="periodsTable" style="width:100%">
              <thead class="bg-light text-center small text-uppercase font-weight-bold">
                  <tr>
                      <th class="py-3 px-4 text-left"><?php echo __('um_col_name'); ?></th>
                      <th class="py-3"><?php echo __('am_col_dates'); ?></th>
                      <th class="py-3 text-center"><?php echo __('ad_table_status'); ?></th>
                      <th class="py-3"><?php echo __('um_col_actions'); ?></th>
                  </tr>
              </thead>
              <tbody>
                  <?php foreach($data['periods'] as $per): ?>
                  <tr class="<?php echo $per->is_active ? 'bg-light-blue' : ''; ?>">
                      <td class="align-middle py-4 px-4 font-weight-bold text-dark"><?php echo $per->name; ?></td>
                      <td class="align-middle py-4 text-center small font-weight-bold text-muted">
                          <i class="far fa-calendar-alt mr-1"></i> <?php echo $per->start_date; ?> <i class="fas fa-arrow-right mx-1 x-small"></i> <?php echo $per->end_date; ?>
                      </td>
                      <td class="align-middle py-4 text-center">
                          <?php echo $per->is_active ? '<span class="badge badge-pill badge-success px-3 py-1">'.__('am_status_active').'</span>' : '<span class="badge badge-pill badge-secondary px-3 py-1">'.__('am_status_inactive').'</span>'; ?>
                      </td>
                      <td class="align-middle py-4 text-center">
                          <div class="btn-group shadow-sm">
                              <button class="btn btn-sm btn-info edit-period-btn"
                                      data-id="<?php echo $per->id; ?>"
                                      data-name="<?php echo $per->name; ?>"
                                      data-start="<?php echo $per->start_date; ?>"
                                      data-end="<?php echo $per->end_date; ?>"
                                      data-active="<?php echo $per->is_active; ?>"
                                      data-toggle="tooltip" title="<?php echo __('am_tooltip_edit_period'); ?>">
                                  <i class="fas fa-edit"></i>
                              </button>
                              <button class="btn btn-sm btn-danger btn-delete-action" 
                                      data-url="<?php echo URL_ROOT; ?>/admin/academic/deletePeriod/<?php echo $per->id; ?>"
                                      data-name="<?php echo $per->name; ?>"
                                      data-toggle="tooltip" title="<?php echo __('am_tooltip_delete_period'); ?>">
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

  <!-- ROOMS TAB -->
  <div class="tab-pane fade p-4" id="rooms" role="tabpanel">
      <div class="d-flex justify-content-between align-items-center mb-4">
          <h4 class="mb-0 font-weight-bold text-primary"><?php echo __('am_tab_rooms'); ?></h4>
          <button class="btn btn-primary px-4 font-weight-bold shadow-sm rounded-pill" data-toggle="modal" data-target="#addRoomModal">
              <i class="fas fa-plus mr-2"></i> <?php echo __('am_btn_add_room'); ?>
          </button>
      </div>
      <div class="table-responsive">
          <table class="table table-hover mb-0 js-no-auto-datatable" id="roomsTable" style="width:100%">
              <thead class="bg-light text-center small text-uppercase font-weight-bold">
                  <tr>
                      <th class="py-3 px-4 text-left"><?php echo __('um_col_name'); ?></th>
                      <th class="py-3"><?php echo __('am_col_location'); ?></th>
                      <th class="py-3"><?php echo __('am_col_capacity'); ?></th>
                      <th class="py-3"><?php echo __('um_col_actions'); ?></th>
                  </tr>
              </thead>
              <tbody>
                  <?php foreach($data['rooms'] as $r): ?>
                  <tr>
                      <td class="align-middle py-4 px-4 font-weight-bold text-dark"><?php echo $r->name; ?></td>
                      <td class="align-middle py-4 text-center text-muted small font-weight-bold">
                          <i class="fas fa-map-marker-alt text-danger mr-1"></i> <?php echo $r->location; ?>
                      </td>
                      <td class="align-middle py-4 text-center">
                          <span class="badge badge-light border px-3 font-weight-bold"><?php echo $r->capacity; ?> <?php echo __('am_col_seats'); ?></span>
                      </td>
                      <td class="align-middle py-4 text-center">
                          <div class="btn-group shadow-sm">
                              <button class="btn btn-sm btn-info edit-room-btn"
                                      data-id="<?php echo $r->id; ?>"
                                      data-name="<?php echo $r->name; ?>"
                                      data-capacity="<?php echo $r->capacity; ?>"
                                      data-location="<?php echo $r->location; ?>"
                                      data-toggle="tooltip" title="<?php echo __('am_tooltip_edit_room'); ?>">
                                  <i class="fas fa-edit"></i>
                              </button>
                              <button class="btn btn-sm btn-danger btn-delete-action" 
                                      data-url="<?php echo URL_ROOT; ?>/admin/academic/deleteRoom/<?php echo $r->id; ?>"
                                      data-name="<?php echo $r->name; ?>"
                                      data-toggle="tooltip" title="<?php echo __('am_tooltip_delete_room'); ?>">
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

<!-- Add Program Modal -->
<div class="modal fade" id="addProgramModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <form action="<?php echo URL_ROOT; ?>/admin/academic/addProgram" method="post">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title font-weight-bold"><i class="fas fa-plus mr-2"></i><?php echo __('am_modal_add_program'); ?></h5>
        <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body py-4">
         <div class="form-group mb-3">
             <label class="font-weight-bold"><?php echo __('am_lbl_program_name'); ?></label>
             <input type="text" name="name" class="form-control py-4" placeholder="e.g. Software Engineering" required>
         </div>
         <div class="form-group">
             <label class="font-weight-bold"><?php echo __('am_lbl_program_code'); ?></label>
             <input type="text" name="code" class="form-control py-4" placeholder="SE-101" required>
         </div>
      </div>
      <div class="modal-footer border-top-0">
        <button type="button" class="btn btn-light px-4 font-weight-bold" data-dismiss="modal"><?php echo __('btn_cancel'); ?></button>
        <button type="submit" class="btn btn-primary px-4 font-weight-bold"><?php echo __('btn_save'); ?></button>
      </div>
    </div>
    </form>
  </div>
</div>

<!-- Edit Program Modal -->
<div class="modal fade" id="editProgramModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <form action="<?php echo URL_ROOT; ?>/admin/academic/editProgram" method="post">
    <input type="hidden" name="id" id="edit_program_id">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-info text-white">
        <h5 class="modal-title font-weight-bold"><i class="fas fa-edit mr-2"></i><?php echo __('am_modal_edit_program'); ?></h5>
        <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body py-4">
         <div class="form-group mb-3">
             <label class="font-weight-bold"><?php echo __('am_lbl_program_name'); ?></label>
             <input type="text" name="name" id="edit_program_name" class="form-control py-4" required>
         </div>
         <div class="form-group">
             <label class="font-weight-bold"><?php echo __('am_col_code'); ?></label>
             <input type="text" name="code" id="edit_program_code" class="form-control py-4" required>
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

<!-- Add Period Modal -->
<div class="modal fade" id="addPeriodModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <form action="<?php echo URL_ROOT; ?>/admin/academic/addPeriod" method="post">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title font-weight-bold"><i class="fas fa-calendar-plus mr-2"></i><?php echo __('am_modal_add_period'); ?></h5>
        <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body py-4">
         <div class="form-group mb-3">
             <label class="font-weight-bold"><?php echo __('am_lbl_period_name'); ?></label>
             <input type="text" name="name" class="form-control py-4" required>
         </div>
         <div class="row">
             <div class="col">
                 <div class="form-group">
                     <label class="font-weight-bold text-success small"><?php echo __('am_lbl_start_date'); ?></label>
                     <input type="date" name="start_date" class="form-control" required>
                 </div>
             </div>
             <div class="col">
                 <div class="form-group">
                     <label class="font-weight-bold text-danger small"><?php echo __('am_lbl_end_date'); ?></label>
                     <input type="date" name="end_date" class="form-control" required>
                 </div>
             </div>
         </div>
         <div class="form-group form-check mt-3">
             <input type="checkbox" name="is_active" class="form-check-input" id="checkActive">
             <label class="form-check-label font-weight-bold text-primary" for="checkActive"><?php echo __('am_lbl_set_active'); ?></label>
         </div>
      </div>
      <div class="modal-footer border-top-0">
        <button type="button" class="btn btn-light px-4 font-weight-bold" data-dismiss="modal"><?php echo __('btn_cancel'); ?></button>
        <button type="submit" class="btn btn-primary px-4 font-weight-bold"><?php echo __('btn_save'); ?></button>
      </div>
    </div>
    </form>
  </div>
</div>

<!-- Edit Period Modal -->
<div class="modal fade" id="editPeriodModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <form action="<?php echo URL_ROOT; ?>/admin/academic/editPeriod" method="post">
    <input type="hidden" name="id" id="edit_period_id">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-info text-white">
        <h5 class="modal-title font-weight-bold"><i class="fas fa-edit mr-2"></i><?php echo __('am_modal_edit_period'); ?></h5>
        <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body py-4">
         <div class="form-group mb-3">
             <label class="font-weight-bold"><?php echo __('um_col_name'); ?></label>
             <input type="text" name="name" id="edit_period_name" class="form-control py-4" required>
         </div>
         <div class="row">
             <div class="col">
                 <div class="form-group">
                     <label class="font-weight-bold text-success small text-uppercase"><?php echo __('am_lbl_start_date'); ?></label>
                     <input type="date" name="start_date" id="edit_period_start" class="form-control" required>
                 </div>
             </div>
             <div class="col">
                 <div class="form-group">
                     <label class="font-weight-bold text-danger small text-uppercase"><?php echo __('am_lbl_end_date'); ?></label>
                     <input type="date" name="end_date" id="edit_period_end" class="form-control" required>
                 </div>
             </div>
         </div>
         <div class="form-group form-check mt-3">
             <input type="checkbox" name="is_active" class="form-check-input" id="edit_period_active">
             <label class="form-check-label font-weight-bold text-primary" for="edit_period_active"><?php echo __('am_lbl_set_active'); ?></label>
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

<!-- Add Room Modal -->
<div class="modal fade" id="addRoomModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <form action="<?php echo URL_ROOT; ?>/admin/academic/addRoom" method="post">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title font-weight-bold"><i class="fas fa-door-open mr-2"></i><?php echo __('am_modal_add_room'); ?></h5>
        <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body py-4">
         <div class="form-group mb-3">
             <label class="font-weight-bold"><?php echo __('am_lbl_room_name'); ?></label>
             <input type="text" name="name" class="form-control py-4" placeholder="e.g. Lab 101" required>
         </div>
         <div class="form-group mb-3">
             <label class="font-weight-bold"><?php echo __('am_col_location'); ?></label>
             <input type="text" name="location" class="form-control py-4" placeholder="e.g. Building A, 2nd Floor">
         </div>
         <div class="form-group">
             <label class="font-weight-bold"><?php echo __('am_lbl_capacity'); ?></label>
             <input type="number" name="capacity" class="form-control py-4" placeholder="30">
         </div>
      </div>
      <div class="modal-footer border-top-0">
        <button type="button" class="btn btn-light px-4 font-weight-bold" data-dismiss="modal"><?php echo __('btn_cancel'); ?></button>
        <button type="submit" class="btn btn-primary px-4 font-weight-bold"><?php echo __('btn_save'); ?></button>
      </div>
    </div>
    </form>
  </div>
</div>

<!-- Edit Room Modal -->
<div class="modal fade" id="editRoomModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <form action="<?php echo URL_ROOT; ?>/admin/academic/editRoom" method="post">
    <input type="hidden" name="id" id="edit_room_id">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-info text-white">
        <h5 class="modal-title font-weight-bold"><i class="fas fa-edit mr-2"></i><?php echo __('am_modal_edit_room'); ?></h5>
        <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body py-4">
         <div class="form-group mb-3">
             <label class="font-weight-bold"><?php echo __('am_lbl_room_name'); ?></label>
             <input type="text" name="name" id="edit_room_name" class="form-control py-4" required>
         </div>
         <div class="form-group mb-3">
             <label class="font-weight-bold"><?php echo __('am_col_location'); ?></label>
             <input type="text" name="location" id="edit_room_location" class="form-control py-4">
         </div>
         <div class="form-group">
             <label class="font-weight-bold"><?php echo __('am_col_capacity'); ?></label>
             <input type="number" name="capacity" id="edit_room_capacity" class="form-control py-4">
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

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-danger text-white border-0 py-3">
        <h5 class="modal-title font-weight-bold"><i class="fas fa-exclamation-triangle mr-2"></i> <?php echo __('um_modal_delete_title'); ?></h5>
        <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body py-5 text-center">
        <p class="mb-1 h5"><?php echo __('um_modal_delete_confirm'); ?> <strong id="deleteItemName" class="text-danger"></strong>?</p>
        <p class="text-muted mb-0 small font-weight-bold"><?php echo __('am_modal_delete_warning'); ?></p>
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

        if ($('#programsTable').length > 0) $('#programsTable').DataTable(dtConfig);
        if ($('#periodsTable').length > 0) $('#periodsTable').DataTable(dtConfig);
        if ($('#roomsTable').length > 0) $('#roomsTable').DataTable(dtConfig);

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
            // Recalculate DataTable responsive column widths on tab switch with a small delay
            setTimeout(function() {
                $.fn.dataTable.tables({visible: true, api: true}).columns.adjust().responsive.recalc();
            }, 50);
        });

        // Edit Program
        $('.edit-program-btn').on('click', function() {
            $('#edit_program_id').val($(this).data('id'));
            $('#edit_program_name').val($(this).data('name'));
            $('#edit_program_code').val($(this).data('code'));
            $('#editProgramModal').modal('show');
        });

        // Edit Period
        $('.edit-period-btn').on('click', function() {
            $('#edit_period_id').val($(this).data('id'));
            $('#edit_period_name').val($(this).data('name'));
            $('#edit_period_start').val($(this).data('start'));
            $('#edit_period_end').val($(this).data('end'));
            $('#edit_period_active').prop('checked', $(this).data('active') == 1);
            $('#editPeriodModal').modal('show');
        });

        // Edit Room
        $('.edit-room-btn').on('click', function() {
            $('#edit_room_id').val($(this).data('id'));
            $('#edit_room_name').val($(this).data('name'));
            $('#edit_room_location').val($(this).data('location'));
            $('#edit_room_capacity').val($(this).data('capacity'));
            $('#editRoomModal').modal('show');
        });

        // Delete Confirmation Logic
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

        handleAjaxForm('#addRoomModal', '#addRoomModal form', 'Room Added!');
    });
</script>
</div>