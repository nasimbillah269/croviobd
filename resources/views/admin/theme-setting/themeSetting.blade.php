@extends('admin.layouts.app')
@section('title')
<title>Theme Setting - {{general()->title}} | {{general()->subtitle}}</title>
@endsection

@push('css')

<style type="text/css">
	.ProductGridSection {
    border: 1px solid gray;
    padding: 5px;
    text-align: center;
    }
	
	.ProductGrid {
    min-height: 120px;
    }
    
    .ProductGrid img {
    max-width: 100%;
    max-height: 115px;
    }
</style>

@endpush
@section('contents')


<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
     <h3 class="content-header-title mb-0">Theme Setting</h3>
     <div class="row breadcrumbs-top">
       <div class="breadcrumb-wrapper col-12">
         <ol class="breadcrumb">
           <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard </a>
           </li>
           <li class="breadcrumb-item active">Theme Setting</li>
         </ol>
       </div>
     </div>
   </div>
   <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
     <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
         <a class="btn btn-outline-primary" href="{{route('admin.themeSetting',['type'=>'add'])}}" onclick="return confirm('Are You Want to Add?')"><i class="fa fa-plus"></i> Add</a>
       	<a class="btn btn-outline-primary reloadPage1" href="{{route('admin.themeSetting')}}">
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
	             	<div class="card-header " style="border-bottom: 1px solid #e3ebf3;">
						<h4 class="card-title">theme Setting</h4>
					</div>
	                 <div class="card-content">
	                     <div class="card-body">
	                         <div class="row">
	                             <div class="col-md-8">
	                                 <h2>Home Page Product Manage</h2>
	                             </div>
	                         </div>

		                     @foreach($homeDatas as $homedata)
		                        
		                        <div class="table-responsive">

		                        <table class="table table-bordered">
		                             <tr>
		                                 <th style="width: 200px;min-width: 200px;">Title</th>
		                                 <td style="min-width: 400px;">
		                                     {{$homedata->name}}
		                                 </td>
		                             </tr>
		                             <tr>
		                                 <th>Title Color</th>
		                                 <td style="">
		                                     {{$homedata->title_color}}
		                                 </td>
		                             </tr>
		                             <tr>
		                                 <th>Backgroud Color</th>
		                                 <td style="">
		                                     {{$homedata->bg_color}}
		                                 </td>
		                             </tr>
		                             <tr>
		                                 <th>Serial</th>
		                                 <td style="">
		                                     {{$homedata->drag}}
		                                 </td>
		                             </tr>
		                             <tr>
		                                 <th>Limit</th>
		                                 <td style="">
		                                     {{$homedata->product_limit}}
		                                 </td>
		                             </tr>
		                             <tr>
		                                 <th>Product Show</th>
		                                 <td style="">
		                                     
		                                     @if($homedata->product_view==1)
		                                     <span>Grid View</span>
		                                     @else
		                                     <span>Slider View</span>
		                                     @endif
		                                     
		                                 </td>
		                             </tr>
		                              <tr>
		                                 <th>Product Type</th>
		                                 <td style="">
		                                     @if($homedata->product_type==1)
		                                     <span>Indivisual Product</span>
		                                     @elseif($homedata->product_type==2)
		                                     <span>Category Product</span>
		                                     @elseif($homedata->product_type==3)
		                                     <span>Brand Product</span>
		                                     @elseif($homedata->product_type==4)
		                                     <span>Latest Product</span>
		                                     @elseif($homedata->product_type==5)
		                                     <span>oldest Product</span>
		                                     @elseif($homedata->product_type==6)
		                                     <span>Fetured Product</span>
		                                     @elseif($homedata->product_type==7)
		                                     <span>Most Sale Product</span>
		                                     @endif
		                                     
		                                 </td>
		                             </tr>
		                             <tr>
		                                 <th>Status</th>
		                                 <td style="">
		                                     {{ucfirst($homedata->status)}}
		                                 </td>
		                             </tr>
		                             <tr>
		                                 <th>Product List</th>
		                                 <td style="padding:1px;">
		                                     
		                                     <div class="row" style="margin: 0;">
    
                                                    @foreach($homedata->products() as $product1)
                                                     <div class="col-md-2 col-6" style="padding:3px;">
                                                         <div class="ProductGridSection">
                                                             <div class="ProductGrid">
                                                                 <img src="{{asset($product1->image())}}" >
                                                             </div>
                                                         </div>
                                                     </div>
                                                     @endforeach
                                                </div>

		                                 </td>
		                             </tr>
		                             <tr>
		                                 <th>Action</th>
		                                 <td style="padding:1px;">
		                                     <a href="{{route('admin.themeSettingEdit',$homedata->id)}}" class="btn btn-success"><i class="fa fa-edit"></i> Manage</a>
		                                     <a href="{{route('admin.themeSettingEdit',[$homedata->id,'type'=>'delete'])}}" class="btn btn-sm btn-danger float-right" onclick="return confirm('Are You Want to Delete?')"><i class="fa fa-trash"></i> Delete</a>
		                                 </td>
		                             </tr>
		                         </table>    
		                     </div>
		                     <hr>

		                     @endforeach
		                     
		                     
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