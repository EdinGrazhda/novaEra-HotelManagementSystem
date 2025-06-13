<!-- Check In/Out Status Section -->
<div class="bg-white rounded-lg shadow-lg overflow-hidden">
    <div class="p-4 bg-[#f9b903] text-[#1B1B18]">
        <h2 class="text-xl font-semibold">Check-in/Check-out Status</h2>
    </div>
    <div class="p-6">
        <!-- Date Display -->
        <div class="text-center mb-6">
            <p class="text-sm text-gray-500">Today's Date</p>
            <p class="text-xl font-semibold text-gray-800">{{ now()->format('F j, Y') }}</p>
        </div>

        <!-- Activity Stats -->
        <div class="flex flex-col md:flex-row justify-center items-center gap-8">
            <!-- Check-ins -->
            <div class="flex flex-col items-center">
                <div class="w-24 h-24 rounded-full bg-blue-50 flex items-center justify-center mb-2 relative">
                    <span class="text-3xl font-bold text-blue-600">{{ $checkedInToday ?? 0 }}</span>
                    <div class="absolute -top-1 -right-1 rounded-full bg-[#f9b903] w-8 h-8 flex items-center justify-center shadow-sm">
                        <i class="fas fa-sign-in-alt text-white"></i>
                    </div>
                </div>
                <p class="text-gray-600 font-medium">Check-ins</p>
            </div>

            <!-- vs divider -->
            <div class="hidden md:flex flex-col items-center">
                <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center mb-2">
                    <span class="text-lg font-semibold text-gray-500">vs</span>
                </div>
                <div class="h-10 w-0.5 bg-gray-200"></div>
            </div>

            <!-- Check-outs -->
            <div class="flex flex-col items-center">
                <div class="w-24 h-24 rounded-full bg-amber-50 flex items-center justify-center mb-2 relative">
                    <span class="text-3xl font-bold text-amber-600">{{ $checkedOutToday ?? 0 }}</span>
                    <div class="absolute -top-1 -right-1 rounded-full bg-[#f9b903] w-8 h-8 flex items-center justify-center shadow-sm">
                        <i class="fas fa-sign-out-alt text-white"></i>
                    </div>
                </div>
                <p class="text-gray-600 font-medium">Check-outs</p>
            </div>
        </div>

        <!-- Pending Checkouts Alert -->
        <div class="mt-8 border-t border-gray-100 pt-4">
            <div class="bg-amber-50 border-l-4 border-amber-500 p-4 flex items-center rounded">
                <div class="rounded-full bg-amber-100 p-2 text-amber-600 mr-3">
                    <i class="fas fa-hourglass-half"></i>
                </div>
                <div>
                    <h4 class="font-semibold text-amber-700">Pending Checkouts</h4>
                    <p class="text-amber-600">
                        <span class="text-xl font-bold mr-1">{{ $pendingCheckouts ?? 0 }}</span>
                        <span class="text-sm">guests need to check out</span>
                    </p>
                </div>
                @if($pendingCheckouts > 0)
                <div class="ml-auto">
                    <button class="bg-white text-amber-700 border border-amber-200 px-3 py-1 rounded text-sm hover:bg-amber-100 transition-colors">
                        View Details
                    </button>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
