@extends('admin.layouts.app') @section('title')
<title>Invoice - {{general()->title}} | {{general()->subtitle}}</title>
@endsection @push('css')

<style type="text/css">
	
    .invoice-inner{
        /*padding: 30px;*/
        width: 100%;
        /*box-shadow: 0px 0px 10px #ccc;*/
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

    .posinvoice-inner{
                padding: 10px;
                border: 1px solid #ddd;
            }
            
            .posinvoice-header{
                text-align: center;
            }
            
            .posinvoice-header img{
                width: 50%;
            }
            
            .posinvoice-header h4{
                
            }
            
            .posinvoice-header p{
                margin: 3px 0;
                font-size: 13px;
                line-height: 15px;
            }
            
            .bin-part {
                border: 1px solid #ccc;
                padding: 0px 5px;
                font-size: 13px;
                margin: 7px 0px;
            }
            
            .posinvoice-products h6 {
                text-align: center;
            }
            
            .posinvoice-products .row{
                font-size: 13px;
            }
            
            .oneTable{
                margin: 0;
            }
            
            .oneTable th, .oneTable td{
                border: none;
                padding: 0px 0px;
                font-size: 13px;
            }
            
            .oneTable th{
                width: 30%;
            }
            
            .twoTable{
                margin: 0;
            }
            
            .twoTable th, .twoTable td{
                border: none;
                padding: 0px 0px;
                font-size: 13px;
            }
            
            .threeTable{
                margin: 0;
            }
            
            .threeTable th, .threeTable td{
                border: none;
                padding: 0px 0px;
                font-size: 13px;
            }
            
            .fourTable{
                margin: 0;
            }
            
            .fourTable th, .fourTable td{
                border: none;
                padding: 0px 0px;
                font-size: 13px;
            }

</style>
<style type="text/css">
    .invoice-inner {
        /*box-shadow: 0px 0px 5px #ccc;*/
        padding: 10px 20px;
    }
    
    .invoice-header {
        padding: 20px 0px 35px;
    }
    
    .invoice-header img{
        width: 100%;
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
            <a class="btn btn-outline-primary" href="{{route('admin.posOrders')}}"> BACK</a>
            <a class="btn btn-success" href="{{route('admin.posOrdersCreate')}}"><i class="fa fa-plus"></i> POS</a>
            <a class="btn btn-outline-primary" href="{{route('admin.orders')}}"> <i class="fa-solid fa-rotate"></i></a>
        </div>
    </div>
</div>

<div class="content-body">
    <!-- Basic Elements start -->
    <section class="basic-elements">
        <div class="row">
            <div class="col-md-12">
            	<div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-6">
            	<ul class="nav nav-tabs" role="tablist" style="border-bottom: none;">
                    <li class="nav-item">
                        <a class="nav-link active" id="baseIcon-tab11" href="#tabIcon1" data-toggle="tab" aria-controls="tabIcon1" href="#tabIcon1" role="tab" aria-selected="true"><i class="fas fa-print"></i> POS Invoice</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="baseIcon-tab12" href="#tabIcon2" data-toggle="tab" aria-controls="tabIcon2" href="#tabIcon2" role="tab" aria-selected="false"><i class="fas fa-print"></i> Invoice</a>
                    </li>
                </ul>
                </div>
            	</div>
            	<br>
            	<div class="tab-content px-1 pt-1">
                    <div class="tab-pane active" id="tabIcon1" role="tabpanel" aria-labelledby="baseIcon-tab11">
                        <div class="row">
                            <div class="col-md-3"></div>
                            <div class="col-md-5">
                                <button class="btn btn-sm btn-success" id="PrintAction2"><i class="fa fa-print"></i> Click Print</button>
                                <div class="card">
                                @include('admin.orders.includes.posInvoices')
                           	 	</div>
                            </div>
                            <div class="col-md-4"></div>
                        </div>
                    </div>

                    <div class="tab-pane" id="tabIcon2" role="tabpanel" aria-labelledby="baseIcon-tab12">
                        
                        <div class="row">
                            <div class="col-md-2">
                            	
                            </div>
	                        <div class="col-md-9">
	                        	<button class="btn btn-sm btn-success " id="PrintAction"><i class="fa fa-print"></i> Click Print</button>
		                        <div class="card">
		                            <div class="invoice-inner invoicePage PrintAreaContact">
		                            @include('admin.orders.includes.Invoice2')
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
<script type="text/javascript"></script>
@endpush
