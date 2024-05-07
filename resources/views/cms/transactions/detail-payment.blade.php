@extends('cms.layouts.app', [
    'title' => 'Detail Patment',
])

@section('content')
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Detail Payment
        </h2>
    </div>

    <div class="intro-y box px-5 pt-5 mt-5">
        <div class="flex flex-col lg:flex-row border-b border-slate-200/60 dark:border-darkmode-400 pb-5 -mx-5">
            {{-- <div class="flex flex-1 px-5 items-center justify-center lg:justify-start">
                <img alt="PAKET SMART WFC" class=" img-fluid rounded-md"
                    src="{{ asset('storage/images/package/' . $package->image) }}">
            </div> --}}
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            <div
                class="mt-6 lg:mt-0 flex-1 px-5 border-l border-r border-slate-200/60 dark:border-darkmode-400 border-t lg:border-t-0 pt-5 lg:pt-0">
                <div class="text-slate-600 dark:text-slate-500">
                    <div class="flex flex-col items-center justify-center border-b pb-2">
                        <h1 class="font-bold text-xl">{{ $payment->order->order_number }}</h1>
                        <span class="text-muted">{{$payment->order->order_date}}</span>
                        <span class="text-muted">{{$payment->order->status}}</span>
                        <span class="text-muted">{{$payment->order->payment_status}}</span>
                    </div>
                    <div class="flex flex-col items-center justify-center border-b pb-2">
                        <h1 class="font-bold text-xl">Rincian Pembayaran</h1>
                        <span class="text-muted">Total Bayar : {{$payment->order->total_price}}</span>
                        <span class="text-muted">Bayar : {{$payment->pay}}</span>
                        <span class="text-muted">Sisa Pembayaran : {{$payment->remaining_payment}}</span>
                    </div>

                    <div class="mt-3">
                        @if ($payment->order->payment_status == 'unpaid')
                            <button type="button" class="btn btn-success" id="pay-button">Bayar</button>
                        @endif
                        <a href="{{ route('order.show', ['order' => $payment->order]) }}" class="btn btn-outline-secondary w-24 mr-1">Kembali</a>
                    </div>

                </div>
            </div>
        </div>
    </div>


@endsection

@push('custom-scripts')
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    <script src="{{ asset('assets/cms/js/ckeditor-classic.js') }}"></script>
    <script type="text/javascript">
        document.getElementById('pay-button').onclick = function(){
          // SnapToken acquired from previous step
          snap.pay('{{ $payment->snap_token }}', {
            // Optional
            onSuccess: function(result){
             /* Kirim permintaan POST ke route Laravel untuk memperbarui status pesanan */
            fetch('{{ route('successPayment', $payment) }}', {
                method: 'POST',
                headers: {
                'Content-Type': 'application/json',
                // Tambahkan token CSRF jika diperlukan
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                body: JSON.stringify(result)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                console.log('Status pesanan berhasil diperbarui menjadi paid!');
                alert('Pembayaran Berhasil!');
                // Reload page after a delay
                // Reload page immediately
                window.location.reload()
                } else {
                console.error('Error memperbarui status pesanan:', data.message);
                // Tampilkan pesan error
                }
            })
            .catch(error => {
                console.error('Error mengirim permintaan ke server:', error);
                // Tampilkan pesan error
            });

            /* Anda dapat tetap menampilkan JSON result di halaman */
            window.location.reload();
            },
            // Optional
            onPending: function(result){
              /* You may add your own js here, this is just example */ document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
            },
            // Optional
            onError: function(result){
              /* You may add your own js here, this is just example */ document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
            }
          });
        };
      </script>

@endpush

