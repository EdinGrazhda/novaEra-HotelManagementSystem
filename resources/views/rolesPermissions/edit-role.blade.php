<x-layouts.app :title="__('Roles Management')">
    <div class="container mx-auto px-4 py-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-0 mb-6">
            <h1 class="text-xl sm:text-2xl font-bold text-gray-800 dark:text-white">Edit Role: {{ $role->name }}</h1>
            <a href="{{ route('roles.index') }}" class="bg-gray-500 text-white px-3 py-2 rounded-md hover:bg-gray-600 text-sm sm:text-base flex items-center whitespace-nowrap">
                <i class="fas fa-arrow-left mr-1 sm:mr-2 flex-shrink-0"></i> Back to Roles
            </a>
        </div>        <!-- Flash messages -->
        @if(session('error'))
        <div id="error-alert" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
        @endif
        
        <script>
            // Auto-hide flash messages after 1.5 seconds
            document.addEventListener('DOMContentLoaded', function() {
                const errorAlert = document.getElementById('error-alert');
                if (errorAlert) {
                    setTimeout(function() {
                        errorAlert.style.transition = 'opacity 0.5s ease-out';
                        errorAlert.style.opacity = '0';
                        setTimeout(function() {
                            errorAlert.style.display = 'none';
                        }, 500);
                    }, 1500);
                }
            });
        </script>

        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
            <div class="p-4 sm:p-6">
                <form action="{{ route('roles.update', $role->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-4 sm:mb-6">
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Role Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $role->name) }}" 
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                            @if($role->name === 'admin') readonly @endif required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4 sm:mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Permissions</label>
                        <div class="bg-gray-50 dark:bg-gray-700 p-3 sm:p-4 border border-gray-300 dark:border-gray-600 rounded-md max-h-64 sm:max-h-80 overflow-y-auto">
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2 sm:gap-3">
                                @foreach($permissions as $permission)
                                    <div class="flex items-center space-x-2 sm:space-x-3">
                                        <input type="checkbox" name="permissions[]" id="permission_{{ $permission->id }}" 
                                            value="{{ $permission->id }}" class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                            @if(in_array($permission->id, old('permissions', $rolePermissions))) checked @endif>
                                        <label for="permission_{{ $permission->id }}" class="text-xs sm:text-sm text-gray-700 dark:text-gray-300">
                                            {{ $permission->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @error('permissions')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex sm:justify-end">
                        <button type="submit" class="w-full sm:w-auto bg-blue-600 text-white px-4 sm:px-6 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 text-sm flex items-center justify-center">
                            <i class="fas fa-save mr-1 sm:mr-2 flex-shrink-0"></i> Update Role
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>
