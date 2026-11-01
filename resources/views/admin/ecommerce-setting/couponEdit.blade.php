@extends('admin.layouts.app')

@section('title')
<title>{{$coupon->exists ? 'Edit' : 'Add'}} Coupon - {{general()->title}} | {{general()->subtitle}}</title>
@endsection

@section('contents')

<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title mb-0">{{$coupon->exists ? 'Edit' : 'Add'}} Coupon</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard </a></li>
                    <li class="breadcrumb-item"><a href="{{route('admin.ecommerceCoupons')}}">Coupons</a></li>
                    <li class="breadcrumb-item active">{{$coupon->exists ? 'Edit' : 'Add'}} Coupon</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
        <div class="btn-group float-md-right" role="group">
            <a class="btn btn-outline-primary" href="{{route('admin.ecommerceCoupons')}}">Back</a>
        </div>
    </div>
</div>

<div class="content-body">
    <section class="basic-elements">
        @include('admin.alerts')

        <div class="card">
            <div class="card-content">
                <div class="card-body">
                    <form action="{{route('admin.ecommerceCouponsUpdate',$coupon->id)}}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Coupon Code *</label>
                                <input type="text" name="code" value="{{old('code',$coupon->code)}}" class="form-control" placeholder="e.g. EID10" style="text-transform: uppercase;" required>
                                @if ($errors->has('code'))
                                <p style="color: red;margin: 0;font-size: 12px;">{{ $errors->first('code') }}</p>
                                @endif
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Status *</label>
                                <select name="status" class="form-control">
                                    <option value="active" {{old('status',$coupon->status?:'active')=='active'?'selected':''}}>Active</option>
                                    <option value="inactive" {{old('status',$coupon->status)=='inactive'?'selected':''}}>Inactive</option>
                                </select>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Discount Type *</label>
                                <select name="discount_type" class="form-control">
                                    <option value="percentage" {{old('discount_type',$coupon->discount_type)!='flat'?'selected':''}}>Percentage (%)</option>
                                    <option value="flat" {{old('discount_type',$coupon->discount_type)=='flat'?'selected':''}}>Flat Amount ({{general()->currency}})</option>
                                </select>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Discount *</label>
                                <input type="number" step="0.01" min="0" name="discount" value="{{old('discount',$coupon->exists ? (float)$coupon->discount : '')}}" class="form-control" required>
                                @if ($errors->has('discount'))
                                <p style="color: red;margin: 0;font-size: 12px;">{{ $errors->first('discount') }}</p>
                                @endif
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Minimum Shopping ({{general()->currency}})</label>
                                <input type="number" step="1" min="0" name="minimum_shopping" value="{{old('minimum_shopping',$coupon->minimum_shopping > 0 ? (float)$coupon->minimum_shopping : '')}}" class="form-control" placeholder="0 = no minimum">
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Maximum Discount ({{general()->currency}}) <small>for percentage</small></label>
                                <input type="number" step="1" min="0" name="max_discount" value="{{old('max_discount',$coupon->max_discount > 0 ? (float)$coupon->max_discount : '')}}" class="form-control" placeholder="0 = no limit">
                            </div>
                            <div class="col-md-4 form-group">
                                <label>Valid From</label>
                                <input type="date" name="date_from" value="{{old('date_from',$coupon->date_from ? $coupon->date_from->format('Y-m-d') : '')}}" class="form-control">
                            </div>
                            <div class="col-md-4 form-group">
                                <label>Valid To</label>
                                <input type="date" name="date_to" value="{{old('date_to',$coupon->date_to ? $coupon->date_to->format('Y-m-d') : '')}}" class="form-control">
                                @if ($errors->has('date_to'))
                                <p style="color: red;margin: 0;font-size: 12px;">{{ $errors->first('date_to') }}</p>
                                @endif
                            </div>
                            <div class="col-md-4 form-group">
                                <label>Usage Limit <small>(used {{(int)$coupon->used_count}})</small></label>
                                <input type="number" step="1" min="0" name="usage_limit" value="{{old('usage_limit',$coupon->usage_limit > 0 ? $coupon->usage_limit : '')}}" class="form-control" placeholder="0 = unlimited">
                            </div>
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-info">Save Coupon</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection
