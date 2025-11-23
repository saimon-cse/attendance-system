<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Course Assignments') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-end mb-4">
                <a href="{{ route('admin.assignments.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Assign Course</a>
            </div>
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr>
                                <th class="p-3 border-b border-gray-700">Course</th>
                                <th class="p-3 border-b border-gray-700">Faculty</th>
                                <th class="p-3 border-b border-gray-700">Session</th>
                                <th class="p-3 border-b border-gray-700">Section</th>
                                <th class="p-3 border-b border-gray-700">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($assignments as $assignment)
                            <tr class="hover:bg-gray-700/50">
                                <td class="p-3 border-b border-gray-700">{{ $assignment->course->course_code }} - {{ $assignment->course->name }}</td>
                                <td class="p-3 border-b border-gray-700">{{ $assignment->faculty->user->name }}</td>
                                <td class="p-3 border-b border-gray-700">{{ $assignment->session_year }}</td>
                                <td class="p-3 border-b border-gray-700">{{ $assignment->section }}</td>
                                <td class="p-3 border-b border-gray-700">
                                    <form action="{{ route('admin.assignments.destroy', $assignment) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:underline" onclick="return confirm('Are you sure?')">Remove</button>
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
