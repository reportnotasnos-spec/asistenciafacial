$(document).ready(function() {
    // --- Dark Mode Logic ---
    const darkModeToggle = $('#darkModeToggle');
    const currentTheme = localStorage.getItem('theme');

    // Initial check
    if (currentTheme === 'dark') {
        document.body.setAttribute('data-theme', 'dark');
        darkModeToggle.prop('checked', true);
    }

    darkModeToggle.on('change', function() {
        if (this.checked) {
            document.body.setAttribute('data-theme', 'dark');
            localStorage.setItem('theme', 'dark');
        } else {
            document.body.removeAttribute('data-theme');
            localStorage.setItem('theme', 'light');
        }
    });

    // --- UI Helpers ---
    window.UI = {
        showSkeleton: function(containerSelector, type = 'list', count = 3) {
            let html = '';
            if (type === 'list') {
                for (let i = 0; i < count; i++) {
                    html += `
                        <div class="mb-3 p-3 border rounded shadow-sm bg-white">
                            <div class="skeleton skeleton-title mb-2"></div>
                            <div class="skeleton skeleton-text" style="width: 80%"></div>
                            <div class="skeleton skeleton-text" style="width: 40%"></div>
                        </div>
                    `;
                }
            } else if (type === 'card') {
                html = '<div class="row">';
                for (let i = 0; i < count; i++) {
                    html += `
                        <div class="col-md-4 mb-4">
                            <div class="card border-0 shadow-sm p-3">
                                <div class="skeleton skeleton-title mb-3"></div>
                                <div class="skeleton skeleton-text"></div>
                                <div class="skeleton skeleton-text" style="width: 60%"></div>
                            </div>
                        </div>
                    `;
                }
                html += '</div>';
            }
            $(containerSelector).html(html);
        }
    };

    // Initialize Bootstrap Tooltips
  $('[data-toggle="tooltip"]').tooltip();

      // Initialize DataTables for Admin Tables
      // We ignore tables with 'js-no-auto-datatable' to allow custom initialization
      if ($('.table:not(.js-no-auto-datatable)').length > 0) {
          var table = $('.table:not(.js-no-auto-datatable)').DataTable({      responsive: true,
      lengthChange: true, // Explicitly enable
      lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
      dom: "<'row'<'col-sm-12 col-md-4'l><'col-sm-12 col-md-4'B><'col-sm-12 col-md-4'f>>" +
        "<'row'<'col-sm-12'tr>>" +
        "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
      buttons: [
        {
          extend: 'excelHtml5',
          text: ' <i class="fas fa-file-excel"></i> ',
          className: 'btn btn-success btn-sm'
        },
        {
          extend: 'pdfHtml5',
          text: ' <i class="fas fa-file-pdf"></i> ',
          className: 'btn btn-danger btn-sm'
        },
        {
          extend: 'csvHtml5',
          text: ' <i class="fas fa-file-csv"></i> ',
          className: 'btn btn-info btn-sm gap-2'
        },
        {
          extend: 'print',
          text: ' <i class="fas fa-print"></i> ',
          className: 'btn btn-secondary btn-sm'
        }
      ],
      language: {
        "sProcessing": "Procesando...",
        "sLengthMenu": "Mostrar _MENU_ registros",
        "sZeroRecords": "No se encontraron resultados",
        "sEmptyTable": "Ningún dato disponible en esta tabla",
        "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
        "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
        "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
        "sInfoPostFix": "",
        "sSearch": "Buscar:",
        "sUrl": "",
        "sInfoThousands": ",",
        "sLoadingRecords": "Cargando...",
        "oPaginate": {
          "sFirst": "Primero",
          "sLast": "Último",
          "sNext": "Siguiente",
          "sPrevious": "Anterior"
        },
        "oAria": {
          "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
          "sSortDescending": ": Activar para ordenar la columna de manera descendente"
        }
      }
    });

    // Fix for DataTables inside Bootstrap Tabs
    // When a tab is shown, we need to tell DataTables to recalculate column widths
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
      $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
    });
  }

  // Auto-dismiss flash messages after 3 seconds
  const flashMsg = $('#msg-flash, .alert-success, .alert-danger, .alert-info');

  if (flashMsg.length > 0) {
    setTimeout(function () {
      flashMsg.fadeOut('slow', function () {
        $(this).remove();
      });
    }, 3000);
  }
});
