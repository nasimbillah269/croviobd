@extends('admin.layouts.app') 

@section('title')
<title>Ecommerce Edit - {{general()->title}} | {{general()->subtitle}}</title>
@endsection 

@push('css')


@endpush @section('contents')

<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title mb-0">Ecommerce Setting</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard </a></li>
                    <li class="breadcrumb-item active">Ecommerce Setting</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
        <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
            <a class="btn btn-outline-primary reloadPage" href="javascript:void(0)">
                <i class="fa-solid fa-rotate"></i>
            </a>
        </div>
    </div>
</div>

<div class="content-body">
    <!-- Basic Elements start -->
    <section class="basic-elements">
        @include('admin.alerts')

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
                            <h4 class="card-title">Ecommerce Setting</h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">

                                <ul class="nav nav-tabs" role="tablist" style="border-bottom: none;">
                                     <li class="nav-item">
                                         <a class="nav-link {{$type=='general'?'active':''}}" href="{{route('admin.ecommerceSetting',['type'=>'general'])}}"><i class="fas fa-cog"></i> General</a>
                                     </li>
                                     <li class="nav-item">
                                         <a class="nav-link {{$type=='delivery'?'active':''}}" href="{{route('admin.ecommerceSetting',['type'=>'delivery'])}}"><i class="fas fa-truck"></i> Delivery &amp; Payment</a>
                                     </li>
                                     <li class="nav-item">
                                         <a class="nav-link {{$type=='facebook'?'active':''}}" href="{{route('admin.ecommerceSetting',['type'=>'facebook'])}}"><i class="fab fa-facebook"></i> Facebook Post Discount</a>
                                     </li>
                                     <!--<li class="nav-item">-->
                                     <!--    <a class="nav-link {{$type=='shipping'?'active':''}}"  href="{{route('admin.ecommerceSetting',['type'=>'shipping'])}}" ><i class="fas fa-truck"></i> Shipping</a>-->
                                     <!--</li>-->
                                 </ul>
                                 <div class="tab-content px-1 pt-1" style="border: 1px solid #ddd;">
                                     <div class="tab-pane {{$type=='general'?'active':''}}" >
                                         
                                         <div class="row">
                                            <div class="col-md-6">
                                                <div class="table-responsive">
                                                    <form action="{{route('admin.ecommerceSettingPost',['type'=>'general'])}}" method="post">
                               				        @csrf
                                                    <table class="table table-borderless">
                                                     <!--   <tr>-->
                                                     <!--    <th>Shipping Charge Free <br> {{Carbon\Carbon::now()->format('d-m-Y h:i A')}}</th>-->
                                                     <!--    <td style="padding:3px;">-->
                                                     <!--       <label>Minimum Shopping ({{general()->currency}}) <b>Frozen</b></label>-->
                                                     <!--       <input type="number" class="form-control form-control-sm mb-1" placeholder="Minimum Shopping Amount" name="frozen_amount" value="{{general()->frozen_amount?:''}}">-->
                                                     <!--       <label>Minimum Shopping ({{general()->currency}}) <b>Dye</b></label>-->
                                                     <!--       <input type="number" class="form-control form-control-sm mb-1" placeholder="Minimum Shopping Amount" name="dye_amount" value="{{general()->dye_amount?:''}}">-->
                                                     <!--       <label>Minimum Shopping ({{general()->currency}}) <b>Mix</b></label>-->
                                                     <!--       <input type="number" class="form-control form-control-sm mb-1" placeholder="Minimum Shopping Amount" name="mix_amount" value="{{general()->mix_amount?:''}}">-->
                                                            <!--<label>Last Shopping Date</label>-->
                                                            <!--<input type="date" class="form-control form-control-sm" placeholder="Minimum Shopping Charge" name="">-->
                                                     <!--    </td>-->
                                                     <!--</tr>-->
                                                     <!--<tr>-->
                                                     <!--    <th>Shipping Charge Applable</th>-->
                                                     <!--    <td style="padding:3px;">-->
                                                     <!--       <select class="form-control form-control-sm" name="shipping_charge_type">-->
                                                     <!--           <option value="1" {{general()->shipping_charge_type==1?'selected':''}}>All Charge Applicable</option>-->
                                                     <!--           <option value="2" {{general()->shipping_charge_type==2?'selected':''}}>Only Selected Zone Applicable</option>-->
                                                     <!--           <option value="3" {{general()->shipping_charge_type==3?'selected':''}}>Only Products Charge Applicable</option>-->
                                                     <!--           <option value="4" {{general()->shipping_charge_type==4?'selected':''}}>Only Defult Charge Applicable</option>-->
                                                     <!--           <option value="5" {{general()->shipping_charge_type==5?'selected':''}}>Product /Select Zone Applicable</option>-->
                                                     <!--       </select>-->
                                                     <!--    </td>-->
                                                     <!--</tr>-->
                                                     <tr>
                                                         <th>Shipping Charge</th>
                                                         <td style="padding:3px;">
                                                             <div class="input-group">
                                                                <input type="number" class="form-control form-control-sm" name="inside_dhaka_shipping_charge" value="{{general()->inside_dhaka_shipping_charge}}" placeholder="Inshide Dhaka Charge">
                                                                <input type="number" class="form-control form-control-sm" name="outside_dhaka_shipping_charge" value="{{general()->outside_dhaka_shipping_charge}}" placeholder="Outside Dhaka Charge">
                                                            </div>
                                                         </td>
                                                     </tr>
                                                     <tr>
                                                         <th>Tax (%)</th>
                                                         <td style="padding:3px;">
                                                             <div class="input-group">
                                                               <input type="number" class="form-control form-control-sm" name="tax" value="{{general()->tax}}" placeholder="Enter Tax">
                                                               <select class="form-control form-control-sm" name="tax_status">
                                                                   <option value="1" {{general()->tax_status==1?'selected':''}}>Tax applicable</option>
                                                                   <option value="0" {{general()->tax_status==0?'selected':''}}>No Tax</option>
                                                               </select>
                                                             </div>
                                                            
                                                         </td>
                                                     </tr>
                                                     <!--<tr>-->
                                                     <!--    <th>Weekend Holyday </th>-->
                                                     <!--    <td style="padding:3px;">-->
                                                     <!--       <select class="form-control form-control-sm" name="weekend_holyday">-->
                                                     <!--           <option value="">Select Day</option>-->
                                                     <!--           <option value="0" {{general()->weekend_holyday===0?'selected':''}}>Sunday</option>-->
                                                     <!--           <option value="1" {{general()->weekend_holyday==1?'selected':''}}>Monday</option>-->
                                                     <!--           <option value="2" {{general()->weekend_holyday==2?'selected':''}}>Tuesday</option>-->
                                                     <!--           <option value="3" {{general()->weekend_holyday==3?'selected':''}}>Wednesday</option>-->
                                                     <!--           <option value="4" {{general()->weekend_holyday==4?'selected':''}}>Thursday</option>-->
                                                     <!--           <option value="5" {{general()->weekend_holyday==5?'selected':''}}>Friday</option>-->
                                                     <!--           <option value="6" {{general()->weekend_holyday==6?'selected':''}}>Saturday</option>-->
                                                                
                                                     <!--       </select>-->
                                                     <!--    </td>-->
                                                     <!--</tr>-->
                                                     <tr>
                                                         <th>Action</th>
                                                         <td style="padding:3px;">
                                                            <button type="submit" class="btn btn-info" style="padding:5px 10px;">Update</button>
                                                         </td>
                                                     </tr>
                                                 </table>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                         
                                         
                                     </div>
                                     <div class="tab-pane {{$type=='delivery'?'active':''}}" >
                                         @if($type=='delivery')
                                         <form action="{{route('admin.ecommerceSettingPost',['type'=>'delivery'])}}" method="post" enctype="multipart/form-data">
                                         @csrf
                                         <div class="row">
                                            <div class="col-md-7">
                                                <h5><b>Delivery Zones (Checkout Sector)</b></h5>
                                                <p class="text-muted" style="font-size: 13px;">Customer selects one of these at checkout, then district and upazila. The charge is added to the order automatically.</p>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered">
                                                        <tr>
                                                            <th>Zone</th>
                                                            <th>Covers</th>
                                                            <th style="width: 150px;">Default Charge ({{general()->currency}})</th>
                                                            <th style="width: 80px;">Active</th>
                                                        </tr>
                                                        @forelse($deliveryZones as $zone)
                                                        <tr>
                                                            <td>{{$zone->name}}<br><small>{{$zone->bn_name}}</small><br>
                                                                <a href="{{route('admin.deliveryZoneDistricts',$zone->id)}}" class="badge badge-info"><i class="fa fa-edit"></i> District Charges{{$zone->districtCharges->count() ? ' ('.$zone->districtCharges->count().' custom)' : ''}}</a>
                                                            </td>
                                                            <td>
                                                                <small>
                                                                {{$zone->division?$zone->division->name:''}} Division
                                                                @if($zone->scope=='city') - Dhaka metro thanas only @elseif($zone->scope=='outer') - without Dhaka metro thanas @endif
                                                                </small>
                                                            </td>
                                                            <td style="padding:3px;">
                                                                <input type="number" min="0" step="1" class="form-control form-control-sm" name="charges[{{$zone->id}}]" value="{{(float)$zone->shipping_charge}}">
                                                            </td>
                                                            <td style="text-align: center;">
                                                                <input type="checkbox" name="statuses[{{$zone->id}}]" value="active" {{$zone->status=='active'?'checked':''}}>
                                                            </td>
                                                        </tr>
                                                        @empty
                                                        <tr>
                                                            <td colspan="4" class="text-danger">No delivery zones found. Run: php artisan db:seed --class=DeliveryZoneSeeder</td>
                                                        </tr>
                                                        @endforelse
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <h5><b>Payment Setting</b></h5>
                                                <table class="table table-borderless">
                                                    <tr>
                                                        <th>Cash On Delivery Advance (% of delivery charge)</th>
                                                        <td style="padding:3px;">
                                                            <input type="number" min="0" max="100" class="form-control form-control-sm" name="cod_advance_percent" value="{{general()->cod_advance_percent}}">
                                                            @if ($errors->has('cod_advance_percent'))
                                                            <p style="color: red;margin: 0;font-size: 10px;">{{ $errors->first('cod_advance_percent') }}</p>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Free Delivery on Full Advance</th>
                                                        <td style="padding:3px;">
                                                            <div class="custom-control custom-switch">
                                                                <input type="checkbox" class="custom-control-input" id="freeDeliverySwitch" name="full_advance_free_delivery" value="1" {{general()->full_advance_free_delivery?'checked':''}}>
                                                                <label class="custom-control-label" for="freeDeliverySwitch">{{general()->full_advance_free_delivery?'On':'Off'}}</label>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Bangla QR / Merchant Number</th>
                                                        <td style="padding:3px;">
                                                            <input type="text" class="form-control form-control-sm" name="bangla_qr_number" value="{{general()->bangla_qr_number}}" placeholder="Shown under the QR at checkout">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Bangla QR Image</th>
                                                        <td style="padding:3px;">
                                                            @if(general()->bangla_qr_image)
                                                            <img src="{{asset(general()->bangla_qr_image)}}" style="max-width: 150px;display: block;margin-bottom: 5px;">
                                                            <label style="font-size: 13px;"><input type="checkbox" name="remove_qr" value="1"> Remove</label>
                                                            @endif
                                                            <input type="file" name="bangla_qr_image" class="form-control form-control-sm" accept="image/*">
                                                            @if ($errors->has('bangla_qr_image'))
                                                            <p style="color: red;margin: 0;font-size: 10px;">{{ $errors->first('bangla_qr_image') }}</p>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div class="col-md-12 mb-1">
                                                <button type="submit" class="btn btn-info" style="padding:5px 10px;">Update</button>
                                            </div>
                                         </div>
                                         </form>
                                         @endif
                                     </div>
                                     <div class="tab-pane {{$type=='facebook'?'active':''}}" >
                                         @if($type=='facebook')
                                         <form action="{{route('admin.ecommerceSettingPost',['type'=>'facebook'])}}" method="post">
                                         @csrf
                                         <div class="row">
                                            <div class="col-md-7">
                                                <p class="text-muted" style="font-size: 13px;">
                                                    Customer pastes the link of a Facebook post about a product they already received, with your page tagged or as collaborator.
                                                    Admin checks it in the order panel; on approve the discount is taken off the order total.
                                                </p>
                                                <table class="table table-borderless">
                                                    <tr>
                                                        <th style="width: 45%;">Feature</th>
                                                        <td style="padding:3px;">
                                                            <div class="custom-control custom-switch">
                                                                <input type="checkbox" class="custom-control-input" id="fbDiscountSwitch" name="fb_discount_status" value="1" {{general()->fb_discount_status?'checked':''}}>
                                                                <label class="custom-control-label" for="fbDiscountSwitch">{{general()->fb_discount_status?'On':'Off'}}</label>
                                                            </div>
                                                            <small class="text-muted">Off = the link field is hidden at checkout.</small>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Discount</th>
                                                        <td style="padding:3px;">
                                                            <div class="input-group">
                                                                <input type="number" min="0" step="0.01" class="form-control form-control-sm" name="fb_discount_amount" value="{{(float)general()->fb_discount_amount}}">
                                                                <select class="form-control form-control-sm" name="fb_discount_type">
                                                                    <option value="flat" {{general()->fb_discount_type!='percentage'?'selected':''}}>Flat ({{general()->currency}})</option>
                                                                    <option value="percentage" {{general()->fb_discount_type=='percentage'?'selected':''}}>Percentage (%) of products</option>
                                                                </select>
                                                            </div>
                                                            @if ($errors->has('fb_discount_amount'))
                                                            <p style="color: red;margin: 0;font-size: 11px;">{{ $errors->first('fb_discount_amount') }}</p>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Maximum Discount ({{general()->currency}}) <small>for percentage, 0 = no limit</small></th>
                                                        <td style="padding:3px;">
                                                            <input type="number" min="0" step="1" class="form-control form-control-sm" name="fb_discount_max" value="{{(float)general()->fb_discount_max}}">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Minimum Post Age (days, max 30)</th>
                                                        <td style="padding:3px;">
                                                            <input type="number" min="1" max="30" class="form-control form-control-sm" name="fb_post_min_days" value="{{general()->fb_post_min_days}}">
                                                            @if ($errors->has('fb_post_min_days'))
                                                            <p style="color: red;margin: 0;font-size: 11px;">{{ $errors->first('fb_post_min_days') }}</p>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Facebook Page Name <small>(shown to customer)</small></th>
                                                        <td style="padding:3px;">
                                                            <input type="text" class="form-control form-control-sm" name="fb_page_name" value="{{general()->fb_page_name}}" placeholder="{{general()->title}}">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Full Advance Orders</th>
                                                        <td style="padding:3px;">
                                                            <select class="form-control form-control-sm" name="fb_full_advance_rule">
                                                                <option value="refund" {{general()->fb_full_advance_rule!='not_allowed'?'selected':''}}>Allowed - discount is refunded to customer after approve</option>
                                                                <option value="not_allowed" {{general()->fb_full_advance_rule=='not_allowed'?'selected':''}}>Not allowed - hide link field for full advance</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th></th>
                                                        <td style="padding:3px;">
                                                            <button type="submit" class="btn btn-info" style="padding:5px 10px;">Update</button>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                         </div>
                                         </form>
                                         @endif
                                     </div>
                                     <div class="tab-pane {{$type=='shipping'?'active':''}}" >

                                         <a href="{{route('admin.ecommerceSettingCreate',$type)}}" class="btn btn-success" style="padding:5px 10px;"><i class="fa fa-plus"></i> Add Zone</a><br><br>
                                         <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th>SL</th>
                                                    <th>Zone Name</th>
                                                    <th>Shipping Zones</th>
                                                    <th>After Day</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                                @foreach($shippingZones as $i=>$zone)
                                                <tr>
                                                    <td>{{$i+1}}</td>
                                                    <td>{{$zone->name}}</td>
                                                    <td>
                                                        @foreach($zone->zoneLists as $i=>$list)
                                                        {{$i==0?'':'-'}} {{$list->zoneId?$list->zoneId->name:''}} 
                                                        @endforeach
                                                    </td>
                                                    <td>{{number_format($zone->shipping_charge)}} Day</td>
                                                    <td>
                                                        @if($zone->status=='active')
                                                        <span class="badge badge-success">{{ucfirst($zone->status)}}</span>
                                                        @else
                                                        <span class="badge badge-danger">{{ucfirst($zone->status)}}</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a href="{{route('admin.ecommerceSettingEdit',[$type,$zone->id])}}" class="badge badge-success" ><i class="fa fa-edit"></i></a>
                                                        <a href="" class="badge badge-danger" ><i class="fa fa-trash"></i></a>
                                                    </td>
                                                </tr>
                                                @endforeach

                                            </table>
                                         </div>
                                        
                                     </div>

                                 </div>


                            </div>
                        </div>
                    </div>

                </div>

            </div>


    </section>
    <!-- Basic Inputs end -->
</div>

@endsection @push('js')

<script>
    $('#fbDiscountSwitch').change(function(){
        $(this).next('label').text(this.checked ? 'On' : 'Off');
    });
    $('#freeDeliverySwitch').change(function(){
        $(this).next('label').text(this.checked ? 'On' : 'Off');
    });

          

</script>

@endpush
