<div id="campaignsGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
    @forelse($campaigns as $campaign)
    <div class="rounded-xl overflow-hidden bg-slate-800/40 p-5 border border-slate-700 hover:border-blue-500/50 transition-all duration-300 hover:scale-105 shadow-md text-sm">
        
        <!-- Campaign Image -->
        <div class="aspect-w-16 aspect-h-9 relative overflow-hidden bg-slate-800 mb-3 rounded-lg">
            @if($campaign->thumbnail)
                <img src="{{ asset('storage/' . $campaign->thumbnail) }}" 
                     alt="{{ $campaign->title }}"
                     class="w-full h-full object-cover transition-transform duration-300 hover:scale-105">
            @else
                <div class="w-full h-full bg-gradient-to-br from-blue-500/20 to-purple-600/20 flex items-center justify-center">
                    <i class="fas {{ $campaign->camp_type === 'medical' ? 'fa-stethoscope' : 'fa-user-md' }} text-3xl text-slate-400"></i>
                </div>
            @endif

            <!-- Status Badges -->
            <div class="absolute top-2 left-2 flex flex-wrap gap-2">
                <span class="bg-green-700 text-white px-3 py-1 rounded-full text-sm font-semibold shadow">
                    <i class="fas fa-check mr-1"></i> Approved
                </span>

                @if($campaign->registration_payment != 0)
                <span class="bg-blue-700 text-white px-3 py-1 rounded-full text-sm font-semibold shadow">
                    <i class="fas fa-credit-card mr-1"></i> Paid: ${{ $campaign->registration_payment }}
                </span>
                @else
                <span class="bg-emerald-700 text-white px-3 py-1 rounded-full text-sm font-semibold shadow">
                    <i class="fas fa-gift mr-1"></i> Free
                </span>
                @endif
            </div>

            <!-- Doctor Info -->
            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-3 text-xs">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full overflow-hidden bg-blue-500/20 flex items-center justify-center">
                        @if($campaign->doctor && $campaign->doctor->image)
                            <img src="{{ asset('storage/' . $campaign->doctor->image) }}" alt="Dr. {{ $campaign->doctor->doctor_name }}" class="w-full h-full object-cover">
                        @else
                            <i class="fas fa-user-md text-white text-sm"></i>
                        @endif
                    </div>
                    <div>
                        <div class="text-white font-medium text-sm">Dr. {{ $campaign->doctor->doctor_name ?? 'N/A' }}</div>
                        <div class="text-slate-300 text-xs">{{ $campaign->doctor->specialty->name ?? 'General' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content -->
        <h3 class="font-semibold text-white mb-2 text-base line-clamp-2">{{ $campaign->title }}</h3>
        <p class="text-slate-400 mb-3 text-sm line-clamp-2">{{ Str::limit($campaign->description, 80) }}</p>

        <div class="space-y-1 text-sm text-slate-300">
            <div class="flex items-center">
                <i class="fas fa-map-marker-alt text-red-400 w-4"></i>
                <span class="ml-1">{{ $campaign->location }}</span>
            </div>
            <div class="flex items-center">
                <i class="fas fa-calendar text-blue-400 w-4"></i>
                <span class="ml-1">
                    {{ \Carbon\Carbon::parse($campaign->date_from)->format('M d') }} - 
                    {{ \Carbon\Carbon::parse($campaign->date_to)->format('M d, Y') }}
                </span>
            </div>
            <div class="flex items-center">
                <i class="fas fa-users text-purple-400 w-4"></i>
                <span class="ml-1">{{ $campaign->patientRegistrations->count() }} Registered</span>
            </div>
        </div>

        <!-- Actions -->
        <div class="grid grid-cols-2 gap-2 mt-4 text-sm">
            <button type="button" onclick="showCampaignDetails('{{ $campaign->id }}')" class="btn-modern">
                <i class="fas fa-eye mr-1"></i>View
            </button>

            @if($campaign->registration_payment != 0)
                @auth
                    <button type="button" onclick="showRegistrationModal('{{ $campaign->id }}')" class="btn-modern-warning">
                        <i class="fas fa-credit-card mr-1"></i>Pay & Register
                    </button>
                @else
                    <button type="button" onclick="showRegistrationModal('{{ $campaign->id }}')" class="btn-modern-secondary">
                        <i class="fas fa-sign-in-alt mr-1"></i>Login
                    </button>
                @endauth
            @else
                @auth
                    <button type="button" onclick="showRegistrationModal('{{ $campaign->id }}')" class="btn-modern-success">
                        <i class="fas fa-user-plus mr-1"></i>Register
                    </button>
                @else
                    <button type="button" onclick="showRegistrationModal('{{ $campaign->id }}')" class="btn-modern-secondary">
                        <i class="fas fa-sign-in-alt mr-1"></i>Login
                    </button>
                @endauth
            @endif
        </div>
    </div>
    @empty
    <div class="col-span-full text-center p-6 text-slate-400 bg-slate-800/30 rounded-xl border border-slate-700">
        <i class="fas fa-calendar-times text-2xl mb-2"></i>
        <p>No campaigns found.</p>
    </div>
    @endforelse
</div>
