<!-- BEGIN: Mobile Menu -->
    <div class="mobile-menu md:hidden">
        <div class="mobile-menu-bar">
            <a href="" class="flex mr-auto">
                <img alt="Smart WFC" class="w-6" src="{{ asset('assets/cms/images/logo.svg') }}">
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
                    <a href="{{ route('dashboard-admin') }}" class="menu {{ Route::is('dashboard-admin') ? 'menu--active' : '' }}">
                        <div class="menu__icon"> <i data-lucide="home"></i> </div>
                        <div class="menu__title"> Dashboard </div>
                    </a>
                </li>
                <li>
                    <a href="javascript:;" class="menu">
                        <div class="menu__icon"> <i data-lucide="shopping-bag"></i> </div>
                        <div class="menu__title"> Transaksi <i data-lucide="chevron-down" class="menu__sub-icon "></i>
                        </div>
                    </a>
                    <ul class="">
                        <li>
                            <a href="#" class="menu">
                                <div class="menu__icon"> <i data-lucide="activity"></i> </div>
                                <div class="menu__title"> Pengisian Paket </div>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="menu">
                                <div class="menu__icon"> <i data-lucide="activity"></i> </div>
                                <div class="menu__title"> Penjualan Paket </div>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="menu">
                                <div class="menu__icon"> <i data-lucide="activity"></i> </div>
                                <div class="menu__title"> Pendistribusian </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:;" class="menu">
                                <div class="menu__icon"> <i data-lucide="activity"></i> </div>
                                <div class="menu__title"> Setoran <i data-lucide="chevron-down"
                                        class="menu__sub-icon "></i> </div>
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
                        </li>
                    </ul>
                </li>
                <li class="menu__devider my-6"></li>
                <li>
                    <a href="javascript:;" class="menu">
                        <div class="menu__icon"> <i data-lucide="file-text"></i> </div>
                        <div class="menu__title"> Laporan <i data-lucide="chevron-down" class="menu__sub-icon "></i></div>
                    </a>
                    <ul class="">
                        <li>
                            <a href="#" class="menu menu--active">
                                <div class="menu__icon"> <i data-lucide="activity"></i> </div>
                                <div class="menu__title"> Pembelian Barang </div>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="menu menu--active">
                                <div class="menu__icon"> <i data-lucide="activity"></i> </div>
                                <div class="menu__title"> Data Barang </div>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="menu menu--active">
                                <div class="menu__icon"> <i data-lucide="activity"></i> </div>
                                <div class="menu__title"> QTY Paket </div>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="javascript:;" class="menu {{ Route::is('user*') ? 'menu--active' : '' }}">
                        <div class="menu__icon"> <i data-lucide="box"></i> </div>
                        <div class="menu__title"> Master <i data-lucide="chevron-down" class="menu__sub-icon "></i>
                        </div>
                    </a>
                    <ul class=" {{ Route::is('user*') ? 'menu__sub-open' : '' }}">
                        <li>
                            <a href="#" class="menu menu--active">
                                <div class="menu__icon"> <i data-lucide="activity"></i> </div>
                                <div class="menu__title"> Supplier </div>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="menu menu--active">
                                <div class="menu__icon"> <i data-lucide="activity"></i> </div>
                                <div class="menu__title"> Barang </div>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="menu menu--active">
                                <div class="menu__icon"> <i data-lucide="activity"></i> </div>
                                <div class="menu__title"> Paket </div>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="menu menu--active">
                                <div class="menu__icon"> <i data-lucide="activity"></i> </div>
                                <div class="menu__title"> Sub Agen </div>
                            </a>
                        </li>
                        @hasrole('super_admin|admin')
                        <li>
                            <a href="{{ route('user.index') }}" class="menu {{ Route::is('user*') ? 'menu--active' : '' }}">
                                <div class="menu__icon"> <i data-lucide="activity"></i> </div>
                                <div class="menu__title"> Users </div>
                            </a>
                        </li>
                        @endhasrole
                    </ul>
                </li>
            </ul>
        </div>
        @endhasrole

        @hasrole('agent')
        <div class="scrollable">
            <a href="javascript:;" class="mobile-menu-toggler"> <i data-lucide="x-circle"
                    class="w-8 h-8 text-white transform -rotate-90"></i> </a>
            <ul class="scrollable__content py-2">
                <li>
                    <a href="{{ route('dashboard-agent') }}" class="menu {{ Route::is('dashboard-agent') ? 'menu--active' : '' }}">
                        <div class="menu__icon"> <i data-lucide="home"></i> </div>
                        <div class="menu__title"> Dashboard </div>
                    </a>
                </li>
                <li>
                    <a href="#" class="menu">
                        <div class="menu__icon"> <i data-lucide="shopping-bag"></i> </div>
                        <div class="menu__title"> Transaksi 
                        </div>
                    </a>
                </li>
                <li class="menu__devider my-6"></li>
                <li>
                    <a href="javascript:;" class="menu">
                        <div class="menu__icon"> <i data-lucide="file-text"></i> </div>
                        <div class="menu__title"> Laporan <i data-lucide="chevron-down" class="menu__sub-icon "></i></div>
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
                    <a href="#" class="menu">
                        <div class="menu__icon"> <i data-lucide="users"></i> </div>
                        <div class="menu__title"> Sub Agen </div>
                    </a>
                </li>
            </ul>
        </div>
        @endhasrole
    </div>
    <!-- END: Mobile Menu -->