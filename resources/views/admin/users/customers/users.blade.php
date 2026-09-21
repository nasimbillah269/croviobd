@extends('admin.layouts.app')
@section('title')
<title>Customer Users - {{general()->title}} | {{general()->subtitle}}</title>
@endsection

@push('css')
<style type="text/css">

</style>
@endpush
@section('contents')


<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
     <h3 class="content-header-title mb-0">Customer Users</h3>
     <div class="row breadcrumbs-top">
       <div class="breadcrumb-wrapper col-12">
         <ol class="breadcrumb">
           <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard </a>
           </li>
           <li class="breadcrumb-item active">Customer Users</li>
         </ol>
       </div>
     </div>
   </div>
   <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
     <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
     	@isset(json_decode(Auth::user()->permission->permission, true)['users']['add'])
       	<a class="btn btn-outline-primary" href="{{route('admin.usersCustomerAdd')}}">Add User</a>
      @endisset
       	<a class="btn btn-outline-primary" href="{{route('admin.usersCustomer')}}">
       		<i class="fa-solid fa-rotate"></i>
       	</a>
     </div>
   </div>
</div>
 
	

 <div class="content-body"><!-- Basic Elements start -->
	 <section class="basic-elements">
	     <div class="row">
	         <div class="col-md-12">
	         		@include('admin.alerts')
	         		<div class="card">
	         				<div class="card-content">
	         						<div class="card-body">
	         							 <div id="accordion">
												    <div class="card-header collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo" id="headingTwo" style="background: #f5f7fa;padding: 10px;cursor: pointer;border: 1px solid #00b5b8;">
												          Search click Here..
												    </div>
												    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion" style="border: 1px solid #00b5b8;border-top: 0;">
												      <div class="card-body">
												       	
												      	<form action="{{route('admin.usersCustomer')}}">
												       		<div class="row">
												       			<div class="col-md-6 mb-1">
												       					<div class="input-group">
			                             		<input type="date" name="startDate" value="{{$r->startDate?Carbon\Carbon::parse($r->startDate)->format('Y-m-d') :''}}" class="form-control {{$errors->has('startDate')?'error':''}}">
			                             		<input type="date" value="{{$r->endDate?Carbon\Carbon::parse($r->endDate)->format('Y-m-d') :''}}" name="endDate" class="form-control {{$errors->has('endDate')?'error':''}}">
			                         			</div>
												       			</div>
												       			<div class="col-md-6 mb-1">
												       					<div class="input-group">
			                             		<input type="text" name="search" value="{{$r->search?$r->search:''}}" placeholder="User Name, Email, Mobile" class="form-control {{$errors->has('search')?'error':''}}">
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
	             	<div class="card-header " style="border-bottom: 1px solid #e3ebf3;">
									 	<h4 class="card-title">Customer users List</h4>
								 	</div>
	                 <div class="card-content">
	                     <div class="card-body">
			                     <div class="table-responsive">

			                         <table class="table table-bordered table-striped">
			                             <thead>
			                                 <tr>
			                                     <th style="min-width: 60px;width:60px">SL</th>
			                                     <th style="min-width: 100px;width:100px">Name</th>
			                                     <th style="min-width: 200px;width:200px">Name</th>
			                                     <th style="min-width: 150px;">Email</th>
			                                     <th style="min-width: 180px;">Status/Date</th>
			                                     <th style="min-width: 100px;width:100px">Action</th>
			                                 </tr>
			                             </thead>
			                             <tbody>
			                             		@foreach($users as $i=>$user)
			                                 <tr>
			                                     <td>{{$i+1}}</td>
			                                     <td style="padding:0 3px;text-align: center;">
			                                     	<span>
			                                     		<img src="{{asset($user->image())}}" style="max-width:60px;max-height: 50px;">
			                                     	</span>
			                                     </td>
			                                     <td><a href="{{route('admin.usersCustomerEdit',[$user->id,'profile'])}}" class="invoice-action-view mr-1">{{$user->name}}</a></td>
			                                     <td>{{$user->email}}</td>
			                                     <td>
			                                     	@if($user->status)
			                                     	<span class="badge badge-success">Active </span>
			                                     	@else
			                                     	<span class="badge badge-danger">Inactive </span>
			                                     	@endif
			                                     	<small>({{$user->created_at->format('Y-m-d')}})</small>
			                                     </td>
			                                     <td style="padding: 8px 5px;text-align: center;">
															                 <a href="{{route('admin.usersCustomerEdit',[$user->id,'profile'])}}" class="invoice-action-view mr-1">
															                   <i class="fa fa-eye"></i>
															                 </a>

															                 @isset(json_decode(Auth::user()->permission->permission, true)['users']['delete'])
															                 @if($user->id==Auth::id()) @else
															                 <a href="{{route('admin.usersCustomerDelete',$user->id)}}" onclick="return confirm('Are You Want To Delete')" class="invoice-action-edit cursor-pointer danger">
															                   <i class="fa fa-trash"></i>
															                 </a> 
															                 @endif
															                 @endisset
															               
			                                     </td>
			                                 </tr>
			                                 @endforeach
			                             </tbody>
			                         </table>

			                        
			                     </div>
                                 {{$users->links('pagination')}}
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

@endpush