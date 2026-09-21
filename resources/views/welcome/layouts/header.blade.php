
<header class="sticky-header">
    <!--Middle Header Part Start-->
    <div class="container">
        <div class="middle-header">
            <div class="row">
                <div class="col-9 col-md-3">
                    <div class="destopmodehide">
                        <span class="moble-menus-models"><i class="fa fa-bars"></i></span>
                    </div>
                    <div class="logo">
                        <a href="{{route('index')}}"><img src="{{asset(general()->logo())}}" alt="{{asset(general()->title)}}" />
                       
                        </a>
                    </div>
                </div>
                <div class="col-md-3 mobilemodehide">
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
                
                <div class="col-md-3">
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

                <div class="col-3 col-md-3">
                    <ul class="cartnavbar mobilemodehide">
                        <!-- <li>-->
                        <!--    <a href="{{route('login')}}"><i class="fa fa-user-o" aria-hidden="true"></i></a>-->
                        <!--</li>-->
                      
                        <li class="HeaderCartItems">
                            @include(App\Models\General::first()->theme.'.carts.includes.headerCartBox')
                        </li>
                        
                        <li>
                            <a style="" href="{{route('myWishlist')}}"><i class="fa fa-heart-o" aria-hidden="true"></i></i>
                            @if(isset($wlCount) && $wlCount > 0)
                               <span class="cartCount wlcounter">{{$wlCount}}</span>
                            @endif
                            <!--@if(isset($wlCount) && $wlCount > 0) <span class="cartCount wlcounter">@if(isset($wlCount) && $wlCount > 0)  $wlCount  @endif</span>@endif-->
                                                           
                                                       
                            </a>
                        </li>
                        
                        <li>
                            @if(Auth::check())
                            <a href="{{route('customer.dashboard')}}"><i class="fa fa-user-o" ></i> Account</a>
                            @else
                            <a href="{{route('login')}}"><i class="fa fa-user-o" ></i> Login</a>
                            @endif
                        </li>
                       
                    </ul>
                    
                    <span class="HeaderCartItems2">
                    
                    @include(App\Models\General::first()->theme.'.carts.includes.headerCartBox2')
                    </span>
                </div>
            </div>
        </div>
    </div>
    <!--Middle Header Part End-->
</header>

<div class="mobileHeader">
    <div class="container">
        <div class="row">
            <div class="col-1">
                <div class="barIcon">
                    <span class="moble-menus-models"><i class="fa fa-bars"></i></span>
                </div>
            </div>
            <div class="col-9">
                <div class="mobileLogo">
                    <a href="{{route('index')}}">
                        <img src="{{asset(general()->logo())}}" alt="{{asset(general()->title)}}" style="width:120px;" />
                    </a>
                </div>
            </div>
            <div class="col-2">
                <div class="mobileCartIcon">
                    <span class="HeaderCartItems2 extra">
                    
                    @include(App\Models\General::first()->theme.'.carts.includes.headerCartBox2')
                    </span>
                </div>
            </div>
        </div>
        <div class="searchRow">
        <div class="row">
            <div class="col-md-12" style="background: #000;padding: 10px 0;">
                <div class="mobileSearch search-section" style="margin-top: 0;">
                    <form action="{{route('productSearch')}}">
                        <div class="input-group">
                            <input type="text" class="form-control AjaxSearchProduct"
                            @if(Request::is('product-search*'))
                            value="{{$r->search?:''}}" 
                            @endif
                            name="search" data-url="{{route('productSearch')}}" placeholder="Search Here" />
                            <div class="input-group-append" style="display:block !important;">
                                <button type="submit" class="input-group-text search-button" style="padding: 18px;background: #f2f2f2;"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </form>
                    <div class="SearchResultDiv"></div>
                </div>
            </div>
        </div>
            
        </div>
    </div>
</div>

<!--Mobile Menu Side Models Start-->
<div class="mobile-menu-side-modals side-modals side-modalsBar right">
    <a href="javascript:void(0)" class="overlay side-modals-close"></a>
    <div class="cart-inner">
        <div class="search-section" style="width: 90%; margin: 20px auto 0px;">
            <form action="{{route('productSearch')}}">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search Here" name="search" />
                    <div class="input-group-append">
                        <button type="submit" class="input-group-text search-button"><i class="fa fa-search" ></i></button>
                    </div>
                </div>
            </form>
        </div>

        <div class="cart_top">
            <div class="row" style="margin: 0;">
                <div class="col-10" style="padding: 0;">
                    <h3 style="margin: 0; font-size: 20px;font-family: sans-serif; font-weight: 600;">{{general()->title}}</h3>
                </div>
                <div class="col-2" style="padding: 0; text-align: center;">
                    <a href="javascript:void(0)" class="side-modals-close" style="color: gray; margin-left: 13px;">
                        <i class="fa fa-times" aria-hidden="true"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="cart_media">
            <div class="primarymenu" style="display: block;">
                <div class="multi-lavel">
                    <ul id="menu" class="metismenu">
                        
                        <li><a href="{{route('index')}}" aria-expanded="false">Home</a></li>
                        @if(menu('Header Menus'))
                        @foreach(menu('Header Menus')->subMenus as $menu)
                        @if($menu->subMenus->count() > 0)
                        <li>
                            <a href="{{asset($menu->munuLink())}}" aria-expanded="false">{{$menu->munuName()}}</a>
                            <span id="downarrow" class="has-arrow" aria-expanded="false" style="right: 20px; height: 25px; width: 25px; color: #000; cursor: pointer; position: absolute;"> </span>

                            <ul id="submenuone" style="padding: 0; background: #fff;" class="mm-collapse">
                                @foreach($menu->subMenus as $menu)
                                <li>
                                    <a href="{{asset($menu->munuLink())}}">{{$menu->munuName()}}</a>
                                </li>
                                @endforeach
                            </ul>
                        </li>
                        @else

                        <li class="nav-item {{asset($menu->munuLink())==url()->current()?'active':''}}">
                            <a class="nav-link" href="{{asset($menu->munuLink())}}" target="{{$menu->target?'_blank':'_self'}}">{{$menu->munuName()}}</a>
                        </li>
                        @endif
                        
                        @endforeach
                        @endif
                        <li>
                            <a  href="{{route('myWishlist')}}">WISHLIST<i class="fa ml-1 fa-heart" aria-hidden="true"></i></a>
                        </li>
                        @if(Auth::check())
                         @if(Auth::user()->admin)
                        <li>
                            <a href="{{route('admin.dashboard')}}" style="font-weight: bold;color: #04049b;">Admin Dashboard</a>
                        </li>
                        @endif
                        <li>
                            <a href="{{route('customer.dashboard')}}" aria-expanded="false">My Account</a></li>
                        <li>
                            <a href="{{route('customer.myOrders')}}">My Orders</a>
                        </li>
                        <li>
                            <a href="{{route('customer.myReviews')}}">My Review</a>
                        </li>
                    
                        <li>
                            <a href="{{ route('logout') }}" onclick="event.preventDefault();
                            document.getElementById('logout-form-sidebar').submit();">Log Out</a>
                                    <form id="logout-form-sidebar" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                        </li>
                        @else
                        <li>
                            <a href="{{route('login')}}" aria-expanded="false">Login</a></li>
                            <li>
                            <a href="{{route('register')}}" aria-expanded="false">Register</a></li>
                        @endif
                        
                    </ul>
                    <ul class="socialMenus">
                        
                        
                        @if(general()->facebook_link)
                            <li>
                                <a href="{{general()->facebook_link}}" ><i class="fa fa-facebook" aria-hidden="true"></i></a>
                            </li>
                            @endif
                            
                            @if(general()->twitter_link)
                            <li>
                                <a href="{{general()->twitter_link}}" ><i class="fa fa-twitter" aria-hidden="true"></i></a>
                            </li>
                            @endif
                            
                            @if(general()->linkedin_link)
                            <li>
                                <a href="{{general()->linkedin_link}}"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
                            </li>
                            @endif
                            
                            @if(general()->instagram_link)
                            <li>
                                <a href="{{general()->instagram_link}}" ><i class="fa fa-instagram" aria-hidden="true"></i></a>
                            </li>
                            @endif 
                            
                            @if(general()->whatsapp_link)
                            <li>
                                <a href="{{general()->whatsapp_link}}" ><i class="fa fa-whatsapp" aria-hidden="true"></i></a>
                            </li>
                            @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
