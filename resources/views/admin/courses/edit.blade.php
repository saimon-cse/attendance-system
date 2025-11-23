<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Course') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('admin.courses.update', $course) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')
                        <div>
                            <label class="block text-sm font-medium text-gray-300">Course Code</label>
                            <input type="text" name="course_code" value="{{ $course->course_code }}" class="mt-1 block w-full bg-gray-900 border-gray-700 rounded-md shadow-sm text-gray-100" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300">Course Name</label>
                            <input type="text" name="course_name" value="{{ $course->course_name }}" class="mt-1 block w-full bg-gray-900 border-gray-700 rounded-md shadow-sm text-gray-100" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300">Department</label>
                            <select name="department_id" class="mt-1 block w-full bg-gray-900 border-gray-700 rounded-md shadow-sm text-gray-100" required>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ $course->department_id == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-300">Credits</label>
                                <input type="number" name="credits" value="{{ $course->credits }}" class="mt-1 block w-full bg-gray-900 border-gray-700 rounded-md shadow-sm text-gray-100">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300">Semester</label>
                                <input type="number" name="semester" value="{{ $course->semester }}" class="mt-1 block w-full bg-gray-900 border-gray-700 rounded-md shadow-sm text-gray-100">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300">Description</label>
                            <textarea name="course_description" class="mt-1 block w-full bg-gray-900 border-gray-700 rounded-md shadow-sm text-gray-100">{{ $course->course_description }}</textarea>
                        </div>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
