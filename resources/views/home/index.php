<div class="jumbotron jumbotron-fluid bg-transparent py-5">
    <div class="container text-center">
        <div class="mb-4 animate__animated animate__fadeIn">
            <img src="<?php echo URL_ROOT; ?>/assets/img/nos-logo.png" alt="Logo" class="img-fluid" style="max-height: 180px;">
        </div>
        <h1 class="display-4 font-weight-bold animate__animated animate__fadeInDown">
            <?php echo $data['title']; ?>
        </h1>
        <p class="lead text-muted animate__animated animate__fadeInUp">
            <?php echo $data['description']; ?>
        </p>
        <hr class="my-4" style="max-width: 100px; border-top: 3px solid var(--primary);">
        
        <?php if(!Session::isLoggedIn()) : ?>
            <div class="mt-5 animate__animated animate__fadeIn">
                <a href="<?php echo URL_ROOT; ?>/auth/login" class="btn btn-primary btn-lg px-5 shadow-sm">
                    Ingresar al Sistema <i class="fas fa-sign-in-alt ml-2"></i>
                </a>
            </div>
        <?php else: ?>
            <div class="row mt-5 justify-content-center">
                <div class="col-md-4 mb-4">
                    <div class="card h-100 border-0 shadow-sm hover-elevate">
                        <div class="card-body text-center p-4">
                            <div class="icon-shape bg-soft-primary text-primary rounded-circle mb-3 mx-auto">
                                <i class="fas fa-user-circle fa-2x"></i>
                            </div>
                            <h5>Mi Perfil</h5>
                            <p class="small text-muted">Gestiona tu información y revisa tus estadísticas.</p>
                            <a href="<?php echo URL_ROOT; ?>/profile" class="btn btn-outline-primary btn-sm stretched-link">Ir a Perfil</a>
                        </div>
                    </div>
                </div>
                
                <?php if($_SESSION['user_role'] == 'admin'): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 border-0 shadow-sm hover-elevate">
                        <div class="card-body text-center p-4">
                            <div class="icon-shape bg-soft-success text-success rounded-circle mb-3 mx-auto">
                                <i class="fas fa-cog fa-2x"></i>
                            </div>
                            <h5>Administración</h5>
                            <p class="small text-muted">Control total de usuarios, cursos y reportes.</p>
                            <a href="<?php echo URL_ROOT; ?>/admin" class="btn btn-outline-success btn-sm stretched-link">Panel Admin</a>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <?php if($_SESSION['user_role'] == 'teacher'): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 border-0 shadow-sm hover-elevate">
                        <div class="card-body text-center p-4">
                            <div class="icon-shape bg-soft-info text-info rounded-circle mb-3 mx-auto">
                                <i class="fas fa-clipboard-check fa-2x"></i>
                            </div>
                            <h5>Asistencia</h5>
                            <p class="small text-muted">Toma asistencia facial en tus clases asignadas.</p>
                            <a href="<?php echo URL_ROOT; ?>/attendance" class="btn btn-outline-info btn-sm stretched-link">Tomar Asistencia</a>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <?php if($_SESSION['user_role'] == 'student'): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 border-0 shadow-sm hover-elevate">
                        <div class="card-body text-center p-4">
                            <div class="icon-shape bg-soft-warning text-warning rounded-circle mb-3 mx-auto">
                                <i class="fas fa-history fa-2x"></i>
                            </div>
                            <h5>Mi Asistencia</h5>
                            <p class="small text-muted">Consulta tu historial de asistencia por curso.</p>
                            <a href="<?php echo URL_ROOT; ?>/profile/attendance" class="btn btn-outline-warning btn-sm stretched-link">Ver Historial</a>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
    .bg-soft-primary { background-color: rgba(0, 123, 255, 0.1); }
    .bg-soft-success { background-color: rgba(40, 167, 69, 0.1); }
    .bg-soft-info { background-color: rgba(23, 162, 184, 0.1); }
    .bg-soft-warning { background-color: rgba(255, 193, 7, 0.1); }
    
    .icon-shape {
        width: 64px;
        height: 64px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .hover-elevate {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }
    .hover-elevate:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }

    [data-theme="dark"] .card {
        background-color: #2c2c2c;
        color: #e0e0e0;
    }
    [data-theme="dark"] .text-muted {
        color: #b0b0b0 !important;
    }
</style>
