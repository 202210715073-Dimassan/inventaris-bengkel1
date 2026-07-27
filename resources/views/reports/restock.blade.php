@extends('layouts.app')

@section('title', 'Laporan Restock')

@section('content')
<!-- Header Area -->
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 animate-fade-in">
    <div>
        <h2 class="text-xl font-bold text-slate-800">Laporan Restock (Stok Kritis)</h2>
        <p class="text-xs text-slate-500 mt-1">Daftar produk yang menyentuh batas ROP dan estimasi unit yang perlu segera diorder.</p>
    </div>
    <div class="flex items-center gap-3 flex-shrink-0 w-full sm:w-auto">
        <a href="{{ route('reports.export.pdf') }}" target="_blank" class="btn-secondary py-2 px-3.5 flex items-center transition-all text-xs font-semibold shadow-sm">
            <svg class="w-4 h-4 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Print PDF
        </a>
        <a href="{{ route('reports.export.excel') }}" class="btn-primary bg-emerald-600 hover:bg-emerald-700 py-2 px-3.5 flex items-center transition-all text-xs font-semibold shadow-sm">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            Export Excel
        </a>
    </div>
</div>

<!-- Alert Info (Dismissible) -->
@if($urgentProducts->count() > 0)
<div id="restock-alert" class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6 flex items-start justify-between animate-fade-in-1 transition-all duration-300">
    <div class="flex items-start">
        <div class="flex-shrink-0 mt-0.5">
            <svg class="h-5 w-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        </div>
        <div class="ml-3 pr-4">
            <h3 class="text-sm font-bold text-red-700">Peringatan Kritis!</h3>
            <p class="mt-1 text-xs text-red-600">
                Terdapat <span class="font-bold">{{ $urgentProducts->count() }}</span> produk yang stoknya sudah menyentuh atau berada di bawah Reorder Point (ROP). Harap segera lakukan restock untuk mencegah kekosongan stok.
            </p>
        </div>
    </div>
    <button onclick="dismissAlert('restock-alert')" class="text-red-400 hover:text-red-600 transition-colors p-1 rounded-lg hover:bg-red-100/50">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
</div>
@endif

<!-- Analytics Summary Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 mb-8 animate-fade-in-2">
    <!-- Card 1 -->
    <div class="card-static p-6 flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Item Kritis</p>
            <h4 class="text-2xl font-bold text-slate-800 mt-1">{{ $urgentProducts->count() }} <span class="text-xs font-semibold text-slate-400">Item</span></h4>
            <p class="text-[11px] text-slate-400 mt-1">Item butuh order segera</p>
        </div>
        <div class="w-12 h-12 rounded-xl bg-red-50 text-red-500 flex items-center justify-center">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        </div>
    </div>
    <!-- Card 2 -->
    <div class="card-static p-6 flex items-center justify-between">
        @php
            $totalSuggestedOrder = 0;
            $breakdown = [];
            foreach($urgentProducts as $p) {
                // Estimasi order ideal: ROP + SS - Stock
                $suggested = ceil(max(0, ($p->rop_value + $p->ss_value) - $p->stock));
                $totalSuggestedOrder += $suggested;
                if ($suggested > 0) {
                    $breakdown[$p->unit] = ($breakdown[$p->unit] ?? 0) + $suggested;
                }
            }
        @endphp
        <div>
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Estimasi Unit Order</p>
            <h4 class="text-2xl font-bold text-slate-850 mt-1 text-vespa-orange">
                ~{{ $totalSuggestedOrder }} 
                <span class="text-xs font-semibold text-slate-400">Unit</span>
            </h4>
            <p class="text-[11px] text-slate-400 mt-1">
                @if(count($breakdown) > 0)
                    Detail: 
                    @foreach($breakdown as $unit => $qty)
                        {{ $qty }} {{ $unit }}{{ !$loop->last ? ',' : '' }}
                    @endforeach
                @else
                    Estimasi unit pengisian ideal
                @endif
            </p>
        </div>
        <div class="w-12 h-12 rounded-xl bg-orange-50 text-vespa-orange flex items-center justify-center">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
        </div>
    </div>
    <!-- Card 3 -->
    <div class="card-static p-6 flex items-center justify-between">
        @php
            $avgLeadTime = $urgentProducts->count() > 0 ? round($urgentProducts->avg('lead_time')) : 0;
        @endphp
        <div>
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider font-sans">Rata-rata Lead Time</p>
            <h4 class="text-2xl font-bold text-slate-800 mt-1">{{ $avgLeadTime }} Hari</h4>
            <p class="text-[11px] text-slate-400 mt-1">Waktu tunggu pengiriman</p>
        </div>
        <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-500 flex items-center justify-center">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
    </div>
</div>

<!-- Table Controls -->
<div class="flex flex-col sm:flex-row justify-between items-stretch sm:items-center gap-4 mb-4 animate-fade-in-3">
    <!-- Search Bar -->
    <div class="relative max-w-xs w-full">
        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </span>
        <input type="text" id="table-search" onkeyup="filterRestockTable()" placeholder="Cari barang..." class="block w-full pl-9 pr-3 py-2 border border-slate-200 rounded-xl bg-white text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-vespa-orange/20 focus:border-vespa-orange transition-all">
    </div>
</div>

<!-- Restock Table -->
<div class="card-static rounded-2xl overflow-hidden shadow-sm border border-slate-100 animate-fade-in-3">
    <div class="overflow-x-auto">
        <table id="restock-table" class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/80 text-slate-500 text-xs uppercase tracking-wider border-b border-slate-100">
                    <th class="px-6 py-3.5 font-semibold cursor-pointer select-none" onclick="sortRestockTable(0)">
                        Nama Barang <span class="sort-icon inline-block ml-1 text-slate-400">⇅</span>
                    </th>
                    <th class="px-6 py-3.5 font-semibold text-center cursor-pointer select-none" onclick="sortRestockTable(1)">
                        Stok Saat Ini <span class="sort-icon inline-block ml-1 text-slate-400">⇅</span>
                    </th>
                    <th class="px-6 py-3.5 font-semibold text-center cursor-pointer select-none" onclick="sortRestockTable(2)">
                        ROP <span class="sort-icon inline-block ml-1 text-slate-400">⇅</span>
                    </th>
                    <th class="px-6 py-3.5 font-semibold text-center">Rasio ROP</th>
                    <th class="px-6 py-3.5 font-semibold text-center">Estimasi Order</th>
                </tr>
            </thead>
            <tbody class="text-sm divide-y divide-slate-100">
                @forelse($urgentProducts as $product)
                @php
                    $ratio = $product->rop_value > 0 ? ($product->stock / $product->rop_value) * 100 : 0;
                    $barColor = $ratio <= 35 ? 'bg-red-500' : ($ratio <= 65 ? 'bg-amber-400' : 'bg-emerald-400');
                    $barBg = $ratio <= 35 ? 'bg-red-100' : ($ratio <= 65 ? 'bg-amber-100' : 'bg-emerald-100');
                    $suggested = ceil(max(0, ($product->rop_value + $product->ss_value) - $product->stock));
                @endphp
                <tr class="table-row-hover hover:bg-slate-50/40 transition-colors group/row" data-name="{{ strtolower($product->name) }}">
                    <td class="px-6 py-4">
                        <div class="font-bold text-slate-800">{{ $product->name }}</div>
                        <div class="text-[11px] text-slate-400 mt-0.5">
                            Kode: {{ $product->code ?? '#PRD-' . str_pad($product->id, 3, '0', STR_PAD_LEFT) }}
                            | SS: {{ round($product->ss_value) }} {{ $product->unit }}
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        {{-- Stok dalam satuan dasar --}}
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-sm font-bold bg-red-100 text-red-700">
                            {{ $product->stock }} {{ $product->unit }}
                        </span>
                        {{-- Jika ada satuan besar, tampilkan juga konversinya --}}
                        @if($product->large_unit && $product->unit_conversion > 1)
                            @php
                                $dus = intdiv($product->stock, $product->unit_conversion);
                                $sisa = $product->stock % $product->unit_conversion;
                            @endphp
                            <div class="text-[11px] text-slate-400 mt-1">
                                {{ $dus }} {{ $product->large_unit }}
                                @if($sisa > 0) & {{ $sisa }} {{ $product->unit }} @endif
                            </div>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        {{-- ROP dalam satuan dasar --}}
                        <span class="font-semibold text-slate-700">{{ round($product->rop_value) }} {{ $product->unit }}</span>
                        {{-- ROP dalam satuan besar jika ada --}}
                        @if($product->large_unit && $product->unit_conversion > 1)
                            <div class="text-[11px] text-slate-400 mt-0.5">
                                ≈ {{ number_format(round($product->rop_value) / $product->unit_conversion, 1) }} {{ $product->large_unit }}
                            </div>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center gap-2">
                            <span class="text-xs font-semibold text-slate-600 w-8 text-right">{{ round($ratio) }}%</span>
                            <div class="w-20 h-2 rounded-full {{ $barBg }} overflow-hidden">
                                <div class="h-full rounded-full {{ $barColor }} transition-all duration-500" style="width: {{ min($ratio, 100) }}%"></div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        {{-- Estimasi order dalam satuan dasar --}}
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-sm font-bold bg-amber-100 text-amber-700">
                            {{ $suggested }} {{ $product->unit }}
                        </span>
                        {{-- Estimasi order dalam satuan besar jika ada --}}
                        @if($product->large_unit && $product->unit_conversion > 1)
                            <div class="text-[11px] text-slate-400 mt-1">
                                ≈ {{ ceil($suggested / $product->unit_conversion) }} {{ $product->large_unit }}
                            </div>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-16 text-center">
                        <div class="flex flex-col items-center">
                            <div class="w-16 h-16 rounded-2xl bg-emerald-50 flex items-center justify-center mb-4">
                                <svg class="w-8 h-8 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <p class="text-sm font-semibold text-slate-700">Semua aman! 🎉</p>
                            <p class="text-xs text-slate-400 mt-1">Tidak ada stok produk kritis yang butuh restock.</p>
                        </div>
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
    // Alert dismissal with transitions
    function dismissAlert(id) {
        const el = document.getElementById(id);
        if (el) {
            el.style.opacity = '0';
            el.style.transform = 'translateY(-10px)';
            setTimeout(() => {
                el.style.display = 'none';
            }, 300);
        }
    }

    // Client-side search filtering
    function filterRestockTable() {
        const query = document.getElementById('table-search').value.toLowerCase();
        const rows = document.querySelectorAll('#restock-table tbody tr');
        
        rows.forEach(row => {
            const name = row.getAttribute('data-name');
            if (name) {
                row.style.display = name.includes(query) ? '' : 'none';
            }
        });
    }

    // Client-side simple table sort
    let sortDirections = [true, true, true, true, true]; // true for asc
    function sortRestockTable(colIndex) {
        const table = document.getElementById('restock-table');
        const tbody = table.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr'));
        
        if (rows.length <= 1 && rows[0].querySelector('td').colSpan) return; // Empty state
        
        const isAsc = sortDirections[colIndex];
        sortDirections[colIndex] = !isAsc;
        
        // Reset all sort indicators
        const indicators = table.querySelectorAll('.sort-icon');
        indicators.forEach(ind => ind.textContent = '⇅');
        
        // Update clicked header indicator
        const currentHeader = table.querySelectorAll('thead th')[colIndex];
        if (currentHeader && currentHeader.querySelector('.sort-icon')) {
            currentHeader.querySelector('.sort-icon').textContent = isAsc ? '▲' : '▼';
        }
        
        rows.sort((a, b) => {
            let valA, valB;
            if (colIndex === 0) {
                // Nama barang (kolom pertama, tanpa checkbox)
                valA = a.querySelector('td:nth-child(1) .font-bold').textContent.trim();
                valB = b.querySelector('td:nth-child(1) .font-bold').textContent.trim();
                return isAsc ? valA.localeCompare(valB) : valB.localeCompare(valA);
            } else {
                // Numbers (Stok atau ROP)
                valA = parseFloat(a.querySelector(`td:nth-child(${colIndex + 1})`).textContent.replace(/[^\d]/g, ''));
                valB = parseFloat(b.querySelector(`td:nth-child(${colIndex + 1})`).textContent.replace(/[^\d]/g, ''));
                return isAsc ? valA - valB : valB - valA;
            }
        });
        
        // Append sorted rows
        rows.forEach(row => tbody.appendChild(row));
    }
</script>
@endpush
