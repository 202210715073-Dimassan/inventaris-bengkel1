@extends('layouts.app')

@section('title', 'Catat Transaksi')

@section('content')
<div class="flex flex-col lg:flex-row gap-6 animate-fade-in">
    
    <!-- Form Transaksi -->
    <div class="w-full lg:w-5/12">
        <div class="card-static rounded-2xl border border-slate-100 p-5 shadow-sm lg:sticky lg:top-6">
            <h3 class="text-base font-bold text-slate-800 mb-5 border-b border-slate-100 pb-3">Pencatatan Transaksi Baru</h3>
            
            <form action="{{ route('transactions.store') }}" method="POST" class="space-y-5">
                @csrf
                <!-- Jenis Transaksi (Tabs Indicator) -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Jenis Transaksi</label>
                    <div class="relative flex p-1 bg-slate-100 rounded-xl">
                        <!-- Background Sliding Indicator -->
                        <div id="type-indicator" class="absolute top-1 left-1 bottom-1 w-[calc(50%-4px)] bg-white rounded-lg shadow-sm transition-all duration-300"></div>
                        
                        <label class="relative flex-1 text-center py-2.5 text-xs font-bold cursor-pointer z-10 select-none">
                            <input type="radio" name="type" value="in" onclick="updateToggle('in')" class="sr-only" checked>
                            <span id="label-in" class="text-emerald-600 transition-colors duration-300 flex items-center justify-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                                Barang Masuk
                            </span>
                        </label>
                        <label class="relative flex-1 text-center py-2.5 text-xs font-bold cursor-pointer z-10 select-none">
                            <input type="radio" name="type" value="out" onclick="updateToggle('out')" class="sr-only">
                            <span id="label-out" class="text-slate-400 transition-colors duration-300 flex items-center justify-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                                Barang Keluar
                            </span>
                        </label>
                    </div>
                </div>

                <!-- Pilih Barang -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5">Pilih Barang</label>
                    <select name="product_id" id="field-product-id" required class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-805 focus:outline-none focus:ring-2 focus:ring-brand-primary/20 focus:border-brand-primary transition-all" onchange="handleProductChange()">
                        <option value="">-- Pilih Barang --</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" data-unit="{{ $product->unit }}" data-large-unit="{{ $product->large_unit }}" data-conversion="{{ $product->unit_conversion }}">
                                {{ $product->code ? '[' . $product->code . '] ' : '' }}{{ $product->name }} (Stok: {{ $product->formatted_stock }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Satuan Transaksi -->
                <div id="transaction-unit-container" class="hidden">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5">Satuan Transaksi</label>
                    <select name="transaction_unit" id="field-tx-unit" class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-primary/20 focus:border-brand-primary transition-all" onchange="calculateHelper()">
                        <!-- Dinonaktifkan atau diisi via JS -->
                    </select>
                </div>

                <!-- Jumlah -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5">Jumlah Kuantitas</label>
                    <input type="number" name="quantity" id="field-quantity" min="1" value="1" required class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-850 focus:outline-none focus:ring-2 focus:ring-brand-primary/20 focus:border-brand-primary transition-all" oninput="calculateHelper()">
                    <span id="quantity-helper-text" class="text-xs text-brand-primary mt-1.5 font-medium hidden"></span>
                </div>

                <!-- Tanggal -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5">Tanggal Transaksi</label>
                    <input type="date" name="transaction_date" value="{{ date('Y-m-d') }}" required class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-850 focus:outline-none focus:ring-2 focus:ring-brand-primary/20 focus:border-brand-primary transition-all [color-scheme:light]">
                </div>

                <!-- Tombol Simpan -->
                <div class="pt-2">
                    <button type="submit" class="w-full btn-primary py-3 flex items-center justify-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Simpan Catatan Transaksi
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- History Table -->
    <div class="w-full lg:w-7/12">
        <div class="card-static rounded-2xl overflow-hidden shadow-sm border border-slate-100 h-full flex flex-col">
            <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <h3 class="text-base font-bold text-slate-800">Riwayat Aktivitas Transaksi</h3>
            </div>
            
            <div class="overflow-x-auto flex-1">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/80 text-slate-500 text-xs uppercase tracking-wider border-b border-slate-100">
                            <th class="px-6 py-3.5 font-semibold">Waktu / Tanggal</th>
                            <th class="px-6 py-3.5 font-semibold">Tipe</th>
                            <th class="px-6 py-3.5 font-semibold">Barang</th>
                            <th class="px-6 py-3.5 font-semibold text-center">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-slate-100">
                        @forelse($transactions as $transaction)
                        <tr class="table-row-hover hover:bg-slate-50/40 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <p class="font-semibold text-slate-700">{{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d M Y') }}</p>
                                <p class="text-[10px] text-slate-400 mt-0.5" title="{{ $transaction->created_at }}">{{ \Carbon\Carbon::parse($transaction->created_at)->diffForHumans() }}</p>
                            </td>
                            <td class="px-6 py-4">
                                @if($transaction->type == 'in')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>
                                    Masuk
                                </span>
                                @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-indigo-50 text-brand-primary">
                                    <span class="w-1.5 h-1.5 rounded-full bg-brand-primary mr-1.5"></span>
                                    Keluar
                                </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-semibold text-slate-800">{{ $transaction->product->name }}</p>
                                @if($transaction->product->code)
                                    <p class="text-[10px] text-slate-400 font-medium mt-0.5">{{ $transaction->product->code }}</p>
                                @endif
                            </td>
                             <td class="px-6 py-4 text-center font-bold text-slate-800">
                                @if($transaction->transaction_unit && $transaction->transaction_unit !== $transaction->product->unit)
                                    {{ $transaction->original_quantity }} {{ $transaction->transaction_unit }}
                                    <span class="text-xs font-normal text-slate-400 block mt-0.5">({{ $transaction->quantity }} {{ $transaction->product->unit }})</span>
                                @else
                                    {{ $transaction->quantity }} <span class="text-xs font-normal text-slate-400">{{ $transaction->product->unit }}</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 rounded-2xl bg-slate-50 flex items-center justify-center mb-4 border border-dashed border-slate-200">
                                        <svg class="w-8 h-8 text-slate-350" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                                    </div>
                                    <p class="text-sm font-semibold text-slate-700">Belum ada riwayat</p>
                                    <p class="text-xs text-slate-400 mt-1">Gunakan form di samping untuk mencatat transaksi masuk/keluar.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($transactions->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 flex justify-between items-center bg-slate-50/50">
                {{ $transactions->links('pagination::tailwind') }}
            </div>
            @endif
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    // Tab indicator translation
    function updateToggle(type) {
        const indicator = document.getElementById('type-indicator');
        const labelIn = document.getElementById('label-in');
        const labelOut = document.getElementById('label-out');
        
        if (type === 'in') {
            // Slide to left
            indicator.style.transform = 'translateX(0)';
            labelIn.className = 'text-emerald-600 transition-colors duration-300 flex items-center justify-center gap-1.5';
            labelOut.className = 'text-slate-450 transition-colors duration-300 flex items-center justify-center gap-1.5';
        } else {
            // Slide to right
            const offsetWidth = indicator.parentElement.clientWidth;
            indicator.style.transform = `translateX(${offsetWidth / 2 - 4}px)`;
            labelIn.className = 'text-slate-450 transition-colors duration-300 flex items-center justify-center gap-1.5';
            labelOut.className = 'text-brand-primary transition-colors duration-300 flex items-center justify-center gap-1.5';
        }
    }

    function handleProductChange() {
        const productSelect = document.getElementById('field-product-id');
        const unitContainer = document.getElementById('transaction-unit-container');
        const txUnitSelect = document.getElementById('field-tx-unit');
        
        if (!productSelect) return;
        
        const selectedOption = productSelect.options[productSelect.selectedIndex];
        
        if (!selectedOption || selectedOption.value === "") {
            unitContainer.classList.add('hidden');
            txUnitSelect.innerHTML = '';
            calculateHelper();
            return;
        }
        
        const unit = selectedOption.getAttribute('data-unit');
        const largeUnit = selectedOption.getAttribute('data-large-unit');
        const conversion = selectedOption.getAttribute('data-conversion');
        
        if (largeUnit && largeUnit !== "" && parseInt(conversion) > 1) {
            unitContainer.classList.remove('hidden');
            txUnitSelect.innerHTML = `
                <option value="${unit}">${unit} (Satuan Utama)</option>
                <option value="${largeUnit}">${largeUnit} (Satuan Besar - isi ${conversion})</option>
            `;
        } else {
            unitContainer.classList.add('hidden');
            txUnitSelect.innerHTML = `
                <option value="${unit}">${unit}</option>
            `;
        }
        
        calculateHelper();
    }

    function calculateHelper() {
        const productSelect = document.getElementById('field-product-id');
        const txUnitSelect = document.getElementById('field-tx-unit');
        const qtyInput = document.getElementById('field-quantity');
        const helperText = document.getElementById('quantity-helper-text');
        
        if (!productSelect || !qtyInput || !helperText) return;
        
        const selectedOption = productSelect.options[productSelect.selectedIndex];
        const qty = parseInt(qtyInput.value) || 0;
        
        if (!selectedOption || selectedOption.value === "" || qty <= 0) {
            helperText.classList.add('hidden');
            helperText.textContent = '';
            return;
        }
        
        const unit = selectedOption.getAttribute('data-unit');
        const largeUnit = selectedOption.getAttribute('data-large-unit');
        const conversion = parseInt(selectedOption.getAttribute('data-conversion')) || 1;
        const currentTxUnit = txUnitSelect.value;
        
        if (largeUnit && currentTxUnit === largeUnit && conversion > 1) {
            const totalBase = qty * conversion;
            helperText.classList.remove('hidden');
            helperText.textContent = `* Setara dengan ${totalBase} ${unit}`;
        } else {
            helperText.classList.add('hidden');
            helperText.textContent = '';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        handleProductChange();
    });
</script>
@endpush
