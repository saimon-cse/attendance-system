<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Teacher Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-end mb-4">
                <a href="{{ route('teacher.sessions.create') }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Start New Session</a>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-bold mb-4">My Courses</h3>
                    @if($assignments->isEmpty())
                        <p class="text-gray-400">No courses assigned yet.</p>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($assignments as $assignment)
                                <div class="p-4 bg-gray-700 rounded-lg shadow">
                                    <h4 class="text-xl font-bold text-white">{{ $assignment->course->course_code }}</h4>
                                    <p class="text-gray-300">{{ $assignment->course->course_name }}</p>
                                    <div class="mt-2 text-sm text-gray-400">
                                        <p>Year: {{ $assignment->academic_year }}</p>
                                        <p>Semester: {{ $assignment->semester }}</p>
                                        <p>Section: {{ $assignment->section }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
