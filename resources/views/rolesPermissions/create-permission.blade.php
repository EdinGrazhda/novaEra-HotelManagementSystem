<x-layouts.app :title="__('Roles and Permissions Management')">
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Create New Permission</h1>
            <a href="{{ route('roles.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                <i class="fas fa-arrow-left mr-2"></i> Back to Permissions
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

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="p-6">
                <div class="mb-6">
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-700">
                                    <strong>Warning:</strong> Adding custom permissions may require additional code changes to make them work properly. 
                                    It's recommended to use the standard permissions defined in the migrations.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <form action="{{ route('permissions.store') }}" method="POST">
                    @csrf
                    <div class="mb-6">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Permission Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" 
                            placeholder="Format: action-resource (e.g., create-rooms)"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            required>
                        <p class="mt-1 text-xs text-gray-500">
                            Naming convention: action-resource (e.g., create-rooms, view-menus, delete-orders)
                        </p>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            <i class="fas fa-save mr-2"></i> Create Permission
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>
