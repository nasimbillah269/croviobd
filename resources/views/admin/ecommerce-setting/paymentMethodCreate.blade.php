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
            <a class="btn btn-outline-primary" href="{{route('admin.ecommerceSetting','payments')}}">
                <i class="fas fa-arrow-left"></i> BACK
            </a>
            <a class="btn btn-outline-primary" href="{{route('admin.ecommerceSettingEdit',['type'=>'payments','id'=>$method->id])}}">
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
                            <h4 class="card-title">Payment Method</h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                            	<div class="row">
                               		<div class="col-md-6">
                               			<h4>Payment Method</h4>
                               			<form action="{{route('admin.ecommerceSettingUpdate',['type'=>'paymentupdate','id'=>$method->id])}}" method="post">
                               				@csrf
	                              			<div class="table-responsive">
	                               				<table class="table table-borderless">
	                               					<tr>
	                               						<td>Method Name*</td>
	                               						<td>
	                               							<input type="text" name="name" value="{{$method->name}}" class="form-control form-control-sm" placeholder="Enter Method Name" required="">
	                               							@if ($errors->has('name'))
									                    	<p style="color: red;margin: 0;font-size: 10px;">{{ $errors->first('name') }}</p>
									                    	@endif
	                               						</td>
	                               					</tr>
	                               					<tr>
	                               						<td>Method Descriptioin</td>
	                               						<td>
	                               							<textarea type="text" name="description" rows="5" class="form-control form-control-sm" placeholder="Write Method Descriptioin">{!!$method->description!!}</textarea>
	                               							@if ($errors->has('description'))
									                    	<p style="color: red;margin: 0;font-size: 10px;">{{ $errors->first('description') }}</p>
									                    	@endif
	                               						</td>
	                               					</tr>
	                               					<tr>
	                               						<td>Serial*</td>
	                               						<td>
	                               							<input type="number" name="serial" value="{{$method->view}}" class="form-control form-control-sm" placeholder="Enter Serial" required="">
	                               							@if ($errors->has('serial'))
									                    	<p style="color: red;margin: 0;font-size: 10px;">{{ $errors->first('serial') }}</p>
									                    	@endif
	                               						</td>
	                               					</tr>
	                               					<tr>
	                               						<td>Status*</td>
	                               						<td>
	                               							<select class="form-control form-control-sm" name="status" required="">
	                               								<option value="">Select Status</option>
	                               								<option value="active" {{$method->status=='active'?'selected':''}}>Active</option>
	                               								<option value="inactive" {{$method->status=='inactive'?'selected':''}}>Inactive</option>
	                               							</select>
	                               							@if ($errors->has('status'))
									                    	<p style="color: red;margin: 0;font-size: 10px;">{{ $errors->first('status') }}</p>
									                    	@endif
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
                               		<div class="col-md-6">
                               			<div>
                               				<h4>Payment Option Add</h4>
                               			</div>
                               			<form action="{{route('admin.ecommerceSettingUpdate',['type'=>'paymentoption','id'=>$method->id])}}" method="post">
                               				@csrf
	                               			<table class="table table-borderless">
	                               					<tr>
	                               						<td>Option Name*</td>
	                               						<td>
	                               							<input type="text" name="option_name" class="form-control form-control-sm" placeholder="Enter Option Name" required="">
	                               							@if ($errors->has('option_name'))
									                    	<p style="color: red;margin: 0;font-size: 10px;">{{ $errors->first('option_name') }}</p>
									                    	@endif
	                               						</td>
	                               					</tr>
	                               					<tr>
	                               						<td>Option Descriptioin</td>
	                               						<td>
	                               							<textarea type="text" name="option_description" rows="5" class="form-control form-control-sm" placeholder="Write Option Descriptioin"></textarea>
	                               							@if ($errors->has('option_description'))
									                    	<p style="color: red;margin: 0;font-size: 10px;">{{ $errors->first('option_description') }}</p>
									                    	@endif
	                               						</td>
	                               					</tr>
	                               					<tr>
	                               						<td>Status*</td>
	                               						<td>
	                               							<select class="form-control form-control-sm" name="option_status" required="">
	                               								<option value="">Select Status</option>
	                               								<option value="active">Active</option>
	                               								<option value="inactive">Inactive</option>
	                               							</select>
	                               							@if ($errors->has('option_status'))
									                    	<p style="color: red;margin: 0;font-size: 10px;">{{ $errors->first('option_status') }}</p>
									                    	@endif
	                               						</td>
	                               					</tr>
	                               					<tr>
	                               						<td>Action</td>
	                               						<td>
	                               							<button type="submit" class="btn btn-success"><i class="fa fa-plus"></i> Add</button>
	                               						</td>
	                               					</tr>
	                               		    </table>
                               		   	</form>

                               		</div>
                               		<div class="col-md-12">
                               			<div class="table-responsive">
                               				
                               			
                               			<h4>Payment Options</h4>
                               			 <table class="table table-bordered">
                               		    	<tr>
                               		    		<th style="min-width: 300px;width: 300px;">Name</th>
                               		    		<th style="min-width: 400px;width: 400px;">Description</th>
                               		    		<th style="min-width: 100px;width: 100px;">Status</th>
                               		    		<th style="min-width: 120px;width: 120px;">Action</th>
                               		    	</tr>
                               		    	@foreach($method->methodOptions as $option)
                               		    	<tr>
                               		    		<td>{{$option->name}}</td>
                               		    		<td>{!!$option->description!!}</td>
                               		    		<td>
                               		    			@if($option->status=='active')
                               		    			<span class="badge badge-success">{{ucfirst($option->status)}}</span>
                               		    			@else
                               		    			<span class="badge badge-danger">{{ucfirst($option->status)}}</span>
                               		    			@endif
                               		    			
                               		    		</td>
                               		    		<td>
                               		    			<a href="javascript:void(0)" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#option{{$option->id}}">Edit</a>
                               		    			<a href="{{route('admin.ecommerceSettingEdit',['type'=>'paymentoptionDelete','id'=>$option->id])}}" class="btn btn-sm btn-danger" onclick="return confirm('Are You Want To Delete?')"><i class="fa fa-trash"></i></a>

                               		    			 <!-- Modal -->
													 <div class="modal fade text-left" id="option{{$option->id}}"  >
													   <div class="modal-dialog" role="document">
													     <div class="modal-content">
													        <form action="{{route('admin.ecommerceSettingUpdate',['type'=>'paymentoptionUpdate','id'=>$option->id])}}" method="post">
						                               				@csrf
														       <div class="modal-header">
														         <h4 class="modal-title" id="myModalLabel1">Update Option</h4>
														         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
														           <span aria-hidden="true">&times; </span>
														         </button>
														       </div>
														       <div class="modal-body">
														            
														       
						                               			<table class="table table-borderless">
						                               					<tr>
						                               						<td>Option Name*</td>
						                               						<td>
						                               							<input type="text" name="update_option_name" class="form-control form-control-sm" value="{{$option->name}}" placeholder="Enter Option Name" required="">
						                               							@if ($errors->has('update_option_name'))
														                    	<p style="color: red;margin: 0;font-size: 10px;">{{ $errors->first('update_option_name') }}</p>
														                    	@endif
						                               						</td>
						                               					</tr>
						                               					<tr>
						                               						<td>Option Descriptioin</td>
						                               						<td>
						                               							<textarea type="text" name="update_option_description" rows="5" class="form-control form-control-sm" placeholder="Write Option Descriptioin">{!!$option->description!!}</textarea>
						                               							@if ($errors->has('update_option_description'))
														                    	<p style="color: red;margin: 0;font-size: 10px;">{{ $errors->first('update_option_description') }}</p>
														                    	@endif
						                               						</td>
						                               					</tr>
						                               					<tr>
						                               						<td>Status*</td>
						                               						<td>
						                               							<select class="form-control form-control-sm" name="update_option_status" required="">
						                               								<option value="">Select Status</option>
						                               								<option value="active" {{$option->status=='active'?'selected':''}}>Active</option>
						                               								<option value="inactive" {{$option->status=='inactive'?'selected':''}}>Inactive</option>
						                               							</select>
						                               							@if ($errors->has('update_option_status'))
														                    	<p style="color: red;margin: 0;font-size: 10px;">{{ $errors->first('update_option_status') }}</p>
														                    	@endif
						                               						</td>
						                               					</tr>
						                               		    </table>
						                               		   	


														       </div>
														       <div class="modal-footer">
														         <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close </button>
														         <button type="submit" class="btn btn-success"> Update</button>
														       </div>
													       </form>
													     </div>
													   </div>
													 </div>

                               		    		</td>
                               		    	</tr>
                               		    	@endforeach
                               		    </table>
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

<script>

          

</script>

@endpush
