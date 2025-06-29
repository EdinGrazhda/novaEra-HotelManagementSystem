<x-layouts.app :title="__('Route Testing')">
    <div class="container mx-auto px-3 sm:px-4 py-4 sm:py-8">
        <h1 class="text-2xl font-bold text-[#1B1B18] dark:text-white mb-4">All Registered Routes</h1>
        
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Method</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">URI</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($routes as $route)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $route['method'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ $route['uri'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ $route['name'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ $route['action'] }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="mt-8">
            <h2 class="text-xl font-bold text-[#1B1B18] dark:text-white mb-4">User Management Links</h2>
            <div class="space-y-4">
                <div>
                    <a href="/users" class="inline-block px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                        User Index (Direct URL)
                    </a>
                </div>
                <div>
                    <a href="{{ url('users') }}" class="inline-block px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600">
                        User Index (URL Helper)
                    </a>
                </div>
                <div>
                    <a href="{{ route('users.index') }}" class="inline-block px-4 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600">
                        User Index (Named Route)
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
