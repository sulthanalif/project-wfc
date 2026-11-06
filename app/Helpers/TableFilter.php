<?php

namespace App\Helpers;

use App\Models\Period;
use Closure;
use Illuminate\Http\Request;

/**
 * Filter tabel dari query string. Controller mendaftarkan filter, helper ini menerapkannya ke query
 * dan mengembalikan definisi yang sama (plus nilai aktif) untuk komponen <x-table.filter>.
 *
 * Definisi per key:
 *   'label'   => 'Status'
 *   'type'    => 'select' | 'date_range' | 'period'
 *   'options' => [value => label]           select (period: otomatis dari tabel periods)
 *   'column'  => 'status'                   kolom untuk where bawaan, default = key
 *   'apply'   => fn ($query, $value) => ... menggantikan where bawaan (wajib untuk period)
 *   'default' => nilai bila parameter tidak ada (period: periode aktif)
 */
class TableFilter
{
    public static function apply($query, Request $request, array $defs): array
    {
        foreach ($defs as $key => &$def) {
            $def['key'] = $key;
            $column = $def['column'] ?? $key;

            if ($def['type'] === 'date_range') {
                $def['from'] = self::date($request->query($key . '_from'));
                $def['to'] = self::date($request->query($key . '_to'));
                if ($def['from']) $query->whereDate($column, '>=', $def['from']);
                if ($def['to']) $query->whereDate($column, '<=', $def['to']);
                continue;
            }

            if ($def['type'] === 'period') {
                $def['options'] ??= Period::orderByDesc('start_date')->pluck('description', 'id')->all();
                $def['default'] ??= self::defaultPeriodId($def['options']);
            }

            $value = $request->query($key, $def['default'] ?? 'all');
            $value = is_string($value) || is_int($value) ? (string) $value : 'all';
            // Nilai di luar daftar opsi kembali ke default, jadi URL yang diubah manual tidak bisa memfilter sembarang nilai.
            if ($value !== 'all' && !array_key_exists($value, $def['options'])) {
                $value = (string) ($def['default'] ?? 'all');
            }
            $def['value'] = $value;

            if ($value === 'all') continue;

            isset($def['apply']) ? $def['apply']($query, $value) : $query->where($column, $value);
        }

        return $defs;
    }

    /** Periode berdasarkan rentang tanggal periode, untuk tabel yang tidak terhubung ke paket (keuangan, dll). */
    public static function byPeriodDates(string $column): Closure
    {
        return function ($query, $periodId) use ($column) {
            $period = Period::find($periodId);
            if ($period) {
                $query->whereDate($column, '>=', $period->start_date)->whereDate($column, '<=', $period->end_date);
            }
        };
    }

    /** Periode lewat paket produk pesanan. $orderRelation diisi bila query bukan Order (mis. 'order' untuk Payment). */
    public static function byOrderPeriod(?string $orderRelation = null): Closure
    {
        $path = ($orderRelation ? $orderRelation . '.' : '') . 'detail.product.package.package';

        return fn ($query, $periodId) => $query->whereHas($path, fn ($q) => $q->where('period_id', $periodId));
    }

    /** Hanya opsi periode tertentu (mis. arsip: periode tidak aktif). */
    public static function periods(bool $active): array
    {
        return Period::where('is_active', $active ? 1 : 0)->orderByDesc('start_date')->pluck('description', 'id')->all();
    }

    private static function defaultPeriodId(array $options): string
    {
        $active = Period::where('is_active', 1)->whereIn('id', array_keys($options))->value('id');

        return (string) ($active ?? array_key_first($options) ?? 'all');
    }

    private static function date($value): ?string
    {
        return is_string($value) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $value) ? $value : null;
    }
}
