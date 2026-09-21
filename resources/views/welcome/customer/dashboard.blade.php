@extends(App\Models\General::first()->theme.'.layouts.app')
@section('title')
<title>Dashboard | {{App\Models\General::first()->title}} | {{App\Models\General::first()->subtitle}}</title>
@endsection
@section('SEO')
<meta name="description" content="{!!App\Models\General::latest()->first()->meta_dsc!!}">
<meta name="keywords" content="{{App\Models\General::latest()->first()->meta_key}}">
<meta property="og:title" content="{{App\Models\General::latest()->first()->name}}">
<meta property="og:description" content="{!!App\Models\General::latest()->first()->meta_dsc!!}">
<meta property="og:image" content="{!!App\Models\General::latest()->first()->meta_dsc!!}">
<meta property="og:url" content="{{route('index')}}">
@endsection
@push('css')
<style type="text/css">
    .shippingTable tr td {
        padding: 5px;
    }
</style>
@endpush
@section('contents')
<div class="userdashboard">
    <div class="container">
    <div class="row" style="margin:0;">
        <div class="col-lg-3 usersidebardiv">
            @include(App\Models\General::first()->theme.'.customer.includes.sidebar')
        </div>
        <div class="col-lg-9 usermainbody">
            <div class="usercontent">
                <div class="profiledetails">
                    <div class="row" style="margin: 0;padding: 10px;">
                        <div class="col-6 col-md-2">
                            <div class="profileImage">
                                <img src="{{asset(Auth::user()->image())}}" width="100px">
                            </div>
                        </div>
                        <div class="col-6 col-md-6">
                            <div class="parsonalprofile2">
                            <p><i class="fa fa-user"></i> {{Auth::user()->name}}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="parsonalprofile">
                                <a href="{{route('customer.profileEdit')}}" class="editPrifilebtn">Edit Profile</a>
                                <a href="{{route('customer.changePassword')}}" class="editPrifilebtn">Change password</a>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="background: #fafafa;margin: 0;padding: 10px;text-align: center;">
                        <div class="dashboardcountinfo">
                            <span>{{Auth::user()->orders()->count()}}</span>
                            <p><a href="{{route('customer.myOrders')}}">All Order</a></p>
                        </div>
                        <div class="dashboardcountinfo">
                            <span>{{Auth::user()->orders()->where('order_status','pending')->count()}}</span>
                            <p><a href="{{route('customer.myOrders',['type'=>'pending'])}}">Pending Order</a></p>
                        </div>
                        <div class="dashboardcountinfo">
                            <span>{{Auth::user()->orders()->where('order_status','confirmed')->count()}}</span>
                            <p><a href="{{route('customer.myOrders',['type'=>'confirmed'])}}">Confirm Order</a></p>
                        </div>
                        <div class="dashboardcountinfo">
                            <span>{{Auth::user()->orders()->where('order_status','shipped')->count()}}</span>
                            <p><a href="{{route('customer.myOrders',['type'=>'shipped'])}}">Shipped Order</a></p>
                        </div>
                        <div class="dashboardcountinfo">
                            <span>{{Auth::user()->orders()->where('order_status','delivered')->count()}}</span>
                            <p><a href="{{route('customer.myOrders',['type'=>'delivered'])}}">Completed Order</a></p>
                        </div>
                    </div>
                </div>

                @include(App\Models\General::first()->theme.'.alerts')
                <div class="row">
                   
                     <div class="col-md-12" style="padding:15px;">
                        <div class="dashbaordInfo">
                        <p>Shipping Address</p>
                        <table class="table table-bordered shippingTable">
                            <tr>
                                <td style="width:30%;">Address</td>
                                <td>: {!!Auth::user()->address_line1!!}</td>
                            </tr>
                            <tr>
                                <td>City</td>
                                <td>: {{Auth::user()->city_name}}</td>
                            </tr>
                            <tr>
                                <td>Postal Code </td>
                                <td>: {{Auth::user()->postal_code}}</td>
                            </tr>
                            <tr>
                                <td>Prefecture </td>
                                <td>: {{Auth::user()->districtN?Auth::user()->districtN->name:''}}</td>
                            </tr>
                            <tr>
                                <td>Country </td>
                                <td>: Bangladesh</td>
                            </tr>
                        </table>
                              
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    </div>
</div>
@endsection
@push('js')
@endpush