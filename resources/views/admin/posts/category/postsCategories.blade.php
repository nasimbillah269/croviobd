@extends('admin.layouts.app')
@section('title')
<title>Post Categories - {{general()->title}} | {{general()->subtitle}}</title>
@endsection

@push('css')
<style type="text/css">

</style>
@endpush
@section('contents')


<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
     <h3 class="content-header-title mb-0">Categories List</h3>
     <div class="row breadcrumbs-top">
       <div class="breadcrumb-wrapper col-12">
         <ol class="breadcrumb">
           <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard </a>
           </li>
           <li class="breadcrumb-item active">Categories List</li>
         </ol>
       </div>
     </div>
   </div>
   <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
     <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
     		@isset(json_decode(Auth::user()->permission->permission, true)['postsCtg']['add'])
       	<a class="btn btn-outline-primary" href="{{route('admin.postsCategoriesCreate')}}">Add Category</a>
       	@endisset
       	<a class="btn btn-outline-primary reloadPage1" href="{{route('admin.postsCategories')}}">
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
										       	
										       	<form action="{{route('admin.postsCategories')}}">
												       		<div class="row">
												       			<div class="col-md-12 mb-0">
												       					<div class="input-group">
		                                 		<input type="text" name="search" value="{{$r->search?$r->search:''}}" placeholder="Category Name" class="form-control {{$errors->has('search')?'error':''}}">
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
									 	<h4 class="card-title">Categories List</h4>
								 	</div>
	                 <div class="card-content">
	                     <div class="card-body">
	                     	<form action="{{route('admin.postsCategories')}}">
	                     	<div class="row">
	                     			<div class="col-md-4">
	                     				<div class="input-group mb-1">
	                     					<select class="form-control form-control-sm rounded-0" name="action" required="">
	                     						<option value="">Select Action</option>
	                     						<option value="1">Category Active</option>
	                     						<option value="2">Category InActive</option>
	                     						<option value="3">Category Featured</option>
	                     						<option value="4">Category Unfeatured</option>
	                     						<option value="5">Category Deleted</option>
	                     					</select>
	                     					<button class="btn btn-sm btn-primary rounded-0" onclick="return confirm('Are You Want To Action?')">Action</button>
	                     				</div>
	                     			</div>
	                     		</div>
		                     <div class="table-responsive">

		                         <table class="table table-striped table-bordered table-hover" >
																    <thead>
																        <tr>
																            <th style="min-width: 60px;"><label style="cursor: pointer;margin-bottom: 0;">
							                              <input class="checkbox" type="checkbox" class="form-control" id="checkall">  All <span class="checkCounter"></span>
							                              </label></th>
																            <th style="min-width: 300px;">Category Name</th>
																            <th style="min-width: 200px;">Parent CTG</th>
																            <th style="max-width: 100px">Image</th>
																            <th  style="min-width: 160px;">Action</th>
																        </tr>
																    </thead>
																    <tbody>
																        @foreach($categories as $i=>$category)
																        <tr>
																            <td>
																            <input class="checkbox" type="checkbox" name="checkid[]" value="{{$category->id}}"><br>{{$i+1}}
																            </td>
																            <td>
																            <span>{{$category->name}}</span><br>
																            @if($category->status=='active')
																           <span><i class="fa fa-check" style="color: #1ab394;"></i></span>
																           @else
																           <span><i class="fa fa-times" style="color: #ed5565;"></i></span>
																           @endif
																           @if($category->fetured==true)
																           <span><i class="fa fa-star" style="color: #1ab394;"></i></span>
																           @endif
																           <span style="font-size: 10px;"><i class="fa fa-user" style="color: #1ab394;"></i>
																                {{$category->user?$category->user->name:'No Author'}}
																            </span>
																            </td>
																            <td>
																              @if($category->parent)
                                              <span>{{$category->parent->name}}</span>
                                              @else
                                              <span class="badge badge-primary">PARENT CTG</span>
                                              @endif
																            </td>
																            <td style="padding:5px;text-align: center;">
															            	<img src="{{asset($category->image())}}" style="max-width: 80px;max-height: 50px;">
															            </td>
																            <td class="center">
																            <a href="{{route('admin.postsCategoriesEdit',$category->id)}}" class="btn btn-sm btn-info">Edit</a>
																            
																            @isset(json_decode(Auth::user()->permission->permission, true)['postsCtg']['delete'])
																            <a href="#deleteModal{{$category->id}}" class="btn btn-sm btn-danger" data-toggle="modal">Delete</a>
																            <div class="modal fade" id="deleteModal{{$category->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
																                <div class="modal-dialog" role="document">
																                  <div class="modal-content">
																                    <div class="modal-header">
																                      <h5 class="modal-title" id="exampleModalLabel">Confermation</h5>
																                    </div>
																                    <div class="modal-body">
																                      Are Your Want To Delete
																                    </div>
																                    <div class="modal-footer">
																                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
																                      <a href="{{route('admin.postsCategoriesDelete',$category->id)}}" class="btn btn-primary">Yes</a>
																                    </div>
																                  </div>
																                </div>
																              </div>
																            @endisset
																            </td>
																        </tr>
																        @endforeach
																    </tbody>
																</table>
																{{$categories->links('pagination')}}
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



@endsection
@push('js')

@endpush