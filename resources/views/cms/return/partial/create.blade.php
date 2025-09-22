@extends('cms.layouts.app', [
    'title' => 'Tambah Pengembalian',
])

@section('content')
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Tambah Pengembalian
        </h2>
    </div>

    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12">
            <!-- BEGIN: Form Layout -->
            <div class="intro-y box p-5">
                <form id="returnForm" action="{{ route('return.store') }}" method="post">
                    @csrf

                    <div class="grid grid-cols-12 gap-3 mt-3">
                        <div class="col-span-6">
                            <label for="return_number" class="form-label">No. Pengembalian <span
                                    class="text-danger">*</span></label>
                            <input id="return_number" name="return_number" value="{{ $returnNumber }}" type="text"
                                class="form-control w-full" readonly>
                        </div>
                        <div class="col-span-6">
                            <label for="return_date" class="form-label">Tanggal Pengembalian <span
                                    class="text-danger">*</span></label>
                            <input id="return_date" name="return_date" type="date" class="form-control w-full"
                                placeholder="Pilih Tanggal" required>
                        </div>
                    </div>

                    @hasrole('super_admin|admin')
                        <div class="grid grid-cols-12 gap-3 mt-3">
                            <div class="col-span-6">
                                <label for="agent_id" class="form-label">Dari Agent <span class="text-danger">*</span></label>
                                <select class="tom-select mt-2 sm:mr-2" id="agent_id" name="agent_id" required>
                                    <option value="">Pilih...</option>
                                    @foreach ($agents as $agent)
                                        <option value="{{ $agent->id }}">{{ $agent->agentProfile->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-span-6" id="order_fields" style="display: none;">
                                <label for="order_id_item" class="form-label">Nomor Pesanan <span
                                        class="text-danger">*</span></label>
                                <select class="tom-select mt-2 sm:mr-2" id="order_id_item" name="order_id_item" required>
                                </select>
                            </div>
                        </div>
                    @endhasrole
                    @hasrole('agent')
                        <input type="hidden" name="agent_id" id="agent_id" value="{{ auth()->user()->id }}">
                    @endhasrole

                    <div class="grid grid-cols-12 gap-3 mt-3">
                        <div class="col-span-6" id="product_fields" style="display: none;">
                            <label for="product_id_item" class="form-label">Pilih Paket <span
                                    class="text-danger">*</span></label>
                            <select class="tom-select mt-2" id="product_id_item" name="product_id_item" required>
                            </select>
                        </div>
                        <div class="col-span-6" id="sub_product_fields" style="display: none;">
                            <label for="sub_product_id_item" class="form-label">Pilih Produk <span
                                    class="text-danger">*</span></label>
                            <select class="tom-select mt-2" id="sub_product_id_item" name="sub_product_id_item" required>
                            </select>
                            <button type="button" class="btn btn-primary mt-2" onclick="addItem()">Tambah</button>
                        </div>
                    </div>

                    <div class="mt-3">
                        <label for="note" class="form-label">Keterangan <span class="text-danger">*</span></label>
                        <textarea id="note" name="note" class="editor"> </textarea>
                        @error('note')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="row mt-3">
                        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
                            <table class="table table-report">
                                <thead>
                                    <tr class="text-center">
                                        <th>Nomor Pesanan</th>
                                        <th>Paket</th>
                                        <th>Produk</th>
                                        <th>Jumlah</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody class="transaksiItem">

                                </tbody>
                                <tfoot>
                                    <tr class="text-center">
                                        <th colspan="3">TOTAL</th>
                                        <th class="totalQty">0</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <div class="text-left mt-5">
                        <input type="hidden" name="total_qty" id="total_qty" value="0">
                        <input type="hidden" name="products" id="productData" value="">
                        <button type="submit" class="btn btn-primary w-24" onclick="simpan(event)">Simpan</button>
                        <a href="{{ route('return.index') }}" class="btn btn-outline-secondary w-24 ml-1">Kembali</a>
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
        document.addEventListener('DOMContentLoaded', () => {
            const currentDate = '{{ now()->format('Y-m-d') }}';
            const dateInput = document.getElementById('return_date');

            if (dateInput) {
                dateInput.value = currentDate;
            }

            const agentSelect = document.getElementById('agent_id');
            const orderFields = document.getElementById('order_fields');
            const orderSelect = document.getElementById('order_id_item');
            const productFields = document.getElementById('product_fields');
            const productSelect = document.getElementById('product_id_item');
            const subProductFields = document.getElementById('sub_product_fields');
            const subProductSelect = document.getElementById('sub_product_id_item');
            const transaksiItemTable = document.querySelector('.transaksiItem');
            const totalQtyElement = document.querySelector('.totalQty');
            const productDataInput = document.getElementById('productData');

            const ordersData = [
                @foreach ($orders as $order)
                    {
                        id: '{{ $order->id }}',
                        agent_id: '{{ $order->agent_id }}',
                        order_number: '{{ $order->order_number }}'
                    },
                @endforeach
            ];

            const orderDetailsData = [
                @foreach ($orders as $order)
                    @foreach ($order->detail as $detail)
                        {
                            order_id: '{{ $order->id }}',
                            id: '{{ $detail->id }}',
                            product_id: '{{ $detail->product_id }}',
                            product_name: '{{ $detail->product->name }}',
                        },
                    @endforeach
                @endforeach
            ];

            const productsData = [
                @foreach ($products as $product)
                    @foreach ($product->subProduct as $subProduct)
                        {
                            product_id: '{{ $product->id }}',
                            sub_product_id: '{{ $subProduct->id }}',
                            sub_product_name: '{{ $subProduct->subProduct->name }}',
                        },
                    @endforeach
                @endforeach
            ];

            let addedItems = [];
            let totalQty = 0;

            function updateSummary() {
                totalQty = addedItems.reduce((sum, item) => sum + parseInt(item.quantity), 0);
                totalQtyElement.textContent = totalQty;
                document.getElementById('total_qty').value = totalQty;
                productDataInput.value = JSON.stringify(addedItems);
            }

            window.removeItem = (index) => {
                addedItems.splice(index, 1);
                renderItemsTable();
            }

            function renderItemsTable() {
                transaksiItemTable.innerHTML = '';
                addedItems.forEach((item, index) => {
                    const row = `
                        <tr class="text-center">
                            <td>
                                ${item.order_number}
                                <input type="hidden" name="item_order_number[]" value="${item.item_order_number}">
                            </td>
                            <td>
                                ${item.product_name}
                                <input type="hidden" name="item_product[]" value="${item.item_product}">
                            </td>
                            <td>
                                ${item.sub_product_name}
                                <input type="hidden" name="item_sub_product[]" value="${item.item_sub_product}">
                            </td>
                            <td>
                                <input type="number" class="form-control text-center" value="${item.quantity}" min="1" onchange="updateItemQty(${index}, this.value)">
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm" onclick="removeItem(${index})">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                    `;
                    transaksiItemTable.innerHTML += row;
                });
                updateSummary();
            }

            window.updateItemQty = (index, newQty) => {
                const item = addedItems[index];
                const validatedQty = Math.max(1, parseInt(newQty));
                item.quantity = validatedQty;
                renderItemsTable();
            }

            // Function to handle adding a new item to the table
            window.addItem = () => {
                const orderId = orderSelect.value;
                const subProductId = subProductSelect.value;
                if (!orderId || !subProductId) {
                    alert('Harap pilih Nomor Pesanan dan Produk terlebih dahulu.');
                    return;
                }

                const orderDetail = orderDetailsData.find(item =>
                    item.order_id === orderId
                );

                if (!orderDetail) {
                    alert('Data detail pesanan tidak ditemukan.');
                    return;
                }

                const product = productsData.find(p => p.sub_product_id === subProductId);
                const order = ordersData.find(o => o.id === orderId);

                if (!product || !order) {
                    alert('Data produk atau pesanan tidak ditemukan.');
                    return;
                }

                const existingItemIndex = addedItems.findIndex(item =>
                    item.item_sub_product === product.sub_product_id
                );

                if (existingItemIndex > -1) {
                    alert('Produk ini sudah ditambahkan.');
                    return;
                }

                addedItems.push({
                    order_number: order.order_number,
                    item_order_number: order.id,
                    product_name: orderDetail.product_name,
                    item_product: orderDetail.product_id,
                    sub_product_name: product.sub_product_name,
                    item_sub_product: product.sub_product_id,
                    quantity: 1,
                });
                renderItemsTable();
            };

            function populateOrders(agentId) {
                orderSelect.innerHTML = '<option value="">Pilih No Pesanan...</option>';

                ordersData.forEach(order => {
                    if (order.agent_id == agentId) {
                        const option = document.createElement('option');
                        option.value = order.id;
                        option.textContent = order.order_number;
                        orderSelect.tomselect.addOption(option);
                    }
                });
            }

            function populateProducts(orderId) {
                productSelect.innerHTML = '<option value="">Pilih Produk...</option>';
                if (orderId) {
                    const filteredProducts = orderDetailsData.filter(detail => detail.order_id === orderId);
                    filteredProducts.forEach(product => {
                        const option = document.createElement('option');
                        option.value = product.product_id;
                        option.textContent = `${product.product_name}`;
                        productSelect.tomselect.addOption(option);
                    });

                    productFields.style.display = 'block';
                } else {
                    productFields.style.display = 'none';
                }
            }

            function populateSubProducts(productId) {
                subProductSelect.innerHTML = '<option value="">Pilih Sub Produk...</option>';
                if (productId) {
                    const filteredSubProducts = productsData.filter(subProduct => subProduct.product_id ===
                        productId);
                    filteredSubProducts.forEach(subProduct => {
                        const option = document.createElement('option');
                        option.value = subProduct.sub_product_id;
                        option.textContent = `${subProduct.sub_product_name}`;
                        subProductSelect.tomselect.addOption(option);
                    });

                    subProductFields.style.display = 'block';
                } else {
                    subProductFields.style.display = 'none';
                }
            }

            if (agentSelect) {
                agentSelect.addEventListener('change', () => {
                    const agentId = agentSelect.value;
                    if (agentId) {
                        orderFields.style.display = 'block';
                        populateOrders(agentId);
                    } else {
                        orderFields.style.display = 'none';
                        orderSelect.tomselect.clear();
                    }
                });
            }

            if (orderSelect) {
                orderSelect.addEventListener('change', () => {
                    const orderId = orderSelect.value;
                    populateProducts(orderId);
                });
            }

            if (productSelect) {
                productSelect.addEventListener('change', () => {
                    const productId = productSelect.value;
                    populateSubProducts(productId);
                });
            }

            window.simpan = (event) => {
                event.preventDefault(); // Mencegah form dari submit secara default

                const addedItems = JSON.parse(document.getElementById('productData').value || '[]');

                if (addedItems.length === 0) {
                    alert('Harap tambahkan setidaknya satu produk untuk dikembalikan.');
                    return;
                }

                const form = document.getElementById('returnForm');
                form.submit();
            };
        });
    </script>
@endpush
