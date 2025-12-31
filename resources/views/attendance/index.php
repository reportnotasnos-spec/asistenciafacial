<div class="mb-5">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="h3 mb-3 font-weight-normal text-dark font-weight-bold"><?php echo __('teach_dashboard'); ?></h1>
            <p class="text-muted"><?php echo __('teach_welcome'); ?> <strong><?php echo $_SESSION['user_name']; ?></strong></p>
        </div>
    </div>

    <!-- Statistic Widgets -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3 shadow-sm h-100">
                <div class="card-header border-0 font-weight-bold"><?php echo __('teach_total_courses'); ?></div>
                <div class="card-body">
                    <h2 class="card-title display-4 font-weight-bold"><?php echo $data['stats']['total_courses']; ?></h2>
                    <p class="card-text small opacity-75"><?php echo __('teach_courses_desc'); ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3 shadow-sm h-100">
                <div class="card-header border-0 font-weight-bold"><?php echo __('teach_unique_students'); ?></div>
                <div class="card-body">
                    <h2 class="card-title display-4 font-weight-bold"><?php echo $data['stats']['total_students']; ?></h2>
                    <p class="card-text small opacity-75"><?php echo __('teach_students_desc'); ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-info mb-3 shadow-sm h-100">
                <div class="card-header border-0 font-weight-bold"><?php echo __('teach_avg_att'); ?></div>
                <div class="card-body">
                    <h2 class="card-title display-4 font-weight-bold"><?php echo $data['stats']['avg_attendance']; ?>%</h2>
                    <p class="card-text small opacity-75"><?php echo __('teach_att_desc'); ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Sidebar: Quick Actions & Risks -->
        <div class="col-md-4 mb-4">
            <!-- Quick Actions -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 font-weight-bold text-primary"><i class="fas fa-bolt mr-2"></i><?php echo __('teach_quick_actions'); ?></h5>
                </div>
                <div class="list-group list-group-flush custom-quick-actions">
                    <a href="<?php echo URL_ROOT; ?>/profile" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3">
                        <span><i class="fas fa-user-circle mr-3 text-primary"></i><?php echo __('teach_my_profile'); ?></span>
                        <i class="fas fa-chevron-right small text-muted"></i>
                    </a>
                    <a href="<?php echo URL_ROOT; ?>/attendance" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3 active bg-soft-primary border-0">
                        <span><i class="fas fa-chalkboard-teacher mr-3 text-primary"></i><?php echo __('teach_my_courses'); ?></span>
                        <i class="fas fa-chevron-right small text-muted"></i>
                    </a>
                    <a href="<?php echo URL_ROOT; ?>/biometrics/register" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3">
                        <span><i class="fas fa-fingerprint mr-3 text-primary"></i><?php echo __('teach_bio_update'); ?></span>
                        <i class="fas fa-chevron-right small text-muted"></i>
                    </a>
                </div>
            </div>

            <!-- Students at Risk -->
            <div class="card border-0 shadow-sm overflow-hidden mb-4">
                <div class="card-header bg-transparent py-3 d-flex justify-content-between align-items-center border-bottom">
                    <h5 class="mb-0 font-weight-bold text-warning d-flex align-items-center">
                        <i class="fas fa-exclamation-triangle mr-2"></i><?php echo __('teach_risk_title'); ?>
                    </h5>
                    <span class="badge badge-warning px-2 py-1 shadow-sm" style="font-size: 0.7rem;"><?php echo __('teach_risk_subtitle'); ?></span>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush custom-quick-actions mb-2">
                        <?php if (empty($data['atRisk'])): ?>
                            <li class="list-group-item text-center py-5 opacity-75">
                                <i class="fas fa-check-circle text-success d-block fa-3x mb-3 opacity-25"></i>
                                <span class="font-weight-bold"><?php echo __('teach_no_risk'); ?></span>
                            </li>
                        <?php else: ?>
                            <?php foreach ($data['atRisk'] as $student): ?>
                                <li class="list-group-item py-3 px-4">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <h6 class="mb-0 font-weight-bold"><?php echo $student->student_name; ?></h6>
                                        <span class="badge badge-pill badge-danger shadow-sm"><?php echo round($student->attendance_pct, 1); ?>%</span>
                                    </div>
                                    <div class="x-small font-weight-bold text-muted text-uppercase letter-spacing-1">
                                        <?php echo $student->subject_name; ?> <span class="opacity-50 ml-1">| <?php echo __('sched_group'); ?> <?php echo $student->group_name; ?></span>
                                    </div>
                                    <div class="progress mt-2" style="height: 4px; background-color: rgba(0,0,0,0.05);">
                                        <div class="progress-bar bg-danger" role="progressbar" style="width: <?php echo $student->attendance_pct; ?>%"></div>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </div>
                <?php if (!empty($data['atRisk'])): ?>
                <div class="card-footer bg-transparent border-0 text-center pt-4 pb-4 border-top">
                    <small class="text-muted font-weight-bold"><?php echo __('teach_risk_show'); ?></small>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Main Content: Today & Schedule -->
        <div class="col-md-8 mb-4">
            <!-- Today's Session -->
            <?php if ($data['today']): ?>
            <div class="card border-0 shadow-sm mb-4 bg-soft-success" style="border-left: 5px solid #28a745 !important;">
                <div class="card-body py-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h6 class="mb-1 text-success font-weight-bold text-uppercase small"><i class="fas fa-clock mr-1"></i><?php echo __('teach_next_class'); ?></h6>
                            <h4 class="mb-2 font-weight-bold text-dark"><?php echo $data['today']->subject_name; ?></h4>
                            <div class="d-flex align-items-center flex-wrap mt-2">
                                <span class="badge badge-primary py-2 px-3 mr-2 mb-1"><i class="far fa-clock mr-1"></i><?php echo date('H:i', strtotime($data['today']->start_time)); ?> - <?php echo date('H:i', strtotime($data['today']->end_time)); ?></span>
                                <span class="badge badge-light border py-2 px-3 mr-2 mb-1 mt-1 text-muted"><i class="fas fa-map-marker-alt mr-1 text-danger"></i> <?php echo $data['today']->room_name ?? 'N/A'; ?></span>
                                <span class="badge badge-light border py-2 px-3 mb-1 mt-1 text-muted"><?php echo __('sched_group'); ?> <?php echo $data['today']->group_name; ?></span>
                            </div>
                        </div>
                        <div class="col-md-4 text-md-right mt-3 mt-md-0">
                            <a href="<?php echo URL_ROOT . '/attendance/session/' . $data['today']->id; ?>" class="btn btn-success btn-lg px-4 shadow-sm font-weight-bold rounded-pill">
                                <i class="fas fa-camera mr-2"></i> <?php echo __('teach_start_tracking'); ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Weekly Schedule -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 font-weight-bold text-primary"><i class="fas fa-calendar-week mr-2"></i><?php echo __('teach_weekly_timeline'); ?></h5>
                    <span class="badge badge-light border text-muted px-3 py-1 font-weight-bold small"><?php echo __('teach_this_week'); ?></span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="weekly-schedule-table mb-0" style="min-width: 700px; table-layout: fixed;">
                            <thead class="text-center small text-uppercase font-weight-bold">
                                <tr>
                                    <?php 
                                    $weekdays = ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'];
                                    $currentDate = new DateTime();
                                    $currentDate->modify('monday this week');
                                    for($i=0; $i<7; $i++):
                                        $isToday = (new DateTime())->format('Y-m-d') == $currentDate->format('Y-m-d');
                                    ?>
                                        <th class="<?php echo $isToday ? 'bg-primary text-white border-primary' : 'text-dark bg-light'; ?>" style="width: 14.28%; border-top: none; padding: 10px 5px;">
                                            <?php echo $weekdays[$i]; ?><br>
                                            <span class="h6 font-weight-bold"><?php echo $currentDate->format('d'); ?></span>
                                        </th>
                                    <?php 
                                        $currentDate->modify('+1 day');
                                    endfor; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <?php 
                                    $currentDate = new DateTime();
                                    $currentDate->modify('monday this week');
                                    for($i=0; $i<7; $i++):
                                        $dateKey = $currentDate->format('Y-m-d');
                                        $sessions = $data['schedule'][$dateKey] ?? [];
                                        $isToday = (new DateTime())->format('Y-m-d') == $dateKey;
                                    ?>
                                        <td class="p-2 align-top <?php echo $isToday ? 'schedule-today-cell' : ''; ?>" style="height: 160px; border: 1px solid #eee;">
                                            <?php if(!empty($sessions)): ?>
                                                <?php foreach($sessions as $s): ?>
                                                    <div class="card mb-1 border-0 shadow-sm schedule-event-card" style="border-left: 3px solid #007bff !important;">
                                                        <div class="card-body p-1" style="font-size: 0.7rem;">
                                                            <div class="font-weight-bold text-dark truncate"><?php echo $s->subject_code; ?></div>
                                                            <div class="text-muted"><i class="far fa-clock mr-1"></i><?php echo date('H:i', strtotime($s->start_time)); ?></div>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </td>
                                    <?php 
                                        $currentDate->modify('+1 day');
                                    endfor; ?>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- My Courses List -->
            <div class="row">
                <div class="col-12">
                    <h5 class="mb-3 font-weight-bold text-dark"><i class="fas fa-book-open mr-2 text-primary"></i><?php echo __('teach_assigned_courses'); ?></h5>
                </div>
                <?php foreach ($data['courses'] as $course): 
                    $attendance = round($course->attendance_avg ?? 0, 1);
                    $barColor = $attendance >= 80 ? 'bg-success' : ($attendance >= 60 ? 'bg-warning' : 'bg-danger');
                ?>
                <div class="col-md-6 mb-4">
                    <div class="card h-100 shadow-sm border-0 hover-elevate">
                        <div class="card-body py-3 px-3">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="badge badge-primary px-2 py-1 small"><?php echo $course->subject_code; ?></span>
                                <span class="badge badge-light border text-muted x-small font-weight-bold"><?php echo __('sched_group'); ?> <?php echo $course->group_name; ?></span>
                            </div>
                            <h6 class="font-weight-bold mb-3 text-dark"><?php echo $course->subject_name; ?></h6>
                            <div class="d-flex justify-content-between mb-1 x-small font-weight-bold">
                                <span class="text-muted"><?php echo $course->student_count; ?> <?php echo __('teach_students'); ?></span>
                                <span class="text-dark"><?php echo $attendance; ?>% <?php echo __('teach_attendance'); ?></span>
                            </div>
                            <div class="progress" style="height: 6px; border-radius: 10px;">
                                <div class="progress-bar <?php echo $barColor; ?> rounded-pill" role="progressbar" style="width: <?php echo $attendance; ?>%"></div>
                            </div>
                            <div class="mt-3">
                                <a href="<?php echo URL_ROOT . '/attendance/course/' . $course->id; ?>" class="btn btn-outline-primary btn-sm btn-block font-weight-bold rounded-pill">
                                    <?php echo __('teach_manage'); ?> <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<style>
.bg-soft-primary { background-color: rgba(0, 123, 255, 0.05); }
.bg-soft-success { background-color: rgba(40, 167, 69, 0.05); }
.hover-elevate { transition: transform 0.2s; }
.hover-elevate:hover { transform: translateY(-3px); box-shadow: 0 .25rem .75rem rgba(0,0,0,.1)!important; }
.truncate { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.x-small { font-size: 0.7rem; }
</style>
