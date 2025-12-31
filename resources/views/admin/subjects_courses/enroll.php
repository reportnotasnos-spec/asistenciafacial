<div class="mb-3">
    <a href="<?php echo URL_ROOT; ?>/admin/subjectCourse#courses" class="btn btn-outline-secondary btn-sm">
        &larr; Back to Courses
    </a>
</div>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="card shadow-sm border-0">
            <div class="card-body bg-primary text-white rounded">
                <h3 class="mb-1"><?php echo $data['course']->subject_name; ?></h3>
                <p class="mb-0">
                    <strong>Period:</strong> <?php echo $data['course']->period_name; ?> | 
                    <strong>Teacher:</strong> <?php echo $data['course']->teacher_name; ?> |
                    <strong>Group:</strong> <?php echo $data['course']->group_name; ?>
                </p>
            </div>
        </div>
    </div>
</div>

<?php Session::flash('enroll_msg'); ?>

<div class="row">
    <!-- Enrolled Students -->
    <div class="col-md-7">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white font-weight-bold d-flex justify-content-between">
                Enrolled Students
                <span class="badge badge-primary"><?php echo count($data['enrolled']); ?></span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive p-3">
                    <table class="table table-hover mb-0 js-no-auto-datatable" id="enrolledTable">
                        <thead class="thead-light">
                            <tr>
                                <th>Name</th>
                                <th>Student ID</th>
                                <th width="80" class="no-export">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($data['enrolled'])): ?>
                                <?php foreach($data['enrolled'] as $s): ?>
                                <tr>
                                    <td><?php echo $s->name; ?></td>
                                    <td><?php echo $s->student_id_number; ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-danger btn-delete-action" 
                                                data-url="<?php echo URL_ROOT; ?>/admin/enrollment/remove/<?php echo $data['course']->id; ?>/<?php echo $s->id; ?>"
                                                data-name="<?php echo $s->name; ?>">
                                            <i class="fas fa-user-minus"></i>
                                        </button>
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

    <!-- Available Students -->
    <div class="col-md-5">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white font-weight-bold">
                Available Students
            </div>
            <div class="card-body p-3">
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0 js-no-auto-datatable" id="availableTable">
                        <thead class="thead-light">
                            <tr>
                                <th>Student Details</th>
                                <th width="80" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($data['available'])): ?>
                                <?php foreach($data['available'] as $s): ?>
                                <tr>
                                    <td>
                                        <div class="font-weight-bold"><?php echo $s->name; ?></div>
                                        <small class="text-muted"><?php echo $s->student_id_number; ?></small>
                                    </td>
                                    <td class="text-center align-middle">
                                        <a href="<?php echo URL_ROOT; ?>/admin/enrollment/add/<?php echo $data['course']->id; ?>/<?php echo $s->id; ?>" class="btn btn-sm btn-success">
                                            <i class="fas fa-plus"></i> Enroll
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
</div>

<!-- Reusable Delete Modal for Unenroll -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-danger text-white border-0">
        <h5 class="modal-title">Remove Student</h5>
        <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body py-4 text-center">
        <p class="mb-0">Are you sure you want to remove <strong><span id="deleteItemName"></span></strong> from this course?</p>
      </div>
      <div class="modal-footer border-0 justify-content-center">
        <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
        <a href="#" id="confirmDeleteBtn" class="btn btn-danger px-4">Remove Student</a>
      </div>
    </div>
  </div>
</div>

<script>
    window.addEventListener('load', function() {
        if ($('#enrolledTable').length > 0) {
            var courseInfo = "Course: <?php echo $data['course']->subject_name; ?> | Period: <?php echo $data['course']->period_name; ?> | Teacher: <?php echo $data['course']->teacher_name; ?> | Group: <?php echo $data['course']->group_name; ?>";
            
            $('#enrolledTable').DataTable({
                pageLength: 25,
                dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                     "<'row'<'col-sm-12'tr>>" +
                     "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [
                    {
                        extend: 'excelHtml5',
                        text: '<i class="fas fa-file-excel mr-1"></i> Excel',
                        className: 'btn btn-success btn-sm mb-3 mr-1',
                        title: 'Enrolled Students - ' + '<?php echo $data['course']->subject_name; ?>',
                        messageTop: courseInfo,
                        exportOptions: { columns: ':not(.no-export)' }
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<i class="fas fa-file-pdf mr-1"></i> PDF',
                        className: 'btn btn-danger btn-sm mb-3 mr-1',
                        title: 'Enrolled Students - ' + '<?php echo $data['course']->subject_name; ?>',
                        messageTop: courseInfo,
                        exportOptions: { columns: ':not(.no-export)' }
                    },
                    {
                        extend: 'csvHtml5',
                        text: '<i class="fas fa-file-csv mr-1"></i> CSV',
                        className: 'btn btn-info btn-sm mb-3 mr-1',
                        title: 'Enrolled Students - ' + '<?php echo $data['course']->subject_name; ?>',
                        exportOptions: { columns: ':not(.no-export)' }
                    },
                    {
                        extend: 'print',
                        text: '<i class="fas fa-print mr-1"></i> Print',
                        className: 'btn btn-secondary btn-sm mb-3',
                        title: 'Enrolled Students',
                        messageTop: '<h4>' + courseInfo + '</h4>',
                        exportOptions: { columns: ':not(.no-export)' }
                    }
                ],
                initComplete: function() {
                    this.api().buttons().container().prependTo('#enrolledTable_wrapper .col-md-6:eq(0)');
                }
            });
        }

        if ($('#availableTable').length > 0) {
            $('#availableTable').DataTable({
                pageLength: 10,
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
                dom: "<'row'<'col-sm-12'f>>" +
                     "<'row'<'col-sm-12'tr>>" +
                     "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                language: {
                    search: "Filter Students:"
                }
            });
        }

        $('.btn-delete-action').on('click', function() {
            var url = $(this).data('url');
            var name = $(this).data('name');
            $('#deleteItemName').text(name);
            $('#confirmDeleteBtn').attr('href', url);
            $('#deleteModal').modal('show');
        });
    });
</script>
