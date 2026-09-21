@extends('admin.layouts.app') @section('title')
<title>Products List - {{general()->title}} | {{general()->subtitle}}</title>
@endsection @push('css')
<style type="text/css">
	
	.searchResultlist {
	    position: absolute;
	    top: 40px;
	    left: 8%;
	    width: 92%;
	    z-index:9;
	}

	.searchResultlist ul {
	    border: 1px solid #ccd6e6;
	    padding: 0;
	    margin: 0;
	    list-style: none;
	    background: white;
	}

	.searchResultlist ul li {
	    padding: 2px 10px;
	    cursor: pointer;
	    border-bottom: 1px dotted #dcdee0;
	}
	.searchResultlist ul li:last-child {
		border-bottom: 0px dotted #dcdee0;
	}
</style>
@endpush @section('contents')

{{--
<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title mb-0">Purchase Invoice</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard </a></li>
                    <li class="breadcrumb-item active">Purchase Invoice</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
        <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">

            <a class="btn btn-outline-primary" href="{{route('admin.purchaseProducts')}}">Back</a>
            <a class="btn btn-outline-success" href="{{route('admin.purchaseProductsInvoice',$order->id)}}">Invoice</a>
            <a class="btn btn-outline-primary" href="{{route('admin.purchaseProductsEdit',$order->id)}}"> <i class="fa-solid fa-rotate"></i> </a>

        </div>
    </div>
</div>
--}}
<div class="content-body">
    <!-- Basic Elements start -->
    <section class="basic-elements">
        <div class="row">
            <div class="col-md-12">
            	@include('admin.alerts')
            	<form action="{{route('admin.purchaseProductsUpdate',$order->id)}}" method="post">
            		@csrf

                <div class="card">
                	<div class="card-header" style="border-bottom: 1px solid #e3ebf3;padding: 1rem;">
                        <div class="row">
                        	<div class="col-md-6">
                        		<h4 class="card-title" style="padding: 5px;">Purchase Invoice</h4>
                        	</div>
                        	<div class="col-md-6">
                        		<div class="left-tools" style="text-align: right;">
                        			<a href="{{route('admin.purchaseProducts')}}"><span class="btn btn-sm btn-primary" style="padding: 8px 15px;border-radius: 0;"> <i class="fas fa-arrow-left"></i> Back</span></a>
                        			<a href="{{route('admin.purchaseProductsInvoice',$order->id)}}"><span class="btn btn-sm btn-success" style="padding: 8px 15px;border-radius: 0;"> <i class="fas fa-print"></i> Invoice</span></a>
                        			<a href="{{route('admin.purchaseProductsEdit',$order->id)}}"><span class="btn btn-sm btn-info" style="padding: 8px 15px;border-radius: 0;"><i class="fa-solid fa-rotate"></i></span></a>
                        		</div>
                        	</div>
                        </div>
                        
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <div class="row">
                            	<div class="col-md-4">
                            		<div class="form-group">
                            			<label>Supplier*</label>
                            			<select class="form-control" name="supplier" required="">
                            				<option value="">Select Supplier</option>
                            				@foreach($suppliers as $supplier)
                            				<option value="{{$supplier->id}}" {{$supplier->id==$order->user_id?'selected':''}}>{{$supplier->name}} ({{$supplier->mobile}})</option>
                            				@endforeach
                            			</select>
                            		</div>
                            	</div>
                            	<div class="col-md-4">
                            		<div class="form-group">
                            			<label>Invoice No*</label>
                            			<input type="text" name="invoice" value="{{$order->created_at->format('dmY').''.$order->id}}" class="form-control" placeholder="Enter Invoice No" required="">
                            		</div>
                            	</div>
                            	<div class="col-md-4">
                            		<div class="form-group">
                            			<label>Purchase Date*</label>
                            			<input type="date" value="{{$order->created_at->format('Y-m-d')}}" name="date" class="form-control" required="">
                            		</div>
                            	</div>
                            	<div class="col-md-12">
                            		<div class="form-group">
                            			<label>Note</label>
                            			<textarea class="form-control" name="note" placeholder="Write Invoice Note.">{{$order->note}}</textarea>
                            		</div>
                            	</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    
                    <div class="card-content">
                        <div class="card-body">
                        	<div class="row">
                        		<div class="col-md-2"></div>
                        		<div class="col-md-5">
                        			<div class="input-group">
                        				<span class="input-group-addon" style="width: 8%;text-align: center;padding: 10px;">
	                                        <i class="fa fa-search"></i>
	                                    </span>
	                                    <div class="SearchContain" style="width: 92%;">
	                                    <input type="text" class="form-control serchProducts" data-type="search" placeholder="Search Product Name" autocomplete="off">
	                                    <div class="searchResultlist">
	                                    	@include('admin.purchase.includes.searchResult')
	                                    </div>
	                                    </div>
                        			</div>
                        		</div>
                        		<div class="col-md-3">
                        			<div class="input-group">
                        				<span class="input-group-addon" style="text-align: center;padding: 10px;">
	                                        <i class="fa fa-barcode"></i>
	                                    </span>
	                                    <input type="text" class="form-control serchBarcodeProdu" data-type="barcode" placeholder="Search Product Barcode" autocomplete="off">
                        			</div>
                        		</div>
                        	</div>
                        	<br>
                        	<div class="purchaseItemsSection">
                        		
	                           @include('admin.purchase.includes.purchaseItems')

                            </div>

                        </div>
                    </div>
                </div>

            	</form>
            </div>
        </div>
    </section>
    <!-- Basic Inputs end -->
</div>

@endsection 

@push('js')

<script type="text/javascript">

	$(".searchResultlist").hide();

	$(document).on('click', function(e) {

	    var container = $(".SearchContain");
	    var containerClose = $(".searchResultlist");
	    
	    if (!$(e.target).closest(container).length) {
	        containerClose.hide();
	    }else{
	    	containerClose.show();
	    }

	});

	var url ="{{route('admin.purchaseProductsEdit',$order->id)}}"; 
	var key;
	var type;
	var id;
	
	$(document).on('click','.searchResultlist ul li,.itemRemovePurchase',function(){
		id =$(this).data('id');
		type =$(this).data('type');
		AjaxParchaseItems(url,id,type,key);
		$(".searchResultlist").hide();
	});

	$(document).on('change','.itemUpdatePurchase',function(){
		id =$(this).data('id');
		type =$(this).data('type');
		key =$(this).val();
		if(key==''){
			key=0;
		}
		AjaxParchaseItems(url,id,type,key);
	});
    
    //*****************Payment Add and Delete ***************        
        $(document).on('click','.PaymentAddformSubmit,.PaymentDelete',function(e){
            e.preventDefault();
            type=$(this).data('type');
            var method =$('.paymentMethod').val();
            var option =$('.paymentOption').val();
            var amount =$('.paymentAmount').val();
            
            $('.paymentMethodError').empty();
            $('.paymentOptionError').empty();
            $('.paymentAmountError').empty();
            
            if(type=='addPayment'){
                
            if(method!='' && option!='' && amount!=''){
            
                $.ajax({
                  url:url,
                  dataType: 'json',
                  cache: false,
                  data: {'method':method,'option':option,'amount':amount,'type':type},
                  success : function(data){

                    $('.purchaseItemsSection').empty().append(data.view);
    
                  },error: function () {
                      alert('error');
    
                    }
                });
                
            }else{
                if(method==''){
                    $('.paymentMethodError').empty().append('This field Is Required');
                }
                if(option==''){
                    $('.paymentOptionError').empty().append('This field Is Required');
                }
                if(amount==''){
                    $('.paymentAmountError').empty().append('This field Is Required');
                }

            }
            
            }else if(type=='deletePayment'){
                
                id=$(this).data('id');

                if(confirm('Are You Want To Transection Delete?') && id!=''){
                    
                    $.ajax({
                      url:url,
                      dataType: 'json',
                      cache: false,
                      data: {'id':id,'type':type},
                      success : function(data){
        
                        $('.purchaseItemsSection').empty().append(data.view);
                        
        
                      },error: function () {
                          alert('error');
        
                        }
                    });
                                        
                }
            }
            
        });
	



	function AjaxParchaseItems(url,id,type,key){

		$.ajax({
          url:url,
          dataType: 'json',
          cache: false,
          data: {'key':key,'id':id,'type':type},
           success : function(data){

            $('.purchaseItemsSection').empty().append(data.view);

           },error: function () {
             // alert('error');

            }
        });

	}

	$(document).on('keyup','.serchBarcodeProdu',function(){
	    
		key =$(this).val();
		type =$(this).data('type');

		$.ajax({
          url:url,
          dataType: 'json',
          cache: false,
          data: {'key':key,'type':type},
          success : function(data){
          	 //$(this).val('');
             if(data.count > 1){
                $('.searchResultlist').empty().append(data.view);
                $(".searchResultlist").show();
             }
             $('.purchaseItemsSection').empty().append(data.datasItems);

          },error: function () {
             // alert('error');

            }
        });


	});


	$(document).on('keyup','.serchProducts',function(){

		key =$(this).val();
		type ='search';

		$.ajax({
          url:url,
          dataType: 'json',
          cache: false,
          data: {'key':key,'type':type},
           success : function(data){

            $('.searchResultlist').empty().append(data.view);

           },error: function () {
             // alert('error');

            }
        });


	});


</script>

@endpush
