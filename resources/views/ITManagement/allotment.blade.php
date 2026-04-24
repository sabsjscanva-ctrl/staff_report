@extends('layouts.app')

@section('title', 'Hardware Allotment')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Hardware System Allotment</h2>
        <p class="text-sm text-gray-500 mt-1">Manage and track IT assets assigned to staff members.</p>
    </div>
</div>

@if(session('success'))
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
    <span class="block sm:inline">{{ session('success') }}</span>
</div>
@endif

<div class="bg-white rounded-xl shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Staff Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">System Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Address</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($staffs as $staff)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $staff->name }}</div>
                        <div class="text-xs text-gray-500">{{ $staff->designation }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $staff->department->dept_name ?? 'N/A' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($staff->systemAllotment)
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ $staff->systemAllotment->type ?? 'Allocated' }}
                            </span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                Unallocated
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $staff->systemAllotment->ip_address ?? 'N/A' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <button onclick="openModal({{ json_encode($staff) }}, {{ json_encode($staff->systemAllotment) }})" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 px-3 py-1 rounded-md transition-colors">
                            Manage Allotment
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Allotment Modal -->
<div id="allotmentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-10 mx-auto p-5 border w-full max-w-4xl shadow-lg rounded-xl bg-white mb-20">
        <div class="flex justify-between items-center mb-4 border-b pb-2">
            <h3 class="text-xl font-bold text-gray-900" id="modalTitle">Manage System Allotment</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <form action="{{ route('it-management.allotment.store') }}" method="POST">
            @csrf
            <input type="hidden" name="staff_id" id="staff_id">
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-2 mb-6">
                <!-- Type -->
                <div class="form-group">
                    <label class="form-label">System Type</label>
                    <select name="type" id="type" class="form-select">
                        <option value="">Select Type</option>
                        <option value="Desktop">Desktop</option>
                        <option value="Laptop">Laptop</option>
                        <option value="Server">Server</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <!-- IP Address -->
                <div class="form-group">
                    <label class="form-label">IP Address</label>
                    <input type="text" name="ip_address" id="ip_address" class="form-input" placeholder="e.g. 192.168.1.100">
                </div>

                <!-- Processor -->
                <div class="form-group">
                    <label class="form-label">Processor</label>
                    <input type="text" name="processor" id="processor" class="form-input" placeholder="e.g. Intel Core i5">
                </div>

                <!-- RAM -->
                <div class="form-group">
                    <label class="form-label">RAM</label>
                    <input type="text" name="ram" id="ram" class="form-input" placeholder="e.g. 16GB DDR4">
                </div>

                <!-- Storage -->
                <div class="form-group">
                    <label class="form-label">Storage</label>
                    <input type="text" name="storage" id="storage" class="form-input" placeholder="e.g. 512GB SSD">
                </div>

                <!-- Motherboard -->
                <div class="form-group">
                    <label class="form-label">Motherboard</label>
                    <input type="text" name="motherboard" id="motherboard" class="form-input" placeholder="e.g. Gigabyte H410">
                </div>

                <!-- Graphic Card -->
                <div class="form-group">
                    <label class="form-label">Graphic Card</label>
                    <input type="text" name="graphic_card" id="graphic_card" class="form-input" placeholder="e.g. NVIDIA GTX 1650">
                </div>



                <!-- Operating System -->
                <div class="form-group">
                    <label class="form-label">Operating System</label>
                    <input type="text" name="operating_system" id="operating_system" class="form-input" placeholder="e.g. Windows 11 Pro">
                </div>

                <!-- Antivirus -->
                <div class="form-group">
                    <label class="form-label">Antivirus</label>
                    <input type="text" name="antivirus" id="antivirus" class="form-input" placeholder="e.g. K7 Total Security">
                </div>
                
                <!-- Licensed Software -->
                <div class="form-group">
                    <label class="form-label">Licensed Software</label>
                    <input type="text" name="licensed_software" id="licensed_software" class="form-input" placeholder="e.g. MS Office 2021">
                </div>
            </div>

            <!-- Full width textareas -->
            <div class="space-y-0 mb-6">
                <div class="form-group">
                    <label class="form-label">Installed Applications</label>
                    <textarea name="installed_applications" id="installed_applications" rows="3" class="form-textarea" placeholder="List key applications..."></textarea>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Remarks</label>
                    <textarea name="remarks" id="remarks" rows="2" class="form-textarea" placeholder="Any additional remarks..."></textarea>
                </div>
            </div>

            <!-- Allotted Peripherals (From Stock) -->
            <div id="stockAllotmentSection" class="mb-8 hidden">
                <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
                    <h4 class="text-sm font-bold text-gray-800 mb-3 flex items-center gap-2">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        Allotted Peripherals (from Stock)
                    </h4>
                    <div id="stockItemsList" class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <!-- Items will be injected here via JS -->
                    </div>
                </div>
            </div>
            
            <div class="flex justify-end gap-3 border-t pt-6">
                <button type="button" onclick="closeModal()" class="bg-white py-2.5 px-6 border border-gray-300 rounded-xl shadow-sm text-sm font-semibold text-gray-700 hover:bg-gray-50 focus:outline-none transition-all">
                    Cancel
                </button>
                <button type="submit" class="bg-indigo-600 py-2.5 px-8 border border-transparent rounded-xl shadow-lg text-sm font-bold text-white hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 transition-all active:scale-95">
                    Save Allotment
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function openModal(staff, allotment) {
        document.getElementById('modalTitle').innerText = 'System Allotment - ' + staff.name;
        document.getElementById('staff_id').value = staff.id;
        
        // Reset form
        const form = document.querySelector('#allotmentModal form');
        form.reset();
        
        // Populate System Allotment if exists
        if (allotment) {
            Object.keys(allotment).forEach(key => {
                const input = document.getElementById(key);
                if (input) {
                    input.value = allotment[key] || '';
                }
            });
        }

        // Handle Stock Allotments
        const stockSection = document.getElementById('stockAllotmentSection');
        const stockList = document.getElementById('stockItemsList');
        stockList.innerHTML = ''; // Clear previous

        if (staff.stock_allotments && staff.stock_allotments.length > 0) {
            stockSection.classList.remove('hidden');
            staff.stock_allotments.forEach(itemAllot => {
                const div = document.createElement('div');
                div.className = 'flex justify-between items-center bg-white p-3 rounded-lg border border-gray-200 shadow-sm';
                div.innerHTML = `
                    <span class="text-sm font-medium text-gray-700">${itemAllot.item.name}</span>
                    <span class="px-2.5 py-0.5 bg-indigo-100 text-indigo-700 rounded-full text-xs font-bold">Qty: ${itemAllot.quantity}</span>
                `;
                stockList.appendChild(div);
            });
        } else {
            stockSection.classList.add('hidden');
        }
        
        document.getElementById('allotmentModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('allotmentModal').classList.add('hidden');
    }
</script>
@endpush
