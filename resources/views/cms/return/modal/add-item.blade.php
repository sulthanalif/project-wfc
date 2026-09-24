<div id="add-item-modal" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header flex items-center justify-between">
                <h2 class="font-medium text-base mr-auto">Tambah Item
                    Pengembalian</h2>
                <button type="button" class="btn btn-outline-secondary btn-sm btn-icon btn-circle"
                    data-tw-dismiss="modal" aria-label="Close"> <i data-lucide="x" class="w-4 h-4"></i> </button>
            </div>
            <div class="modal-body p-0">
                <form action="{{ route('return.item.create', ['return' => $return]) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="p-5">
                        <input type="hidden" name="order_id" value="{{ $order->id }}">
                        <div class="grid grid-cols-12 gap-3">
                            <div class="col-span-6">
                                <label for="product_id" class="form-label">Pilih Paket <span
                                        class="text-danger">*</span></label>
                                <select class="tom-select mt-2" id="product_id" name="product_id" required>
                                    <option value="">Pilih Paket...</option>
                                    @foreach ($order->detail as $product)
                                        <option value="{{ $product->product_id }}">{{ $product->product->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-span-6" id="sub_product_fields" style="display: none;">
                                <label for="sub_product_id_item" class="form-label">Pilih Produk <span
                                        class="text-danger">*</span></label>
                                <select class="tom-select mt-2" id="sub_product_id_item" name="sub_product_id_item"
                                    required>
                                </select>
                            </div>
                        </div>
                        <div class="grid grid-cols-12 gap-3 mt-3">
                            <div class="col-span-6">
                                <label for="qty" class="form-label">Jumlah
                                    Item</label>
                                <input type="number" name="qty" class="form-control"
                                    placeholder="Masukkan jumlah item" min="1" required>
                            </div>
                            <div class="col-span-6">
                                <label for="status_product" class="form-label">Pilih Status Item <span
                                        class="text-danger">*</span></label>
                                <select class="tom-select" id="status_product" name="status_product" required>
                                    <option value="">Pilih Status Item...</option>
                                    <option value="damaged">Rusak</option>
                                    <option value="expired">Kadaluarsa</option>
                                    <option value="overstock">Kelebihan</option>
                                    <option value="other">Lainnya</option>
                                </select>
                            </div>
                        </div>
                        <div class="grid grid-cols-12 gap-3 mt-3">
                            <div class="col-span-12">
                                <label for="proof_image" class="form-label">Upload Foto <span
                                        class="text-danger">*</span></label>
                                <div class="px-4 pb-4 mt-5 flex items-center justify-center cursor-pointer relative">
                                    <i data-lucide="image" class="w-4 h-4 mr-2"></i>
                                    <span id="fileName">
                                        <span class="text-primary mr-1">Upload a file</span> or drag and drop
                                    </span>
                                    <input id="proof_image" name="proof_image" type="file"
                                        class="w-full h-full top-0 left-0 absolute opacity-0"
                                        onchange="previewFile(this); updateFileName(this)">
                                </div>
                                <div id="image-preview" class="hidden mt-2"></div>
                            </div>
                        </div>
                    </div>
                    <div class="px-5 pb-8">
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary w-24">Simpan</button>
                            <button type="button" data-tw-dismiss="modal"
                                class="btn btn-outline-secondary w-24 ml-1">Batal</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('custom-scripts')
    <script>
        function previewFile(input) {
            const file = input.files[0];
            const preview = document.getElementById('image-preview');

            if (!preview) {
                return;
            }

            if (file) {
                if (file.size > 2 * 1024 * 1024) {
                    alert('Ukuran gambar lebih dari 2MB. Silahkan pilih gambar yang lebih kecil');
                    preview.innerHTML = '';
                    preview.classList.add('hidden');
                    input.value = '';
                    return;
                }

                const allowedExtensions = ['jpg', 'jpeg', 'png'];
                const extension = file.name.split('.').pop().toLowerCase();
                if (!allowedExtensions.includes(extension)) {
                    alert('Hanya file dengan tipe (jpg, jpeg, png) yang diperbolehkan!!');
                    preview.innerHTML = '';
                    preview.classList.add('hidden');
                    input.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `<img src="${e.target.result}" class="w-auto h-40 object-cover rounded border border-slate-200 shadow-sm">`;
                    preview.classList.remove('hidden');
                };

                reader.readAsDataURL(file);
            } else {
                preview.innerHTML = '';
                preview.classList.add('hidden');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const productSelect = document.getElementById('product_id');
            const subProductFields = document.getElementById('sub_product_fields');
            const subProductSelect = document.getElementById('sub_product_id_item');

            productSelect.addEventListener('change', () => {
                const productId = productSelect.value;
                populateSubProducts(productId);
            });

            const productsData = [
                @foreach ($products as $product)
                    @foreach ($product->subProduct as $subProduct)
                        {
                            product_id: '{{ $product->id }}',
                            sub_product_id: '{{ $subProduct->subProduct->id }}',
                            sub_product_name: '{{ $subProduct->subProduct->name }}',
                        },
                    @endforeach
                @endforeach
            ];

            const returnDetail = @json($detail);

            function populateSubProducts(productId) {
                subProductSelect.tomselect.clearOptions();
                subProductSelect.tomselect.addOption({
                    value: '',
                    text: 'Pilih Sub Produk...'
                });
                if (productId) {
                    const filteredSubProducts = productsData.filter(subProduct => subProduct.product_id ===
                        productId);
                    filteredSubProducts.forEach(subProduct => {
                        const isReturned = returnDetail.some(detail => detail.sub_product_id == subProduct
                            .sub_product_id);
                        if (!isReturned) {
                            subProductSelect.tomselect.addOption({
                                value: subProduct.sub_product_id,
                                text: subProduct.sub_product_name
                            });
                        }
                    });

                    subProductFields.style.display = 'block';
                } else {
                    subProductFields.style.display = 'none';
                }
            }
        });
    </script>
@endpush
