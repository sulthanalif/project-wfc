<!-- BEGIN: Top Bar -->
<div
    class="top-bar-boxed h-[70px] md:h-[65px] z-[51] border-b border-white/[0.08] mt-12 md:mt-0 -mx-3 sm:-mx-8 md:-mx-0 px-3 md:border-b-0 relative md:fixed md:inset-x-0 md:top-0 sm:px-8 md:px-10 md:pt-10 md:bg-gradient-to-b md:from-slate-100 md:to-transparent dark:md:from-darkmode-700">
    @if (session('success'))
        <div class="-intro-y z-50">
            <div class="alert alert-outline-secondary alert-dismissible show flex items-center fixed top-2 right-0 bg-white p-4 rounded shadow-lg"
                role="alert">
                <span class="text-success flex">
                    <i data-lucide="check-circle" class="w-6 h-6 mr-2"></i> {{ session('success') }}
                </span>
                <button type="button" class="btn-close" data-tw-dismiss="alert" aria-label="Close"> <i data-lucide="x"
                        class="w-4 h-4"></i> </button>
            </div>
        </div>
    @endif
    @if (session('error'))
        <div class="-intro-y z-50">
            <div class="alert alert-outline-secondary alert-dismissible show flex items-center fixed top-2 right-0 bg-white p-4 rounded shadow-lg"
                role="alert">
                <span class="text-danger flex">
                    <i data-lucide="x-circle" class="w-6 h-6 mr-2"></i> {{ session('error') }}
                </span>
                <button type="button" class="btn-close" data-tw-dismiss="alert" aria-label="Close"> <i data-lucide="x"
                        class="w-4 h-4"></i> </button>
            </div>
        </div>
    @endif
    <div class="h-full flex items-center">
        <!-- BEGIN: Logo -->
        @if (auth()->user()->hasRole('super_admin|admin|finance_admin'))
            <a href="{{ route('dashboard-admin') }}" class="logo -intro-x hidden md:flex xl:w-[180px] block">
                <img alt="Paket Smart WFC" class="logo__image w-6" src="{{ asset('assets/logo2.png') }}">
                <span class="logo__text text-white text-lg ml-3"> Paket Smart WFC </span>
            </a>
        @else
            <a href="{{ route('dashboard-agent') }}" class="logo -intro-x hidden md:flex xl:w-[180px] block">
                <img alt="Paket Smart WFC" class="logo__image w-6" src="{{ asset('assets/logo2.png') }}">
                <span class="logo__text text-white text-lg ml-3"> Paket Smart WFC </span>
            </a>
        @endif
        <!-- END: Logo -->
        <!-- BEGIN: Breadcrumb -->
        <nav aria-label="breadcrumb" class="-intro-x h-[45px] mr-auto">
            <ol class="breadcrumb breadcrumb-light">
                <li class="breadcrumb-item"><a href="#">Application</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
            </ol>
        </nav>
        <!-- END: Breadcrumb -->
        <!-- BEGIN: Notifications -->
        {{-- <div class="intro-x dropdown mr-4 sm:mr-6">
            <div class="dropdown-toggle notification notification--bullet cursor-pointer" role="button"
                aria-expanded="false" data-tw-toggle="dropdown"> <i data-lucide="bell"
                    class="notification__icon dark:text-slate-500"></i> </div>
            <div class="notification-content pt-2 dropdown-menu">
                <div class="notification-content__box dropdown-content">
                    <div class="notification-content__title">Notifications</div>
                    <div class="cursor-pointer relative flex items-center ">
                        <div class="w-12 h-12 flex-none image-fit mr-1">
                            <img alt="Paket Smart WFC" class="rounded-full"
                                src="{{ asset('assets/cms/images/profile.svg') }}">
                            <div
                                class="w-3 h-3 bg-success absolute right-0 bottom-0 rounded-full border-2 border-white">
                            </div>
                        </div>
                        <div class="ml-2 overflow-hidden">
                            <div class="flex items-center">
                                <a href="javascript:;" class="font-medium truncate mr-5">Al Pacino</a>
                                <div class="text-xs text-slate-400 ml-auto whitespace-nowrap">01:10 PM</div>
                            </div>
                            <div class="w-full truncate text-slate-500 mt-0.5">Contrary to popular belief, Lorem
                                Ipsum is not simply random text. It has roots in a piece of classical Latin
                                literature from 45 BC, making it over 20</div>
                        </div>
                    </div>
                    <div class="cursor-pointer relative flex items-center mt-5">
                        <div class="w-12 h-12 flex-none image-fit mr-1">
                            <img alt="Paket Smart WFC" class="rounded-full"
                                src="{{ asset('assets/cms/images/profile.svg') }}">
                            <div
                                class="w-3 h-3 bg-success absolute right-0 bottom-0 rounded-full border-2 border-white">
                            </div>
                        </div>
                        <div class="ml-2 overflow-hidden">
                            <div class="flex items-center">
                                <a href="javascript:;" class="font-medium truncate mr-5">Sean Connery</a>
                                <div class="text-xs text-slate-400 ml-auto whitespace-nowrap">01:10 PM</div>
                            </div>
                            <div class="w-full truncate text-slate-500 mt-0.5">It is a long established fact that a
                                reader will be distracted by the readable content of a page when looking at its
                                layout. The point of using Lorem </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
        <!-- END: Notifications -->
        <!-- BEGIN: Account Menu -->
        <div class="intro-x dropdown w-8 h-8">
            <div class="dropdown-toggle w-8 h-8 rounded-full overflow-hidden shadow-lg image-fit zoom-in scale-110"
                role="button" aria-expanded="false" data-tw-toggle="dropdown">
                @if (auth()->user()->hasRole('agent'))
                    <img alt="Profile"
                        src="{{ auth()->user()->agentProfile->photo ? route('getImage', ['path' => 'photos', 'imageName' => auth()->user()->agentProfile->photo]) : asset('assets/cms/images/profile.svg') }}">
                @else
                    <img alt="Profile" src="{{ asset('assets/cms/images/profile.svg') }}">
                @endif
            </div>
            <div class="dropdown-menu w-56">
                <ul
                    class="dropdown-content bg-primary/80 before:block before:absolute before:bg-black before:inset-0 before:rounded-md before:z-[-1] text-white">
                    <li class="p-2">
                        @hasrole('super_admin|admin|finance_admin')
                            <div class="font-medium">{{ optional(auth()->user()->adminProfile)->name }}</div>
                            <div class="text-xs text-white/60 mt-0.5 dark:text-slate-500">{{ auth()->user()->email }}
                            </div>
                        @endhasrole
                        @hasrole('agent')
                            <div class="font-medium">{{ optional(auth()->user()->agentProfile)->name }}</div>
                            <div class="text-xs text-white/60 mt-0.5 dark:text-slate-500">{{ auth()->user()->email }}</div>
                        @endhasrole
                    </li>
                    <li>
                        <hr class="dropdown-divider border-white/[0.08]">
                    </li>
                    @hasrole('agent')
                        @if (auth()->user()->active == 1)
                            <li>
                                <a href="{{ route('users.profile', ['id' => auth()->user()->id]) }}"
                                    class="dropdown-item hover:bg-white/5"> <i data-lucide="user" class="w-4 h-4 mr-2"></i>
                                    Profile </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider border-white/[0.08]">
                            </li>
                        @endif
                    @endhasrole
                    <li>
                        <a href="{{ route('logout') }}" class="dropdown-item hover:bg-white/5"
                            onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
                            <i data-lucide="toggle-right" class="w-4 h-4 mr-2"></i> Logout </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
        </div>
        <!-- END: Account Menu -->
    </div>
</div>
<!-- END: Top Bar -->
