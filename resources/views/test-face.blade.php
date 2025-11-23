<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Face Recognition System</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        .video-wrapper { position: relative; width: 100%; max-width: 640px; margin: 0 auto; aspect-ratio: 4/3; background: #000; border-radius: 12px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.5); }
        video { width: 100%; height: 100%; object-fit: cover; transform: scaleX(-1); }
        canvas { display: none; }
        .overlay { position: absolute; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none; }
        .face-box { position: absolute; border: 2px solid #3b82f6; background: rgba(59, 130, 246, 0.1); border-radius: 4px; transition: all 0.1s ease; }
        .face-label { position: absolute; top: -25px; left: 0; background: #3b82f6; color: white; padding: 2px 8px; font-size: 12px; border-radius: 4px; white-space: nowrap; }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #1f2937; }
        ::-webkit-scrollbar-thumb { background: #4b5563; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #6b7280; }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen font-sans antialiased selection:bg-blue-500 selection:text-white">

    <div class="container mx-auto px-4 py-8 max-w-6xl">
        <header class="flex justify-between items-center mb-8">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-blue-600 rounded-lg">
                    <i data-lucide="scan-face" class="w-8 h-8 text-white"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold tracking-tight">FaceGuard AI</h1>
                    <p class="text-gray-400 text-sm">Attendance & Security System</p>
                </div>
            </div>
            <div class="flex gap-2">
                <span class="px-3 py-1 bg-green-900/30 text-green-400 border border-green-800 rounded-full text-xs font-medium flex items-center gap-1">
                    <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span> System Online
                </span>
            </div>
        </header>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Camera Feed -->
            <div class="lg:col-span-2 space-y-6">
                <div class="video-wrapper group">
                    <video id="video" autoplay playsinline></video>
                    <div id="overlay" class="overlay"></div>
                    
                    <!-- Camera Controls Overlay -->
                    <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <button onclick="startCamera()" class="p-3 bg-gray-800/80 hover:bg-blue-600 text-white rounded-full backdrop-blur-sm transition-all shadow-lg" title="Restart Camera">
                            <i data-lucide="camera" class="w-5 h-5"></i>
                        </button>
                        <button onclick="toggleAutoDetect()" id="autoDetectBtn" class="p-3 bg-gray-800/80 hover:bg-green-600 text-white rounded-full backdrop-blur-sm transition-all shadow-lg" title="Auto Detect">
                            <i data-lucide="zap" class="w-5 h-5"></i>
                        </button>
                    </div>
                </div>

                <!-- Stats Bar -->
                <div class="grid grid-cols-3 gap-4">
                    <div class="bg-gray-800 p-4 rounded-xl border border-gray-700">
                        <p class="text-gray-400 text-xs uppercase font-semibold">Faces Detected</p>
                        <p class="text-2xl font-bold text-blue-400" id="facesCount">0</p>
                    </div>
                    <div class="bg-gray-800 p-4 rounded-xl border border-gray-700">
                        <p class="text-gray-400 text-xs uppercase font-semibold">Processing Time</p>
                        <p class="text-2xl font-bold text-purple-400" id="procTime">- ms</p>
                    </div>
                    <div class="bg-gray-800 p-4 rounded-xl border border-gray-700">
                        <p class="text-gray-400 text-xs uppercase font-semibold">Confidence</p>
                        <p class="text-2xl font-bold text-green-400" id="confidenceScore">-</p>
                    </div>
                </div>
            </div>

            <!-- Right Column: Controls & Logs -->
            <div class="space-y-6 flex flex-col h-full">
                <!-- Control Panel -->
                <div class="bg-gray-800 rounded-xl border border-gray-700 overflow-hidden shadow-xl">
                    <!-- Tabs -->
                    <div class="flex border-b border-gray-700">
                        <button onclick="switchTab('detect')" id="tab-detect" class="flex-1 py-3 text-sm font-medium text-blue-400 border-b-2 border-blue-500 bg-gray-700/50 transition-colors">
                            Detect
                        </button>
                        <button onclick="switchTab('enroll')" id="tab-enroll" class="flex-1 py-3 text-sm font-medium text-gray-400 hover:text-gray-200 transition-colors">
                            Enroll
                        </button>
                        <button onclick="switchTab('identify')" id="tab-identify" class="flex-1 py-3 text-sm font-medium text-gray-400 hover:text-gray-200 transition-colors">
                            Recognize
                        </button>
                    </div>

                    <div class="p-6">
                        <!-- Detect Tab -->
                        <div id="content-detect" class="space-y-4">
                            <p class="text-gray-400 text-sm">Real-time face detection mode. Use this to verify camera angle and lighting.</p>
                            <button onclick="captureAndDetect()" class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition-all shadow-lg shadow-blue-900/20 flex items-center justify-center gap-2">
                                <i data-lucide="scan" class="w-4 h-4"></i> Detect Now
                            </button>
                        </div>

                        <!-- Enroll Tab -->
                        <div id="content-enroll" class="space-y-4 hidden">
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-gray-400 uppercase">Student ID</label>
                                <input type="text" id="studentId" placeholder="Enter ID (e.g. 2024001)" class="w-full px-4 py-3 bg-gray-900 border border-gray-600 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all placeholder-gray-600">
                            </div>
                            <button onclick="enrollFace()" class="w-full py-3 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-semibold transition-all shadow-lg shadow-purple-900/20 flex items-center justify-center gap-2">
                                <i data-lucide="user-plus" class="w-4 h-4"></i> Enroll Face
                            </button>
                        </div>

                        <!-- Identify Tab -->
                        <div id="content-identify" class="space-y-4 hidden">
                            <p class="text-gray-400 text-sm">Identify enrolled students from the database.</p>
                            <button onclick="identifyFace()" class="w-full py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold transition-all shadow-lg shadow-green-900/20 flex items-center justify-center gap-2">
                                <i data-lucide="search" class="w-4 h-4"></i> Identify Person
                            </button>
                            
                            <!-- Result Card -->
                            <div id="identityResult" class="hidden mt-4 p-4 bg-gray-900 rounded-lg border border-gray-700">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 bg-gray-700 rounded-full flex items-center justify-center">
                                        <i data-lucide="user" class="w-6 h-6 text-gray-400"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-white" id="resultName">Unknown</h3>
                                        <p class="text-xs text-gray-400" id="resultId">ID: -</p>
                                    </div>
                                </div>
                                <div class="mt-3 pt-3 border-t border-gray-800 flex justify-between text-xs">
                                    <span class="text-gray-500">Match Score</span>
                                    <span class="text-green-400 font-mono" id="resultScore">0.00%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Logs -->
                <div class="bg-gray-800 rounded-xl border border-gray-700 flex-grow flex flex-col overflow-hidden shadow-xl">
                    <div class="p-3 border-b border-gray-700 bg-gray-800/50 flex justify-between items-center">
                        <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">System Logs</h3>
                        <button onclick="clearLogs()" class="text-gray-500 hover:text-white transition-colors"><i data-lucide="trash-2" class="w-3 h-3"></i></button>
                    </div>
                    <div id="logs" class="flex-grow overflow-y-auto p-3 font-mono text-xs space-y-2 h-48">
                        <div class="text-blue-400 border-l-2 border-blue-500 pl-2">System initialized...</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <canvas id="canvas" width="640" height="480"></canvas>

    <script>
        lucide.createIcons();
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const ctx = canvas.getContext('2d');
        const overlay = document.getElementById('overlay');
        const logs = document.getElementById('logs');
        let autoDetectInterval = null;

        function switchTab(tab) {
            ['detect', 'enroll', 'identify'].forEach(t => {
                document.getElementById(`content-${t}`).classList.add('hidden');
                document.getElementById(`tab-${t}`).classList.remove('text-blue-400', 'border-b-2', 'border-blue-500', 'bg-gray-700/50');
                document.getElementById(`tab-${t}`).classList.add('text-gray-400');
            });
            document.getElementById(`content-${tab}`).classList.remove('hidden');
            document.getElementById(`tab-${tab}`).classList.add('text-blue-400', 'border-b-2', 'border-blue-500', 'bg-gray-700/50');
            document.getElementById(`tab-${tab}`).classList.remove('text-gray-400');
        }

        function log(message, type = 'info') {
            const colors = { info: 'text-blue-400 border-blue-500', success: 'text-green-400 border-green-500', error: 'text-red-400 border-red-500' };
            const div = document.createElement('div');
            div.className = `${colors[type]} border-l-2 pl-2 animate-in fade-in slide-in-from-left-2 duration-300`;
            div.innerText = `[${new Date().toLocaleTimeString()}] ${message}`;
            logs.prepend(div);
        }

        function clearLogs() { logs.innerHTML = ''; }

        async function startCamera() {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({ video: { width: 640, height: 480 } });
                video.srcObject = stream;
                log("Camera started successfully", "success");
            } catch (err) {
                log("Camera error: " + err.message, "error");
            }
        }

        function getFrameBlob() {
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
            return new Promise(resolve => canvas.toBlob(resolve, 'image/jpeg'));
        }

        async function captureAndDetect() {
            const start = performance.now();
            const blob = await getFrameBlob();
            const formData = new FormData();
            formData.append('image', blob, 'capture.jpg');

            try {
                const response = await fetch('/api/face/detect', {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                });
                const data = await response.json();
                const duration = Math.round(performance.now() - start);

                document.getElementById('procTime').innerText = `${duration} ms`;
                overlay.innerHTML = '';

                if (data.faces && data.faces.length > 0) {
                    document.getElementById('facesCount').innerText = data.faces.length;
                    document.getElementById('confidenceScore').innerText = (data.faces[0].det_score * 100).toFixed(1) + '%';
                    data.faces.forEach(face => drawBox(face.bbox));
                } else {
                    document.getElementById('facesCount').innerText = '0';
                    document.getElementById('confidenceScore').innerText = '-';
                }
            } catch (err) {
                log("Detection failed", "error");
            }
        }

        async function enrollFace() {
            const studentId = document.getElementById('studentId').value;
            if (!studentId) {
                log("Enter Student ID first", "error");
                document.getElementById('studentId').focus();
                return;
            }

            const blob = await getFrameBlob();
            const formData = new FormData();
            formData.append('image', blob, 'enroll.jpg');
            formData.append('student_id', studentId);

            log(`Enrolling ID: ${studentId}...`);

            try {
                const response = await fetch('/api/face/enroll', {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                });
                const data = await response.json();

                if (response.ok) {
                    log("Enrollment successful!", "success");
                } else {
                    let errorMsg = data.error || "Unknown error";
                    if (data.python_response) errorMsg += ` (${JSON.stringify(data.python_response)})`;
                    log("Failed: " + errorMsg, "error");
                }
            } catch (err) {
                log("Error: " + err.message, "error");
            }
        }

        async function identifyFace() {
            const blob = await getFrameBlob();
            const formData = new FormData();
            formData.append('image', blob, 'identify.jpg');

            log("Identifying...");

            try {
                const response = await fetch('/api/face/identify', {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                });
                const data = await response.json();

                overlay.innerHTML = '';
                const resultCard = document.getElementById('identityResult');
                
                if (data.student) {
                    log(`Identified: ${data.student.student_id}`, "success");
                    drawBox(data.bbox, data.student.student_id);
                    
                    resultCard.classList.remove('hidden');
                    document.getElementById('resultName').innerText = "Student Found"; // Or name if available
                    document.getElementById('resultId').innerText = `ID: ${data.student.student_id}`;
                    document.getElementById('resultScore').innerText = (data.similarity * 100).toFixed(2) + '%';
                } else {
                    log("Unknown person", "error");
                    if(data.bbox) drawBox(data.bbox, "Unknown");
                    resultCard.classList.add('hidden');
                }
            } catch (err) {
                log("Identification error: " + err.message, "error");
            }
        }

        function drawBox(bbox, label = null) {
            const [x1, y1, x2, y2] = bbox;
            const width = x2 - x1;
            const height = y2 - y1;
            
            const div = document.createElement('div');
            div.className = 'face-box';
            
            // Mirroring logic
            const containerWidth = 640; 
            // In a real responsive scenario, we'd need to scale these coordinates based on actual video display size
            // For now assuming 640px width fixed in CSS
            
            div.style.left = `${containerWidth - x1 - width}px`;
            div.style.top = `${y1}px`;
            div.style.width = `${width}px`;
            div.style.height = `${height}px`;
            
            if (label) {
                const labelDiv = document.createElement('div');
                labelDiv.className = 'face-label';
                labelDiv.innerText = label;
                div.appendChild(labelDiv);
            }

            overlay.appendChild(div);
        }
        
        function toggleAutoDetect() {
            const btn = document.getElementById('autoDetectBtn');
            if (autoDetectInterval) {
                clearInterval(autoDetectInterval);
                autoDetectInterval = null;
                btn.classList.remove('bg-green-600');
                btn.classList.add('bg-gray-800/80');
                log("Auto-detect stopped");
            } else {
                autoDetectInterval = setInterval(captureAndDetect, 1000); // Every 1s
                btn.classList.remove('bg-gray-800/80');
                btn.classList.add('bg-green-600');
                log("Auto-detect started");
            }
        }

        startCamera();
    </script>
</body>
</html>
