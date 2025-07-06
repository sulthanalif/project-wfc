<div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
    <table class="table table-report -mt-2">
        <thead>
            <tr>
                <th class="text-center whitespace-nowrap">#</th>
                <th class="text-center whitespace-nowrap">NAMA AGEN</th>
                <th class="text-center whitespace-nowrap">NOMER ORDER</th>
                <th class="text-center whitespace-nowrap">JUMLAH</th>
                {{-- <th class="text-center whitespace-nowrap">KASIR</th> --}}
                <th class="text-center whitespace-nowrap">WAKTU</th>
            </tr>
        </thead>
        <tbody>
            @if (!$payments)
                <tr>
                    <td colspan="5" class="font-medium whitespace-nowrap text-center">Belum Ada Data</td>
                </tr>
            @else
                @foreach ($payments as $payment)
                    <tr class="intro-x">
                        <td>
                            <p class="font-medium whitespace-nowrap text-center">{{ $loop->iteration }}</p>
                        </td>

                        <td>
                            <p class="text-center mr-3">
                                {{ $payment->order->agent->agentProfile->name }} </p>
                        </td>
                        <td>
                            <p class="text-slate-500 text-center mr-3"> {{ $payment->order->order_number }}
                            </p>
                        </td>
                        <td>
                            <p class="text-slate-500 flex text-center"> Rp.
                                {{ number_format($payment->pay, 0, ',', '.') }} </p>
                        </td>
                        <td>
                            <p class="text-slate-500 text-center items-center mr-3"> {{ $payment->created_at }}
                            </p>
                        </td>

                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>
