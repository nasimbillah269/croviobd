@extends('admin.layouts.app')
@section('title')
<title>Theme Setting - {{general()->title}} | {{general()->subtitle}}</title>
@endsection

@push('css')
<style type="text/css">
	.SearchContain{
	    position:relative;
	}
	.searchResultlist {
	    position: absolute;
	    top: 30px;
	    left:0;
	    width: 100%;
	    z-index:9;
	}

	.searchResultlist ul {
	    border: 1px solid #ccd6e6;
	    padding: 0;
	    margin: 0;
	    list-style: none;
	    background: white;
	}

	.searchResultlist ul li {
	    padding: 2px 10px;
	    cursor: pointer;
	    border-bottom: 1px dotted #dcdee0;
	}
	.searchResultlist ul li:last-child {
		border-bottom: 0px dotted #dcdee0;
	}
	
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
         <a class="btn btn-outline-primary" href="{{route('admin.themeSetting')}}">Back</a>
       	<a class="btn btn-outline-primary reloadPage1" href="{{route('admin.themeSettingEdit',$homedata->id)}}">
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
	                                 <h2>Data Manage</h2>
	                             </div>
	                         </div>

		                    <div class="table-responsive" style="min-height: 1000px;">
                                <form action="{{route('admin.themeSettingUpdate',$homedata->id)}}" method="post" enctype="multipart/form-data">
                                    @csrf
		                        <table class="table table-bordered">
		                             <tr>
		                                 <th style="width: 200px;min-width: 200px;">Title</th>
		                                 <td style="padding:1px;min-width: 400px;">
		                                     <input type="text" class="form-control form-control-sm" name="title" placeholder="Enter Title" value="{{$homedata->name}}">
		                                 </td>
		                             </tr>
		                             <tr>
		                                 <th>Title Color</th>
		                                 <td style="padding:1px;">
		                                     <input type="text" class="form-control form-control-sm" name="title_color" placeholder="Enter Color" value="{{$homedata->title_color}}">
		                                 </td>
		                             </tr>
		                             <tr>
		                                 <th>Backgroud Color</th>
		                                 <td style="padding:1px;">
		                                     <input type="text" class="form-control form-control-sm" name="bg_color" placeholder="Enter Color" value="{{$homedata->bg_color}}">
		                                 </td>
		                             </tr>
		                             <tr>
		                                 <th>Serial</th>
		                                 <td style="padding:1px;">
		                                     <input type="text" class="form-control form-control-sm" name="serial" placeholder="Enter Serial" value="{{$homedata->drag}}">
		                                 </td>
		                             </tr>
		                             <tr>
		                                 <th>Limit</th>
		                                 <td style="padding:1px;">
		                                     <input type="text" class="form-control form-control-sm" name="limit" placeholder="Enter Limit" value="{{$homedata->product_limit}}">
		                                 </td>
		                             </tr>
		                                <tr>
		                                 <th>Upload Banner</th>
		                                 <td style="padding:1px;">
		                                     <div class="input-group">
		                                        <input type="file" class="form-control form-control-sm" name="banner" accept="image/*" >
		                                        <div class="image">
		                                            <img src="{{asset($homedata->banner())}}" style="max-width: 150px;max-height: 40px;">
		                                        </div>
		                                        <input type="text" class="form-control form-control-sm"  placeholder="Enter Link" name="banner_link" value="{{$homedata->banner_link}}">
		                                        <label style="margin: 0;padding: 5px;"><input type="checkbox" class=""  placeholder="Enter Limit" name="banner_status" {{$homedata->banner_status?'checked':''}}> Active</label>
		                                     </div>
		                                 </td>
		                             </tr>
		                             <tr>
		                                 <th>Product Show</th>
		                                 <td style="padding:1px;">
		                                     <select class="form-control form-control-sm" name="product_view">
		                                         <option value="1" {{$homedata->product_view==1?'selected':''}}>Grid View</option>
		                                         <option value="2" {{$homedata->product_view==2?'selected':''}}>Slider View</option>
		                                     </select>
		                                 </td>
		                             </tr>
		                              <tr>
		                                 <th>Product Type</th>
		                                 <td style="padding:1px;">
		                                     <select class="form-control form-control-sm" name="product_type">
		                                         <option value="">Select Type</option>
		                                         <!--<option value="1" {{$homedata->product_type==1?'selected':''}}>Indivisual Product</option>-->
		                                         <!--<option value="2" {{$homedata->product_type==2?'selected':''}}>Category Product</option>-->
		                                         <!--<option value="3" {{$homedata->product_type==3?'selected':''}}>Brand Product</option>-->
		                                         <option value="4" {{$homedata->product_type==4?'selected':''}}>Latest Product</option>
		                                         <!--<option value="5" {{$homedata->product_type==5?'selected':''}}>oldest Product</option>-->
		                                         <!--<option value="6" {{$homedata->product_type==6?'selected':''}}>Fetured Product</option>-->
		                                         <option value="7" {{$homedata->product_type==7?'selected':''}}>Most Sale Product</option>
		                                         <option value="6" {{$homedata->product_type==6?'selected':''}}>Discount Product</option>
		                                         
		                                     </select>
		                                 </td>
		                             </tr>
		                             <tr>
		                                 <th>Status</th>
		                                 <td style="padding:1px;">
		                                     <select class="form-control form-control-sm" name="status">
		                                         <option value="active" {{$homedata->status=='active'?'selected':''}}>Active</option>
		                                         <option value="inactive" {{$homedata->status=='inactive'?'selected':''}}>Inactive</option>
		                                     </select>
		                                 </td>
		                             </tr>
		                             <tr>
		                                 <th>Product List</th>
		                                 <td style="padding:1px;">
		                                     <div class="form-group" style="margin: 0;">
		                                         <label style="margin: 0;"><b>Search Product</b></label>
		                                         <div class="SearchContain">
		                                         <input type="text" class="form-control form-control-sm serchProducts" Placeholder="Search Product here..">
		                                         <div class="searchResultlist">
        	                                    	@include('admin.purchase.includes.searchResult')
        	                                    </div>
        	                                    </div>
		                                     </div>
		                                     <hr>
		                                     <div class="HomeProducts">
		                                         @include('admin.theme-setting.includes.homeProducts')
		                                     </div>
		                                     
		                                 </td>
		                             </tr>
		                             <tr>
		                                 <th>Action</th>
		                                 <td style="padding:1px;">
		                                     <button type="submit" class="btn btn-success">Submit</button>
		                                 </td>
		                             </tr>
		                         </table>    
                                </form>
		                     </div>
		                     
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


<script>
    $(document).ready(function(){
        
        $(".searchResultlist").hide();

    	$(document).on('click', function(e) {
    
    	    var container = $(".SearchContain");
    	    var containerClose = $(".searchResultlist");
    	    
    	    if (!$(e.target).closest(container).length) {
    	        containerClose.hide();
    	    }else{
    	    	containerClose.show();
    	    }
    
    	});
        
        
        var url ="{{route('admin.themeSettingEdit',$homedata->id)}}";
        var key;
        var type;
        
        $(document).on('keyup','.serchProducts',function(){
            type ='search';
    		key =$(this).val();

    		$.ajax({
              url:url,
              dataType: 'json',
              cache: false,
              data: {'key':key,'type':type},
               success : function(data){
    
                $('.searchResultlist').empty().append(data.view);
    
               },error: function () {
                 // alert('error');
    
                }
            });
    
    	});
    	
    	$(document).on('click','.searchResultlist ul li',function(){
    		id =$(this).data('id');
    		type =$(this).data('type');
    		
    		$(".searchResultlist").hide();
    		
    		$.ajax({
              url:url,
              dataType: 'json',
              cache: false,
              data: {'key':key,'id':id,'type':type},
               success : function(data){
    
                $('.HomeProducts').empty().append(data.view);
    
               },error: function () {
                 // alert('error');
    
                }
            });
    		
    		
    	});
    	
        
    });
</script>

@endpush