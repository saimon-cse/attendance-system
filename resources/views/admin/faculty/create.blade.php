<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Add Faculty') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('admin.faculty.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-300">Name</label>
                                <input type="text" name="name" class="mt-1 block w-full bg-gray-900 border-gray-700 rounded-md shadow-sm text-gray-100" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300">Email</label>
                                <input type="email" name="email" class="mt-1 block w-full bg-gray-900 border-gray-700 rounded-md shadow-sm text-gray-100" required>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-300">Password</label>
                                <input type="password" name="password" class="mt-1 block w-full bg-gray-900 border-gray-700 rounded-md shadow-sm text-gray-100" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300">Confirm Password</label>
                                <input type="password" name="password_confirmation" class="mt-1 block w-full bg-gray-900 border-gray-700 rounded-md shadow-sm text-gray-100" required>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-300">Employee ID</label>
                                <input type="text" name="employee_id" class="mt-1 block w-full bg-gray-900 border-gray-700 rounded-md shadow-sm text-gray-100" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300">Department</label>
                                <select name="department_id" class="mt-1 block w-full bg-gray-900 border-gray-700 rounded-md shadow-sm text-gray-100" required>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-300">Designation</label>
                                <input type="text" name="designation" class="mt-1 block w-full bg-gray-900 border-gray-700 rounded-md shadow-sm text-gray-100" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300">Joining Date</label>
                                <input type="date" name="joining_date" class="mt-1 block w-full bg-gray-900 border-gray-700 rounded-md shadow-sm text-gray-100">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300">Qualification</label>
                            <input type="text" name="qualification" class="mt-1 block w-full bg-gray-900 border-gray-700 rounded-md shadow-sm text-gray-100">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300">Specialization</label>
                            <input type="text" name="specialization" class="mt-1 block w-full bg-gray-900 border-gray-700 rounded-md shadow-sm text-gray-100">
                        </div>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Create Faculty</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
