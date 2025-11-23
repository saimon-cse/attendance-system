<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Add Department') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('admin.departments.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-300">Department Name</label>
                            <input type="text" name="name" class="mt-1 block w-full bg-gray-900 border-gray-700 rounded-md shadow-sm text-gray-100" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300">Code</label>
                            <input type="text" name="code" class="mt-1 block w-full bg-gray-900 border-gray-700 rounded-md shadow-sm text-gray-100" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300">Description</label>
                            <textarea name="description" class="mt-1 block w-full bg-gray-900 border-gray-700 rounded-md shadow-sm text-gray-100"></textarea>
                        </div>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Create</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
