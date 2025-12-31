<div class="container mt-4 mb-5">
    <div class="mb-4">
        <a href="<?php echo URL_ROOT; ?>/attendance" class="btn btn-link text-muted pl-0 font-weight-bold text-decoration-none">
            <i class="fas fa-chevron-left mr-1"></i> <?php echo __('att_back_courses'); ?>
        </a>
    </div>

    <!-- Course Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-body bg-dark text-white rounded py-4 px-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="badge badge-primary px-3 mb-2 font-weight-bold"><?php echo $data['course']->subject_code; ?></span>
                            <h2 class="mb-1 font-weight-bold"><?php echo $data['course']->subject_name; ?></h2>
                            <p class="mb-0 opacity-75">
                                <i class="fas fa-users mr-1"></i> <strong><?php echo __('sched_group'); ?>:</strong> <?php echo $data['course']->group_name; ?> | 
                                <i class="fas fa-calendar-alt mr-1"></i> <strong><?php echo __('sched_period'); ?>:</strong> <?php echo $data['course']->period_name; ?>
                            </p>
                        </div>
                        <div class="text-right d-none d-md-block">
                            <i class="fas fa-chalkboard-teacher fa-3x opacity-25"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sessions Table -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
            <h5 class="mb-0 font-weight-bold text-primary"><i class="fas fa-list-ul mr-2"></i><?php echo __('att_log_title'); ?></h5>
            <div>
                <a href="<?php echo URL_ROOT . '/attendance/export/' . $data['course']->id; ?>" class="btn btn-sm btn-outline-success font-weight-bold mr-2">
                    <i class="fas fa-file-csv mr-1"></i> <?php echo __('att_btn_export'); ?>
                </a>
                <span class="badge badge-light border text-muted px-3 font-weight-bold"><?php echo __('att_lbl_history'); ?></span>
            </div>
        </div>
        <div class="card-body p-3">
            <div class="table-responsive">
                <table class="table table-hover mb-0 js-no-auto-datatable" id="courseSessionsTable">
                    <thead class="bg-light text-center small text-uppercase font-weight-bold">
                        <tr>
                            <th class="py-3 px-4"><?php echo __('att_tbl_date'); ?></th>
                            <th class="py-3"><?php echo __('att_tbl_schedule'); ?></th>
                            <th class="py-3 text-center"><?php echo __('att_tbl_status'); ?></th>
                            <th class="py-3"><?php echo __('att_tbl_action'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($data['sessions'])): ?>
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">
                                    <i class="fas fa-calendar-times fa-2x d-block mb-3 opacity-25"></i>
                                    <?php echo __('att_no_sessions'); ?>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($data['sessions'] as $session): 
                                $date = new DateTime($session->specific_date);
                                $now = new DateTime();
                                $isToday = $date->format('Y-m-d') === $now->format('Y-m-d');
                            ?>
                                <tr class="<?php echo $isToday ? 'bg-light-blue' : ''; ?>">
                                    <td class="align-middle py-4 px-4 font-weight-bold text-dark" data-order="<?php echo $session->specific_date; ?>">
                                        <?php echo $date->format('M d, Y (D)'); ?>
                                        <?php if($isToday): ?>
                                            <span class="badge badge-primary ml-2"><?php echo __('att_today'); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="align-middle py-4 small font-weight-bold text-muted text-center">
                                        <i class="far fa-clock mr-1 text-primary"></i> <?php echo date('H:i', strtotime($session->start_time)); ?> - <?php echo date('H:i', strtotime($session->end_time)); ?>
                                    </td>
                                    <td class="text-center align-middle py-4">
                                        <?php if($session->status == 'completed'): ?>
                                            <span class="badge badge-pill badge-success px-3 py-1 font-weight-bold"><?php echo __('att_status_completed'); ?></span>
                                        <?php elseif($session->status == 'cancelled'): ?>
                                            <span class="badge badge-pill badge-danger px-3 py-1 font-weight-bold"><?php echo __('att_status_cancelled'); ?></span>
                                        <?php else: ?>
                                            <span class="badge badge-pill badge-warning px-3 py-1 font-weight-bold text-dark"><?php echo __('att_status_scheduled'); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="align-middle py-4 text-center">
                                        <a href="<?php echo URL_ROOT . '/attendance/session/' . $session->id; ?>" class="btn btn-sm btn-primary rounded-pill px-4 font-weight-bold shadow-sm">
                                            <?php if($session->status == 'completed'): ?>
                                                <i class="fas fa-file-alt mr-1"></i> <?php echo __('att_btn_view_report'); ?>
                                            <?php else: ?>
                                                <i class="fas fa-camera mr-1"></i> <?php echo __('att_btn_take_attendance'); ?>
                                            <?php endif; ?>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if ($('#courseSessionsTable').length > 0) {
            $('#courseSessionsTable').DataTable({
                pageLength: 10,
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
                order: [[0, 'desc']],
                responsive: true,
                dom: "<'row'<'col-sm-12 col-md-4'l><'col-sm-12 col-md-4'B><'col-sm-12 col-md-4'f>>" +
                     "<'row'<'col-sm-12'tr>>" +
                     "<'row'<'col-sm-12 col-md-5 small'i><'col-sm-12 col-md-7'p>>",
                buttons: [
                    {
                        extend: 'excelHtml5',
                        text: '<i class="fas fa-file-excel"></i>',
                        className: 'btn btn-success btn-sm px-3 shadow-sm',
                        titleAttr: 'Export to Excel'
                    },
                    {
                        extend: 'csvHtml5',
                        text: '<i class="fas fa-file-csv"></i>',
                        className: 'btn btn-info btn-sm px-3 shadow-sm',
                        titleAttr: 'Export to CSV'
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<i class="fas fa-file-pdf"></i>',
                        className: 'btn btn-danger btn-sm px-3 shadow-sm',
                        titleAttr: 'Export to PDF'
                    },
                    {
                        extend: 'print',
                        text: '<i class="fas fa-print"></i>',
                        className: 'btn btn-secondary btn-sm px-3 shadow-sm',
                        titleAttr: 'Print Table'
                    }
                ],
                language: {
                    "url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json"
                }
            });
        }
    });
</script>