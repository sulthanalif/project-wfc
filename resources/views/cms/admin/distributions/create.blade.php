@extends('cms.layouts.app', [
    'title' => 'Tambah Distribusi',
])

@section('content')
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Tambah Distribusi
        </h2>
    </div>

    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12">
            <!-- BEGIN: Form Layout -->
            <div class="intro-y box p-5">
                <form action="{{ route('distribution.store') }}" method="post" enctype="multipart/form-data"
                    id="distributionForm">
                    @csrf
                    <div class="grid grid-cols-12 gap-3">
                        <div class="col-span-12 lg:col-span-6">
                            <div>
                                <label for="distribution_number" class="form-label">Nomer Distribusi <span
                                        class="text-danger">*</span></label>
                                <input id="distribution_number" name="distribution_number" type="text"
                                    class="form-control w-full" value="{{ $distributionNumber }}" readonly>
                                @error('distribution_number')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mt-3">
                                <label for="date" class="form-label">Tanggal Distribusi <span
                                        class="text-danger">*</span></label>
                                <input id="date" name="date" type="date" class="form-control w-full" required>
                                @error('date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mt-3">
                                <label for="order_id" class="form-label">Pesanan <span class="text-danger">*</span></label>
                                <select class="tom-select" id="order_id" name="order_id">
                                    <option disabled selected>Pilih Pesanan...</option>
                                    @foreach ($datas as $order)
                                        @php
                                            $totalOrderQty = 0;
                                            $totalDistributionQty = 0;
                                        @endphp
                                        @foreach ($order->detail as $orderDetail)
                                            @php
                                                $totalOrderQty += $orderDetail->qty;
                                            @endphp
                                            @foreach ($orderDetail->distributionDetail as $distributionDetail)
                                                @php
                                                    $totalDistributionQty += $distributionDetail->qty;
                                                @endphp
                                            @endforeach
                                        @endforeach

                                        @if ($totalOrderQty != $totalDistributionQty)
                                            <option value="{{ $order->id }}">{{ $order->order_number }} -
                                                {{ $order->agent->agentProfile->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="mt-3" id="product_fields" style="display: none;">
                                <label for="product_id_item" class="form-label">Pilih Produk <span
                                        class="text-danger">*</span></label>
                                <select class="tom-select" id="product_id_item" name="product_id_item" required>
                                </select>
                                <button type="button" class="btn btn-primary mt-2" onclick="addItem()">Tambah</button>
                            </div>
                        </div>
                        <div class="col-span-12 lg:col-span-6 mt-3 lg:mt-0" >
                            <div>
                                <label for="method" class="form-label">Metode Distribusi <span class="text-danger">*</span></label>
                                <select class="tom-select" id="method" name="method" onchange="changeMethod()" required>
                                    <option value="">Pilih Metode</option>
                                    <option value="diantar">Diantar</option>
                                    <option value="dijemput">Dijemput</option>
                                </select>
                            </div>
                            <div class="mt-3" id="driver_fields" style="display: none;">
                                <label for="driver" class="form-label" id="driver-label">Nama Pengemudi <span
                                        class="text-danger">*</span></label>
                                <input id="driver" name="driver" type="text" class="form-control w-full" required>
                                @error('date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mt-3" id="police_number_fields" style="display: none;">
                                <label for="police_number" class="form-label">Nomer Polisi </label>
                                <input id="police_number" name="police_number" type="text" class="form-control w-full">
                                @error('police_number')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row mt-5">
                        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
                            <table class="table table-report">
                                <thead>
                                    <tr class="text-center">
                                        <th>Nama Sub Agent</th>
                                        <th>Nama Produk</th>
                                        <th>Jumlah</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody class="prodItem" id="product-item">

                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="text-left mt-5">
                        <input type="hidden" name="products" id="productData" value="">
                        <button type="submit" class="btn btn-primary w-24" onclick="simpan(event)">Simpan</button>
                        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary w-24 ml-1">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
        <!-- END: Form Layout -->
    </div>
@endsection

@push('custom-scripts')
    <script src="{{ asset('assets/cms/js/ckeditor-classic.js') }}"></script>
    <script>
        const currentDate = '{{ now()->format('Y-m-d') }}'; // Blade templating to get current date
        const dateInput = document.getElementById('date');

        dateInput.value = currentDate;

        document.addEventListener('DOMContentLoaded', () => {
            orderSelect = document.getElementById('order_id');
            orderSelect.addEventListener('change', handleOrderChange);
        });

        function changeMethod() {
            const selectedMethod = document.getElementById('method').value;
            const driverFields = document.getElementById('driver_fields');
            const policeNumberFields = document.getElementById('police_number_fields');
            const driverLabel = document.getElementById('driver-label');

            if (selectedMethod === 'diantar') {
                driverFields.style.display = 'block';
                policeNumberFields.style.display = 'block';
                driverLabel.innerHTML = `Nama Pengemudi<span
                                        class="text-danger">*</span>`;
            } else if (selectedMethod === 'dijemput') {
                driverFields.style.display = 'block';
                policeNumberFields.style.display = 'none';
                driverLabel.innerHTML = `Dijemput Oleh<span
                                        class="text-danger">*</span>`;
            } else {
            }
        }

        function handleOrderChange(event) {

            const orderId = event.target.value;
            const productFields = document.getElementById('product_fields');

            if (orderId) {
                productFields.style.display = 'block';
                populateProducts(orderId);
            } else {
                productFields.style.display = 'none';
                clearProductSelection();
            }
        }

        function populateProducts(orderId) {
            productSelect = document.getElementById('product_id_item');

            // Clear existing options first
            productSelect.tomselect.clear();
            productSelect.tomselect.clearOptions();

            // Add default disabled option
            productSelect.tomselect.addOption({
                value: '',
                text: 'Pilih Item...',
                disabled: true
            });

            @foreach ($datas as $order)
                if ('{{ $order->id }}' == orderId) {
                    @foreach ($order->detail as $products)
                        @php
                            $qty = 0;
                            foreach ($products->distributionDetail as $distributionDetail) {
                                $qty += $distributionDetail->qty;
                            }
                        @endphp

                        var option = {
                            value: '{{ $products->id }}',
                            text: "{{ $products->subAgent ? $products->subAgent->name : $order->agent->agentProfile->name }} - {{ $products->product->name }} {{ $products->product->is_safe_point == 1 ? '(Titik Aman)' : '' }} - {{ $products->qty - $qty }}",
                            qty: '{{ $products->qty - $qty }}',
                            disabled: {{ ($products->qty - $qty == 0) ? 'true' : 'false' }}
                        };

                        productSelect.tomselect.addOption(option);
                    @endforeach
                }
            @endforeach

            // Reset selection
            productSelect.tomselect.setValue('');
        }

        function addItem() {
            const selectedOption = productSelect.selectedOptions[0];

            const itemId = selectedOption.value;
            const itemSubAgent = selectedOption.textContent.trim().split(' - ')[0];
            const itemName = selectedOption.textContent.trim().split(' - ')[1];
            const qty = selectedOption.textContent.trim().split(' - ')[2].replace(/[^0-9]/g, '');
            const itemQuantity = parseInt(qty, 10);

            const existingRow = document.querySelector(`#product-item tr input[value="${itemId}"]`);
            if (!existingRow) {
                const newRow = createTableRow(itemId, itemSubAgent, itemName, itemQuantity);
                document.getElementById('product-item').appendChild(newRow);
            }
        }

        function createTableRow(id, subAgent, name, quantity) {
            const row = document.createElement('tr');
            row.innerHTML = `<tr>
                <input value="${id}" id="product-id" name="product-id" type="hidden">
                <td>${subAgent}</td>
                <td>${name}</td>
                <td class="text-center"><input type="number" min="1" value="${quantity}" class="quantityInput" onchange="updateQty(this)" data-initial-value="${quantity}" id="product-qty" max="${quantity}"></td>
                <td class="text-center"><button type="button" class="btn btn-danger btn-sm removeItem" onclick="removeItem(this)">Hapus</button></td>
                </tr>`;
            return row;
        }

        function removeItem(button) {
            const row = $(button).closest('tr');

            row.remove();
        }

        function clearProductSelection() {
            productSelect.selectedIndex = 0;
        }

        function simpan(event) {
            const productData = [];
            $('#product-item tr').each(function() {
                const productId = $(this).find('#product-id').val();
                const qty = $(this).find('#product-qty').val();

                productData.push({
                    productId: productId,
                    qty: qty
                });
            });

            $('#productData').val(JSON.stringify(productData));

            $('#distributionForm').submit();
        }
    </script>
@endpush
