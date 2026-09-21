@extends('admin.layouts.app') @section('title')
<title>Products List - {{general()->title}} | {{general()->subtitle}}</title>
@endsection @push('css')
<style type="text/css">
    
    .card .topBorderHeader{
        border-top: 3px solid #3d3196 !important;
    }


</style>
@endpush 

@section('contents')

<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title mb-0">Products List</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard </a></li>
                    <li class="breadcrumb-item active">Products List</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
        <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
            @isset(json_decode(Auth::user()->permission->permission, true)['products']['add'])
            <a class="btn btn-outline-primary" href="{{route('admin.productsCreate')}}">Add Product</a>
            @endisset
            <a class="btn btn-outline-primary" href="{{route('admin.products')}}">
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
                                        <form action="{{route('admin.products')}}">
                                            <div class="row">
                                                <div class="col-md-6 mb-1">
                                                    <div class="input-group">
                                                        <input type="date" name="startDate" value="{{$r->startDate?Carbon\Carbon::parse($r->startDate)->format('Y-m-d') :''}}" class="form-control {{$errors->has('startDate')?'error':''}}" />
                                                        <input type="date" value="{{$r->endDate?Carbon\Carbon::parse($r->endDate)->format('Y-m-d') :''}}" name="endDate" class="form-control {{$errors->has('endDate')?'error':''}}" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-1">
                                                    <div class="input-group">
                                                        <input type="text" name="search" value="{{$r->search?$r->search:''}}" placeholder="Product Name, Category Name" class="form-control {{$errors->has('search')?'error':''}}" />
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
                    <div class="card-header topBorderHeader" style="border-bottom: 1px solid #e3ebf3;">
                        <h4 class="card-title">Products List</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form action="{{route('admin.products')}}">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="input-group mb-1">
                                            <select class="form-control form-control-sm rounded-0" name="action" required="">
                                                <option value="">Select Action</option>
                                                <option value="1">Products Active</option>
                                                <option value="2">Products InActive</option>
                                                <option value="3">Add Top Sale</option>
                                                <option value="4">Remove Top Sale</option>
                                                <option value="5">Products Delete</option>
                                            </select>
                                            <button class="btn btn-sm btn-primary rounded-0" onclick="return confirm('Are You Want To Action?')">Action</button>
                                        </div>
                                    </div>
                                    <div class="col-md-3"></div>
                                    <div class="col-md-5">
                                        <ul class="statuslist">
                                            <li><a href="{{route('admin.products')}}">All ({{$totals->total}})</a></li>
                                            <li><a href="{{route('admin.products',['status'=>'active'])}}">Active ({{$totals->active}})</a></li>
                                            <li><a href="{{route('admin.products',['status'=>'inactive'])}}">Inactive ({{$totals->inactive}})</a></li>
                                            <li><a href="{{route('admin.products',['status'=>'temp'])}}">Drafts ({{$totals->temp}})</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th style="min-width: 60px;">
                                                    <label style="cursor: pointer; margin-bottom: 0;"> <input class="checkbox" type="checkbox" class="form-control" id="checkall" /> All <span class="checkCounter"></span> </label>
                                                </th>
                                                <th style="min-width: 350px;">Product Name</th>
                                                <th style="min-width: 80px;">Image</th>
                                                <th style="min-width: 200px;">Catagory</th>
                                                <th style="min-width: 80px;">Status</th>
                                                <th style="min-width: 160px;">Action/Author</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
                                            @foreach($products as $i=>$product)
                                            <tr>
                                                <td>
                                                    <input class="checkbox" type="checkbox" name="checkid[]" value="{{$product->id}}" /><br />
                                                    {{$products->currentpage()==1?$i+1:$i+($products->perpage()*($products->currentpage() - 1))+1}}
                                                </td>
                                                <td>
                                                    <span><a href="{{route('productView',$product->slug?:'no-slug')}}" target="_blank">{{$product->name}}</a></span>
                                                    <br/>
                                                    <span style="color: #ccc;"><b style="color: #1ab394;">{{general()->currency}}</b> {{priceFormat($product->final_price)}}</span>

                                                    @if($product->fetured==true)
                                                    <span><i class="fa fa-star" style="color: #1ab394;"></i></span>
                                                    @endif

                                                    @if($product->brand)
                                                    <span style="color: #ccc;"><b style="color: #1ab394;">Brand:</b> {{$product->brand->name}}</span>
                                                    @endif

                                                    @if($product->fetured==true)
                                                    <span style="color: #ccc;"><b style="color: #1ab394;">Sup:</b> Sukd skdj sd s</span>
                                                    @endif
                                                    <span style="color: #ccc;"><i class="fa fa-calendar" style="color: #1ab394;"></i> {{$product->created_at->format('Y-m-d')}}</span>
                                                </td>
                                                <td style="padding: 5px; text-align: center;">
                                                    <img src="{{asset($product->image())}}" style="max-width: 70px; max-height: 50px;" />
                                                </td>
                                                <td>
                                                    @foreach($product->productCategories as $i=>$ctg)

                                                     {{$i==0?'':'-'}} {{$ctg->name}} 

                                                     @endforeach
                                                </td>
                                                <td>
                                                    @if($product->status=='active')
                                                    <span class="badge badge-success">Active </span>
                                                    @elseif($product->status=='inactive')
                                                    <span class="badge badge-danger">Inactive </span>
                                                    @else
                                                    <span class="badge badge-danger">Draft </span>
                                                    @endif 
                                                </td>
                                                <td style="padding: 5px;">
                                                    <a href="{{route('admin.productsEdit',$product->id)}}" class="btn btn-sm btn-info">Edit</a>
                                                    <a href="{{route('admin.productsView',$product->id)}}" class="btn btn-sm btn-success">View</a>
                                                    @isset(json_decode(Auth::user()->permission->permission, true)['products']['delete'])
                                                    <a href="{{route('admin.productsDelete',$product->id)}}" class="btn btn-sm btn-danger" onclick="return confirm('Are You Want To Delete?')">Delete</a>
                                                    @endisset
                                                    <br />
                                                    <span style="color: #ccc;">
                                                        <i class="fa fa-user" style="color: #1ab394;"></i>
                                                        {{Str::limit($product->user?$product->user->name:'No Author',15)}}
                                                    </span>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                                    {{$products->links('pagination')}}
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Basic Inputs end -->
</div>

@endsection @push('js') @endpush
