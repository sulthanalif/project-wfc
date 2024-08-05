<!-- BEGIN: Payment Confirmation Modal -->
<div id="detail-confirmation-modal{{ $item->id }}" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="form-label text-lg font-bold">Ubah Produk</h2>
            </div>
            <div class="modal-body p-0">
                <div class="p-5">
                    {{-- <i data-lucide="x-circle" class="w-16 h-16 text-danger mx-auto mt-3"></i> --}}
                    <form action="{{ route('order.editDetail', $item) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="text" name="sub_agent_id" id="sub_agent_id" value="{{ $item->sub_agent_id }}"
                            hidden>
                        <div>
                            <label for="product_id" class="form-label">Produk Yang Dibeli <span
                                    class="text-danger">*</span></label>
                            <select class="form-select mt-2 sm:mr-2" id="product_id" name="product_id" required>
                                @php
                                    $product = \App\Helpers\GetProduct::detail($item->product->name);
                                @endphp
                                @foreach ($product as $v)
                                    @if ($v->package->package->period->is_active)
                                        <option value="{{ $v->id }}"
                                            {{ $item->product_id == $v->id ? 'selected' : '' }}>{{ $v->name }}
                                            {{ $v->is_safe_point == 1 ? '(Titik Aman)' : '' }}</option>
                                    @endif
                                @endforeach
                            </select>
                            @error('product_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mt-3">
                            <label for="qty" class="form-label">Jumlah Yang Dibeli <span
                                    class="text-danger">*</span></label>
                            <input type="number" id="qty" name="qty" class="form-control w-full"
                                value="{{ $item->qty }}">
                            @error('qty')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>



                        <div class="px-5 mt-3 pb-8 text-center">
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
<!-- END: Payment Confirmation Modal -->
