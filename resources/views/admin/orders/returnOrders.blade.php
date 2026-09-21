@extends('admin.layouts.app') @section('title')
<title>Order List - {{general()->title}} | {{general()->subtitle}}</title>
@endsection @push('css')
<style type="text/css"></style>
@endpush @section('contents')

<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title mb-0">Order List</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard </a></li>
                    <li class="breadcrumb-item active">Order List</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
        <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
            <a class="btn btn-outline-primary" href="{{route('admin.returnOrders')}}">
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
                                        <form action="{{route('admin.returnOrders')}}">
                                            <div class="row">
                                                <div class="col-md-6 mb-1">
                                                    <div class="input-group">
                                                        <input type="date" name="startDate" value="{{$r->startDate?Carbon\Carbon::parse($r->startDate)->format('Y-m-d') :''}}" class="form-control {{$errors->has('startDate')?'error':''}}" />
                                                        <input type="date" value="{{$r->endDate?Carbon\Carbon::parse($r->endDate)->format('Y-m-d') :''}}" name="endDate" class="form-control {{$errors->has('endDate')?'error':''}}" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-1">
                                                    <div class="input-group">
                                                        <input type="text" name="search" value="{{$r->search?$r->search:''}}" placeholder="Order Invoice, Customer Mobile, email" class="form-control {{$errors->has('search')?'error':''}}" />
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
                        <h4 class="card-title">Order List</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                           
                                <div class="table-responsive">
                                    
                                  <table class="table table-striped table-bordered table-hover" >
								    <thead>
								        <tr>
								            <th>S:L</th>
								            <th width="10%">invoice NO</th>
								            <th width="25%">Customer</th>
								            <th>Product</th>
								            <th width="10%">Price</th>
								            <th>Status</th>
								            <th width="10%">Action</th>
								        </tr>
								    </thead>
								    <tbody>
								       @foreach($returnItems as $i=>$item)
								    	<tr>
								    		<td>{{$i+1}}</td>
								    		<td>{{$item->order?$item->order->invoice:'No Invoice'}}</td>
								    		<td>
								    		@if($item->order)
								    		    <span>{{$item->order->name}} - {{$item->order->mobile}}</span>
								    		@else
								    		    <span>No Customer</span>
								    		@endif
								    		</td>
								    		<td>
								    		    @if($item->Mitem)
								    		    <span>{{Str::limit($item->Mitem->product_name,30)}} - <b>QTY: {{$item->return_quantity}}</b></span>
								    		    @else
								    		    <span>No Product</span>
								    		    @endif
								    		 </td>
								    		<td>{{App\Models\General::first()->currency}} {{number_format($item->return_price)}}</td>
								    		<td>
								    		    @if($item->status=='confirmed')
								                <span class="badge badge-info" style="background: #3f51b5;">{{ucfirst($item->status)}}</span>
								                @else
								                <span class="badge badge-info" style="background: #ff9800;">{{ucfirst($item->status)}}</span>
								                @endif
								    		</td>
								    		<td>
								    		    <a href="{{route('admin.returnOrdersManage',$item->order?$item->order->id:0)}}" class="btn btn-sm btn-success">Manage</a>
								    		</td>
								    	</tr>
								        @endforeach
								        @if($returnItems->count()==0)
								        <tr><td colspan="7"><center>No Reurn Order Found</center></td></tr>
								        @endif
								    </tbody>
								</table>

									 {{$returnItems->links('pagination')}}
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

@endpush
