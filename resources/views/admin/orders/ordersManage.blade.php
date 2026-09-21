@extends('admin.layouts.app') @section('title')
<title>Order List - {{general()->title}} | {{general()->subtitle}}</title>
@endsection @push('css')
<style type="text/css">
    

.courier-card{
    background:#fff;
    padding:35px;
    border-radius:20px;
    box-shadow:0 10px 30px rgba(0,0,0,0.08);
}

.courier-card h3{
    margin-bottom:10px;
    color:#1e293b;
    font-weight:700;
}

.courier-card p{
    color:#64748b;
    margin-bottom:25px;
    font-size:14px;
}

.form-group{
    margin-bottom:25px;
}

.form-group label{
    display:block;
    margin-bottom:8px;
    font-weight:600;
    color:#334155;
}

.form-control{
    width:100%;
    height:50px;
    padding:0 15px;
    border:1px solid #dbe2ea;
    border-radius:12px;
    font-size:15px;
    outline:none;
    transition:0.3s;
    box-sizing:border-box;
}

.form-control:focus{
    border-color:#0d6efd;
    box-shadow:0 0 0 4px rgba(13,110,253,0.15);
}

.btn-check{
    width:100%;
    height:50px;
    border:none;
    background:linear-gradient(45deg,#0d6efd,#2563eb);
    color:#fff;
    font-size:16px;
    font-weight:600;
    border-radius:12px;
    cursor:pointer;
    transition:0.3s;
}

.btn-check:hover{
    transform:translateY(-2px);
    box-shadow:0 8px 20px rgba(13,110,253,0.3);
}
    
</style>
@endpush @section('contents')


<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title mb-0">Order Manage</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard </a></li>
                    <li class="breadcrumb-item active">Order Manage</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
        <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
            <a class="btn btn-outline-primary" href="{{route('admin.orders')}}"><i class="fa fa-list"></i> Back</a>
            <a class="btn btn-success" href="{{route('admin.invoice',$order->id)}}"><i class="fa fa-print"></i> Invoice</a>
            <a class="btn btn-outline-primary" href="{{route('admin.ordersManage',$order->id)}}">
                <i class="fa-solid fa-rotate"></i>
            </a>
        </div>
    </div>
</div>


<div class="content-body">
    <!-- Basic Elements start -->
    <section class="basic-elements">
        <div class="row">
            <div class="col-md-12">
            	<div class="card">
                    <div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
                        <h4 class="card-title">Customer Info</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                        	<div class="row">
                        		<div class="col-md-6">
                        			{{--<table class="table table-borderless">
                        			    <tr>
		                        			<th>INVOICE:</th>
		                        			<td>{{$order->invoice}}</td>
		                        		</tr>
		                        		
		                        		<tr>
		                        			<th>Name:</th>
		                        			<td>{{$order->name}}</td>
		                        		</tr>
		                        		<tr>
		                        			<th>Mobile:</th>
		                        			<td>{{ $order->mobile}}</td>
		                        		</tr>
		                        		<tr>
		                        			<th>Billing & Shipping:</th>
		                        			<td>{{$order->address}}</td>
		                        		</tr>
		                        		<tr>
		                        			<th>Delivery Area:</th>
		                        			<td>{{$order->area_name}}</td>
		                        		</tr>
		                        	</table>--}}
		                        	
		                        	  @if(Session::has('success'))
                                        <div class="alert alert-success alert-dismissable">
                                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                            <strong>Success! </strong> {{Session::get('success')}}.
                                        </div>
                                      @endif
		                        	
		                        	<form action="{{ route('admin.ordersManageUpdate',$order->id) }}" method="POST">
                                    @csrf
                                
                                    <input type="hidden" name="actionType" value="customerUpdate">
                                
                                    <div class="row">
                                
                                        <div class="col-md-6 mb-2">
                                            <label>Name</label>
                                            <input
                                                type="text"
                                                class="form-control"
                                                name="name"
                                                value="{{ old('name',$order->name) }}"
                                                required>
                                        </div>
                                
                                        <div class="col-md-6 mb-2">
                                            <label>Mobile</label>
                                        
                                            <div class="form-control d-flex align-items-center justify-content-between">
                                                <a href="tel:{{ $order->mobile }}" >{{ $order->mobile }}</a>
                                        
                                                <a href="tel:{{ $order->mobile }}" class="btn btn-sm btn-success">
                                                    <i class="fa fa-phone"></i> Call
                                                </a>
                                            </div>
                                        </div>
                                
                                      
                                
                                        <div class="col-md-6 mb-2">
                                            <label>Delivery Area</label>
                                            <input
                                                type="text"
                                                class="form-control"
                                                name="area_name"
                                                value="{{ old('area_name',$order->area_name) }}">
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label>Order Note</label>
                                            <input
                                                type="text"
                                                class="form-control"
                                                name="order_note"
                                                value="{{ old('note',$order->note) }}">
                                        </div>
                                
                                        <div class="col-md-12 mb-2">
                                            <label>Billing & Shipping Address</label>
                                            <textarea
                                                class="form-control"
                                                rows="4"
                                                name="address">{{ old('address',$order->address) }}</textarea>
                                        </div>
                                        <div class="col-md-12 mb-2">
                                            <label>Admin Order Note</label>
                                    
                                                 <input
                                                type="text"
                                                class="form-control"
                                                name="admin_note"
                                                value="{{ old('admin_note',$order->admin_note) }}">
                                        </div>
                                
                                        <div class="col-md-12">
                                            <button class="btn btn-primary" style="float: right;">
                                                <i class="fa fa-save"></i>
                                                Update Customer Info
                                            </button>
                                        </div>
                                
                                    </div>
                                
                                </form>
		                        	
		                        	
                        		</div>
                        		<div class="col-md-6">
                        			<table class="table table-borderless">
		                        		<tr>
		                        			<th>Grand Total:</th>
		                        			<td>{{priceFullFormat($order->grand_total)}}</td>
		                        		</tr>
		                        		<tr>
		                        			<th>Paid:</th>
		                        			<td>{{priceFullFormat($order->paid_amount)}}</td>
		                        		</tr>
		                        		<tr>
		                        			<th>Due:</th>
		                        			<td>{{priceFullFormat($order->due_amount)}}</td>
		                        		</tr>
		                        		@if($order->extra_amount > 0)
		                        		<tr>
		                        			<th>Advence:</th>
		                        			<td>{{priceFullFormat($order->extra_amount)}}
		                        			
		                        			
		                        			</td>
		                        		</tr>
		                        		@endif
		                        		
		                        		<tr>
		                        			<th>Payment:</th>
		                        			<td>
		                        			    @if($order->payment_status=='partial')
								                <span class="badge badge-success" style="background:#ff9800;">{{ucfirst($order->payment_status)}}</span>
								                @elseif($order->payment_status=='paid')
								                <span class="badge badge-success" style="background:#673ab7;">{{ucfirst($order->payment_status)}}</span>
								                @else
								                <span class="badge badge-success" style="background:#f44336;">{{ucfirst($order->payment_status)}}</span>
								                @endif
		                        			</td>
		                        		</tr>
		                        		<tr>
		                        			<th>Order Status:</th>
		                        			<td>
		                        			    @if($order->payment_method==null)
									            <span class="badge badge-success" style="background:#ff5722;">Pending Payment</span>
									            @else
									            @if($order->order_status=='confirmed')
									            <span class="badge badge-success" style="background:#e91e63;">{{ucfirst($order->order_status)}}</span>
									            @elseif($order->order_status=='shipped')
									            <span class="badge badge-success" style="background:#673ab7;">{{ucfirst($order->order_status)}}</span>
									            @elseif($order->order_status=='delivered')
									            <span class="badge badge-success" style="background:#1c84c6;">Comleted</span>
									            @elseif($order->order_status=='cancelled')
									            <span class="badge badge-success" style="background:#f44336;">{{ucfirst($order->order_status)}}</span>
									            @else
									            <span class="badge badge-success" style="background:#ff9800;">{{ucfirst($order->order_status)}}</span>
									            @endif
									            @endif
		                        			    
		                        			</td>
		                        		</tr>
		                        		<tr>
		                        			<th>Date:</th>
		                        			<td>{{$order->created_at->format('d-m-Y')}}</td>
		                        		</tr>
		                        	</table>
                        		</div>
                        		{{--<div class="col-md-12">
                        		    <p><b>Order Note:</b></p>
                        		    <div>
                        		        {!!$order->note!!}
                        		    </div>
                        		</div>--}}
                        	</div>
                        	
                        </div>
                    </div>
                </div>
                
              
        <div class="card">
           <div class="courier-card">
    <h3>ðŸ“¦ Courier Check</h3>
    <p>Enter customer phone number to check courier information.</p>

@php
    $courierResult = json_decode($order->corier_result, true);
@endphp

@if(isset($courierResult['status']) && $courierResult['status'] == 'success')

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Courier History</h5>
        </div>

        <div class="card-body p-0">
            <table class="table table-bordered table-hover mb-0">
                <thead class="table-light">
                <tr>
                    <th>Courier</th>
                    <th>Total Parcel</th>
                    <th>Success Parcel</th>
                    <th>Cancelled Parcel</th>
                    <th>Success Ratio</th>
                </tr>
                </thead>
                <tbody>
                @foreach($courierResult['data'] as $key => $courier)
                    @if($key != 'summary')
                        <tr>
                            <td>
                                <img src="{{ $courier['logo'] }}"
                                     width="35"
                                     height="35"
                                     class="me-2"
                                     style="object-fit:contain">

                                {{ $courier['name'] }}
                            </td>
                            <td>{{ $courier['total_parcel'] }}</td>
                            <td class="text-success">
                                {{ $courier['success_parcel'] }}
                            </td>
                            <td class="text-danger">
                                {{ $courier['cancelled_parcel'] }}
                            </td>
                            <td>
                                <span class="badge bg-success">
                                    {{ $courier['success_ratio'] }}%
                                </span>
                            </td>
                        </tr>
                    @endif
                @endforeach
                </tbody>

                <tfoot class="table-secondary">
                <tr>
                    <th>Summary</th>
                    <th>{{ $courierResult['data']['summary']['total_parcel'] }}</th>
                    <th>{{ $courierResult['data']['summary']['success_parcel'] }}</th>
                    <th>{{ $courierResult['data']['summary']['cancelled_parcel'] }}</th>
                    <th>{{ $courierResult['data']['summary']['success_ratio'] }}%</th>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>

    @if(!empty($courierResult['reports']))
        <div class="card shadow-sm">
            <div class="card-header bg-danger text-white">
                Fraud Reports
            </div>

            <div class="card-body">
                @foreach($courierResult['reports'] as $report)
                    <div class="border rounded p-3 mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <img src="{{ $report['courierLogo'] }}"
                                 width="35"
                                 class="me-2">

                            <strong>{{ $report['courierName'] }}</strong>
                        </div>

                        <p class="mb-1">
                            <strong>Name:</strong> {{ $report['name'] }}
                        </p>

                        <p class="mb-1">
                            <strong>Details:</strong>
                            {{ $report['details'] }}
                        </p>

                        <small class="text-muted">
                            {{ \Carbon\Carbon::parse($report['created_at'])->format('d M Y') }}
                        </small>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

@else

    <div class="alert alert-warning">
        {{ $courierResult['message'] ?? 'No courier information found.' }}
    </div>

@endif
</div>
        </div>

                
                
                <div class="card">
                    <div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
                        <h4 class="card-title">Orders Item</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                        	<form class="form-inline" method="post" action="{{route('admin.ordersManageUpdate',$order->id)}}">
					                @csrf
					                <input type="hidden" value="orderUpdate" name="actionType">
					                <div class="table-responsive m-t">
					                    <table class="table table-sm table-bordered table-striped">
					                        <thead>
					                            <tr>
					                                <th style="min-width: 50px;width: 50px;">SL</th>
					                                <th style="min-width: 70px;width: 70px;">Image</th>
					                                <th style="min-width: 300px;">Items</th>
					                                <th style="width: 250px;">Order Status</th>

					                            </tr>
					                        </thead>
					                        <tbody>

					                            @foreach($order->items as $i=>$item)
					                            <tr>
					                                <td>{{ $i+1 }}</td>
					                               
					                                <td>

					                                  @if($item->product)
					                                    <img src="{{asset($item->product->image())}}" style="max-height: 40px;max-width: 100%;">
					                                  @endif
					                                </td>
					                                
					                                <td>
					                                  <div><strong>{{ $item->product_name }}</strong></div>
					                                  <small>
					                                    ID:{{ $item->product_id }}
					                                    
					                                    @if($item->color)
					                                    , Color: {{ $item->color }} 
					                                    @endif
					                                    
					                                    @if($item->bar_code)
					                                    , BarCode: {{ $item->bar_code }} 
					                                    @endif
					                                    
					                                    @if($item->sku_code)
					                                    , SKU: {{ $item->sku_code }} 
					                                    @endif
					                                    
					                                    @if($item->weight_unit && $item->weight_amount)
					                                    , Weight: {{ $item->weight_amount }} {{ $item->weight_unit }} 
					                                    @endif
					                                    
					                                    @if($item->dimensions_unit && $item->dimensions_length || $item->dimensions_width  || $item->dimensions_height )
					                                    , Dimensions($item->dimensions_unit): L-{{ $item->dimensions_length }} W-{{ $item->dimensions_width }} H-{{ $item->dimensions_height }}
					                                    @endif

					                                    @if($item->size)
					                                    , Size: {{ $item->size }}
					                                    @endif
					                                    </small>
					                                    @if($item->returnItems->count() > 0)
					                                    @if($item->return_type)
					                                    <span class="badge badge-info" style="background: #ff9800;">Return</span>
					                                    @else
					                                    <span class="badge badge-primary" style="background: #ff5722;">Cancel</span>
					                                    @endif
					                                    @endif
					                                </td>
					                                 <td>
					                                   QTY: {{ $item->quantity }} / {{priceFullFormat($item->price)}}<br>
					                                  Total: {{ priceFullFormat($item->total_price) }}
					                                </td>
					                        </tr>
					                       
					                        @endforeach


					                    </tbody>

					                    <thead>
					                        <tr>
					                            <th></th>
					                            <th></th>
					                            <th>
					                            </th>
					                            <th>
                                                <label style="text-align: left;display: block;">Order Status</label>
					                            <div class="input-group input-group-sm">

					                              <select class="form-control mb-1" name="order_status" id="order_status" style="width: 250px;border: 2px solid #009688;height: 35px;">
					                                @if($order->order_status == 'pending')
					                                <option {{ $order->order_status == 'pending' ? 'selected' : '' }} value="pending">Pending</option>
					                                <option {{ $order->order_status == 'confirmed' ? 'selected' : '' }} value="confirmed">Confirmed</option>
					                                <option {{ $order->order_status == 'cancelled' ? 'selected' : '' }} value="cancelled">Cancelled</option>
					                                @elseif($order->order_status=='confirmed')
					                                <option {{ $order->order_status == 'confirmed' ? 'selected' : '' }} value="confirmed">Confirmed</option>
					                                <option {{ $order->order_status == 'shipped' ? 'selected' : '' }} value="shipped">Shipped</option>
					                                <option {{ $order->order_status == 'cancelled' ? 'selected' : '' }} value="cancelled">Cancelled</option>
					                                @elseif($order->order_status=='shipped')
					                                <option {{ $order->order_status == 'shipped' ? 'selected' : '' }} value="shipped">Shipped</option>
					                                <option {{ $order->order_status == 'delivered' ? 'selected' : '' }} value="delivered">Completed</option>
					                                <option {{ $order->order_status == 'cancelled' ? 'selected' : '' }} value="cancelled">Cancelled</option>
					                                @elseif($order->order_status=='delivered')
					                                <option {{ $order->order_status == 'delivered' ? 'selected' : '' }} value="delivered">Completed</option>
					                                @elseif($order->order_status=='cancelled')
					                                <option {{ $order->order_status == 'cancelled' ? 'selected' : '' }} value="cancelled">Cancelled</option>
					                                @elseif($order->order_status=='returned')
					                                <option {{ $order->order_status == 'returned' ? 'selected' : '' }} value="returned">Returned</option>
					                                @endif
					                            </select>
					                        </div>
					                        <label style="text-align: left;display: block;" >Payment Status</label>
					                        <select class="form-control mb-1" name="payment_status" id="payment_status" style="width: 250px;border: 2px solid #009688;height: 35px;">
					                                <option {{ $order->order_status == 'unpaid' ? 'selected' : '' }} value="unpaid">Unpaid</option>
					                                <option {{ $order->order_status == 'paid' ? 'selected' : '' }} value="paid">Paid</option>
					                         </select>
					                         {{--
					                         <div class="input-group input-group-sm" style="width: 250px;">
					                            <label style="cursor: pointer;padding: 0 5px;"><input type="checkbox" name="order_sms"> Send SMS</label>
					                            <label style="cursor: pointer;padding: 0 5px;"><input type="checkbox" name="order_mail"> Send Mail</label>
					                        </div>
					                        --}}
					                        <div class="form-group">
					                            <button class="btn btn-success" type="submit" style="width: 100%;background-color: #e91e63 !important;border-color: #e91e63;">
					                            <i class="fa fa-check"></i>
					                            Order Update
					                           </button>
					                        </div>
					                 
					                </th>
					            </tr>
					        </thead>
					    </table>
					</div><!-- /table-responsive -->
					</form>
                        </div>
                    </div>
                </div>
                
                {{--
                <div class="card">
                    <div class="card-header" style="border-bottom: 1px solid #e3ebf3;padding: 1rem;">
                        <div class="row">
                        	<div class="col-md-6">
                        		<h4 class="card-title" style="padding: 5px;">Order Payment</h4>
                        	</div>
                        	<div class="col-md-6">
                        		<div class="left-tools" style="text-align: right;">
                        			<button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#payment" style="padding: 8px 15px;border-radius: 0;"> <i class="fas fa-money"></i> payment</button>
                        		</div>
                        	</div>
                        </div>
                        
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                        	<h4>All Transections:</h4>
                            <table class="table table-bordered">
                                <tr>
                                    <th style="min-width:150px;">Billing By</th>
                                    <th style="min-width:300px;">Billing Info</th>
                                    <th style="min-width:300px;">Note</th>
                                    <th style="min-width:150px;">Amount</th>
                                </tr>
                               @foreach($order->transectionsAll as $i=>$transection)
                               <tr>
                                <td>
                                    <b>TNX:</b> {{$transection->transection_id}}<br>
                                    <b>Method:</b> {{$transection->methodOption?$transection->methodOption->name:''}}<br>
                                    
                                    <b>Date:</b> {{$transection->created_at->format('Y-m-d h:i A')}}
                                </td>
                                <td>
                                    <b>Name:</b> {{$transection->billing_name}} <br>
                                    <b>Mobile:</b> {{$transection->billing_mobile}} <br>
                                    <b>E-mail:</b> {{$transection->billing_email}} <br>
                                </td>
                                <td>
                                    <b>Type: </b>@if($transection->type==1)
                                    <span class="badge badge-success" style="background:#00bcd4;">Recharge</span>
                                    @elseif($transection->type==2)
                                    <span class="badge badge-success" style="background:#ff9800;">Re-fund Order</span>
                                    @else
                                    <span class="badge badge-success" style="background:#8bc34a;">Order Payment</span>
                                    @endif <br>
                                    <b>Address:</b> {{$transection->billing_address}}<br>
                                    <b>Note:</b> {{$transection->billing_note}}
                                </td>
                                <td>{{$transection->currency}} {{number_format($transection->amount,2)}}
                                    <br>
                                    <b>Status:</b> {{ucfirst($transection->status)}}
                                </td>
                               </tr>
                               @endforeach
                               @if($order->transectionsAll->count()==0)
                               <tr>
                                   <td colspan="4">
                                       
                                       <center>
                                           <span>No Transection</span>
                                       </center>
                                   </td>
                               </tr>
                               @endif
                            </table>
                        </div>
                    </div>
                </div>
                --}}
            </div>
        </div>
    </section>
</div>


{{--
 <!-- Modal -->
 <div class="modal fade text-left" id="payment">
   <div class="modal-dialog" role="document">
	 <div class="modal-content">
	 	<form action="{{route('admin.ordersManageUpdate',$order->id)}}" method="post">
	   		@csrf
	   	<input type="hidden" value="payment" name="actionType">
	   <div class="modal-header">
		 <h4 class="modal-title" id="myModalLabel1">Payment</h4>
		 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		   <span aria-hidden="true">&times; </span>
		 </button>
	   </div>
	   <div class="modal-body">
	   		<div class="form-group">
	   		    <input type="hidden" name="type" value="1" class="paymentType"  checked="">
	   		</div>
	   		<div class="form-group">
	   		    <label>Amount</label>
	   		    <input type="number" class="form-control PayAmount" name="amount" placeholder="Enter Amount" value="{{$order->due_amount?:''}}">
	   		</div>
	   		<div class="form-group">
	   		    <label>Method</label>
	   		    <div class="input-group">
	   		    <select class="form-control paymentMethod" name="method" required="">
	   		       @foreach($methods as $method)
                       <option value="{{$method->id}}" >{{$method->name}}</option>
                    @endforeach
	   		    </select>
	   		    <select class="form-control paymentOption" name="option" required="">
	   		        
	   		        @if($options)
    	   		        @foreach($options as $option)
                           <option value="{{$option->id}}" >{{$option->name}}</option>
                        @endforeach
	   		        @endif
	   		        
	   		    </select>
	   		    </div>
	   		</div>
	   		<div class="form-group">
	   		    <label>Note</label>
	   		    <textarea name="note" class="form-control" placeholder="Write Note.."></textarea>
	   		</div>
	   </div>
	   <div class="modal-footer">
		 <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close </button>
		 <button type="submit" class="btn btn-primary">Submit</button>
	   </div>
	   </form>
	 </div>
   </div>
 </div>
 --}}

@endsection 

@push('js')

<script>
    $(document).ready(function(){
        $('.paymentType').click(function(){
            var amount =$(this).data('amount');
            
            if(amount==0){
                amount='';
            }
            
            
            $('.PayAmount').val(amount);

        });
    });
</script>

@endpush