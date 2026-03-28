@extends('layouts.app')
@section('title', 'Office - Create / Edit')

@section('content')

{{-- Toast Container --}}
<div id="toast-container" class="fixed top-5 right-5 z-50 flex flex-col gap-3 w-96 pointer-events-none"></div>

{{-- Breadcrumb --}}
<nav class="flex items-center gap-2 text-xs text-gray-400 mb-6">
    <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 transition">Dashboard</a>
    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
    <a href="{{ route('office.view') }}" class="hover:text-indigo-600 transition">Offices</a>
    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
    <span class="text-gray-600" id="breadcrumb-label">New Office</span>
</nav>

<div class="flex flex-col lg:flex-row gap-8">

    {{-- Left: Form Card --}}
    <div class="flex-1 min-w-0">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

            {{-- Card Header --}}
            <div class="bg-gradient-to-r from-indigo-600 to-indigo-500 px-7 py-5 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-white font-semibold text-base leading-tight" id="form-heading">New Office Add Karein</h2>
                        <p class="text-indigo-200 text-xs mt-0.5">All starred fields are required </p>
                    </div>
                </div>
                <a href="{{ route('office.view') }}"
                    class="flex items-center gap-1.5 text-xs font-medium text-white/80 hover:text-white bg-white/10 hover:bg-white/20 px-3 py-1.5 rounded-lg transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Back to List
                </a>
            </div>

            {{-- Form --}}
            <form id="office-form" novalidate class="px-7 py-7">
                @csrf
                <input type="hidden" id="office-id" value="">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-5">
                    {{-- Office Name --}}
                    <div class="sm:col-span-2">
                        <label for="name" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">
                            Office Name <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <input type="text" id="name" name="name"
                                placeholder="e.g. HEAD OFFICE MUMBAI"
                                class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm uppercase bg-gray-50
                                       focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                                       transition placeholder:normal-case" />
                        </div>
                        <p class="text-red-500 text-xs mt-1.5 hidden flex items-center gap-1" id="err-name">
                            <svg class="w-3 h-3 inline" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            <span></span>
                        </p>
                    </div>

                    {{-- City --}}
                    <div>
                        <label for="city" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">
                            City <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <input type="text" id="city" name="city"
                                placeholder="e.g. MUMBAI"
                                class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm uppercase bg-gray-50
                                       focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                                       transition placeholder:normal-case" />
                        </div>
                        <p class="text-red-500 text-xs mt-1.5 hidden flex items-center gap-1" id="err-city">
                            <svg class="w-3 h-3 inline" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            <span></span>
                        </p>
                    </div>

                    {{-- Status --}}
                    <div>
                        <label for="status" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <select id="status" name="status"
                                class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm bg-gray-50 appearance-none
                                       focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                                <option value="">-- Select Status --</option>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>
                        <p class="text-red-500 text-xs mt-1.5 hidden flex items-center gap-1" id="err-status">
                            <svg class="w-3 h-3 inline" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            <span></span>
                        </p>
                    </div>

                    {{-- Address --}}
                    <div class="sm:col-span-2">
                        <label for="address" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">
                            Address <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute top-3 left-0 pl-3.5 flex items-start pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                            </div>
                            <textarea id="address" name="address" rows="3"
                                placeholder="Full address ..."
                                class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm uppercase bg-gray-50 resize-none
                                       focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                                       transition placeholder:normal-case"></textarea>
                        </div>
                        <p class="text-red-500 text-xs mt-1.5 hidden flex items-center gap-1" id="err-address">
                            <svg class="w-3 h-3 inline" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            <span></span>
                        </p>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                    <button type="submit" id="submit-btn"
                        class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white
                               font-semibold px-6 py-2.5 rounded-xl transition text-sm shadow-sm shadow-indigo-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span id="submit-label">Save Karein</span>
                    </button>
                    <button type="button" onclick="resetForm()"
                        class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200
                               text-gray-600 font-medium px-5 py-2.5 rounded-xl transition text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Reset
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Right: Info Panel --}}
    <div class="w-full lg:w-72 flex-shrink-0 flex flex-col gap-5">

        {{-- Tips Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                <span class="w-6 h-6 bg-indigo-100 rounded-lg flex items-center justify-center">
                    <svg class="w-3.5 h-3.5 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                </span>
                Filling Tips
            </h3>
            <ul class="text-xs text-gray-500 space-y-2.5">
                <li class="flex items-start gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 mt-1.5 flex-shrink-0"></span>
                    Name, City aur Address are automatically converted to CAPITAL letters.
                </li>
                <li class="flex items-start gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 mt-1.5 flex-shrink-0"></span>
                      choose Active or Inactive from Status field .
                </li>
                
            </ul>
        </div>

        {{-- Status Preview Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                <span class="w-6 h-6 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-3.5 h-3.5 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </span>
                Status Preview
            </h3>
            <div class="flex flex-col gap-2">
                <div class="flex items-center justify-between text-xs bg-green-50 border border-green-100 rounded-lg px-3 py-2">
                    <span class="text-gray-600">Active</span>
                    <span class="bg-green-500 text-white px-2 py-0.5 rounded-full text-xs font-medium">Active</span>
                </div>
                <div class="flex items-center justify-between text-xs bg-red-50 border border-red-100 rounded-lg px-3 py-2">
                    <span class="text-gray-600">Inactive</span>
                    <span class="bg-red-400 text-white px-2 py-0.5 rounded-full text-xs font-medium">Inactive</span>
                </div>
            </div>
        </div>

        {{-- Quick Nav --}}
        <a href="{{ route('office.view') }}"
            class="flex items-center gap-3 bg-indigo-50 hover:bg-indigo-100 border border-indigo-100 rounded-2xl px-5 py-4 transition group">
            <div class="w-9 h-9 bg-indigo-600 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:bg-indigo-700 transition">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-semibold text-indigo-700">View All Offices</p>
                <p class="text-xs text-indigo-400">Registered offices dekhein</p>
            </div>
        </a>
    </div>

</div>

@endsection

@push('scripts')
<script>
    const API_BASE = '/api/offices';
    const HEADERS = {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'X-Requested-With': 'XMLHttpRequest'
    };

    // ─── Toast ────────────────────────────────────────────────
    function showToast(message, type = 'success') {
        const colors = {
            success: 'bg-green-500',
            error:   'bg-red-500',
            warning: 'bg-yellow-500',
            info:    'bg-blue-500',
        };
        const icons = {
            success: '✓',
            error:   '✕',
            warning: '⚠',
            info:    'ℹ',
        };
        const container = document.getElementById('toast-container');
        const toast = document.createElement('div');
        toast.className = `${colors[type]} text-white px-5 py-3 rounded-xl shadow-lg flex items-center gap-3
                           transform translate-x-0 transition-all duration-300`;
        toast.innerHTML = `
            <span class="text-lg font-bold">${icons[type]}</span>
            <span class="text-sm flex-1">${message}</span>
            <button onclick="this.parentElement.remove()" class="text-white/70 hover:text-white text-lg leading-none">&times;</button>
        `;
        container.appendChild(toast);
        setTimeout(() => { toast.style.opacity = '0'; setTimeout(() => toast.remove(), 300); }, 4000);
    }

    // ─── Field Error Helpers ──────────────────────────────────
    function showFieldError(field, msg) {
        const el = document.getElementById('err-' + field);
        const input = document.getElementById(field);
        if (el) {
            const span = el.querySelector('span');
            if (span) span.textContent = msg;
            el.classList.remove('hidden');
        }
        if (input) input.classList.add('border-red-400', 'bg-red-50');
    }

    function clearErrors() {
        ['name', 'city', 'address', 'status'].forEach(f => {
            const el = document.getElementById('err-' + f);
            const input = document.getElementById(f);
            if (el) { const span = el.querySelector('span'); if (span) span.textContent = ''; el.classList.add('hidden'); }
            if (input) input.classList.remove('border-red-400', 'bg-red-50');
        });
    }

    // ─── Auto Uppercase on Input ─────────────────────────────
    ['name', 'city', 'address'].forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            el.addEventListener('input', () => {
                const pos = el.selectionStart;
                el.value = el.value.toUpperCase();
                el.setSelectionRange(pos, pos);
            });
        }
    });

    // ─── Load data if editing (URL has ?id=X) ────────────────
    const urlParams = new URLSearchParams(window.location.search);
    const editId = urlParams.get('id');

    if (editId) {
        document.getElementById('form-heading').textContent = 'Office Edit Karein';
        document.getElementById('submit-label').textContent = 'Update Karein';
        document.getElementById('breadcrumb-label').textContent = 'Edit Office';
        document.getElementById('office-id').value = editId;

        fetch(`${API_BASE}/${editId}`, {
            headers: HEADERS
        })
        .then(r => r.json())
        .then(res => {
            if (res.success) {
                const d = res.data;
                document.getElementById('name').value    = d.name;
                document.getElementById('city').value    = d.city;
                document.getElementById('address').value = d.address;
                document.getElementById('status').value  = d.status;
            } else {
                showToast('Office data load nahi ho saka.', 'error');
            }
        })
        .catch(() => showToast('Server se data fetch karne mein error aaya.', 'error'));
    }

    // ─── Form Submit ──────────────────────────────────────────
    document.getElementById('office-form').addEventListener('submit', async function (e) {
        e.preventDefault();
        clearErrors();

        const id      = document.getElementById('office-id').value;
        const payload = {
            name:    document.getElementById('name').value.trim(),
            city:    document.getElementById('city').value.trim(),
            address: document.getElementById('address').value.trim(),
            status:  document.getElementById('status').value,
        };

        const isEdit = !!id;
        const url    = isEdit ? `${API_BASE}/${id}` : API_BASE;
        const method = isEdit ? 'PUT' : 'POST';

        const btn = document.getElementById('submit-btn');
        btn.disabled = true;
        btn.querySelector('#submit-label') && (btn.querySelector('#submit-label').textContent = 'Saving...');
        btn.classList.add('opacity-75', 'cursor-not-allowed');

        try {
            const response = await fetch(url, {
                method,
                headers: HEADERS,
                body: JSON.stringify(payload),
            });

            const res = await response.json();

            if (response.ok && res.success) {
                showToast(res.message, 'success');
                if (!isEdit) {
                    resetForm();
                }
            } else if (response.status === 422) {
                // Laravel validation errors
                const errors = res.errors || {};
                let hasFieldError = false;
                Object.entries(errors).forEach(([field, messages]) => {
                    showFieldError(field, messages[0]);
                    hasFieldError = true;
                });
                if (!hasFieldError) {
                    showToast(res.message || 'Validation error aayi.', 'error');
                } else {
                    showToast('Form mein kuch errors hain, please check karein.', 'warning');
                }
            } else {
                showToast(res.message || 'Kuch galat ho gaya. Dobara try karein.', 'error');
            }
        } catch (err) {
            showToast('Network error aaya. Internet check karein.', 'error');
        } finally {
            btn.disabled = false;
            btn.classList.remove('opacity-75', 'cursor-not-allowed');
            const label = document.getElementById('submit-label');
            if (label) label.textContent = isEdit ? 'Update Karein' : 'Save Karein';
        }
    });

    // ─── Reset Form ───────────────────────────────────────────
    function resetForm() {
        document.getElementById('office-form').reset();
        document.getElementById('office-id').value = '';
        clearErrors();
    }
</script>
@endpush
