<style>
    #video-container {
        position: relative;
        width: 100%;
        max-width: 640px;
        margin: 0 auto;
        border-radius: 8px;
        overflow: hidden;
        background: #000;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    video {
        width: 100%;
        height: auto;
        display: block;
        transform: scaleX(-1); /* Mirror effect */
    }
    canvas {
        position: absolute;
        top: 0;
        left: 0;
        transform: scaleX(-1); /* Mirror canvas to match video */
    }
    .student-card {
        transition: all 0.3s ease;
    }
    .student-card.present {
        background-color: #d4edda;
        border-color: #c3e6cb;
    }
    .student-card .status-icon {
        font-size: 1.2rem;
    }
</style>

<div class="container-fluid mt-3">
    <div class="row">
        <!-- Left: Camera & Controls -->
        <div class="col-md-7">
            <div class="card shadow-sm mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><?php echo __('sess_live_title'); ?></h5>
                    <div id="status-indicator" class="badge badge-warning"><?php echo __('sess_init'); ?></div>
                </div>
                <div class="card-body text-center p-0 bg-dark">
                    <div id="video-container">
                        <video id="video" autoplay muted playsinline></video>
                    </div>
                </div>
                <div class="card-footer">
                    <button id="btn-toggle-cam" class="btn btn-secondary"><?php echo __('sess_stop_cam'); ?></button>
                    <small class="text-muted ml-2"><?php echo __('sess_cam_tip'); ?></small>
                </div>
            </div>
            
            <div id="logs-container" class="card card-body shadow-sm" style="max-height: 200px; overflow-y: auto;">
                <h6><?php echo __('sess_activity'); ?></h6>
                <ul id="activity-log" class="list-unstyled mb-0 small"></ul>
            </div>
        </div>

        <!-- Right: Student List -->
        <div class="col-md-5">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><?php echo __('sess_roster'); ?> (<?php echo count($data['students']); ?>)</h5>
                    <div class="progress mt-2" style="height: 5px;">
                        <div id="attendance-progress" class="progress-bar bg-success" role="progressbar" style="width: 0%"></div>
                    </div>
                </div>
                <div class="card-body p-0" style="max-height: 700px; overflow-y: auto;">
                    <ul class="list-group list-group-flush" id="student-list">
                        <?php foreach ($data['students'] as $student): 
                            // Check if already present
                            $isPresent = false;
                            foreach ($data['attendance_log'] as $log) {
                                if ($log->student_id == $student->id && $log->status == 'present') {
                                    $isPresent = true;
                                    break;
                                }
                            }
                        ?>
                        <li class="list-group-item student-card <?php echo $isPresent ? 'present' : ''; ?>" 
                            id="student-row-<?php echo $student->id; ?>"
                            data-id="<?php echo $student->id; ?>"
                            data-name="<?php echo $student->name; ?>"
                            data-descriptor="<?php echo htmlspecialchars($student->descriptor ?? ''); ?>">
                            
                            <div class="d-flex align-items-center">
                                <img src="<?php echo !empty($student->profile_picture) ? URL_ROOT . '/' . $student->profile_picture : 'https://ui-avatars.com/api/?name=' . urlencode($student->name); ?>" 
                                     class="rounded-circle mr-3" width="40" height="40" alt="Avatar" loading="lazy">
                                
                                <div class="flex-grow-1">
                                    <h6 class="mb-0"><?php echo $student->name; ?></h6>
                                    <small class="text-muted"><?php echo $student->student_code ?? __('sess_no_id'); ?></small>
                                </div>

                                <div class="status-icon text-success <?php echo $isPresent ? '' : 'd-none'; ?>">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <button class="btn btn-sm btn-outline-primary ml-2 btn-manual-mark <?php echo $isPresent ? 'd-none' : ''; ?>" 
                                        onclick="markAttendance(<?php echo $student->id; ?>, 'manual')">
                                    <?php echo __('sess_btn_mark'); ?>
                                </button>
                            </div>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Load face-api.js -->
<script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>

<script>
    const URL_ROOT = '<?php echo URL_ROOT; ?>';
    const SESSION_ID = <?php echo $data['session']->id; ?>;
    const video = document.getElementById('video');
    const videoContainer = document.getElementById('video-container');
    const statusIndicator = document.getElementById('status-indicator');
    
    // Prepare Labeled Descriptors
    const students = document.querySelectorAll('.student-card');
    let labeledDescriptors = [];

    // Pre-load logic
    students.forEach(el => {
        const rawDescriptor = el.dataset.descriptor;
        const name = el.dataset.name;
        const id = el.dataset.id;
        
        if (rawDescriptor && rawDescriptor !== "null" && rawDescriptor !== "") {
            try {
                const descriptorFloat32 = new Float32Array(JSON.parse(rawDescriptor));
                // We use the ID as the label for easy lookup
                labeledDescriptors.push(new faceapi.LabeledFaceDescriptors(id, [descriptorFloat32]));
            } catch (e) {
                console.error("Error parsing descriptor for " + name, e);
            }
        }
    });

    const MODEL_URL = 'https://justadudewhohacks.github.io/face-api.js/models';

    // Start
    Promise.all([
        faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL),
        faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL),
        faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL),
        faceapi.nets.ssdMobilenetv1.loadFromUri(MODEL_URL) // More accurate than tiny
    ]).then(startVideo);

    function startVideo() {
        statusIndicator.innerText = "<?php echo __('sess_js_starting'); ?>";
        statusIndicator.className = "badge badge-info";
        
        navigator.mediaDevices.getUserMedia({ video: {} })
            .then(stream => {
                video.srcObject = stream;
            })
            .catch(err => {
                console.error(err);
                statusIndicator.innerText = "<?php echo __('sess_js_error'); ?>";
                statusIndicator.className = "badge badge-danger";
            });
    }

    video.addEventListener('play', () => {
        statusIndicator.innerText = "<?php echo __('sess_js_active'); ?>";
        statusIndicator.className = "badge badge-success";

        const canvas = faceapi.createCanvasFromMedia(video);
        videoContainer.append(canvas);
        const displaySize = { width: video.videoWidth, height: video.videoHeight };
        faceapi.matchDimensions(canvas, displaySize);

        // Create Face Matcher
        // 0.6 is the distance threshold (lower = stricter)
        const faceMatcher = labeledDescriptors.length > 0 
            ? new faceapi.FaceMatcher(labeledDescriptors, 0.5) 
            : null;

        if(!faceMatcher) {
            console.warn("No biometric data available for matching.");
            addLog("<?php echo __('sess_js_no_bio'); ?>", "text-warning");
        }

        setInterval(async () => {
            if (!faceMatcher) return;

            // Detect all faces
            const detections = await faceapi.detectAllFaces(video, new faceapi.TinyFaceDetectorOptions())
                .withFaceLandmarks()
                .withFaceDescriptors();
            
            const resizedDetections = faceapi.resizeResults(detections, displaySize);
            
            canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height);

            const results = resizedDetections.map(d => faceMatcher.findBestMatch(d.descriptor));

            results.forEach((result, i) => {
                const box = resizedDetections[i].detection.box;
                const drawBox = new faceapi.draw.DrawBox(box, { label: result.toString() });
                drawBox.draw(canvas);

                // Check if match found (label is not 'unknown')
                if (result.label !== 'unknown') {
                    // Match found! result.label is the student ID
                    const studentId = result.label;
                    const distance = result.distance;
                    
                    // console.log(`Matched Student ID: ${studentId} (Distance: ${distance})`);
                    
                    markAttendance(studentId, 'face_id');
                }
            });
        }, 500); // Check every 500ms
    });

    // Debounce/Throttle map to prevent spamming API for same person
    const recentlyMarked = new Set();

    function markAttendance(studentId, method) {
        if (recentlyMarked.has(studentId)) return; // Already processed recently
        
        // Find DOM element
        const row = document.getElementById('student-row-' + studentId);
        if (row.classList.contains('present')) return; // Already present in UI

        // Add to temp Set to prevent double firing immediately
        recentlyMarked.add(studentId);

        // Optimistic UI Update
        row.classList.add('present');
        row.querySelector('.status-icon').classList.remove('d-none');
        const btn = row.querySelector('.btn-manual-mark');
        if(btn) btn.classList.add('d-none');

        const studentName = row.dataset.name;
        addLog(`Marked: ${studentName}`, "text-success");
        updateProgress();

        // Send to Backend
        fetch(URL_ROOT + '/attendance/mark', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                session_id: SESSION_ID,
                student_id: studentId
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                // Keep as present
            } else {
                console.warn("Server warning: " + data.message);
                if (data.message !== 'Already marked') {
                   // Revert if error (optional, but 'Already marked' is fine)
                }
            }
        })
        .catch(err => {
            console.error("Network Error", err);
            // Revert UI?
        });
        
        // Clear from recentlyMarked after 10 seconds to allow re-trigger if needed (though UI prevents it)
        setTimeout(() => recentlyMarked.delete(studentId), 10000);
    }

    function addLog(msg, colorClass) {
        const ul = document.getElementById('activity-log');
        const li = document.createElement('li');
        li.className = colorClass;
        li.innerText = new Date().toLocaleTimeString() + ': ' + msg;
        ul.prepend(li);
    }

    function updateProgress() {
        const total = students.length;
        const present = document.querySelectorAll('.student-card.present').length;
        const percent = Math.round((present / total) * 100);
        document.getElementById('attendance-progress').style.width = percent + '%';
    }
    
    // Initial progress
    updateProgress();
</script>
