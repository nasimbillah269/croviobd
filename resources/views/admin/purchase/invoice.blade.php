@extends('admin.layouts.app') @section('title')
<title>Invoice - {{general()->title}} | {{general()->subtitle}}</title>
@endsection @push('css')
<style type="text/css">
	
    .invoice-inner{
        padding: 30px;
        width: 100%;
        box-shadow: 0px 0px 10px #ccc;
    }
    
    .demo-info,.invoice-info{
        font-size: 12px;
    }
    
    .invoice-inner h6,p,h5{
        margin: 0;
    }

    .demo-info img{
        width: 150px;
    }
    
    p.billingTo {
        padding: 3px 10px;
        border: 2px solid green;
        border-radius: 10px;
        font-size: 17px;
        margin: 5px 0px;
        font-weight: 500;
    }
    
    .remarkTable{
        margin: 0;
        font-size: 85%;
    }
    
    .remarkTable th, .remarkTable td{
        padding: 3px 0px;;
    }
    
    .subtotalTable{
        margin: 0;
        font-size: 85%;
    }
    
    .subtotalTable th, .subtotalTable td{
        padding: 1px 0px;
        border: none;
    }
    
    .payment-details{
        margin-top: 15px;
    }
    
    .signature-part{
        margin-top: 130px;
    }
    
    .signature-part span{
        line-height: 4px;
        display: block;
    }
    .posinvoice-inner {
        box-shadow: 0px 0px 10px #ccc;

    }

</style>
@endpush @section('contents')


<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title mb-0">Invoice</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard </a></li>
                    <li class="breadcrumb-item active">Invoice</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
        <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">

            <a class="btn btn-outline-primary" href="{{route('admin.purchaseProducts')}}">Back</a>
            <a class="btn btn-outline-info" href="{{route('admin.purchaseProductsEdit',$order->id)}}">Edit</a>
            <button class="btn btn-success" id="PrintAction" ><i class="fa fa-print"></i> Print</button>

            <a class="btn btn-outline-primary" href="{{route('admin.purchaseProductsInvoice',$order->id)}}">
                <i class="fa-solid fa-rotate"></i>
            </a>
        </div>
    </div>
</div>

<div class="content-body">
    <!-- Basic Elements start -->
    <section class="basic-elements">
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8">
                <div class="card">

            	        <div class="invoice-inner PrintAreaContact">
                    <div class="row">
                        <div class="col-8">

                            <div class="demo-info">
                                <img src="{{asset(general()->logo())}}" alt="company-logo" style="max-width:150px;">
                                <h6>{{general()->title}}</h6>
                                <p>{!!general()->address!!}</p>
                                <p><b>Mobile:</b> {{general()->mobile}}</p>
                                <p><b>Email:</b> {{general()->email}}</p>
                                <p><b>Sold By:</b> {{$order->posByuser?$order->posByuser->name:''}}</p>
                                <p><b>Remarks:</b> {!!$order->note!!}</p>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="invoice-info">
                                <h6>Invoice</h6>
                                <p>Invoice No: <b>{{$order->invoice}}</b></p>
                                <p>Date: {{$order->created_at->format('d/m/Y')}}</p>
                                <p>Time: {{$order->created_at->format('h:i A')}}</p>
                                <p class="billingTo">Billing To</p>
                                <h5>{{$order->user?$order->user->name:'Not Found'}}</h5>
                                <p><b>Mobile:</b> {{$order->user?$order->user->mobile:''}}</p>
                                <p><b>Address:</b> {{$order->user?$order->user->fullAddress():''}}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="invoice-products">
                        <table class="table remarkTable">
                          <thead>
                            <tr>
                              <th style="width: 10%;">SL.</th>
                              <th style="width: 35%; text-align: center;">Item</th>
                              <th style="width: 15%;text-align: center;">Quantity</th>
                              <th style="width: 20%;text-align: center;">Rate</th>
                              <th style="text-align: end;">Total</th>
                            </tr>
                          </thead>
                          <tbody>
                          	@foreach($order->items as $i=>$item)
	                            <tr>
	                              <td>{{$i+1}}</td>
	                              <td style="text-align: center;">{{$item->product_name}}</td>
	                              <td style="text-align: center;">{{$item->quantity}}</td>
	                              <td style="text-align: center;">{{priceFormat($item->price)}}</td>
	                              <td style="text-align: end;">{{priceFormat($item->total_price)}}</td>
	                            </tr>
                            @endforeach
                            <tr>
                            	<th colspan="4" style="text-align: end;border-color: white;">Subtotal</th>
                            	<td style="text-align: end;border-color: white;">{{priceFormat($order->total_price)}}</td>
                            </tr>
                            <tr>
                            	<th colspan="4" style="text-align: end;border-color: white;">Discount</th>
                            	<td style="text-align: end;border-color: white;">{{priceFormat($order->discount_price)}}</td>
                            </tr>
                            <tr>
                            	<th colspan="4" style="text-align: end;border-color: white;">Total Amount</th>
                            	<td style="text-align: end;border-color: white;">{{priceFormat($order->grand_total)}}</td>
                            </tr>
                            <tr>
                            	<th colspan="4" style="text-align: end;border-color: white;">Paid</th>
                            	<td style="text-align: end;border-color: white;">{{priceFormat($order->paid_amount)}}</td>
                            </tr>
                            <tr>
                            	<th colspan="4" style="text-align: end;border-color: white;">Due</th>
                            	<td style="text-align: end;border-color: white;">{{priceFormat($order->due_amount)}}</td>
                            </tr>
                          </tbody>
                        </table>
                        
                        <div class="payment-details">
                            <span><b>Payment Method</b></span>
                            <div class="row">
                                <div class="col-8">
                                    <table class="table remarkTable">
                                      <thead>
                                        <tr>
                                          <th style="width: 10%;">SL.</th>
                                          <th style="width: 40%; text-align: center;">Payment Method</th>
                                          <th style="text-align: center;">Payment By</th>
                                          <th style="text-align: end;width: 150px;">Amount</th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                      	@foreach($order->transections as $transection)
                                        <tr>
                                          <td>1</td>
                                          <td style="text-align: center;">{{$transection->method?$transection->method->name:'No Found'}}</td>
                                          <td style="text-align: center;">{{$transection->methodOption?$transection->methodOption->name:'-'}}</td>
                                          <td style="text-align: end;">{{priceFullFormat($transection->amount)}}</td>
                                        </tr>
                                        @endforeach
                                        <tr>
                                        	<td colspan="3"></td>
                                        	<td style="text-align: end;">{{priceFullFormat($order->transections->sum('amount'))}}</td>
                                        </tr>
                                      </tbody>
                                    </table>
                                </div>
                                <div class="col-4"></div>
                            </div>
                        </div>
                        
                        <div class="signature-part">
                            <div class="row">
                                <div class="col-6">
                                    ------------------<br>
                                    <span><b>Received By</b></span>
                                </div>
                                <div class="col-6" style="text-align: end;">
                                    ------------------<br>
                                    <span><b>Authorised By</b></span>
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

@endsection 

@push('js') 


<script type="text/javascript">
	
</script>

@endpush
