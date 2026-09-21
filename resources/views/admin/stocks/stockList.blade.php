@extends('admin.layouts.app') @section('title')
<title>Stock List - {{general()->title}} | {{general()->subtitle}}</title>
@endsection @push('css')
<style type="text/css"></style>
@endpush @section('contents')

<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title mb-0">Stock List</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard </a></li>
                    <li class="breadcrumb-item active">Stock List</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
        <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
            <a class="btn btn-outline-primary" href="{{route('admin.stockList')}}">
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
                                        <form action="{{route('admin.stockList')}}">
                                            <div class="row">
                                                <div class="col-md-4 mb-0">
                                                    <div class="form-group">
                                                        <select class="form-control" name="category">
                                                            <option value="">Select Category</option>
                                                            @foreach($categories as $ctg)
                                                            <option value="{{$ctg->id}}" {{$r->category==$ctg->id?'selected':''}} >{{$ctg->name}}</option>
                                                            @foreach($ctg->subctgs as $subctg)
                                                            <option value="{{$subctg->id}}" {{$r->category==$subctg->id?'selected':''}} > - {{$subctg->name}}</option>
                                                            @foreach($subctg->subctgs as $subsubctg)
                                                            <option value="{{$subsubctg->id}}" {{$r->category==$subsubctg->id?'selected':''}} > -- {{$subsubctg->name}}</option>
                                                            @endforeach
                                                            @endforeach
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 mb-0">
                                                    <div class="form-group">
                                                        <input type="text" name="price" value="{{$r->price?$r->price:''}}" placeholder="Price" class="form-control {{$errors->has('price')?'error':''}}" />
                                                    </div>
                                                </div>
                                                <div class="col-md-2 mb-0">
                                                    <div class="form-group">
                                                        <input type="text" name="quantity" value="{{$r->quantity?$r->quantity:''}}" placeholder="Quantity" class="form-control {{$errors->has('quantity')?'error':''}}" />
                                                    </div>
                                                </div>
                                                <div class="col-md-4 mb-0">
                                                    <div class="input-group">
                                                        <input type="text" name="search" value="{{$r->search?$r->search:''}}" placeholder="Product Name" class="form-control {{$errors->has('search')?'error':''}}" />
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
                        <h4 class="card-title">Stock List</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">

                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th style="min-width: 60px;">SL</th>
                                                <th >Image</th>
                                                <th >Name</th>
                                                <th >Purchase Price</th>
                                                <th >Final</th>
                                                <th >Stock</th>
                                                <th >Purchase Stock Price</th>
                                                <th >Sale Stock Price</th>
                                                <th >Profit</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($products as $i=>$product)
                                            <tr>
                                                <td>
                                                    {{$products->currentpage()==1?$i+1:$i+($products->perpage()*($products->currentpage() - 1))+1}}
                                                </td>
                                                <td>
                                                    <img src="{{asset($product->image())}}" style="max-width:60px;">
                                                </td>
                                                <td><a href="{{route('productView',$product->slug?:'no-slug')}}">{{$product->name}}</a></td>
                                                <td>{{priceFormat($product->purchase_price)}}</td>
                                                <td>{{priceFormat($product->final_price)}}</td>
                                                <td>{{$product->quantity}}</td>
                                                <td>{{$product->quantity*$product->purchase_price}}</td>
                                                <td>{{$product->quantity*$product->final_price}}</td>
                                                <td>{{($product->quantity*$product->final_price)-($product->quantity*$product->purchase_price)}}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                {{$products->links('pagination')}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Basic Inputs end -->
</div>

@endsection @push('js') @endpush
