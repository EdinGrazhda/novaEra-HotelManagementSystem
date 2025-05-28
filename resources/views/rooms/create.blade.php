<x-layouts.app :title="__('Create Room')">
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="bg-[#F8B803] p-6">
            <h1 class="text-2xl font-semibold text-[#1B1B18]">Add New Room</h1>
            <p class="text-[#1B1B18] opacity-80">Create a new room in the hotel management system</p>
        </div>
        
        <form action="{{ route('rooms.store') }}" method="POST" class="p-6">
            @csrf
            
            @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                    <p class="font-bold">Please correct the following errors:</p>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="room_number" class="block text-sm font-medium text-gray-700 mb-1">Room Number <span class="text-red-500">*</span></label>
                    <input type="text" name="room_number" id="room_number" value="{{ old('room_number') }}" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#F8B803]" 
                        required placeholder="Enter room number (e.g. 101)">
                </div>
                
                <div>
                    <label for="room_floor" class="block text-sm font-medium text-gray-700 mb-1">Floor <span class="text-red-500">*</span></label>
                    <input type="text" name="room_floor" id="room_floor" value="{{ old('room_floor') }}" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#F8B803]" 
                        required placeholder="Enter floor (e.g. 1st Floor)">
                </div>
                
                <div>
                    <label for="room_type" class="block text-sm font-medium text-gray-700 mb-1">Room Type <span class="text-red-500">*</span></label>
                    <select name="room_type" id="room_type" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#F8B803]" 
                        required>
                        <option value="" disabled {{ old('room_type') ? '' : 'selected' }}>Select room type</option>
                        <option value="single" {{ old('room_type') == 'single' ? 'selected' : '' }}>Single</option>
                        <option value="double" {{ old('room_type') == 'double' ? 'selected' : '' }}>Double</option>
                        <option value="suite" {{ old('room_type') == 'suite' ? 'selected' : '' }}>Suite</option>
                    </select>
                </div>
                
                <div>
                    <label for="room_status" class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                    <select name="room_status" id="room_status" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#F8B803]" 
                        required>
                        <option value="" disabled {{ old('room_status') ? '' : 'selected' }}>Select status</option>
                        <option value="available" {{ old('room_status') == 'available' ? 'selected' : '' }}>Available</option>
                        <option value="occupied" {{ old('room_status') == 'occupied' ? 'selected' : '' }}>Occupied</option>
                        <option value="maintenance" {{ old('room_status') == 'maintenance' ? 'selected' : '' }}>Under Maintenance</option>
                    </select>
                </div>
                
                <div>
                    <label for="room_category_id" class="block text-sm font-medium text-gray-700 mb-1">Room Category <span class="text-red-500">*</span></label>
                    <select name="room_category_id" id="room_category_id" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#F8B803]" 
                        required>
                        <option value="" disabled {{ old('room_category_id') ? '' : 'selected' }}>Select category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('room_category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->category_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="md:col-span-2">
                    <label for="room_description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="room_description" id="room_description" rows="4" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#F8B803]" 
                        placeholder="Enter room description">{{ old('room_description') }}</textarea>
                </div>
            </div>
            
            <div class="flex justify-end mt-6 space-x-3">
                <a href="{{ route('rooms.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition duration-200">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-[#F8B803] text-[#1B1B18] font-medium rounded-md hover:bg-yellow-500 transition duration-200">
                    Save Room
                </button>
            </div>
        </form>
    </div>
</div>
</x-layouts.app>