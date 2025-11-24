<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Enroll Student in Course') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-600 text-white rounded-lg">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('admin.enrollments.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-300">Student</label>
                                <select name="student_id" class="mt-1 block w-full bg-gray-900 border-gray-700 rounded-md shadow-sm text-gray-100" required>
                                    <option value="">Select Student</option>
                                    @foreach($students as $student)
                                        <option value="{{ $student->id }}">{{ $student->student_id }} - {{ $student->user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300">Course</label>
                                <select name="course_id" class="mt-1 block w-full bg-gray-900 border-gray-700 rounded-md shadow-sm text-gray-100" required>
                                    <option value="">Select Course</option>
                                    @foreach($courses as $course)
                                        <option value="{{ $course->id }}" {{ (isset($selectedCourseId) && $selectedCourseId == $course->id) ? 'selected' : '' }}>
                                            {{ $course->course_code }} - {{ $course->course_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-300">Academic Year</label>
                                <input type="text" name="academic_year" placeholder="2024-2025" class="mt-1 block w-full bg-gray-900 border-gray-700 rounded-md shadow-sm text-gray-100" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300">Semester</label>
                                <select name="semester" class="mt-1 block w-full bg-gray-900 border-gray-700 rounded-md shadow-sm text-gray-100" required>
                                    <option value="odd">Odd</option>
                                    <option value="even">Even</option>
                                    <option value="summer">Summer</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300">Section</label>
                                <input type="text" name="section" placeholder="A" class="mt-1 block w-full bg-gray-900 border-gray-700 rounded-md shadow-sm text-gray-100">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300">Course Assignment (Optional)</label>
                            <select name="course_assignment_id" class="mt-1 block w-full bg-gray-900 border-gray-700 rounded-md shadow-sm text-gray-100">
                                <option value="">No Assignment</option>
                                @foreach($assignments as $assignment)
                                    <option value="{{ $assignment->id }}">
                                        {{ $assignment->course->course_code }} - {{ $assignment->faculty->user->name }} ({{ $assignment->session_year }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Enroll Student</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
