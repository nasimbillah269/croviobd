@extends(App\Models\General::first()->theme.'.layouts.app')
@section('title')
<title>My Balance | {{App\Models\General::first()->title}} | {{App\Models\General::first()->subtitle}}</title>
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
                <div class="myrecentorder">
                    <p style="font-weight: bold;border-bottom: 1px solid #eaeded;padding: 5px 0;">Money Bag Balance</p>
                    <div class="row" style="margin:0;">
                        <div class="col-md-6" style="padding:10px;">
                            <div class="mybalanetotal">
                                <p>My Balance Total (BDT) </p>
                                <span>{{ Auth::user()->balance ?: 0.00 }}</span>
                                <!--<h6>Last Added <span style="font-size:18px;">125.00</span></h6>-->
                            </div>
                        </div>
                        <div class="col-md-6" style="padding:10px;">
                            <div class="mybalanetotal">
                                <p>Add Balance</p>

                                <form method="post" action="{{ route('customer.addBalanceToWallet') }}">
            @csrf

            

            <h5>Amount (BDT)</h5>
            <div class="input-group input-group-sm mb-3">
  <input type="number" required class="form-control" name="amount" placeholder="e.g: 5000">
  <div class="input-group-append">
    <button class="btn btn-primary" type="submit">Add Balance</button>

  </div>




</div>

            
            
           
          </form>
                            </div>
                        </div>

</div>


<div class="row">

                        <div class="col-md-12" style="padding:10px;">

@include(App\Models\General::first()->theme.'.alerts')


                            <div class="alltrasection">
                                <p style="font-weight: bold;border-bottom: 1px solid #eaeded;padding: 5px 0;">All Transaction</p>
                    <div style="overflow: auto;">
                                <table class="table">
                                    <tr>
                                        <th>Date</th>
                                        <th>Method</th>
                                        <th>Previous Balance</th>
                                        <th>Transfered Balance</th>
                                        <th>New Balance</th>
                                        <th>Status</th>
                                    </tr>


                                </table>

   
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>
@endsection
@push('js')
@endpush