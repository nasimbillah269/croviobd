<div class="usersidebar">
    <div class="cd-user">
        <img src="{{asset(Auth::user()->image())}}" alt="{{Auth::user()->name}}">
        <div style="min-width:0;">
            <p class="cd-user-name">{{Auth::user()->name}}</p>
            <p class="cd-user-meta">{{Auth::user()->email ?: Auth::user()->mobile}}</p>
        </div>
    </div>
    <ul id="usersidebar">
        @if(Auth::user()->admin)
        <li>
            <a href="{{route('admin.dashboard')}}"><i class="fa fa-shield"></i> Admin Dashboard</a>
        </li>
        <li class="cd-divider"></li>
        @endif
        <li class="{{ request()->routeIs('customer.dashboard') ? 'active' : '' }}">
            <a href="{{route('customer.dashboard')}}"><i class="fa fa-th-large"></i> Dashboard</a>
        </li>
        <li class="{{ request()->routeIs('customer.myOrders','customer.orderDetails','customer.orderCancel','customer.orderReturn') ? 'active' : '' }}">
            <a href="{{route('customer.myOrders')}}"><i class="fa fa-shopping-bag"></i> My Orders</a>
        </li>
        {{--
        <li class="{{ request()->routeIs('customer.returnCancellations') ? 'active' : '' }}">
            <a href="{{route('customer.returnCancellations')}}"><i class="fa fa-undo"></i> Return & Cancellations</a>
        </li>
        --}}
        <li class="{{ request()->routeIs('customer.myReviews','customer.orderReview') ? 'active' : '' }}">
            <a href="{{route('customer.myReviews')}}"><i class="fa-regular fa-star"></i> My Reviews</a>
        </li>
        <li class="{{ request()->routeIs('customer.profileEdit','customer.profileVerifyUpdate','customer.profileUpdateVerifyCode') ? 'active' : '' }}">
            <a href="{{route('customer.profileEdit')}}"><i class="fa-regular fa-user"></i> Edit Profile</a>
        </li>
        <li class="{{ request()->routeIs('customer.changePassword') ? 'active' : '' }}">
            <a href="{{route('customer.changePassword')}}"><i class="fa fa-lock"></i> Change Password</a>
        </li>
        <li class="cd-divider"></li>
        <li class="cd-logout">
            <a href="{{ route('logout') }}" onclick="event.preventDefault();
            document.getElementById('logout-form-sidebar').submit();"><i class="fa fa-sign-out"></i> Log Out</a>
            <form id="logout-form-sidebar" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </li>
    </ul>
</div>
