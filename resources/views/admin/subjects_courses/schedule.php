<div class="mb-3">
    <a href="<?php echo URL_ROOT; ?>/admin/subjectCourse#courses" class="btn btn-outline-secondary btn-sm">
        &larr; <?php echo __('sched_back_courses'); ?>
    </a>
</div>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="card shadow-sm border-0">
            <div class="card-body bg-dark text-white rounded">
                <h3 class="mb-1"><?php echo $data['course']->subject_name; ?></h3>
                <p class="mb-0">
                    <strong><?php echo __('sched_period'); ?>:</strong> <?php echo $data['course']->period_name; ?> | 
                    <strong><?php echo __('sched_teacher'); ?>:</strong> <?php echo $data['course']->teacher_name; ?> |
                    <strong><?php echo __('sched_group'); ?>:</strong> <?php echo $data['course']->group_name; ?>
                </p>
            </div>
        </div>
    </div>
</div>

<?php Session::flash('schedule_msg'); ?>

<div class="row">
    <!-- Configuration Form -->
    <div class="col-md-4">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white font-weight-bold">
                <i class="fas fa-calendar-plus mr-2"></i> <?php echo __('sched_config_title'); ?>
            </div>
            <div class="card-body">
                <form action="<?php echo URL_ROOT; ?>/admin/subjectCourse/generateSchedule/<?php echo $data['course']->id; ?>" method="post">
                    <input type="hidden" name="period_id" value="<?php echo $data['course']->period_id; ?>">
                    
                    <label class="font-weight-bold mb-3"><?php echo __('sched_config_subtitle'); ?></label>
                    <p class="small text-muted mb-3"><?php echo __('sched_config_desc'); ?></p>

                    <div id="schedule-container">
                        <?php
                        $weekdays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                        foreach ($weekdays as $day):
                          ?>
                            <div class="schedule-day-row border-bottom pb-2 mb-2">
                                <div class="custom-control custom-checkbox mb-2">
                                    <input type="checkbox" name="schedules[<?php echo $day; ?>][enabled]" value="1" class="custom-control-input day-checkbox" id="check-<?php echo $day; ?>" data-target="#details-<?php echo $day; ?>">
                                    <label class="custom-control-label font-weight-bold" for="check-<?php echo $day; ?>"><?php echo __('sched_' . strtolower($day)); ?></label>
                                </div>
                                
                                <div class="row d-none schedule-details" id="details-<?php echo $day; ?>">
                                    <div class="col-6">
                                        <div class="form-group mb-1">
                                            <label class="small"><?php echo __('sched_start_time'); ?></label>
                                            <input type="time" name="schedules[<?php echo $day; ?>][start_time]" class="form-control form-control-sm">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group mb-1">
                                            <label class="small"><?php echo __('sched_end_time'); ?></label>
                                            <input type="time" name="schedules[<?php echo $day; ?>][end_time]" class="form-control form-control-sm">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group mb-1">
                                            <label class="small"><?php echo __('sched_room'); ?></label>
                                            <select name="schedules[<?php echo $day; ?>][room_id]" class="form-control form-control-sm">
                                                <?php foreach ($data['rooms'] as $room): ?>
                                                    <option value="<?php echo $room->id; ?>"><?php echo $room->name; ?> (<?php echo $room->location; ?>)</option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="alert alert-info small mt-3">
                        <i class="fas fa-info-circle"></i> <?php echo __('sched_gen_info'); ?>
                    </div>

                    <button type="button" id="btnShowGenerateConfirm" class="btn btn-primary btn-block shadow-sm">
                        <?php echo __('sched_btn_generate'); ?>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Preview / Existing Sessions -->
    <div class="col-md-8">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white font-weight-bold d-flex justify-content-between align-items-center">
                <span><i class="fas fa-list-ul mr-2"></i><?php echo __('sched_current_sessions'); ?></span>
                <div>
                    <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#addManualSessionModal">
                        <i class="fas fa-plus"></i> <?php echo __('sched_btn_manual'); ?>
                    </button>
                    <span class="badge badge-secondary ml-2"><?php echo count($data['existing_sessions']); ?></span>
                </div>
            </div>
            <div class="card-body p-3">
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0 js-no-auto-datatable" id="sessionsTable" style="width:100%">
                        <thead class="thead-light">
                            <tr>
                                <th><?php echo __('sched_tbl_date'); ?></th>
                                <th><?php echo __('sched_tbl_time'); ?></th>
                                <th><?php echo __('sched_tbl_room'); ?></th>
                                <th><?php echo __('sched_tbl_status'); ?></th>
                                <th width="80"><?php echo __('sched_tbl_actions'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- DataTables Server-side will populate this -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Confirm Generate Modal -->
<div class="modal fade" id="confirmGenerateModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title"><i class="fas fa-calendar-alt mr-2"></i> <?php echo __('sched_modal_confirm_title'); ?></h5>
        <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body py-4">
        <p><?php echo __('sched_modal_confirm_desc'); ?></p>
        <div class="alert alert-warning mb-0 small">
            <i class="fas fa-exclamation-circle mr-1"></i> <?php echo __('sched_modal_confirm_warn'); ?>
        </div>
      </div>
      <div class="modal-footer border-0">
        <button type="button" class="btn btn-light px-4" data-dismiss="modal"><?php echo __('btn_cancel'); ?></button>
        <button type="button" id="confirmGenerateBtn" class="btn btn-primary px-4 shadow-sm"><?php echo __('sched_btn_confirm_yes'); ?></button>
      </div>
    </div>
  </div>
</div>

<!-- Add Manual Session Modal -->
<div class="modal fade" id="addManualSessionModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <form id="addManualSessionForm" action="<?php echo URL_ROOT; ?>/admin/subjectCourse/addSession" method="post">
    <input type="hidden" name="course_id" value="<?php echo $data['course']->id; ?>">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title"><i class="fas fa-plus mr-2"></i> <?php echo __('sched_modal_manual_title'); ?></h5>
        <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body">
         <div class="form-group">
             <label class="font-weight-bold"><?php echo __('sched_tbl_date'); ?></label>
             <input type="date" name="specific_date" class="form-control" required>
         </div>
         <div class="row">
             <div class="col">
                 <div class="form-group">
                     <label class="font-weight-bold"><?php echo __('sched_start_time'); ?></label>
                     <input type="time" name="start_time" class="form-control" required>
                 </div>
             </div>
             <div class="col">
                 <div class="form-group">
                     <label class="font-weight-bold"><?php echo __('sched_end_time'); ?></label>
                     <input type="time" name="end_time" class="form-control" required>
                 </div>
             </div>
         </div>
         <div class="form-group">
             <label class="font-weight-bold"><?php echo __('sched_room'); ?></label>
             <select name="room_id" class="form-control" required>
                 <?php foreach ($data['rooms'] as $room): ?>
                     <option value="<?php echo $room->id; ?>"><?php echo $room->name; ?> (<?php echo $room->location; ?>)</option>
                 <?php endforeach; ?>
             </select>
         </div>
      </div>
      <div class="modal-footer border-0">
        <button type="button" class="btn btn-light" data-dismiss="modal"><?php echo __('btn_cancel'); ?></button>
        <button type="submit" class="btn btn-success px-4"><?php echo __('sched_btn_create'); ?></button>
      </div>
    </div>
    </form>
  </div>
</div>

<!-- Edit Session Modal -->
<div class="modal fade" id="editSessionModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <form id="editSessionForm" action="<?php echo URL_ROOT; ?>/admin/subjectCourse/updateSession" method="post">
    <input type="hidden" name="id" id="edit_session_id">
    <input type="hidden" name="course_id" value="<?php echo $data['course']->id; ?>">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-info text-white">
        <h5 class="modal-title"><i class="fas fa-edit mr-2"></i> <?php echo __('sched_modal_edit_title'); ?></h5>
        <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body">
         <div class="form-group">
             <label class="font-weight-bold"><?php echo __('sched_tbl_date'); ?></label>
             <input type="date" name="specific_date" id="edit_session_date" class="form-control" required>
         </div>
         <div class="row">
             <div class="col">
                 <div class="form-group">
                     <label class="font-weight-bold"><?php echo __('sched_start_time'); ?></label>
                     <input type="time" name="start_time" id="edit_session_start" class="form-control" required>
                 </div>
             </div>
             <div class="col">
                 <div class="form-group">
                     <label class="font-weight-bold"><?php echo __('sched_end_time'); ?></label>
                     <input type="time" name="end_time" id="edit_session_end" class="form-control" required>
                 </div>
             </div>
         </div>
         <div class="form-group">
             <label class="font-weight-bold"><?php echo __('sched_room'); ?></label>
             <select name="room_id" id="edit_session_room" class="form-control" required>
                 <?php foreach ($data['rooms'] as $room): ?>
                     <option value="<?php echo $room->id; ?>"><?php echo $room->name; ?> (<?php echo $room->location; ?>)</option>
                 <?php endforeach; ?>
             </select>
         </div>
      </div>
      <div class="modal-footer border-0">
        <button type="button" class="btn btn-light" data-dismiss="modal"><?php echo __('btn_cancel'); ?></button>
        <button type="submit" class="btn btn-info px-4"><?php echo __('sched_btn_save_changes'); ?></button>
      </div>
    </div>
    </form>
  </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-danger text-white border-0">
        <h5 class="modal-title"><i class="fas fa-exclamation-triangle mr-2"></i> <?php echo __('sched_modal_delete_title'); ?></h5>
        <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body py-4 text-center">
        <p class="mb-0"><?php echo __('sched_modal_delete_desc'); ?> <strong id="deleteItemName"></strong>?</p>
        <p class="text-danger small mt-2"><i class="fas fa-info-circle mr-1"></i> <?php echo __('sched_modal_delete_info'); ?></p>
      </div>
      <div class="modal-footer border-0 justify-content-center">
        <button type="button" class="btn btn-light px-4" data-dismiss="modal"><?php echo __('btn_cancel'); ?></button>
        <button type="button" id="confirmDeleteBtn" class="btn btn-danger px-4 shadow-sm"><?php echo __('sched_btn_delete_forever'); ?></button>
      </div>
    </div>
  </div>
</div>

<!-- Status Message Modal (For Errors) -->
<div class="modal fade" id="statusModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
    <div class="modal-content border-0 shadow text-center">
      <div class="modal-body py-4">
        <div id="statusIcon" class="mb-3"></div>
        <h5 id="statusTitle"></h5>
        <p id="statusMessage" class="text-muted small mb-0"></p>
        <button type="button" class="btn btn-primary mt-4 px-4 btn-sm" data-dismiss="modal">Aceptar</button>
      </div>
    </div>
  </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var sessionsTable;
        var editingRow = null;

        // --- DataTables Global Config ---
        $.fn.dataTable.ext.errMode = 'throw'; // Log errors to console instead of alert()

        // --- Helper: Show Non-invasive Toast (Using Toastr) ---
        function showToast(message, type = 'success') {
            if (type === 'success') {
                toastr.success(message);
            } else if (type === 'error') {
                toastr.error(message);
            } else if (type === 'warning') {
                toastr.warning(message);
            } else {
                toastr.info(message);
            }
        }

        // --- Helper: Show Status Modal (For Errors) ---
        function showStatus(type, title, message) {
            var iconHtml = type === 'success' 
                ? '<i class="fas fa-check-circle fa-3x text-success"></i>' 
                : '<i class="fas fa-times-circle fa-3x text-danger"></i>';
            
            $('#statusIcon').html(iconHtml);
            $('#statusTitle').text(title);
            $('#statusMessage').text(message);
            $('#statusModal').modal('show');
        }

        // --- DataTables Initialization ---
        if ($('#sessionsTable').length > 0) {
            sessionsTable = $('#sessionsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '<?php echo URL_ROOT; ?>/admin/subjectCourse/getSessionsDataTable/<?php echo $data['course']->id; ?>',
                    type: 'GET'
                },
                columns: [
                    { 
                        data: 'specific_date',
                        render: function(data, type, row) {
                            var date = new Date(data + 'T00:00:00');
                            return date.toLocaleDateString('es-ES', { month: 'short', day: '2-digit', year: 'numeric', weekday: 'short' });
                        }
                    },
                    { 
                        data: 'start_time',
                        render: function(data, type, row) {
                            return data.substring(0,5) + ' - ' + row.end_time.substring(0,5);
                        }
                    },
                    { 
                        data: 'room_name',
                        render: function(data) {
                            return '<small>' + (data || '-') + '</small>';
                        }
                    },
                    { 
                        data: 'status',
                        render: function(data) {
                            return '<span class="badge badge-light border text-uppercase" style="font-size: 0.7rem;">' + data + '</span>';
                        }
                    },
                    {
                        data: 'id',
                        orderable: false,
                        className: 'text-center',
                        render: function(data, type, row) {
                            var URL_ROOT = '<?php echo URL_ROOT; ?>';
                            return `
                                <div class="btn-group">
                                    <button class="btn btn-xs btn-outline-info edit-session-btn"
                                            data-id="${row.id}"
                                            data-date="${row.specific_date}"
                                            data-start="${row.start_time}"
                                            data-end="${row.end_time}"
                                            data-room="${row.room_id}"
                                            data-toggle="tooltip" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-xs btn-outline-danger btn-delete-action" 
                                            data-url="${URL_ROOT}/admin/subjectCourse/deleteSession/${row.id}"
                                            data-name="Session on ${row.specific_date}"
                                            data-toggle="tooltip" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            `;
                        }
                    }
                ],
                drawCallback: function() {
                    $('[data-toggle="tooltip"]').tooltip();
                },
                pageLength: 10,
                lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                order: [[0, 'asc']], 
                responsive: true,
                dom: "<'row'<'col-sm-12 col-md-4'l><'col-sm-12 col-md-4'B><'col-sm-12 col-md-4'f>>" +
                     "<'row'<'col-sm-12'tr>>" +
                     "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [
                    {
                        extend: 'excelHtml5',
                        text: '<i class="fas fa-file-excel"></i>',
                        className: 'btn btn-success btn-sm',
                        titleAttr: 'Export to Excel',
                        exportOptions: { columns: [0, 1, 2, 3] }
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<i class="fas fa-file-pdf"></i>',
                        className: 'btn btn-danger btn-sm',
                        titleAttr: 'Export to PDF',
                        exportOptions: { columns: [0, 1, 2, 3] }
                    },
                    {
                        extend: 'print',
                        text: '<i class="fas fa-print"></i>',
                        className: 'btn btn-secondary btn-sm',
                        titleAttr: 'Print Table',
                        exportOptions: { columns: [0, 1, 2, 3] }
                    }
                ],
                language: {
                    "url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json"
                }
            });
        }

        // --- Add Manual Session Logic (AJAX) ---
        $('#addManualSessionForm').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            var btn = form.find('button[type="submit"]');
            var originalBtnText = btn.text();

            btn.prop('disabled', true).text('<?php echo __('js_creating'); ?>');

            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: form.serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#addManualSessionModal').modal('hide');
                        form[0].reset();
                        showToast('<?php echo __('js_session_created'); ?>');
                        if (sessionsTable) {
                            sessionsTable.ajax.reload(null, false);
                        } else {
                            location.reload();
                        }
                    } else {
                        showStatus('error', '<?php echo __('js_creation_failed'); ?>', response.message || 'Error creating session');
                    }
                },
                error: function(xhr) { 
                    showStatus('error', 'Server Error', 'Check date/time formats or server logs.'); 
                },
                complete: function() { btn.prop('disabled', false).text(originalBtnText); }
            });
        });

        // --- Edit Logic (AJAX) ---
        $('#sessionsTable').on('click', '.edit-session-btn', function() {
            var btn = $(this);
            $('#edit_session_id').val(btn.data('id'));
            $('#edit_session_date').val(btn.data('date'));
            $('#edit_session_start').val(btn.data('start'));
            $('#edit_session_end').val(btn.data('end'));
            $('#edit_session_room').val(btn.data('room'));
            $('#editSessionModal').modal('show');
        });

        $('#editSessionForm').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            var btn = form.find('button[type="submit"]');
            var originalBtnText = btn.text();

            btn.prop('disabled', true).text('<?php echo __('js_saving'); ?>');

            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: form.serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#editSessionModal').modal('hide');
                        showToast('<?php echo __('js_session_updated'); ?>');
                        if (sessionsTable) {
                            sessionsTable.ajax.reload(null, false);
                        } else {
                            location.reload();
                        }
                    } else {
                        showStatus('error', '<?php echo __('js_update_failed'); ?>', response.message || 'Error updating session');
                    }
                },
                error: function() { showStatus('error', 'Server Error', 'Could not connect to the server.'); },
                complete: function() { btn.prop('disabled', false).text(originalBtnText); }
            });
        });

        // --- Delete Logic (AJAX with Confirmation) ---
        var deleteUrl = '';
        $('#sessionsTable').on('click', '.btn-delete-action', function() {
            deleteUrl = $(this).data('url');
            $('#deleteItemName').text($(this).data('name'));
            $('#deleteModal').modal('show');
        });

        $('#confirmDeleteBtn').on('click', function(e) {
            e.preventDefault();
            var btn = $(this);
            var originalBtnText = btn.text();
            btn.prop('disabled', true).text('<?php echo __('js_deleting'); ?>');

            $.ajax({
                url: deleteUrl,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#deleteModal').modal('hide');
                        showToast('<?php echo __('js_session_deleted'); ?>');
                        if (sessionsTable) {
                            sessionsTable.ajax.reload(null, false);
                        } else {
                            location.reload();
                        }
                    } else {
                        $('#deleteModal').modal('hide');
                        showStatus('error', '<?php echo __('js_delete_failed'); ?>', response.message || 'Error deleting session');
                    }
                },
                error: function() { 
                    $('#deleteModal').modal('hide');
                    showStatus('error', 'Server Error', 'Could not delete the session.'); 
                },
                complete: function() { btn.prop('disabled', false).text(originalBtnText); }
            });
        });

        // --- Dynamic Schedule Logic ---
        function toggleDayRow(checkbox) {
            var targetId = checkbox.getAttribute('data-target');
            var targetDiv = document.querySelector(targetId);
            var inputs = targetDiv.querySelectorAll('input, select');
            
            if(checkbox.checked) {
                targetDiv.classList.remove('d-none');
                inputs.forEach(input => input.required = true);
            } else {
                targetDiv.classList.add('d-none');
                inputs.forEach(input => input.required = false);
            }
        }

        var checkboxes = document.querySelectorAll('.day-checkbox');
        checkboxes.forEach(function(checkbox) {
            toggleDayRow(checkbox);
            checkbox.addEventListener('change', function() {
                toggleDayRow(this);
            });
        });

        // --- Generate Calendar Logic (AJAX) ---
        $('#btnShowGenerateConfirm').on('click', function() {
            var checked = document.querySelectorAll('.day-checkbox:checked');
            if(checked.length === 0) {
                showStatus('error', '<?php echo __('js_select_day_err'); ?>', '<?php echo __('js_select_day_msg'); ?>');
                return false;
            }
            $('#confirmGenerateModal').modal('show');
        });

        $('#confirmGenerateBtn').on('click', function() {
            var btn = $(this);
            var originalBtnText = btn.text();
            var form = $('form[action*="generateSchedule"]');

            btn.prop('disabled', true).text('<?php echo __('js_generating'); ?>');

            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: form.serialize(),
                dataType: 'json',
                success: function(response) {
                    $('#confirmGenerateModal').modal('hide');
                    if (response.success) {
                        showToast(response.message);
                        if (sessionsTable) {
                            sessionsTable.ajax.reload(null, false);
                        } else {
                            setTimeout(function() { location.reload(); }, 1000);
                        }
                    } else {
                        showStatus('error', 'Generation Failed', response.message);
                    }
                },
                error: function() {
                    $('#confirmGenerateModal').modal('hide');
                    showStatus('error', 'Server Error', 'Could not generate sessions.');
                },
                complete: function() {
                    btn.prop('disabled', false).text(originalBtnText);
                }
            });
        });
    });
</script>