@extends('admin.layouts.app')
@section('title')
<title>{{App\Models\General::first()->title}} | {{App\Models\General::first()->subtitle}}</title>
@endsection

@push('css')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">

<style type="text/css">

</style>
@endpush
@section('contents')


<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
     <h3 class="content-header-title mb-0">Attribute Edit</h3>
     <div class="row breadcrumbs-top">
       <div class="breadcrumb-wrapper col-12">
         <ol class="breadcrumb">
           <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard </a>
           </li>
           <li class="breadcrumb-item active">Attribute Edit</li>
         </ol>
       </div>
     </div>
   </div>
   <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
     <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
       	<a class="btn btn-outline-primary" href="{{route('admin.productsAttributes')}}">BACK</a>
       	<a class="btn btn-outline-primary" href="{{route('admin.productsAttributesEdit',$attribute->id)}}">
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
	        <div class="col-md-4">
	         	<form action="{{route('admin.productsAttributesUpdate',$attribute->id)}}" method="post" enctype="multipart/form-data">
	    		@csrf
	            <div class="card">
	             	<div class="card-header " style="border-bottom: 1px solid #e3ebf3;">
					 	<h4 class="card-title">Attribute Edit</h4>
				 	</div>
	                 <div class="card-content">
	                     <div class="card-body">
                        	<div class="form-group">
		                     	<label for="name">Attribute Name(*) </label>
		                     	<input type="text" class="form-control {{$errors->has('name')?'error':''}}" name="name" placeholder="Enter Attribute Name" value="{{$attribute->name?:old('name')}}" required="" />
		                    	@if ($errors->has('name'))
		                    	<p style="color: red;margin: 0;font-size: 10px;">{{ $errors->first('name') }}</p>
		                    	@endif
		             		</div>
		             		<div class="form-group">
							<label for="description">Description </label>
							<textarea name="description" class="form-control {{$errors->has('description')?'error':''}}" placeholder="Enter Description">{!!$attribute->description!!}</textarea>
							@if ($errors->has('description'))
							<p style="color: red;margin: 0;font-size: 10px;">{{ $errors->first('description') }}</p>
							@endif
		             		</div>
		             		<div class="form-group">
		             			<label>Attribute Type <small>(How to show Attribute)</small></label>
		             			<select class="form-control" name="type">
		             				<option value="1" {{$attribute->view==1?'selected':''}} >Text</option>
		             				<option value="2" {{$attribute->view==2?'selected':''}} >Color</option>
		             				<option value="3" {{$attribute->view==3?'selected':''}} >Image</option>
		             				
		             			</select>
		             		</div>
		             		<div class="form-group col-6">
                    			<label for="status">Attribute Status</label>
					               	<div class="custom-control custom-checkbox">
					                 <input type="checkbox" class="custom-control-input" id="status" name="status"  {{$attribute->status=='active'?'checked':''}}/>
					                 <label class="custom-control-label" for="status">Active</label>
					               </div>
	                        </div> 

	                        <button type="submit" class="btn btn-primary mr-sm-1 mb-1 mb-sm-0">Save
		                                  changes </button>

		                 </div>
		             </div>
		         	</div>
		         	</form>
				</div>


				<div class="col-md-8">
	         		
		            <div class="card">
		             	<div class="card-header " style="border-bottom: 1px solid #e3ebf3;">
						 	<h4 class="card-title">Attribute Items</h4>
					 	</div>
		                 <div class="card-content">
		                     <div class="card-body">
		                     	<div class="row">
		                     		<div class="col-md-4">
		                     			<a href="{{route('admin.productsAttributesItemAdd',$attribute->id)}}" class="btn btn-primary"><i class="fa fa-plus"></i> Add Item</a>
		                     		</div>
		                     		<div class="col-md-8">
		                     			<form action="{{route('admin.productsAttributesEdit',$attribute->id)}}">
		                     			<div class="input-group">
			                         		<input type="text" name="search" value="{{$r->search?$r->search:''}}" placeholder="Attribute Item Name" class="form-control {{$errors->has('search')?'error':''}}">
			                         		<button type="submit" class="btn btn-success rounded-0">Search</button>
			                 			</div>
			                 			</form>
		                     		</div>
		                     	</div>
		                     	
		                     	
		                     	
		                     	
		                     	<hr>
		                     	<form action="{{route('admin.productsAttributesEdit',$attribute->id)}}">
	                     		<div class="row">
	                     			<div class="col-md-4">
	                     				<div class="input-group mb-1">
	                     					<select class="form-control form-control-sm rounded-0" name="action" required="">
	                     						<option value="">Select Action</option>
	                     						<option value="5">Attributes Delete</option>
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
					                            </label>
					                        </th>
								            <th>Attributes Name</th>
								            
								            <th width="25%">Action</th>
								        </tr>
								    </thead>
								    <tbody>
								        @foreach($items as $i=>$item)
								        <tr>
								            <td>
								            <input class="checkbox" type="checkbox" name="checkid[]" value="{{$item->id}}">
								            {{$i+1}}
								            </td>
								            <td>
								            <span>
								                <a href="" target="_blank">{{$item->name}}</a></span>
								              @if($item->parent)
								              @if($item->parent->view==2)
								            	<span style="background:{{$item->icon}};height: 10px;width: 100px;display: inline-block;"></span>
								            	@elseif($item->parent->view==3)
								            	<img src="{{asset($item->parent->image())}}" style="max-width:50px;">
								            	@else
								            	@endif 
								            	@endif 
								               
								            </td>
								            <td class="center">
								            <a href="{{route('admin.productsAttributesItemEdit',$item->id)}}" class="btn btn-sm btn-info">Edit</a>  
								            </td>
								        </tr>
								        @endforeach
								    </tbody>
								</table>
								{{$items->links('pagination')}}
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