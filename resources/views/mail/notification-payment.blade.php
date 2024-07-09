Pembayaran berhasil <br>
<br>
nomer pesanan : {{ $payment->order->order_number }}<br>
nomer invoice : {{ $payment->invoice_number }}<br>
tanggal : {{ \Carbon\Carbon::parse($payment->date)->format('d M Y') }}<br>
<br>
status pembayaran : {{ strtoupper($payment->order->payment_status) }} <br>
<br>
transaksi<br>
jumlah bayar : Rp. {{ number_format($payment->pay, 0, ',', '.') }}<br>
jumlah sisa pembayaran : Rp. {{ number_format($payment->remaining_payment, 0, ',', '.') }}<br>
metode pembayaran : {{ strtoupper($payment->method) }}<br>
<br>
note : {!!  $payment->note ?? '-'  !!}<br>
<br>
<br>
<a target="_blank" href="{{ route('getInvoice', ['order' => $payment->order, 'payment' => $payment]) }}">Download Invoice</a>
