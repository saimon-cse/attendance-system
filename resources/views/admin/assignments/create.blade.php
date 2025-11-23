<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Assign Course to Faculty') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('admin.assignments.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-300">Course</label>
                                <select name="course_id" class="mt-1 block w-full bg-gray-900 border-gray-700 rounded-md shadow-sm text-gray-100" required>
                                    @foreach($courses as $course)
                                        <option value="{{ $course->id }}">{{ $course->course_code }} - {{ $course->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300">Faculty</label>
                                <select name="faculty_id" class="mt-1 block w-full bg-gray-900 border-gray-700 rounded-md shadow-sm text-gray-100" required>
                                    @foreach($faculties as $faculty)
                                        <option value="{{ $faculty->id }}">{{ $faculty->user->name }} ({{ $faculty->designation }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-300">Session Year</label>
                                <input type="text" name="session_year" placeholder="2024-2025" class="mt-1 block w-full bg-gray-900 border-gray-700 rounded-md shadow-sm text-gray-100" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300">Semester</label>
                                <input type="number" name="semester" class="mt-1 block w-full bg-gray-900 border-gray-700 rounded-md shadow-sm text-gray-100" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300">Section</label>
                                <input type="text" name="section" placeholder="A" class="mt-1 block w-full bg-gray-900 border-gray-700 rounded-md shadow-sm text-gray-100" required>
                            </div>
                        </div>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Assign Course</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
