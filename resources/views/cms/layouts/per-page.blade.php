@php
    $param = $param ?? 'perPage';
    $val = request()->get($param, 10);
@endphp
<div class="w-auto relative text-slate-500 mr-2">
    <select id="records_per_page" class="form-control box" data-param="{{ $param }}">
        <option value="10" {{ $val == 10 ? 'selected' : '' }}>10</option>
        <option value="25" {{ $val == 25 ? 'selected' : '' }}>25</option>
        <option value="50" {{ $val == 50 ? 'selected' : '' }}>50</option>
        <option value="all" {{ $val == 'all' ? 'selected' : '' }}>All</option>
    </select>
</div>
