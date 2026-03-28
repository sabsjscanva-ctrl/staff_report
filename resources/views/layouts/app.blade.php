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
        <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">
            <div class="flex items-center gap-6">
                <a href="{{ Auth::user()->role === 'admin' ? route('admin.dashboard') : (Auth::user()->role === 'manager' ? route('manager.dashboard') : route('staff.dashboard')) }}"
                   class="font-bold text-lg hover:text-indigo-200 transition">Dashboard</a>

                {{-- Office Management Dropdown (Admin only) --}}
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
                        <a href="{{ route('office.create') }}"
                            class="flex items-center gap-2 px-4 py-2.5 text-sm hover:bg-indigo-50 hover:text-indigo-700 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                            Add Office
                        </a>
                        <a href="{{ route('office.view') }}"
                            class="flex items-center gap-2 px-4 py-2.5 text-sm hover:bg-indigo-50 hover:text-indigo-700 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                            View Offices
                        </a>
                    </div>
                </div>
                @endif

                {{-- Department Management Dropdown (Admin only) --}}
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
                        <a href="{{ route('department.create') }}"
                            class="flex items-center gap-2 px-4 py-2.5 text-sm hover:bg-indigo-50 hover:text-indigo-700 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                            Add Department
                        </a>
                        <a href="{{ route('department.view') }}"
                            class="flex items-center gap-2 px-4 py-2.5 text-sm hover:bg-indigo-50 hover:text-indigo-700 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                            View Departments
                        </a>
                    </div>
                </div>
                @endif

                {{-- Staff Management Dropdown (Admin only) --}}
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
                        <a href="{{ route('staff.create') }}"
                            class="flex items-center gap-2 px-4 py-2.5 text-sm hover:bg-indigo-50 hover:text-indigo-700 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                            Add Staff
                        </a>
                        <a href="{{ route('staff.view') }}"
                            class="flex items-center gap-2 px-4 py-2.5 text-sm hover:bg-indigo-50 hover:text-indigo-700 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                            View Staff
                        </a>
                    </div>
                </div>
                @endif

                {{-- Daily Report (All roles) --}}
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
                        <a href="{{ route('daily-report.create') }}"
                            class="flex items-center gap-2 px-4 py-2.5 text-sm hover:bg-indigo-50 hover:text-indigo-700 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                            Submit Report
                        </a>
                        <a href="{{ route('daily-report.index') }}"
                            class="flex items-center gap-2 px-4 py-2.5 text-sm hover:bg-indigo-50 hover:text-indigo-700 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                            View Reports
                        </a>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <span class="text-sm">{{ Auth::user()->name }} &mdash;
                    <span class="capitalize bg-indigo-500 px-2 py-0.5 rounded text-xs">{{ Auth::user()->role }}</span>
                </span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="bg-red-500 hover:bg-red-600 text-white text-sm px-3 py-1 rounded transition">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 py-8">
        @yield('content')
    </main>

    @stack('scripts')

    <script>
        function toggleOfficeMenu() {
            document.getElementById('office-dropdown').classList.toggle('hidden');
        }
        function toggleDepartmentMenu() {
            document.getElementById('department-dropdown').classList.toggle('hidden');
        }
        function toggleStaffMenu() {
            document.getElementById('staff-dropdown').classList.toggle('hidden');
        }
        function toggleDailyReportMenu() {
            document.getElementById('dailyreport-dropdown').classList.toggle('hidden');
        }
        // Close dropdowns when clicking outside
        document.addEventListener('click', function (e) {
            const officeWrapper = document.getElementById('office-menu-wrapper');
            if (officeWrapper && !officeWrapper.contains(e.target)) {
                document.getElementById('office-dropdown').classList.add('hidden');
            }
            const deptWrapper = document.getElementById('department-menu-wrapper');
            if (deptWrapper && !deptWrapper.contains(e.target)) {
                document.getElementById('department-dropdown').classList.add('hidden');
            }
            const staffWrapper = document.getElementById('staff-menu-wrapper');
            if (staffWrapper && !staffWrapper.contains(e.target)) {
                document.getElementById('staff-dropdown').classList.add('hidden');
            }
            const drWrapper = document.getElementById('dailyreport-menu-wrapper');
            if (drWrapper && !drWrapper.contains(e.target)) {
                document.getElementById('dailyreport-dropdown').classList.add('hidden');
            }
        });
    </script>
</body>
</html>
