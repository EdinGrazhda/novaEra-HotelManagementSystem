<x-layouts.app :title="__('Edit Menu')">
    <div class="container mx-auto px-4 py-8">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-[#1B1B18]">Edit Menu Item</h1>
            <a href="{{ route('menu.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 font-medium rounded-md hover:bg-gray-300 transition duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M7.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Back to Menu List
            </a>
        </div>

        <!-- Form validation errors -->
        @if($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                <p class="font-bold">Please correct the following errors:</p>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Menu edit form -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <form action="{{ route('menu.update', $menu) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <!-- Name Field -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Menu Item Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $menu->name) }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#F8B803]" 
                               required>
                    </div>
                    
                    <!-- Description Field -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" id="description" rows="4" 
                                  class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#F8B803]" 
                                  required>{{ old('description', $menu->description) }}</textarea>
                    </div>
                    
                    <!-- Submit Button -->
                    <div class="flex items-center justify-end">
                        <button type="submit" class="px-6 py-2 bg-[#F8B803] text-[#1B1B18] font-medium rounded-md hover:bg-yellow-500 transition duration-200">
                            Update Menu Item
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>
