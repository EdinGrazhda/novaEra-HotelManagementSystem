<x-layouts.app :title="__('Menu Details')">
    <div class="container mx-auto px-4 py-8">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-[#1B1B18]">Menu Item Details</h1>
            <div class="flex space-x-2">
                <a href="{{ route('menu.edit', $menu) }}" class="px-4 py-2 bg-blue-500 text-white font-medium rounded-md hover:bg-blue-600 transition duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                    </svg>
                    Edit
                </a>
                <a href="{{ route('menu.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 font-medium rounded-md hover:bg-gray-300 transition duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                    Back
                </a>
            </div>
        </div>

        <!-- Menu item details -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700 border-b pb-2">Menu Item Information</h3>
                        
                        <div class="mt-4 grid grid-cols-1 gap-6">
                            <!-- Name -->
                            <div>
                                <p class="text-sm text-gray-500">Name</p>
                                <p class="text-lg font-medium">{{ $menu->name }}</p>
                            </div>
                            
                            <!-- Description -->
                            <div>
                                <p class="text-sm text-gray-500">Description</p>
                                <p class="text-base">{{ $menu->description }}</p>
                            </div>
                            
                            <!-- Created & Updated -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-500">Created At</p>
                                    <p>{{ $menu->created_at->format('M d, Y H:i A') }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Last Updated</p>
                                    <p>{{ $menu->updated_at->format('M d, Y H:i A') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between pt-4 border-t">
                        <form action="{{ route('menu.destroy', $menu) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 transition duration-200"
                                    onclick="return confirm('Are you sure you want to delete this menu item?')">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                Delete Menu Item
                            </button>
                        </form>
                        
                        <a href="{{ route('menu.edit', $menu) }}" class="px-4 py-2 bg-[#F8B803] text-[#1B1B18] font-medium rounded-md hover:bg-yellow-500 transition duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                            </svg>
                            Edit Menu Item
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
