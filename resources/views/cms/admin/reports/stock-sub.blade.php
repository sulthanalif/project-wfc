@extends('cms.layouts.app', [
    'title' => 'Laporan Total Deposit',
])

@section('content')
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 2xl:col-span-9">
            <div class="grid grid-cols-12 gap-6">
                <!-- BEGIN: Laporan Total Deposit -->
                <div class="col-span-12 mt-8">
                    <div class="intro-y flex items-center h-10">
                        <h2 class="text-lg font-medium truncate mr-5">
                            Laporan Total Setoran
                        </h2>
                    </div>
                </div>
                <!-- END: Laporan Total Deposit -->
            </div>
        </div>

        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2 gap-2">
            <a href="{{ route('totalDeposit', array_merge(request()->except('page'), ['export' => 1])) }}"
                class="btn btn-primary shadow-md mr-2"> <i data-lucide="file"
                    class="w-4 h-4 mr-3"></i> Export </a>
            <div class="w-full xl:w-auto flex items-center mt-3 xl:mt-0 ml-auto">
                <div class="w-56 relative text-slate-500">
                    <input type="text" class="form-control w-56 box pr-10" placeholder="Search..." id="filter">
                    <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-lucide="search"></i>
                </div>
            </div>
        </div>
        <!-- BEGIN: Data List -->
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
          <table class="table table-report -mt-2">
              <thead>
                  <tr>
                      <th class="text-center whitespace-nowrap">#</th>
                      <th class="text-center whitespace-nowrap">ITEM</th>
                      {{-- <th class="text-center whitespace-nowrap">HARGA</th> --}}
                      <th class="text-center whitespace-nowrap">SATUAN</th>
                      <th class="text-center whitespace-nowrap">JUMLAH STOK</th>

                  </tr>
              </thead>
              <tbody>
                  @if (!isset($products) || (is_array($products) && empty($products)) || (is_object($products) && $products->isEmpty()))
                      <tr>
                          <td colspan="8" class="font-medium whitespace-nowrap text-center">Belum Ada Data</td>
                      </tr>
                  @else
                      @foreach ($products as $data)
                          <tr class="intro-x">
                              <td>
                                  <p class="font-medium whitespace-nowrap text-center">{{ $loop->iteration }}</p>
                              </td>
                              <td>
                                  <span class="text-slate-500">{{ $data['name'] }}</span>
                              </td>
                              <td align='center'>
                                  <span class="text-slate-500">{{ $data['unit'] }}</span>
                              </td>

                              <td align='center'>
                                  <span class="text-slate-500">{{ $data['procurement'] ?? 0 }}</span>
                              </td>
                          </tr>
                      @endforeach
                  @endif
              </tbody>
          </table>
      </div>
        <!-- END: Data List -->
        <!-- BEGIN: Pagination -->
        {{-- <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center">
            {{ $paginationData->links('cms.layouts.paginate') }}
        </div> --}}
        <!-- END: Pagination -->
    </div>
@endsection
