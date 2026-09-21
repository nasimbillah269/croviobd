@extends('admin.layouts.app') @section('title')
<title>Invoice - {{general()->title}} | {{general()->subtitle}}</title>
@endsection @push('css')
<style type="text/css">
	
    /*.invoice-inner{*/
    /*    padding: 30px;*/
    /*    width: 100%;*/
    /*    box-shadow: 0px 0px 10px #ccc;*/
    /*}*/
    
    /*.demo-info,.invoice-info{*/
    /*    font-size: 12px;*/
    /*}*/
    
    /*.invoice-inner h6,p,h5{*/
    /*    margin: 0;*/
    /*}*/

    /*.demo-info img{*/
    /*    width: 150px;*/
    /*}*/
    
    /*p.billingTo {*/
    /*    padding: 3px 10px;*/
    /*    border: 2px solid green;*/
    /*    border-radius: 10px;*/
    /*    font-size: 17px;*/
    /*    margin: 5px 0px;*/
    /*    font-weight: 500;*/
    /*}*/
    
    /*.remarkTable{*/
    /*    margin: 0;*/
    /*    font-size: 85%;*/
    /*}*/
    
    /*.remarkTable th, .remarkTable td{*/
    /*    padding: 3px 0px;;*/
    /*}*/
    
    /*.subtotalTable{*/
    /*    margin: 0;*/
    /*    font-size: 85%;*/
    /*}*/
    
    /*.subtotalTable th, .subtotalTable td{*/
    /*    padding: 1px 0px;*/
    /*    border: none;*/
    /*}*/
    
    /*.payment-details{*/
    /*    margin-top: 15px;*/
    /*}*/
    
    /*.signature-part{*/
    /*    margin-top: 130px;*/
    /*}*/
    
    /*.signature-part span{*/
    /*    line-height: 4px;*/
    /*    display: block;*/
    /*}*/
    /*.posinvoice-inner {*/
    /*    box-shadow: 0px 0px 10px #ccc;*/

    /*}*/

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

            <a class="btn btn-outline-primary" href="{{route('admin.orders')}}">Back</a>
            <a class="btn btn-outline-primary" href="{{route('admin.ordersManage',$order->id)}}">Manage</a>
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
            <div class="col-md-9">
                <div class="card">

            	   <div class="invoice-inner invoicePage PrintAreaContact">
                    <style type="text/css">
                        .invoiceContainer {
                            overflow: auto;
                        }
                        
                        .invoice-inner.InnerInvoiePage {
                            min-width: 600px;
                        }
                    
                        .invoice-inner {
                            /*box-shadow: 0px 0px 5px #ccc;*/
                            padding: 10px 20px;
                            overflow: auto;
                            min-width: 600px;
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
                        .infoTable tr td
                        {
                            padding: 2px 5px;
                            font-size: 12px;
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
                        .note {
                            margin-top: 10px;
                            padding: 10px;
                            background-color: red;
                            color: #fff;
                        }
                        </style>
                    @include('admin.orders.includes.Invoice2')
                    
                    </div>
                </div>

            </div>
        </div>
    </section>
    <!-- Basic Inputs end -->
</div>

@endsection 

@push('js') 

<script src="{{asset('batikrom/js/inword.js')}}"></script>

<script type='text/javascript'>
    
    $(document).ready(function(){
        
        var date = new Date();
        date.setDate(date.getDate() + 7);
        
        console.log(date);
        
        
        var words="";

        $(function() {
        	var totalamount = (
        		Number($('#inWordTotal').data('amount'))
        		);
        	words = toWords(totalamount);
        	$('#inWordTotal').empty().append(words + 'Taka only');
        });
        
    });
</script>

@endpush
