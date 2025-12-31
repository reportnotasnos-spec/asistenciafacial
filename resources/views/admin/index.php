<div class="mb-5">
<div class="row mb-4">
    <div class="col-md-12">
        <h1 class="h3 mb-3 font-weight-normal text-dark font-weight-bold"><?php echo __('ad_dashboard'); ?></h1>
        <p class="text-muted"><?php echo __('ad_welcome'); ?> <?php echo $_SESSION['user_name']; ?></p>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card text-white bg-primary mb-3 shadow-sm h-100">
            <div class="card-header border-0"><?php echo __('ad_card_students'); ?></div>
            <div class="card-body">
                <h2 class="card-title display-4"><?php echo $data['stats']['students']; ?></h2>
                <p class="card-text"><?php echo __('ad_desc_students'); ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-success mb-3 shadow-sm h-100">
            <div class="card-header border-0"><?php echo __('ad_card_teachers'); ?></div>
            <div class="card-body">
                <h2 class="card-title display-4"><?php echo $data['stats']['teachers']; ?></h2>
                <p class="card-text"><?php echo __('ad_desc_teachers'); ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-info mb-3 shadow-sm h-100">
            <div class="card-header border-0"><?php echo __('ad_card_courses'); ?></div>
            <div class="card-body">
                <h2 class="card-title display-4"><?php echo $data['stats']['courses']; ?></h2>
                <p class="card-text"><?php echo __('ad_desc_courses'); ?></p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Quick Actions -->
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 font-weight-bold text-primary"><i class="fas fa-bolt mr-2"></i><?php echo __('ad_quick_actions'); ?></h5>
            </div>
            <div class="list-group list-group-flush custom-quick-actions">
                <a href="<?php echo URL_ROOT; ?>/admin/userManage" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3">
                    <span><i class="fas fa-users-cog mr-3 text-primary"></i><?php echo __('ad_manage_users'); ?></span>
                    <i class="fas fa-chevron-right small text-muted"></i>
                </a>
                <a href="<?php echo URL_ROOT; ?>/admin/academic" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3">
                    <span><i class="fas fa-university mr-3 text-primary"></i><?php echo __('ad_manage_academic'); ?></span>
                    <i class="fas fa-chevron-right small text-muted"></i>
                </a>
                <a href="<?php echo URL_ROOT; ?>/admin/subjectCourse" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3">
                    <span><i class="fas fa-book mr-3 text-primary"></i><?php echo __('ad_manage_subjects'); ?></span>
                    <i class="fas fa-chevron-right small text-muted"></i>
                </a>
                <a href="<?php echo URL_ROOT; ?>/admin/settings" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3">
                    <span><i class="fas fa-cogs mr-3 text-primary"></i><?php echo __('ad_settings'); ?></span>
                    <i class="fas fa-chevron-right small text-muted"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="col-md-8 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h5 class="mb-0 font-weight-bold text-primary"><?php echo __('ad_recent_activity'); ?></h5>
                <span class="badge badge-light border text-muted"><?php echo __('ad_global_feed'); ?></span>
            </div>
            <div class="card-body p-3">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 js-no-auto-datatable" id="recentAttendanceTable">
                        <thead class="bg-light text-center small text-uppercase font-weight-bold">
                            <tr>
                                <th class="py-3"><?php echo __('ad_table_time'); ?></th>
                                <th class="py-3"><?php echo __('ad_table_student'); ?></th>
                                <th class="py-3"><?php echo __('ad_table_subject'); ?></th>
                                <th class="py-3"><?php echo __('ad_table_status'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data['recent_logs'] as $log): ?>
                                <tr>
                                    <td class="align-middle py-4 small font-weight-bold text-primary">
                                        <i class="far fa-clock mr-1"></i><?php echo date('H:i', strtotime($log->scan_time)); ?>
                                    </td>
                                    <td class="align-middle py-4">
                                        <span class="text-dark font-weight-bold small"><?php echo $log->student_name; ?></span>
                                    </td>
                                    <td class="align-middle py-4">
                                        <div class="small font-weight-bold text-dark"><?php echo $log->subject_name; ?></div>
                                        <div class="x-small text-muted font-weight-bold"><i class="fas fa-map-marker-alt mr-1 text-danger"></i><?php echo $log->room_name ?? 'N/A'; ?></div>
                                    </td>
                                    <td class="align-middle py-4 text-center">
                                        <?php if($log->status == 'present'): ?>
                                            <span class="badge badge-pill badge-success px-3 py-1"><?php echo __('ad_status_present'); ?></span>
                                        <?php elseif($log->status == 'late'): ?>
                                            <span class="badge badge-pill badge-warning px-3 py-1"><?php echo __('ad_status_late'); ?></span>
                                        <?php else: ?>
                                            <span class="badge badge-pill badge-danger px-3 py-1"><?php echo __('ad_status_absent'); ?></span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white text-center py-2 border-top-0">
                <a href="#" class="small text-decoration-none font-weight-bold"><?php echo __('ad_view_history'); ?> <i class="fas fa-arrow-right ml-1"></i></a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var $table = $('#recentAttendanceTable');
    if ($table.length > 0) {
        $table.DataTable({
            pageLength: 5,
            lengthMenu: [5, 10, 25],
            order: [[0, 'desc']],
            responsive: true,
            dom: "<'row'<'col-sm-12'tr>>" +
                 "<'row'<'col-sm-12 col-md-5 small'i><'col-sm-12 col-md-7'p>>",
            language: {
                "sEmptyTable": "No recent attendance records found",
                "sZeroRecords": "No matching records found",
                "sInfo": "Showing _START_ to _END_ of _TOTAL_ entries",
                "sInfoEmpty": "Showing 0 to 0 of 0 entries",
                "sInfoFiltered": "(filtered from _MAX_ total entries)",
                "sSearch": "Search:",
                "oPaginate": {
                    "sFirst": "First",
                    "sLast": "Last",
                    "sNext": "Next",
                    "sPrevious": "Previous"
                }
            }
        });
    }
});
</script>
</div>
