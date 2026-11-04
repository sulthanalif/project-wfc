@props([
    'colspan' => 10,
    'message' => 'Belum Ada Data',
    'icon' => 'inbox',
])

<tr>
    <td colspan="{{ $colspan }}" class="text-center py-8 text-slate-500">
        <div class="flex flex-col items-center justify-center">
            <i data-lucide="{{ $icon }}" class="w-10 h-10 text-slate-300 mb-2"></i>
            <span class="font-medium text-base">{{ $slot->isNotEmpty() ? $slot : $message }}</span>
        </div>
    </td>
</tr>
