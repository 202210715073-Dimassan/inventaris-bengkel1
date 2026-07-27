@extends('layouts.app')

@section('title', 'Dashboard Overview')

@section('content')
<!-- Welcome Header -->
<div class="mb-8 animate-fade-in">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-slate-800" id="greeting-text">Selamat Siang, {{ Auth::user()->name ?? 'Admin' }}!</h2>
            <p class="text-sm text-slate-500 mt-1">Berikut ringkasan inventaris hari ini — <span id="today-date" class="font-medium text-slate-600"></span></p>
        </div>
        <div class="flex items-center gap-2">
            <div class="relative">
                <button id="filter-btn" onclick="toggleDropdown()" class="btn-secondary flex items-center gap-2">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span id="filter-label">Hari Ini</span>
                    <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div id="filter-dropdown" class="hidden absolute right-0 mt-2 w-40 bg-white rounded-xl border border-slate-100 shadow-lg py-1 z-20 animate-slide-in">
                    <button onclick="setFilter('Hari Ini')" class="w-full text-left px-4 py-2 text-sm text-slate-700 hover:bg-indigo-50 hover:text-brand-primary transition-colors">Hari Ini</button>
                    <button onclick="setFilter('7 Hari')" class="w-full text-left px-4 py-2 text-sm text-slate-700 hover:bg-indigo-50 hover:text-brand-primary transition-colors">7 Hari</button>
                    <button onclick="setFilter('30 Hari')" class="w-full text-left px-4 py-2 text-sm text-slate-700 hover:bg-indigo-50 hover:text-brand-primary transition-colors">30 Hari</button>
                    <button onclick="setFilter('Semua')" class="w-full text-left px-4 py-2 text-sm text-slate-700 hover:bg-indigo-50 hover:text-brand-primary transition-colors">Semua</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
    <!-- Stat 1: Total Item Produk -->
    <div class="card-premium rounded-2xl p-6 relative overflow-hidden group animate-fade-in-1 bg-white">
        <div class="absolute right-0 top-0 w-28 h-28 bg-gradient-to-bl from-indigo-100/60 to-transparent rounded-bl-full -mr-4 -mt-4 transition-transform duration-300 group-hover:scale-110"></div>
        <div class="flex items-center justify-between relative z-10">
            <div>
                <p class="text-sm font-medium text-slate-500">Total Item Produk</p>
                <h3 class="text-3xl font-extrabold text-slate-800 mt-1">{{ $totalProducts ?? 0 }} <span class="text-xs font-semibold text-slate-400">Item</span></h3>
                <div class="mt-2 flex items-center text-xs text-slate-400">
                    <svg class="w-3.5 h-3.5 mr-1 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    Master Barang
                </div>
            </div>
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-brand-primary flex items-center justify-center shadow-sm">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            </div>
        </div>
    </div>

    <!-- Stat 2: Total Volume Stok -->
    <div class="card-premium rounded-2xl p-6 relative overflow-hidden group animate-fade-in-2 bg-white">
        <div class="absolute right-0 top-0 w-28 h-28 bg-gradient-to-bl from-teal-100/60 to-transparent rounded-bl-full -mr-4 -mt-4 transition-transform duration-300 group-hover:scale-110"></div>
        <div class="flex items-center justify-between relative z-10">
            <div>
                <p class="text-sm font-medium text-slate-500">Total Stok Fisik</p>
                <h3 class="text-3xl font-extrabold text-slate-800 mt-1">{{ number_format($totalStock ?? 0) }} <span class="text-xs font-semibold text-slate-400">Unit</span></h3>
                <div class="mt-2 flex items-center text-xs text-slate-400">
                    <svg class="w-3.5 h-3.5 mr-1 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    Kuantitas Gabungan
                </div>
            </div>
            <div class="w-12 h-12 rounded-xl bg-teal-50 text-brand-teal flex items-center justify-center shadow-sm">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
            </div>
        </div>
    </div>

    <!-- Stat 3: Transaksi Hari Ini -->
    <div class="card-premium rounded-2xl p-6 relative overflow-hidden group animate-fade-in-2 bg-white">
        <div class="absolute right-0 top-0 w-28 h-28 bg-gradient-to-bl from-sky-100/60 to-transparent rounded-bl-full -mr-4 -mt-4 transition-transform duration-300 group-hover:scale-110"></div>
        <div class="flex items-center justify-between relative z-10">
            <div>
                <p class="text-sm font-medium text-slate-500">Transaksi (Hari Ini)</p>
                <h3 class="text-3xl font-extrabold text-slate-800 mt-1">{{ $transactionsToday ?? 0 }}</h3>
                <div class="mt-2 flex items-center gap-3 text-xs">
                    <span class="inline-flex items-center text-teal-600 font-semibold">
                        <span class="dot-indicator bg-teal-400"></span>
                        {{ $inToday ?? 0 }} Masuk
                    </span>
                    <span class="text-slate-300">|</span>
                    <span class="inline-flex items-center text-brand-primary font-semibold">
                        <span class="dot-indicator bg-brand-primary"></span>
                        {{ $outToday ?? 0 }} Keluar
                    </span>
                </div>
            </div>
            <div class="w-12 h-12 rounded-xl bg-sky-50 text-brand-secondary flex items-center justify-center shadow-sm">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
            </div>
        </div>
    </div>

    <!-- Stat 4: Critical Alert -->
    <div class="card-premium rounded-2xl p-6 relative overflow-hidden group animate-fade-in-3 {{ ($lowStockCount ?? 0) > 0 ? 'border-red-200 bg-gradient-to-br from-red-50/50 to-white' : 'bg-white' }}">
        <div class="absolute right-0 top-0 w-28 h-28 bg-gradient-to-bl from-red-100/60 to-transparent rounded-bl-full -mr-4 -mt-4 transition-transform duration-300 group-hover:scale-110"></div>
        <div class="flex items-center justify-between relative z-10">
            <div>
                <p class="text-sm font-medium {{ ($lowStockCount ?? 0) > 0 ? 'text-red-650' : 'text-slate-500' }}">Stok Mencapai ROP</p>
                <h3 class="text-3xl font-extrabold {{ ($lowStockCount ?? 0) > 0 ? 'text-red-650' : 'text-slate-800' }} mt-1">{{ $lowStockCount ?? 0 }} <span class="text-xs font-semibold text-slate-400">Item</span></h3>
                <div class="mt-2">
                    <a href="{{ route('reports.restock') }}" class="text-xs {{ ($lowStockCount ?? 0) > 0 ? 'text-red-500 hover:text-red-700' : 'text-slate-400 hover:text-slate-650' }} font-semibold flex items-center transition-colors group/link">
                        Lihat Laporan 
                        <svg class="w-3.5 h-3.5 ml-1 transition-transform group-hover/link:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>
            </div>
            <div class="w-12 h-12 rounded-xl {{ ($lowStockCount ?? 0) > 0 ? 'bg-red-50 text-red-500' : 'bg-slate-50 text-slate-400' }} flex items-center justify-center shadow-sm">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
        </div>
    </div>
</div>

<!-- Urgent Restock Table -->
<div class="card-static rounded-2xl overflow-hidden animate-fade-in-3 bg-white">
    <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center">
        <h2 class="text-base font-semibold text-slate-800 flex items-center">
            <span class="w-2 h-2 rounded-full bg-red-500 mr-2.5 animate-pulse"></span>
            Stok Kritis — Mencapai ROP
        </h2>
        <a href="{{ route('reports.restock') }}" class="text-sm text-brand-primary hover:text-brand-primaryHover font-semibold flex items-center gap-1 transition-colors group">
            Lihat Semua
            <svg class="w-3.5 h-3.5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/80 text-slate-500 text-xs uppercase tracking-wider border-b border-slate-100">
                    <th class="px-6 py-3.5 font-semibold">Nama Barang</th>
                    <th class="px-6 py-3.5 font-semibold text-center">Stok Saat Ini</th>
                    <th class="px-6 py-3.5 font-semibold text-center">Safety Stock</th>
                    <th class="px-6 py-3.5 font-semibold text-center">ROP (Batas Order)</th>
                    <th class="px-6 py-3.5 font-semibold text-center">Status</th>
                    <th class="px-6 py-3.5 font-semibold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                @forelse($urgentProducts ?? [] as $index => $product)
                @php
                    $ratio = $product->rop_value > 0 ? ($product->stock / $product->rop_value) * 100 : 0;
                    $barColor = $ratio <= 30 ? 'bg-red-500' : ($ratio <= 60 ? 'bg-amber-400' : 'bg-emerald-400');
                    $barBg = $ratio <= 30 ? 'bg-red-100' : ($ratio <= 60 ? 'bg-amber-100' : 'bg-emerald-100');
                @endphp
                <tr class="table-row-hover border-b border-slate-50 {{ $index % 2 == 1 ? 'bg-slate-50/30' : '' }} group/row">
                    <td class="px-6 py-4">
                        <p class="font-semibold text-slate-800">{{ $product->name }}</p>
                        <p class="text-xs text-slate-400 mt-0.5">Kode: {{ $product->code ?? '#PRD-' . str_pad($product->id, 3, '0', STR_PAD_LEFT) }}</p>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold {{ $product->stock <= $product->ss_value ? 'bg-red-500 text-white' : 'bg-amber-100 text-amber-700' }}">
                            {{ $product->formatted_stock }}
                        </span>
                        <!-- Mini Progress Bar -->
                        <div class="mt-1.5 mx-auto w-16 h-1.5 rounded-full {{ $barBg }} overflow-hidden">
                            <div class="h-full rounded-full {{ $barColor }} transition-all duration-500" style="width: {{ min($ratio, 100) }}%"></div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center text-slate-500 font-medium">{{ round($product->ss_value) }} {{ $product->unit }}</td>
                    <td class="px-6 py-4 text-center text-slate-700 font-semibold">{{ $product->formatted_rop }}</td>
                    <td class="px-6 py-4 text-center">
                        <div class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold {{ $product->stock <= $product->ss_value ? 'bg-red-50 text-red-600' : 'bg-amber-50 text-amber-600' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $product->stock <= $product->ss_value ? 'bg-red-500 animate-pulse' : 'bg-amber-400' }} mr-1.5"></span>
                            {{ $product->stock <= $product->ss_value ? 'Darurat' : 'Kritis' }}
                        </div>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-1.5 opacity-0 group-hover/row:opacity-100 transition-opacity duration-200">
                            <a href="{{ route('reports.restock') }}" class="p-1.5 rounded-lg hover:bg-indigo-50 text-slate-400 hover:text-brand-primary transition-colors" title="Lihat Laporan">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </a>
                            <a href="https://wa.me/?text=Halo%2C%20saya%20ingin%20pesan%20{{ urlencode($product->name) }}" target="_blank" class="p-1.5 rounded-lg hover:bg-emerald-50 text-slate-400 hover:text-emerald-600 transition-colors" title="Order via WhatsApp">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-16 text-center">
                        <div class="flex flex-col items-center">
                            <div class="w-16 h-16 rounded-2xl bg-emerald-50 flex items-center justify-center mb-4">
                                <svg class="w-8 h-8 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <p class="text-sm font-semibold text-slate-700">Semua stok aman! 🎉</p>
                            <p class="text-xs text-slate-400 mt-1">Tidak ada barang yang menyentuh batas Reorder Point.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Complete Stock Monitoring Section -->
<div class="card-static rounded-2xl overflow-hidden mt-8 animate-fade-in-3 bg-white">
    <div class="px-6 py-5 border-b border-slate-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-base font-semibold text-slate-800 flex items-center">
                <span class="w-2 h-2 rounded-full bg-brand-primary mr-2.5"></span>
                Pemantauan Seluruh Stok Barang
            </h2>
            <p class="text-xs text-slate-450 mt-0.5">Daftar stok fisik terkini beserta satuan dan status Reorder Point (ROP)</p>
        </div>
        <div class="w-full sm:w-64">
            <input type="text" id="stock-search" onkeyup="filterStockTable()" placeholder="Cari nama barang..." class="block w-full px-3.5 py-1.5 border border-slate-200 rounded-xl bg-white text-xs text-slate-750 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-primary/20 focus:border-brand-primary transition-all">
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse" id="stock-table">
            <thead>
                <tr class="bg-slate-50/80 text-slate-500 text-xs uppercase tracking-wider border-b border-slate-100">
                    <th class="px-6 py-3.5 font-semibold">Kode</th>
                    <th class="px-6 py-3.5 font-semibold">Nama Barang</th>
                    <th class="px-6 py-3.5 font-semibold">Kategori</th>
                    <th class="px-6 py-3.5 font-semibold text-center">Stok Saat Ini</th>
                    <th class="px-6 py-3.5 font-semibold text-center">Reorder Point (ROP)</th>
                    <th class="px-6 py-3.5 font-semibold text-center">Status</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                @forelse($products ?? [] as $index => $product)
                @php
                    $isCritical = $product->stock <= $product->rop_value;
                    $isWarning = !$isCritical && ($product->stock <= ($product->rop_value + 5));
                    
                    if ($isCritical) {
                        $badgeClass = 'bg-red-50 text-red-600 border border-red-100';
                        $statusText = 'Butuh Restock';
                        $statusDot = 'bg-red-500 animate-pulse';
                    } elseif ($isWarning) {
                        $badgeClass = 'bg-amber-50 text-amber-700 border border-amber-100';
                        $statusText = 'Stok Tipis';
                        $statusDot = 'bg-amber-500';
                    } else {
                        $badgeClass = 'bg-emerald-50 text-emerald-700 border border-emerald-100';
                        $statusText = 'Aman';
                        $statusDot = 'bg-emerald-500';
                    }
                @endphp
                <tr class="table-row-hover border-b border-slate-50 {{ $index % 2 == 1 ? 'bg-slate-50/30' : '' }} stock-row">
                    <td class="px-6 py-4 text-slate-450 font-medium">
                        {{ $product->code ?? '#PRD-' . str_pad($product->id, 3, '0', STR_PAD_LEFT) }}
                    </td>
                    <td class="px-6 py-4 font-semibold text-slate-800 name-cell">{{ $product->name }}</td>
                    <td class="px-6 py-4 text-slate-500">{{ $product->category ?? '-' }}</td>
                    <td class="px-6 py-4 text-center font-bold {{ $isCritical ? 'text-red-650' : 'text-slate-800' }}">
                        {{ $product->formatted_stock }}
                    </td>
                    <td class="px-6 py-4 text-center text-slate-500 font-medium">
                        {{ $product->formatted_rop }}
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $badgeClass }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $statusDot }} mr-1.5"></span>
                            {{ $statusText }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-16 text-center">
                        <p class="text-sm font-semibold text-slate-700">Tidak ada produk.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Greeting based on time
    (function() {
        const hour = new Date().getHours();
        let greeting = 'Selamat Malam';
        if (hour >= 5 && hour < 12) greeting = 'Selamat Pagi';
        else if (hour >= 12 && hour < 15) greeting = 'Selamat Siang';
        else if (hour >= 15 && hour < 18) greeting = 'Selamat Sore';
        
        const el = document.getElementById('greeting-text');
        if (el) el.textContent = greeting + ', {{ Auth::user()->name ?? "Admin" }}!';

        // Format today date
        const dateEl = document.getElementById('today-date');
        if (dateEl) {
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            dateEl.textContent = new Date().toLocaleDateString('id-ID', options);
        }
    })();

    // Dropdown toggle
    function toggleDropdown() {
        document.getElementById('filter-dropdown').classList.toggle('hidden');
    }
    function setFilter(label) {
        document.getElementById('filter-label').textContent = label;
        document.getElementById('filter-dropdown').classList.add('hidden');
    }
    // Close dropdown on outside click
    document.addEventListener('click', function(e) {
        const btn = document.getElementById('filter-btn');
        const dropdown = document.getElementById('filter-dropdown');
        if (btn && dropdown && !btn.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.classList.add('hidden');
        }
    });

    // Filter stock table
    function filterStockTable() {
        const input = document.getElementById('stock-search');
        const filter = input.value.toLowerCase();
        const rows = document.getElementsByClassName('stock-row');
        
        for (let i = 0; i < rows.length; i++) {
            const nameCell = rows[i].getElementsByClassName('name-cell')[0];
            if (nameCell) {
                const textValue = nameCell.textContent || nameCell.innerText;
                if (textValue.toLowerCase().indexOf(filter) > -1) {
                    rows[i].style.display = "";
                } else {
                    rows[i].style.display = "none";
                }
            }
        }
    }
</script>
@endpush
