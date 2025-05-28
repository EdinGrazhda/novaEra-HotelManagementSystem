<x-layouts.app :title="__('Room Management')">
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-8">
        <div class="bg-[#F8B803] p-6">
            <h1 class="text-2xl font-semibold text-[#1B1B18]">Room Management Dashboard</h1>
            <p class="text-[#1B1B18] opacity-80">Welcome to the NovaERA Hotel Management System</p>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold mb-1">Total Rooms</h3>
                            <p class="text-3xl font-bold">{{ $totalRooms ?? 0 }}</p>
                        </div>
                        <div class="rounded-full bg-white bg-opacity-30 p-3">
                            <i class="fas fa-bed text-2xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg shadow p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold mb-1">Available Rooms</h3>
                            <p class="text-3xl font-bold">{{ $availableRooms ?? 0 }}</p>
                        </div>
                        <div class="rounded-full bg-white bg-opacity-30 p-3">
                            <i class="fas fa-check-circle text-2xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-r from-red-500 to-red-600 rounded-lg shadow p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold mb-1">Occupied Rooms</h3>
                            <p class="text-3xl font-bold">{{ $occupiedRooms ?? 0 }}</p>
                        </div>
                        <div class="rounded-full bg-white bg-opacity-30 p-3">
                            <i class="fas fa-user text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-8">
                <h2 class="text-xl font-semibold mb-4 text-[#1B1B18]">Quick Actions</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <a href="{{ route('rooms.create') }}" class="flex items-center p-4 bg-gray-50 rounded-lg border border-gray-200 hover:bg-gray-100 transition-colors">
                        <div class="rounded-full bg-[#F8B803] bg-opacity-20 p-3 mr-3">
                            <i class="fas fa-plus text-[#F8B803]"></i>
                        </div>
                        <div>
                            <h3 class="font-medium text-[#1B1B18]">Add New Room</h3>
                            <p class="text-sm text-gray-500">Create a new room</p>
                        </div>
                    </a>
                    
                    <a href="{{ route('rooms.index') }}" class="flex items-center p-4 bg-gray-50 rounded-lg border border-gray-200 hover:bg-gray-100 transition-colors">
                        <div class="rounded-full bg-blue-500 bg-opacity-20 p-3 mr-3">
                            <i class="fas fa-list text-blue-500"></i>
                        </div>
                        <div>
                            <h3 class="font-medium text-[#1B1B18]">View All Rooms</h3>
                            <p class="text-sm text-gray-500">Manage your rooms</p>
                        </div>
                    </a>
                    
                    <a href="#" class="flex items-center p-4 bg-gray-50 rounded-lg border border-gray-200 hover:bg-gray-100 transition-colors">
                        <div class="rounded-full bg-green-500 bg-opacity-20 p-3 mr-3">
                            <i class="fas fa-calendar-alt text-green-500"></i>
                        </div>
                        <div>
                            <h3 class="font-medium text-[#1B1B18]">Bookings</h3>
                            <p class="text-sm text-gray-500">Manage reservations</p>
                        </div>
                    </a>
                    
                    <a href="#" class="flex items-center p-4 bg-gray-50 rounded-lg border border-gray-200 hover:bg-gray-100 transition-colors">
                        <div class="rounded-full bg-purple-500 bg-opacity-20 p-3 mr-3">
                            <i class="fas fa-cog text-purple-500"></i>
                        </div>
                        <div>
                            <h3 class="font-medium text-[#1B1B18]">Settings</h3>
                            <p class="text-sm text-gray-500">Configure system</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
</x-layouts.app>
