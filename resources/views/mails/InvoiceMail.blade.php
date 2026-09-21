<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="shortcut icon" href="{{asset(general()->favicon())}}">
<title>Registration Mail Form {{general()->title}} </title>
<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300&display=swap" rel="stylesheet">

<style>

body{
margin:0;
background:#f1f1f1;
}
table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

td, th {
  border: 1px solid #dddddd;
  text-align: left;
  font-size:14px;
  padding: 8px;
}

.row{
display: flex;
}
.col-1 {
    flex: 0 0 8.33333%;
    max-width: 8.33333%;
}
.col-3 {
    flex: 0 0 25%;
    max-width: 25%;
}
.col-4 {
    flex: 0 0 33.33333%;
    max-width: 33.33333%;
}

.col-5 {
    flex: 0 0 41.66667%;
    max-width: 41.66667%;
}
.col-6 {
    flex: 0 0 50%;
    max-width: 50%;
}
.col-7 {
    flex: 0 0 58.33333%;
    max-width: 58.33333%;
}

.invoice-inner {
        /*box-shadow: 0px 0px 5px #ccc;*/
        padding: 10px 20px;
    }
    
    .invoice-header {
        padding: 20px 0px 35px;
    }
    
    
    .invoice-header h6{
        margin-top: 15px!important;
    }
    
    .invoice-header h6, p{
        margin: 0;
        line-height: 15px;
        font-size: 12px;
    }
    
    .invoice-inner h2{
        margin: 10px 0px;
        font-size: 41px;
        letter-spacing: 3px;
        color: #00549e;
    }
    
    .ordrinfotable {
        padding: 10px 12px;
        border: 1px solid #ccc;
    }
    
    table.tableOrderinfo.table {
        margin: 0;
        padding: 0;
    }
    
    .tableOrderinfo td{
        padding: 0;
        font-size: 13px;
        line-height: 17px;
        border: none;
    }
    
    .mainTable{
        margin: 30px 0;
    }
    
    .mainproducttable{
        margin: 0;
        padding: 0;
        width: 100%;
    }
    
    .mainproducttable td{
        padding: 5px 7px;
        font-size: 12px;
        border: 1px solid #ccc;
    }
    
    tr.headerTable {
        background-color: #e2e2e2;
    }
    
    tr.headerTable td{
        font-size: 13px;
        padding: 7px;
    }
    
    .boxFrozen {
        border: 1px solid #ccc;
        text-align: center;
        margin-bottom: 6px;
        border-bottom: 0px solid #ccc;
    }
    
    .boxFrozen h3{
        padding: 5px;
        color: #fff;
        margin: 0;
        background-color: #ff1414;
        font-size: 16px;
    }
    
    .boxFrozen p{
        font-size: 16px;
        padding: 5px 0px;
        border-bottom: 1px solid #ccc;
    }
    
    .footerInvoice{
        margin-top: 100px;
    }

    @media only screen and (max-width: 567px) {
        .invoice-inner {
            padding: 10px;
            margin: 10px 0px;
        }
        .invoiceContainer{
            padding:0;
        }
    }

</style>

</head>
<body>
<div style="margin:25px auto;width:80%;min-width:600px;overflow:auto;padding:15px;background:#fff;">

<div class="invoiceContainer">
	<div class="invoice-inner">
		<div class="invoice-header">
			<div class="row">
				<div class="col-4">
					<img src="{{asset(general()->logo())}}" style="max-height:80px;">
				</div>
				<div class="col-1"></div>
				<div class="col-7" style="text-align: end;">
					<h6>CONTACT INFORMATION:</h6>
					<p>{{general()->address_one}}</p>
					<p>{{general()->mobile}}</p>
					<p>{{general()->website}}</p>
					<p>{{general()->email}}</p>
				</div>
			</div>
		</div>
		<hr style="border: 2px solid #00549e; margin: 0;">
		<h2>INVOICE</h2>
		<div class="orderInfo">
			<div class="row" style="flex-wrap: wrap;">
				<div class="col-5 mt-3" style="flex: 0 0 41.666667%;max-width: 41.666667%;margin-top: 1rem;">
					<p style="margin: 0;line-height: 15px;font-size: 12px;">Order From:</p>

					<table class="table table-striped infoTable">
					    <tr>
					        <td><b>Name:</b> {{$order->name}}</td>
					    </tr>
					    <tr>
					        <td><b>Mobile:</b> {{$order->mobile}}</td>
					    </tr>
					    <tr>
					        <td><b>Address:</b> {{$order->address}}</td>
					    </tr>
					    <tr>
					        <td><b>Area:</b> {{$order->area_name}}</td>
					    </tr>
					 
					</table>
				</div>
				<div class="col-2 mt-3" style="flex: 0 0 16.666667%;max-width: 16.666667%;margin-top: 1rem;">
					
				</div>
				<div class="col-5" style="flex: 0 0 41.666667%;max-width: 41.666667%;">
					<div class="ordrinfotable" style="border: 1px solid #ccc;padding: 10px 12px;">
						<table class="tableOrderinfo table">
						  <thead>
						    <tr>
						      <td style="width: 40%;padding: 0;font-size: 13px;line-height: 17px;border: none;">Order Number</td>
						      <td style="padding: 0;font-size: 13px;line-height: 17px;border: none;">: {{ $order->invoice}}</td>
						    </tr>
						    <tr>
						      <td style="width: 40%;padding: 0;font-size: 13px;line-height: 17px;border: none;">Order Date</td>
						      <td style="padding: 0;font-size: 13px;line-height: 17px;border: none;">: {{ $order->created_at->format('d-m-Y h:i A') }}</td>
						    </tr>
						    <tr>
						      <td style="width: 40%;padding: 0;font-size: 13px;line-height: 17px;border: none;">Payment Method</td>
						      <td style="padding: 0;font-size: 13px;line-height: 17px;border: none;">: Cash On Delivery</td>
						    </tr>
						    <tr>
						      <td style="width: 40%;padding: 0;font-size: 13px;line-height: 17px;border: none;">Order Status</td>
						      <td style="padding: 0;font-size: 13px;line-height: 17px;border: none;">: {{ucfirst($order->order_status)}}</td>
						    </tr>
						  </thead>
						</table>
					</div>							
				</div>
			</div>
		</div>
        
        <div class="table-responsive">
			<div class="mainTable" style="margin: 30px 0;">
				<table class="table mainproducttable">
				  <thead>
				    <tr class="headerTable">
				      <td style="width: 40%;">Product Name & Description</td>
				      <td style="width: 12%; text-align: center;">Unit Price</td>
				      <td style="width: 12%; text-align: center;">Quantity</td>
				      <td style="width: 15%; text-align: center;">Total Price</td>
				    </tr>
				  </thead>
				  <tbody>
				      
				    @foreach($order->items as $i=>$item)
				    <tr>
				      <td>{{ $item->product_name }}</td>
				      <td style="text-align: center;">{{ priceFormat($item->price) }}</td>
				      <td style="text-align: center;">{{$item->quantity}}</td>
				      <td style="text-align: center;">{{ priceFormat($item->final_price) }}</td>
				    </tr>
				    @endforeach
				    
				    <tr>
				      <td colspan="3"  style="text-align: end;">Subtotal</td>
				      <td style="text-align: center;">{{ priceFormat($order->total_price) }}</td>
				    </tr>
				    @if($order->tax > 0)
				    <tr>
				      <td colspan="3" style="text-align: end;">Tax</td>
				      <td style="text-align: center;">{{ priceFormat($order->tax) }}</td>
				    </tr>
				    @endif
				    <tr>
				      <td colspan="3" style="text-align: end;">Shipping</td>
				      <td style="text-align: center;">
				      @if($order->shipping_charge>0)
				      {{priceFormat($order->shipping_charge)}}
				      @else
				      Free Shipping
				      @endif
				      </td>
				    </tr>
				    <tr>
				      <td colspan="3" style="text-align: end;">Grand Total</td>
				      <td style="text-align: center;">{{ priceFormat($order->grand_total) }}</td>
				    </tr>
				  </tbody>
				</table>
			</div>
		</div>
		<div class="frozenTable">
		    

			<div class="row" style="display:flex;">
			    
			    <div class="col-8" style="flex: 0 0 66.666667%;max-width: 66.666667%;">
			        @if($order->note)
			        <b>Order Note</b><br>
				    <p>{!!$order->note!!}</p>
			        @endif
			    </div>
				<div class="col-2" style="flex: 0 0 16.666667%;max-width: 16.666667%;">
				
				
				</div>
				<div class="col-md-2" style="flex: 0 0 16.666667%;max-width: 16.666667%;">
				    @if($order->payment_status=='paid')
				    <div class="paidsStatus" style="text-align:center;">
				        <img src="{{asset('medies/paid.png')}}" style="max-width:80px;">
				    </div>
				     @endif
				</div>
			</div>
		</div>

		<div class="footerInvoice">
			<div class="row">
				<div class="col-6">
					<p>Thank you for shopping from {{general()->title}}</p>
				</div>
				<div class="col-6" style="text-align: end;">
					------------------------
					<p>Authorised Sign</p>
				</div>
			</div>
		</div>
	</div>
</div>


</div>
</body>
</html>

