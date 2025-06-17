<!-- Cleaning Status Section -->
<div wire:poll.15s="poll" class="bg-white rounded-lg shadow-lg overflow-hidden">
    <div class="p-4 bg-[#f9b903] text-[#1B1B18] flex items-center justify-between">
        <h2 class="text-xl font-semibold">Housekeeping Status</h2>
        <div class="flex items-center">
            <div class="rounded-full bg-white/70 px-3 py-1 text-xs flex items-center mr-2">
                <span class="flex items-center">
                    <i class="fas fa-sync-alt mr-1.5"></i> 
                    <span>Updated <span id="cleaning-last-updated" class="highlight-on-change">{{ now()->format('H:i:s') }}</span></span>
                </span>
            </div>
            <div class="flex items-center">
                <span class="text-xs opacity-80 mr-2">Auto-updating</span>
                <span class="relative flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                </span>
            </div>
        </div>
    </div>
    <div class="p-6">
        <!-- Cleaning Status Gauge -->
        <div class="flex flex-col items-center justify-center mb-8">
            <div class="relative w-40 h-40">
                <!-- Background circle -->
                <svg class="w-full h-full" viewBox="0 0 36 36">
                    <!-- Gray background track -->
                    <path
                        d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                        stroke="#f1f1f1"
                        stroke-width="4"
                        fill="none"
                    />
                    <!-- Clean percentage arc with gradient -->
                    <path
                        d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                        stroke="url(#cleanGradient)"
                        stroke-width="4"
                        fill="none"
                        stroke-dasharray="{{ $totalRooms > 0 ? ($cleanRooms / $totalRooms) * 100 : 0 }}, 100"
                        stroke-linecap="round"
                        class="clean-percentage-arc"
                        filter="url(#glow)"
                        wire:key="clean-arc-{{ $cleanRooms }}"
                    >
                        <animate 
                            attributeName="stroke-dasharray" 
                            from="0, 100" 
                            to="{{ $totalRooms > 0 ? ($cleanRooms / $totalRooms) * 100 : 0 }}, 100" 
                            dur="1.5s"
                            begin="0s"
                            fill="freeze"
                            restart="whenNotActive"
                        />
                    </path>
                    <!-- SVG Gradient definition with enhanced colors -->
                    <defs>
                        <linearGradient id="cleanGradient" x1="0%" y1="0%" x2="100%" y2="0%">
                            <stop offset="0%" stop-color="#10B981" />
                            <stop offset="50%" stop-color="#22C993" />
                            <stop offset="100%" stop-color="#34D399" />
                            <!-- Animated gradient stops -->
                            <animate attributeName="x1" values="0%;20%;0%" dur="3s" repeatCount="indefinite" />
                            <animate attributeName="x2" values="100%;120%;100%" dur="3s" repeatCount="indefinite" />
                        </linearGradient>
                        
                        <!-- Add an enhanced glow filter for the arc -->
                        <filter id="glow" x="-20%" y="-20%" width="140%" height="140%">
                            <feGaussianBlur stdDeviation="2.5" result="blur" />
                            <feFlood flood-color="#10B981" flood-opacity="0.4" result="glow" />
                            <feComposite in="glow" in2="blur" operator="in" result="softGlow" />
                            <feComposite in="SourceGraphic" in2="softGlow" operator="over" />
                        </filter>
                        
                        <!-- Pulsing filter for status changes -->
                        <filter id="statusPulse" x="-20%" y="-20%" width="140%" height="140%">
                            <feGaussianBlur stdDeviation="1" result="blur" />
                            <feFlood flood-color="#f9b903" flood-opacity="0.6" result="pulse" />
                            <feComposite in="pulse" in2="blur" operator="in" result="pulseBlur" />
                            <feComposite in="SourceGraphic" in2="pulseBlur" operator="over" />
                        </filter>
                    </defs>
                    
                    <!-- White center circle for better visuals -->
                    <circle cx="18" cy="18" r="12" fill="white" />
                    
                    <!-- Number display with animation -->
                    <text x="18" y="17.5" text-anchor="middle" fill="#333" font-size="7.5" font-weight="700" class="clean-percentage counter-animation" wire:key="clean-percent-{{ $cleanRooms }}">
                        {{ $totalRooms > 0 ? round(($cleanRooms / $totalRooms) * 100) : 0 }}
                    </text>
                    <!-- Percentage symbol -->
                    <text x="18" y="22.5" text-anchor="middle" fill="#666" font-size="3.5" font-weight="500" letter-spacing="0.3" class="percentage-label">
                        CLEAN %
                    </text>
                    
                    <!-- Add decorative circle behind the center -->
                    <circle cx="18" cy="18" r="14" fill="none" stroke="#f9f9f9" stroke-width="0.5" class="decorative-circle"></circle>
                </svg>
            </div>
            <p class="text-gray-500 mt-2">Clean Rooms Percentage</p>
            
            <!-- Simple legend -->
            <div class="flex items-center mt-1 space-x-3 text-xs text-gray-500">
                <span class="flex items-center">
                    <span class="inline-block w-2 h-2 rounded-full bg-[#10B981] mr-1"></span> Clean
                </span>
                <span class="flex items-center">
                    <span class="inline-block w-2 h-2 rounded-full bg-amber-500 mr-1"></span> In Progress
                </span>
                <span class="flex items-center">
                    <span class="inline-block w-2 h-2 rounded-full bg-red-500 mr-1"></span> Needs Cleaning
                </span>
            </div>
        </div>
        
        <!-- Cleaning Status Details -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <!-- Clean -->
            <div class="bg-white border border-gray-100 rounded-lg p-4 shadow-sm relative overflow-hidden hover:shadow-md transition-all duration-300 status-card clean-card">
                <div class="absolute top-0 right-0 h-full w-1 bg-green-500"></div>
                <div class="absolute inset-0 bg-green-500 opacity-0 card-flash"></div>
                <div class="flex items-center justify-between relative z-10">
                    <div>
                        <p class="text-sm text-gray-500">Clean</p>
                        <p class="text-2xl font-bold text-gray-800 cleaning-status-value value-counter" 
                           wire:key="clean-rooms-{{ $cleanRooms }}" 
                           data-value="{{ $cleanRooms ?? 0 }}">
                            {{ $cleanRooms ?? 0 }}
                        </p>
                    </div>
                    <div class="rounded-full bg-green-100 p-2 text-green-500 status-icon-container pulse-on-change">
                        <i class="fas fa-check-circle text-lg status-icon"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="relative pt-1">
                        <div class="overflow-hidden h-2 text-xs flex rounded bg-green-100">
                            <div 
                                class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-green-500 transition-all duration-1000 ease-out progress-bar-animate" 
                                style="width: {{ $totalRooms > 0 ? round(($cleanRooms / $totalRooms) * 100) : 0 }}%"
                                wire:key="clean-bar-{{ $cleanRooms }}"
                                data-percentage="{{ $totalRooms > 0 ? round(($cleanRooms / $totalRooms) * 100) : 0 }}"
                            ></div>
                        </div>
                        <div class="mt-1 text-xs text-green-600 cleaning-status-percent percent-counter" 
                             wire:key="clean-percent-value-{{ $cleanRooms }}"
                             data-value="{{ $totalRooms > 0 ? round(($cleanRooms / $totalRooms) * 100) : 0 }}">
                            {{ $totalRooms > 0 ? round(($cleanRooms / $totalRooms) * 100) : 0 }}% of total rooms
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- In Progress -->
            <div class="bg-white border border-gray-100 rounded-lg p-4 shadow-sm relative overflow-hidden hover:shadow-md transition-all duration-300 status-card progress-card">
                <div class="absolute top-0 right-0 h-full w-1 bg-amber-500"></div>
                <div class="absolute inset-0 bg-amber-500 opacity-0 card-flash"></div>
                <div class="flex items-center justify-between relative z-10">
                    <div>
                        <p class="text-sm text-gray-500">In Progress</p>
                        <p class="text-2xl font-bold text-gray-800 cleaning-status-value value-counter" 
                           wire:key="in-progress-rooms-{{ $inProgressCleaningRooms }}"
                           data-value="{{ $inProgressCleaningRooms ?? 0 }}">
                           {{ $inProgressCleaningRooms ?? 0 }}
                        </p>
                    </div>
                    <div class="rounded-full bg-amber-100 p-2 text-amber-500 status-icon-container pulse-on-change">
                        <i class="fas fa-broom text-lg status-icon cleaning-icon"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="relative pt-1">
                        <div class="overflow-hidden h-2 text-xs flex rounded bg-amber-100">
                            <div 
                                class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-amber-500 transition-all duration-1000 ease-out progress-bar-animate" 
                                style="width: {{ $totalRooms > 0 ? round(($inProgressCleaningRooms / $totalRooms) * 100) : 0 }}%"
                                wire:key="progress-bar-{{ $inProgressCleaningRooms }}"
                                data-percentage="{{ $totalRooms > 0 ? round(($inProgressCleaningRooms / $totalRooms) * 100) : 0 }}"
                            ></div>
                        </div>
                        <div class="mt-1 text-xs text-amber-600 cleaning-status-percent percent-counter" 
                             wire:key="in-progress-percent-{{ $inProgressCleaningRooms }}"
                             data-value="{{ $totalRooms > 0 ? round(($inProgressCleaningRooms / $totalRooms) * 100) : 0 }}">
                            {{ $totalRooms > 0 ? round(($inProgressCleaningRooms / $totalRooms) * 100) : 0 }}% of total rooms
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Needs Cleaning -->
            <div class="bg-white border border-gray-100 rounded-lg p-4 shadow-sm relative overflow-hidden hover:shadow-md transition-all duration-300 status-card needs-cleaning-card">
                <div class="absolute top-0 right-0 h-full w-1 bg-red-500"></div>
                <div class="absolute inset-0 bg-red-500 opacity-0 card-flash"></div>
                <div class="flex items-center justify-between relative z-10">
                    <div>
                        <p class="text-sm text-gray-500">Needs Cleaning</p>
                        <p class="text-2xl font-bold text-gray-800 cleaning-status-value value-counter" 
                           wire:key="not-cleaned-rooms-{{ $notCleanedRooms }}"
                           data-value="{{ $notCleanedRooms ?? 0 }}">
                           {{ $notCleanedRooms ?? 0 }}
                        </p>
                    </div>
                    <div class="rounded-full bg-red-100 p-2 text-red-500 status-icon-container pulse-on-change">
                        <i class="fas fa-exclamation-triangle text-lg status-icon attention-icon"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="relative pt-1">
                        <div class="overflow-hidden h-2 text-xs flex rounded bg-red-100">
                            <div 
                                class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-red-500 transition-all duration-1000 ease-out progress-bar-animate" 
                                style="width: {{ $totalRooms > 0 ? round(($notCleanedRooms / $totalRooms) * 100) : 0 }}%"
                                wire:key="needs-cleaning-bar-{{ $notCleanedRooms }}"
                                data-percentage="{{ $totalRooms > 0 ? round(($notCleanedRooms / $totalRooms) * 100) : 0 }}"
                            ></div>
                        </div>
                        <div class="mt-1 text-xs text-red-600 cleaning-status-percent percent-counter" 
                             wire:key="not-cleaned-percent-{{ $notCleanedRooms }}"
                             data-value="{{ $totalRooms > 0 ? round(($notCleanedRooms / $totalRooms) * 100) : 0 }}">
                            {{ $totalRooms > 0 ? round(($notCleanedRooms / $totalRooms) * 100) : 0 }}% of total rooms
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('livewire:initialized', () => {
            // Log initial load
            console.log('Cleaning status section initialized at', new Date().toLocaleTimeString());
            
            // Initialize counter animations
            initializeCounters();
            
            // Initialize progress bars 
            initializeProgressBars();
            
            // Update timestamp
            updateTimestamp();
            
            // Listen for cleaning status update events
            Livewire.on('cleaningStatusChanged', (data) => {
                console.log('Cleaning status updated event received:', data);
                
                // Flash status notification
                showStatusNotification('Status updated!');
                
                // Animate all elements with enhanced effects
                animateAllElements();
                
                // Update the timestamp with animation
                if (data && data.timestamp) {
                    const timestamp = document.getElementById('cleaning-last-updated');
                    if (timestamp) {
                        timestamp.textContent = data.timestamp;
                        timestamp.classList.add('highlight-on-change');
                        setTimeout(() => {
                            timestamp.classList.remove('highlight-on-change');
                        }, 2000);
                    }
                } else {
                    updateTimestamp();
                }
            });
            
            // Also listen for the alternate event name
            Livewire.on('cleaningStatusUpdated', (data) => {
                console.log('Cleaning status updated alternate event received:', data);
                animateAllElements();
                updateTimestamp(data.timestamp);
            });
        });
        
        // Handle Livewire updates to highlight changed elements
        document.addEventListener('livewire:update', () => {
            console.log('Cleaning status section updated at', new Date().toLocaleTimeString());
            animateAllElements();
        });
        
        // Initialize animated counters for all stat values
        function initializeCounters() {
            // Set initial values for counters
            document.querySelectorAll('.value-counter, .percent-counter').forEach(counter => {
                counter.setAttribute('data-prev-value', counter.getAttribute('data-value') || '0');
            });
        }
        
        // Initialize progress bars with smooth animation
        function initializeProgressBars() {
            document.querySelectorAll('.progress-bar-animate').forEach(bar => {
                bar.setAttribute('data-prev-percentage', bar.getAttribute('data-percentage') || '0');
            });
        }
        
        // Update timestamp with current time
        function updateTimestamp(time = null) {
            const timestamp = document.getElementById('cleaning-last-updated');
            if (timestamp) {
                timestamp.textContent = time || new Date().toLocaleTimeString('en-US', { 
                    hour: '2-digit', 
                    minute: '2-digit',
                    second: '2-digit',
                    hour12: false 
                });
                timestamp.classList.add('timestamp-updated');
                setTimeout(() => {
                    timestamp.classList.remove('timestamp-updated');
                }, 2000);
            }
        }
        
        // Show a status notification
        function showStatusNotification(message) {
            // Create notification element if it doesn't exist
            let notification = document.querySelector('.status-notification');
            if (!notification) {
                notification = document.createElement('div');
                notification.className = 'status-notification';
                notification.style.opacity = '0';
                notification.style.transform = 'translateY(20px)';
                document.querySelector('.bg-white.rounded-lg.shadow-lg').appendChild(notification);
            }
            
            // Set message and show notification
            notification.textContent = message;
            notification.style.opacity = '1';
            notification.style.transform = 'translateY(0)';
            
            // Hide after delay
            setTimeout(() => {
                notification.style.opacity = '0';
                notification.style.transform = 'translateY(20px)';
            }, 3000);
        }
        
        // Animate all elements with enhanced effects
        function animateAllElements() {
            // Animate counters with counting effect
            animateCounters();
            
            // Animate progress bars
            animateProgressBars();
            
            // Flash cards when values change
            flashCards();
            
            // Animate the gauge arc with enhanced effects
            animateGaugeArc();
            
            // Pulse the icons
            pulseIcons();
            
            // Update timestamp
            updateTimestamp();
        }
        
        // Animate number counters with counting effect
        function animateCounters() {
            document.querySelectorAll('.value-counter, .percent-counter').forEach(counter => {
                const currentValue = parseInt(counter.getAttribute('data-value') || '0');
                const prevValue = parseInt(counter.getAttribute('data-prev-value') || '0');
                
                if (currentValue !== prevValue) {
                    // Save new previous value
                    counter.setAttribute('data-prev-value', currentValue);
                    
                    // Add highlight class
                    counter.classList.add('counter-animation', 'highlight-change');
                    
                    // Optional: Animate counting up/down
                    if (Math.abs(currentValue - prevValue) < 20) {
                        const duration = 1500;
                        const frameDuration = 1000/60;
                        const totalFrames = Math.round(duration / frameDuration);
                        const increment = (currentValue - prevValue) / totalFrames;
                        
                        let currentCount = prevValue;
                        let frame = 0;
                        
                        const animate = () => {
                            currentCount += increment;
                            frame++;
                            
                            counter.textContent = Math.round(currentCount);
                            
                            if (counter.classList.contains('percent-counter')) {
                                counter.textContent += '% of total rooms';
                            }
                            
                            if (frame < totalFrames) {
                                requestAnimationFrame(animate);
                            } else {
                                counter.textContent = currentValue;
                                if (counter.classList.contains('percent-counter')) {
                                    counter.textContent += '% of total rooms';
                                }
                            }
                        };
                        
                        animate();
                    }
                    
                    // Remove classes after animation completes
                    setTimeout(() => {
                        counter.classList.remove('highlight-change', 'counter-animation');
                    }, 2500);
                }
            });
        }
        
        // Animate progress bars with smooth transitions
        function animateProgressBars() {
            document.querySelectorAll('.progress-bar-animate').forEach(bar => {
                const currentPercentage = bar.getAttribute('data-percentage') || '0';
                const prevPercentage = bar.getAttribute('data-prev-percentage') || '0';
                
                if (currentPercentage !== prevPercentage) {
                    // Save new previous percentage
                    bar.setAttribute('data-prev-percentage', currentPercentage);
                    
                    // Add animation class
                    bar.classList.add('bar-animation');
                    
                    // Remove class after animation completes
                    setTimeout(() => {
                        bar.classList.remove('bar-animation');
                    }, 1500);
                }
            });
        }
        
        // Flash cards when values change
        function flashCards() {
            document.querySelectorAll('.status-card').forEach((card, index) => {
                // Stagger the animations for visual effect
                setTimeout(() => {
                    // Add card flash effect
                    const flash = card.querySelector('.card-flash');
                    if (flash) {
                        flash.classList.add('flash-animation');
                        setTimeout(() => {
                            flash.classList.remove('flash-animation');
                        }, 1000);
                    }
                    
                    // Add card animation
                    card.classList.add('card-updated');
                    setTimeout(() => {
                        card.classList.remove('card-updated');
                    }, 700);
                }, index * 150);
            });
        }
        
        // Animate the gauge arc
        function animateGaugeArc() {
            const arc = document.querySelector('.clean-percentage-arc');
            if (arc) {
                arc.classList.add('arc-animation');
                setTimeout(() => {
                    arc.classList.remove('arc-animation');
                }, 1500);
            }
            
            const percentage = document.querySelector('.clean-percentage');
            if (percentage) {
                percentage.classList.add('percentage-animation');
                setTimeout(() => {
                    percentage.classList.remove('percentage-animation');
                }, 1500);
            }
        }
        
        // Pulse icons for visual feedback
        function pulseIcons() {
            document.querySelectorAll('.status-icon').forEach(icon => {
                icon.classList.add('icon-animation');
                
                const container = icon.closest('.pulse-on-change');
                if (container) {
                    container.classList.add('container-pulse');
                    setTimeout(() => {
                        container.classList.remove('container-pulse');
                    }, 1800);
                }
                
                setTimeout(() => {
                    icon.classList.remove('icon-animation');
                }, 1500);
            });
            
            // Special animation for cleaning icon
            const cleaningIcon = document.querySelector('.cleaning-icon');
            if (cleaningIcon) {
                cleaningIcon.classList.add('swing-animation');
                setTimeout(() => {
                    cleaningIcon.classList.remove('swing-animation');
                }, 1500);
            }
            
            // Special animation for attention icon
            const attentionIcon = document.querySelector('.attention-icon');
            if (attentionIcon) {
                attentionIcon.classList.add('shake-animation');
                setTimeout(() => {
                    attentionIcon.classList.remove('shake-animation');
                }, 1500);
            }
        }
    </script>
    
    <style>
        /* Enhanced highlight animation for changed values with subtle glow effect */
        .highlight-change {
            animation: pulse-highlight 2.5s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }
        
        .highlight-change::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            border-radius: inherit;
            box-shadow: 0 0 0 0 rgba(249, 185, 3, 0);
            animation: glow-pulse 2.5s cubic-bezier(0.4, 0, 0.6, 1);
            z-index: 1;
            pointer-events: none;
        }
        
        .highlight-on-change {
            font-weight: bold;
            animation: pulse-highlight-text 2.5s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }
        
        @keyframes pulse-highlight {
            0% { background-color: transparent; transform: scale(1); }
            10% { background-color: rgba(249, 185, 3, 0.15); transform: scale(1.02); }
            30% { background-color: rgba(249, 185, 3, 0.3); transform: scale(1); }
            70% { background-color: rgba(249, 185, 3, 0.2); }
            100% { background-color: transparent; }
        }
        
        @keyframes glow-pulse {
            0% { box-shadow: 0 0 0 0 rgba(249, 185, 3, 0); }
            25% { box-shadow: 0 0 8px 3px rgba(249, 185, 3, 0.4); }
            70% { box-shadow: 0 0 12px 5px rgba(249, 185, 3, 0.1); }
            100% { box-shadow: 0 0 0 0 rgba(249, 185, 3, 0); }
        }
        
        @keyframes pulse-highlight-text {
            0% { color: inherit; text-shadow: none; }
            20% { color: #f9b903; text-shadow: 0 0 8px rgba(249, 185, 3, 0.5); }
            70% { color: #f9b903; text-shadow: 0 0 4px rgba(249, 185, 3, 0.3); }
            100% { color: inherit; text-shadow: none; }
        }
        
        /* Improved gauge arc animation with more fluid motion */
        .clean-percentage-arc {
            transition: stroke-dasharray 1.2s cubic-bezier(0.4, 0, 0.2, 1);
            transform-origin: center;
        }
        
        .arc-animation {
            animation: arc-pulse 1.5s cubic-bezier(0.4, 0, 0.2, 1);
            filter: drop-shadow(0 0 3px rgba(249, 185, 3, 0.4));
        }
        
        @keyframes arc-pulse {
            0% { opacity: 0.7; stroke-width: 3.8; }
            30% { opacity: 1; stroke-width: 5; filter: drop-shadow(0 0 5px rgba(249, 185, 3, 0.6)); }
            70% { opacity: 1; stroke-width: 4.5; filter: drop-shadow(0 0 3px rgba(249, 185, 3, 0.4)); }
            100% { opacity: 1; stroke-width: 4; filter: drop-shadow(0 0 0px rgba(249, 185, 3, 0)); }
        }
        
        /* Auto-update indicator animation with a smoother pulse */
        .animate-ping {
            animation: ping 1.8s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        
        @keyframes ping {
            0% { transform: scale(0.95); opacity: 0.7; }
            50% { transform: scale(1.3); opacity: 0.3; }
            100% { transform: scale(0.95); opacity: 0.7; }
        }
        
        /* Subtle card animations when values change */
        .fade-in-up {
            animation: fadeInUp 0.7s ease-out;
        }
        
        @keyframes fadeInUp {
            from { opacity: 0.6; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Animated progress counter effect */
        .counter-animation {
            animation: counter-pop 1s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        
        @keyframes counter-pop {
            0% { transform: scale(0.95); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        
        /* Improved icon animations */
        .icon-animation {
            animation: icon-pulse 1.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        @keyframes icon-pulse {
            0% { transform: scale(0.9); opacity: 0.7; }
            50% { transform: scale(1.2); opacity: 1; }
            100% { transform: scale(1); opacity: 1; }
        }
    </style>
</div>
