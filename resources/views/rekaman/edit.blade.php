@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-8 rounded-xl shadow-md border border-slate-200">
    <h2 class="text-2xl font-bold text-slate-900 mb-6">Edit Metadata Rekaman</h2>

    @if ($errors->any())
        <div class="mb-4 p-4 bg-rose-50 border-l-4 border-rose-500 text-rose-700 text-sm rounded">
            <ul class="list-disc pl-4 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('rekaman.update', $rekaman->id) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Kode Rekaman</label>
            <input type="text" name="kode_rekaman" value="{{ old('kode_rekaman', $rekaman->kode_rekaman) }}" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 bg-white">
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Nama Rekaman / Subjek</label>
            <input type="text" name="nama_rekaman" value="{{ old('nama_rekaman', $rekaman->nama_rekaman) }}" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 bg-white">
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Habitat Ekosistem</label>
            <select name="habitat_id" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 bg-white">
                <option value="">-- Pilih Habitat --</option>
                @foreach($habitats as $habitat)
                    <option value="{{ $habitat->id }}" @selected(old('habitat_id', $rekaman->habitat_id) == $habitat->id)>{{ $habitat->nama_habitat }}</option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Koordinat GPS</label>
                <input type="text" name="koordinat_gps" value="{{ old('koordinat_gps', optional($rekaman->titikPerekaman)->koordinat_gps) }}" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 bg-white">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Elevasi (MDPL)</label>
                <input type="number" step="0.01" name="elevasi_mdpl" value="{{ old('elevasi_mdpl', optional($rekaman->titikPerekaman)->elevasi_mdpl) }}" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 bg-white">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Lokasi Spesifik</label>
            <input type="text" name="lokasi_spesifik" value="{{ old('lokasi_spesifik', optional($rekaman->titikPerekaman)->lokasi_spesifik) }}" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 bg-white">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Tanggal Perekaman</label>
                <input type="date" name="tanggal_perekaman" value="{{ old('tanggal_perekaman', optional($rekaman->tanggal_perekaman)->format('Y-m-d')) }}" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 bg-white">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Nama Perekam</label>
                <input type="text" name="perekam" value="{{ old('perekam', $rekaman->perekam) }}" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 bg-white">
            </div>
        </div>

        <!-- Anotasi Suara (Many-to-Many Pivot Edit) -->
        <div class="border-t border-slate-200 pt-4 mt-4">
            <label class="block text-sm font-semibold text-slate-800 mb-2">Anotasi Suara Ditemukan</label>
            <div class="space-y-2 bg-slate-50 p-4 rounded-lg border border-slate-200">
                @foreach($anotasis as $anotasi)
                    @php
                        $pivotRow = $rekaman->anotasis->where('id', $anotasi->id)->first();
                        $isChecked = in_array($anotasi->id, old('anotasi_ids', $selectedAnotasis));
                        $oldFreq = old('frekuensi_dominan.' . $anotasi->id, optional($pivotRow)->pivot->frekuensi_dominan ?? '');
                    @endphp
                    <div class="flex items-center justify-between">
                        <label class="flex items-center space-x-2 text-sm text-slate-700 cursor-pointer">
                            <input type="checkbox" name="anotasi_ids[]" value="{{ $anotasi->id }}" @checked($isChecked) class="rounded text-emerald-600 focus:ring-emerald-500">
                            <span>{{ $anotasi->jenis_suara }}</span>
                        </label>
                        <input type="text" name="frekuensi_dominan[{{ $anotasi->id }}]" value="{{ $oldFreq }}" placeholder="Frekuensi Dominan" class="text-xs px-2 py-1 border border-slate-300 rounded bg-white w-48">
                    </div>
                @endforeach
            </div>
        </div>

        <div class="flex justify-end space-x-3 pt-4">
            <a href="{{ route('rekaman.index') }}" class="bg-slate-200 hover:bg-slate-300 text-slate-700 px-4 py-2 rounded-lg font-medium">Batal</a>
            <button type="submit" class="bg-amber-600 hover:bg-amber-700 text-white px-5 py-2 rounded-lg font-medium shadow">Perbarui Data</button>
        </div>
    </form>
</div>
@endsection
