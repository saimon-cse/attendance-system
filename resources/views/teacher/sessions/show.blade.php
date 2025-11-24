<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Attendance Session: ') }} {{ $session->courseAssignment->course->course_code }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Camera Section -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">Live Camera</h3>
                    <div class="relative">
                        <video id="video" class="w-full rounded-lg transform scale-x-[-1]" autoplay playsinline></video>
                        <canvas id="canvas" class="hidden"></canvas>
                        <div id="status-overlay" class="absolute top-4 right-4 px-3 py-1 rounded-full text-sm font-bold hidden"></div>
                    </div>
                    <div class="mt-4 flex justify-center">
                        <button id="toggle-attendance" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-bold">
                            Start Attendance
                        </button>
                    </div>
                </div>

                <!-- Recent Attendance Log -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">Recent Attendance</h3>
                    <div id="attendance-log" class="space-y-2 max-h-[400px] overflow-y-auto">
                        <!-- Attendance items will be added here -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const toggleBtn = document.getElementById('toggle-attendance');
        const logContainer = document.getElementById('attendance-log');
        const statusOverlay = document.getElementById('status-overlay');
        
        let isScanning = false;
        let scanInterval;

        // Start Camera
        navigator.mediaDevices.getUserMedia({ video: true })
            .then(stream => video.srcObject = stream)
            .catch(err => console.error("Camera error:", err));

        toggleBtn.addEventListener('click', () => {
            isScanning = !isScanning;
            toggleBtn.textContent = isScanning ? 'Stop Attendance' : 'Start Attendance';
            toggleBtn.classList.toggle('bg-red-600');
            toggleBtn.classList.toggle('bg-blue-600');
            
            if (isScanning) {
                startScanning();
            } else {
                stopScanning();
            }
        });

        function startScanning() {
            scanInterval = setInterval(captureAndSend, 2000); // Scan every 2 seconds
        }

        function stopScanning() {
            clearInterval(scanInterval);
        }

        async function captureAndSend() {
            if (!isScanning) return;

            const context = canvas.getContext('2d');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            
            const imageData = canvas.toDataURL('image/jpeg');

            try {
                const response = await fetch('{{ route("teacher.sessions.mark", $session->id) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ image: imageData })
                });

                const data = await response.json();

                if (data.success) {
                    showStatus('Match Found: ' + data.student.name, 'bg-green-500');
                    addLog(data.student.name, data.student.id, 'Present');
                } else if (data.message === 'No match found') {
                    showStatus('No Match', 'bg-yellow-500');
                }
            } catch (error) {
                console.error('Error:', error);
            }
        }

        function showStatus(text, colorClass) {
            statusOverlay.textContent = text;
            statusOverlay.className = `absolute top-4 right-4 px-3 py-1 rounded-full text-sm font-bold text-white ${colorClass}`;
            statusOverlay.classList.remove('hidden');
            setTimeout(() => statusOverlay.classList.add('hidden'), 1500);
        }

        function addLog(name, id, status) {
            const div = document.createElement('div');
            div.className = 'flex justify-between items-center p-3 bg-gray-700 rounded text-gray-200';
            div.innerHTML = `
                <div>
                    <span class="font-bold">${name}</span>
                    <span class="text-xs text-gray-400 ml-2">(${id})</span>
                </div>
                <span class="text-green-400 font-bold">${status}</span>
            `;
            logContainer.prepend(div);
        }
    </script>
</x-app-layout>
