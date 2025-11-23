<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Courses') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-end mb-4">
                <a href="{{ route('admin.courses.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Add Course</a>
            </div>
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr>
                                <th class="p-3 border-b border-gray-700">Code</th>
                                <th class="p-3 border-b border-gray-700">Name</th>
                                <th class="p-3 border-b border-gray-700">Department</th>
                                <th class="p-3 border-b border-gray-700">Credits</th>
                                <th class="p-3 border-b border-gray-700">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($courses as $course)
                            <tr class="hover:bg-gray-700/50">
                                <td class="p-3 border-b border-gray-700">{{ $course->course_code }}</td>
                                <td class="p-3 border-b border-gray-700">{{ $course->course_name }}</td>
                                <td class="p-3 border-b border-gray-700">{{ $course->department->name }}</td>
                                <td class="p-3 border-b border-gray-700">{{ $course->credits ?? '-' }}</td>
                                <td class="p-3 border-b border-gray-700">
                                    <a href="{{ route('admin.courses.edit', $course) }}" class="text-blue-400 hover:underline mr-2">Edit</a>
                                    <form action="{{ route('admin.courses.destroy', $course) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:underline" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
