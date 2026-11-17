@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-8 rounded-xl shadow-md border border-slate-200">
    <div class="flex justify-between items-center mb-6 border-b border-slate-200 pb-4">
        <div>
            <span class="text-xs font-bold uppercase tracking-wider text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded">{{ $rekaman->kode_rekaman }}</span>
            <h2 class="text-2xl font-bold text-slate-900 mt-1">{{ $rekaman->nama_rekaman }}</h2>
        </div>
        <a href="{{ route('rekaman.index') }}" class="text-sm bg-slate-100 hover:bg-slate-200 text-slate-700 px-3 py-1.5 rounded-lg">&larr; Kembali</a>
    </div>

    <div class="space-y-4 text-sm">
        <div class="grid grid-cols-3 gap-2">
            <span class="font-medium text-slate-500">Habitat Ekosistem</span>
            <span class="col-span-2 text-slate-800 font-semibold">{{ $rekaman->habitat->nama_habitat ?? '-' }}</span>
        </div>
        <div class="grid grid-cols-3 gap-2">
            <span class="font-medium text-slate-500">Koordinat GPS</span>
            <span class="col-span-2 text-slate-800 font-mono">{{ $rekaman->titikPerekaman->koordinat_gps ?? '-' }}</span>
        </div>
        <div class="grid grid-cols-3 gap-2">
            <span class="font-medium text-slate-500">Lokasi Spesifik</span>
            <span class="col-span-2 text-slate-800">{{ $rekaman->titikPerekaman->lokasi_spesifik ?? '-' }}</span>
        </div>
        <div class="grid grid-cols-3 gap-2">
            <span class="font-medium text-slate-500">Elevasi (MDPL)</span>
            <span class="col-span-2 text-slate-800">{{ $rekaman->titikPerekaman->elevasi_mdpl ?? '-' }}</span>
        </div>
        <div class="grid grid-cols-3 gap-2">
            <span class="font-medium text-slate-500">Tanggal Perekaman</span>
            <span class="col-span-2 text-slate-800">{{ \Carbon\Carbon::parse($rekaman->tanggal_perekaman)->format('d F Y') }}</span>
        </div>
        <div class="grid grid-cols-3 gap-2">
            <span class="font-medium text-slate-500">Perekam Lapangan</span>
            <span class="col-span-2 text-slate-800">{{ $rekaman->perekam }}</span>
        </div>
    </div>

    <!-- Daftar Anotasi Suara (Many-to-Many Pivot) -->
    <div class="mt-8 border-t border-slate-200 pt-4">
        <h3 class="font-semibold text-slate-800 mb-3">Anotasi Suara Teridentifikasi:</h3>
        <div class="space-y-2">
            @forelse($rekaman->anotasis as $anotasi)
                <div class="p-3 bg-slate-50 rounded-lg border border-slate-200 flex justify-between items-center text-sm">
                    <div>
                        <span class="font-semibold text-emerald-800">{{ $anotasi->jenis_suara }}</span>
                        <p class="text-xs text-slate-500">{{ $anotasi->keterangan }}</p>
                    </div>
                    @if($anotasi->pivot->frekuensi_dominan)
                        <span class="text-xs bg-emerald-100 text-emerald-800 px-2 py-1 rounded font-mono">Freq: {{ $anotasi->pivot->frekuensi_dominan }}</span>
                    @endif
                </div>
            @empty
                <p class="text-sm text-slate-500 italic">Tidak ada anotasi suara yang tersemat pada rekaman ini.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
