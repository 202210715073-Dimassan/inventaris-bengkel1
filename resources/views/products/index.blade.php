@extends('layouts.app')

@section('title', 'Master Barang')

@section('content')
<!-- Filters & Search Header -->
<div class="flex flex-col sm:flex-row justify-between items-stretch sm:items-center gap-3 mb-6 animate-fade-in">
    <div class="relative w-full sm:max-w-xs">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </div>
        <form action="{{ route('products.index') }}" method="GET">
            <input type="text" name="search" value="{{ request('search') }}" class="block w-full pl-9 pr-3 py-2 border border-slate-200 rounded-xl leading-5 bg-white text-slate-750 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-primary/20 focus:border-brand-primary text-sm transition-all" placeholder="Cari barang...">
        </form>
    </div>
    <div>
        <button onclick="openDrawer('add')" class="btn-primary flex items-center gap-1.5 shadow-sm w-full sm:w-auto justify-center">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Barang
        </button>
    </div>
</div>

<!-- Products Table -->
<div class="card-static rounded-2xl overflow-hidden shadow-sm border border-slate-100 animate-fade-in-1 bg-white">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/80 text-slate-500 text-xs uppercase tracking-wider border-b border-slate-100">
                    <th class="px-6 py-3.5 font-semibold">Kode</th>
                    <th class="px-6 py-3.5 font-semibold">Nama Barang</th>
                    <th class="px-6 py-3.5 font-semibold">Kategori</th>
                    <th class="px-6 py-3.5 font-semibold">Supplier</th>
                    <th class="px-6 py-3.5 font-semibold text-center">Stok Fisik</th>
                    <th class="px-6 py-3.5 font-semibold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm divide-y divide-slate-100">
                @forelse($products as $product)
                @php
                    $isCritical = $product->stock <= $product->rop_value;
                @endphp
                <tr class="table-row-hover hover:bg-slate-50/40 transition-all group/row">
                    <td class="px-6 py-4 text-slate-450 font-medium">
                        {{ $product->code ?? '#PRD-' . str_pad($product->id, 3, '0', STR_PAD_LEFT) }}
                    </td>
                    <td class="px-6 py-4 font-semibold text-slate-800">{{ $product->name }}</td>
                    <td class="px-6 py-4 text-slate-500">{{ $product->category ?? '-' }}</td>
                    <td class="px-6 py-4 text-sm text-slate-500">
                        @if($product->supplier)
                            <span class="inline-flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-indigo-400"></span>
                                {{ $product->supplier->name }}
                            </span>
                        @else
                            <span class="text-slate-300 italic">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center font-bold {{ $isCritical ? 'text-red-650' : 'text-slate-850' }}">
                        {{ $product->formatted_stock }}
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-1.5">
                            <button onclick="openEditDrawer(this)" data-product="{{ json_encode($product) }}" class="p-2 rounded-lg hover:bg-indigo-50 text-slate-400 hover:text-brand-primary transition-colors" title="Edit Barang">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </button>
                            <button onclick="triggerDelete({{ $product->id }}, '{{ $product->name }}')" class="p-2 rounded-lg hover:bg-red-50 text-slate-400 hover:text-red-650 transition-colors" title="Hapus Barang">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-16 text-center">
                        <div class="flex flex-col items-center">
                            <div class="w-16 h-16 rounded-2xl bg-slate-50 flex items-center justify-center mb-4 border border-dashed border-slate-200">
                                <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            </div>
                            <p class="text-sm font-semibold text-slate-700">Belum ada barang</p>
                            <p class="text-xs text-slate-400 mt-1">Daftar produk inventaris Anda akan muncul di sini.</p>
                            <button onclick="openDrawer('add')" class="btn-primary py-2 px-4 mt-4 text-xs">Tambah Barang Pertama</button>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($products->hasPages())
    <div class="px-6 py-4 border-t border-slate-100 flex justify-between items-center bg-slate-50/50">
        {{ $products->links('pagination::tailwind') }}
    </div>
    @endif
</div>

<!-- Sliding Drawer Container -->
<div id="drawer-overlay" onclick="closeDrawer()" class="drawer-overlay"></div>
<div id="drawer-panel" class="drawer-panel sm:max-w-[28rem]">
    <!-- Header -->
    <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between sticky top-0 bg-white z-10">
        <h3 id="drawer-title" class="text-base font-bold text-slate-800">Tambah Master Barang Baru</h3>
        <button onclick="closeDrawer()" class="text-slate-400 hover:text-slate-600 transition-colors p-1.5 rounded-lg hover:bg-slate-50">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
    
    <!-- Form -->
    <form id="drawer-form" method="POST" class="flex flex-col h-[calc(100%-65px)] bg-white">
        @csrf
        <div id="method-container"></div>
        
        <div class="flex-1 px-6 py-5 space-y-5 overflow-y-auto">
            <!-- Kode & Nama Barang Grid -->
            <div class="grid grid-cols-3 gap-4">
                <div class="col-span-1">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5">Kode Barang</label>
                    <input type="text" name="code" id="field-code" placeholder="Contoh: GEN-CM1691" class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-850 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-primary/20 focus:border-brand-primary transition-all">
                </div>
                <div class="col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5">Nama Barang</label>
                    <input type="text" name="name" id="field-name" required placeholder="Contoh: Oli Mesin MPX 2" class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-850 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-primary/20 focus:border-brand-primary transition-all">
                </div>
            </div>

            <!-- Kategori & Supplier Grid -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5">Kategori</label>
                    <input type="text" name="category" id="field-category" placeholder="Contoh: Servis & Performa" class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-850 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-primary/20 focus:border-brand-primary transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5">Supplier <span class="text-red-500">*</span></label>
                    <select name="supplier_id" id="field-supplier-id" required class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-primary/20 focus:border-brand-primary transition-all">
                        <option value="">-- Pilih Supplier --</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                        @endforeach
                    </select>
                    @if($suppliers->isEmpty())
                        <p class="text-xs text-amber-600 mt-1">
                            ⚠ Belum ada supplier. <a href="{{ route('suppliers.index') }}" class="underline font-semibold text-brand-primary">Tambah supplier dulu</a>.
                        </p>
                    @endif
                </div>
            </div>
            
            <!-- Grid Stok & Satuan -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5">Stok Awal</label>
                    <input type="number" name="stock" id="field-stock" value="0" min="0" required class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-850 focus:outline-none focus:ring-2 focus:ring-brand-primary/20 focus:border-brand-primary transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5">Satuan Terkecil (Utama) <span class="text-red-500">*</span></label>
                    <select name="unit" id="field-unit" required class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-primary/20 focus:border-brand-primary transition-all">
                        <option value="pcs">pcs (Pieces)</option>
                        <option value="unit">unit</option>
                        <option value="botol">botol</option>
                        <option value="set">set</option>
                        <option value="drum">drum</option>
                        <option value="box">box</option>
                    </select>
                </div>
            </div>

            <!-- Satuan Besar & Konversi Grid -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5">Satuan Besar (Opsional)</label>
                    <select name="large_unit" id="field-large-unit" class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-primary/20 focus:border-brand-primary transition-all" onchange="toggleConversionInput()">
                        <option value="">Tidak Ada (Tanpa Satuan Besar)</option>
                        <option value="dus">dus</option>
                        <option value="box">box</option>
                        <option value="karton">karton</option>
                        <option value="pack">pack</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5">Konversi (Isi per Satuan Besar)</label>
                    <input type="number" name="unit_conversion" id="field-unit-conversion" value="1" min="1" class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-850 focus:outline-none focus:ring-2 focus:ring-brand-primary/20 focus:border-brand-primary transition-all" placeholder="Contoh: 24">
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5" title="Lead Time">Lead Time (Hari)</label>
                    <input type="number" name="lead_time" id="field-lead-time" min="0" required placeholder="Kirim rata-rata" class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-850 focus:outline-none focus:ring-2 focus:ring-brand-primary/20 focus:border-brand-primary transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5" title="Max Lead Time">Max Lead Time (Hari)</label>
                    <input type="number" name="max_lead_time" id="field-max-lead-time" min="0" required placeholder="Kirim terlama" class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-850 focus:outline-none focus:ring-2 focus:ring-brand-primary/20 focus:border-brand-primary transition-all">
                </div>
            </div>
            
            <div class="p-3.5 bg-blue-50/60 border border-blue-100 rounded-xl text-xs text-blue-700 leading-relaxed">
                <span class="font-bold text-blue-800">Sistem Perhitungan Otomatis ROP:</span> Lead Time dan Max Lead Time di atas digunakan untuk menghitung Safety Stock (SS) dan Reorder Point (ROP) secara otomatis berdasarkan rata-rata transaksi barang keluar.
            </div>
        </div>
        
        <!-- Action Buttons Footer -->
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 flex items-center justify-end gap-3 sticky bottom-0 z-10">
            <button type="button" onclick="closeDrawer()" class="btn-secondary py-2 px-4 text-xs font-semibold">Batal</button>
            <button type="submit" id="drawer-submit-btn" class="btn-primary py-2 px-4 text-xs font-bold">Simpan Barang</button>
        </div>
    </form>
</div>

<!-- Custom Confirmation Dialog (Hapus) -->
<div id="delete-modal" class="hidden fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" onclick="closeDeleteModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full border border-slate-100 animate-slide-in">
            <div class="p-6 text-center">
                <div class="w-12 h-12 rounded-full bg-red-50 text-red-500 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <h3 class="text-base font-bold text-slate-800">Hapus Master Barang?</h3>
                <p class="text-xs text-slate-500 mt-2">Anda akan menghapus produk <span id="delete-product-name" class="font-bold text-slate-700"></span>. Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="px-6 py-4 bg-slate-50/50 border-t border-slate-100 flex items-center justify-end gap-3">
                <button type="button" onclick="closeDeleteModal()" class="btn-secondary py-2 px-4 text-xs font-semibold">Batal</button>
                <form id="delete-form" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-primary bg-red-600 hover:bg-red-700 py-2 px-4 text-xs font-bold">Hapus Sekarang</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Open/Close Sliding Drawer
    function toggleConversionInput() {
        const largeUnit = document.getElementById('field-large-unit');
        const conversion = document.getElementById('field-unit-conversion');
        if (largeUnit && conversion) {
            if (largeUnit.value === '') {
                conversion.value = 1;
                conversion.disabled = true;
                conversion.style.opacity = '0.5';
            } else {
                conversion.disabled = false;
                conversion.style.opacity = '1';
            }
        }
    }

    function openDrawer(mode, data = null) {
        const overlay = document.getElementById('drawer-overlay');
        const panel = document.getElementById('drawer-panel');
        const title = document.getElementById('drawer-title');
        const form = document.getElementById('drawer-form');
        const submitBtn = document.getElementById('drawer-submit-btn');
        const methodContainer = document.getElementById('method-container');
        
        // Fields
        const fCode           = document.getElementById('field-code');
        const fName           = document.getElementById('field-name');
        const fCategory       = document.getElementById('field-category');
        const fSupplierId     = document.getElementById('field-supplier-id');
        const fStock          = document.getElementById('field-stock');
        const fUnit           = document.getElementById('field-unit');
        const fLargeUnit      = document.getElementById('field-large-unit');
        const fUnitConversion = document.getElementById('field-unit-conversion');
        const fLeadTime       = document.getElementById('field-lead-time');
        const fMaxLeadTime    = document.getElementById('field-max-lead-time');

        // Reset
        if (form) form.reset();
        if (methodContainer) methodContainer.innerHTML = '';
        
        if (fStock) {
            fStock.removeAttribute('disabled');
            const parentDiv = fStock.closest('div');
            if (parentDiv) parentDiv.style.opacity = '1';
        }

        if (mode === 'add') {
            if (title) title.textContent = 'Tambah Master Barang Baru';
            if (form) form.action = "{{ route('products.store') }}";
            if (submitBtn) submitBtn.textContent = 'Simpan Barang';
            if (fUnit) fUnit.value = 'pcs';
            if (fLargeUnit) fLargeUnit.value = '';
            if (fUnitConversion) fUnitConversion.value = 1;
        } else if (mode === 'edit' && data) {
            if (title) title.textContent = 'Edit Data Master Barang';
            if (form) form.action = `/products/${data.id}`;
            
            // Put method override (Standard HTML input method override)
            if (methodContainer) methodContainer.innerHTML = '<input type="hidden" name="_method" value="PUT">';
            
            // Populate fields
            if (fCode) fCode.value = data.code || '';
            if (fName) fName.value = data.name || '';
            if (fCategory) fCategory.value = data.category || '';
            if (fSupplierId) fSupplierId.value = data.supplier_id ?? '';
            if (fStock) fStock.value = data.stock ?? 0;
            if (fUnit) fUnit.value = data.unit || 'pcs';
            if (fLargeUnit) fLargeUnit.value = data.large_unit || '';
            if (fUnitConversion) fUnitConversion.value = data.unit_conversion ?? 1;
            if (fLeadTime) fLeadTime.value = data.lead_time ?? 0;
            if (fMaxLeadTime) fMaxLeadTime.value = data.max_lead_time ?? 0;

            if (submitBtn) submitBtn.textContent = 'Simpan Perubahan';
        }

        // Apply input toggling
        toggleConversionInput();

        // Show
        if (overlay) overlay.classList.add('active');
        if (panel) panel.classList.add('active');
    }

    function openEditDrawer(btn) {
        if (!btn) return;
        try {
            const rawData = btn.getAttribute('data-product');
            if (rawData) {
                const data = JSON.parse(rawData);
                openDrawer('edit', data);
            }
        } catch (e) {
            console.error("Error parsing product data", e);
        }
    }

    function closeDrawer() {
        const overlay = document.getElementById('drawer-overlay');
        const panel = document.getElementById('drawer-panel');
        if (overlay) overlay.classList.remove('active');
        if (panel) panel.classList.remove('active');
    }

    // Custom Delete Dialog
    function triggerDelete(id, name) {
        const delName = document.getElementById('delete-product-name');
        const delForm = document.getElementById('delete-form');
        const delModal = document.getElementById('delete-modal');
        
        if (delName) delName.textContent = name;
        if (delForm) delForm.action = `/products/${id}`;
        if (delModal) delModal.classList.remove('hidden');
    }

    // Close Delete Modal
    function closeDeleteModal() {
        const delModal = document.getElementById('delete-modal');
        if (delModal) delModal.classList.add('hidden');
    }
</script>
@endpush
