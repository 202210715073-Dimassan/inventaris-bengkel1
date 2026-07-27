@extends('layouts.app')

@section('title', 'Master Supplier')

@section('content')
<!-- Header -->
<div class="flex flex-col sm:flex-row justify-between items-stretch sm:items-center gap-4 mb-6 animate-fade-in">
    <div class="relative max-w-xs w-full">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </div>
        <form action="{{ route('suppliers.index') }}" method="GET">
            <input type="text" name="search" value="{{ request('search') }}" class="block w-full pl-9 pr-3 py-2 border border-slate-200 rounded-xl leading-5 bg-white text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-primary/20 focus:border-brand-primary text-sm transition-all" placeholder="Cari supplier...">
        </form>
    </div>
    <div>
        <button onclick="openDrawer('add')" class="btn-primary flex items-center gap-1.5 shadow-sm w-full sm:w-auto justify-center">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Supplier
        </button>
    </div>
</div>

<!-- Flash Messages -->
@if(session('success'))
<div class="mb-4 px-4 py-3 bg-emerald-50 border border-emerald-200 rounded-xl text-sm text-emerald-700 flex items-center gap-2 animate-fade-in">
    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
    {{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700 flex items-center gap-2 animate-fade-in">
    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M12 3a9 9 0 110 18A9 9 0 0112 3z"/></svg>
    {{ session('error') }}
</div>
@endif

<!-- Suppliers Table -->
<div class="card-static rounded-2xl overflow-hidden shadow-sm border border-slate-100 animate-fade-in-1">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/80 text-slate-500 text-xs uppercase tracking-wider border-b border-slate-100">
                    <th class="px-6 py-3.5 font-semibold">ID</th>
                    <th class="px-6 py-3.5 font-semibold">Nama Supplier</th>
                    <th class="px-6 py-3.5 font-semibold">Telepon</th>
                    <th class="px-6 py-3.5 font-semibold">Email</th>
                    <th class="px-6 py-3.5 font-semibold">Alamat</th>
                    <th class="px-6 py-3.5 font-semibold text-center">Jml. Produk</th>
                    <th class="px-6 py-3.5 font-semibold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm divide-y divide-slate-100">
                @forelse($suppliers as $supplier)
                <tr class="table-row-hover hover:bg-slate-50/40 transition-all">
                    <td class="px-6 py-4 text-slate-400 font-medium">#SUP-{{ str_pad($supplier->id, 3, '0', STR_PAD_LEFT) }}</td>
                    <td class="px-6 py-4 font-semibold text-slate-800">{{ $supplier->name }}</td>
                    <td class="px-6 py-4 text-slate-500">{{ $supplier->phone ?? '-' }}</td>
                    <td class="px-6 py-4 text-slate-500">{{ $supplier->email ?? '-' }}</td>
                    <td class="px-6 py-4 text-slate-500 max-w-xs truncate">{{ $supplier->address ?? '-' }}</td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700">
                            {{ $supplier->products_count }} produk
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-1.5">
                            <button onclick="openEditDrawer(this)" data-supplier="{{ json_encode($supplier) }}" class="p-2 rounded-lg hover:bg-blue-50 text-slate-400 hover:text-blue-600 transition-colors" title="Edit Supplier">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </button>
                            <button onclick="triggerDelete({{ $supplier->id }}, '{{ $supplier->name }}')" class="p-2 rounded-lg hover:bg-red-50 text-slate-400 hover:text-red-600 transition-colors" title="Hapus Supplier">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-16 text-center">
                        <div class="flex flex-col items-center">
                            <div class="w-16 h-16 rounded-2xl bg-slate-50 flex items-center justify-center mb-4 border border-dashed border-slate-200">
                                <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <p class="text-sm font-semibold text-slate-700">Belum ada supplier</p>
                            <p class="text-xs text-slate-400 mt-1">Tambahkan supplier untuk menghubungkan dengan produk.</p>
                            <button onclick="openDrawer('add')" class="btn-primary py-2 px-4 mt-4 text-xs">Tambah Supplier Pertama</button>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($suppliers->hasPages())
    <div class="px-6 py-4 border-t border-slate-100 flex justify-between items-center bg-slate-50/50">
        {{ $suppliers->links('pagination::tailwind') }}
    </div>
    @endif
</div>

<!-- Sliding Drawer -->
<div id="drawer-overlay" onclick="closeDrawer()" class="drawer-overlay"></div>
<div id="drawer-panel" class="drawer-panel">
    <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between sticky top-0 bg-white z-10">
        <h3 id="drawer-title" class="text-base font-bold text-slate-800">Tambah Supplier Baru</h3>
        <button onclick="closeDrawer()" class="text-slate-400 hover:text-slate-600 transition-colors p-1.5 rounded-lg hover:bg-slate-50">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
    <form id="drawer-form" method="POST" class="flex flex-col h-[calc(100%-65px)]">
        @csrf
        <div id="method-container"></div>
        <div class="flex-1 px-6 py-5 space-y-5">
            <!-- Nama Supplier -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5">Nama Supplier <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="field-name" required placeholder="Contoh: PT. Andalan Spare Part" class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-primary/20 focus:border-brand-primary transition-all">
            </div>
            <!-- Telepon -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5">Nomor Telepon</label>
                <input type="text" name="phone" id="field-phone" placeholder="Contoh: 0812-3456-7890" class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-primary/20 focus:border-brand-primary transition-all">
            </div>
            <!-- Email -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5">Email</label>
                <input type="email" name="email" id="field-email" placeholder="Contoh: supplier@email.com" class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-primary/20 focus:border-brand-primary transition-all">
            </div>
            <!-- Alamat -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5">Alamat</label>
                <textarea name="address" id="field-address" rows="3" placeholder="Contoh: Jl. Raya Industri No. 10, Bandung" class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-primary/20 focus:border-brand-primary transition-all resize-none"></textarea>
            </div>
        </div>
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 flex items-center justify-end gap-3 sticky bottom-0 z-10">
            <button type="button" onclick="closeDrawer()" class="btn-secondary py-2 px-4 text-xs font-semibold">Batal</button>
            <button type="submit" id="drawer-submit-btn" class="btn-primary py-2 px-4 text-xs font-bold">Simpan Supplier</button>
        </div>
    </form>
</div>

<!-- Delete Confirmation Modal -->
<div id="delete-modal" class="hidden fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="closeDeleteModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full border border-slate-100">
            <div class="p-6 text-center">
                <div class="w-12 h-12 rounded-full bg-red-50 text-red-500 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <h3 class="text-base font-bold text-slate-800">Hapus Supplier?</h3>
                <p class="text-xs text-slate-500 mt-2">Anda akan menghapus supplier <span id="delete-supplier-name" class="font-bold text-slate-700"></span>. Aksi ini tidak dapat dibatalkan.</p>
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
    function openDrawer(mode, data = null) {
        const overlay   = document.getElementById('drawer-overlay');
        const panel     = document.getElementById('drawer-panel');
        const title     = document.getElementById('drawer-title');
        const form      = document.getElementById('drawer-form');
        const submitBtn = document.getElementById('drawer-submit-btn');
        const methodContainer = document.getElementById('method-container');

        if (form) form.reset();
        if (methodContainer) methodContainer.innerHTML = '';

        if (mode === 'add') {
            if (title) title.textContent = 'Tambah Supplier Baru';
            if (form) form.action = "{{ route('suppliers.store') }}";
            if (submitBtn) submitBtn.textContent = 'Simpan Supplier';
        } else if (mode === 'edit' && data) {
            if (title) title.textContent = 'Edit Data Supplier';
            if (form) form.action = `/suppliers/${data.id}`;
            if (methodContainer) methodContainer.innerHTML = '<input type="hidden" name="_method" value="PUT">';

            document.getElementById('field-name').value    = data.name    || '';
            document.getElementById('field-phone').value   = data.phone   || '';
            document.getElementById('field-email').value   = data.email   || '';
            document.getElementById('field-address').value = data.address || '';

            if (submitBtn) submitBtn.textContent = 'Simpan Perubahan';
        }

        if (overlay) overlay.classList.add('active');
        if (panel)   panel.classList.add('active');
    }

    function openEditDrawer(btn) {
        try {
            const data = JSON.parse(btn.getAttribute('data-supplier'));
            openDrawer('edit', data);
        } catch (e) {
            console.error('Error parsing supplier data', e);
        }
    }

    function closeDrawer() {
        document.getElementById('drawer-overlay')?.classList.remove('active');
        document.getElementById('drawer-panel')?.classList.remove('active');
    }

    function triggerDelete(id, name) {
        document.getElementById('delete-supplier-name').textContent = name;
        document.getElementById('delete-form').action = `/suppliers/${id}`;
        document.getElementById('delete-modal').classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('delete-modal').classList.add('hidden');
    }
</script>
@endpush
