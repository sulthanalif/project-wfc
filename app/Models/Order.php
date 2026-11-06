<?php

namespace App\Models;

use App\Helpers\TableFilter;
use App\Helpers\UUIDGenerator;
use App\Helpers\ValidateRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /** Filter tabel pesanan (index & arsip). Arsip hanya memilih periode yang tidak aktif. */
    public static function tableFilters(bool $archive = false): array
    {
        $filters = [
            'period' => [
                'label' => 'Periode',
                'type' => 'period',
                'options' => $archive ? TableFilter::periods(false) : null,
                'default' => $archive ? 'all' : null,
                'apply' => TableFilter::byOrderPeriod(),
            ],
            'status' => [
                'label' => 'Status',
                'type' => 'select',
                'options' => ['pending' => 'Pending', 'accepted' => 'Diterima', 'stop' => 'Mundur', 'reject' => 'Ditolak', 'canceled' => 'Dibatalkan'],
            ],
            'payment_status' => [
                'label' => 'Pembayaran',
                'type' => 'select',
                'options' => ['unpaid' => 'Belum Dibayar', 'pending' => 'Dicicil', 'paid' => 'Lunas'],
            ],
        ];

        if (!ValidateRole::check('agent')) {
            $filters['agent_id'] = [
                'label' => 'Agen',
                'type' => 'select',
                'options' => AgentProfile::orderBy('name')->pluck('name', 'user_id')->all(),
            ];
        }

        $filters['order_date'] = ['label' => 'Tanggal Pesanan', 'type' => 'date_range'];

        return $filters;
    }

    use HasFactory, UUIDGenerator;

    protected $table = 'orders';
    public $incrementing = false;
    protected $primaryKey = 'id';
    protected $keyType = 'string';

    protected $fillable = [
        'agent_id',
        'order_number',
        'total_price',
        'order_date',
        // 'access_date',
        'status',
        'payment_status',
        'delivery_status',
        'description',
    ];

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function detail()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function payment()
    {
        return $this->hasMany(Payment::class);
    }


    public function distributions()
    {
        return $this->hasMany(Distribution::class);
    }

    public function isAllItemDistributed()
    {
        return $this->detail->pluck('qty')->sum() === $this->distributions->pluck('detail')->flatten()->pluck('qty')->sum();
    }

    public function productReturnDetail()
    {
        return $this->hasMany(ProductReturnDetail::class);
    }

    public function recalculatePaymentStatus(): void
    {
        $payments = $this->payment()->get();
        $acceptedPay = $payments->where('status', 'accepted')->sum('pay');

        if ($this->total_price > 0 && $acceptedPay >= $this->total_price) {
            $this->payment_status = 'paid';
        } elseif ($payments->whereIn('status', ['accepted', 'pending'])->isNotEmpty()) {
            $this->payment_status = 'pending';
        } else {
            $this->payment_status = 'unpaid';
        }
        $this->save();
    }
}
