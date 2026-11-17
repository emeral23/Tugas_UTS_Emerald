@extends('layouts.app')

@section('content')
<section class="mb-8 overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-900 via-emerald-800 to-teal-700 shadow-lg">
    <div class="flex flex-col gap-6 p-6 sm:p-8 md:flex-row md:items-center md:justify-between">
        <div class="max-w-2xl">
            <span class="mb-3 inline-flex items-center rounded-full bg-white/10 px-3 py-1 text-xs font-semibold uppercase tracking-wider text-emerald-100 ring-1 ring-inset ring-white/20">
                Laboratorium Bioakustik
            </span>
            <h2 class="text-2xl font-bold tracking-tight text-white sm:text-3xl">Metadata Rekaman Suara Lapangan</h2>
            <p class="mt-2 text-sm leading-6 text-emerald-100 sm:text-base">
                Kelola dan telusuri data pemantauan bioakustik ekosistem.
            </p>
        </div>
        <a href="{{ route('rekaman.create') }}" class="inline-flex shrink-0 items-center justify-center gap-2 rounded-lg bg-white px-5 py-3 text-sm font-semibold text-emerald-900 shadow-sm transition hover:bg-emerald-50 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-emerald-800">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path d="M10 4a1 1 0 011 1v4h4a1 1 0 110 2h-4v4a1 1 0 11-2 0v-4H5a1 1 0 110-2h4V5a1 1 0 011-1z" />
            </svg>
            Tambah Rekaman
        </a>
    </div>
</section>

<section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
    <div class="border-b border-slate-200 p-4 sm:p-6">
        <div class="mb-4 flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h3 class="text-lg font-semibold text-slate-900">Daftar Rekaman</h3>
                <p class="mt-1 text-sm text-slate-500">Cari berdasarkan kode, nama rekaman, atau perekam.</p>
            </div>
            @if (method_exists($rekamans, 'total'))
                <p class="text-sm text-slate-500">
                    Total <span class="font-semibold text-slate-700">{{ $rekamans->total() }}</span> data
                </p>
            @endif
        </div>

        <form action="{{ route('rekaman.index') }}" method="GET" class="flex flex-col gap-3 sm:flex-row">
            <label for="search-rekaman" class="sr-only">Cari rekaman</label>
            <div class="relative flex-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="pointer-events-none absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 103.438 9.793l3.634 3.635a.75.75 0 101.06-1.061l-3.634-3.634A5.5 5.5 0 009 3.5zM5 9a4 4 0 118 0 4 4 0 01-8 0z" clip-rule="evenodd" />
                </svg>
                <input id="search-rekaman" type="text" name="q" value="{{ request('q') }}" placeholder="Cari kode, nama rekaman, atau perekam..." class="w-full rounded-lg border border-slate-300 bg-white py-2.5 pl-10 pr-4 text-sm text-slate-900 placeholder:text-slate-400 transition focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-600/20">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="inline-flex flex-1 items-center justify-center rounded-lg bg-emerald-700 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:ring-offset-2 sm:flex-none">
                    Cari
                </button>
                @if (request('q'))
                    <a href="{{ route('rekaman.index') }}" class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full min-w-[850px] text-left text-sm">
            <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                <tr>
                    <th scope="col" class="px-6 py-4 font-semibold">Kode &amp; Nama</th>
                    <th scope="col" class="px-6 py-4 font-semibold">Habitat</th>
                    <th scope="col" class="px-6 py-4 font-semibold">Lokasi / Koordinat</th>
                    <th scope="col" class="px-6 py-4 font-semibold">Perekam &amp; Tanggal</th>
                    <th scope="col" class="px-6 py-4 text-right font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($rekamans as $rekaman)
                    <tr class="transition hover:bg-emerald-50/40">
                        <td class="px-6 py-4">
                            <span class="block font-semibold text-emerald-800">{{ $rekaman->kode_rekaman }}</span>
                            <span class="mt-1 block text-slate-600">{{ $rekaman->nama_rekaman }}</span>
                        </td>
                        <td class="px-6 py-4 text-slate-700">{{ $rekaman->habitat->nama_habitat ?? '-' }}</td>
                        <td class="px-6 py-4">
                            <span class="block font-medium text-slate-800">{{ $rekaman->titikPerekaman->lokasi_spesifik ?? '-' }}</span>
                            <span class="mt-1 block font-mono text-xs text-slate-500">GPS: {{ $rekaman->titikPerekaman->koordinat_gps ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="block text-slate-800">{{ $rekaman->perekam }}</span>
                            <span class="mt-1 block text-xs text-slate-500">{{ \Carbon\Carbon::parse($rekaman->tanggal_perekaman)->format('d M Y') }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('rekaman.show', $rekaman->id) }}" class="rounded-md px-2.5 py-1.5 font-medium text-sky-700 transition hover:bg-sky-50 hover:text-sky-900">Detail</a>
                                <a href="{{ route('rekaman.edit', $rekaman->id) }}" class="rounded-md px-2.5 py-1.5 font-medium text-amber-700 transition hover:bg-amber-50 hover:text-amber-900">Edit</a>
                                <form action="{{ route('rekaman.destroy', $rekaman->id) }}" method="POST" onsubmit="return confirm('Hapus data rekaman ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded-md px-2.5 py-1.5 font-medium text-rose-700 transition hover:bg-rose-50 hover:text-rose-900">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center">
                            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 9l10.5-3 3 10.5-10.5 3L9 9zM9 9L5 5m7 7l-4 4m7-7l4 4" />
                                </svg>
                            </div>
                            <p class="mt-4 font-medium text-slate-700">Belum ada data rekaman</p>
                            <p class="mt-1 text-sm text-slate-500">Tambahkan metadata rekaman untuk mulai mengelola data bioakustik.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if (method_exists($rekamans, 'links'))
        <div class="border-t border-slate-200 px-4 py-4 sm:px-6">
            {{ $rekamans->links() }}
        </div>
    @endif
</section>
@endsection
