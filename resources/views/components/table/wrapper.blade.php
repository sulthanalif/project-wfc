@props([
    'collection' => null,
    'id' => null,
])

<div {{ $attributes->merge(['class' => 'table-container relative intro-y col-span-12 overflow-auto lg:overflow-visible']) }} @if($id) id="{{ $id }}" @endif>
    <table class="table table-report -mt-2">
        @if(isset($header))
            <thead>
                {{ $header }}
            </thead>
        @endif
        <tbody>
            {{ $slot }}
        </tbody>
    </table>
</div>
