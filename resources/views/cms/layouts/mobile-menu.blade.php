<!-- BEGIN: Mobile Menu -->
<div class="mobile-menu md:hidden">
    <div class="mobile-menu-bar">
        <a href="" class="flex mr-auto">
            <img alt="Smart WFC" class="w-6" src="{{ asset('assets/logo2.PNG') }}">
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
                <li class="menu__devider my-6"></li>
                <li>
                    <a href="javascript:;" class="menu @if (Route::is('totalDeposit') || Route::is('rproductDetail') || Route::is('instalment')) menu--active @endif">
                        <div class="menu__icon"> <i data-lucide="file-text"></i> </div>
                        <div class="menu__title"> Laporan <i data-lucide="chevron-down" class="menu__sub-icon "></i></div>
                    </a>
                    <ul class="@if (Route::is('totalDeposit') || Route::is('rproductDetail') || Route::is('instalment')) menu__sub-open @endif">
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
                            <a href="{{ route('totalDeposit') }}" class="menu {{ Route::is('totalDeposit') ? 'menu--active' : '' }}">
                                <div class="menu__icon"> <i data-lucide="activity"></i> </div>
                                <div class="menu__title"> Total Setoran </div>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('rproductDetail') }}" class="menu{{ Route::is('rproductDetail') ? 'menu--active' : '' }}">
                                <div class="menu__icon"> <i data-lucide="activity"></i> </div>
                                <div class="menu__title"> Rincian Perpaket </div>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('instalment') }}" class="menu {{ Route::is('instalment') ? 'menu--active' : '' }}">
                                <div class="menu__icon"> <i data-lucide="activity"></i> </div>
                                <div class="menu__title"> Rincian Cicilan </div>
                            </a>
                        </li>
                    </ul>
                </li>
                @hasrole('super_admin|admin')
                    <li>
                        <a href="javascript:;" class="menu @if (Route::is('user*') ||
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
                            {{-- <li>
                                <a href="{{ route('catalog.index') }}"
                                    class="menu {{ Route::is('catalog*') ? 'menu--active' : '' }}">
                                    <div class="menu__icon"> <i data-lucide="activity"></i> </div>
                                    <div class="menu__title"> Katalog </div>
                                </a>
                            </li> --}}
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
                        <a href="javascript:;" class="menu">
                            <div class="menu__icon"> <i data-lucide="file-text"></i> </div>
                            <div class="menu__title"> Laporan <i data-lucide="chevron-down" class="menu__sub-icon "></i>
                            </div>
                        </a>
                        <ul class="">
                            <li>
                                <a href="#" class="menu">
                                    <div class="menu__icon"> <i data-lucide="activity"></i> </div>
                                    <div class="menu__title"> Jumlah Paket </div>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="menu">
                                    <div class="menu__icon"> <i data-lucide="activity"></i> </div>
                                    <div class="menu__title"> Setoran </div>
                                </a>
                            </li>
                        </ul>
                    </li>
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
