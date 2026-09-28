@extends('admin.layouts.app') @section('title')
<title>Product Edit - {{general()->title}} | {{general()->subtitle}}</title>
@endsection @push('css')

<style type="text/css">
    .catagorydiv {
        max-height: 300px;
        overflow: auto;
    }
    .catagorydiv ul {
        padding-left: 20px;
    }
    .catagorydiv ul li {
        list-style: none;
    }
    .areaChargeTable tr td{
        padding:4px;
    }

    .areaChargeTable tr th{
        padding:4px;
    }

    .form-control.error {
        border-color: #cf8b8b;
        background: #fed1d1;
    }
    .table tbody+tbody {
        border-top: 0px solid #98A4B8;
    }

</style>
@endpush 

@section('contents')

<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title mb-0">Product Edit</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard </a></li>
                    <li class="breadcrumb-item active">Product Edit</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
        <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
            <a class="btn btn-outline-primary" href="{{route('admin.products')}}">BACK</a>
            @isset(json_decode(Auth::user()->permission->permission, true)['products']['add'])
			       <a class="btn btn-outline-primary" href="{{route('admin.productsCreate')}}">Add Product</a>
			      @endisset
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
        <form action="{{route('admin.productsUpdate',$product->id)}}" class="mainformDATA" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
                            <h4 class="card-title">Product Edit 
                            @if($product->slug)
                            <a href="{{route('productView',$product->slug?:'no-slug')}}" target="_blank">View</a>
                            @endif
                            </h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="name">Product Name </label>
                                    <input type="text" class="form-control {{$errors->has('name')?'error':''}}" name="name" placeholder="Enter Product Name" value="{{$product->name?:old('name')}}" required="" />
                                    @if ($errors->has('name'))
                                    <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('name') }}</p>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="short_description">Short Description </label>
                                    <textarea name="short_description" class="form-control {{$errors->has('short_description')?'error':''}}" placeholder="Enter Short Description">{!!$product->short_description!!}</textarea>
                                    @if ($errors->has('short_description'))
                                    <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('short_description') }}</p>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="description">Description </label>
                                    <textarea name="description" class="{{$errors->has('description')?'error':''}} summernote" placeholder="Enter Description">{!!$product->description!!}</textarea>
                                    @if ($errors->has('description'))
                                    <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('description') }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
                            <h4 class="card-title">Product Data</h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">



                                 <ul class="nav nav-tabs" role="tablist" style="border-bottom: none;">
                                     <li class="nav-item">
                                         <a class="nav-link active" id="base-tab1" data-toggle="tab" aria-controls="tab1" href="#tab1" role="tab" aria-selected="true"><i class="fas fa-cog"></i> General</a>
                                     </li>
                                     <li class="nav-item">
                                         <a class="nav-link" id="base-tab2" data-toggle="tab" aria-controls="tab2" href="#tab2" role="tab" aria-selected="false"><i class="fas fa-truck"></i> Shipping</a>
                                     </li>
                                     <li class="nav-item">
                                         <a class="nav-link" id="base-tab3" data-toggle="tab" aria-controls="tab3" href="#tab3" role="tab" aria-selected="false"><i class="fas fa-exchange-alt"></i> Attributes</a>
                                     </li>
                                     <li class="nav-item">
                                         <a class="nav-link" id="base-tab4" data-toggle="tab" aria-controls="tab4" href="#tab4" role="tab" aria-selected="false"><i class="fas fa-tags"></i> Others</a>
                                     </li>
                                 </ul>
                                 <div class="tab-content px-1 pt-1" style="border: 1px solid #ddd;">
                                     <div class="tab-pane active" id="tab1" role="tabpanel" aria-labelledby="base-tab1">
                                         @include('admin.products.includes.productsDataGeneral')
                                     </div>
                                     <div class="tab-pane" id="tab2" role="tabpanel" aria-labelledby="base-tab2">
                                         @include('admin.products.includes.productsDataShipping')
                                     </div>
                                     <div class="tab-pane" id="tab3" role="tabpanel" aria-labelledby="base-tab3">
                                         @include('admin.products.includes.productsDataAttributes')
                                     </div>
                                     <div class="tab-pane" id="tab4" role="tabpanel" aria-labelledby="base-tab4">
                                         @include('admin.products.includes.productsDataOthers')
                                     </div>
                                 </div>


                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
                            <h4 class="card-title">SEO Optimize</h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="seo_title">SEO Meta Title</label>
                                    <input type="text" class="form-control {{$errors->has('seo_title')?'error':''}}" name="seo_title" placeholder="Enter SEO Meta Title" value="{{$product->seo_title?:old('seo_title')}}" />
                                    @if ($errors->has('seo_title'))
                                    <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('seo_title') }}</p>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="seo_desc">SEO Meta Description </label>
                                    <textarea name="seo_desc" class="form-control {{$errors->has('seo_desc')?'error':''}}" placeholder="Enter SEO Meta Description">{!!$product->seo_desc!!}</textarea>
                                    @if ($errors->has('seo_desc'))
                                    <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('seo_desc') }}</p>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="seo_keyword">SEO Meta Keyword </label>
                                    <textarea name="seo_keyword" class="form-control {{$errors->has('seo_keyword')?'error':''}}" placeholder="Enter SEO Meta Keyword">{!!$product->seo_keyword!!}</textarea>
                                    @if ($errors->has('seo_keyword'))
                                    <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('seo_keyword') }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
                            <h4 class="card-title">Product Images</h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="image">Product Image</label>
                                    <input type="file" name="image" class="form-control {{$errors->has('image')?'error':''}}" />
                                    @if ($errors->has('image'))
                                    <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('image') }}</p>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <img src="{{asset($product->image())}}" style="max-width: 100px;" />
                                    @isset(json_decode(Auth::user()->permission->permission, true)['products']['add'])
                                    @if($product->imageFile)
                                    <a href="{{route('admin.mediesDelete',$product->imageFile->id)}}" class="mediaDelete" style="color: red;"><i class="fa fa-trash"></i></a>
                                    @endif
                                    @endisset
                                </div>
                                <div class="form-group">
                                    <label for="gallery_image">Product Gallery</label>
                                    <input type="file" name="gallery_image[]" class="form-control {{$errors->has('gallery_image')?'error':''}}"  multiple="" />
                                    @if ($errors->has('gallery_image'))
                                    <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('gallery_image') }}</p>
                                    @endif
                                </div>
                                <div class="row">
                                    @foreach($product->galleryFiles as $gallery)
                                    <div class="col-md-4 form-group">
                                    <img src="{{asset($gallery->file_url)}}" style="max-width: 60px;max-height: 60px" />

                                    @isset(json_decode(Auth::user()->permission->permission, true)['products']['add'])
                                    <a href="{{route('admin.mediesDelete',$gallery->id)}}" class="mediaDelete" style="color: red;"><i class="fa fa-trash"></i></a>
                                    @endisset
                                    </div>
                                    @endforeach
                                    
                                </div>
                                
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
                            <h4 class="card-title">Product Category</h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                @if ($errors->has('categoryid*'))
                                <p style="color: red; margin: 0; font-size: 10px;">The Category Must Be a Number</p>
                                @endif
                                <div class="catagorydiv">
                                    <ul>
                                        @foreach($categories as $ctg)
                                        <li>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" value="{{$ctg->id}}" id="category_{{$ctg->id}}" name="categoryid[]" @foreach($product->productCtgs as $postctg)
                                                {{$postctg->reff_id==$ctg->id?'checked':''}} @endforeach/>
                                                <label class="custom-control-label" for="category_{{$ctg->id}}">{{$ctg->name}}</label>
                                            </div>
                                            @if($ctg->subctgs->count() >0) @include('admin.products.includes.productEditSubctg',['subcategories' => $ctg->subctgs,'i'=>1]) @endif
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
                            <h4 class="card-title">Product Tags</h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                @if ($errors->has('tags*'))
                                <p style="color: red; margin: 0; font-size: 10px;">The Tags Must Be a Number</p>
                                @endif
                                <select data-placeholder="Select Tags..." name="tags[]" class="select2 form-control" multiple="multiple">
                                    @foreach($tags as $i=>$tag)
                                    <option value="{{$tag->id}}" @foreach($product->productTags as $producttag) {{$producttag->reff_id==$tag->id?'selected':''}} @endforeach>{{$tag->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
                            <h4 class="card-title">Product Brand</h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                @if ($errors->has('brand'))
                                <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('brand') }}</p>
                                @endif
                                <select name="brand" class="form-control">
                                    <option value="">Select Brands</option>
                                    @foreach($brands as $i=>$brand)
                                    <option value="{{$brand->id}}" {{$product->brand_id==$brand->id?'selected':''}} >{{$brand->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
                            <h4 class="card-title">Product Action</h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-6">
                                        <label for="status">Product Status</label>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="status" name="status" {{$product->status=='active'?'checked':''}}/>
                                            <label class="custom-control-label" for="status">Active</label>
                                        </div>
                                    </div>
                                    <div class="form-group col-6">
                                        <label for="fetured">Top Sale</label>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="fetured" name="fetured" {{$product->fetured?'checked':''}}/>
                                            <label class="custom-control-label" for="fetured">Active</label>
                                        </div>
                                    </div>
                                </div>
                                {{--<div class="row">
                                    <div class="form-group col-6">
                                        <label for="status">Product Type {{$product->itemType()}}</label>
                                        <select class="form-control form-control-sm" name="product_type" >
                                            <option value="">Select Type</option>
                                            <option value="1" {{$product->product_type==1?'selected':''}}>Frozen</option>
                                            <option value="2" {{$product->product_type==2?'selected':''}}>Dye</option>
                                        </select>
                                    </div>
                                </div>--}}
                                <div class="form-group">
                                    <label>Published Date</label>
                                    <input type="date" class="form-control form-control-sm" name="created_at" value="{{$product->created_at->format('Y-m-d')}}">
                                    @if ($errors->has('created_at'))
                                    <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('created_at') }}</p>
                                    @endif
                                </div>
                                @isset(json_decode(Auth::user()->permission->permission, true)['products']['add'])
                                <button type="submit" class="btn btn-primary mr-sm-1 mb-1 mb-sm-0">Save changes</button>
                                @endisset
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    </section>
    <!-- Basic Inputs end -->
</div>

@endsection @push('js')

<script>
    $(".summernote").summernote({
        placeholder: "Hello stand alone ui",
        tabsize: 2,
        height: 120,
        toolbar: [
            ["style", ["style"]],
            ["font", ["bold", "underline"]],
            ["color", ["color"]],
            ["para", ["ul", "ol", "paragraph"]],
            ["table", ["table"]],
            ["insert", ["link", "picture"]],
            ["view", ["fullscreen", "codeview"]],
        ],
    });


    $(document).ready(function(){


        $(document).on('click','.SubmitSingleProduct',function() {
            $('.SubmitSingleForm').submit();
        });

        $(document).on('change','.SubmitSingleProduct1',function() {
            $('.SubmitSingleForm').submit();
        });

        





        ///Product Price Jquery

            $(document).on('change keyup mouseup','.priceUpdate',function(){

                var Rprice =parseInt($('.regular_price').val());

                var Dtype =$('.discounttype').val();

                var discount =parseInt($('.discount').val());

                var final_price=0;

                if(Rprice < 1){
                  final_price =0;
                }

                if(Dtype=='percent'){

                 if(discount < 100){
                    final_price =Rprice - Rprice * discount /100 ;
                  }else{
                    final_price =Rprice;
                  }
                  
                }else{

                  if(Rprice > discount){
                    final_price =Rprice - discount;
                  }else{
                    final_price =Rprice;
                  }

                }
              
              $('.final_price').val(final_price);

            });

            

          ///Product Price Jquery

          ///Product Ajax Update Data Jquery
          $(document).on('change','.productDataAjaxUpdate',function(){

                var url =$(this).data('url');
                var data =$(this).val();

                $.ajax({
                  url:url,
                  dataType: 'json',
                  cache: false,
                  data:{data:data},
                   success : function(data){

                   },error: function () {
                      alert('error');
                    }
                });

          });
          ///Product Ajax Update Data Jquery

          ///Product Attribute Filters Jquery
          $(document).on('change','.attributesItemFilter',function(){
                var url =$(this).data('url');
                var attriID =$(this).val();

                $.ajax({
                  url:url,
                  dataType: 'json',
                  cache: false,
                  data:{attriID:attriID},
                   success : function(data){
                    $('.AttributeItems').empty().append(data.viewData);
                   },error: function () {
                      alert('error');
                    }
                });

          });

          ///Product Attribute Filters Jquery

          ///Product Attribute Add Jquery
          $(document).on('click','.attributesItemAdd',function(){
                var url =$(this).data('url');
                var attriID =$('.attributesItemAddId').val();

                if(attriID){

                    $.ajax({
                      url:url,
                      dataType: 'json',
                      cache: false,
                      data:{attriID:attriID},
                       success : function(data){
                        $('.AttributeItemsList').empty().append(data.viewData);
                       },error: function () {
                          alert('error');
                        }
                    });

                }else{
                   $('.attributesItemAddId').addClass('error'); 
                }
          
            });
          ///Product Attribute Add Jquery

          ///Product Attribute Update Jquery
          $(document).on('change','.attributesItemColor',function(){

                var url =$(this).data('url');
                var attriID =$(this).data('id');
                var attriValue =$(this).val();
 
                if(attriID && attriValue){

                    $.ajax({
                      url:url,
                      dataType: 'json',
                      cache: false,
                      data:{attriID:attriID,attriValue:attriValue},
                       success : function(data){
                        $('.AttributeItemsList').empty().append(data.viewData);
                       },error: function () {
                          alert('error');
                        }
                    });

                }

            });

          $(document).on('change','.attributesItemImage',function(){
            var url =$(this).data('url');
            var attriID =$(this).data('id');

            var file_data = $(this).prop('files')[0];
            var form_data = new FormData();

                form_data.append('attriValue', file_data);
                form_data.append('attriID', attriID);

                $.ajax({
                  url:url,
                  type:'POST',
                  dataType: 'json',
                  cache: false,
                  contentType: false,
                  processData: false,
                  data:form_data,
                   success : function(data){
                    $('.AttributeItemsList').empty().append(data.viewData);
                   },error: function () {
                      alert('error');
                    }
                });

            });

          

          ///Product Attribute Update Jquery


          ///Product Attribute Delete Jquery
          $(document).on('click','.attributesItemDelete',function(){
            var url =$(this).data('url');
            var attriID =$(this).data('id');

            if(confirm('Are You Want To Delete?') && attriID){

                $.ajax({
                  url:url,
                  dataType: 'json',
                  cache: false,
                  data:{attriID:attriID},
                   success : function(data){
                    $('.AttributeItemsList').empty().append(data.viewData);
                   },error: function () {
                      alert('error');
                    }
                });

            }

          
          });
          ///Product Attribute Delete Jquery



          ///Product Extra Attribute Add Jquery

          $(document).on('click','.extraAttributeTitle,.extraAttributeValue,.attributesItemAddId',function(){
            $('.extraAttributeTitle').removeClass('error');
            $('.extraAttributeValue').removeClass('error');
            $('.attributesItemAddId').removeClass('error');

          });


          $(document).on('click','.extraAttributeAdd',function(){

                var url =$(this).data('url');
                var title =$('.extraAttributeTitle').val();
                var value =$('.extraAttributeValue').val();

                if(title==''){
                    $('.extraAttributeTitle').addClass('error');
                }

                if(value==''){
                    $('.extraAttributeValue').addClass('error');
                }

                if(title && value){
                    
                    $.ajax({
                      url:url,
                      dataType: 'json',
                      cache: false,
                      data:{title:title,value:value},
                       success : function(data){
                        $('.extraAttributeList').empty().append(data.viewData);
                       },error: function () {
                          alert('error');
                        }
                    });

                }

          });
          ///Product Extra Attribute Add Jquery

          ///Product Extra Attribute Delete Jquery

          $(document).on('click','.extraAttributeDelete',function(){

                var url =$(this).data('url');
                var attriID =$(this).data('id');

                if(confirm('Are You Want To Delete?')){

                    $.ajax({
                      url:url,
                      dataType: 'json',
                      cache: false,
                      data:{attriID:attriID},
                       success : function(data){
                        $('.extraAttributeList').empty().append(data.viewData);
                       },error: function () {
                          alert('error');
                        }
                    });

                }

            });


          ///Product Extra Attribute Delete Jquery

          ///Product Extra Attribute Delete Jquery
          $(document).on('click','.priceVariationStatus',function(){

            var url =$(this).data('url');
    
            $.ajax({
              url:url,
              dataType: 'json',
              cache: false,
               success : function(data){
               $('.priceVariationDiv').empty().append(data.viewData);
               },error: function () {
                  alert('error');
                }
            });

          });
          
          ///Product Extra Attribute Delete Jquery

          ///Product Extra Attribute Delete Jquery
          $(document).on('change','.variationItemsValue',function(){
            if($(this).val()!=''){
              $(this).removeClass('error');
            }
          });

          $(document).on('click','.variationItemsAdd',function(){

            var url =$(this).data('url');
            var form =$('.mainformDATA');
            var data =form.serialize();
            var valid =false;

            //At least one item (e.g. only Color) is enough
            $('.variationItemsValue').each(function(){
              if($(this).val()!=''){
                valid =true;
              }
            });

            if(!valid){
              $('.variationItemsValue').addClass('error');
              return;
            }
            $('.variationItemsValue').removeClass('error');

            $.ajax({
              url:url,
              dataType: 'json',
              cache: false,
              data:data,
               success : function(data){
               $('.priceVariationDiv').empty().append(data.viewData);
               },error: function () {
                  alert('error');
                }
            });

          });

          ///Product Extra Attribute Delete Jquery

          ///Variation Sku Image Update Jquery
          $(document).on('change','.variationImageInput',function(){

            var url =$(this).data('url');
            var skuID =$(this).data('sku');
            var file_data =$(this).prop('files')[0];
            var form_data =new FormData();

            form_data.append('image', file_data);
            form_data.append('skuID', skuID);

            $.ajax({
              url:url,
              type:'POST',
              dataType: 'json',
              cache: false,
              contentType: false,
              processData: false,
              data:form_data,
               success : function(data){
               $('.priceVariationDiv').empty().append(data.viewData);
               },error: function () {
                  alert('error');
                }
            });

          });
          ///Variation Sku Image Update Jquery

          ///Variation Sku Color/Size Edit Jquery
          $(document).on('change','.variationEditSelect',function(){

            var row =$(this).closest('tr');
            var url =$(this).data('url');
            var skuID =$(this).data('sku');
            var items =[];

            row.find('.variationEditSelect').each(function(){
              items.push($(this).val());
            });

            $.ajax({
              url:url,
              dataType: 'json',
              cache: false,
              data:{skuID:skuID,variationItems:items},
               success : function(data){
               $('.priceVariationDiv').empty().append(data.viewData);
               },error: function () {
                  alert('error');
                }
            });

          });
          ///Variation Sku Color/Size Edit Jquery

          ///Variation Sku Price/Quantity Update Jquery
          $(document).on('change','.variationPriceInput, .variationQtyInput',function(){

            var row =$(this).closest('tr');
            var url =$(this).data('url');
            var skuID =$(this).data('sku');
            var price =row.find('.variationPriceInput').val();
            var quantity =row.find('.variationQtyInput').val();

            $.ajax({
              url:url,
              dataType: 'json',
              cache: false,
              data:{skuID:skuID,price:price,quantity:quantity},
               success : function(data){
               $('.priceVariationDiv').empty().append(data.viewData);
               },error: function () {
                  alert('error');
                }
            });

          });
          ///Variation Sku Price/Quantity Update Jquery

          ///Variation Sku Delete Jquery
          $(document).on('click','.variationItemsDelete',function(){

            var url =$(this).data('url');
            var skuID =$(this).data('id');

            if(confirm('Are You Want To Delete?') && skuID){

                $.ajax({
                  url:url,
                  dataType: 'json',
                  cache: false,
                  data:{skuID:skuID},
                   success : function(data){
                   $('.priceVariationDiv').empty().append(data.viewData);
                   },error: function () {
                      alert('error');
                    }
                });

            }

          });
          ///Variation Sku Delete Jquery


    });
</script>

@endpush
