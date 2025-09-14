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
                <a href="javascript:;" class="side-menu @if (Route::is('order*') || Route::is('distribution*')) side-menu--active @endif">
                    <div class="side-menu__icon"> <i data-lucide="shopping-bag"></i> </div>
                    <div class="side-menu__title">
                        Transaksi
                        <div class="side-menu__sub-icon "> <i data-lucide="chevron-down"></i> </div>
                    </div>
                </a>
                <ul class="@if (Route::is('order*') || Route::is('distribution*') || Route::is('payment*')) side-menu__sub-open @endif">
                    <li>
                        <a href="{{ route('order.index') }}"
                            class="side-menu {{ Route::is('order*') ? 'side-menu--active' : '' }}">
                            <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                            <div class="side-menu__title"> Pesanan </div>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('payment.index') }}"
                            class="side-menu {{ Route::is('payment*') ? 'side-menu--active' : '' }}">
                            <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                            <div class="side-menu__title"> Pembayaran </div>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('distribution.index') }}"
                            class="side-menu {{ Route::is('distribution*') ? 'side-menu--active' : '' }}">
                            <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                            <div class="side-menu__title"> Pendistribusian </div>
                        </a>
                    </li>
                </ul>
            </li>
            {{-- <li class="side-nav__devider my-4"></li> --}}
            <li>
                <a href="javascript:;" class="side-menu @if (Route::is('totalDeposit') ||
                        Route::is('rproductDetail') ||
                        Route::is('ragentOrder') ||
                        Route::is('instalment') ||
                        Route::is('daily') ||
                        Route::is('requirement')) side-menu--active @endif">
                    <div class="side-menu__icon"> <i data-lucide="file-text"></i> </div>
                    <div class="side-menu__title"> Laporan
                        <div class="side-menu__sub-icon "> <i data-lucide="chevron-down"></i> </div>
                    </div>
                </a>
                <ul class="@if (Route::is('totalDeposit') ||
                        Route::is('rproductDetail') ||
                        Route::is('ragentOrder') ||
                        Route::is('instalment') ||
                        Route::is('daily') ||
                        Route::is('requirement')) side-menu__sub-open @endif">
                    <li>
                        <a href="{{ route('daily') }}"
                            class="side-menu {{ Route::is('daily') ? 'side-menu--active' : '' }}">
                            <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                            <div class="side-menu__title"> Harian </div>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('totalDeposit') }}"
                            class="side-menu {{ Route::is('totalDeposit') ? 'side-menu--active' : '' }}">
                            <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                            <div class="side-menu__title"> Total Setoran </div>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('rproductDetail') }}"
                            class="side-menu {{ Route::is('rproductDetail') ? 'side-menu--active' : '' }}">
                            <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                            <div class="side-menu__title"> Perpaket </div>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('ragentOrder') }}"
                            class="side-menu {{ Route::is('ragentOrder') ? 'side-menu--active' : '' }}">
                            <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                            <div class="side-menu__title"> Pesanan Agen </div>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('instalment') }}"
                            class="side-menu {{ Route::is('instalment') ? 'side-menu--active' : '' }}">
                            <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                            <div class="side-menu__title"> Cicilan </div>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('requirement') }}"
                            class="side-menu {{ Route::is('requirement') ? 'side-menu--active' : '' }}">
                            <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                            <div class="side-menu__title"> Sub Produk </div>
                        </a>
                    </li>
                </ul>
            </li>
            @hasrole('finance_admin|super_admin')
                <li>
                    <a href="javascript:;" class="side-menu @if (Route::is('income*') || Route::is('spending*') || Route::is('type-spending.index')) side-menu--active @endif">
                        <div class="side-menu__icon"> <i data-lucide="dollar-sign"></i> </div>
                        <div class="side-menu__title">
                            Keuangan
                            <div class="side-menu__sub-icon "> <i data-lucide="chevron-down"></i> </div>
                        </div>
                    </a>
                    <ul class="@if (Route::is('cash-flow*') || Route::is('income*') || Route::is('loan*') || Route::is('spending*') || Route::is('type-spending.index')) side-menu__sub-open @endif">
                        <li>
                            <a href="{{ route('cash-flow.index') }}"
                                class="side-menu {{ Route::is('cash-flow*') ? 'side-menu--active' : '' }}">
                                <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                                <div class="side-menu__title"> Cash Flow </div>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('income.index') }}"
                                class="side-menu {{ Route::is('income*') ? 'side-menu--active' : '' }}">
                                <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                                <div class="side-menu__title"> Pemasukan </div>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('loan.index') }}"
                                class="side-menu {{ Route::is('loan*') ? 'side-menu--active' : '' }}">
                                <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                                <div class="side-menu__title"> Pinjaman/Piutang </div>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('spending.index') }}"
                                class="side-menu {{ Route::is('spending*') ? 'side-menu--active' : '' }}">
                                <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                                <div class="side-menu__title"> Pengeluaran </div>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('type-spending.index') }}"
                                class="side-menu {{ Route::is('type-spending.index') ? 'side-menu--active' : '' }}">
                                <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                                <div class="side-menu__title"> Akun </div>
                            </a>
                        </li>
                    </ul>
                </li>
            @endhasrole
            @hasrole('super_admin|admin')
                <li>
                    <a href="javascript:;" class="side-menu @if (Route::is('user*') ||
                            Route::is('options.index') ||
                            Route::is('package*') ||
                            Route::is('product*') ||
                            Route::is('sub-product*') ||
                            Route::is('sub-agent*') ||
                            Route::is('supplier*') ||
                            Route::is('bank-owner*') ||
                            Route::is('getAdministration')) side-menu--active @endif">
                        <div class="side-menu__icon"> <i data-lucide="box"></i> </div>
                        <div class="side-menu__title">
                            Master
                            <div class="side-menu__sub-icon "> <i data-lucide="chevron-down"></i> </div>
                        </div>
                    </a>
                    <ul class="@if (Route::is('user*') ||
                            Route::is('options.index') ||
                            Route::is('package*') ||
                            Route::is('product*') ||
                            Route::is('sub-product*') ||
                            Route::is('sub-agent*') ||
                            Route::is('supplier*') ||
                            Route::is('bank-owner*') ||
                            Route::is('getAdministration')) side-menu__sub-open @endif">
                        <li>
                            <a href="{{ route('sub-product.index') }}"
                                class="side-menu {{ Route::is('sub-product*') ? 'side-menu--active' : '' }}">
                                <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                                <div class="side-menu__title"> Sub Produk </div>
                            </a>
                        </li>
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
                        <li>
                            <a href="{{ route('options.index') }}"
                                class="side-menu {{ Route::is('options.index') ? 'side-menu--active' : '' }}">
                                <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                                <div class="side-menu__title"> Periode </div>
                            </a>
                        </li>
                        @hasrole('super_admin|admin|finance_admin')
                            <li>
                                <a href="{{ route('bank-owner.index') }}"
                                    class="side-menu {{ Route::is('bank-owner*') ? 'side-menu--active' : '' }}">
                                    <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                                    <div class="side-menu__title"> Bank </div>
                                </a>
                            </li>
                        @endhasrole
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
                @hasrole('super_admin')
                    <li>
                        <a href="javascript:;" class="side-menu @if (Route::is('landingpage*')) side-menu--active @endif">
                            <div class="side-menu__icon"> <i data-lucide="settings"></i> </div>
                            <div class="side-menu__title"> Landing Page
                                <div class="side-menu__sub-icon "> <i data-lucide="chevron-down"></i> </div>
                            </div>
                        </a>
                        <ul class="@if (Route::is('landingpage*')) side-menu__sub-open @endif">
                            <li>
                                <a href="{{ route('landingpage.header') }}"
                                    class="side-menu {{ Route::is('landingpage.header') ? 'side-menu--active' : '' }}">
                                    <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                                    <div class="side-menu__title"> Header </div>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('landingpage.profile') }}"
                                    class="side-menu {{ Route::is('landingpage.profile') ? 'side-menu--active' : '' }}">
                                    <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                                    <div class="side-menu__title"> Profile </div>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('landingpage.detailProfile') }}"
                                    class="side-menu {{ Route::is('landingpage.detailProfile') ? 'side-menu--active' : '' }}">
                                    <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                                    <div class="side-menu__title"> Detail Profile </div>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('landingpage.gallery') }}"
                                    class="side-menu {{ Route::is('landingpage.gallery') ? 'side-menu--active' : '' }}">
                                    <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                                    <div class="side-menu__title"> Galeri </div>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('landingpage.review') }}"
                                    class="side-menu {{ Route::is('landingpage.review') ? 'side-menu--active' : '' }}">
                                    <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                                    <div class="side-menu__title"> Review </div>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('landingpage.contact') }}"
                                    class="side-menu {{ Route::is('landingpage.contact') ? 'side-menu--active' : '' }}">
                                    <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                                    <div class="side-menu__title"> Contact </div>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endhasrole
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
                    <a href="javascript:;" class="side-menu @if (Route::is('order*') || Route::is('payment-agent*')) side-menu--active @endif">
                        <div class="side-menu__icon"> <i data-lucide="shopping-bag"></i> </div>
                        <div class="side-menu__title">
                            Transaksi
                            <div class="side-menu__sub-icon "> <i data-lucide="chevron-down"></i> </div>
                        </div>
                    </a>
                    <ul class="@if (Route::is('order*') || Route::is('payment*')) side-menu__sub-open @endif">
                        <li>
                            <a href="{{ route('order.index') }}"
                                class="side-menu {{ Route::is('order*') ? 'side-menu--active' : '' }}">
                                <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                                <div class="side-menu__title"> Pesanan </div>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('payment-agent.index') }}"
                                class="side-menu {{ Route::is('payment-agent*') ? 'side-menu--active' : '' }}">
                                <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                                <div class="side-menu__title"> Pembayaran </div>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="{{ route('reviews.index') }}"
                        class="side-menu {{ Route::is('reviews*') ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"> <i data-lucide="message-square"></i> </div>
                        <div class="side-menu__title">
                            Ulasan
                        </div>
                    </a>
                </li>
                {{-- <li class="side-nav__devider my-6"></li>
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
                </li> --}}
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
