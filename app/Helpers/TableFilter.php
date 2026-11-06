<?php

namespace App\Helpers;

use App\Models\Period;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Filter tabel dari query string. Controller mendaftarkan filter, helper ini menerapkannya ke query
 * dan mengembalikan definisi yang sama (plus nilai aktif) untuk komponen <x-table.filter>.
 *
 * Definisi per key:
 *   'label'   => 'Status'
 *   'type'    => 'select' | 'date_range' | 'period'
 *   'options' => [value => label]           select (period: otomatis dari tabel periods)
 *   'column'  => 'status'                   kolom untuk where bawaan, default = key
 *   'apply'   => fn ($query, $value) => ... menggantikan where bawaan (wajib untuk period);
 *                null = hanya membaca nilai, controller menerapkannya sendiri (pakai $filters[key]['value'])
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

            if ($value === 'all' || (array_key_exists('apply', $def) && $def['apply'] === null)) continue;

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

    /**
     * Periode lewat paket produk pesanan: kolom $column (berisi id pesanan) harus ada di daftar pesanan periode itu.
     * $through diisi bila kolom itu ada di relasi, mis. byOrderPeriod('order_id', 'productReturnDetail').
     * Pakai subquery join sekali jalan; whereHas 4 tingkat dievaluasi per baris dan lambat di tabel besar.
     */
    public static function byOrderPeriod(string $column = 'id', ?string $through = null): Closure
    {
        return function ($query, $periodId) use ($column, $through) {
            $orderIds = self::orderIdsInPeriods([$periodId]);

            $through
                ? $query->whereHas($through, fn ($q) => $q->whereIn($column, $orderIds))
                : $query->whereIn($query->qualifyColumn($column), $orderIds);
        };
    }

    /** Subquery id pesanan yang punya produk dari paket di periode-periode ini. */
    public static function orderIdsInPeriods($periodIds)
    {
        return DB::table('order_details')
            ->join('product_packages', 'product_packages.product_id', '=', 'order_details.product_id')
            ->join('packages', 'packages.id', '=', 'product_packages.package_id')
            ->whereIn('packages.period_id', $periodIds)
            ->select('order_details.order_id');
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
