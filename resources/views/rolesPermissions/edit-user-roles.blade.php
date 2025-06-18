<x-layouts.app :title="__('Roles Management')">
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Manage Roles for {{ $user->name }}</h1>
            <a href="{{ route('roles.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                <i class="fas fa-arrow-left mr-2"></i> Back to Users
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
                    <p class="text-gray-700"><strong>Email:</strong> {{ $user->email }}</p>
                </div>

                <form action="{{ route('users.update.roles', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Assign Roles</label>
                        <div class="bg-gray-50 p-4 border border-gray-300 rounded-md">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach($roles as $role)
                                    <div class="flex items-center space-x-3">
                                        <input type="checkbox" name="roles[]" id="role_{{ $role->id }}" 
                                            value="{{ $role->id }}" class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                            @if(in_array($role->id, old('roles', $userRoles))) checked @endif>
                                        <label for="role_{{ $role->id }}" class="text-sm text-gray-700">
                                            {{ $role->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @error('roles')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            <i class="fas fa-save mr-2"></i> Update User Roles
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>
