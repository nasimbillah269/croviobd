@extends(App\Models\General::first()->theme.'.layouts.app')
@section('title')
<title>My Review | {{App\Models\General::first()->title}} | {{App\Models\General::first()->subtitle}}</title>
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
    .myrecentorder tr td,th{
        padding:5px !important;
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
            <div class="usercontent">
                <div class="myrecentorder" style="min-height: 300px;">
                    <p style="font-weight: bold;border-bottom: 1px solid #eaeded;padding: 5px 0;">My Reviews</p>
                    <div  class="table-responsive">
                    <table class="table table-bordered">

                    <tr style="background: #f3f3f3;">
                        <th style="width: 50px;min-width: 50px;">SL</th>
                        <th colspan="2" style="min-width: 300px;">Product</th>
                        <th style="width: 170px;min-width: 170px;">date</th>
                    </tr>

                    @foreach($reviews as $i=>$review)

                     <tr>
                        <td rowspan="2" style="text-align: center;">{{$reviews->currentpage()==1?$i+1:$i+($reviews->perpage()*($reviews->currentpage() - 1))+1}}</td>
                        <td>
                            <img width="30" class="rounded p-0 m-0" src="{{asset($review->post->image())}}" alt="{{$review->post->name}}">
                          </td>
                          <td><a href="{{route('productView',$review->post->slug)}}">{{ Str::limit($review->post->name,35,'..') }}</a></td>
                          <td>
                              {{ $review->created_at->format('d-m-Y h:i A') }}
                          </td>
                    </tr>
                    <tr style="background: #f3f3f3;">
                        <td colspan="3">{!!$review->content!!}</td>
                    </tr>

                    @endforeach
                    </table>
                    </div>
                    {{ $reviews->links('pagination') }}
                </div>
                
            </div>
        </div>
    </div>
    </div>
</div>
@endsection
@push('js')
@endpush