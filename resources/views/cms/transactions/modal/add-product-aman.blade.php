<div id="add-product-modal" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="form-label text-lg font-bold">Tambah Produk</h2>
            </div>
            <div class="modal-body p-0">
                <div class="p-5">
                    {{-- <i data-lucide="x-circle" class="w-16 h-16 text-danger mx-auto mt-3"></i> --}}
                    <form id="orderForm" action="{{ route('order.addItems', $order) }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="agent_id" id="agent_id" value="{{ auth()->user()->id }}">
                        <div>
                            <label for="package_id" class="form-label">Pilih Paket <span
                                    class="text-danger">*</span></label>
                            <select class="tom-select mt-2 sm:mr-2" id="package_id" name="package_id" required>
                                <option value="">Pilih...</option>
                                @foreach ($packages as $package)
                                    <option value="{{ $package->id }}">{{ $package->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mt-3" id="product_fields" style="display: none;">
                            <label for="product_id_item" class="form-label">Pilih Item <span
                                    class="text-danger">*</span></label>
                            <select class="tom-select mt-2 sm:mr-2" id="product_id_item" name="product_id_item"
                                required>
                            </select>
                            <button type="button" class="btn btn-primary mt-2" onclick="addItem()">Tambah</button>
                        </div>

                        <div class="row mt-3">
                            <div class="intro-y col-span-12 overflow-auto">
                                <table class="table table-report table-responsive">
                                    <thead>
                                        <tr class="text-center">
                                            <th>Produk</th>
                                            <th>Harga</th>
                                            <th>Jumlah</th>
                                            <th>Sub Total</th>
                                            <th>Sub Agent</th>
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
                        <div class="text-left px-5 mt-3">
                            <input type="hidden" name="total_price" value="0">
                            <input type="hidden" name="products" id="productData" value="">
                            <button type="submit" class="btn btn-primary w-24" onclick="simpan(event)">Simpan</button>
                            <button type="button" data-tw-dismiss="modal"
                                class="btn btn-outline-secondary w-24 ml-1">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('custom-scripts')
    <script src="{{ asset('assets/cms/js/ckeditor-classic.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script>
        // Constants
        const INITIAL_PRICE = 0;
        const INITIAL_QUANTITY = 0;

        let totalHarga = INITIAL_PRICE;
        let qty = INITIAL_QUANTITY;

        document.addEventListener('DOMContentLoaded', () => {
            const packageSelectEl = document.getElementById('package_id');

            // Gunakan instance TomSelect jika ada, atau fallback ke Vanilla event listener
            if (packageSelectEl.tomselect) {
                packageSelectEl.tomselect.on('change', (value) => {
                    handlePackageChange(value);
                });
            } else {
                packageSelectEl.addEventListener('change', (e) => {
                    handlePackageChange(e.target.value);
                });
            }
        });

        function handlePackageChange(packageId) {
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
            const itemSelectEl = document.getElementById('product_id_item');

            // 1. Bersihkan option di TomSelect / Select Biasa
            if (itemSelectEl.tomselect) {
                itemSelectEl.tomselect.clear();
                itemSelectEl.tomselect.clearOptions();
            } else {
                itemSelectEl.innerHTML = '<option value="" disabled selected>Pilih Item...</option>';
            }

            // 2. Data produk yang di-render dari Blade
            const packageData = {
                @foreach ($packages as $package)
                    '{{ $package->id }}': [
                        @foreach ($package->product as $product)
                            @if(optional($product->product)->is_safe_point == 1)
                                {
                                    id: '{{ $product->product->id }}',
                                    name: "{{ $product->product->name }} (Titik Aman)",
                                    price: {{ $product->product->total_price }}
                                },
                            @endif
                        @endforeach
                    ],
                @endforeach
            };

            const products = packageData[packageId] || [];

            // 3. Masukkan data opsi ke TomSelect / Select Element
            products.forEach(prod => {
                const labelText = `${prod.name} - Rp. ${new Intl.NumberFormat('id-ID').format(prod.price)}`;

                if (itemSelectEl.tomselect) {
                    itemSelectEl.tomselect.addOption({
                        value: prod.id,
                        text: labelText,
                        harga: prod.price
                    });
                } else {
                    const opt = document.createElement('option');
                    opt.value = prod.id;
                    opt.textContent = labelText;
                    opt.dataset.harga = prod.price;
                    itemSelectEl.appendChild(opt);
                }
            });
        }

        function clearProductSelection() {
            const packageSelectEl = document.getElementById('package_id');
            if (packageSelectEl.tomselect) {
                packageSelectEl.tomselect.clear();
            } else {
                packageSelectEl.value = '';
            }
        }

        function addItem() {
            const itemSelectEl = document.getElementById('product_id_item');
            let itemId, itemName, itemPrice;

            // 1. Ambil data item terpilih baik dari TomSelect maupun HTML Select biasa
            if (itemSelectEl.tomselect) {
                itemId = itemSelectEl.tomselect.getValue();
                if (!itemId) {
                    alert('Silahkan pilih item terlebih dahulu!');
                    return;
                }
                const selectedData = itemSelectEl.tomselect.options[itemId];
                // Mengambil harga yang disimpan di data object
                itemPrice = parseInt(selectedData.harga || 0, 10);
                // Mengambil nama produk bersih (sebelum tanda -)
                itemName = selectedData.text.split(' - ')[0];
            } else {
                const selectedOption = itemSelectEl.selectedOptions[0];
                if (!selectedOption || !selectedOption.value) {
                    alert('Silahkan pilih item terlebih dahulu!');
                    return;
                }
                itemId = selectedOption.value;
                itemName = selectedOption.textContent.trim().split(' - ')[0];
                const hargaText = selectedOption.textContent.trim().split(' - ')[1] || '0';
                itemPrice = parseInt(hargaText.replace(/[^0-9]/g, ''), 10);
            }

            // 2. Cek apakah produk sudah ada di tabel transaksi
            let existingRow = null;
            $('.transaksiItem tr').each(function() {
                const productId = $(this).find('.product-id-input').val();
                const subAgentSelected = $(this).find('.sub_agent_item').val();
                if (productId === itemId && !subAgentSelected) {
                    existingRow = $(this);
                    return false; // Break loop jQuery
                }
            });

            if (existingRow) {
                // Jika produk sudah ada, tambahkan quantity-nya
                const quantityInput = existingRow.find('.quantityInput');
                const newQuantity = parseInt(quantityInput.val(), 10) + 1;
                quantityInput.val(newQuantity);
                updateQty(quantityInput[0]);
            } else {
                // Jika produk baru, tambahkan baris baru ke tabel
                const itemQuantity = 1;
                const newRow = createTableRow(itemId, itemName, itemPrice, itemQuantity);
                $('.transaksiItem').append(newRow);

                updateTotals(itemPrice, itemQuantity);
            }

            // Reset pilihan dropdown item setelah berhasil ditambahkan
            if (itemSelectEl.tomselect) {
                itemSelectEl.tomselect.clear();
            } else {
                itemSelectEl.selectedIndex = 0;
            }
        }

        function createTableRow(id, name, price, quantity) {
            const subtotal = price * quantity;

            // Perbaikan: Ganti id="product-id" jadi class="product-id-input" agar tidak ada ID ganda di HTML
            const row = `<tr>
        <input value="${id}" class="product-id-input" name="product_id[]" type="hidden">
        <td>${name}</td>
        <td class="text-center" data-price="${price}">${formatRupiah(price)}</td>
        <td class="text-center">
            <input type="number" min="1" value="${quantity}" class="quantityInput form-control w-20 text-center mx-auto" onchange="updateQty(this)" data-initial-value="${quantity}">
        </td>
        <td class="text-center subtotal-td">${formatRupiah(subtotal)}</td>
        <td id="sub_agent_fields">
            <select class="tom-select sub_agent_item form-control" name="sub_agent_id[]">
                <option value="" disabled selected>Pilih Sub Agent...</option>
            </select>
        </td>
        <td class="text-center">
            <button type="button" class="btn btn-danger btn-sm removeItem" onclick="removeItem(this)">Hapus</button>
        </td>
    </tr>`;

            const tableRow = $(row);

            // Populate opsi Sub Agent ke dropdown di dalam baris
            setTimeout(() => {
                const agentId = $('#agent_id').val();
                if (agentId) {
                    populateSubAgentsInRow(agentId, tableRow.find('.sub_agent_item'));
                }
            }, 0);

            return tableRow;
        }

        function populateSubAgentsInRow(agentId, subAgentSelect) {
            subAgentSelect.html('<option disabled selected>Pilih Sub Agent...</option>');

            // Assuming the agents data is embedded directly in the script
            const agents = [{
                id: '{{ $agents->id }}',
                subAgents: [
                    @foreach ($agents->subAgent as $subAgent)
                        {
                            id: '{{ $subAgent->id }}',
                            name: '{{ $subAgent->name }}',
                            phone_number: '{{ $subAgent->phone_number }}'
                        },
                    @endforeach
                ]
            }, ];

            const selectedAgent = agents.find(agent => agent.id === agentId);
            if (selectedAgent) {
                selectedAgent.subAgents.forEach(subAgent => {
                    const option = document.createElement('option');
                    option.value = subAgent.id;
                    option.textContent = `${subAgent.name} - ${subAgent.phone_number}`;
                    subAgentSelect.append(option);
                });
            }
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

        function clearSubAgentSelection() {
            const subAgentSelect = document.getElementById('sub_agent_item');
            subAgentSelect.innerHTML = '<option disabled selected>Pilih Sub Agent...</option>';
        }

        function formatRupiah(number) {
            const formatter = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR'
            });
            return formatter.format(number);
        }

        function simpan(event) {
            event.preventDefault(); // Mencegah submit bawaan form agar proses JSON selesai dulu

            const productData = [];

            $('.transaksiItem tr').each(function() {
                const row = $(this);
                const productId = row.find('.product-id-input').val();

                // Ambil nominal angka murni dari subtotal
                const subTotalText = row.find('.subtotal-td').text();
                const subTotal = parseInt(subTotalText.replace(/[^0-9]/g, ''), 10) || 0;

                const qty = parseInt(row.find('.quantityInput').val(), 10) || 1;
                const subAgentId = row.find('.sub_agent_item').val() || null;

                // Validasi jika productId tersedia
                if (productId) {
                    productData.push({
                        // Mengirim dua variasi key (camelCase & snake_case) agar cocok dengan Controller
                        productId: productId,
                        product_id: productId,
                        subTotal: subTotal,
                        sub_total: subTotal,
                        qty: qty,
                        quantity: qty,
                        subAgentId: subAgentId,
                        sub_agent_id: subAgentId
                    });
                }
            });

            if (productData.length === 0) {
                alert('Harap tambahkan minimal satu produk sebelum menyimpan pesanan!');
                return false;
            }

            // Masukkan string JSON ke hidden input
            $('#productData').val(JSON.stringify(productData));

            // Submit form secara langsung
            document.getElementById('orderForm').submit();
        }
    </script>
@endpush
