<!-- Real-time Check In/Out Status Section -->
<div class="bg-white rounded-lg shadow-lg overflow-hidden" 
    wire:poll.7s="poll"
    x-data="{
        lastUpdate: '',
        isUpdating: false,
        checkedInToday: @entangle('checkedInToday'),
        checkedOutToday: @entangle('checkedOutToday'),
        pendingCheckouts: @entangle('pendingCheckouts'),
        
        init() {
            this.$wire.on('checkinCheckoutStatusUpdated', (timestamp) => {
                this.lastUpdate = timestamp;
                this.highlightChanges();
            });
        },
        
        highlightChanges() {
            this.isUpdating = true;
            setTimeout(() => {
                this.isUpdating = false;
            }, 2000);
        }
    }">
    <div class="p-4 bg-[#f9b903] text-[#1B1B18] flex justify-between items-center">
        <h2 class="text-xl font-semibold">Check-in/Check-out Status</h2>
        <div class="text-xs flex items-center gap-1">
            <span>Updated: {{ $lastUpdated }}</span>
            <span x-show="isUpdating" class="inline-block ml-1">
                <i class="fas fa-sync-alt fa-spin text-[#1B1B18]"></i>
            </span>
        </div>
    </div>
    <div class="p-6">
        <!-- Date Display -->
        <div class="text-center mb-6">
            <p class="text-sm text-gray-500">Today's Date</p>
            <p class="text-xl font-semibold text-gray-800">{{  now()->format('H:i:s') }}</p>
        </div>

        <!-- Activity Stats -->
        <div class="flex flex-col md:flex-row justify-center items-center gap-8">
            <!-- Check-ins -->
            <div class="flex flex-col items-center">
                <div class="w-24 h-24 rounded-full bg-blue-50 flex items-center justify-center mb-2 relative"
                    x-bind:class="{'highlight-change': isUpdating && $wire.checkedInToday != checkedInToday}">
                    <span class="text-3xl font-bold text-blue-600"
                        x-text="$wire.checkedInToday"
                        x-transition:enter="transition-all duration-300"
                        x-transition:enter-start="opacity-0 transform scale-90"
                        x-transition:enter-end="opacity-100 transform scale-100">{{ $checkedInToday }}</span>
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
                <div class="w-24 h-24 rounded-full bg-amber-50 flex items-center justify-center mb-2 relative"
                    x-bind:class="{'highlight-change': isUpdating && $wire.checkedOutToday != checkedOutToday}">
                    <span class="text-3xl font-bold text-amber-600"
                        x-text="$wire.checkedOutToday"
                        x-transition:enter="transition-all duration-300"
                        x-transition:enter-start="opacity-0 transform scale-90"
                        x-transition:enter-end="opacity-100 transform scale-100">{{ $checkedOutToday }}</span>
                    <div class="absolute -top-1 -right-1 rounded-full bg-[#f9b903] w-8 h-8 flex items-center justify-center shadow-sm">
                        <i class="fas fa-sign-out-alt text-white"></i>
                    </div>
                </div>
                <p class="text-gray-600 font-medium">Check-outs</p>
            </div>
        </div>

        <!-- Pending Checkouts Alert -->
        <div class="mt-8 border-t border-gray-100 pt-4">
            <div class="bg-amber-50 border-l-4 border-amber-500 p-4 flex items-center rounded"
                x-bind:class="{'highlight-change': isUpdating && $wire.pendingCheckouts != pendingCheckouts}">
                <div class="rounded-full bg-amber-100 p-2 text-amber-600 mr-3">
                    <i class="fas fa-hourglass-half"></i>
                </div>
                <div>
                    <h4 class="font-semibold text-amber-700">Pending Checkouts</h4>
                    <p class="text-amber-600">
                        <span class="text-xl font-bold mr-1"
                            x-text="$wire.pendingCheckouts"
                            x-transition:enter="transition-all duration-300"
                            x-transition:enter-start="opacity-0 transform scale-95"
                            x-transition:enter-end="opacity-100 transform scale-100">{{ $pendingCheckouts }}</span>
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

<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('checkinCheckoutStatusUpdated', (timestamp) => {
            console.log('Check-in/check-out status update received at: ' + timestamp);
        });
    });
</script>