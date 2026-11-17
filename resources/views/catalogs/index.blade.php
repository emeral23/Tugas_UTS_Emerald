@extends('layouts.app')

@section('content')
<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
    <div>
        <p class="text-sm font-semibold uppercase tracking-wider text-emerald-700">Data Referensi</p>
        <h2 class="mt-1 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">{{ $title }}</h2>
        <p class="mt-2 text-sm text-slate-600">{{ $description }}</p>
    </div>
    <a href="{{ route($createRoute) }}" class="inline-flex shrink-0 items-center justify-center gap-2 rounded-lg bg-emerald-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:ring-offset-2">
        <span aria-hidden="true" class="text-lg leading-none">+</span>
        Tambah Data
    </a>
</div>

<section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
    <div class="flex flex-col gap-4 border-b border-slate-200 p-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
        <p class="text-sm text-slate-500">
            Total <span class="font-semibold text-slate-800">{{ $items->total() }}</span> data
        </p>
        <form action="{{ route($indexRoute) }}" method="GET" class="flex gap-2">
            <label for="catalog-search" class="sr-only">Cari {{ $title }}</label>
            <input id="catalog-search" type="search" name="q" value="{{ request('q') }}" placeholder="Cari data..." class="min-w-0 flex-1 rounded-lg border border-slate-300 px-3 py-2 text-sm placeholder:text-slate-400 focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-600/20 sm:w-64 sm:flex-none">
            <button type="submit" class="rounded-lg bg-slate-800 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-900">Cari</button>
            @if (request('q'))
                <a href="{{ route($indexRoute) }}" class="rounded-lg border border-slate-300 px-3 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50">Reset</a>
            @endif
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full min-w-[640px] text-left text-sm">
            <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                <tr>
                    @foreach ($columns as $column)
                        <th scope="col" class="px-5 py-3.5 font-semibold">{{ $column['label'] }}</th>
                    @endforeach
                    <th scope="col" class="px-5 py-3.5 text-right font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($items as $item)
                    <tr class="transition hover:bg-emerald-50/40">
                        @foreach ($columns as $column)
                            @php($value = data_get($item, $column['key']))
                            <td class="max-w-xs px-5 py-4 text-slate-700">
                                <span class="block truncate" title="{{ $value ?? '-' }}">{{ $value ?? '-' }}</span>
                            </td>
                        @endforeach
                        <td class="whitespace-nowrap px-5 py-4">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route($editRoute, $item) }}" class="rounded-md px-2.5 py-1.5 font-medium text-amber-700 transition hover:bg-amber-50 hover:text-amber-900">Edit</a>
                                <form action="{{ route($destroyRoute, $item) }}" method="POST" onsubmit="return confirm('Hapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded-md px-2.5 py-1.5 font-medium text-rose-700 transition hover:bg-rose-50 hover:text-rose-900">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ count($columns) + 1 }}" class="px-6 py-14 text-center">
                            <p class="font-medium text-slate-700">{{ $emptyMessage }}</p>
                            @if (request('q'))
                                <p class="mt-1 text-sm text-slate-500">Coba gunakan kata kunci pencarian lain.</p>
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="border-t border-slate-200 px-4 py-4 sm:px-6">
        {{ $items->links() }}
    </div>
</section>
@endsection
