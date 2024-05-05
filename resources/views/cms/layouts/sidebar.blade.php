<!-- BEGIN: Side Menu -->
<nav class="side-nav">
    @hasrole('super_admin|admin|finance_admin')
        <ul>
            <li>
                <a href="{{ route('dashboard-admin') }}"
                    class="side-menu {{ Route::is('dashboard-admin') ? 'side-menu--active' : '' }}">
                    <div class="side-menu__icon"> <i data-lucide="home"></i> </div>
                    <div class="side-menu__title">
                        Dashboard
                    </div>
                </a>
            </li>
            <li>
                <a href="javascript:;" class="side-menu @if (Route::is('order*')) side-menu--active @endif">
                    <div class="side-menu__icon"> <i data-lucide="shopping-bag"></i> </div>
                    <div class="side-menu__title">
                        Transaksi
                        <div class="side-menu__sub-icon "> <i data-lucide="chevron-down"></i> </div>
                    </div>
                </a>
                <ul class="@if (Route::is('order*')) side-menu__sub-open @endif">
                    {{-- <li>
                        <a href="#" class="side-menu">
                            <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                            <div class="side-menu__title"> Pengisian Paket </div>
                        </a>
                    </li> --}}
                    <li>
                        <a href="{{ route('order.index') }}" class="side-menu {{ Route::is('order*') ? 'side-menu--active' : '' }}">
                            <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                            <div class="side-menu__title"> Pesanan </div>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="side-menu">
                            <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                            <div class="side-menu__title"> Pendistribusian </div>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:;" class="side-menu">
                            <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                            <div class="side-menu__title"> Setoran <i data-lucide="chevron-down"
                                    class="side-menu__sub-icon "></i>
                            </div>
                        </a>
                        <ul class="">
                            <li>
                                <a href="#" class="side-menu">
                                    <div class="side-menu__icon"> <i data-lucide="zap"></i> </div>
                                    <div class="side-menu__title">Total Cicilan</div>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="side-menu">
                                    <div class="side-menu__icon"> <i data-lucide="zap"></i> </div>
                                    <div class="side-menu__title">Rincian Cicilan</div>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="side-menu">
                                    <div class="side-menu__icon"> <i data-lucide="zap"></i> </div>
                                    <div class="side-menu__title">Rincian Perpaket</div>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li class="side-nav__devider my-6"></li>
            <li>
                <a href="javascript:;" class="side-menu">
                    <div class="side-menu__icon"> <i data-lucide="file-text"></i> </div>
                    <div class="side-menu__title"> Laporan
                        <div class="side-menu__sub-icon "> <i data-lucide="chevron-down"></i> </div>
                    </div>
                </a>
                <ul class="">
                    <li>
                        <a href="#" class="side-menu">
                            <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                            <div class="side-menu__title"> Pembelian Barang </div>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="side-menu">
                            <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                            <div class="side-menu__title"> Data Barang </div>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="side-menu">
                            <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                            <div class="side-menu__title"> QTY Paket </div>
                        </a>
                    </li>
                </ul>
            </li>
            @hasrole('super_admin|admin')
                <li>
                    <a href="javascript:;" class="side-menu @if (Route::is('user*') ||
                            Route::is('catalog*') ||
                            Route::is('package*') ||
                            Route::is('product*') ||
                            Route::is('sub-agent*') ||
                            Route::is('supplier*') ||
                            Route::is('getAdministration')) side-menu--active @endif">
                        <div class="side-menu__icon"> <i data-lucide="box"></i> </div>
                        <div class="side-menu__title">
                            Master
                            <div class="side-menu__sub-icon "> <i data-lucide="chevron-down"></i> </div>
                        </div>
                    </a>
                    <ul class="@if (Route::is('user*') ||
                            Route::is('catalog*') ||
                            Route::is('package*') ||
                            Route::is('product*') ||
                            Route::is('sub-agent*') ||
                            Route::is('supplier*') ||
                            Route::is('getAdministration')) side-menu__sub-open @endif">
                        <li>
                            <a href="{{ route('product.index') }}"
                                class="side-menu {{ Route::is('product*') ? 'side-menu--active' : '' }}">
                                <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                                <div class="side-menu__title"> Produk </div>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('package.index') }}"
                                class="side-menu {{ Route::is('package*') ? 'side-menu--active' : '' }}">
                                <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                                <div class="side-menu__title"> Paket </div>
                            </a>
                        </li>
                        {{-- <li>
                            <a href="{{ route('catalog.index') }}"
                                class="side-menu {{ Route::is('catalog*') ? 'side-menu--active' : '' }}">
                                <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                                <div class="side-menu__title"> Katalog </div>
                            </a>
                        </li> --}}
                        <li>
                            <a href="{{ route('supplier.index') }}"
                                class="side-menu {{ Route::is('supplier*') ? 'side-menu--active' : '' }}">
                                <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                                <div class="side-menu__title"> Supplier </div>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('sub-agent.index') }}"
                                class="side-menu {{ Route::is('sub-agent*') ? 'side-menu--active' : '' }}">
                                <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                                <div class="side-menu__title"> Sub Agen </div>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('user.index') }}"
                                class="side-menu @if (Route::is('user*') || Route::is('getAdministration')) side-menu--active @endif">
                                <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                                <div class="side-menu__title"> Users </div>
                            </a>
                        </li>
                    </ul>
                </li>
            @endhasrole
        </ul>
    @endhasrole


    @hasrole('agent')
        <ul>
            @if (auth()->user()->active == 0)
                @if (auth()->user()->administration == null)
                    <li>
                        <a href="{{ route('nonactive') }}"
                            class="side-menu {{ Route::is('nonactive') ? 'side-menu--active' : '' }}">
                            <div class="side-menu__icon"> <i data-lucide="home"></i> </div>
                            <div class="side-menu__title">
                                Dashboard
                            </div>
                        </a>
                    </li>
                @else
                    <li>
                        <a href="{{ route('waiting') }}"
                            class="side-menu {{ Route::is('waiting') ? 'side-menu--active' : '' }}">
                            <div class="side-menu__icon"> <i data-lucide="home"></i> </div>
                            <div class="side-menu__title">
                                Dashboard
                            </div>
                        </a>
                    </li>
                @endif
            @else
                <li>
                    <a href="{{ route('dashboard-agent') }}"
                        class="side-menu {{ Route::is('dashboard-agent') ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"> <i data-lucide="home"></i> </div>
                        <div class="side-menu__title">
                            Dashboard
                        </div>
                    </a>
                </li>
                <li>
                    <a href="{{route('order.index')}}" class="side-menu">
                        <div class="side-menu__icon"> <i data-lucide="shopping-bag"></i> </div>
                        <div class="side-menu__title">
                            Transaksi
                        </div>
                    </a>
                </li>
                <li class="side-nav__devider my-6"></li>
                <li>
                    <a href="javascript:;" class="side-menu">
                        <div class="side-menu__icon"> <i data-lucide="file-text"></i> </div>
                        <div class="side-menu__title"> Laporan </div>
                        <div class="side-menu__sub-icon "> <i data-lucide="chevron-down"></i> </div>
                    </a>
                    <ul class="">
                        <li>
                            <a href="#" class="side-menu">
                                <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                                <div class="side-menu__title"> Jumlah Paket </div>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="side-menu">
                                <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                                <div class="side-menu__title"> Setoran </div>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="{{ route('sub-agent.index') }}"
                        class="side-menu {{ Route::is('sub-agent*') ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"> <i data-lucide="users"></i> </div>
                        <div class="side-menu__title">
                            Sub Agen
                        </div>
                    </a>
                </li>
            @endif
        </ul>
    @endhasrole
</nav>
<!-- END: Side Menu -->
