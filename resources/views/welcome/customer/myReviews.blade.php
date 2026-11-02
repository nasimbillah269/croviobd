@extends(App\Models\General::first()->theme.'.layouts.app')
@section('title')
<title>My Review | {{App\Models\General::first()->title}} | {{App\Models\General::first()->subtitle}}</title>
@endsection
@section('SEO')
<meta name="description" content="{!!App\Models\General::latest()->first()->meta_dsc!!}">
<meta name="keywords" content="{{App\Models\General::latest()->first()->meta_key}}">
<meta property="og:title" content="{{App\Models\General::latest()->first()->name}}">
<meta property="og:description" content="{!!App\Models\General::latest()->first()->meta_dsc!!}">
<meta property="og:image" content="{{asset(general()->logo())}}">
<meta property="og:url" content="{{route('index')}}">
@endsection
@push('css')
@endpush
@section('contents')

<div class="userdashboard">
    <div class="container">
    <div class="row">
        <div class="col-lg-3 usersidebardiv">
            @include(App\Models\General::first()->theme.'.customer.includes.sidebar')
        </div>
        <div class="col-lg-9 usermainbody">

            @include(App\Models\General::first()->theme.'.alerts')

            <div class="cd-card">
                <div class="cd-card-head">
                    <div>
                        <h4>My Reviews</h4>
                        <small>{{ $reviews->total() }} {{ Str::plural('review', $reviews->total()) }} written</small>
                    </div>
                </div>

                @if($reviews->count())
                <div>
                    @foreach($reviews as $review)
                    <div class="cd-review">
                        @if($review->post)
                        <img src="{{asset($review->post->image())}}" alt="{{$review->post->name}}">
                        @endif
                        <div class="cd-review-body">
                            <div class="cd-review-top">
                                @if($review->post)
                                <a href="{{route('productView',$review->post->slug)}}">{{ Str::limit($review->post->name,60,'..') }}</a>
                                @endif
                                <span class="cd-review-date">{{ $review->created_at->format('d M Y, h:i A') }}</span>
                            </div>
                            @if($review->rating)
                            <div class="cd-stars">
                                @for($s=1;$s<=5;$s++)
                                <i class="fa fa-star {{ $s <= $review->rating ? '' : 'off' }}"></i>
                                @endfor
                            </div>
                            @endif
                            <div class="cd-review-text">{!!$review->content!!}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div style="margin-top:20px;">
                    {{ $reviews->links('pagination') }}
                </div>
                @else
                <div class="cd-empty">
                    <i class="fa-regular fa-star"></i>
                    <h5>No reviews yet</h5>
                    <p>After an order is completed, you can review its products from the order details page.</p>
                    <a href="{{route('customer.myOrders',['type'=>'delivered'])}}" class="btn btn-primary">View Completed Orders</a>
                </div>
                @endif
            </div>

        </div>
    </div>
    </div>
</div>
@endsection
@push('js')
@endpush
