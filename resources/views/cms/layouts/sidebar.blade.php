<!-- BEGIN: Side Menu -->
<nav class="side-nav">
    @hasrole('super_admin|admin|finance_admin')
    <ul>
        <li>
            <a href="{{ route('dashboard-admin') }}" class="side-menu {{ Route::is('dashboard-admin') ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon"> <i data-lucide="home"></i> </div>
                <div class="side-menu__title">
                    Dashboard
                </div>
            </a>
        </li>
        <li>
            <a href="javascript:;" class="side-menu">
                <div class="side-menu__icon"> <i data-lucide="shopping-bag"></i> </div>
                <div class="side-menu__title">
                    Transaksi
                    <div class="side-menu__sub-icon "> <i data-lucide="chevron-down"></i> </div>
                </div>
            </a>
            <ul class="">
                <li>
                    <a href="#" class="side-menu">
                        <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                        <div class="side-menu__title"> Pengisian Paket </div>
                    </a>
                </li>
                <li>
                    <a href="#" class="side-menu">
                        <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                        <div class="side-menu__title"> Penjualan Paket </div>
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
                        <div class="side-menu__title"> Setoran <i data-lucide="chevron-down" class="side-menu__sub-icon "></i>
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
                    <div class="side-menu__sub-icon "> <i data-lucide="chevron-down"></i> </div></div>
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
        <li>
            <a href="javascript:;" class="side-menu {{ Route::is('user*') ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon"> <i data-lucide="box"></i> </div>
                <div class="side-menu__title">
                    Master
                    <div class="side-menu__sub-icon "> <i data-lucide="chevron-down"></i> </div>
                </div>
            </a>
            <ul class="{{ Route::is('user*') ? 'side-menu__sub-open' : '' }}">
                <li>
                    <a href="#" class="side-menu">
                        <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                        <div class="side-menu__title"> Supplier </div>
                    </a>
                </li>
                <li>
                    <a href="#" class="side-menu">
                        <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                        <div class="side-menu__title"> Barang </div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('catalog.index') }}" class="side-menu">
                        <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                        <div class="side-menu__title"> Katalog </div>
                    </a>
                </li>
                <li>
                    <a href="#" class="side-menu">
                        <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                        <div class="side-menu__title"> Sub Agen </div>
                    </a>
                </li>
                @hasrole('super_admin|admin')
                <li>
                    <a href="{{ route('user.index') }}" class="side-menu {{ Route::is('user*') ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                        <div class="side-menu__title"> Users </div>
                    </a>
                </li>
                @endhasrole
            </ul>
        </li>
    </ul>
    @endhasrole


    @hasrole('agent')
    <ul>
        <li>
            <a href="{{ route('dashboard-agent') }}" class="side-menu {{ Route::is('dashboard-agent') ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon"> <i data-lucide="home"></i> </div>
                <div class="side-menu__title">
                    Dashboard
                </div>
            </a>
        </li>
        <li>
            <a href="javascript:;" class="side-menu">
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
            <a href="javascript:;" class="side-menu">
                <div class="side-menu__icon"> <i data-lucide="users"></i> </div>
                <div class="side-menu__title">
                    Sub Agen
                </div>
            </a>
        </li>
    </ul>
    @endhasrole
</nav>
<!-- END: Side Menu -->