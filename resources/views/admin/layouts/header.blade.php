@php
    $paPending = App\Models\Order::where('order_type','customer_order')->where('order_status','pending')->count();
    $paAdvance = App\Models\Order::where('order_type','customer_order')->where('advance_status','pending')->count();
    $paFb = App\Models\Order::where('order_type','customer_order')->where('fb_post_status','pending')->count();
@endphp
 <!-- BEGIN: Header-->
 <nav class="header-navbar navbar-expand-lg navbar navbar-with-menu fixed-top navbar-semi-dark">
   <div class="navbar-wrapper">
     <div class="navbar-header">
       <ul class="nav navbar-nav flex-row">
         <li class="nav-item mobile-menu d-lg-none mr-auto">
           <a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i class="fas fa-bars"></i></a>
         </li>
         <li class="nav-item mr-auto">
          <a class="navbar-brand" href="{{route('admin.dashboard')}}" style="display: flex; align-items: center;">
            <img class="brand-logo" src="{{asset(general()->favicon())}}" alt="{{general()->title}}" />
            <h5 class="brand-text" style="margin: 0;">{{general()->title ?: 'Crovio'}}<small>Admin Panel</small></h5>
          </a>
         </li>
         <li class="nav-item d-none d-lg-block nav-toggle">
           <a class="nav-link modern-nav-toggle pr-0" data-toggle="collapse" title="Toggle sidebar"><i class="fas fa-bars"></i></a>
         </li>
       </ul>
     </div>
     <div class="navbar-container content">
       <div class="collapse navbar-collapse" id="navbar-mobile">
         <ul class="nav navbar-nav mr-auto float-left">
           <li class="nav-item d-none d-md-block">
             <a class="nav-link nav-link-expand" href="#" title="Full screen"><i class="fas fa-expand"></i></a>
           </li>
           <li class="nav-item d-none d-lg-block">
             <form class="pa-search" action="{{route('admin.orders')}}" method="get">
               <i class="fas fa-search"></i>
               <input type="text" name="search" value="{{Request::is('admin/orders*') ? request('search') : ''}}" placeholder="Search orders by invoice, mobile or email..." autocomplete="off" />
             </form>
           </li>
         </ul>
         <ul class="nav navbar-nav float-right">
           <li class="nav-item d-none d-lg-block">
             <div class="pa-quick">
               <a href="{{route('admin.orders','pending')}}" class="pa-chip" title="Pending orders">
                 <i class="fas fa-shopping-bag"></i> Pending
                 @if($paPending)<span class="pa-count">{{$paPending}}</span>@endif
               </a>
               @if($paAdvance)
               <a href="{{route('admin.orders','advance-pending')}}" class="pa-chip" title="Advance payments to verify">
                 <i class="fas fa-qrcode"></i> Verify
                 <span class="pa-count" style="background: var(--pa-warning);">{{$paAdvance}}</span>
               </a>
               @endif
               @if($paFb)
               <a href="{{route('admin.orders','fb-pending')}}" class="pa-chip" title="Facebook posts to review">
                 <i class="fab fa-facebook"></i> FB
                 <span class="pa-count" style="background: #1877f2;">{{$paFb}}</span>
               </a>
               @endif
               <a href="{{route('index')}}" target="_blank" class="pa-chip" title="Open website">
                 <i class="fas fa-external-link-alt"></i> Visit Site
               </a>
             </div>
           </li>

           <li class="dropdown dropdown-user nav-item">
             <a class="dropdown-toggle nav-link dropdown-user-link" href="javascript:void(0)" data-toggle="dropdown">
               <div class="avatar">
                 <img src="{{asset(Auth::user()->image())}}" alt="avatar" />
               </div>
               <span class="d-none d-sm-block">
                 <span class="user-name">{{Str::limit(Auth::user()->name,18)}}</span>
                 <span class="user-role">Administrator</span>
               </span>
             </a>
             <div class="dropdown-menu dropdown-menu-right" style="min-width: 240px;">
               <div class="pa-dropdown-head">
                 <b>{{Auth::user()->name}}</b>
                 <span>{{Auth::user()->email ?: Auth::user()->mobile}}</span>
               </div>
               <div class="dropdown-divider"></div>
               <a class="dropdown-item" href="{{route('admin.myProfile')}}"><i class="fas fa-user"></i> My Profile</a>
               <a class="dropdown-item" href="{{route('customer.dashboard')}}"><i class="fas fa-th-large"></i> Customer Dashboard</a>
               <a class="dropdown-item" href="{{route('index')}}" target="_blank"><i class="fas fa-store"></i> Visit Website</a>
               <div class="dropdown-divider"></div>
               <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" style="color: var(--pa-danger);">
                 <i class="fas fa-sign-out-alt" style="color: var(--pa-danger);"></i> Logout
               </a>
               <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                 @csrf
               </form>
             </div>
           </li>
         </ul>
       </div>
     </div>
   </div>
 </nav>
 <!-- END: Header-->
