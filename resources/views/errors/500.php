<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Server Error</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; height: 100vh; display: flex; align-items: center; justify-content: center; }
        .error-card { border: none; border-radius: 1rem; box-shadow: 0 10px 25px rgba(0,0,0,0.1); overflow: hidden; max-width: 500px; width: 100%; }
        .error-header { background: #dc3545; padding: 2rem; text-align: center; color: white; }
        .error-body { padding: 2rem; text-align: center; background: white; }
        .error-icon { font-size: 4rem; margin-bottom: 1rem; opacity: 0.8; }
        .btn-home { border-radius: 50px; padding: 0.75rem 2rem; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="error-card mx-auto">
            <div class="error-header">
                <i class="fas fa-exclamation-triangle error-icon"></i>
                <h2 class="font-weight-bold mb-0">Server Error</h2>
            </div>
            <div class="error-body">
                <h5 class="text-danger mb-3">Something went wrong</h5>
                <p class="text-muted mb-4">
                    The server encountered an internal error. Please try again later or contact the administrator if the problem persists.
                </p>
                <?php if (defined('DEBUG_MODE') && DEBUG_MODE && isset($errorMessage)): ?>
                    <div class="alert alert-secondary text-left small overflow-auto" style="max-height: 150px;">
                        <strong>Debug:</strong> <?php echo htmlspecialchars($errorMessage); ?>
                    </div>
                <?php endif; ?>
                <a href="/" class="btn btn-danger btn-home shadow-sm">
                    <i class="fas fa-home mr-2"></i> Back to Home
                </a>
            </div>
        </div>
    </div>
</body>
</html>
