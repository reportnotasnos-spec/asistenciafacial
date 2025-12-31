<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo URL_ROOT; ?>/assets/img/favicon.ico">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.2/css/buttons.bootstrap4.min.css">
    
    <link rel="stylesheet" href="<?php echo URL_ROOT; ?>/assets/css/style.css">
    <title><?php echo SITE_NAME; ?></title>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-3 sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand font-weight-bold" href="<?php echo URL_ROOT; ?>">
                <i class="fas fa-user-check mr-2"></i><?php echo SITE_NAME; ?>
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarsExampleDefault">
                <ul class="navbar-nav mr-auto">
                    <?php if(Session::isLoggedIn()) : ?>
                        <!-- Admin Links -->
                        <?php if($_SESSION['user_role'] == 'admin'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo URL_ROOT; ?>/admin">
                                    <i class="fas fa-tachometer-alt mr-1"></i> <?php echo __('nav_dashboard'); ?>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo URL_ROOT; ?>/auth/register">
                                    <i class="fas fa-users-cog mr-1"></i> <?php echo __('nav_users'); ?>
                                </a>
                            </li>
                        
                        <!-- Teacher Links -->
                        <?php elseif($_SESSION['user_role'] == 'teacher'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo URL_ROOT; ?>/attendance">
                                    <i class="fas fa-chalkboard-teacher mr-1"></i> <?php echo __('nav_courses'); ?>
                                </a>
                            </li>
                        
                        <!-- Student Links -->
                        <?php elseif($_SESSION['user_role'] == 'student'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo URL_ROOT; ?>/profile">
                                    <i class="fas fa-id-card mr-1"></i> <?php echo __('nav_profile'); ?>
                                </a>
                            </li>
                        <?php endif; ?>
                    <?php else: ?>
                        <!-- Guest Links -->
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo URL_ROOT; ?>">
                                <i class="fas fa-home mr-1"></i> <?php echo __('nav_home'); ?>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
                
                <ul class="navbar-nav ml-auto align-items-center">
                    <!-- Language Switcher -->
                    <li class="nav-item dropdown mr-2">
                        <a class="nav-link dropdown-toggle small font-weight-bold" href="#" id="langDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-globe mr-1"></i> <?php echo strtoupper(get_current_lang()); ?>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow p-0" aria-labelledby="langDropdown" style="min-width: auto;">
                            <a class="dropdown-item small py-2 <?php echo get_current_lang() == 'en' ? 'active' : ''; ?>" href="<?php echo URL_ROOT; ?>/language/set/en">English</a>
                            <a class="dropdown-item small py-2 <?php echo get_current_lang() == 'es' ? 'active' : ''; ?>" href="<?php echo URL_ROOT; ?>/language/set/es">Español</a>
                        </div>
                    </li>

                    <!-- Dark Mode Toggle -->
                    <li class="nav-item mr-3">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="darkModeToggle">
                            <label class="custom-control-label text-white small" for="darkModeToggle">
                                <i class="fas fa-moon"></i>
                            </label>
                        </div>
                    </li>
                    
                    <?php if(Session::isLoggedIn()) : ?>
                        <!-- Notification Bell -->
                        <li class="nav-item dropdown mr-3">
                            <a class="nav-link" href="#" id="notificationDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-bell fa-lg"></i>
                                <span class="badge badge-danger badge-pill position-absolute" id="notification-badge" style="top: 5px; right: 0; display: none;">0</span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow p-0" aria-labelledby="notificationDropdown" style="width: 320px; max-height: 400px; overflow-y: auto;">
                                <div class="d-flex justify-content-between align-items-center px-3 py-2 bg-light border-bottom">
                                    <h6 class="mb-0 text-muted">Notifications</h6>
                                    <a href="#" id="mark-all-read" class="small text-primary">Mark all read</a>
                                </div>
                                <div id="notification-list">
                                    <div class="text-center py-3 text-muted">
                                        <small>No notifications</small>
                                    </div>
                                </div>
                            </div>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <div class="bg-white text-dark rounded-circle d-flex justify-content-center align-items-center mr-2" style="width: 30px; height: 30px; font-weight: bold;">
                                    <?php echo strtoupper(substr($_SESSION['user_name'], 0, 1)); ?>
                                </div>
                                <span><?php echo $_SESSION['user_name']; ?></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow" aria-labelledby="navbarDropdown">
                                <h6 class="dropdown-header">Role: <?php echo ucfirst($_SESSION['user_role']); ?></h6>
                                <div class="dropdown-divider"></div>
                                
                                <a class="dropdown-item" href="<?php echo URL_ROOT; ?>/profile">
                                    <i class="fas fa-user-circle mr-2 text-muted"></i> Profile
                                </a>
                                
                                <a class="dropdown-item" href="<?php echo URL_ROOT; ?>/users/change-password">
                                    <i class="fas fa-key mr-2 text-muted"></i> Change Password
                                </a>
                                
                                <?php if($_SESSION['user_role'] != 'admin'): ?>
                                    <a class="dropdown-item" href="<?php echo URL_ROOT; ?>/biometrics/register">
                                        <i class="fas fa-fingerprint mr-2 text-muted"></i> Biometrics
                                    </a>
                                <?php endif; ?>

                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-danger" href="<?php echo URL_ROOT; ?>/auth/logout">
                                    <i class="fas fa-sign-out-alt mr-2"></i> <?php echo __('nav_logout'); ?>
                                </a>
                            </div>
                        </li>
                    <?php else : ?>
                        <li class="nav-item">
                            <a class="nav-link btn btn-outline-light px-3 ml-2" href="<?php echo URL_ROOT; ?>/auth/login">
                                <?php echo __('nav_login'); ?> <i class="fas fa-sign-in-alt ml-1"></i>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <?php Session::flash('login_success'); ?>
        <?php Session::flash('logout_success'); ?>
        <?php Session::flash('profile_success'); ?>
        <?php
        // This is where the specific view content will be injected
        if (isset($view_path) && file_exists($view_path)) {
            require_once $view_path;
        }
        ?>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
    <!-- DataTables Buttons -->
    <script src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.bootstrap4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.print.min.js"></script>

    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script src="<?php echo URL_ROOT; ?>/assets/js/main.js?v=<?php echo time(); ?>"></script>
    
    <script>
    $(document).ready(function() {
        // Global Toastr Configuration
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };

        // Auto-process PHP Flash Messages
        $('.alert[id="msg-flash"]').each(function() {
            const message = $(this).text().trim();
            const className = $(this).attr('class');
            
            if (className.includes('alert-success')) {
                toastr.success(message);
            } else if (className.includes('alert-danger')) {
                toastr.error(message);
            } else if (className.includes('alert-warning')) {
                toastr.warning(message);
            } else {
                toastr.info(message);
            }
            
            // Remove the static alert from DOM
            $(this).remove();
        });
    });
    </script>
    
    <?php if(Session::isLoggedIn()) : ?>
    <script>
    $(document).ready(function() {
        const URL_ROOT = '<?php echo URL_ROOT; ?>';
        
        function loadNotifications() {
            $.ajax({
                url: URL_ROOT + '/notifications/fetch',
                method: 'GET',
                success: function(data) {
                    $('#notification-badge').text(data.count);
                    if(data.count > 0) {
                        $('#notification-badge').show();
                    } else {
                        $('#notification-badge').hide();
                    }

                    let html = '';
                    if(data.notifications.length === 0) {
                        html = '<div class="text-center py-3 text-muted"><small>No notifications</small></div>';
                    } else {
                        data.notifications.forEach(n => {
                            let bgClass = n.is_read == 0 ? 'bg-light' : '';
                            let icon = 'info-circle text-info';
                            if(n.type === 'success') icon = 'check-circle text-success';
                            if(n.type === 'warning') icon = 'exclamation-triangle text-warning';
                            if(n.type === 'danger') icon = 'times-circle text-danger';

                            html += `
                                <div class="dropdown-item px-3 py-2 border-bottom ${bgClass} notification-item" data-id="${n.id}">
                                    <div class="media">
                                        <i class="fas fa-${icon} mr-3 mt-1"></i>
                                        <div class="media-body">
                                            <h6 class="mt-0 mb-1 small font-weight-bold text-wrap">${n.title}</h6>
                                            <p class="mb-0 small text-muted text-wrap">${n.message}</p>
                                            <small class="text-muted" style="font-size: 0.7rem;">${new Date(n.created_at).toLocaleString()}</small>
                                        </div>
                                    </div>
                                </div>
                            `;
                        });
                    }
                    $('#notification-list').html(html);

                    // Re-bind click events
                    $('.notification-item').click(function(e) {
                        // e.preventDefault(); // Don't block links if we add them later
                        let id = $(this).data('id');
                        $.post(URL_ROOT + '/notifications/markRead/' + id, function() {
                            loadNotifications(); // Refresh
                        });
                    });
                }
            });
        }

        // Initial Load
        loadNotifications();

        // Mark All Read
        $('#mark-all-read').click(function(e) {
            e.preventDefault();
            $.post(URL_ROOT + '/notifications/markAllRead', function() {
                loadNotifications();
            });
        });

        // Poll every 60 seconds
        setInterval(loadNotifications, 60000);
    });
    </script>
    <?php endif; ?>
</body>
</html>
