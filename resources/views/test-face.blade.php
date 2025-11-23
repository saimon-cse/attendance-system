<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Face Recognition Test</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .video-container { position: relative; width: 640px; height: 480px; background: #000; border-radius: 8px; overflow: hidden; }
        video { width: 100%; height: 100%; object-fit: cover; transform: scaleX(-1); }
        canvas { display: none; }
        .overlay { position: absolute; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none; }
        .face-box { position: absolute; border: 2px solid #00ff00; background: rgba(0, 255, 0, 0.1); }
    </style>
</head>
<body class="bg-gray-900 text-white min-h-screen flex flex-col items-center justify-center p-4">

    <div class="max-w-4xl w-full bg-gray-800 p-6 rounded-xl shadow-2xl">
        <h1 class="text-3xl font-bold mb-6 text-center text-blue-400">Face Recognition System</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Camera Section -->
            <div class="flex flex-col items-center">
                <div class="video-container shadow-lg mb-4">
                    <video id="video" autoplay playsinline></video>
                    <div id="overlay" class="overlay"></div>
                </div>
                <div class="flex gap-4">
                    <button onclick="startCamera()" class="px-4 py-2 bg-green-600 hover:bg-green-700 rounded-lg font-semibold transition">Start Camera</button>
                    <button onclick="captureAndDetect()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 rounded-lg font-semibold transition">Detect Face</button>
                </div>
            </div>

            <!-- Controls & Results -->
            <div class="flex flex-col gap-6">
                <!-- Enrollment -->
                <div class="bg-gray-700 p-4 rounded-lg">
                    <h2 class="text-xl font-semibold mb-3 border-b border-gray-600 pb-2">Enrollment</h2>
                    <div class="flex gap-2 mb-3">
                        <input type="text" id="studentId" placeholder="Enter Student ID (e.g. 1)" class="w-full px-3 py-2 bg-gray-600 rounded border border-gray-500 focus:outline-none focus:border-blue-400">
                    </div>
                    <button onclick="enrollFace()" class="w-full px-4 py-2 bg-purple-600 hover:bg-purple-700 rounded-lg font-semibold transition">Enroll Current Face</button>
                </div>

                <!-- Results Log -->
                <div class="bg-gray-700 p-4 rounded-lg flex-grow flex flex-col h-64">
                    <h2 class="text-xl font-semibold mb-3 border-b border-gray-600 pb-2">Logs / Results</h2>
                    <div id="logs" class="flex-grow overflow-y-auto font-mono text-sm text-gray-300 space-y-1 p-2 bg-gray-800 rounded">
                        <p class="text-gray-500">System ready...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <canvas id="canvas" width="640" height="480"></canvas>

    <script>
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const ctx = canvas.getContext('2d');
        const overlay = document.getElementById('overlay');
        const logs = document.getElementById('logs');

        function log(message, type = 'info') {
            const colors = { info: 'text-gray-300', success: 'text-green-400', error: 'text-red-400' };
            const div = document.createElement('div');
            div.className = colors[type];
            div.innerText = `[${new Date().toLocaleTimeString()}] ${message}`;
            logs.prepend(div);
        }

        async function startCamera() {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({ video: { width: 640, height: 480 } });
                video.srcObject = stream;
                log("Camera started", "success");
            } catch (err) {
                log("Error accessing camera: " + err.message, "error");
            }
        }

        function getFrameBlob() {
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
            return new Promise(resolve => canvas.toBlob(resolve, 'image/jpeg'));
        }

        async function captureAndDetect() {
            const blob = await getFrameBlob();
            const formData = new FormData();
            formData.append('image', blob, 'capture.jpg');

            log("Sending image for detection...");
            
            try {
                const response = await fetch('/api/face/detect', {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                });
                const data = await response.json();

                // Clear previous boxes
                overlay.innerHTML = '';

                if (data.faces && data.faces.length > 0) {
                    log(`Detected ${data.faces.length} face(s)`, "success");
                    data.faces.forEach(face => {
                        drawBox(face.bbox);
                    });
                } else {
                    log("No faces detected", "error");
                }
            } catch (err) {
                log("Detection failed: " + err.message, "error");
            }
        }

        async function enrollFace() {
            const studentId = document.getElementById('studentId').value;
            if (!studentId) {
                log("Please enter a Student ID", "error");
                return;
            }

            const blob = await getFrameBlob();
            const formData = new FormData();
            formData.append('image', blob, 'enroll.jpg');
            formData.append('student_id', studentId);

            log(`Enrolling face for Student ID: ${studentId}...`);

            try {
                const response = await fetch('/api/face/enroll', {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                });
                const data = await response.json();

                if (response.ok) {
                    log("Enrollment successful!", "success");
                    console.log(data);
                } else {
                    let errorMsg = data.error || "Unknown error";
                    if (data.python_response) {
                        errorMsg += " (" + JSON.stringify(data.python_response) + ")";
                    }
                    log("Enrollment failed: " + errorMsg, "error");
                }
            } catch (err) {
                log("Enrollment error: " + err.message, "error");
            }
        }

        function drawBox(bbox) {
            // bbox is [x1, y1, x2, y2]
            // Video is mirrored via CSS, so we need to flip coordinates if drawing on top?
            // Actually, if we draw absolute divs, we just need to calculate left/top/width/height.
            // Since video is scaleX(-1), the overlay should probably also be scaleX(-1) to match, 
            // OR we calculate the mirrored position. Let's try simple first.
            
            const [x1, y1, x2, y2] = bbox;
            const width = x2 - x1;
            const height = y2 - y1;
            
            const div = document.createElement('div');
            div.className = 'face-box';
            div.style.left = `${x1}px`;
            div.style.top = `${y1}px`;
            div.style.width = `${width}px`;
            div.style.height = `${height}px`;
            
            // Adjust for mirrored video if needed. 
            // For now, let's assume the backend returns coordinates matching the image sent.
            // If the image sent is from the canvas (which draws the video frame), it is NOT mirrored unless we flip it on canvas.
            // The video element is visually mirrored with CSS, but the underlying stream is not.
            // So the coordinates will match the "unmirrored" stream.
            // To make the box match the mirrored video, we need to mirror the x-coordinates relative to the container width.
            
            // Mirroring logic: new_x = container_width - old_x - width
            const containerWidth = 640;
            div.style.left = `${containerWidth - x1 - width}px`;

            overlay.appendChild(div);
        }

        // Start camera on load
        startCamera();
    </script>
</body>
</html>
