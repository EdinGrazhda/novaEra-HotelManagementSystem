<x-layouts.app :title="__('User Details')">
    <div class="container mx-auto px-3 sm:px-4 py-4 sm:py-8">
        <div class="mb-6 flex items-center">
            <a href="{{ url('/users') }}" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h1 class="text-2xl font-bold text-[#1B1B18] dark:text-white">User Details</h1>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
            <div class="p-6">
                <div class="flex flex-col sm:flex-row items-start sm:items-center mb-6">
                    <div class="flex-shrink-0 h-20 w-20 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center mb-4 sm:mb-0">
                        <span class="text-2xl font-medium">{{ $user->initials() }}</span>
                    </div>
                    <div class="sm:ml-6">
                        <h2 class="text-xl font-bold text-gray-800 dark:text-white">{{ $user->name }}</h2>
                        <p class="text-gray-600 dark:text-gray-300">{{ $user->email }}</p>
                        <div class="mt-3 flex flex-wrap gap-2">
                            @foreach ($user->roles as $role)
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($role->name == 'admin') bg-purple-100 text-purple-800 
                                    @elseif($role->name == 'receptionist') bg-blue-100 text-blue-800
                                    @elseif($role->name == 'cleaner') bg-green-100 text-green-800
                                    @elseif($role->name == 'chef') bg-yellow-100 text-yellow-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($role->name) }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>

                <hr class="my-6 border-gray-200 dark:border-gray-700">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-800 dark:text-white mb-3">Account Information</h3>
                        <div class="space-y-3">
                            <div>
                                <span class="block text-sm font-medium text-gray-500 dark:text-gray-400">Created</span>
                                <span class="block text-gray-800 dark:text-gray-200">{{ $user->created_at->format('F j, Y \a\t g:i A') }}</span>
                            </div>
                            <div>
                                <span class="block text-sm font-medium text-gray-500 dark:text-gray-400">Last Updated</span>
                                <span class="block text-gray-800 dark:text-gray-200">{{ $user->updated_at->format('F j, Y \a\t g:i A') }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <h3 class="text-lg font-medium text-gray-800 dark:text-white mb-3">Permissions</h3>
                        <div class="space-y-1">
                            @foreach ($user->getAllPermissions() as $permission)
                                <div class="text-sm text-gray-800 dark:text-gray-200">
                                    <span class="inline-block w-2 h-2 bg-green-400 rounded-full mr-2"></span>
                                    {{ ucfirst(str_replace('-', ' ', $permission->name)) }}
                                </div>
                            @endforeach
                            
                            @if ($user->getAllPermissions()->count() === 0)
                                <span class="text-sm text-gray-500 dark:text-gray-400">No specific permissions assigned.</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex justify-end space-x-3">
                    <a href="{{ url('/users/' . $user->id . '/edit') }}" class="px-4 py-2 bg-[#F8B803] text-[#1B1B18] font-medium rounded-md hover:bg-yellow-500 transition duration-200">
                        Edit User
                    </a>
                    @if ($user->id !== request()->user()->id)
                        <form action="{{ url('/users/' . $user->id) }}" method="POST" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-red-600 text-white font-medium rounded-md hover:bg-red-700 transition duration-200"
                                onclick="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                                Delete User
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
