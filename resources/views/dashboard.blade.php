@extends('layouts.app')

@section('title', 'Dashboard - Laboratorium Bioakustik')

@section('content')
<div class="mb-8">
    <p class="text-sm font-semibold uppercase tracking-wider text-emerald-700">Ringkasan Laboratorium</p>
    <h2 class="mt-1 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">Dashboard</h2>
    <p class="mt-2 text-sm text-slate-600">Pantau data utama pemantauan bioakustik dalam satu tempat.</p>
</div>

<section aria-label="Ringkasan data" class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
    @foreach ($statistics as $statistic)
        <a href="{{ route($statistic['route']) }}" class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-emerald-300 hover:shadow-md">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-sm font-medium text-slate-500">{{ $statistic['label'] }}</p>
                    <p class="mt-3 text-3xl font-bold tracking-tight text-slate-900">{{ $statistic['count'] }}</p>
                </div>
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700 transition group-hover:bg-emerald-100" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 12h14M12 5l7 7-7 7" />
                    </svg>
                </span>
            </div>
            <p class="mt-4 text-xs font-semibold text-emerald-700">Lihat data <span aria-hidden="true">&rarr;</span></p>
        </a>
    @endforeach
</section>

<section class="mt-8 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
    <div class="flex flex-col gap-3 border-b border-slate-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
        <div>
            <h3 class="font-semibold text-slate-900">Rekaman Terbaru</h3>
            <p class="mt-1 text-sm text-slate-500">Lima data rekaman terakhir yang ditambahkan.</p>
        </div>
        <a href="{{ route('rekaman.index') }}" class="text-sm font-semibold text-emerald-700 hover:text-emerald-900">Lihat semua</a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full min-w-[600px] text-left text-sm">
            <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                <tr>
                    <th scope="col" class="px-6 py-3 font-semibold">Kode &amp; Nama</th>
                    <th scope="col" class="px-6 py-3 font-semibold">Habitat</th>
                    <th scope="col" class="px-6 py-3 font-semibold">Tanggal</th>
                    <th scope="col" class="px-6 py-3 text-right font-semibold">Perekam</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($rekamansTerbaru as $rekaman)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4">
                            <a href="{{ route('rekaman.show', $rekaman) }}" class="font-semibold text-emerald-800 hover:underline">{{ $rekaman->kode_rekaman }}</a>
                            <span class="mt-1 block text-slate-600">{{ $rekaman->nama_rekaman }}</span>
                        </td>
                        <td class="px-6 py-4 text-slate-700">{{ $rekaman->habitat->nama_habitat ?? '-' }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $rekaman->tanggal_perekaman->format('d M Y') }}</td>
                        <td class="px-6 py-4 text-right text-slate-700">{{ $rekaman->perekam }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center">
                            <p class="font-medium text-slate-700">Belum ada data rekaman</p>
                            <p class="mt-1 text-sm text-slate-500">Tambahkan rekaman pertama untuk melihat ringkasan di sini.</p>
                            <a href="{{ route('rekaman.create') }}" class="mt-4 inline-flex rounded-lg bg-emerald-700 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-800">Tambah Rekaman</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
@endsection
