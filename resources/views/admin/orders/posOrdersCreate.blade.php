@extends('admin.layouts.app') @section('title')
<title>POS Order List - {{general()->title}} | {{general()->subtitle}}</title>
@endsection @push('css')
<style type="text/css">
    
    .productGrid {
        text-align: center;
        background: white;
        cursor: pointer;
        box-shadow: 1px 1px 5px 0px rgb(0 0 0 / 10%);
    -webkit-box-shadow: 1px 1px 5px 0px rgb(0 0 0 / 10%);
    -moz-box-shadow: 1px 1px 5px 0px rgba(0, 0, 0, 0.1);

    }

    .productGrid img {
        max-width: 100%;
        height: 150px;
        max-height: 150px;
    }

    .productGrid p {
        margin: 0;
        background: #c3f8ff;
        line-height: 16px;
        padding: 5px;
        font-size: 13px;
        font-weight: bold;
    }

    .productArea{
        overflow: auto;
        max-height: 500px;
        position: relative;
    }

    .infoTable tr td{
        padding: 5px;
    }
    .infoTable tr th{
        padding: 5px;
    }
    .infoTableHight{
        height: 250px;
    }

    .loader {
        margin: auto;
        border: 10px solid #EAF0F6;
        border-radius: 50%;
        border-top: 10px solid #ff5722;
        width: 60px;
        height: 60px;
        animation: spinner 4s linear infinite;
    }
    
    .discountInputEdit{
        display:none;
    }
    .shippingInputEdit{
        display:none;
    }
    
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        top: 0px !important;
    }
    
    @keyframes spinner {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    .productArea::-webkit-scrollbar {
      width: 2px;
    }


    .productArea::-webkit-scrollbar-thumb {
      background: green; 
    }

    .infoTableHight::-webkit-scrollbar {
      width: 2px;
    }


    .infoTableHight::-webkit-scrollbar-thumb {
      background: green; 
    }

    /*.select2-container {
        width: 65% !important;
    }
    */

    .select2-container .select2-selection--single{
        height: 30px;
        padding: 0 !important;
    }

    .itemQtyinput{
        width:70px;
        text-align: center;
    }

    .actionLoading {
        position: absolute;
        top: 0;
        background: #80808078;
        text-align: center;
        width: 100%;
        height: 100%;
        display: none;
    }

    .LoadingRotated{
        top: 40%;
        position: absolute;
        left: 40%;
    }

    .ItemMinus{
        padding: 5px;
        background: #eceef4;
        border-radius: 0;
        margin: 0px;
        cursor: not-allowed;
    }
    .ItemMinus.ItemMinusClick,.ItemPlus.ItemPlusClick {
        cursor: pointer;
    }

    .ItemPlus{
        padding: 5px;
        background: #2dcee3;
        color: white;
        cursor: not-allowed;
    }


    .checkOutbtn {
        cursor: no-drop !important;
        background: gray;
        background-color: #d1d3d2;
        border-color: #d1d3d2;
        color: gray;
    }

    input[type=number] {
      border: 1px solid #ccc;
      outline: none;
    }

    /* Chrome, Safari, Edge, Opera */
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
      -webkit-appearance: none;
      margin: 0;
    }
    textarea:focus, input:focus{
    outline: none;
    }
    /* Firefox */
    input[type=number] {
      -moz-appearance: textfield;
    }


    .invoice-inner{
        padding: 30px;
        width: 100%;
        box-shadow: 0px 0px 10px #ccc;
        margin: 30px auto;
    }
    
    .demo-info{
        font-size: 85%;
    }
    
    .demo-info img{
        width: 30%;
    }
    
    .demo-info h6{
        margin: 0;
    }
    
    .demo-info p{
        margin: 0;
    }
    
    .invoice-info{
        font-size: 85%;
    }
    
    .invoice-info h6{
        margin: 0;
    }
    
    .invoice-info h5{
        margin: 0;
    }
    
    .invoice-info p{
        margin: 0;
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
{{--
<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title mb-0">POS Order</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard </a></li>
                    <li class="breadcrumb-item active">POS Order</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
        <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
            <a class="btn btn-outline-primary" href="{{route('admin.posOrdersCreate')}}">New POS</a>
            <a class="btn btn-outline-primary" href="{{route('admin.orders')}}">
                <i class="fa-solid fa-rotate"></i>
            </a>
        </div>
    </div>
</div>
--}}
<div class="content-body">
    <!-- Basic Elements start -->
    <section class="basic-elements">
        <div class="row">
            <div class="col-md-5">
                @include('admin.alerts')
                <div class="productAreSection">
                    
                
                <div class="card">
                    <!-- <div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
                        <h4 class="card-title">Our Products</h4>
                    </div> -->
                    <div class="card-content">
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-6" style="margin-bottom: 0;">
                                   <select class="form-control form-control-sm searchProduct searchProductCat" data-type="category">
                                       <option value="">All Category</option>
                                       @foreach($categories as $category)
                                       <option value="{{$category->id}}">{{$category->name}}</option>
                                       @endforeach

                                   </select>
                               </div>
                               <div class="form-group col-md-6" style="margin-bottom: 0;">
                                   <select class="form-control form-control-sm searchProduct searchProductBrand" data-type="brand">
                                       <option value="">All Brand</option>
                                       @foreach($brands as $brand)
                                       <option value="{{$brand->id}}">{{$brand->name}}</option>
                                       @endforeach
                                   </select>
                               </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
                <div class="productArea">
                    <div class="row GetProductsSearh" style="margin:0;">

                        @include('admin.orders.includes.posSearchProduct')

                    </div>
                    <div class="actionLoading">
                        <span class="loader LoadingRotated"></span>
                    </div>
                </div>

            </div>

            </div>
            <div class="col-md-7">
                @include('admin.alerts')

                <div class="card">
                    <div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
                        <h4 class="card-title">POS</h4>
                        <div class="ibox-tools">
                            
                            <a class="btn btn-danger ClearPOS" data-type="clearpos" href="javascript:void(0)" style="background-color: #ff2845!important;"><i class="fa fa-trash"></i> Clear</a>
                            <!--<a class="btn btn-danger"  href="javascript:void(0)" data-toggle="modal" data-target="#holdinginvoice" style="background-color: #ff5722 !important;"><i class="fa fa-list"></i> Hold</a>-->
                            <a class="btn btn-info"  href="{{route('admin.posOrdersInvoice',$order->id)}}" target="_blank" ><i class="fa fa-print"></i> Invoice</a>
                            <a class="btn btn-success" href="javascript:void(0)" data-toggle="modal" data-target="#payment"><i class="fa fa-money"></i> Payment</a>
                        </div>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="input-group">
                                       <span class="input-group-addon" style="padding: 5px 10px"><i class="fa fa-search"></i> </span>
                                       <input type="text" placeholder="Search key" class="form-control form-control-sm searchProductKey">
                                   </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row" style="margin:0;">
                                        <div class="col-2" style="padding:0;">
                                            <span class="input-group-addon" style="padding: 5px 10px;display: block;"><i class="fa fa-user"></i> </span>
                                        </div>
                                        <div class="col-8" style="padding:0;">
                                            <select class="select2 form-control form-control-sm customerUser" data-type="customer">
                                                     <option value="0">Geust </option>
                                                     @foreach($users as $user)
                                                     <option value="{{$user->id}}" {{$user->id==$order->user_id?'selected':''}}>{{$user->name}}  </option>
                                                     @endforeach()
                                             </select>
                                        </div>
                                        <div class="col-2" style="padding:0;">
                                            <span class="input-group-addon" style="padding: 5px 10px;display: block;"><i class="fa fa-plus"></i> </span>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group">
                                       <span class="input-group-addon" style="padding: 5px 10px"><i class="fa fa-barcode"></i> </span>
                                       <input type="text" placeholder="Search barcode" class="form-control form-control-sm searchProductBarcode">
                                   </div>
                                </div>
                            </div>    
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-content">
                        <div class="card-body InvoiceItemsSection">

                            @include('admin.orders.includes.posInvoiceItem')
                            

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <!-- Basic Inputs end -->
</div>


 <!-- Modal -->
 <div class="modal fade text-left" id="invoice"  >
   <div class="modal-dialog modal-lg" role="document">
     <div class="modal-content">

       <div class="modal-header">
         <h4 class="modal-title" id="myModalLabel1">Invoice</h4>
         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
           <span aria-hidden="true">&times; </span>
         </button>
       </div>
       <div class="modal-body">
            
             @include('admin.orders.includes.posInvoice')

       </div>
       <div class="modal-footer">
         <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close </button>
       </div>

     </div>
   </div>
 </div>

  <!-- Modal -->
 <div class="modal fade text-left" id="holdinginvoice"  >
   <div class="modal-dialog modal-lg" role="document">
     <div class="modal-content">

       <div class="modal-header">
         <h4 class="modal-title" id="myModalLabel1">Hold Invoice</h4>
         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
           <span aria-hidden="true">&times; </span>
         </button>
       </div>
       <div class="modal-body">
            
            <div class="table-responsive">
                <h3>Hold Invoice</h3>
                <table class="table table-bordered">
                    <tr>
                        <td>Invoice: 25145824145</td>
                        <td><input type="text" name="note" class="form-control" placeholder="Write Note..."></td>
                        <td>
                            <button type="button" class="btn btn-danger" onclick="return confirm('Are You Want To Hold This Invoice?')"><i class="fa fa-stop"></i> HOlD</button>
                        </td>
                    </tr>
                </table>
            </div>
            <hr>
            <div class="table-responsive">
                <h3>Holding Invoices List</h3>
                <table class="table table-bordered">
                    <tr>
                        <th>SL</th>
                        <th>Invoice</th>
                        <th>Amount</th>
                        <th>Note</th>
                        <th>Action</th>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td>
                            125145528541
                        </td>
                        <td>BDT 25,151.0</td>
                        <td>Need to wait</td>
                        <td>
                            <button type="button" class="btn btn-info" onclick="return confirm('Are You Want To UnHold This Invoice?')"><i class="fa fa-stop"></i> UNHOlD</button>
                        </td>
                    </tr>
                </table>
            </div>

       </div>
       <div class="modal-footer">
         <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close </button>
       </div>

     </div>
   </div>
 </div>





 <!-- Modal -->
 <div class="modal fade text-left" id="payment"  >
   <div class="modal-dialog modal-lg InvoicePaymentSection" role="document">
        @include('admin.orders.includes.posInvoicePayments')
   </div>
 </div>



@endsection @push('js')

<script type="text/javascript">
    $(document).ready(function(){


         //*****************Order Check Out Submit *************** 

        $(document).on('click','.SubmitCheckOut',function(){
            
            var ItemCount=$(this).data('items');
            
            if(ItemCount==0){
                alert('Need To Buy any Item First.');
            }else{
            
            if(confirm('Are You Want To Checkout Confirm?')){
                var url ="{{route('admin.posOrdersManageUpdate',$order->id)}}";
                var received =$('.ReceivedAmount').val();
                var changed =$('.ChangeAmount').val();

                
                $.ajax({
                  url:url,
                  dataType: 'json',
                  cache: false,
                  data: {'received':received,'changed':changed},
                  success : function(data){
                        if(data.success){
                            window.location.replace("{{route('admin.posOrdersInvoice',$order->id)}}");
                        }else{
                            window.location.reload();
                        }
                  },error: function () {
                      alert('error');

                    }
                });

            }
            
            }

        });

        
        //*****************Order Clear all Items Action *************** 
        $(document).on('click','.ClearPOS',function(){

            if(confirm('Are You Want To Clear All?')){
                var url ="{{route('admin.posOrdersManageUpdate',$order->id)}}";
                var type =$(this).data('type');
                var id =null;
                $.ajax({
                  url:url,
                  dataType: 'json',
                  cache: false,
                  data: {'type':type,'id':id},
                   success : function(data){
                        if(data.success){
                            window.location.replace("{{route('admin.posOrdersCreate')}}");
                        }else{
                            window.location.reload();
                        }
                   },error: function () {
                      alert('error');

                    }
                });

            }

        });

        


        var type;
        var id;
        var invoice;
        var url="{{route('admin.posOrdersCreate')}}";
        var cat;
        var brand;
        var key;
        var value;
        var barcode;
        var qty;
        var discount;
        var discounttype='Flat';
        var SubTotalsPay =0;
        var disTotal=0;

        $(document).on('change','.searchProduct',function(){
           cat = $('.searchProductCat').val();
           brand = $('.searchProductBrand').val();
           barcode=null;
           type='search';
           key=null;
           $('.searchProductKey').val('');
           ActionAjax(url,type,cat,brand,key,barcode);
        });

        $(document).on('keyup','.searchProductKey',function(){
            type='search';
            key=$(this).val();
            barcode=null;
            type='search';
            ActionAjax(url,type,cat,brand,key,barcode);

        });


        $(document).on('keyup','.searchProductBarcode',function(){
            
            barcode=$(this).val();
            key=null;
            cat=null;
            brand=null;
            ActionAjax(url,type,cat,brand,key,barcode);

        });

        $(document).on('change','.OrderUpdate',function(){

             type =$(this).data('type');
             value = $(this).val();
             
             url ="{{route('admin.posOrdersPaymentsUpdate',$order->id)}}";

            ActionMethodAjax(id,type,url,value);
            
        });
        
        //*****************Shipping Charge Apply for Order *************** 
        $(document).on('click','.shippingEdit',function(){
            $('.shippingInputEdit').show();
            $('.shippingText').hide();
        });
        
        $(document).on('keyup','.shippingInputAmount',function(){
            discount = parseFloat($('.shippingInputAmount').val());
            if(isNaN(discount) || discount==''){
                discount=0;
            }
        });
        
        $(document).on('click','.shippingInputEditAction',function(){
            $('.shippingInputEdit').hide();
            $('.shippingText').show();
            type ='shippingAmount'
            ActionItemAjax(id,type,invoice,url,qty,discount,discounttype);
        });
        
        
        //*****************Discount Apply for Order *************** 
         $(document).on('click','.discountEdit',function(){
            $('.discountInputEdit').show();
            $('.discountText').hide();
        });
        
        $(document).on('keyup','.discountInputAmount',function(){
            SubTotalsPay =$('.SubTotalsAmount').data('amount');
            discounttype=$('.discountInputType').val();
            discount = parseFloat($('.discountInputAmount').val());
            if(isNaN(discount) || discount==''){
                discount=0;
            }
            
            DiscountCalculate(SubTotalsPay,discounttype,discount,disTotal);
        });
        
        $(document).on('change','.discountInputType',function(){
            SubTotalsPay =$('.SubTotalsAmount').data('amount');
            discounttype=$('.discountInputType').val();
            discount = parseFloat($('.discountInputAmount').val());
            if(isNaN(discount) || discount==''){
                discount=0;
            }
            DiscountCalculate(SubTotalsPay,discounttype,discount,disTotal);
        });
        
        function DiscountCalculate(SubTotalsPay,discounttype,discount,disTotal){
            
            if(discounttype=='Percantage'){
                if(discount > 100){
                disTotal =100;
                $('.discountInputAmount').val(disTotal);
                alert('Can Not Discount over 100%');
                }else{
                  disTotal =discount;  
                }
            }else{
                if(discount > SubTotalsPay){
                    disTotal =SubTotalsPay;
                    alert('Can Not Discount over Totals Amounts');
                    $('.discountInputAmount').val(disTotal);
                }else{
                    disTotal =discount;
                }  
            }

        }
        
        $(document).on('click','.discountInputEditAction',function(){
            $('.discountInputEdit').hide();
            $('.discountText').show();
            type ='discountAmount'
            ActionItemAjax(id,type,invoice,url,qty,discount,discounttype);
            
        });



        $(document).on('click','.AlertMessage',function(){
            $(this).empty();
        });
        
        
        //*****************Calculate Amount and Change Money ***************
        $(document).on('keyup','.TotalPayedUpdate',function(){
            var payAmount =parseFloat($('.TotalPayed').val());
            
            if(payAmount==''){
                payAmount=0;
            }
            var Received =parseFloat($('.ReceivedAmount').val());
            if(Received==''){
                Received=0;
            }
            var change ='';
            if(Received >=payAmount){
              change =Received-payAmount;
              
            }
            if(change!=''){
                change =parseFloat(change).toFixed({{general()->currency_decimal}});
            }
            $('.ChangeAmount').val(change);
            
        });
        
        //*****************Order Item Add and Item Quantity Update and Item Delete ***************
        $(document).on('click','.productGrid,.itemDelete,.ItemMinusClick,.ItemPlusClick',function(){

           id = $(this).data('id');
           type = $(this).data('type');
           qty=null;
           discount=null;
           discounttype=null;
           ActionItemAjax(id,type,invoice,url,qty,discount,discounttype);

        });

        $(document).on('change','.itemQtyinput',function(){
            id = $(this).data('id');
            type = $(this).data('type');
            qty = $(this).val();
            discount = null;
            discounttype=null;
            ActionItemAjax(id,type,invoice,url,qty,discount,discounttype);
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
    
                    $('.InvoicePaymentSection').empty().append(data.payment);
                    $('.InvoiceItemsSection').empty().append(data.view);
                    
    
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
        
                        $('.InvoicePaymentSection').empty().append(data.payment);
                        $('.InvoiceItemsSection').empty().append(data.view);
                        
        
                      },error: function () {
                          alert('error');
        
                        }
                    });
                                        
                }
            }
            
        });
        
        //*****************Customer Information Update***************
        $(document).on('change','.customerUser',function(){
            id = $(this).val();
            type = $(this).data('type');

            $.ajax({
              url:url,
              dataType: 'json',
              cache: false,
              data: {'type':type,'id':id},
               success : function(data){

               },error: function () {
                  alert('error');

                }
            });


        });
        
        
        ///Function Action Start
        function ActionAjax(url,type,cat,brand,key,barcode){

            $('.actionLoading').show();

            $.ajax({
              url:url,
              dataType: 'json',
              cache: false,
              data: {'type':type,'cat':cat,'brand':brand,'key':key,'barcode':barcode},
               success : function(data){
                $('.actionLoading').hide();
                $('.GetProductsSearh').empty().append(data.view);
                $('.InvoicePaymentSection').empty().append(data.payment);
                $('.InvoiceItemsSection').empty().append(data.viewItems);
                $('.searchProductBarcode').val('');
               },error: function () {
                 // alert('error');
                  $('.actionLoading').hide();
                }
            });

        }


        function ActionItemAjax(id,type,invoice,url,qty,discount,discounttype){

            $.ajax({
              url:url,
              dataType: 'json',
              cache: false,
              data: {'id':id,'type':type,'invoice':invoice,'qty':qty,'discount':discount,'discounttype':discounttype},
               success : function(data){

                $('.InvoiceItemsSection').empty().append(data.view);
                $('.InvoicePaymentSection').empty().append(data.payment);
                
               },error: function () {
                  alert('error');

                }
            });

        }

        function ActionMethodAjax(id,type,url,value){
   
            $.ajax({
              url:url,
              dataType: 'json',
              cache: false,
              data: {'id':id,'type':type,'value':value},
               success : function(data){

                $('.InvoicePaymentSection').empty().append(data.payment);
                $('.InvoiceItemsSection').empty().append(data.view);

               },error: function () {
                  alert('error');

                }
            });

        }


    });
</script>


@endpush
