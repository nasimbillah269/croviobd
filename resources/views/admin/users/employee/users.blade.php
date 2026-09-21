@extends('admin.layouts.app')
@section('title')
<title>Employee - {{general()->title}} | {{general()->subtitle}}</title>
@endsection

@push('css')
<style type="text/css">
		/* Chrome, Safari, Edge, Opera */
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
      -webkit-appearance: none;
      margin: 0;
    }
    textarea:focus, input:focus{
    outline: none;
    }
    /* Firefox */
    input[type=number] {
      -moz-appearance: textfield;
    }
</style>
@endpush
@section('contents')


<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
     <h3 class="content-header-title mb-0">Employee </h3>
     <div class="row breadcrumbs-top">
       <div class="breadcrumb-wrapper col-12">
         <ol class="breadcrumb">
           <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard </a>
           </li>
           <li class="breadcrumb-item active">Employee</li>
         </ol>
       </div>
     </div>
   </div>
   <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
     <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
       @isset(json_decode(myrole()->permission, true)['employee']['add'])
       	<button class="btn btn-outline-primary" type="button" data-toggle="modal" data-target="#employeeAdd">
       		Add Employee 
       	</button>
        @endisset
       	<a class="btn btn-outline-primary" href="{{route('admin.employeeUser')}}">
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
												       	
												      	<form action="{{route('admin.employeeUser')}}">
												       		<div class="row">
												       			<div class="col-md-6 mb-1">
												       					<div class="input-group">
			                             		<input type="date" name="startDate" value="{{$r->startDate?Carbon\Carbon::parse($r->startDate)->format('Y-m-d') :''}}" class="form-control {{$errors->has('startDate')?'error':''}}">
			                             		<input type="date" value="{{$r->endDate?Carbon\Carbon::parse($r->endDate)->format('Y-m-d') :''}}" name="endDate" class="form-control {{$errors->has('endDate')?'error':''}}">
			                         			</div>
												       			</div>
												       			<div class="col-md-6 mb-1">
												       					<div class="input-group">
			                             		<input type="text" name="search" value="{{$r->search?$r->search:''}}" placeholder="Employee Name, Email, Mobile" class="form-control {{$errors->has('search')?'error':''}}">
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
									 	<h4 class="card-title">Employee List</h4>
								 	</div>
	                 <div class="card-content">
	                     <div class="card-body">
			                     <div class="table-responsive">

			                         <table class="table table-bordered table-striped">
			                             <thead>
			                                 <tr>
			                                     <th style="min-width: 60px;width:60px">SL</th>
			                                     <th style="min-width: 100px;width:100px">Image</th>
			                                     <th style="min-width: 250px;width:250px">Employee Name</th>
			                                     <th style="min-width: 150px;">Mobile</th>
			                                     <th style="min-width: 150px;">Designation</th>
			                                     <th style="min-width: 150px;">Status</th>
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
			                                     <td><a href="{{route('admin.employeeUserEdit',$user->id)}}" class="btn btn-sm btn-info">{{$user->name}}</a> 
			                                     </td>
			                                     <td>{{$user->mobile}}</td>
			                                     <td>{{$user->designation}}</td>
			                                     <td>
			                                     	@if($user->status)
			                                     	<span class="badge badge-success">Active </span>
			                                     	@else
			                                     	<span class="badge badge-danger">Inactive </span>
			                                     	@endif<br>
			                                     	<small><i class="fa fa-calendar"></i> {{$user->created_at->format('Y-m-d')}}</small>
			                                     </td>
			                                     <td style="padding: 8px 5px;text-align: center;">

									                <a href="{{route('admin.employeeUserEdit',$user->id)}}" class="btn btn-sm btn-info"> Edit </a>   
                                                    @isset(json_decode(myrole()->permission, true)['employee']['delete'])
									                 <a href="{{route('admin.employeeUserDelete',$user->id)}}" onclick="return confirm('Are You Want To Delete')" class="invoice-action-edit cursor-pointer danger">
									                   <i class="fa fa-trash"></i>
									                 </a>
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




 <!-- Modal -->
 <div class="modal fade text-left" id="employeeAdd" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
   <div class="modal-dialog" role="document">
	 <div class="modal-content">
	 	<form action="{{route('admin.employeeUser')}}" method="post">
	   		@csrf
	   <div class="modal-header">
		 <h4 class="modal-title" id="myModalLabel1">Add Employee</h4>
		 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		   <span aria-hidden="true">&times; </span>
		 </button>
	   </div>
	   <div class="modal-body">
	   		
	   		<div class="form-group">
	   				<label>Employee Name*</label>
	   				<input type="text" name="name" class="form-control" placeholder="Enter Employee Name" required="">
	   		</div>
	   		<div class="form-group">
	   				<label>Employee Mobile/Email*</label>
	   				<input type="text" name="mobile" class="form-control" placeholder="Enter Employee Mobile/Email" required="">
	   		</div>

	   </div>
	   <div class="modal-footer">
		 <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close </button>
		 <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> Add Employee</button>
	   </div>
	   </form>
	 </div>
   </div>
 </div>


@endsection
@push('js')

@endpush