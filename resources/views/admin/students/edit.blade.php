<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Student') }} - {{ $student->user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Basic Info Section -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-bold mb-4">Basic Information</h3>
                    <form action="{{ route('admin.students.update', $student) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-300">Full Name</label>
                                <input type="text" name="name" value="{{ $student->user->name }}" class="mt-1 block w-full bg-gray-900 border-gray-700 rounded-md shadow-sm text-gray-100" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300">Email</label>
                                <input type="email" name="email" value="{{ $student->user->email }}" class="mt-1 block w-full bg-gray-900 border-gray-700 rounded-md shadow-sm text-gray-100" required>
                            </div>
                        </div>
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-300">Student ID</label>
                                <input type="text" name="student_id" value="{{ $student->student_id }}" class="mt-1 block w-full bg-gray-900 border-gray-700 rounded-md shadow-sm text-gray-100" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300">Enrollment Number</label>
                                <input type="text" name="enrollment_number" value="{{ $student->enrollment_number }}" class="mt-1 block w-full bg-gray-900 border-gray-700 rounded-md shadow-sm text-gray-100">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300">Department</label>
                                <select name="department_id" class="mt-1 block w-full bg-gray-900 border gray-700 rounded-md shadow-sm text-gray-100" required>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}" {{ $student->department_id == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="grid grid-cols-4 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-300">Session Year</label>
                                <input type="text" name="session_year" value="{{ $student->session_year }}" class="mt-1 block w-full bg-gray-900 border-gray-700 rounded-md shadow-sm text-gray-100" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300">Current Year</label>
                                <input type="number" name="current_year" value="{{ $student->current_year }}" min="1" max="4" class="mt-1 block w-full bg-gray-900 border-gray-700 rounded-md shadow-sm text-gray-100" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300">Current Semester</label>
                                <input type="number" name="current_semester" value="{{ $student->current_semester }}" min="1" max="8" class="mt-1 block w-full bg-gray-900 border-gray-700 rounded-md shadow-sm text-gray-100" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300">Program Type</label>
                                <select name="program_type" class="mt-1 block w-full bg-gray-900 border-gray-700 rounded-md shadow-sm text-gray-100" required>
                                    <option value="undergraduate" {{ $student->program_type == 'undergraduate' ? 'selected' : '' }}>Undergraduate</option>
                                    <option value="postgraduate" {{ $student->program_type == 'postgraduate' ? 'selected' : '' }}>Postgraduate</option>
                                    <option value="doctoral" {{ $student->program_type == 'doctoral' ? 'selected' : '' }}>Doctoral</option>
                                    <option value="diploma" {{ $student->program_type == 'diploma' ? 'selected' : '' }}>Diploma</option>
                                </select>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-300">Degree Name</label>
                                <input type="text" name="degree_name" value="{{ $student->degree_name }}" class="mt-1 block w-full bg-gray-900 border-gray-700 rounded-md shadow-sm text-gray-100" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300">Enrollment Status</label>
                                <select name="enrollment_status" class="mt-1 block w-full bg-gray-900 border-gray-700 rounded-md shadow-sm text-gray-100">
                                    <option value="active" {{ $student->enrollment_status == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ $student->enrollment_status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    <option value="suspended" {{ $student->enrollment_status == 'suspended' ? 'selected' : '' }}>Suspended</option>
                                    <option value="graduated" {{ $student->enrollment_status == 'graduated' ? 'selected' : '' }}>Graduated</option>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Update Student</button>
                    </form>
                </div>
            </div>

            <!-- Face Enrollment Section -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-bold mb-4">Face Enrollment</h3>
                    <div class="mb-4">
                        <p class="text-sm text-gray-400">Current Status: 
                            <span class="px-2 py-1 rounded text-xs {{ $student->face_enrollment_status == 'enrolled' ? 'bg-green-600' : 'bg-yellow-600' }}">
                                {{ ucfirst($student->face_enrollment_status) }}
                            </span>
                        </p>
                        @if($student->face_enrolled_at)
                            <p class="text-sm text-gray-400">Enrolled At: {{ $student->face_enrolled_at->format('Y-m-d H:i:s') }}</p>
                        @endif
                    </div>
                    
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-medium mb-2">Camera Preview</h4>
                            <video id="camera-preview" autoplay playsinline class="w-full bg-black rounded-lg"></video>
                            <div class="mt-2 flex gap-2">
                                <button id="start-camera-btn" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Start Camera</button>
                                <button id="capture-btn" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700" disabled>Capture Face</button>
                            </div>
                        </div>
                        <div>
                            <h4 class="font-medium mb-2">Captured Image</h4>
                            <canvas id="captured-canvas" class="w-full bg-black rounded-lg"></canvas>
                            <div class="mt-2">
                                <button id="enroll-btn" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700" disabled>Enroll Face</button>
                                <button id="retake-btn" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700" disabled>Retake</button>
                            </div>
                        </div>
                    </div>
                    <div id="face-message" class="mt-4 p-3 rounded-lg hidden"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const video = document.getElementById('camera-preview');
        const canvas = document.getElementById('captured-canvas');
        const ctx = canvas.getContext('2d');
        const startCameraBtn = document.getElementById('start-camera-btn');
        const captureBtn = document.getElementById('capture-btn');
        const enrollBtn = document.getElementById('enroll-btn');
        const retakeBtn = document.getElementById('retake-btn');
        const messageDiv = document.getElementById('face-message');
        
        let stream = null;
        let capturedImageData = null;

        startCameraBtn.addEventListener('click', async () => {
            try {
                stream = await navigator.mediaDevices.getUserMedia({ 
                    video: { width: 640, height: 480 } 
                });
                video.srcObject = stream;
                captureBtn.disabled = false;
                showMessage('Camera started successfully', 'success');
            } catch (error) {
                showMessage('Failed to access camera: ' + error.message, 'error');
            }
        });

        captureBtn.addEventListener('click', () => {
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            ctx.drawImage(video, 0, 0);
            capturedImageData = canvas.toDataURL('image/jpeg');
            enrollBtn.disabled = false;
            retakeBtn.disabled = false;
            showMessage('Image captured! Click "Enroll Face" to save.', 'success');
        });

        retakeBtn.addEventListener('click', () => {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            capturedImageData = null;
            enrollBtn.disabled = true;
            retakeBtn.disabled = true;
            showMessage('Ready to capture again', 'info');
        });

        enrollBtn.addEventListener('click', async () => {
            if (!capturedImageData) {
                showMessage('No image captured!', 'error');
                return;
            }

            enrollBtn.disabled = true;
            enrollBtn.textContent = 'Enrolling...';

            try {
                const response = await fetch('{{ route("admin.students.enroll-face", $student) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        face_image: capturedImageData
                    })
                });

                const data = await response.json();

                if (response.ok) {
                    showMessage('Face enrolled successfully!', 'success');
                    setTimeout(() => window.location.reload(), 2000);
                } else {
                    showMessage('Error: ' + (data.error || 'Failed to enroll face'), 'error');
                    enrollBtn.disabled = false;
                    enrollBtn.textContent = 'Enroll Face';
                }
            } catch (error) {
                showMessage('Network error: ' + error.message, 'error');
                enrollBtn.disabled = false;
                enrollBtn.textContent = 'Enroll Face';
            }
        });

        function showMessage(text, type) {
            messageDiv.textContent = text;
            messageDiv.className = 'mt-4 p-3 rounded-lg block';
            if (type === 'success') {
                messageDiv.classList.add('bg-green-600', 'text-white');
            } else if (type === 'error') {
                messageDiv.classList.add('bg-red-600', 'text-white');
            } else {
                messageDiv.classList.add('bg-blue-600', 'text-white');
            }
            messageDiv.classList.remove('hidden');
        }
    </script>
</x-app-layout>
