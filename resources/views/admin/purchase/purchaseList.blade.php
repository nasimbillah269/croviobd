@extends('admin.layouts.app') @section('title')
<title>Products List - {{general()->title}} | {{general()->subtitle}}</title>
@endsection @push('css')
<style type="text/css"></style>
@endpush @section('contents')


<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title mb-0">Purchase List</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard </a></li>
                    <li class="breadcrumb-item active">Purchase List</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
        <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">

            <a class="btn btn-outline-primary" href="{{route('admin.purchaseProducts',['type'=>'parchase'])}}">Add Purchase</a>

            <a class="btn btn-outline-primary" href="{{route('admin.purchaseProducts')}}">
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
                @include('admin.alerts')
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <div id="accordion">
                                <div
                                    class="card-header collapsed"
                                    data-toggle="collapse"
                                    data-target="#collapseTwo"
                                    aria-expanded="false"
                                    aria-controls="collapseTwo"
                                    id="headingTwo"
                                    style="background: #f5f7fa; padding: 10px; cursor: pointer; border: 1px solid #00b5b8;"
                                >
                                    Search click Here..
                                </div>
                                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion" style="border: 1px solid #00b5b8; border-top: 0;">
                                    <div class="card-body">
                                        <form action="{{route('admin.purchaseProducts')}}">
                                            <div class="row">
                                                <div class="col-md-5 mb-1">
                                                    <div class="input-group">
                                                        <input type="date" name="startDate" value="{{$r->startDate?Carbon\Carbon::parse($r->startDate)->format('Y-m-d') :''}}" class="form-control {{$errors->has('startDate')?'error':''}}" />
                                                        <input type="date" value="{{$r->endDate?Carbon\Carbon::parse($r->endDate)->format('Y-m-d') :''}}" name="endDate" class="form-control {{$errors->has('endDate')?'error':''}}" />
                                                    </div>
                                                </div>
                                                <div class="col-md-3 mb-1">
                                                    <select name="supplier" class="form-control {{$errors->has('search')?'error':''}}">
                                                        <option value="">Select Supplier</option>
                                                        @foreach($suppliers as $supplier)
                                                        <option value="{{$supplier->id}}" {{$r->supplier?$r->supplier==$supplier->id?'selected':'':''}}>{{$supplier->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-4 mb-1">
                                                    <div class="input-group">
                                                        <input type="text" name="search" value="{{$r->search?$r->search:''}}" placeholder="Invoice No" class="form-control {{$errors->has('search')?'error':''}}" />
                                                        <button type="submit" class="btn btn-success rounded-0">Search</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
                        <h4 class="card-title">Purchase List</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">

                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th style="min-width: 60px;">
                                                    <label style="cursor: pointer; margin-bottom: 0;"> S:L </label>
                                                </th>
                                                <th style="min-width: 150px;">Invoice</th>
                                                <th style="min-width: 150px;">Supplier</th>
                                                <th style="min-width: 100px;">Date</th>
                                                <th style="min-width: 100px;">Totals</th>
                                                <th style="min-width: 80px;">Status</th>
                                                <th style="min-width: 120px;">Action/Author</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
                                            @foreach($orders as $i=>$order)
                                            <tr>
                                                <td>
                                                    {{$orders->currentpage()==1?$i+1:$i+($orders->perpage()*($orders->currentpage() - 1))+1}}
                                                </td>
                                                <td>
                                                    <a href="{{route('admin.purchaseProductsInvoice',$order->id)}}">{{$order->invoice}}</a>
                                                   

                                                </td>
                                                <td>
                                                   {{$order->user?$order->user->name:''}}

                                                </td>
                                                <td style="padding: 5px; text-align: center;">
                                                    {{$order->created_at->format('Y-m-d')}}
                                                </td>
                                                <td>
                                                    {{priceFormat($order->grand_total)}}
                                                </td>
                                                <td>
                                                   {{$order->order_status}} 
                                                </td>
                                                <td style="padding: 5px;">
                                                    <a href="{{route('admin.purchaseProductsEdit',$order->id)}}" class="btn btn-sm btn-info">Edit</a>
                                                    @if($order->items->sum('quantity')==0)
                                                    <a href="{{route('admin.purchaseProductsDelete',$order->id)}}" class="btn btn-sm btn-danger" onclick="return confirm('Are You Want To Delete?')"><i class="fa fa-trash"></i> </a>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                                    {{$orders->links('pagination')}}
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
	$()
</script>

@endpush
