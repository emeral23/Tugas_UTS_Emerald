@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-3xl">
    <div class="mb-6">
        <a href="{{ route($indexRoute) }}" class="text-sm font-medium text-emerald-700 hover:text-emerald-900">&larr; Kembali ke daftar</a>
        <h2 class="mt-3 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">{{ $title }}</h2>
        <p class="mt-2 text-sm text-slate-600">{{ $description }}</p>
    </div>

    <form action="{{ route($actionRoute, $routeParameters) }}" method="POST" class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        @csrf
        @if ($httpMethod !== 'POST')
            @method($httpMethod)
        @endif

        <div class="space-y-5 p-5 sm:p-7">
            @if ($errors->any())
                <div role="alert" class="rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800">
                    <p class="font-semibold">Periksa kembali data yang diisi:</p>
                    <ul class="mt-2 list-inside list-disc space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @foreach ($fields as $field)
                <div>
                    <label for="{{ $field['name'] }}" class="mb-1.5 block text-sm font-semibold text-slate-700">
                        {{ $field['label'] }}
                        @if ($field['required'] ?? false)
                            <span class="text-rose-600" aria-hidden="true">*</span>
                        @endif
                    </label>

                    @if (($field['type'] ?? 'text') === 'textarea')
                        <textarea id="{{ $field['name'] }}" name="{{ $field['name'] }}" rows="{{ $field['rows'] ?? 4 }}" @if ($field['required'] ?? false) required @endif class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-600/20 @error($field['name']) border-rose-400 @enderror" placeholder="{{ $field['placeholder'] ?? '' }}">{{ old($field['name'], $field['value'] ?? '') }}</textarea>
                    @else
                        <input id="{{ $field['name'] }}" type="{{ $field['type'] ?? 'text' }}" name="{{ $field['name'] }}" value="{{ old($field['name'], $field['value'] ?? '') }}" @if ($field['required'] ?? false) required @endif @if (isset($field['maxLength'])) maxlength="{{ $field['maxLength'] }}" @endif @if (isset($field['step'])) step="{{ $field['step'] }}" @endif placeholder="{{ $field['placeholder'] ?? '' }}" class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-600/20 @error($field['name']) border-rose-400 @enderror">
                    @endif

                    @error($field['name'])
                        <p class="mt-1.5 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
            @endforeach
        </div>

        <div class="flex flex-col-reverse gap-3 border-t border-slate-200 bg-slate-50 px-5 py-4 sm:flex-row sm:justify-end sm:px-7">
            <a href="{{ route($indexRoute) }}" class="inline-flex justify-center rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">Batal</a>
            <button type="submit" class="inline-flex justify-center rounded-lg bg-emerald-700 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:ring-offset-2">{{ $submitLabel }}</button>
        </div>
    </form>
</div>
@endsection
