@extends('admin.layouts.app')

@section('title')
<title>Coupons - {{general()->title}} | {{general()->subtitle}}</title>
@endsection

@section('contents')

<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title mb-0">Coupons</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard </a></li>
                    <li class="breadcrumb-item active">Coupons</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
        <div class="btn-group float-md-right" role="group">
            <a class="btn btn-outline-primary" href="{{route('admin.ecommerceCouponsCreate')}}">
                <i class="fa fa-plus"></i> Add Coupon
            </a>
        </div>
    </div>
</div>

<div class="content-body">
    <section class="basic-elements">
        @include('admin.alerts')

        <div class="card">
            <div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
                <h4 class="card-title">Coupon List</h4>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <form action="{{route('admin.ecommerceCoupons')}}">
                        <div class="row">
                            <div class="col-md-4 mb-1">
                                <input type="text" name="search" value="{{$r->search}}" class="form-control" placeholder="Search Coupon Code">
                            </div>
                            <div class="col-md-3 mb-1">
                                <select name="status" class="form-control">
                                    <option value="">All Status</option>
                                    <option value="active" {{$r->status=='active'?'selected':''}}>Active</option>
                                    <option value="inactive" {{$r->status=='inactive'?'selected':''}}>Inactive</option>
                                </select>
                            </div>
                            <div class="col-md-2 mb-1">
                                <button type="submit" class="btn btn-success btn-block">Search</button>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <th>SL</th>
                                <th>Code</th>
                                <th>Discount</th>
                                <th>Min. Shopping</th>
                                <th>Validity</th>
                                <th>Used</th>
                                <th>Status</th>
                                <th style="width: 90px;">Action</th>
                            </tr>
                            @forelse($coupons as $i=>$coupon)
                            <tr>
                                <td>{{$coupons->firstItem()+$i}}</td>
                                <td><b>{{$coupon->code}}</b></td>
                                <td>
                                    @if($coupon->discount_type=='flat')
                                    {{priceFullFormat($coupon->discount)}}
                                    @else
                                    {{(float)$coupon->discount}}%
                                    @if($coupon->max_discount > 0)<br><small>Max {{priceFullFormat($coupon->max_discount)}}</small>@endif
                                    @endif
                                </td>
                                <td>{{$coupon->minimum_shopping > 0 ? priceFullFormat($coupon->minimum_shopping) : '-'}}</td>
                                <td>
                                    <small>
                                    {{$coupon->date_from ? $coupon->date_from->format('d-m-Y') : 'Any'}}
                                    to
                                    {{$coupon->date_to ? $coupon->date_to->format('d-m-Y') : 'Any'}}
                                    </small>
                                </td>
                                <td>{{$coupon->used_count}}{{$coupon->usage_limit > 0 ? ' / '.$coupon->usage_limit : ''}}</td>
                                <td>
                                    @if($coupon->status=='active')
                                    <span class="badge badge-success">Active</span>
                                    @else
                                    <span class="badge badge-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{route('admin.ecommerceCouponsEdit',$coupon->id)}}" class="badge badge-success"><i class="fa fa-edit"></i></a>
                                    <a href="{{route('admin.ecommerceCouponsDelete',$coupon->id)}}" class="badge badge-danger" onclick="return confirm('Are you sure you want to delete this coupon?')"><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">No coupons found.</td>
                            </tr>
                            @endforelse
                        </table>
                        {{$coupons->links('pagination')}}
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection
