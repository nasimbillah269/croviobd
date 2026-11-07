
<header class="sticky-header">
    <!--Middle Header Part Start-->
    <div class="container">
        <div class="middle-header">
            <div class="row">
                <div class="col-9 col-md-3 col-xl-2">
                    <div class="destopmodehide">
                        <span class="moble-menus-models"><i class="fa fa-bars"></i></span>
                    </div>
                    <div class="logo">
                        <a href="{{route('index')}}"><img src="{{asset(general()->logo())}}" alt="{{asset(general()->title)}}" />
                       
                        </a>
                    </div>
                </div>
                <div class="col-md-3 col-xl-4 mobilemodehide">
                    <div class="search-section" style="width: 100%;">
                        <form action="{{route('productSearch')}}">
                            <div class="input-group">
                                <input type="text" class="form-control AjaxSearchProduct"
                                @if(Request::is('product-search*'))
                                value="{{$r->search?:''}}" 
                                @endif
                                name="search" data-url="{{route('productSearch')}}" placeholder="Search Here..." />
                                <div class="input-group-append">
                                    <button type="submit" class="input-group-text search-button"><i class="fa fa-search" aria-hidden="true"></i></button>
                                </div>
                            </div>
                        </form>
                        <div class="SearchResultDiv">
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 col-xl-2 mobilemodehide hdr-offer-col">
                    <a href="{{route('offerProducts')}}" class="headerOfferLink {{ request()->routeIs('offerProducts') ? 'active' : '' }}">
                        <i class="fa-solid fa-tags"></i> Offer Products
                    </a>
                    <!--<div class="navLink">-->
                    <!--    <ul>-->
                    <!--        <li>-->
                    <!--            <a href="{{route('index')}}">-->
                    <!--                শপিং-->
                    <!--            </a>-->
                    <!--        </li>-->
                    <!--        <li>-->
                    <!--            <a href="#">-->
                    <!--                অফার সমূহ-->
                    <!--            </a>-->
                    <!--        </li>-->
                    <!--    </ul>-->
                    <!--</div>-->
                </div>

                <div class="col-3 col-md-3 col-xl-4">
                    <ul class="cartnavbar mobilemodehide">
                        <!-- <li>-->
                        <!--    <a href="{{route('login')}}"><i class="fa fa-user-o" aria-hidden="true"></i></a>-->
                        <!--</li>-->
                      
                        <li class="HeaderCartItems">
                            @include(general()->theme.'.carts.includes.headerCartBox')
                        </li>

                        <li>
                            <a href="{{route('myWishlist')}}" class="headerWishlist {{ request()->routeIs('myWishlist') ? 'active' : '' }}" aria-label="My wishlist" title="My wishlist">
                                <i class="fa-regular fa-heart"></i>
                                <span class="cartCounts wlcounter" style="{{ ($wlCount ?? 0) > 0 ? '' : 'display:none;' }}">{{ $wlCount ?? 0 }}</span>
                            </a>
                        </li>
                        
                        <li>
                            @if(Auth::check())
                            <a href="{{route('customer.dashboard')}}" class="hdr-account"><i class="fa-regular fa-user"></i> <span>Account</span></a>
                            @else
                            <a href="{{route('login')}}" class="hdr-account"><i class="fa-regular fa-user"></i> <span>Login</span></a>
                            @endif
                        </li>
                       
                    </ul>
                    
                    <span class="HeaderCartItems2">
                    
                    @include(general()->theme.'.carts.includes.headerCartBox2')
                    </span>
                </div>
            </div>
        </div>
    </div>
    <!--Middle Header Part End-->
</header>

<div class="mobileHeader">
    <div class="container">
        <div class="mh-bar">
            <button type="button" class="mh-icon moble-menus-models" aria-label="Open menu"><i class="fa-solid fa-bars-staggered"></i></button>

            <div class="mobileLogo">
                <a href="{{route('index')}}">
                    <img src="{{asset(general()->logo())}}" alt="{{general()->title}}" />
                </a>
            </div>

            <div class="mh-right">
                <a href="{{route('myWishlist')}}" class="mh-icon" aria-label="My wishlist">
                    <i class="fa-regular fa-heart"></i>
                    <span class="mh-badge wlcounter" style="{{ ($wlCount ?? 0) > 0 ? '' : 'display:none;' }}">{{ $wlCount ?? 0 }}</span>
                </a>
                <div class="mobileCartIcon">
                    <span class="HeaderCartItems2 extra">
                    @include(general()->theme.'.carts.includes.headerCartBox2')
                    </span>
                </div>
            </div>
        </div>

        <div class="searchRow">
            <div class="mobileSearch search-section mh-search">
                <form action="{{route('productSearch')}}">
                    <div class="input-group">
                        <input type="text" class="form-control AjaxSearchProduct"
                        @if(Request::is('product-search*'))
                        value="{{$r->search?:''}}"
                        @endif
                        name="search" data-url="{{route('productSearch')}}" placeholder="Search products..." aria-label="Search products" />
                        <div class="input-group-append">
                            <button type="submit" class="input-group-text search-button" aria-label="Search"><i class="fa-solid fa-magnifying-glass"></i></button>
                        </div>
                    </div>
                </form>
                <div class="SearchResultDiv"></div>
            </div>
        </div>
    </div>
</div>

<!--Mobile Menu Side Models Start-->
<div class="mobile-menu-side-modals side-modals side-modalsBar right md-drawer">
    <a href="javascript:void(0)" class="overlay side-modals-close" aria-label="Close menu"></a>
    <div class="cart-inner">
        <div class="md-head">
            <div class="md-head-top">
                <a href="{{route('index')}}" class="md-logo"><img src="{{asset(general()->logo())}}" alt="{{general()->title}}"></a>
                <a href="javascript:void(0)" class="side-modals-close md-close" aria-label="Close menu"><i class="fa-solid fa-xmark"></i></a>
            </div>

            @if(Auth::check())
            <a href="{{route('customer.dashboard')}}" class="md-user">
                <img src="{{asset(Auth::user()->image())}}" alt="{{Auth::user()->name}}">
                <span>
                    <small>Welcome back</small>
                    <b>{{ Str::limit(Auth::user()->name, 22) }}</b>
                </span>
                <i class="fa-solid fa-angle-right"></i>
            </a>
            @else
            <div class="md-auth">
                <a href="{{route('login')}}" class="md-auth-login"><i class="fa-regular fa-user"></i> Login</a>
                <a href="{{route('register')}}" class="md-auth-register">Register</a>
            </div>
            @endif
        </div>

        <div class="md-body">
            <form action="{{route('productSearch')}}" class="md-search">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" name="search" placeholder="Search products..." aria-label="Search products">
            </form>

            <a href="{{route('offerProducts')}}" class="md-offer">
                <span class="md-offer-icon"><i class="fa-solid fa-tags"></i></span>
                <span><b>Offer Products</b><small>Best discounts in one place</small></span>
                <i class="fa-solid fa-angle-right"></i>
            </a>

            <p class="md-label">Menu</p>
            <div class="primarymenu" style="display: block;">
                <div class="multi-lavel">
                    <ul id="menu" class="metismenu md-nav">
                        <li class="{{ request()->routeIs('index') ? 'md-active' : '' }}"><a href="{{route('index')}}"><i class="fa-solid fa-house"></i> Home</a></li>
                        @if(menu('Header Menus'))
                        @foreach(menu('Header Menus')->subMenus as $menu)
                        @if($menu->subMenus->count() > 0)
                        <li>
                            <a href="{{asset($menu->munuLink())}}" class="has-arrow" aria-expanded="false"><i class="fa-regular fa-folder"></i> {{$menu->munuName()}}</a>
                            <ul class="mm-collapse md-sub">
                                @foreach($menu->subMenus as $sub)
                                <li><a href="{{asset($sub->munuLink())}}">{{$sub->munuName()}}</a></li>
                                @endforeach
                            </ul>
                        </li>
                        @else
                        <li class="{{ asset($menu->munuLink())==url()->current() ? 'md-active' : '' }}">
                            <a href="{{asset($menu->munuLink())}}" target="{{$menu->target?'_blank':'_self'}}"><i class="fa-regular fa-file-lines"></i> {{$menu->munuName()}}</a>
                        </li>
                        @endif
                        @endforeach
                        @endif
                        <li class="{{ request()->routeIs('myWishlist') ? 'md-active' : '' }}"><a href="{{route('myWishlist')}}"><i class="fa-regular fa-heart"></i> Wishlist</a></li>
                    </ul>
                </div>
            </div>

            @if(Auth::check())
            <p class="md-label">My Account</p>
            <ul class="md-nav">
                @if(Auth::user()->admin)
                <li><a href="{{route('admin.dashboard')}}"><i class="fa-solid fa-gauge-high"></i> Admin Dashboard</a></li>
                @endif
                <li><a href="{{route('customer.dashboard')}}"><i class="fa-solid fa-table-cells-large"></i> Dashboard</a></li>
                <li><a href="{{route('customer.myOrders')}}"><i class="fa-solid fa-bag-shopping"></i> My Orders</a></li>
                <li><a href="{{route('customer.myReviews')}}"><i class="fa-regular fa-star"></i> My Reviews</a></li>
                <li>
                    <a href="{{ route('logout') }}" class="md-logout" onclick="event.preventDefault(); document.getElementById('logout-form-sidebar').submit();"><i class="fa-solid fa-arrow-right-from-bracket"></i> Log Out</a>
                    <form id="logout-form-sidebar" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
            @endif
        </div>

        <div class="md-foot">
            @if(general()->mobile)
            <a href="tel:{{general()->mobile}}" class="md-call">
                <span class="md-call-icon"><i class="fa-solid fa-headset"></i></span>
                <span><small>Need help? Call us</small><b>{{general()->mobile}}</b></span>
            </a>
            @endif
            <div class="md-social">
                @if(general()->facebook_link)<a href="{{general()->facebook_link}}" target="_blank" rel="noopener" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>@endif
                @if(general()->instagram_link)<a href="{{general()->instagram_link}}" target="_blank" rel="noopener" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>@endif
                @if(general()->youtube_link)<a href="{{general()->youtube_link}}" target="_blank" rel="noopener" aria-label="YouTube"><i class="fa-brands fa-youtube"></i></a>@endif
                @if(general()->twitter_link)<a href="{{general()->twitter_link}}" target="_blank" rel="noopener" aria-label="X"><i class="fa-brands fa-x-twitter"></i></a>@endif
                @if(general()->linkedin_link)<a href="{{general()->linkedin_link}}" target="_blank" rel="noopener" aria-label="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a>@endif
                @if(general()->mobile)<a href="https://wa.me/88{{general()->mobile}}" target="_blank" rel="noopener" aria-label="WhatsApp"><i class="fa-brands fa-whatsapp"></i></a>@endif
            </div>
        </div>
    </div>
</div>
