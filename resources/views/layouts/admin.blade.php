<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>@yield('title', 'Roomie Admin')</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600&display=swap" rel="stylesheet">
        <script src="https://cdn.tailwindcss.com"></script>

        <style>
            body {
                font-family: 'Outfit', sans-serif;
            }
        </style>
        @yield('styles')
    </head>

    <body class="bg-gray-50">
        <div class="min-h-screen flex">
            <!-- Sidebar -->
            <aside
                class="w-64 bg-slate-900 text-white flex flex-col fixed h-full z-10 transition-transform duration-300">
                <div class="p-6 border-b border-slate-800">
                    <h1 class="text-2xl font-bold text-white">Roomie<span class="text-indigo-500">.</span></h1>
                </div>

                <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
                    <a href="{{ route('admin.dashboard') }}"
                        class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 shadow-lg shadow-indigo-500/20 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800' }} rounded-xl transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                            </path>
                        </svg>
                        Dashboard
                    </a>

                    <a href="{{ route('admin.users.index') }}"
                        class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.users.*') ? 'bg-indigo-600 shadow-lg shadow-indigo-500/20 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800' }} rounded-xl transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                        Users
                    </a>
                    {{-- Properties --}}
                    <a href="{{ route('admin.properties.index') }}"
                        class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.properties.*') ? 'bg-indigo-600 shadow-lg shadow-indigo-500/20 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800' }} rounded-xl transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 10l9-7 9 7v10a2 2 0 01-2 2h-4a2 2 0 01-2-2v-4H9v4a2 2 0 01-2 2H3a2 2 0 01-2-2V10z" />
                        </svg>
                        Properties
                    </a>

                    {{-- Rooms --}}
                    <a href="{{ route('admin.rooms.index') }}"
                        class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.rooms.*') ? 'bg-indigo-600 shadow-lg shadow-indigo-500/20 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800' }} rounded-xl transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 11V7a2 2 0 012-2h12a2 2 0 012 2v4M4 11h16M4 11v6m16-6v6M2 17h20" />
                        </svg>
                        Rooms
                    </a>

                    <!-- Bookings -->
                    <a href="{{ route('admin.bookings.index') }}"
                        class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.bookings.*') ? 'bg-indigo-600 shadow-lg shadow-indigo-500/20 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800' }} rounded-xl transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                        Bookings
                    </a>

                    <!-- Payments -->
                    <a href="{{ route('admin.payments.index') }}"
                        class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.payments.*') ? 'bg-indigo-600 shadow-lg shadow-indigo-500/20 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800' }} rounded-xl transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                        Payments
                    </a>
                </nav>

                <div class="p-4 border-t border-slate-800">
                    <form action="{{ route('admin.logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="flex w-full items-center gap-3 px-4 py-3 text-red-400 hover:bg-slate-800 rounded-xl transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                </path>
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>
            </aside>

            <!-- Main Content -->
            <main class="flex-1 p-8 ml-64 min-w-0">
                @yield('content')
            </main>
        </div>

        @yield('scripts')
    </body>

</html>
