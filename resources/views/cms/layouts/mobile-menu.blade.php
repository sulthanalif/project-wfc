<!-- BEGIN: Mobile Menu -->
<div class="mobile-menu md:hidden">
    <div class="mobile-menu-bar">
        <a href="" class="flex mr-auto">
            <img alt="Smart WFC" class="w-6" src="{{ asset('assets/logo2.png') }}">
        </a>
        <a href="javascript:;" class="mobile-menu-toggler"> <i data-lucide="bar-chart-2"
                class="w-8 h-8 text-white transform -rotate-90"></i> </a>
    </div>
    @hasrole('super_admin|admin|finance_admin')
        <div class="scrollable">
            <a href="javascript:;" class="mobile-menu-toggler"> <i data-lucide="x-circle"
                    class="w-8 h-8 text-white transform -rotate-90"></i> </a>
            <ul class="scrollable__content py-2">
                <li>
                    <a href="{{ route('dashboard-admin') }}"
                        class="menu {{ Route::is('dashboard-admin') ? 'menu--active' : '' }}">
                        <div class="menu__icon"> <i data-lucide="home"></i> </div>
                        <div class="menu__title"> Dashboard </div>
                    </a>
                </li>
                <li>
                    <a href="javascript:;" class="menu @if (Route::is('order*') || Route::is('distribution*')) menu--active @endif">
                        <div class="menu__icon"> <i data-lucide="shopping-bag"></i> </div>
                        <div class="menu__title"> Transaksi <i data-lucide="chevron-down" class="menu__sub-icon "></i>
                        </div>
                    </a>
                    <ul class="@if (Route::is('order*') || Route::is('distribution*')) menu__sub-open @endif">
                        <li>
                            <a href="{{ route('order.index') }}"
                                class="menu {{ Route::is('order*') ? 'menu--active' : '' }}">
                                <div class="menu__icon"> <i data-lucide="activity"></i> </div>
                                <div class="menu__title"> Pesanan </div>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('distribution.index') }}"
                                class="menu {{ Route::is('distribution*') ? 'menu--active' : '' }}">
                                <div class="menu__icon"> <i data-lucide="activity"></i> </div>
                                <div class="menu__title"> Pendistribusian </div>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="menu__devider my-4"></li>
                <li>
                    <a href="javascript:;" class="menu @if (Route::is('totalDeposit') || Route::is('rproductDetail') || Route::is('instalment') || Route::is('requirement')) menu--active @endif">
                        <div class="menu__icon"> <i data-lucide="file-text"></i> </div>
                        <div class="menu__title"> Laporan <i data-lucide="chevron-down" class="menu__sub-icon "></i></div>
                    </a>
                    <ul class="@if (Route::is('totalDeposit') || Route::is('rproductDetail') || Route::is('instalment') || Route::is('requirement')) menu__sub-open @endif">
                        {{-- <li>
                            <a href="javascript:;" class="menu">
                                <div class="menu__icon"> <i data-lucide="activity"></i> </div>
                                <div class="menu__title"> Setoran <i data-lucide="chevron-down" class="menu__sub-icon "></i>
                                </div>
                            </a>
                            <ul class="">
                                <li>
                                    <a href="#" class="menu">
                                        <div class="menu__icon"> <i data-lucide="zap"></i> </div>
                                        <div class="menu__title">Total Cicilan</div>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="menu">
                                        <div class="menu__icon"> <i data-lucide="zap"></i> </div>
                                        <div class="menu__title">Rincian Cicilan</div>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="menu">
                                        <div class="menu__icon"> <i data-lucide="zap"></i> </div>
                                        <div class="menu__title">Rincian Perpaket</div>
                                    </a>
                                </li>
                            </ul>
                        </li> --}}
                        <li>
                            <a href="{{ route('totalDeposit') }}"
                                class="menu {{ Route::is('totalDeposit') ? 'menu--active' : '' }}">
                                <div class="menu__icon"> <i data-lucide="activity"></i> </div>
                                <div class="menu__title"> Total Setoran </div>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('rproductDetail') }}"
                                class="menu{{ Route::is('rproductDetail') ? 'menu--active' : '' }}">
                                <div class="menu__icon"> <i data-lucide="activity"></i> </div>
                                <div class="menu__title"> Rincian Perpaket </div>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('instalment') }}"
                                class="menu {{ Route::is('instalment') ? 'menu--active' : '' }}">
                                <div class="menu__icon"> <i data-lucide="activity"></i> </div>
                                <div class="menu__title"> Rincian Cicilan </div>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('requirement') }}"
                                class="menu {{ Route::is('requirement') ? 'menu--active' : '' }}">
                                <div class="menu__icon"> <i data-lucide="activity"></i> </div>
                                <div class="menu__title"> Rincian Sub Produk </div>
                            </a>
                        </li>
                    </ul>
                </li>
                @hasrole('finance_admin|super_admin')
                    <li>
                        <a href="javascript:;" class="menu @if (Route::is('income*') || Route::is('spending*')) menu--active @endif">
                            <div class="menu__icon"> <i data-lucide="dollar-sign"></i> </div>
                            <div class="menu__title"> Keuangan <i data-lucide="chevron-down" class="menu__sub-icon "></i>
                            </div>
                        </a>
                        <ul class="@if (Route::is('income*') || Route::is('spending*')) menu__sub-open @endif">
                            <li>
                                <a href="{{ route('income.index') }}"
                                    class="menu {{ Route::is('income*') ? 'menu--active' : '' }}">
                                    <div class="menu__icon"> <i data-lucide="activity"></i> </div>
                                    <div class="menu__title"> Pemasukan </div>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('spending.index') }}"
                                    class="menu {{ Route::is('spending*') ? 'menu--active' : '' }}">
                                    <div class="menu__icon"> <i data-lucide="activity"></i> </div>
                                    <div class="menu__title"> Pengeluaran </div>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endhasrole
                @hasrole('super_admin|admin')
                    <li>
                        <a href="javascript:;" class="menu @if (Route::is('user*') ||
                                Route::is('options.index') ||
                                Route::is('package*') ||
                                Route::is('product*') ||
                                Route::is('sub-product*') ||
                                Route::is('sub-agent*') ||
                                Route::is('supplier*') ||
                                Route::is('getAdministration')) menu--active @endif">
                            <div class="menu__icon"> <i data-lucide="box"></i> </div>
                            <div class="menu__title"> Master <i data-lucide="chevron-down" class="menu__sub-icon "></i>
                            </div>
                        </a>
                        <ul class="@if (Route::is('user*') ||
                                Route::is('options.index') ||
                                Route::is('package*') ||
                                Route::is('product*') ||
                                Route::is('sub-product*') ||
                                Route::is('sub-agent*') ||
                                Route::is('supplier*') ||
                                Route::is('getAdministration')) menu__sub-open @endif">
                            <li>
                                <a href="{{ route('sub-product.index') }}"
                                    class="menu {{ Route::is('sub-product*') ? 'menu--active' : '' }}">
                                    <div class="menu__icon"> <i data-lucide="activity"></i> </div>
                                    <div class="menu__title"> Sub Produk </div>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('product.index') }}"
                                    class="menu {{ Route::is('product*') ? 'menu--active' : '' }}">
                                    <div class="menu__icon"> <i data-lucide="activity"></i> </div>
                                    <div class="menu__title"> Produk </div>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('package.index') }}"
                                    class="menu {{ Route::is('package*') ? 'menu--active' : '' }}">
                                    <div class="menu__icon"> <i data-lucide="activity"></i> </div>
                                    <div class="menu__title"> Paket </div>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('options.index') }}"
                                    class="menu {{ Route::is('options.index') ? 'menu--active' : '' }}">
                                    <div class="menu__icon"> <i data-lucide="activity"></i> </div>
                                    <div class="menu__title"> Periode </div>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('supplier.index') }}"
                                    class="menu {{ Route::is('supplier*') ? 'menu--active' : '' }}">
                                    <div class="menu__icon"> <i data-lucide="activity"></i> </div>
                                    <div class="menu__title"> Supplier </div>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('sub-agent.index') }}"
                                    class="menu {{ Route::is('sub-agent*') ? 'menu--active' : '' }}">
                                    <div class="menu__icon"> <i data-lucide="activity"></i> </div>
                                    <div class="menu__title"> Sub Agen </div>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('user.index') }}"
                                    class="menu @if (Route::is('user*') || Route::is('getAdministration')) menu--active @endif">
                                    <div class="menu__icon"> <i data-lucide="activity"></i> </div>
                                    <div class="menu__title"> Users </div>
                                </a>
                            </li>
                        </ul>
                    </li>
                    @hasrole('super_admin')
                        <li>
                            <a href="javascript:;" class="menu @if (Route::is('landingpage*')) menu--active @endif">
                                <div class="menu__icon"> <i data-lucide="settings"></i> </div>
                                <div class="menu__title"> Landing Page <i data-lucide="chevron-down" class="menu__sub-icon "></i>
                                </div>
                            </a>
                            <ul class="@if (Route::is('landingpage*')) menu__sub-open @endif">
                                <li>
                                    <a href="{{ route('landingpage.header') }}"
                                        class="menu {{ Route::is('landingpage.header') ? 'menu--active' : '' }}">
                                        <div class="menu__icon"> <i data-lucide="activity"></i> </div>
                                        <div class="menu__title"> Header </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('landingpage.profile') }}"
                                        class="menu {{ Route::is('product.index') ? 'menu--active' : '' }}">
                                        <div class="menu__icon"> <i data-lucide="activity"></i> </div>
                                        <div class="menu__title"> Profile </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('landingpage.detailProfile') }}"
                                        class="menu {{ Route::is('landingpage.detailProfile') ? 'menu--active' : '' }}">
                                        <div class="menu__icon"> <i data-lucide="activity"></i> </div>
                                        <div class="menu__title"> Detail Profile </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('supplier.index') }}"
                                        class="menu {{ Route::is('supplier*') ? 'menu--active' : '' }}">
                                        <div class="menu__icon"> <i data-lucide="activity"></i> </div>
                                        <div class="menu__title"> Supplier </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('landingpage.gallery') }}"
                                        class="menu {{ Route::is('landingpage.gallery') ? 'menu--active' : '' }}">
                                        <div class="menu__icon"> <i data-lucide="activity"></i> </div>
                                        <div class="menu__title"> Galeri </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('landingpage.review') }}"
                                        class="menu {{ Route::is('landingpage.review') ? 'menu--active' : '' }}">
                                        <div class="menu__icon"> <i data-lucide="activity"></i> </div>
                                        <div class="menu__title"> Review </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('landingpage.contact') }}"
                                        class="menu {{ Route::is('landingpage.contact') ? 'menu--active' : '' }}">
                                        <div class="menu__icon"> <i data-lucide="activity"></i> </div>
                                        <div class="menu__title"> Kontak </div>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endhasrole
                @endhasrole
            </ul>
        </div>
    @endhasrole

    @hasrole('agent')
        <div class="scrollable">
            <a href="javascript:;" class="mobile-menu-toggler"> <i data-lucide="x-circle"
                    class="w-8 h-8 text-white transform -rotate-90"></i> </a>
            <ul class="scrollable__content py-2">
                @if (auth()->user()->active == 0)
                    <li>
                        <a href="{{ route('nonactive') }}"
                            class="menu {{ Route::is('nonactive') ? 'menu--active' : '' }}">
                            <div class="menu__icon"> <i data-lucide="home"></i> </div>
                            <div class="menu__title"> Dashboard </div>
                        </a>
                    </li>
                @else
                    <li>
                        <a href="{{ route('dashboard-agent') }}"
                            class="menu {{ Route::is('dashboard-agent') ? 'menu--active' : '' }}">
                            <div class="menu__icon"> <i data-lucide="home"></i> </div>
                            <div class="menu__title"> Dashboard </div>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('order.index') }}"
                            class="menu {{ Route::is('order*') ? 'menu--active' : '' }}">
                            <div class="menu__icon"> <i data-lucide="shopping-bag"></i> </div>
                            <div class="menu__title"> Transaksi
                            </div>
                        </a>
                    </li>
                    <li class="menu__devider my-6"></li>
                    <li>
                        <a href="{{ route('sub-agent.index') }}"
                            class="menu {{ Route::is('sub-agent*') ? 'menu--active' : '' }}">
                            <div class="menu__icon"> <i data-lucide="users"></i> </div>
                            <div class="menu__title"> Sub Agen </div>
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    @endhasrole
</div>
<!-- END: Mobile Menu -->
