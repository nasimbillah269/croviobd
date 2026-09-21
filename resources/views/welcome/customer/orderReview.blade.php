@extends(App\Models\General::first()->theme.'.layouts.app')
@section('title')
<title>Order Review | {{App\Models\General::first()->title}} | {{App\Models\General::first()->subtitle}}</title>
@endsection
@section('SEO')
<meta name="description" content="{!!App\Models\General::latest()->first()->meta_dsc!!}">
<meta name="keywords" content="{{App\Models\General::latest()->first()->meta_key}}">
<meta property="og:title" content="{{App\Models\General::latest()->first()->name}}">
<meta property="og:description" content="{!!App\Models\General::latest()->first()->meta_dsc!!}">
<meta property="og:image" content="{!!App\Models\General::latest()->first()->meta_dsc!!}">
<meta property="og:url" content="{{route('index')}}">
@endsection
@push('css')

<style type="text/css">
span.reviewstar i {
    color: #ff9800;
}
span.reviewstar i.no {
    color: #9e9e9e;
}
</style>

@endpush
@section('contents')

<div class="userdashboard">
    <div class="container">
        <div class="row" style="margin:0;">
            <div class="col-lg-3 usersidebardiv">
                @include(App\Models\General::first()->theme.'.customer.includes.sidebar')
                
            </div>
            <div class="col-lg-9 usermainbody">
                
    
            	@include(App\Models\General::first()->theme.'.alerts')
            	
            	@if(session('selectError'))
                <div class="alert alert-danger alert-dismissable">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                    <strong>Oops! </strong> {{Session::get('selectError') }}.
                </div>
                @endif
    
                <div class="usercontent">
                    <div class="myrecentorder" style="min-height: 300px;">
                        <p style="font-weight: bold;border-bottom: 1px solid #eaeded;padding: 5px 0;">Orders Review 
                        <a class="btn btn-sm btn-info float-right" href="{{route('customer.orderDetails',$item->order_id)}}">Back Order</a></p>
                        <form action="{{route('customer.orderReviewPost',$item->id)}}" method="post">
                            @csrf
                        <table class="table table-bordered">
                            <tr>
                                <th colspan="2">Products</th>
                                <th>Review</th>
                            </tr>
                            <tr>
                                <td>
                                    @if($item->product)
                                        <img src="{{asset($item->product->fi())}}" style="max-width:50px;width:100%;">
                                    @endif
                                </td>
                                <td>
                                    <div><strong style="font-size: 12px;line-height: 14px;display: block;">{{ $item->product_name }}</strong></div>
                                <small>
                                    @if($item->color)
                                    Color: {{ $item->color }}, 
                                    @endif

                                    @if($item->size)
                                    Size: {{ $item->size }},
                                    @endif
                                    @if($item->status=='cancelled')
                                    <span class="badge badge-success" style="background-color: #f44336;">{{ucfirst($item->status)}}</span><br>
                                    @endif
                                </small>
                                </td>
                                <td>
                                     @if ($errors->has('star'))
                                          <p style="color: red;margin: 0;">{{ $errors->first('star') }}</p>
                                        @endif
                                    <select class="form-control" name="star">
                                        <option value="5" {{$item->review?$item->review->rating==5?'selected':'':'' }} >Five Star</option>
                                        <option value="4" {{$item->review?$item->review->rating==4?'selected':'':'' }} >Four Star</option>
                                        <option value="3" {{$item->review?$item->review->rating==3?'selected':'':'' }} >Three Star</option>
                                        <option value="2" {{$item->review?$item->review->rating==2?'selected':'' :''}} >Two Star</option>
                                        <option value="1" {{$item->review?$item->review->rating==1?'selected':'' :''}} >One Star</option>
                                    </select>
                                    
                                    <span class="reviewstar">
                                        @if($item->review)
                                        @if($item->review->rating==1)
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star no"></i>
                                        <i class="fa fa-star no"></i>
                                        <i class="fa fa-star no"></i>
                                        <i class="fa fa-star no"></i>
                                        @elseif($item->review->rating==2)
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star no"></i>
                                        <i class="fa fa-star no"></i>
                                        <i class="fa fa-star no"></i>
                                        @elseif($item->review->rating==3)
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star no"></i>
                                        <i class="fa fa-star no"></i>
                                        @elseif($item->review->rating==4)
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star no"></i>
                                        @else
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        @endif
                                        @else
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        @endif
                                    </span>
                                </td>
                            </tr>
  
                        </table>
                        
                        <div class="form-group">
                            <label>Write You Review</label>
                            @if ($errors->has('review'))
                              <p style="color: red;margin: 0;">{{ $errors->first('review') }}</p>
                            @endif
                            <textarea class="form-control" rows="5" name="review">{{$item->review?$item->review->comment:old('review')}}</textarea>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-success" type="submit">Submit</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
@endpush