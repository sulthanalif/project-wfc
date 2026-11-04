@props([
    'type' => 'order',
    'status' => 'pending',
    'text' => null,
])

@php
    $configs = [
        // Order & Generic Statuses
        'accepted' => ['bg' => 'bg-success/10 text-success border-success/20', 'icon' => 'check-circle', 'label' => 'Diterima'],
        'stop' => ['bg' => 'bg-primary/10 text-primary border-primary/20', 'icon' => 'pause-circle', 'label' => 'Mundur'],
        'reject' => ['bg' => 'bg-danger/10 text-danger border-danger/20', 'icon' => 'alert-circle', 'label' => 'Ditolak'],
        'canceled' => ['bg' => 'bg-slate-100 text-slate-500 border-slate-200', 'icon' => 'x-circle', 'label' => 'Dibatalkan'],
        'pending' => ['bg' => 'bg-warning/10 text-warning border-warning/20', 'icon' => 'clock', 'label' => 'Pending'],
        'processed' => ['bg' => 'bg-primary/10 text-primary border-primary/20', 'icon' => 'loader', 'label' => 'Diproses'],
        'finished' => ['bg' => 'bg-success/10 text-success border-success/20', 'icon' => 'check-circle', 'label' => 'Selesai'],

        // Payment Statuses
        'paid' => ['bg' => 'bg-success/10 text-success border-success/20', 'icon' => 'check-square', 'label' => 'Lunas'],
        'pending_payment' => ['bg' => 'bg-warning/10 text-warning border-warning/20', 'icon' => 'clock', 'label' => 'Dicicil'],
        'unpaid' => ['bg' => 'bg-danger/10 text-danger border-danger/20', 'icon' => 'x-square', 'label' => 'Belum Dibayar'],

        // Delivery Statuses
        'delivered' => ['bg' => 'bg-success/10 text-success border-success/20', 'icon' => 'truck', 'label' => 'Sukses'],
        'shipping' => ['bg' => 'bg-warning/10 text-warning border-warning/20', 'icon' => 'clock', 'label' => 'Sedang Proses'],
        'undelivered' => ['bg' => 'bg-slate-100 text-slate-500 border-slate-200', 'icon' => 'package', 'label' => 'Belum Dikirim'],
    ];

    $cfg = $configs[$status] ?? ['bg' => 'bg-slate-100 text-slate-600 border-slate-200', 'icon' => 'info', 'label' => ucfirst($status)];
    $label = $text ?? $cfg['label'];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium border ' . $cfg['bg']]) }}>
    <i data-lucide="{{ $cfg['icon'] }}" class="w-3.5 h-3.5 mr-1.5"></i>
    <span>{{ $label }}</span>
</span>
