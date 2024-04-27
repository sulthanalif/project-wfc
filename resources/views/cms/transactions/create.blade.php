@extends('cms.layouts.app', [
    'title' => 'Order',
])

@section('content')
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Order
        </h2>
    </div>

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible show flex items-center mb-2" role="alert">
            <i data-lucide="alert-octagon" class="w-6 h-6 mr-2"></i> {{ session('error') }}
            <button type="button" class="btn-close text-white" data-tw-dismiss="alert" aria-label="Close"> <i data-lucide="x"
                    class="w-4 h-4"></i> </button>
        </div>
    @endif

    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12">
            <!-- BEGIN: Form Layout -->
            <div class="intro-y box p-5">
                <form action="{{ route('order.store') }}" method="post">
                    @csrf
                    <div>
                        <label for="order_number" class="form-label">No. Pesanan <span class="text-danger">*</span></label>
                        <input id="order_number" name="order_number" value="{{ $orderNumber }}" type="text" class="form-control w-full"
                            placeholder="Masukkan Nama Lengkap Sub Agent" readonly>
                    </div>
                    @hasrole('super_admin|admin')
                        <div class="mt-3">
                            <label for="agent_id" class="form-label">Dari Agent <span class="text-danger">*</span></label>
                            <select class="form-select mt-2 sm:mr-2" id="agent_id" name="agent_id" required>
                                <option value="">Pilih...</option>
                                @foreach ($agents as $agent)
                                    <option value="{{ $agent->id }}">{{ $agent->agentProfile->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endhasrole
                    @hasrole('agent')
                        <input type="text" name="agent_id" value="{{ auth()->user()->id }}">
                    @endhasrole

                    <div class="mt-3">
                        <label for="product_id" class="form-label">Pilih Product <span class="text-danger">*</span></label>
                        <select class="form-select mt-2 sm:mr-2" id="product_id" name="product_id" required>
                            <option value="">Pilih...</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}"
                                    data-nama="{{ $product->name }}"
                                    data-id="{{ $product->id }}"
                                    data-harga="{{ $product->price }}"
                                    >{{ $product->name . ' - ' . $product->price }}</option>
                            @endforeach
                        </select>
                        <button type="button" class="btn btn-primary" onclick="tambahItem()">Tambah</button>
                    </div>

                    <div class="row">
                        <div class="col-md-12 table-responsive">
                            <table class="table table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Jumlah</th>
                                        <th>Harga</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody class="transaksiItem">

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="2">Jumlah</th>
                                        <th class="qty">0</th>
                                        <th class="totalHarga">0</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <div class="text-left mt-5">
                        <input type="hidden" name="total_price" value="0">
                        <button type="submit" class="btn btn-primary w-24">Simpan</button>
                        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary w-24 mr-1">Kembali</a>
                    </div>
                </form>
            </div>
            <!-- END: Form Layout -->
        </div>
    </div>
@endsection

@push('custom-scripts')
    <script src="{{ asset('assets/cms/js/ckeditor-classic.js') }}"></script>

    <script>
        var totalHarga = 0;
        var qty = 0;
        var listItem = [];



        function tambahItem() {
            updateTotalHarga(parseInt($('#product_id').find(':selected').data('harga')))
        }

        function updateTotalHarga(nom) {
            totalHarga += nom;
            $('[name=total_price]').val(totalHarga)
            $('.totalHarga').html(formatRupiah(totalHarga.toString()))
        }

    </script>
@endpush
