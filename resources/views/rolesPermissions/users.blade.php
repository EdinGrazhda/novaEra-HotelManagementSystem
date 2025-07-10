<x-layouts.app :title="__('User Management')">
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">User Management</h1>
        </div>
        
        <!-- Flash messages -->
        @if(session('success'))
        <div id="success-alert" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
        @endif

        @if(session('error'))
        <div id="error-alert" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
        @endif
        
        <script>
            // Auto-hide flash messages after 1.5 seconds
            document.addEventListener('DOMContentLoaded', function() {
                // Success message
                const successAlert = document.getElementById('success-alert');
                if (successAlert) {
                    setTimeout(function() {
                        successAlert.style.transition = 'opacity 0.5s ease-out';
                        successAlert.style.opacity = '0';
                        setTimeout(function() {
                            successAlert.style.display = 'none';
                        }, 500);
                    }, 1500);
                }
                
                // Error message
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

        <!-- Users Section -->
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg mb-8 overflow-hidden">
            <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4 border-b dark:border-gray-600">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Users & Their Roles</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">User</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Email</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Roles</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($users as $user)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">{{ $user->email }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-1">
                                    @foreach($user->roles as $role)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                        {{ $role->name }}
                                    </span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                <a href="{{ route('users.edit.roles', $user->id) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                    <i class="fas fa-user-tag"></i> Manage Roles
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination Links -->
            <div class="px-6 py-4">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</x-layouts.app>
