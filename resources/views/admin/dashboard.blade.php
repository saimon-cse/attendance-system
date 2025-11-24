<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-bold mb-4">Admin Management</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <a href="{{ route('admin.departments.index') }}" class="p-4 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition">
                            Manage Departments
                        </a>
                        <a href="{{ route('admin.courses.index') }}" class="p-4 bg-green-600 text-white rounded-lg shadow hover:bg-green-700 transition">
                            Manage Courses
                        </a>
                        <a href="{{ route('admin.faculty.index') }}" class="p-4 bg-purple-600 text-white rounded-lg shadow hover:bg-purple-700 transition">
                            Manage Faculty
                        </a>
                        <a href="{{ route('admin.assignments.index') }}" class="p-4 bg-orange-600 text-white rounded-lg shadow hover:bg-orange-700 transition">
                            Assign Courses
                        </a>
                        <a href="{{ route('admin.students.index') }}" class="p-4 bg-pink-600 text-white rounded-lg shadow hover:bg-pink-700 transition">
                            Manage Students
                        </a>
                        <a href="{{ route('admin.enrollments.index') }}" class="p-4 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700 transition">
                            Student Enrollments
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
