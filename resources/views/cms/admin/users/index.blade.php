@extends('cms.layouts.app', [
    'title' => 'Users',
])

@section('content')
    <h2 class="intro-y text-lg font-medium mt-10">
        Users
    </h2>
    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
            <a href="{{ route('user.create') }}" class="btn btn-primary shadow-md mr-2">Tambah User</a>
            <div class="hidden md:block mx-auto text-slate-500">Menampilkan {{ $users->firstItem() }} hingga {{ $users->lastItem() }} dari {{ $users->total() }} data</div>
            <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                <div class="w-56 relative text-slate-500">
                    <input type="text" class="form-control w-56 box pr-10" placeholder="Search...">
                    <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-lucide="search"></i>
                </div>
            </div>
        </div>
        <!-- BEGIN: Users Layout -->
        @foreach ($users as $user)
            <div class="intro-y col-span-12 md:col-span-4">
                <div class="box">
                    <div
                        class="flex flex-col lg:flex-row items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                        <div class="w-24 h-24 lg:w-12 lg:h-12 image-fit lg:mr-1">
                            <img alt="Profile" class="rounded-full" src="{{ asset('assets/cms/images/profile.svg') }}">
                        </div>
                        <div class="lg:ml-2 lg:mr-auto text-center lg:text-left mt-3 lg:mt-0">
                            <a href="{{ route('user.show', ['user' => $user->id]) }}"
                                class="font-medium">{{ $user->agentProfile ? $user->agentProfile->name : $user->roles->first()->name }}</a>
                            @if ($user->roles->first()->name == 'super_admin')
                                <div class="text-slate-500 text-xs mt-0.5">Super Admin</div>
                            @elseif ($user->roles->first()->name == 'admin')
                                <div class="text-slate-500 text-xs mt-0.5">Admin</div>
                            @elseif ($user->roles->first()->name == 'finance_admin')
                                <div class="text-slate-500 text-xs mt-0.5">Admin Keuangan</div>
                            @else
                                <div class="text-slate-500 text-xs mt-0.5">Agen</div>
                            @endif
                        </div>
                        <div class="flex -ml-2 lg:ml-0 lg:justify-end mt-3 lg:mt-0">
                            @if (in_array($user->roles->first()->name, ['super_admin', 'admin', 'finance_admin']))
                                <a href=""
                                    class="w-8 h-8 rounded-full flex items-center justify-center border dark:border-darkmode-400 ml-2 text-slate-400 zoom-in tooltip"
                                    title="{{ $user->email }}"> <i class="w-3 h-3" data-lucide="mail"></i> </a>
                            @else
                                <a href=""
                                    class="w-8 h-8 rounded-full flex items-center justify-center border dark:border-darkmode-400 ml-2 text-slate-400 zoom-in tooltip"
                                    title="{{ $user->email }}"> <i class="w-3 h-3" data-lucide="mail"></i> </a>
                                <a href=""
                                    class="w-8 h-8 rounded-full flex items-center justify-center border dark:border-darkmode-400 ml-2 text-slate-400 zoom-in tooltip"
                                    title="{{ empty($user->agentProfile->phone_number) ? 'Nomer HP Belum Diisi' : $user->agentProfile->phone_number }}">
                                    <i class="w-3 h-3" data-lucide="phone"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="flex flex-wrap lg:flex-nowrap items-center justify-left p-5">
                        <a href="{{ route('user.show', ['user' => $user->id]) }}"
                            class="btn btn-primary py-1 px-2 mr-2">Profile</a>
                        <form method="delete" action="{{ route('user.destroy', ['user' => $user->id]) }}">
                            @csrf
                            <button type="submit" class="btn btn-danger py-1 px-2">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach


        <!-- END: Users Layout -->
        <!-- BEGIN: Pagination -->
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center">
            {{ $users->links() }}
        </div>
        {{-- <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center">
        <nav class="w-full sm:w-auto sm:mr-auto">
            <ul class="pagination">
                <li class="page-item">
                    <a class="page-link" href="#"> <i class="w-4 h-4" data-lucide="chevrons-left"></i> </a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="#"> <i class="w-4 h-4" data-lucide="chevron-left"></i> </a>
                </li>
                <li class="page-item"> <a class="page-link" href="#">...</a> </li>
                <li class="page-item"> <a class="page-link" href="#">1</a> </li>
                <li class="page-item active"> <a class="page-link" href="#">2</a> </li>
                <li class="page-item"> <a class="page-link" href="#">3</a> </li>
                <li class="page-item"> <a class="page-link" href="#">...</a> </li>
                <li class="page-item">
                    <a class="page-link" href="#"> <i class="w-4 h-4" data-lucide="chevron-right"></i> </a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="#"> <i class="w-4 h-4" data-lucide="chevrons-right"></i> </a>
                </li>
            </ul>
        </nav>
        <select class="w-20 form-select box mt-3 sm:mt-0">
            <option>10</option>
            <option>25</option>
            <option>35</option>
            <option>50</option>
        </select>
    </div> --}}
        <!-- END: Pagination -->
    </div>
@endsection
