<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Staff Daily Report')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen">
    <nav class="bg-indigo-700 text-white shadow">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex items-center justify-between h-16">
                <!-- Left side: Brand/Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ Auth::user()->role === 'admin' ? route('admin.dashboard') : (Auth::user()->role === 'manager' ? route('manager.dashboard') : route('staff.dashboard')) }}"
                       class="font-bold text-xl hover:text-indigo-200 transition">Dashboard</a>
                </div>

                <!-- Desktop Menu (Links) -->
                <div class="hidden md:flex items-center gap-4 lg:gap-6">
                    {{-- Office Management Dropdown --}}
                    @if(Auth::user()->role === 'admin')
                    <div class="relative" id="office-menu-wrapper">
                        <button onclick="toggleOfficeMenu()"
                            class="flex items-center gap-1 text-sm font-medium hover:text-indigo-200 transition focus:outline-none">
                            Office Management
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div id="office-dropdown"
                            class="hidden absolute left-0 mt-2 w-44 bg-white text-gray-700 rounded-xl shadow-lg py-1 z-50 border border-gray-100">
                            <a href="{{ route('office.create') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm hover:bg-indigo-50 hover:text-indigo-700 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                                Add Office
                            </a>
                            <a href="{{ route('office.view') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm hover:bg-indigo-50 hover:text-indigo-700 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" /></svg>
                                View Offices
                            </a>
                        </div>
                    </div>
                    @endif

                    {{-- Department Management Dropdown --}}
                    @if(Auth::user()->role === 'admin')
                    <div class="relative" id="department-menu-wrapper">
                        <button onclick="toggleDepartmentMenu()"
                            class="flex items-center gap-1 text-sm font-medium hover:text-indigo-200 transition focus:outline-none">
                            Department Management
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div id="department-dropdown"
                            class="hidden absolute left-0 mt-2 w-48 bg-white text-gray-700 rounded-xl shadow-lg py-1 z-50 border border-gray-100">
                            <a href="{{ route('department.create') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm hover:bg-indigo-50 hover:text-indigo-700 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                                Add Department
                            </a>
                            <a href="{{ route('department.view') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm hover:bg-indigo-50 hover:text-indigo-700 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" /></svg>
                                View Departments
                            </a>
                        </div>
                    </div>
                    @endif

                    {{-- Staff Management Dropdown --}}
                    @if(Auth::user()->role === 'admin')
                    <div class="relative" id="staff-menu-wrapper">
                        <button onclick="toggleStaffMenu()"
                            class="flex items-center gap-1 text-sm font-medium hover:text-indigo-200 transition focus:outline-none">
                            Staff Management
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div id="staff-dropdown"
                            class="hidden absolute left-0 mt-2 w-44 bg-white text-gray-700 rounded-xl shadow-lg py-1 z-50 border border-gray-100">
                            <a href="{{ route('staff.create') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm hover:bg-indigo-50 hover:text-indigo-700 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                                Add Staff
                            </a>
                            <a href="{{ route('staff.view') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm hover:bg-indigo-50 hover:text-indigo-700 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" /></svg>
                                View Staff
                            </a>
                        </div>
                    </div>
                    @endif

                    {{-- IT Management Dropdown --}}
                    @if(Auth::user()->canAccessIT())
                    <div class="relative" id="it-menu-wrapper">
                        <button onclick="toggleITMenu()"
                            class="flex items-center gap-1 text-sm font-medium hover:text-indigo-200 transition focus:outline-none">
                            IT Management
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div id="it-dropdown"
                            class="hidden absolute left-0 mt-2 w-52 bg-white text-gray-700 rounded-xl shadow-lg py-1 z-50 border border-gray-100">
                            <a href="{{ route('it-management.allotment.index') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm hover:bg-indigo-50 hover:text-indigo-700 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 21h6l-.75-4M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                Hardware Allotment
                            </a>
                            <a href="{{ route('it-management.backup.index') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm hover:bg-indigo-50 hover:text-indigo-700 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" /></svg>
                                Backup Logs
                            </a>
                        </div>
                    </div>
                    @endif

                    {{-- Stock Management Dropdown --}}
                    @if(Auth::user()->canAccessIT())
                    <div class="relative" id="stock-menu-wrapper">
                        <button onclick="toggleStockMenu()"
                            class="flex items-center gap-1 text-sm font-medium hover:text-indigo-200 transition focus:outline-none">
                            Stock Management
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div id="stock-dropdown"
                            class="hidden absolute left-0 mt-2 w-52 bg-white text-gray-700 rounded-xl shadow-lg py-1 z-50 border border-gray-100">
                            <a href="{{ route('stock-management.categories.index') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm hover:bg-indigo-50 hover:text-indigo-700 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" /></svg>
                                Stock Categories
                            </a>
                            <a href="{{ route('stock-management.items.index') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm hover:bg-indigo-50 hover:text-indigo-700 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                                Manage Stock Items
                            </a>
                            <a href="{{ route('stock-management.purchases.index') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm hover:bg-indigo-50 hover:text-indigo-700 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                                Stock Purchase
                            </a>
                            <a href="{{ route('stock-management.allotments.index') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm hover:bg-indigo-50 hover:text-indigo-700 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                Stock Allotment
                            </a>
                        </div>
                    </div>
                    @endif

                    {{-- Daily Report Dropdown --}}
                    <div class="relative" id="dailyreport-menu-wrapper">
                        <button onclick="toggleDailyReportMenu()"
                            class="flex items-center gap-1 text-sm font-medium hover:text-indigo-200 transition focus:outline-none">
                            Daily Report
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div id="dailyreport-dropdown"
                            class="hidden absolute left-0 mt-2 w-48 bg-white text-gray-700 rounded-xl shadow-lg py-1 z-50 border border-gray-100">
                            <a href="{{ route('daily-report.create') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm hover:bg-indigo-50 hover:text-indigo-700 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                                Submit Report
                            </a>
                            <a href="{{ route('daily-report.index') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm hover:bg-indigo-50 hover:text-indigo-700 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" /></svg>
                                View Reports
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Right side: User Info & Logout (Desktop) -->
                <div class="hidden md:flex items-center gap-4">
                    <span class="text-sm whitespace-nowrap">
                        {{ Auth::user()->name }} &mdash;
                        <span class="capitalize bg-indigo-500 px-2 py-0.5 rounded text-xs">{{ Auth::user()->role }}</span>
                    </span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white text-sm px-3 py-1.5 rounded-lg transition shadow-sm font-medium">
                            Logout
                        </button>
                    </form>
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden flex items-center">
                    <button onclick="toggleMobileMenu()" class="text-white hover:text-indigo-200 focus:outline-none p-2 rounded-md bg-indigo-600">
                        <svg id="mobile-menu-icon" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                        </svg>
                        <svg id="mobile-menu-close-icon" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu (Hidden by default) -->
        <div id="mobile-menu" class="hidden md:hidden bg-indigo-800 border-t border-indigo-600 pb-4">
            <div class="px-4 py-3 border-b border-indigo-700 mb-2 flex items-center justify-between">
                <div>
                    <div class="text-base font-medium">{{ Auth::user()->name }}</div>
                    <div class="text-sm text-indigo-300 capitalize">{{ Auth::user()->role }}</div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white text-xs px-3 py-1.5 rounded transition">
                        Logout
                    </button>
                </form>
            </div>

            <div class="px-2 space-y-1">
                @if(Auth::user()->role === 'admin')
                    <div class="text-xs font-semibold text-indigo-300 uppercase tracking-wider px-3 py-2">Management</div>
                    <a href="{{ route('office.view') }}" class="block px-3 py-2 rounded-md text-base font-medium hover:bg-indigo-600 transition">Office Management</a>
                    <a href="{{ route('department.view') }}" class="block px-3 py-2 rounded-md text-base font-medium hover:bg-indigo-600 transition">Department Management</a>
                    <a href="{{ route('staff.view') }}" class="block px-3 py-2 rounded-md text-base font-medium hover:bg-indigo-600 transition">Staff Management</a>
                @endif

                @if(Auth::user()->canAccessIT())
                    <div class="text-xs font-semibold text-indigo-300 uppercase tracking-wider px-3 py-2">IT & Stock</div>
                    <a href="{{ route('it-management.allotment.index') }}" class="block px-3 py-2 rounded-md text-base font-medium hover:bg-indigo-600 transition">Hardware Allotment</a>
                    <a href="{{ route('it-management.backup.index') }}" class="block px-3 py-2 rounded-md text-base font-medium hover:bg-indigo-600 transition">Backup Logs</a>
                    <a href="{{ route('stock-management.items.index') }}" class="block px-3 py-2 rounded-md text-base font-medium hover:bg-indigo-600 transition">Stock Management</a>
                @endif

                <div class="text-xs font-semibold text-indigo-300 uppercase tracking-wider px-3 py-2">Daily Work</div>
                <a href="{{ route('daily-report.create') }}" class="block px-3 py-2 rounded-md text-base font-medium hover:bg-indigo-600 transition">Submit Report</a>
                <a href="{{ route('daily-report.index') }}" class="block px-3 py-2 rounded-md text-base font-medium hover:bg-indigo-600 transition">View Reports</a>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 py-8">
        @yield('content')
    </main>

    @stack('scripts')

    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            const icon = document.getElementById('mobile-menu-icon');
            const closeIcon = document.getElementById('mobile-menu-close-icon');
            
            menu.classList.toggle('hidden');
            icon.classList.toggle('hidden');
            closeIcon.classList.toggle('hidden');
        }

        function toggleOfficeMenu() {
            document.getElementById('office-dropdown').classList.toggle('hidden');
        }
        function toggleDepartmentMenu() {
            document.getElementById('department-dropdown').classList.toggle('hidden');
        }
        function toggleStaffMenu() {
            document.getElementById('staff-dropdown').classList.toggle('hidden');
        }
        function toggleITMenu() {
            document.getElementById('it-dropdown').classList.toggle('hidden');
        }
        function toggleStockMenu() {
            document.getElementById('stock-dropdown').classList.toggle('hidden');
        }
        function toggleDailyReportMenu() {
            document.getElementById('dailyreport-dropdown').classList.toggle('hidden');
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', function (e) {
            const dropdowns = [
                { wrapper: 'office-menu-wrapper', dropdown: 'office-dropdown' },
                { wrapper: 'department-menu-wrapper', dropdown: 'department-dropdown' },
                { wrapper: 'staff-menu-wrapper', dropdown: 'staff-dropdown' },
                { wrapper: 'it-menu-wrapper', dropdown: 'it-dropdown' },
                { wrapper: 'stock-menu-wrapper', dropdown: 'stock-dropdown' },
                { wrapper: 'dailyreport-menu-wrapper', dropdown: 'dailyreport-dropdown' }
            ];

            dropdowns.forEach(item => {
                const wrapper = document.getElementById(item.wrapper);
                const dropdown = document.getElementById(item.dropdown);
                if (wrapper && dropdown && !wrapper.contains(e.target)) {
                    dropdown.classList.add('hidden');
                }
            });
        });
    </script>
</body>
</html>
