<!-- Top Navigation - Lebih Kompak -->
<header class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700 transition-colors duration-300 sticky top-0 z-30">
    <div class="flex items-center justify-between px-4 py-3">
        <div class="flex items-center">
            <button @click="sidebarOpen = !sidebarOpen" 
                    class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded-lg p-1.5 lg:hidden transition-colors duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
            <div class="ml-3 lg:ml-0">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">
                    @yield('title', 'Dashboard')
                </h2>
                <p x-data="clock()" x-init="start()" x-text="display" x-cloak
                class="text-xs text-gray-500 dark:text-gray-400"></p>
            </div>
        </div>

        <div class="flex items-center space-x-3">
            <!-- Search - Lebih Kecil -->
            <!-- <div class="hidden md:block relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" 
                       placeholder="Search..." 
                       class="w-48 pl-9 pr-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors duration-200">
            </div> -->

            <!-- Notifications - Lebih Kecil -->
            <!-- <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" 
                        class="relative p-2 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-3.5-3.5c-.2-.2-.4-.5-.5-.8V9c0-3.3-2.7-6-6-6s-6 2.7-6 6v3.7c-.1.3-.3.6-.5.8L0 17h5m5 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                    <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                </button>

                <div x-show="open" 
                     @click.away="open = false"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="absolute right-0 mt-2 w-72 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 z-50">
                    <div class="p-3 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Notifications</h3>
                    </div>
                    <div class="max-h-48 overflow-y-auto">
                        <div class="p-3 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                            <div class="flex items-start">
                                <div class="w-2 h-2 bg-blue-500 rounded-full mt-1.5 mr-3"></div>
                                <div>
                                    <p class="text-sm text-gray-900 dark:text-white">New user registered</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">2 minutes ago</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->

            <!-- Theme Toggle -->
            <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)" 
                    class="p-2 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                </svg>
                <svg x-show="darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
            </button>

            <!-- User Dropdown - Lebih Kompak -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" 
                        class="flex items-center space-x-2 text-sm rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 hover:bg-gray-100 dark:hover:bg-gray-700 p-1.5 transition-colors duration-200">
                    <div class="w-7 h-7 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                        <span class="text-white font-bold text-xs">{{ substr(auth()->user()->name, 0, 1) }}</span>
                    </div>
                    <div class="hidden sm:block text-left">
                        <p class="text-gray-700 dark:text-gray-200 font-medium text-sm">{{ auth()->user()->name }}</p>
                    </div>
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <div x-show="open" 
                     @click.away="open = false"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 py-1 z-50">
                    <div class="px-3 py-2 border-b border-gray-200 dark:border-gray-700">
                        <p class="text-sm text-gray-900 dark:text-white font-medium">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ auth()->user()->email }}</p>
                    </div>
                    <a href="{{ route('admin.profile.show') }}" 
                       class="flex items-center px-3 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Profile
                    </a>
                    <hr class="my-1 border-gray-200 dark:border-gray-700">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" 
                                class="flex items-center w-full px-3 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
  document.addEventListener('alpine:init', () => {
    Alpine.data('clock', (opts = {}) => ({
      // opsi
      tz: opts.tz ?? (localStorage.getItem('tz') || Intl.DateTimeFormat().resolvedOptions().timeZone),
      locale: opts.locale ?? 'id-ID',
      withTzName: opts.withTzName ?? true,   // tampilkan nama zona (short)
      tickMs: opts.tickMs ?? 1000,           // interval update

      // state
      now: new Date(),
      timer: null,

      start(){ this.timer = setInterval(() => this.now = new Date(), this.tickMs) },
      stop(){ if (this.timer) clearInterval(this.timer) },

      setTz(val){
        if (!val || val === 'auto') {
          this.tz = Intl.DateTimeFormat().resolvedOptions().timeZone;
          localStorage.removeItem('tz');
        } else {
          this.tz = val;
          localStorage.setItem('tz', val);
        }
      },

      get display(){
        try {
          const o = {
            timeZone: this.tz,
            weekday: 'short', day: '2-digit', month: 'short', year: 'numeric',
            hour: '2-digit', minute: '2-digit', second: '2-digit',
            hour12: false
          };
          if (this.withTzName) o.timeZoneName = 'short'; // contoh: GMT+7, WIB mungkin tidak selalu muncul
          return new Intl.DateTimeFormat(this.locale, o).format(this.now);
        } catch (e) {
          // timezone invalid -> fallback ke timezone browser
          const o = {
            weekday: 'short', day: '2-digit', month: 'short', year: 'numeric',
            hour: '2-digit', minute: '2-digit', second: '2-digit',
            hour12: false
          };
          return new Intl.DateTimeFormat(this.locale, o).format(this.now);
        }
      }
    }));
  });
</script>