@props([
    'filters' => [],
])

@php
    // Parameter selain filter (search, perPage, dll) ikut dikirim lagi supaya tidak hilang saat filter diganti.
    $filterKeys = collect($filters)->flatMap(fn ($f) => $f['type'] === 'date_range' ? [$f['key'] . '_from', $f['key'] . '_to'] : [$f['key']])->all();
    $keep = collect(request()->query())->except(array_merge($filterKeys, ['page', 'export']))->filter(fn ($v) => is_scalar($v));
    $hasDate = collect($filters)->contains(fn ($f) => $f['type'] === 'date_range');
    $changed = collect($filters)->contains(fn ($f) => $f['type'] === 'date_range'
        ? ($f['from'] || $f['to'])
        : (string) $f['value'] !== (string) ($f['default'] ?? 'all'));
@endphp

@if (count($filters))
    <form method="GET" action="{{ url()->current() }}" data-table-filter
        onsubmit="window.showTableLoading && window.showTableLoading()"
        {{ $attributes->merge(['class' => 'intro-y col-span-12 flex flex-wrap items-end gap-3']) }}>
        @foreach ($keep as $name => $val)
            <input type="hidden" name="{{ $name }}" value="{{ $val }}">
        @endforeach

        @foreach ($filters as $f)
            <div class="w-full sm:w-auto">
                <label for="tf-{{ $f['key'] }}" class="block text-xs text-slate-500 mb-1">{{ $f['label'] }}</label>
                @if ($f['type'] === 'date_range')
                    <div class="flex items-center gap-2">
                        <input id="tf-{{ $f['key'] }}" type="date" name="{{ $f['key'] }}_from" value="{{ $f['from'] }}"
                            class="form-control box w-full sm:w-40" aria-label="{{ $f['label'] }} dari">
                        <span class="text-slate-500 text-xs">s/d</span>
                        <input type="date" name="{{ $f['key'] }}_to" value="{{ $f['to'] }}"
                            class="form-control box w-full sm:w-40" aria-label="{{ $f['label'] }} sampai">
                    </div>
                @else
                    <select id="tf-{{ $f['key'] }}" name="{{ $f['key'] }}" class="form-select box w-full sm:w-40"
                        onchange="this.form.requestSubmit()">
                        <option value="all">Semua</option>
                        @foreach ($f['options'] as $val => $label)
                            <option value="{{ $val }}" @selected((string) $f['value'] === (string) $val)>{{ $label }}</option>
                        @endforeach
                    </select>
                @endif
            </div>
        @endforeach

        @if ($hasDate)
            <button type="submit" class="btn btn-primary">Terapkan</button>
        @endif
        @if ($changed)
            <a href="{{ url()->current() . ($keep->isNotEmpty() ? '?' . http_build_query($keep->all()) : '') }}"
                class="btn btn-outline-secondary">Reset filter</a>
        @endif
    </form>
@endif
