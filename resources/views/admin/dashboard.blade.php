@extends('admin.layouts.app')
@section('title')
<title>Dashbaord - {{general()->title}} | {{general()->subtitle}}</title>
@endsection

@push('css')
<style type="text/css">

</style>
@endpush
@section('contents')
<div class="content-header row">
   </div>
   <div class="content-body">
    <!-- Grouped multiple cards for statistics starts here -->
  <div class="row grouped-multiple-statistics-card">
     <div class="col-12">
       <div class="card">
         <div class="card-body">
           <div class="row">
             <div class="col-lg-6 col-xl-3 col-sm-6 col-12">
               <div class="d-flex align-items-start mb-sm-1 mb-xl-0 border-right-blue-grey border-right-lighten-5">
                 <span class="card-icon primary d-flex justify-content-center mr-3">
                   <i class="fas fa-stream customize-icon font-large-2 p-1"></i>
                 </span>
                 <div class="stats-amount mr-3">
                   <h3 class="heading-text text-bold-600">{{$reports['services']}}</h3>
                   <p class="sub-heading">Services </p>
                 </div>
                 <span class="inc-dec-percentage">
                   <small class="info"><i class="fa fa-arrow-up"></i> 30 Days</small>
                 </span>
               </div>
             </div>
             <div class="col-lg-6 col-xl-3 col-sm-6 col-12">
               <div class="d-flex align-items-start mb-sm-1 mb-xl-0 border-right-blue-grey border-right-lighten-5">
                 <span class="card-icon danger d-flex justify-content-center mr-3">
                   <i class="fas fa-copy customize-icon font-large-2 p-1"></i>
                 </span>
                 <div class="stats-amount mr-3">
                   <h3 class="heading-text text-bold-600">{{$reports['posts']}}</h3>
                   <p class="sub-heading">Posts</p>
                 </div>
                 <span class="inc-dec-percentage">
                   <small class="success"><i class="fa fa-arrow-up"></i> 30 Days </small>
                 </span>
               </div>
             </div>
             <div class="col-lg-6 col-xl-3 col-sm-6 col-12">
               <div class="d-flex align-items-start border-right-blue-grey border-right-lighten-5">
                 <span class="card-icon success d-flex justify-content-center mr-3">
                   <i class="fas fa-file-alt customize-icon font-large-2 p-1"></i>
                 </span>
                 <div class="stats-amount mr-3">
                   <h3 class="heading-text text-bold-600">{{$reports['pages']}}</h3>
                   <p class="sub-heading">Pages </p>
                 </div>
                 <span class="inc-dec-percentage">
                   <small class="primary"><i class="fa fa-arrow-up"></i> Total </small>
                 </span>
               </div>
             </div>
             <div class="col-lg-6 col-xl-3 col-sm-6 col-12">
               <div class="d-flex align-items-start">
                 <span class="card-icon warning d-flex justify-content-center mr-3">
                   <i class="fa fa-chess-rook customize-icon font-large-2 p-1"></i>
                 </span>
                 <div class="stats-amount mr-3">
                   <h3 class="heading-text text-bold-600">{{$reports['brands']}}</h3>
                   <p class="sub-heading">Brands </p>
                 </div>
                 <span class="inc-dec-percentage">
                   <small class="success"><i class="fa fa-arrow-up"></i> Total</small>
                 </span>
               </div>
             </div>
           </div>
         </div>
       </div>
     </div>
   </div>
   <!-- Grouped multiple cards for statistics ends here -->



   <!-- active users and my task timeline cards starts here -->
   <div class="row match-height">
       <!-- active users card -->
       <div class="col-xl-12 col-lg-12">
       <div class="card active-users">
         <div class="card-header border-0">
           <h4 class="card-title">Latest Products</h4>
         </div>
         <div class="card-content">
          {{--
          <div>{!! DNS1D::getBarcodeHTML('4445645656', 'C39',3,33,'green', true) !!}</div><br />
          <div>{!! DNS1D::getBarcodeHTML('4445645656', 'POSTNET') !!}</div><br />
                <div>{!! DNS1D::getBarcodeHTML('4445645656', 'PHARMA') !!}</div><br />
                <div>{!! DNS2D::getBarcodeHTML('4445645656', 'QRCODE') !!}</div><br /><hr><br>

               <div> {!! DNS1D::getBarcodeHTML('Md Rabiu Karim', 'C128',5,250, 'green', true) !!} </div><br>
            --}}
           <div id="audience-list-scroll" class="table-responsive position-relative">

             <table class="table table-striped table-bordered table-hover">
                  <thead>
                      <tr>
                          <th style="min-width: 60px;">SL</th>
                          <th style="min-width: 350px;">Product Name</th>
                          <th style="min-width: 80px;">Image</th>
                          <th style="min-width: 200px;">Catagory</th>
                          <th style="min-width: 80px;">Status</th>
                          <th style="min-width: 160px;">Action/Author</th>
                      </tr>
                  </thead>
                  <tbody>
                      
                      @foreach($products as $i=>$product)
                      <tr>
                          <td>
                              {{$products->currentpage()==1?$i+1:$i+($products->perpage()*($products->currentpage() - 1))+1}}
                          </td>
                          <td>
                              <span><a href="{{route('productView',$product->slug?:'no-slug')}}" target="_blank">{{$product->name}}</a></span>
                              <br/>
                              <span style="color: #ccc;"><b style="color: #1ab394;">{{general()->currency}}</b> {{priceFormat($product->final_price)}}</span>

                              @if($product->fetured==true)
                              <span><i class="fa fa-star" style="color: #1ab394;"></i></span>
                              @endif

                              @if($product->brand)
                              <span style="color: #ccc;"><b style="color: #1ab394;">Brand:</b> {{$product->brand->name}}</span>
                              @endif

                              @if($product->fetured==true)
                              <span style="color: #ccc;"><b style="color: #1ab394;">Sup:</b> Sukd skdj sd s</span>
                              @endif
                              <span style="color: #ccc;"><i class="fa fa-calendar" style="color: #1ab394;"></i> {{$product->created_at->format('d-m-Y')}}</span>
                          </td>
                          <td style="padding: 5px; text-align: center;">
                              <img src="{{asset($product->image())}}" style="max-width: 70px; max-height: 50px;" />
                          </td>
                          <td>
                              @foreach($product->productCategories as $i=>$ctg)

                               {{$i==0?'':'-'}} {{$ctg->name}} 

                               @endforeach
                          </td>
                          <td>
                              @if($product->status=='active')
                              <span class="badge badge-success">Active </span>
                              @elseif($product->status=='inactive')
                              <span class="badge badge-danger">Inactive </span>
                              @else
                              <span class="badge badge-danger">Draft </span>
                              @endif 
                          </td>
                          <td style="padding: 5px;">
                              <a href="{{route('admin.productsEdit',$product->id)}}" class="btn btn-sm btn-info">Edit</a>
                              <a href="{{route('admin.productsView',$product->id)}}" class="btn btn-sm btn-success">View</a>
                              @if(myRole())
                              @isset(json_decode(myRole()->permission, true)['products']['delete'])
                              <a href="{{route('admin.productsDelete',$product->id)}}" class="btn btn-sm btn-danger" onclick="return confirm('Are You Want To Delete?')">Delete</a>
                              @endisset
                              @endif
                              <br />
                              <span style="color: #ccc;">
                                  <i class="fa fa-user" style="color: #1ab394;"></i>
                                  {{Str::limit($product->user?$product->user->name:'No Author',15)}}
                              </span>
                          </td>
                      </tr>
                      @endforeach
                  </tbody>
              </table>

           </div>
         </div>
       </div>
     </div>

   </div>
</div>
@endsection
@push('js')

@endpush