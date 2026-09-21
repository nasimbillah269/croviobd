@extends('admin.layouts.app') 

@section('title')
<title>Ecommerce Edit - {{general()->title}} | {{general()->subtitle}}</title>
@endsection 

@push('css')


@endpush @section('contents')

<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title mb-0">Ecommerce Setting</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard </a></li>
                    <li class="breadcrumb-item active">Ecommerce Setting</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
        <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
            <a class="btn btn-outline-primary" href="{{route('admin.ecommerceSetting',$type)}}">
                Back
            </a>
            <a class="btn btn-outline-primary reloadPage" href="javascript:void(0)">
                <i class="fa-solid fa-rotate"></i>
            </a>
        </div>
    </div>
</div>

<div class="content-body">
    <!-- Basic Elements start -->
    <section class="basic-elements">
        @include('admin.alerts')

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
                            <h4 class="card-title">Shipping Zone</h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                            	<div class="row">
                               		<div class="col-md-6">
                               		    <form action="{{route('admin.ecommerceSettingUpdate',[$type,$zone->id])}}" method="post">
                               		        @csrf
                               		    
                              			<div class="table-responsive">
                               		
                               				<table class="table table-borderless">
                               					<tr>
                               						<td style="width: 150px;">Zone Name</td>
                               						<td style="width: 300px;min-width: 300px;">
                               						    
                               							<input type="text" name="name" value="{{$zone->name}}" class="form-control form-control-sm" placeholder="Enter Zone Name" required="">
                               						
                               						</td>
                               					</tr>
                                                <!--<tr>-->
                                                <!--    <td>Shippig Charge</td>-->
                                                <!--    <td>-->
                                                <!--        <input type="number" name="shipping_charge" value="{{$zone->shipping_charge}}" class="form-control form-control-sm" placeholder="Enter Shipping Charge" required="">-->
                                                <!--    </td>-->
                                                <!--</tr>-->
                                                <tr>
                                                    <td>After Shippig Day</td>
                                                    <td>
                                                        <input type="number" name="shipping_day" step="any" value="{{$zone->shipping_charge}}" class="form-control form-control-sm" placeholder="After Shipping Day" required="">
                                                    </td>
                                                </tr>
                               					<tr>
                               						<td>Zone Descriptioin</td>
                               						<td>
                               							<textarea type="text" name="description" rows="5"  class="form-control form-control-sm" placeholder="Enter Zone Description">{!!$zone->content!!}</textarea>
                               						</td>
                               					</tr>
                               					<tr>
                               						<td>Zones </td>
                               						<td>
                               							<!-- <select class="form-control form-control-sm" multiple="">
                               								<option value="">Select City</option>
                               								<option value="1">Dhaka</option>
                               								<option value="1">Kulna</option>
                               								<option value="1">Rajshahi</option>
                               							</select> -->

                                                        <select data-placeholder="Select Zone..." name="zones[]" class="select2 form-control" multiple="multiple" required="">
                                                            @foreach($zones as $i=>$zoneN)
                                                            <option value="{{$zoneN->id}}" @foreach($zone->zoneLists as $list)
                                                            {{$list->src_id==$zoneN->id?'selected':''}} @endforeach >{{$zoneN->name}}</option>
                                                            @endforeach
                                                        </select>

                               						</td>
                               					</tr>
                               					<tr>
                               						<td>Status</td>
                               						<td>
                               							<select class="form-control form-control-sm" name="status" required="">
                               								<option value="">Select Status</option>
                               								<option value="active" {{$zone->status=='active'?'selected':''}}>Active</option>
                               								<option value="inactive" {{$zone->status=='inactive'?'selected':''}}>Inactive</option>
                               							</select>
                               						</td>
                               					</tr>
                               					<tr>
                               						<td>Action</td>
                               						<td>
                               							<button type="submit" class="btn btn-primary">Submit</button>
                               						</td>
                               					</tr>
                               				</table>
                               			</div>
                               			</form>
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

<script>

          

</script>

@endpush
