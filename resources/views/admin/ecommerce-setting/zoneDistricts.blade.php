@extends('admin.layouts.app')

@section('title')
<title>{{$zone->name}} Delivery Charges - {{general()->title}} | {{general()->subtitle}}</title>
@endsection

@section('contents')

<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title mb-0">{{$zone->name}} - District Charges</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard </a></li>
                    <li class="breadcrumb-item"><a href="{{route('admin.ecommerceSetting','delivery')}}">Delivery &amp; Payment</a></li>
                    <li class="breadcrumb-item active">{{$zone->name}}</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
        <div class="btn-group float-md-right" role="group">
            <a class="btn btn-outline-primary" href="{{route('admin.ecommerceSetting','delivery')}}">Back</a>
        </div>
    </div>
</div>

<div class="content-body">
    <section class="basic-elements">
        @include('admin.alerts')

        <div class="card">
            <div class="card-content">
                <div class="card-body">
                    <form action="{{route('admin.deliveryZoneDistrictsUpdate',$zone->id)}}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label><b>Zone Default Charge ({{general()->currency}})</b></label>
                                <input type="number" min="0" step="1" name="shipping_charge" value="{{(float)$zone->shipping_charge}}" class="form-control" required>
                                <small class="text-muted">Used for every district below that has no charge of its own.</small>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <th style="width: 60px;">SL</th>
                                    <th>District</th>
                                    <th style="width: 220px;">Charge ({{general()->currency}})</th>
                                </tr>
                                @forelse($districts as $i=>$district)
                                @php($override = $zone->districtCharges->firstWhere('district_id',$district->id))
                                <tr>
                                    <td>{{$i+1}}</td>
                                    <td>{{$district->name}} <small class="text-muted">{{trim($district->bn_name)}}</small></td>
                                    <td style="padding:3px;">
                                        <input type="number" min="0" step="1" class="form-control form-control-sm" name="charges[{{$district->id}}]" value="{{$override ? (float)$override->shipping_charge : ''}}" placeholder="Default ({{(float)$zone->shipping_charge}})">
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="3" class="text-center">No districts in this zone.</td></tr>
                                @endforelse
                            </table>
                        </div>

                        <button type="submit" class="btn btn-info">Update Charges</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection
