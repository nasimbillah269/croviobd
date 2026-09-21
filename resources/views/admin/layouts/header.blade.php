 <!-- BEGIN: Header-->
 <nav class="header-navbar navbar-expand-lg navbar navbar-with-menu fixed-top navbar-semi-dark navbar-shadow">
   <div class="navbar-wrapper">
     <div class="navbar-header">
       <ul class="nav navbar-nav flex-row">
         <li class="nav-item mobile-menu d-lg-none mr-auto"><a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#">
          <!-- <i class="fa-2x fas fa-bars"></i> -->
          <i class="fa-2x fas fa-arrow-alt-circle-right"></i>
          </a>
          </li>
         <li class="nav-item mr-auto">
          <a class="navbar-brand" href="{{route('index')}}" style="display: flex; align-items: center;">
            <img class="brand-logo" src="{{asset(general()->favicon())}}" style="max-height:30px;background: white;border-radius: 50%;padding: 3px;" />
             <h5 class="brand-text" style="font-size: 16px; letter-spacing: 0; margin: 0;">Crovio</h5>
           </a>
           </li>
         <li class="nav-item d-none d-lg-block nav-toggle"><a class="nav-link modern-nav-toggle pr-0" data-toggle="collapse" style="    color: white;"><i class="fa-2x fas fa-bars"></i></a></li>
         <!-- <li class="nav-item d-lg-none"><a class="nav-link open-navbar-container" data-toggle="collapse" data-target="#navbar-mobile"><i class="fa fa-ellipsis-v"></i></a></li> -->
       </ul>
     </div>
     <div class="navbar-container content">
       <div class="collapse navbar-collapse" id="navbar-mobile">
         <ul class="nav navbar-nav mr-auto float-left">
           
           <li class="nav-item d-none d-md-block">
            <a class="nav-link nav-link-expand" href="#">
              <i class="fas fa-compress"></i>
            </a>
          </li>
          <li class="nav-item d-none d-md-block">
            <a class="nav-link">Quick Link</a>
          </li>
          <li class="nav-item d-none d-md-block">
            <a href="{{route('admin.orders')}}" class="nav-link" style="padding:12px;">
              <span class="btn btn-sm btn-success" style="padding: 8px 15px;border-radius: 0;"> Customer Order <i class="fa fa-shopping-cart"></i></span>
            </a>
          </li>
         </ul>
         <ul class="nav navbar-nav float-right">

           <li class="dropdown dropdown-user nav-item"><a class="dropdown-toggle nav-link dropdown-user-link" href="javascript:void(0)" data-toggle="dropdown">
               <div class="avatar avatar-online">
                <img src="{{asset(Auth::user()->image())}}" alt="avatar" /><i></i>
              </div>
               <span class="user-name">{{Str::limit(Auth::user()->name,15)}}</span></a>
               <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item" href="{{route('customer.dashboard')}}" style="min-width: 220px"><i class="fas fa-th-large"></i> My Dashboard </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="{{route('admin.myProfile')}}" style="min-width: 220px"><i class="fa fa-user"></i> My Profile </a>

                 <div class="dropdown-divider"></div>
                 
                 <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                  <i class="fas fa-power-off"></i> Logout 
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