<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Student Course Enrollments') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-end mb-4">
                <a href="{{ route('admin.enrollments.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Enroll Student</a>
            </div>
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr>
                                <th class="p-3 border-b border-gray-700">Student</th>
                                <th class="p-3 border-b border-gray-700">Course</th>
                                <th class="p-3 border-b border-gray-700">Academic Year</th>
                                <th class="p-3 border-b border-gray-700">Semester</th>
                                <th class="p-3 border-b border-gray-700">Section</th>
                                <th class="p-3 border-b border-gray-700">Status</th>
                                <th class="p-3 border-b border-gray-700">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($enrollments as $enrollment)
                            <tr class="hover:bg-gray-700/50">
                                <td class="p-3 border-b border-gray-700">
                                    {{ $enrollment->student->user->name }}<br>
                                    <span class="text-xs text-gray-400">{{ $enrollment->student->student_id }}</span>
                                </td>
                                <td class="p-3 border-b border-gray-700">
                                    {{ $enrollment->course->course_code }}<br>
                                    <span class="text-xs text-gray-400">{{ $enrollment->course->course_name }}</span>
                                </td>
                                <td class="p-3 border-b border-gray-700">{{ $enrollment->academic_year }}</td>
                                <td class="p-3 border-b border-gray-700">{{ ucfirst($enrollment->semester) }}</td>
                                <td class="p-3 border-b border-gray-700">{{ $enrollment->section ?? '-' }}</td>
                                <td class="p-3 border-b border-gray-700">
                                    <span class="px-2 py-1 rounded text-xs 
                                        @if($enrollment->enrollment_status == 'enrolled') bg-green-600
                                        @elseif($enrollment->enrollment_status == 'completed') bg-blue-600
                                        @elseif($enrollment->enrollment_status == 'dropped') bg-red-600
                                        @else bg-gray-600
                                        @endif">
                                        {{ ucfirst($enrollment->enrollment_status) }}
                                    </span>
                                </td>
                                <td class="p-3 border-b border-gray-700">
                                    <form action="{{ route('admin.enrollments.destroy', $enrollment) }}" method="POST" class="inline">
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
