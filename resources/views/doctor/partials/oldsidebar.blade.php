<aside id="sidebar" class="w-72 bg-gradient-to-b from-slate-800 via-slate-900 to-slate-950 text-white transform -translate-x-full md:translate-x-0 md:relative fixed md:static z-40 top-0 left-0 h-full md:h-auto md:flex md:flex-col transition-transform duration-300 ease-in-out shadow-2xl border-r-2 border-slate-600/60 shadow-slate-900/50">
    <!-- Branding -->
    <div class="flex items-center gap-3 px-6 py-6 border-b-2 border-slate-600/60 bg-gradient-to-r from-slate-800/50 to-slate-700/30 shadow-lg">
      <a href="{{ route('doctor.dashboard') }}" class="flex items-center gap-3">
        <div class="bg-gradient-to-br from-blue-500 to-purple-600 p-2 rounded-xl backdrop-blur-sm shadow-lg">
<img src="{{ asset('storage/PngVectordeisgn.png') }}" class="w-8 h-8" />

        </div>
        <div>
          <h1 class="text-white font-bold text-xl leading-5">FreeDoctor</h1>
          <p class="text-xs text-slate-300">Professional Portal</p>
        </div>
      </a>
    </div>

    <!-- Doctor Profile Section -->
    <div class="px-6 py-4 border-b-2 border-slate-600/60 bg-gradient-to-r from-slate-800/30 to-transparent shadow-md">
      <!-- Main Profile Header -->
      <div class="flex items-center gap-3 mb-3">
        <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-xl shadow-lg ring-2 ring-blue-400/30">
          {{ substr(auth('doctor')->user()->doctor_name ?? 'D', 0, 1) }}
        </div>
        <div class="flex-1">
          <h3 class="font-bold text-white text-base leading-tight">
            Dr. {{ auth('doctor')->user()->doctor_name ?? 'Doctor' }}
          </h3>
          @if(auth('doctor')->user()->specialty)
            <div class="mt-1">
              <span class="text-slate-300 text-xs bg-slate-700/60 px-2 py-1 rounded-full inline-flex items-center border border-slate-600/50">
                <i class="fas fa-stethoscope mr-1"></i>
                {{ auth('doctor')->user()->specialty->name ?? 'Specialist' }}
              </span>
            </div>
          @else
            <p class="text-slate-400 text-xs mt-1">Medical Specialist</p>
          @endif
        </div>
      </div>

      <!-- Quick Info -->
      <div class="grid grid-cols-2 gap-2">
        <div class="bg-emerald-800/40 px-3 py-2 rounded-lg text-center border border-emerald-600/50">
          <div class="text-emerald-300 text-xs font-medium">Status</div>
          <div class="text-white font-bold text-sm flex items-center justify-center gap-1">
            <i class="fas fa-circle text-green-400 text-xs"></i>
            Active
          </div>
        </div>
        <div class="bg-slate-700/60 px-3 py-2 rounded-lg text-center border border-slate-600/50">
          <div class="text-slate-300 text-xs font-medium">Doctor ID</div>
          <div class="text-white font-bold text-sm">#{{ str_pad(auth('doctor')->user()->id ?? '000', 3, '0', STR_PAD_LEFT) }}</div>
        </div>
      </div>

      <!-- View Full Profile Button -->
      <div class="mt-3">
        <a href="{{ route('doctor.profile') }}" class="block w-full bg-gradient-to-r from-slate-600/80 to-slate-700/80 hover:from-slate-600 hover:to-slate-700 text-white text-center py-2 px-3 rounded-lg text-xs font-medium transition-all duration-300 border border-slate-500/40">
          <i class="fas fa-user mr-1"></i>
          View Full Profile
        </a>
      </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 overflow-y-auto py-4  scrollbar-custom"style="max-height: 60vh;">
    <ul class="space-y-2 px-4">
  @php
    $active = request()->segment(2);
    $menuItems = [
      [
        'route' => 'dashboard',
        'label' => 'Dashboard',
        'icon' => 'tachometer-alt',
        'color' => 'from-purple-500 to-purple-600'
      ],
      [
        'route' => 'campaigns',
        'label' => 'My Campaigns',
        'icon' => 'calendar-plus',
        'color' => 'from-green-500 to-green-600'
      ],
      [
        'route' => 'patients',
        'label' => 'Patient Registrations',
        'icon' => 'users',
        'color' => 'from-blue-500 to-blue-600'
      ],
      [
        'route' => 'sponsors',
        'label' => 'Sponsorships',
        'icon' => 'handshake',
        'color' => 'from-yellow-500 to-orange-500',
        'description' => 'Manage sponsors & funding'
      ],
      [
        'route' => 'business-reach-out',
        'label' => 'Business Opportunities',
        'icon' => 'briefcase',
        'color' => 'from-indigo-500 to-purple-600',
        'description' => 'Organization requests & camps'
      ],
      [
        'route' => 'profit',
        'label' => 'Earnings & Reports',
        'icon' => 'chart-line',
        'color' => 'from-emerald-500 to-teal-600'
      ],
      [
        'route' => 'profile',
        'label' => 'Profile Settings',
        'icon' => 'user-cog',
        'color' => 'from-gray-500 to-gray-600'
      ],
      [
        'route' => 'notifications',
        'label' => 'Notifications',
        'icon' => 'bell',
        'color' => 'from-red-500 to-pink-600'
      ],
    ];
  @endphp

  @foreach($menuItems as $item)
    <li>
      <a href="{{ route('doctor.' . $item['route']) }}"
         class="sidebar-nav-item group flex items-center px-4 py-3 rounded-xl transition-all duration-300 
                {{ $active === $item['route'] 
                   ? 'sidebar-nav-active bg-gradient-to-r ' . $item['color'] . ' text-white shadow-lg border border-white/20' 
                   : 'text-slate-300 hover:text-white hover:bg-slate-700/50 border border-slate-600/50 hover:border-slate-500/70 hover:shadow-md hover:shadow-slate-800/20' }}">
        <div class="sidebar-nav-icon flex items-center justify-center w-10 h-10 rounded-lg 
                    {{ $active === $item['route'] 
                       ? 'bg-white/20 border border-white/30' 
                       : 'bg-slate-700/40 border border-slate-600/50 group-hover:bg-slate-600/60' }} 
                    transition-all duration-300">
          <i class="fas fa-{{ $item['icon'] }} text-lg sidebar-text-transition {{ $active === $item['route'] ? 'text-white' : 'text-slate-300 group-hover:text-white' }}"></i>
        </div>
        <div class="ml-3 flex-1">
          <div class="font-medium text-sm sidebar-text-transition {{ $active === $item['route'] ? 'text-white' : 'text-slate-300 group-hover:text-white' }}">{{ $item['label'] }}</div>
          @if(isset($item['description']))
            <div class="text-xs opacity-75 mt-1 sidebar-text-transition {{ $active === $item['route'] ? 'text-white' : 'text-slate-400 group-hover:text-slate-200' }}">{{ $item['description'] }}</div>
          @endif
        </div>
        @if($active === $item['route'])
          <div class="w-2 h-2 bg-white rounded-full shadow-lg"></div>
        @endif
      </a>
    </li>
  @endforeach
</ul>

    </nav>

    <!-- Quick Stats -->
    <div class="px-4 py-3 border-t-2 border-slate-600/60 bg-gradient-to-r from-slate-800/30 to-transparent shadow-lg">
      <div class="text-xs text-slate-300 mb-2">Quick Stats</div>
      <div class="grid grid-cols-3 gap-2 text-xs">
        <div class="bg-slate-700/60 px-2 py-1 rounded text-center border border-slate-600/50">
          <div class="text-white font-bold">{{ $totalCampaigns ?? '0' }}</div>
          <div class="text-slate-300">Campaigns</div>
        </div>
        <div class="bg-green-800/40 px-2 py-1 rounded text-center border border-green-600/30">
          <div class="text-white font-bold">{{ $totalSponsors ?? '0' }}</div>
          <div class="text-green-300">Sponsors</div>
        </div>
        <div class="bg-purple-800/40 px-2 py-1 rounded text-center border border-purple-600/30">
          <div class="text-white font-bold">{{ $totalPatients ?? '0' }}</div>
          <div class="text-purple-300">Patients</div>
        </div>
      </div>
    </div>

    <!-- Logout -->
    <div class="px-4 py-4">
      <form action="{{ route('doctor.logout') }}" method="POST" class="w-full" onsubmit="return confirm('Are you sure you want to logout?')">
        @csrf
        <button type="submit" class="sidebar-nav-item flex items-center justify-center w-full px-4 py-3 rounded-xl bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50 shadow-lg hover:shadow-xl transform hover:scale-105 group">
          <i class="fas fa-sign-out-alt mr-2 sidebar-text-transition group-hover:text-white"></i>
          <span class="font-medium sidebar-text-transition group-hover:text-white">Logout</span>
        </button>
      </form>
    </div>
  </aside>
