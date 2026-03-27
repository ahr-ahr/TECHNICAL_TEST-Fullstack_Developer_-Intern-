@php
    $role = auth()->user()->role;
    $notifications = auth()->user()->notifications()->latest()->take(5)->get();
    $unreadCount = auth()->user()->unreadNotifications()->count();
@endphp

<nav x-data="{ open: false }" class="bg-white/80 backdrop-blur border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <div class="flex items-center space-x-4">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <div class="hidden sm:flex items-center space-x-4 sm:ms-6">

                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        <span class="flex items-center space-x-1">
                            <i class="fa-solid fa-house text-sm"></i>
                            <span>Dashboard</span>
                        </span>
                    </x-nav-link>

                    <x-nav-link :href="route('activity')" :active="request()->routeIs('activity')">
                        <span class="flex items-center space-x-1">
                            <i class="fa-solid fa-clock-rotate-left text-sm"></i>
                            <span>Activity Log</span>
                        </span>
                    </x-nav-link>

                    @if ($role === 'admin')
                        <x-nav-link :href="route('vehicle')" :active="request()->is('vehicle*')">
                            <span class="flex items-center space-x-1">
                                <i class="fa-solid fa-car text-sm"></i>
                                <span>Kendaraan</span>
                            </span>
                        </x-nav-link>

                        <x-nav-link :href="route('driver')" :active="request()->is('driver*')">
                            <span class="flex items-center space-x-1">
                                <i class="fa-solid fa-id-card text-sm"></i>
                                <span>Driver</span>
                            </span>
                        </x-nav-link>

                        <x-nav-link :href="route('booking')" :active="request()->is('booking*')">
                            <span class="flex items-center space-x-1">
                                <i class="fa-solid fa-calendar-check text-sm"></i>
                                <span>Booking</span>
                            </span>
                        </x-nav-link>

                        <x-nav-link :href="route('approval')" :active="request()->is('approval*')">
                            <span class="flex items-center space-x-1">
                                <i class="fa-solid fa-check text-sm"></i>
                                <span>Approval</span>
                            </span>
                        </x-nav-link>

                        <x-nav-link :href="route('report')" :active="request()->is('report*')">
                            <span class="flex items-center space-x-1">
                                <i class="fa-solid fa-chart-line text-sm"></i>
                                <span>Laporan</span>
                            </span>
                        </x-nav-link>
                    @endif

                    @if ($role === 'approver')
                        <x-nav-link :href="route('approval')" :active="request()->is('approval*')">
                            <span class="flex items-center space-x-1">
                                <i class="fa-solid fa-check text-sm"></i>
                                <span>Approval</span>
                            </span>
                        </x-nav-link>

                        <x-nav-link :href="route('history')" :active="request()->is('history*')">
                            <span class="flex items-center space-x-1">
                                <i class="fa-solid fa-clock-rotate-left text-sm"></i>
                                <span>Riwayat</span>
                            </span>
                        </x-nav-link>
                    @endif

                </div>
            </div>

            <div class="flex-1 px-4" x-data="{ open: false, query: '' }">

                <div class="max-w-md mx-auto relative">

                    <div class="absolute left-3 top-2.5 text-gray-400">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </div>

                    <input type="text" x-model="query" @focus="open = true" @click.outside="open = false"
                        placeholder="Search..."
                        class="w-full pl-10 pr-4 py-2 text-sm bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500">

                    <div x-show="open && query.length > 0"
                        class="absolute mt-2 w-full bg-white rounded-lg shadow-lg p-2 text-sm z-50">

                        <div class="p-2 text-gray-500">
                            Searching...
                        </div>

                    </div>

                </div>

            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6 space-x-4">

                <div x-data="{ open: false }" class="relative">

                    <button @click="open = !open" class="relative text-gray-500 hover:text-gray-700">
                        <i class="fa-regular fa-bell text-lg"></i>

                        @if ($unreadCount > 0)
                            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs px-1.5 rounded-full">
                                {{ $unreadCount }}
                            </span>
                        @endif
                    </button>

                    <div x-show="open" @click.outside="open = false"
                        class="absolute right-0 mt-2 w-64 bg-white rounded-lg shadow-lg p-3 text-sm z-50">

                        <p class="font-semibold mb-2">Notifications</p>

                        <div class="space-y-2 max-h-60 overflow-y-auto">

                            @forelse ($notifications as $notif)
                                <a href="{{ route('notification.read', $notif->id) }}"
                                    class="block p-2 rounded hover:bg-gray-100">

                                    {{ $notif->data['message'] ?? '-' }}

                                    @if (!$notif->read_at)
                                        <span class="text-xs text-blue-500 ml-2">(new)</span>
                                    @endif
                                </a>
                            @empty
                                <div class="text-gray-400 text-sm">
                                    No notifications
                                </div>
                            @endforelse

                        </div>

                    </div>
                </div>

                <div x-data="{ open: false }" class="relative">

                    <button @click="open = !open" class="flex items-center space-x-2 text-gray-600 hover:text-gray-800">

                        <i class="fa-solid fa-user-circle text-xl"></i>
                    </button>

                    <div x-show="open" @click.outside="open = false"
                        class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50">

                        <a href="{{ route('profile.edit') }}"
                            class="flex items-center space-x-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">

                            <i class="fa-solid fa-user w-4 text-gray-500"></i>
                            <span>Profile</span>
                        </a>

                        <div class="border-t my-1"></div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full flex items-center space-x-2 px-4 py-2 text-sm text-red-600 hover:bg-gray-100">

                                <i class="fa-solid fa-right-from-bracket w-4"></i>
                                <span>Logout</span>
                            </button>
                        </form>

                    </div>
                </div>

            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">

            <!-- Dashboard -->
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                <div class="flex items-center space-x-3">
                    <i class="fa-solid fa-house w-4"></i>
                    <span>Dashboard</span>
                </div>
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('activity')" :active="request()->routeIs('activity')">
                <span class="flex items-center space-x-3">
                    <i class="fa-solid fa-clock-rotate-left w-4"></i>
                    <span>Activity Log</span>
                </span>
            </x-responsive-nav-link>

            <!-- ADMIN -->
            @if ($role === 'admin')
                <x-responsive-nav-link :href="route('vehicle')" :active="request()->is('vehicle*')">
                    <div class="flex items-center space-x-3">
                        <i class="fa-solid fa-car w-4"></i>
                        <span>Kendaraan</span>
                    </div>
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('driver')" :active="request()->is('driver*')">
                    <div class="flex items-center space-x-3">
                        <i class="fa-solid fa-id-card w-4"></i>
                        <span>Driver</span>
                    </div>
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('booking')" :active="request()->is('booking*')">
                    <div class="flex items-center space-x-3">
                        <i class="fa-solid fa-calendar-check w-4"></i>
                        <span>Booking</span>
                    </div>
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('approval')" :active="request()->is('approval*')">
                    <div class="flex items-center space-x-3">
                        <i class="fa-solid fa-check w-4"></i>
                        <span>Approval</span>
                    </div>
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('report')" :active="request()->is('report*')">
                    <div class="flex items-center space-x-3">
                        <i class="fa-solid fa-chart-line w-4"></i>
                        <span>Laporan</span>
                    </div>
                </x-responsive-nav-link>
            @endif

            <!-- APPROVER -->
            @if ($role === 'approver')
                <x-responsive-nav-link :href="route('approval')" :active="request()->is('approval*')">
                    <div class="flex items-center space-x-3">
                        <i class="fa-solid fa-check w-4"></i>
                        <span>Approval</span>
                    </div>
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('history')" :active="request()->is('history*')">
                    <div class="flex items-center space-x-3">
                        <i class="fa-solid fa-clock-rotate-left w-4"></i>
                        <span>Riwayat</span>
                    </div>
                </x-responsive-nav-link>
            @endif

        </div>

        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="mt-3 space-y-1">
                <div x-data="{ openNotif: false }" class="px-4">

                    <button @click="openNotif = !openNotif"
                        class="w-full py-2 flex items-center justify-between text-sm text-gray-700">

                        <div class="flex items-center space-x-2">
                            <i class="fa-regular fa-bell"></i>
                            <span>Notifications</span>
                        </div>

                        @if ($unreadCount > 0)
                            <span class="bg-red-500 text-white text-xs px-2 rounded-full">
                                {{ $unreadCount }}
                            </span>
                        @endif
                    </button>

                    <div x-show="openNotif"
                        class="mt-2 bg-gray-50 rounded-lg p-2 space-y-1 text-sm max-h-60 overflow-y-auto">

                        @forelse ($notifications as $notif)
                            <a href="{{ route('notification.read', $notif->id) }}"
                                class="block p-2 hover:bg-gray-100 rounded">

                                {{ $notif->data['message'] ?? '-' }}

                                @if (!$notif->read_at)
                                    <span class="text-xs text-blue-500">(new)</span>
                                @endif
                            </a>
                        @empty
                            <div class="text-gray-400 text-sm">
                                No notifications
                            </div>
                        @endforelse

                    </div>

                </div>

                <x-responsive-nav-link :href="route('profile.edit')">
                    <div class="flex items-center space-x-3">
                        <i class="fa-solid fa-user w-4 text-gray-500"></i>
                        <span>Profile</span>
                    </div>
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();">

                        <div class="flex items-center space-x-3 text-red-600">
                            <i class="fa-solid fa-right-from-bracket w-4"></i>
                            <span>Logout</span>
                        </div>

                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
