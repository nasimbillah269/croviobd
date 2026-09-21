<div class="usersidebar">
<p style="margin: 0;font-size: 20px;color: #333;">Shortcuts</p>
<ul id="usersidebar">
    @if(Auth::user()->admin)
    <li>
        <a href="{{route('admin.dashboard')}}" style="font-weight: bold;color: #04049b;">Admin Dashboard</a>
    </li>
    @endif
    <li>
        <a href="{{route('customer.dashboard')}}">My Dashboard</a>
    </li>
    <li>
        <a href="{{route('customer.profileEdit')}}">Edit Profile</a>
    </li>
    <li>
        <a href="{{route('customer.changePassword')}}">Change Password</a>
    </li>
    <li>
        <a href="{{route('customer.myOrders')}}">My Orders</a>
    </li>
    {{--
    <li>
        <a href="{{route('customer.returnCancellations')}}">Return & Cancellations</a>
    </li>
    --}}
    
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
</ul>
</div>
{{--
<div class="screenapps">
    <h4>Our Mobile App</h4>
    <p>Search Anywhere, Anytime!</p>
    <img src="{{asset('medies/qrcode1.png')}}">
    <p>Search Anywhere, Anytime!</p>
</div>
--}}