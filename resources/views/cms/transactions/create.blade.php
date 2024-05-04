@extends('cms.layouts.app', [
    'title' => 'Tambah Pesanan',
])

@section('content')
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Tambah Pesanan
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
                <form id="orderForm" amethod="post">
                    @csrf
                    <div>
                        <label for="order_number" class="form-label">No. Pesanan <span class="text-danger">*</span></label>
                        <input id="order_number" name="order_number" value="{{ $orderNumber }}" type="text"
                            class="form-control w-full" placeholder="Masukkan Nama Lengkap Sub Agent" readonly>
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
                        <label for="package_id" class="form-label">Pilih Paket <span class="text-danger">*</span></label>
                        <select class="form-select mt-2 sm:mr-2" id="package_id" name="package_id" required>
                            <option>Pilih...</option>
                            @foreach ($packages as $package)
                                <option value="{{ $package->id }}">{{ $package->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mt-3" id="product_fields" style="display: none;">
                        <label for="product_id_item" class="form-label">Pilih Item <span
                                class="text-danger">*</span></label>
                        <select class="form-select mt-2 sm:mr-2" id="product_id_item" name="product_id_item" required>
                        </select>
                        <button type="button" class="btn btn-primary mt-2" onclick="addItem()">Tambah</button>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12 table-responsive">
                            <table class="table table-hover table-bordered">
                                <thead>
                                    <tr class="text-center">
                                        <th>Nama</th>
                                        <th>Harga</th>
                                        <th>Jumlah</th>
                                        <th>Sub Total</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody class="transaksiItem">

                                </tbody>
                                <tfoot>
                                    <tr class="text-center">
                                        <th colspan="2">TOTAL</th>
                                        <th class="qty">0</th>
                                        <th class="totalHarga">0</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <div class="text-left mt-5">
                        <input type="hidden" name="total_price" value="0">
                        <button type="submit" class="btn btn-primary w-24" onclick="simpan(event)">Simpan</button>
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
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script>
        // Constants
        const INITIAL_PRICE = 0;
        const INITIAL_QUANTITY = 0;

        // Global variables (reduced usage)
        let totalHarga = INITIAL_PRICE;
        let qty = INITIAL_QUANTITY;
        let productSelect;

        // Event listeners
        document.addEventListener('DOMContentLoaded', () => {
            productSelect = document.getElementById('package_id');
            productSelect.addEventListener('change', handlePackageChange);
        });

        // Functions
        function handlePackageChange(event) {
            const packageId = event.target.value;
            const productFields = document.getElementById('product_fields');

            if (packageId) {
                productFields.style.display = 'block';
                populateProducts(packageId);
            } else {
                productFields.style.display = 'none';
                clearProductSelection();
            }
        }

        function populateProducts(packageId) {
            productSelect = document.getElementById('product_id_item');
            productSelect.innerHTML = '<option>Pilih...</option>';

            @foreach ($packages as $package)
                if ('{{ $package->id }}' == packageId) {
                    @foreach ($package->product as $product)
                        var option = document.createElement('option');
                        option.value = '{{ $product->product->id }}';
                        option.textContent =
                            '{{ $product->product->name }} - Rp. {{ number_format($product->product->price, 0, ',', '.') }}';
                        option.dataset.harga = '{{ $product->product->price }}';
                        productSelect.appendChild(option);
                    @endforeach
                }
            @endforeach

            // const packages = @json($packages);
            // packages.forEach(package => {
            //     if (package.id === packageId) {
            //         package.product.forEach(product => {
            //             console.log(product.product_id);
            //             const option = document.createElement('option');
            //             option.value = product.id;
            //             option.textContent = `${product.name} - Rp. ${formatRupiah(product.price)}`;
            //             option.dataset.harga = product.price;
            //             productSelect.appendChild(option);
            //         });
            //     }
            // });
        }

        function addItem() {
            const selectedOption = productSelect.selectedOptions[0];

            if (!selectedOption) {
                alert('Silahkan pilih item terlebih dahulu!');
                return;
            }

            const itemId = selectedOption.value;
            const itemName = selectedOption.textContent.trim().split(' - ')[0];
            const itemPrice = parseInt(selectedOption.dataset.harga);
            const itemQuantity = 1;

            const existingRow = $('.transaksiItem td:nth-child(2)').filter(function() {
                return $(this).text() === itemName;
            }).closest('tr');

            if (existingRow.length > 0) {
                const input = existingRow.find('.quantityInput');
                const newQuantity = parseInt(input.val()) + 1;
                input.val(newQuantity);
                const priceChange = itemPrice * 1;
                updateQty(input[0], priceChange);
            } else {
                const newRow = createTableRow(itemId, itemName, itemPrice, itemQuantity);
                $('.transaksiItem').append(newRow);

                updateTotals(itemPrice);
                qty += itemQuantity;
                $('.qty').html(qty.toString());
            }
        }

        function createTableRow(id, name, price, quantity) {
            const row = `<tr>
                <input value="${id}" id="product-id" name="product-id" type="hidden">
                <td>${name}</td>
                <td class="text-center">${price.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' })}</td>
                <td class="text-center"><input type="number" min="1" value="${quantity}" class="quantityInput" onchange="updateQty(this)" data-initial-value="${quantity}" id="product-qty"></td>     
                <td class="text-center">${price.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' })}</td>          
                <td class="text-center"><button type="button" class="btn btn-danger btn-sm removeItem" onclick="removeItem(this)">Hapus</button></td>
                </tr>`;

            return row;
        }

        function updateTotals(priceChange, quantityChange = 0) {
            totalHarga += priceChange;

            if (isNaN(totalHarga) || totalHarga < 0) {
                totalHarga = 0;
            }

            qty += quantityChange;

            $('.qty').html(qty.toString());
            $('[name=total_price]').val(totalHarga);
            $('.totalHarga').html(formatRupiah(totalHarga.toString()));
        }

        function updateQty(input, priceChange) {
            const newQuantity = parseInt($(input).val());
            const row = $(input).closest('tr');

            if (isNaN(newQuantity) || newQuantity <= 0) {
                alert('Jumlah item tidak valid!');
                $(input).val(1);
                return;
            }

            const initialQuantity = parseInt($(input).data('initialValue'));
            const itemPrice = parseInt(row.find('td:nth-child(3)').text().replace(/[^0-9,-]/g, ''));

            const quantityChange = newQuantity - initialQuantity;
            const newPrice = itemPrice * newQuantity;

            if (priceChange == null) {
                const oldPrice = itemPrice * initialQuantity;
                priceChange = newPrice - oldPrice;
            }

            row.find('.quantityInput').val(newQuantity);

            row.find('td:nth-child(5)').text(newPrice.toLocaleString('id-ID', {
                style: 'currency',
                currency: 'IDR'
            }));

            updateTotals(priceChange, quantityChange);
            $(input).data('initialValue', newQuantity);
        }

        function removeItem(button) {
            const row = $(button).closest('tr');
            const itemPrice = parseInt(row.find('td:nth-child(3)').text().replace(/[^0-9,-]/g, ''));
            const quantity = parseInt(row.find('.quantityInput').val());

            const totalPrice = itemPrice * quantity;
            const priceChange = -totalPrice;

            updateTotals(-totalPrice);
            qty -= quantity;
            if (qty < 0) {
                qty = 0;
            }
            $('.qty').html(qty.toString());
            row.remove();
        }

        // Helper functions (optional)
        function clearProductSelection() {
            productSelect.selectedIndex = 0;
        }

        function formatRupiah(number) {
            const formatter = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR'
            });
            return formatter.format(number);
        }

        function simpan(event) {
            event.preventDefault();

            const formData = {
                _token: $('meta[name="csrf-token"]').attr('content'),
                order_number: $('#order_number').val(),
                agent_id: $('#agent_id').val(),
                products: [],
                total_price: totalHarga
            };

            $('.transaksiItem tr').each(function() {
                const productId = $(this).find('#product-id').val();
                const qty = $(this).find('#product-qty').val();

                formData.products.push({
                    productId: productId,
                    qty: qty,
                });
            });

            console.log(formData);

            // const csrfToken = $('meta[name="csrf-token"]').attr('content');
            // const data = {
            //     _token: csrfToken,
            //     ...formData
            // };

            $.ajax({
                url: "{{ route('order.store') }}",
                type: 'POST',
                data: formData,
                // data: JSON.stringify(formData),
                // contentType: 'application/json',
                // processData: false,
                // headers: {
                //     'X-CSRF-TOKEN': csrfToken
                // },
                success: function(response) {
                    // Handle response dari backend jika request berhasil
                    console.log('Data berhasil disimpan:', response);
                    window.location.href = "{{ route('order.index') }}";
                },
                error: function(xhr, status, error) {
                    // Handle error jika request gagal
                    console.error('Terjadi kesalahan:', error);
                }
            });
        }
    </script>
@endpush
