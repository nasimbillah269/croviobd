 <!-- BEGIN: Main Menu-->
   <div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
     <div class="main-menu-content">
       <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
         <li class=" navigation-header"><span>General </span><i class="feather icon-minus" ></i>
         </li>
         <li class=" nav-item {{Request::is('admin/dashboard')? 'active' : ''}}">
          <a href="{{route('admin.dashboard')}}"><i class="fas fa-th-large"></i><span class="menu-title" >Dashboard</span></a>
         </li>
         <li class=" nav-item {{Request::is('admin/my-profile')? 'active' : ''}}">
          <a href="{{route('admin.myProfile')}}"><i class="fas fa-user-tie"></i><span class="menu-title" >My Profile</span></a>
         </li>
         <!--Permission Check List Menus Start-->

         @if($roles = myrole())

         @if(
         isset(json_decode($roles->permission, true)['posts']['list']) || 
         isset(json_decode($roles->permission, true)['postsCtg']['list']) || 
         isset(json_decode($roles->permission, true)['postsTag']['list']) || 
         isset(json_decode($roles->permission, true)['postsComment']['list']) 
         )
         <li class="nav-item {{Request::is('admin/posts*')? 'active' : ''}}">
          <a href="index.html">
          <i class="fas fa-file-alt"></i>
          <span class="menu-title" data-i18n="Dashboard">Posts </span>
          <!-- <span class="badge badge badge-primary badge-pill float-right mr-2">3 </span> -->
          </a>
           <ul class="menu-content">

             @isset(json_decode($roles->permission, true)['posts']['list'])
             <li class="
             @if( Request::is('admin/posts/categories*') || Request::is('admin/posts/tags*') || Request::is('admin/posts/comments*') )
             @else
             {{Request::is('admin/posts*')? 'active' : ''}}
             @endif
             ">
              <a class="menu-item" href="{{route('admin.posts')}}" >All Posts </a>
             </li>
             @endisset

             @isset(json_decode($roles->permission, true)['posts']['add'])
             <li><a class="menu-item" href="{{route('admin.postsCreate')}}" >New Post </a>
             </li>
             @endisset

             @isset(json_decode($roles->permission, true)['postsCtg']['list'])
             <li class="{{Request::is('admin/posts/categories*')? 'active' : ''}}">
              <a class="menu-item" href="{{route('admin.postsCategories')}}" >Categories </a>
             </li>
             @endisset

             @isset(json_decode($roles->permission, true)['postsTag']['list'])
             <li class="{{Request::is('admin/posts/tags*')? 'active' : ''}}">
              <a class="menu-item" href="{{route('admin.postsTags')}}">Tags </a>
             </li>
             @endisset

             @isset(json_decode($roles->permission, true)['postsComment']['list'])
             <li class="{{Request::is('admin/posts/comments*')? 'active' : ''}}">
              <a class="menu-item" href="{{route('admin.postsCommentsAll')}}">Comments </a>
             </li>
             @endisset
           </ul>
         </li>
         @endif

         @isset(json_decode($roles->permission, true)['pages']['list'])
          <li class="nav-item {{Request::is('admin/pages*')? 'active' : ''}}"><a href="{{route('admin.pages')}}"><i class="fas fa-copy"></i><span class="menu-title">Pages</span></a>
         </li>
         @endisset

         @isset(json_decode($roles->permission, true)['medies']['list'])
         <li class="nav-item {{Request::is('admin/medies*')? 'active' : ''}}"><a href="{{route('admin.medies')}}"><i class="fas fa-images"></i><span class="menu-title">Medias Library</span></a>
         </li>
         @endisset
        
        @isset(json_decode($roles->permission, true)['ecommerSetting']['list'])
         <li class=" navigation-header"><span>E-Commerce Unit </span><i class=" feather icon-minus" data-toggle="tooltip" data-placement="right" data-original-title="Others"></i>
         </li>
         <li class="nav-item {{Request::is('admin/ecommerce*')? 'active' : ''}}">
          <a href="#"><i class="fas fa-puzzle-piece"></i><span class="menu-title" >Ecommerce Setting </span></a>
           <ul class="menu-content">
             {{-- <li class=""><a class="menu-item" href="{{route('admin.ecommercePromotions')}}">Promotions</a></li> --}}
             <li class="{{Request::is('admin/ecommerce/setting*')? 'active' : ''}}"><a class="menu-item" href="{{route('admin.ecommerceSetting',['type'=>'general'])}}">Setting</a>
             </li>
           </ul>
         </li>
        @endisset

         @if(
         isset(json_decode($roles->permission, true)['products']['list']) ||  
         isset(json_decode($roles->permission, true)['productsCtg']['list']) 
         )
         <li class=" nav-item"><a href="#"><i class="fas fa-stream"></i><span class="menu-title" >Products </span></a>
           <ul class="menu-content">
             
            @isset(json_decode($roles->permission, true)['products']['list'])
             <li class="
             @if( Request::is('admin/products/categories*'))
             @else
             {{Request::is('admin/products*')? 'active' : ''}}
             @endif
             "><a class="menu-item" href="{{route('admin.products')}}">All Products</a>
             </li>
             @endisset

             @isset(json_decode($roles->permission, true)['products']['add'])
             <li><a class="menu-item" href="{{route('admin.productsCreate')}}" >New Product</a>
             </li>
             @endisset

              @isset(json_decode($roles->permission, true)['productsCtg']['list'])
             <li class="{{Request::is('admin/products/categories*')? 'active' : ''}}"><a class="menu-item" href="{{route('admin.productsCategories')}}">Categories</a>
             </li>
             @endisset

             <li class="{{Request::is('admin/products/tags*')? 'active' : ''}}"><a class="menu-item" href="{{route('admin.productsTags')}}">Tags</a>
             </li>

              @isset(json_decode($roles->permission, true)['productsCtg']['list'])
             <li class="{{Request::is('admin/products/attributes*')? 'active' : ''}}"><a class="menu-item" href="{{route('admin.productsAttributes')}}">Attributes</a>
             </li>
             @endisset

           </ul>
         </li>
         @endif
        
        @isset(json_decode($roles->permission, true)['stockList']['list'])
         <li class="nav-item {{Request::is('admin/stock*')? 'active' : ''}}"><a href="#"><i class="fas fa-box"></i><span class="menu-title" >Stock </span></a>
           <ul class="menu-content">
             <li class="{{Request::is('admin/stock/list*')? 'active' : ''}}"><a class="menu-item" href="{{route('admin.stockList')}}">Stock List</a>
             </li>

           </ul>
         </li>
         @endisset
         <li class=" navigation-header"><span>Order Manage Unit </span><i class=" feather icon-minus" data-toggle="tooltip" data-placement="right" data-original-title="Others"></i>
         </li>

        
        
        @isset(json_decode($roles->permission, true)['orderManagement']['list'])
          <li class="nav-item {{Request::is('admin/orders*')? 'active' : ''}}">
          <a href="#"><i class="fas fa-sitemap"></i><span class="menu-title" >Order Management</span></a>
           <ul class="menu-content">
             <li class="{{Request::is('admin/orders')? 'active' : ''}}"><a class="menu-item" href="{{route('admin.orders')}}"> Order List</a></li>
             <li class="{{Request::is('admin/orders/pending')? 'active' : ''}}"><a class="menu-item" href="{{route('admin.orders','pending')}}"> Pending Order</a></li>
             <li class="{{Request::is('admin/orders/confirmed')? 'active' : ''}}"><a class="menu-item" href="{{route('admin.orders','confirmed')}}"> Confirmed Orders</a></li>
             <li class="{{Request::is('admin/orders/shipped')? 'active' : ''}}"><a class="menu-item" href="{{route('admin.orders','shipped')}}"> Shipped Orders</a></li>
             <li class="{{Request::is('admin/orders/delivered')? 'active' : ''}}"><a class="menu-item" href="{{route('admin.orders','delivered')}}"> Completed Orders</a></li>
             <li class="{{Request::is('admin/orders/cancelled')? 'active' : ''}}"><a class="menu-item" href="{{route('admin.orders','cancelled')}}"> Cancelled Orders</a></li>
             <li class="{{Request::is('admin/orders/unpaid')? 'active' : ''}}"><a class="menu-item" href="{{route('admin.orders','unpaid')}}"> Unpaid Orders</a></li>
             <li class="{{Request::is('admin/orders/pending-payment')? 'active' : ''}}"><a class="menu-item" href="{{route('admin.orders','pending-payment')}}"> Pending Payment</a></li> 
           </ul>
         </li>
        @endisset
        
       
         
        @isset(json_decode($roles->permission, true)['reports']['list'])
        <li class=" navigation-header"><span style="color: #009688;font-weight: bold;">Report Unit </span><i class=" feather icon-minus" data-toggle="tooltip" data-placement="right" data-original-title="Others"></i>
         </li>
         <li class="nav-item {{Request::is('admin/reports*')? 'active' : ''}}">
          <a href="#"><i class="fas fa-chart-line"></i><span class="menu-title" > Reports Management</span></a>
           <ul class="menu-content">
             <li class="{{Request::is('admin/reports/summery*')? 'active' : ''}}"><a class="menu-item" href="{{route('admin.reportsAll','summery')}}"> Summery Reports</a></li>
             <li class="{{Request::is('admin/reports/products*')? 'active' : ''}}"><a class="menu-item" href="{{route('admin.reportsAll','products')}}"> Products Reports</a></li>
             <li class="{{Request::is('admin/reports/sales-product*')? 'active' : ''}}"><a class="menu-item" href="{{route('admin.reportsAll','sales-product')}}"> Products Sales Reports</a></li>
             <li class="{{Request::is('admin/reports/customer-orders*')? 'active' : ''}}"><a class="menu-item" href="{{route('admin.reportsAll','customer-orders')}}"> Customer Orders</a></li>

           </ul>
         </li>
        @endisset
         <li class=" navigation-header"><span style="color: #009688;font-weight: bold;">General Unit </span><i class=" feather icon-minus" data-toggle="tooltip" data-placement="right" data-original-title="Others"></i>
         </li>

         @isset(json_decode($roles->permission, true)['clients']['list'])
         <li class=" nav-item {{Request::is('admin/clients*')? 'active' : ''}}"><a href="{{route('admin.clients')}}"><i class="fas fa-user-tie"></i><span class="menu-title">Clients</span></a>
         </li>
         @endisset

         @isset(json_decode($roles->permission, true)['brands']['list'])
         <li class=" nav-item {{Request::is('admin/brands*')? 'active' : ''}}"><a href="{{route('admin.brands')}}"><i class="fas fa-chess-rook"></i><span class="menu-title">Brands</span></a>
         </li>
         @endisset

         @isset(json_decode($roles->permission, true)['sliders']['list'])
         <li class=" nav-item {{Request::is('admin/sliders*')? 'active' : ''}}"><a href="{{route('admin.sliders')}}"><i class="fas fa-chalkboard"></i><span class="menu-title">Sliders</span></a>
         </li>
         @endisset

         @isset(json_decode($roles->permission, true)['galleries']['list'])
         <li class=" nav-item {{Request::is('admin/galleries*')? 'active' : ''}}"><a href="{{route('admin.galleries')}}"><i class="fas fa-images"></i><span class="menu-title">Galleries</span></a>
         </li>
         @endisset

         @isset(json_decode($roles->permission, true)['menus']['list'])
         <li class=" nav-item {{Request::is('admin/menus*')? 'active' : ''}}"><a href="{{route('admin.menus')}}"><i class="fas fa-bars"></i><span class="menu-title">Menus Setting</span></a>
         </li>
         @endisset

         @isset(json_decode($roles->permission, true)['themeSetting']['list'])
         <li class=" nav-item {{Request::is('admin/theme-setting*')? 'active' : ''}}"><a href="{{route('admin.themeSetting')}}"><i class="fa-solid fa-sliders"></i><span class="menu-title">Theme Setting</span></a>
         </li>
         @endisset


         <li class=" navigation-header"><span style="color: #00bcd4;font-weight: bold;">Users Management </span><i class="feather icon-droplet feather icon-minus" data-toggle="tooltip" data-placement="right" data-original-title="UI"></i>
         </li>
         @isset(json_decode($roles->permission, true)['adminUsers']['list'])
         <li class=" nav-item {{Request::is('admin/users/admin*')? 'active' : ''}}">
          <a href="{{route('admin.usersAdmin')}}"><i class="fas fa-user"></i><span class="menu-title" >Administrator Users </span></a>
         </li>
         @endisset

         @isset(json_decode($roles->permission, true)['adminRoles']['list'])
         <li class=" nav-item {{Request::is('admin/users/role*')? 'active' : ''}}">
          <a href="javascript:void(0)"><i class="fas fa-ruler-combined"></i><span class="menu-title" data-i18n="Cards">Roles User </span></a>
           <ul class="menu-content">
             <li class="{{Request::is('admin/users/role*')? 'active' : ''}}"><a class="menu-item" href="{{route('admin.userRoles')}}" >Roles List </a>
             </li>
             @isset(json_decode($roles->permission, true)['adminRoles']['add'])
             <li><a class="menu-item" href="{{route('admin.userRoleAction',['add',0])}}" >Add Role </a>
             </li>
             @endisset
           </ul>
         </li>
         @endisset

         @isset(json_decode($roles->permission, true)['users']['list'])
         <li class=" nav-item  {{Request::is('admin/users/customer*')? 'active' : ''}}"><a href="#"><i class="fas 

        fa-users"></i><span class="menu-title" data-i18n="Content">Customer Users </span></a>
           <ul class="menu-content">
             <li class="
             @if(Request::is('admin/users/customer/add*'))
             @else
             {{Request::is('admin/users/customer*')? 'active' : ''}}
             @endif
             "><a class="menu-item" href="{{route('admin.usersCustomer')}}" >Users List </a>
             </li>
             @isset(json_decode($roles->permission, true)['users']['add'])
             <li class="{{Request::is('admin/users/customer/add*')? 'active' : ''}}">
              <a class="menu-item" href="{{route('admin.usersCustomerAdd')}}" >Add User</a>
             </li>
             @endisset
           </ul>
         </li>
         @endisset


         @isset(json_decode($roles->permission, true)['subscribe']['list'])
         <li class=" nav-item {{Request::is('admin/subscribes*')? 'active' : ''}}">
          <a href="{{route('admin.subscribes')}}"><i class="fas fa-user-tag"></i><span class="menu-title" >Subscribe Users </span></a>
         </li>
         @endisset

         
          
         

         <li class=" navigation-header"><span style="color: #000000;font-weight: bold;">Apps Setting </span><i class=" feather icon-minus" data-toggle="tooltip" data-placement="right" data-original-title="Others"></i>
         </li>
         @isset(json_decode($roles->permission, true)['appsSetting']['general'])
         <li class="nav-item {{Request::is('admin/setting/general*')? 'active' : ''}}">
            <a href="{{route('admin.setting','general')}}">
              <i class="fa fa-cog"></i>
              <span class="menu-title" >General Setting</span>
            </a>
         </li>
         @endisset

         @isset(json_decode($roles->permission, true)['appsSetting']['mail'])
         <li class=" nav-item {{Request::is('admin/setting/mail*')? 'active' : ''}}">
            <a href="{{route('admin.setting','mail')}}">
              <i class="fas fa-envelope"></i>
              <span class="menu-title" >Mail Setting</span>
            </a>
         </li>
         @endisset

         @isset(json_decode($roles->permission, true)['appsSetting']['sms'])
          <li class=" nav-item {{Request::is('admin/setting/sms*')? 'active' : ''}}">
            <a href="{{route('admin.setting','sms')}}">
              <i class="fas fa-comments"></i>
              <span class="menu-title">SMS Setting</span>
            </a>
         </li>
         @endisset

         @isset(json_decode($roles->permission, true)['appsSetting']['social'])
          <li class=" nav-item {{Request::is('admin/setting/social*')? 'active' : ''}}">
            <a href="{{route('admin.setting','social')}}">
              <i class="fab fa-codepen"></i>
              <span class="menu-title">Social Setting</span>
            </a>
         </li>
         @endisset
         @endif

         <!--Permission Check List Menus End-->

         <li class=" nav-item">
          <a href="{{route('admin.setting','document')}}">
            <i class="fa fa-folder"></i>
            <span class="menu-title">Documentation </span>
          </a>
         </li>

       </ul>
       <div style="padding: 15px;text-align: center;border: 1px solid #e5e7ec;font-size: 20px;">
         <p>Support Center<br>Contact Us<br>Call: {{general()->mobile}}</p>
       </div>
     </div>
   </div>
   <!-- END: Main Menu-->