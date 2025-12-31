<div class="container mt-4 mb-5">
    <div class="mb-4">
        <a href="<?php echo URL_ROOT; ?>/profile" class="btn btn-link text-muted pl-0 font-weight-bold text-decoration-none">
            <i class="fas fa-chevron-left mr-1"></i> Back to Dashboard
        </a>
    </div>

    <!-- Course Info Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm border-0 overflow-hidden">
                <div class="card-body bg-primary text-white py-4 px-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <span class="badge badge-light text-primary px-3 mb-2 font-weight-bold"><?php echo $data['course']->subject_code; ?></span>
                            <h2 class="mb-1 font-weight-bold"><?php echo $data['course']->subject_name; ?></h2>
                            <p class="mb-0 opacity-75">
                                <i class="fas fa-calendar-alt mr-1"></i> <strong>Period:</strong> <?php echo $data['course']->period_name; ?> | 
                                <i class="fas fa-users mr-1"></i> <strong>Group:</strong> <?php echo $data['course']->group_name; ?> |
                                <i class="fas fa-chalkboard-teacher mr-1"></i> <strong>Teacher:</strong> <?php echo $data['course']->teacher_name; ?>
                            </p>
                        </div>
                        <div class="col-md-4 text-md-right mt-3 mt-md-0 border-left-md pl-md-4" style="border-color: rgba(255,255,255,0.2) !important;">
                            <?php 
                                $total = count($data['history']);
                                $attended = 0;
                                foreach($data['history'] as $h) if($h->attendance_status) $attended++;
                                $pct = ($total > 0) ? round(($attended * 100) / $total, 1) : 100;
                                $absences = 0;
                                foreach($data['history'] as $h) {
                                    $isPast = (new DateTime($h->specific_date . ' ' . $h->end_time)) < (new DateTime());
                                    if($isPast && !$h->attendance_status) $absences++;
                                }
                            ?>
                            <div class="attendance-stat">
                                <h1 class="mb-0 font-weight-bold display-4" style="font-size: 3rem;"><?php echo $pct; ?>%</h1>
                                <p class="text-uppercase small font-weight-bold mb-0 opacity-75">Attendance Rate</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white border-0 py-3 px-4">
                    <div class="row text-center text-md-left">
                        <div class="col-md-3 border-right">
                            <small class="text-muted text-uppercase font-weight-bold d-block">Total Sessions</small>
                            <span class="h5 font-weight-bold text-dark"><?php echo $total; ?></span>
                        </div>
                        <div class="col-md-3 border-right">
                            <small class="text-muted text-uppercase font-weight-bold d-block text-success">Attended</small>
                            <span class="h5 font-weight-bold text-success"><?php echo $attended; ?></span>
                        </div>
                        <div class="col-md-3 border-right">
                            <small class="text-muted text-uppercase font-weight-bold d-block text-danger">Absences</small>
                            <span class="h5 font-weight-bold text-danger"><?php echo $absences; ?></span>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted text-uppercase font-weight-bold d-block text-info">Upcoming</small>
                            <span class="h5 font-weight-bold text-info"><?php echo $total - ($attended + $absences); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- History Table -->
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                    <h5 class="mb-0 font-weight-bold text-primary"><i class="fas fa-history mr-2"></i>Full Attendance History</h5>
                    <span class="badge badge-light border text-muted px-3 font-weight-bold">Detailed Log</span>
                </div>
                <div class="card-body p-3">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 js-no-auto-datatable" id="attendanceDetailTable">
                            <thead class="bg-light text-center small text-uppercase font-weight-bold">
                                <tr>
                                    <th class="py-3 px-4">Date</th>
                                    <th class="py-3">Schedule</th>
                                    <th class="py-3">Room</th>
                                    <th class="py-3 text-center">Status</th>
                                    <th class="py-3">Method</th>
                                    <th class="py-3">Scan Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($data['history'] as $session): 
                                    $isPast = (new DateTime($session->specific_date . ' ' . $session->end_time)) < (new DateTime());
                                    
                                    $statusBadge = '<span class="badge badge-pill badge-light border px-3 py-1 text-uppercase x-small font-weight-bold">Upcoming</span>';
                                    if ($session->attendance_status == 'present') {
                                        $statusBadge = '<span class="badge badge-pill badge-success px-3 py-1 font-weight-bold"><i class="fas fa-check-circle mr-1"></i> PRESENT</span>';
                                    } elseif ($session->attendance_status == 'late') {
                                        $statusBadge = '<span class="badge badge-pill badge-warning px-3 py-1 font-weight-bold"><i class="fas fa-clock mr-1"></i> LATE</span>';
                                    } elseif ($isPast && !$session->attendance_status) {
                                        $statusBadge = '<span class="badge badge-pill badge-danger px-3 py-1 font-weight-bold"><i class="fas fa-times-circle mr-1"></i> ABSENT</span>';
                                    }
                                ?>
                                    <tr>
                                        <td class="align-middle py-4 px-4 font-weight-bold text-dark" data-order="<?php echo $session->specific_date; ?>">
                                            <?php echo date('M d, Y (D)', strtotime($session->specific_date)); ?>
                                        </td>
                                        <td class="align-middle py-4 small font-weight-bold text-muted">
                                            <i class="far fa-clock mr-1 text-primary"></i> <?php echo date('H:i', strtotime($session->start_time)); ?> - <?php echo date('H:i', strtotime($session->end_time)); ?>
                                        </td>
                                        <td class="align-middle py-4 small">
                                            <span class="badge badge-light border font-weight-bold text-muted px-2 py-1">
                                                <i class="fas fa-map-marker-alt text-danger mr-1"></i> <?php echo $session->room_name ?? 'N/A'; ?>
                                            </span>
                                        </td>
                                        <td class="text-center align-middle py-4">
                                            <?php echo $statusBadge; ?>
                                        </td>
                                        <td class="align-middle py-4 small text-muted font-weight-bold">
                                            <?php echo $session->verification_method ? '<i class="fas fa-fingerprint mr-1 text-info"></i>' . ucfirst(str_replace('_', ' ', $session->verification_method)) : '-'; ?>
                                        </td>
                                        <td class="align-middle py-4 small font-weight-bold <?php echo $session->scan_time ? 'text-primary' : 'text-muted'; ?>">
                                            <?php echo $session->scan_time ? date('H:i:s', strtotime($session->scan_time)) : '--:--:--'; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if ($('#attendanceDetailTable').length > 0) {
            $('#attendanceDetailTable').DataTable({
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

<style>
@media (min-width: 768px) {
    .border-left-md {
        border-left: 1px solid rgba(255,255,255,0.2) !important;
    }
}
.x-small {
    font-size: 0.75rem;
}
</style>