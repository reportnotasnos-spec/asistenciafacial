<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card card-body bg-light mt-5 text-center">
            <h2><?php echo __('bio_title'); ?></h2>
            <p><?php echo __('bio_subtitle'); ?></p>
            
            <div id="camera-container" class="position-relative d-inline-block mx-auto rounded-circle overflow-hidden shadow-lg" style="width: 400px; height: 400px; background: #000; border: 8px solid #fff;">
                <video id="video" width="640" height="480" autoplay muted style="width: 100%; height: 100%; object-fit: cover; transform: scaleX(-1);"></video>
                
                <!-- Guide Overlay -->
                <div class="position-absolute w-100 h-100" style="top:0; left:0; border: 2px dashed rgba(255,255,255,0.5); border-radius: 50%; pointer-events: none;"></div>

                <div id="loading-overlay" class="position-absolute w-100 h-100 d-flex justify-content-center align-items-center flex-column text-white" style="top:0; left:0; background: rgba(0,0,0,0.7); z-index: 10;">
                    <div class="spinner-border text-light mb-2" role="status">
                        <span class="sr-only"><?php echo __('bio_loading'); ?></span>
                    </div>
                    <span id="loading-text"><?php echo __('bio_loading_models'); ?></span>
                </div>
            </div>

            <div class="mt-3">
                <button id="btn-register" class="btn btn-primary btn-lg" disabled>
                    <?php echo __('bio_btn_capture'); ?>
                </button>
            </div>
            
            <div id="message-container" class="mt-3"></div>
        </div>
    </div>
</div>

<!-- Load face-api.js from CDN -->
<script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>

<script>
    const video = document.getElementById('video');
    const btnRegister = document.getElementById('btn-register');
    const loadingOverlay = document.getElementById('loading-overlay');
    const loadingText = document.getElementById('loading-text');
    const messageContainer = document.getElementById('message-container');

    // Helper to log status to UI
    function updateStatus(msg) {
        console.log(msg);
        loadingText.innerText = msg;
    }

    // Load models
    // We use a public CDN for models to avoid downloading them locally for now. 
    // In production, these should be hosted locally in /public/models
    const MODEL_URL = 'https://justadudewhohacks.github.io/face-api.js/models';

    updateStatus("<?php echo __('bio_loading_models'); ?>");

    Promise.all([
        faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL),
        faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL),
        faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL)
    ]).then(() => {
        updateStatus("<?php echo __('bio_msg_models_loaded'); ?>");
        startVideo();
    }).catch(err => {
        console.error(err);
        updateStatus("Error: " + err.message);
        messageContainer.innerHTML = '<div class="alert alert-danger"><?php echo __('bio_msg_models_err'); ?></div>';
    });

    function startVideo() {
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            updateStatus("API not supported");
            messageContainer.innerHTML = '<div class="alert alert-danger"><?php echo __('bio_msg_no_cam'); ?></div>';
            return;
        }

        navigator.mediaDevices.getUserMedia({ video: {} })
            .then(stream => {
                updateStatus("Camera stream ready");
                video.srcObject = stream;
            })
            .catch(err => {
                console.error(err);
                updateStatus("Denied: " + err.message);
                messageContainer.innerHTML = '<div class="alert alert-warning"><?php echo __('bio_msg_cam_denied'); ?> ' + err.message + '</div>';
            });
    }

    // Ensure we handle the video starting in multiple ways to be robust
    function onVideoReady() {
        if (loadingOverlay.classList.contains('d-none')) return; // Already ready
        
        console.log("Video is ready and playing.");
        
        // Fix: Use Bootstrap classes to hide because d-flex has !important
        loadingOverlay.classList.remove('d-flex');
        loadingOverlay.classList.add('d-none');
        
        btnRegister.disabled = false;
    }

    video.addEventListener('loadedmetadata', () => {
        video.play()
            .then(() => {
                onVideoReady();
            })
            .catch(e => {
                console.error("Play error:", e);
                updateStatus("Error: " + e.message);
            });
    });

    video.addEventListener('play', () => {
        onVideoReady();
    });

    video.addEventListener('canplay', () => {
        onVideoReady();
    });

    btnRegister.addEventListener('click', async () => {
        btnRegister.disabled = true;
        btnRegister.innerText = "<?php echo __('bio_msg_processing'); ?>";
        messageContainer.innerHTML = '';

        // Detect face
        const detections = await faceapi.detectSingleFace(video, new faceapi.TinyFaceDetectorOptions()).withFaceLandmarks().withFaceDescriptor();

        if (detections) {
            const descriptor = Array.from(detections.descriptor); // Convert Float32Array to normal Array
            
            // Capture image from video (CROP TO SQUARE)
            const canvas = document.createElement('canvas');
            const size = Math.min(video.videoWidth, video.videoHeight);
            canvas.width = 500; // Standard size for profile pic
            canvas.height = 500;
            const ctx = canvas.getContext('2d');
            
            // Calculate center crop
            const sourceX = (video.videoWidth - size) / 2;
            const sourceY = (video.videoHeight - size) / 2;
            
            ctx.drawImage(video, sourceX, sourceY, size, size, 0, 0, 500, 500);
            const imageData = canvas.toDataURL('image/jpeg', 0.9);

            // Send to server
            fetch('<?php echo URL_ROOT; ?>/biometrics/register', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ 
                    descriptor: descriptor,
                    image: imageData
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    messageContainer.innerHTML = '<div class="alert alert-success"><?php echo __('bio_msg_success'); ?></div>';
                    setTimeout(() => {
                        window.location.href = '<?php echo URL_ROOT; ?>/profile';
                    }, 2000);
                } else {
                    messageContainer.innerHTML = '<div class="alert alert-danger">Error: ' + data.message + '</div>';
                    btnRegister.disabled = false;
                    btnRegister.innerText = "<?php echo __('bio_btn_capture'); ?>";
                }
            })
            .catch(err => {
                console.error(err);
                messageContainer.innerHTML = '<div class="alert alert-danger"><?php echo __('bio_msg_net_err'); ?></div>';
                btnRegister.disabled = false;
                btnRegister.innerText = "<?php echo __('bio_btn_capture'); ?>";
            });

        } else {
            messageContainer.innerHTML = '<div class="alert alert-warning"><?php echo __('bio_msg_no_face'); ?></div>';
            btnRegister.disabled = false;
            btnRegister.innerText = "<?php echo __('bio_btn_capture'); ?>";
        }
    });
</script>
